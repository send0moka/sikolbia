<?php

namespace App\Services;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportExportService
{
	public function __construct(private ReportService $reportService) {}

	/**
	 * Contract
	 * Inputs:
	 *  - moduleType: 'lahan' | 'benih-pupuk' | 'iklim-opt-dpi'
	 *  - selections: same shape as PertanianReportController@filter
	 *  - config: { tata_letak, provinsi_ids, kabupaten_ids }
	 * Output: 
	 *  - Symfony\Component\HttpFoundation\BinaryFileResponse (Excel download)
	 */
	public function exportToExcel(string $moduleType, array $selections, array $config, ?string $filename = null)
	{
		$result = $this->reportService->generateReportData($moduleType, [
			'selections' => $selections,
			'config' => $config,
		]);

		$headers = $result['headers'] ?? [];
		$rows = $result['rows'] ?? [];

		[$array2D, $merges, $headerRows, $totalCols] = $this->buildSheetDataWithMerges($headers, $rows);

		$export = new class($array2D, $merges, $headerRows, $totalCols) implements FromArray, WithTitle, WithEvents {
			public function __construct(private array $data, private array $merges, private int $headerRows, private int $totalCols) {}
			public function array(): array { return $this->data; }
			public function title(): string { return 'Laporan'; }
			public function registerEvents(): array
			{
				return [
					AfterSheet::class => function(AfterSheet $event) {
						// Apply merges
						foreach ($this->merges as $m) {
							[$r1, $c1, $r2, $c2] = $m; // 1-based indexes
							$start = Coordinate::stringFromColumnIndex($c1) . $r1;
							$end   = Coordinate::stringFromColumnIndex($c2) . $r2;
							if (!($r1 === $r2 && $c1 === $c2)) {
								$event->sheet->getDelegate()->mergeCells("{$start}:{$end}");
							}
						}
						// Style header rows: center alignment and bold
						if ($this->headerRows > 0 && $this->totalCols > 0) {
							$headerRange = 'A1:' . Coordinate::stringFromColumnIndex($this->totalCols) . $this->headerRows;
							$style = $event->sheet->getDelegate()->getStyle($headerRange);
							$style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$style->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
							$style->getFont()->setBold(true);

							// Freeze panes below header and after first column (A)
							$freezeCell = 'B' . ($this->headerRows + 1);
							$event->sheet->getDelegate()->freezePane($freezeCell);
						}

						// Auto-size all columns based on content (Wilayah + dynamic headers)
						for ($c = 1; $c <= max(1, $this->totalCols); $c++) {
							$event->sheet->getDelegate()->getColumnDimensionByColumn($c)->setAutoSize(true);
						}
					}
				];
			}
		};

		$name = $filename ?? ('laporan-pertanian-' . now()->format('Ymd-His') . '.xlsx');
		return Excel::download($export, $name);
	}

	/**
	 * Build a proper 2D grid for headers (respecting span/rowspan) and body, and return merge coordinates.
	 * @param array $headers Header rows: [[{name,span,rowspan},...], ...]
	 * @param array $rows Body rows: [{wilayah, values:[]}, ...]
	 * @return array{array<int,array<int,mixed>>, array<int,array{int,int,int,int}>}
	 */
	private function buildSheetDataWithMerges(array $headers, array $rows): array
	{
		$sheet = [];
		$mergeRanges = [];

		$headerRowCount = count($headers);
		if ($headerRowCount === 0) {
			// No header: dump values directly with wilayah first
			foreach ($rows as $r) { $sheet[] = array_merge([$r['wilayah'] ?? ''], $r['values'] ?? []); }
			// totalCols minimal: 1 (Wilayah) + values count if exists
			$totalCols = !empty($rows) ? (1 + count($rows[0]['values'] ?? [])) : 1;
			return [$sheet, $mergeRanges, 0, $totalCols];
		}

		// Total columns = sum of 'span' in first header row
		$totalCols = 0;
		foreach ($headers[0] as $cell) { $totalCols += max(1, (int)($cell['span'] ?? 1)); }

		// Place header grid using column occupancy tracker for rowspans
		// colTracker[c] = rows remaining occupied in this column (including current row)
		$colTracker = array_fill(0, $totalCols, 0);

		for ($r = 0; $r < $headerRowCount; $r++) {
			$rowValues = array_fill(0, $totalCols, '');
			$currentCol = 0; // 0-based index

			// Move currentCol to next free slot helper
			$advanceToNextFree = function() use (&$currentCol, &$colTracker, $totalCols) {
				while ($currentCol < $totalCols && $colTracker[$currentCol] > 0) { $currentCol++; }
			};

			foreach ($headers[$r] as $cell) {
				$name = (string)($cell['name'] ?? '');
				$span = max(1, (int)($cell['span'] ?? 1));
				$rowspan = max(1, (int)($cell['rowspan'] ?? 1));

				$advanceToNextFree();
				$startCol = $currentCol; // 0-based
				// Fill this cell's span with the name in the current row
				for ($c = 0; $c < $span; $c++) {
					if (($startCol + $c) < $totalCols) {
						$rowValues[$startCol + $c] = $name;
						// Mark occupancy for future rows due to rowspan
						$colTracker[$startCol + $c] = max($colTracker[$startCol + $c], $rowspan);
					}
				}
				$currentCol = $startCol + $span;

				// Record merge rectangle if needed (use 1-based rows/cols)
				$r1 = $r + 1; $c1 = $startCol + 1; $r2 = $r + $rowspan; $c2 = $startCol + $span;
				if ($rowspan > 1 || $span > 1) { $mergeRanges[] = [$r1, $c1, $r2, $c2]; }
			}

			$sheet[] = $rowValues;
			// Decrement occupancy for next row
			for ($i = 0; $i < $totalCols; $i++) { if ($colTracker[$i] > 0) $colTracker[$i]--; }
		}

		// Body rows start after headerRowCount
		foreach ($rows as $r) {
			$row = array_fill(0, $totalCols, '');
			$row[0] = $r['wilayah'] ?? '';
			$values = array_values($r['values'] ?? []);
			for ($i = 0; $i < min(count($values), $totalCols - 1); $i++) {
				$row[1 + $i] = $values[$i];
			}
			$sheet[] = $row;
		}

		return [$sheet, $mergeRanges, $headerRowCount, $totalCols];
	}
}

