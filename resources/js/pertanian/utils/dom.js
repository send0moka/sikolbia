// DOM utilities shared across modules

export function getCsrfToken() {
  try {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  } catch {
    return '';
  }
}

// Basic HTML sanitizer (allow simple inline formatting; strip scripts and event handlers)
export function sanitizeHtml(input) {
  try {
    const allowedTags = new Set(['b', 'i', 'em', 'strong', 'u', 'br', 'ul', 'ol', 'li', 'p', 'span', 'div']);
    const allowedAttrs = new Set(['class']);
    const tpl = document.createElement('template');
    tpl.innerHTML = String(input || '');
    const walker = document.createTreeWalker(tpl.content, NodeFilter.SHOW_ELEMENT, null);
    const toRemove = [];
    while (walker.nextNode()) {
      const el = walker.currentNode;
      const tag = (el.tagName || '').toLowerCase();
      if (!allowedTags.has(tag)) { toRemove.push(el); continue; }
      for (const attr of Array.from(el.attributes || [])) {
        const name = attr.name.toLowerCase();
        const val = String(attr.value || '');
        if (!allowedAttrs.has(name) || /^on/i.test(name) || /^javascript:/i.test(val)) {
          el.removeAttribute(attr.name);
        }
      }
    }
    for (const n of toRemove) { n.replaceWith(document.createTextNode(n.textContent || '')); }
    return tpl.innerHTML;
  } catch {
    return String(input || '');
  }
}

export function scrollToBottom(el) {
  try {
    if (!el) return;
    el.scrollTop = el.scrollHeight;
  } catch {}
}

export function setupResizeObserver(el, handler) {
  try {
    if (!el || typeof ResizeObserver === 'undefined') return () => {};
    const ro = new ResizeObserver(() => {
      try { handler && handler(); } catch {}
    });
    ro.observe(el);
    return () => { try { ro.disconnect(); } catch {} };
  } catch {
    return () => {};
  }
}
