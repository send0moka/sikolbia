// Lightweight HTTP helper with CSRF, JSON parsing, and optional in-memory caching
// Usage:
//   import { get, post, request } from './http.js'
//   const data = await get('/api/benih-pupuk/topiks', { cacheTtlMs: 5 * 60_000 })

import { getCsrfToken } from '../utils/dom.js';
import { parseJsonOrText } from '../utils/http.js';

const _cache = new Map(); // key -> { expiry: number, value: any }

const APP_BASE_URL = (() => {
  try {
    const meta = typeof document !== 'undefined'
      ? document.querySelector('meta[name="app-url"]')
      : null;
    const metaUrl = meta?.getAttribute('content');
    const windowUrl = typeof window !== 'undefined' ? window.APP_URL : '';
    const raw = metaUrl || windowUrl || '';
    return raw ? String(raw).replace(/\/+$/, '') : '';
  } catch (err) {
    console.warn('Unable to resolve APP_URL', err);
    return '';
  }
})();

function toAbsoluteUrl(url) {
  if (!url) return url;
  const isAbsolute = /^([a-z][a-z0-9+.-]*:)?\/\//i.test(url);
  if (isAbsolute || !APP_BASE_URL) return url;
  const normalizedPath = url.startsWith('/') ? url : `/${url}`;
  return `${APP_BASE_URL}${normalizedPath}`;
}

function _makeKey(method, url, body) {
  const b = body ? (typeof body === 'string' ? body : JSON.stringify(body)) : '';
  return `${method.toUpperCase()} ${url} ${b}`;
}


export async function request(url, options = {}) {
  const {
    method = 'GET',
    headers = {},
    body = undefined,
    cacheTtlMs = 0, // only applies to GET-like requests
    credentials = 'same-origin',
    json = true, // if true and body is object, stringify and set JSON header
    signal,
  } = options;

  const upper = method.toUpperCase();
  const isCacheable = upper === 'GET' && cacheTtlMs > 0;
  const resolvedUrl = toAbsoluteUrl(url);
  const cacheKey = isCacheable ? _makeKey(upper, resolvedUrl, null) : null;

  if (isCacheable && _cache.has(cacheKey)) {
    const entry = _cache.get(cacheKey);
    if (entry && entry.expiry > Date.now()) {
      return entry.value;
    } else {
      _cache.delete(cacheKey);
    }
  }

  const finalHeaders = new Headers(headers);
  if (json && body && typeof body === 'object' && !(body instanceof FormData)) {
    finalHeaders.set('Content-Type', 'application/json');
  }
  finalHeaders.set('X-Requested-With', 'XMLHttpRequest');

  // Attach CSRF for non-GET requests
  if (upper !== 'GET' && !finalHeaders.has('X-CSRF-TOKEN')) {
    const token = getCsrfToken();
    if (token) finalHeaders.set('X-CSRF-TOKEN', token);
  }

  const init = {
    method: upper,
    headers: finalHeaders,
    credentials,
    signal,
  };
  if (body !== undefined) {
    init.body = json && body && typeof body === 'object' && !(body instanceof FormData)
      ? JSON.stringify(body)
      : body;
  }

  const res = await fetch(resolvedUrl, init);
  const parsed = await parseJsonOrText(res);

  if (!res.ok) {
    const msg = parsed && parsed._nonJson ? (parsed.text || '') : JSON.stringify(parsed);
    const err = new Error(`HTTP ${res.status} ${res.statusText}: ${msg?.slice?.(0, 200)}`);
    err.status = res.status;
    err.details = parsed;
    throw err;
  }

  if (isCacheable) {
    _cache.set(cacheKey, { expiry: Date.now() + cacheTtlMs, value: parsed });
  }
  return parsed;
}

export function get(url, opts = {}) {
  return request(url, { ...opts, method: 'GET' });
}

export function post(url, data, opts = {}) {
  return request(url, { ...opts, method: 'POST', body: data });
}

export function clearCache() {
  _cache.clear();
}

export default { request, get, post, clearCache };
