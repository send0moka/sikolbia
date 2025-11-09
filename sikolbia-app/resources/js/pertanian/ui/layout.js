// Layout & wilayah height syncing helpers bound to Alpine ctx
import { setupResizeObserver } from '../utils/dom.js';

export function setupHeightSync(ctx) {
  try {
    const layoutEl = ctx.$refs?.layoutBox;
    if (!layoutEl) return;
    // Observe container size changes
    ctx.__layoutRoCleanup && ctx.__layoutRoCleanup();
    ctx.__layoutRoCleanup = setupResizeObserver(layoutEl, () => syncHeights(ctx));
    // Also update on window resize
    if (!ctx.__layoutResizeHandler) {
      ctx.__layoutResizeHandler = () => syncHeights(ctx);
      window.addEventListener('resize', ctx.__layoutResizeHandler);
    }
    // Initial sync
    syncHeights(ctx);
  } catch {}
}

export function syncHeights(ctx) {
  try {
    const layoutEl = ctx.$refs?.layoutBox;
    const wilayahEl = ctx.$refs?.wilayahBox;
    if (!layoutEl || !wilayahEl) return;
    const target = layoutEl.offsetHeight;
    wilayahEl.style.height = target + 'px';
    // adjust all scroll areas inside wilayah
    const scrollEls = wilayahEl.querySelectorAll('[data-wilayah-scroll]');
    const contRect = wilayahEl.getBoundingClientRect();
    const styles = getComputedStyle(wilayahEl);
    const padB = parseFloat(styles.paddingBottom || '0');
    scrollEls.forEach((scrollEl) => {
      const scrollRect = scrollEl.getBoundingClientRect();
      const topOffset = scrollRect.top - contRect.top;
      const desired = Math.max(120, target - topOffset - padB);
      scrollEl.style.height = desired + 'px';
      scrollEl.style.maxHeight = desired + 'px';
      scrollEl.style.overflowY = 'auto';
    });
  } catch {}
}

export default { setupHeightSync, syncHeights };
