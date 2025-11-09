// Chart rendering and interactions for results tab
// Depends on global Chart from CDN as present in Blade templates

export function renderChart(ctx) {
  const canvas = document.getElementById('dynamicChart');
  if (!canvas) return;
  const canRender = ctx.dynamicRows && ctx.dynamicRows.length > 0;
  if (window.myChart) { try { window.myChart.destroy(); } catch {} }
  if (!canRender) return;

  const labels = ctx.dynamicRows.map((row) => row.wilayah);
  const datasets = [];
  const colors = [
    'rgba(54, 162, 235, 0.8)', 'rgba(255, 99, 132, 0.8)', 'rgba(75, 192, 192, 0.8)',
    'rgba(255, 206, 86, 0.8)', 'rgba(153, 102, 255, 0.8)', 'rgba(255, 159, 64, 0.8)',
    'rgba(201, 203, 207, 0.8)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 99, 132, 0.6)'
  ];

  const columnLabels = [];
  if (ctx.dynamicHeaders && ctx.dynamicHeaders.length > 0) {
    const lastHeaderRow = ctx.dynamicHeaders[ctx.dynamicHeaders.length - 1];
    lastHeaderRow.forEach((header) => { if (header.name !== 'Wilayah') columnLabels.push(header.name); });
  }

  if (labels.length > 0 && columnLabels.length > 0) {
    columnLabels.forEach((columnLabel, index) => {
      const data = ctx.dynamicRows.map((row) => {
        const value = row.values ? row.values[index] : null;
        return value !== null && value !== undefined ? parseFloat(value) || 0 : 0;
      });
      datasets.push({
        label: columnLabel,
        data,
        backgroundColor: colors[index % colors.length],
        borderColor: colors[index % colors.length].replace('0.8', '1'),
        borderWidth: 1,
      });
    });
  }

  const ctx2d = canvas.getContext('2d');
  const scrollWrap = document.getElementById('chart-scroll');
  const containerWidth = scrollWrap ? scrollWrap.clientWidth : 800;
  const containerHeight = scrollWrap ? scrollWrap.clientHeight : 384;
  const perLabelWidth = Math.max(70, (datasets.length || 1) * 18 + 40);
  const desiredWidth = Math.max(containerWidth, (labels.length || 1) * perLabelWidth);
  ctx2d.canvas.style.width = desiredWidth + 'px';
  ctx2d.canvas.style.height = containerHeight + 'px';
  ctx2d.canvas.width = desiredWidth; // important when responsive:false
  ctx2d.canvas.height = containerHeight;

  // eslint-disable-next-line no-undef
  window.myChart = new Chart(ctx2d, {
    type: 'bar',
    data: { labels, datasets },
    options: {
      responsive: false,
      scales: { y: { beginAtZero: true } },
      plugins: { legend: { display: !!ctx.showLegend, position: 'top' } },
    },
  });
}

export function toggleLegend(ctx) {
  ctx.showLegend = !ctx.showLegend;
  renderChart(ctx);
}

export function scrollToProvince(ctx) {
  if (!ctx.selectedProvinceForScroll) return;
  const scrollWrap = document.getElementById('chart-scroll');
  if (!scrollWrap) return;
  const labels = (ctx.dynamicRows || []).map((row) => row.wilayah);
  const index = labels.indexOf(ctx.selectedProvinceForScroll);
  if (index === -1) return;
  const perLabelWidth = Math.max(70, 120);
  const scrollPosition = index * perLabelWidth;
  const containerWidth = scrollWrap.clientWidth;
  const next = Math.max(0, scrollPosition - containerWidth / 2);
  scrollWrap.scrollTo({ left: next, behavior: 'smooth' });
}

export function initChartResizeHandlerOnce(ctx) {
  if (ctx.__chartResizeInitialized) return;
  ctx.__chartResizeInitialized = true;
  window.addEventListener('resize', () => { try { renderChart(ctx); } catch {} });
}

export default { renderChart, toggleLegend, scrollToProvince, initChartResizeHandlerOnce };
