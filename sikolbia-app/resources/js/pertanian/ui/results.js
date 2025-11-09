// Results & filtering controller for Unified Pertanian Reports
import api from '../api/pertanianApi.js';
import { formatTimestampId } from '../utils/format.js';

function buildPayload(ctx) {
  const isLahan = ctx.moduleType === 'lahan';
  const selections = (ctx.selections || []).map((s) => {
    const base = { variabel_id: s.variabel_id, klasifikasi_ids: s.klasifikasi_ids };
    if (isLahan) {
      return { ...base, tahuns: s.tahun_ids };
    } else {
      return { ...base, tahun_ids: s.tahun_ids, bulan_ids: s.bulan_ids || [] };
    }
  });
  const config = {
    tata_letak: ctx.selection?.tata_letak || 'tipe_1',
    provinsi_ids: ctx.wilayahLevel === 'nasional' ? (ctx.selection?.provinsi_ids || []) : [],
    kabupaten_ids: ctx.wilayahLevel === 'provinsi' ? (ctx.selection?.kabupaten_ids || []) : [],
  };
  return { selections, config };
}

function addStoredResult(ctx, payload, results) {
  const resultIndex = (ctx.storedResults?.length || 0) + 1;
  const storedResult = {
    id: Date.now(),
    title: `Hasil ${resultIndex}`,
    timestamp: formatTimestampId(new Date()),
    results,
    config: { ...payload.config },
    selections: (ctx.selections || []).map((s) => ({ ...s })),
    exportSelections: payload.selections,
    exportConfig: payload.config,
  };
  ctx.storedResults = ctx.storedResults || [];
  ctx.storedResults.push(storedResult);
  ctx.selectedResultIndex = ctx.storedResults.length - 1;
}

export async function fetchData(ctx) {
  if (!Array.isArray(ctx.selections) || ctx.selections.length === 0) {
    alert('Silakan tambahkan data terlebih dahulu.');
    return;
  }
  ctx.isProcessing = true;
  try {
    const payload = buildPayload(ctx);
    const results = await api.filterReport(ctx.moduleType, payload);
    // Expecting results = { headers: HeaderRow[], rows: Row[], config: {...} }
    addStoredResult(ctx, payload, results);
  } catch (error) {
    const msg = String(error && error.message ? error.message : 'Terjadi kesalahan tak dikenal');
    let friendly = msg;
    if (/Page Expired|CSRF|token|<!DOCTYPE/i.test(msg)) {
      friendly = 'Sesi/CSRF kedaluwarsa. Muat ulang halaman lalu coba lagi.';
    }
    alert('Terjadi kesalahan saat mengambil data: ' + friendly);
  } finally {
    ctx.isProcessing = false;
  }
}

export function saveWizardResult(ctx, chat) {
  const payload = chat?.payload; if (!payload) return;
  const results = chat?.results || chat?.payload?.results || { headers: [], rows: [] };
  // Reuse the same storage shape as fetchData path
  addStoredResult(ctx, payload, results);
  ctx.conversation.push({ sender:'bot', type:'text', text: 'Hasil disimpan ke panel. Anda dapat membuka tab Tabel/Grafik untuk melihat lebih lengkap.' });
}

export function computeDynamicRows(ctx) {
  const currentResult = ctx.selectedResultIndex !== null ? ctx.storedResults[ctx.selectedResultIndex] : null;
  const rows = currentResult?.results?.rows || [];
  const sorted = [...rows];
  sorted.sort((a, b) => {
    const aHas = a.wilayah_sorter !== undefined && a.wilayah_sorter !== null;
    const bHas = b.wilayah_sorter !== undefined && b.wilayah_sorter !== null;
    if (aHas && bHas) {
      if (a.wilayah_sorter !== b.wilayah_sorter) return a.wilayah_sorter - b.wilayah_sorter;
      return String(a.wilayah).localeCompare(String(b.wilayah));
    }
    if (aHas && !bHas) return -1;
    if (!aHas && bHas) return 1;
    return String(a.wilayah).localeCompare(String(b.wilayah));
  });
  return sorted;
}

export function selectStoredResult(ctx, index) {
  ctx.selectedResultIndex = index;
  if (ctx.activeResultTab === 'grafik') {
    ctx.$nextTick(() => ctx.renderChart && ctx.renderChart());
  }
}

export function toggleResultSelection(ctx, id, checked) {
  const nid = Number(id);
  ctx.selectedResultIds = ctx.selectedResultIds || [];
  if (checked) {
    if (!ctx.selectedResultIds.includes(nid)) ctx.selectedResultIds.push(nid);
  } else {
    ctx.selectedResultIds = ctx.selectedResultIds.filter((x) => x !== nid);
  }
}

export function removeSelectedResults(ctx) {
  if (!ctx.selectedResultIds || ctx.selectedResultIds.length === 0) return;
  const ids = new Set(ctx.selectedResultIds.map(Number));
  const prevSelected = (ctx.selectedResultIndex !== null && ctx.storedResults?.[ctx.selectedResultIndex])
    ? ctx.storedResults[ctx.selectedResultIndex].id
    : null;
  ctx.storedResults = (ctx.storedResults || []).filter((r) => !ids.has(Number(r.id)));
  ctx.selectedResultIds = [];

  if (ctx.storedResults.length === 0) {
    ctx.selectedResultIndex = null;
    if (window.myChart && typeof window.myChart.destroy === 'function') window.myChart.destroy();
    return;
  }

  let newIndex = null;
  if (prevSelected != null) newIndex = ctx.storedResults.findIndex((r) => Number(r.id) === Number(prevSelected));
  if (newIndex === -1 || newIndex === null) newIndex = Math.min(ctx.selectedResultIndex ?? 0, ctx.storedResults.length - 1);
  selectStoredResult(ctx, newIndex);
}

export function clearAllResults(ctx) {
  if (!ctx.storedResults || ctx.storedResults.length === 0) return;
  ctx.showClearConfirm = true;
}

export function clearAllResultsConfirmed(ctx) {
  ctx.storedResults = [];
  ctx.selectedResultIds = [];
  ctx.selectedResultIndex = null;
  ctx.showClearConfirm = false;
  if (window.myChart && typeof window.myChart.destroy === 'function') window.myChart.destroy();
}

export default {
  fetchData,
  saveWizardResult,
  selectStoredResult,
  toggleResultSelection,
  removeSelectedResults,
  clearAllResults,
  clearAllResultsConfirmed,
  computeDynamicRows,
};
