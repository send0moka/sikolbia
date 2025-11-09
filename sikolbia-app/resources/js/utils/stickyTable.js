// Sticky Table utilities: equalize column widths for complex multi-row headers
// Auto-initializes and re-scans on DOM mutations. Safe to import multiple times.

const INIT_ATTR = 'data-sticky-init';

function equalizeColumns(table) {
    try {
        // Avoid adding multiple colgroups if already present
        if (table.querySelector('colgroup')) return;
        const colgroup = document.createElement('colgroup');
        const thead = table.querySelector('thead');
        if (!thead) return;
        const firstRow = thead.querySelector('tr:first-child');
        if (!firstRow) return;
        const cells = Array.from(firstRow.querySelectorAll('th'));
        cells.forEach((_, idx) => {
            const col = document.createElement('col');
            if (idx === 0) {
                col.style.minWidth = '120px';
            } else {
                col.style.minWidth = '100px';
                col.style.width = '100px';
            }
            colgroup.appendChild(col);
        });
        table.insertBefore(colgroup, table.firstChild);
    } catch (e) {
        // noop
    }
}

function recalcFor(table) {
    try {
        const wrap = table.closest('.sticky-table-container');
        const thead = table.querySelector('thead');
        if (!wrap || !thead) return;
        equalizeColumns(table);
        const rows = Array.from(thead.querySelectorAll('tr'));
        const dpr = window.devicePixelRatio || 1;
        let acc = 0;
        rows.forEach((row, idx) => {
            wrap.style.setProperty(`--row-top-${idx + 1}`, `${acc}px`);
            row.querySelectorAll('th').forEach(th => (th.style.top = `${acc}px`));
            const rectH = row.getBoundingClientRect().height;
            const snapped = Math.round(rectH * dpr) / dpr;
            acc += snapped;
        });
    } catch (e) {
        // noop
    }
}

function wire(table) {
    if (!table || table.hasAttribute(INIT_ATTR)) return;
    table.setAttribute(INIT_ATTR, '1');
    const doRecalc = () => recalcFor(table);
    requestAnimationFrame(() => requestAnimationFrame(doRecalc));

    const ro = new ResizeObserver(doRecalc);
    const wrap = table.closest('.sticky-table-container');
    if (wrap) ro.observe(wrap);
    ro.observe(table);

    const mo = new MutationObserver(() => requestAnimationFrame(doRecalc));
    mo.observe(table, { childList: true, subtree: true, attributes: true });

    const io = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) doRecalc();
        });
    }, { root: null, threshold: 0 });
    io.observe(table);

    window.addEventListener('resize', doRecalc, { passive: true });
}

function scan() {
    document.querySelectorAll('table.sticky-table').forEach(wire);
}

function init() {
    scan();
    // Observe the whole document for newly inserted tables/headers
    const rootMO = new MutationObserver(() => scan());
    rootMO.observe(document.body, { childList: true, subtree: true });
    // Defer scans to catch late layout stabilizations
    setTimeout(scan, 0);
    setTimeout(scan, 200);
    setTimeout(scan, 500);
}

const StickyTable = { init, scan, wire };

// Auto-init when DOM is ready
if (typeof document !== 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }
}

export default StickyTable;
