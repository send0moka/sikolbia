// Unified Pertanian Reports API client
// Wraps module-agnostic endpoints for topiks, variabels, klasifikasis, temporal data, wilayahs, and filtering

import { get, post } from './http.js';

// Helpers to build base path per module type
function base(moduleType) {
  // moduleType: 'lahan' | 'benih-pupuk' | 'iklim-opt-dpi'
  return `/api/${moduleType}`;
}

// Metadata endpoints
export function fetchTopiks(moduleType, { cacheTtlMs = 5 * 60_000 } = {}) {
  return get(`${base(moduleType)}/topiks`, { cacheTtlMs });
}

export function fetchVariabels(moduleType, topikId, { cacheTtlMs = 5 * 60_000 } = {}) {
  return get(`${base(moduleType)}/variabels/${encodeURIComponent(topikId)}`, { cacheTtlMs });
}

export function fetchKlasifikasis(moduleType, payload) {
  // payload typically: { variabel_id: number }
  return post(`${base(moduleType)}/klasifikasis`, payload);
}

export function fetchYears(moduleType, { cacheTtlMs = 5 * 60_000 } = {}) {
  return get(`${base(moduleType)}/years`, { cacheTtlMs });
}

export function fetchBulans(moduleType, { cacheTtlMs = 5 * 60_000 } = {}) {
  return get(`${base(moduleType)}/bulans`, { cacheTtlMs });
}

// Wilayah tree (public helper under /pertanian)
export function fetchWilayahs({ cacheTtlMs = 10 * 60_000 } = {}) {
  return get(`/pertanian/wilayahs`, { cacheTtlMs });
}

// Main filter endpoint (returns headers & rows)
export function filterReport(moduleType, selectionPayload) {
  // POST /pertanian/{moduleType}/filter
  return post(`/pertanian/${moduleType}/filter`, selectionPayload);
}

export default {
  fetchTopiks,
  fetchVariabels,
  fetchKlasifikasis,
  fetchYears,
  fetchBulans,
  fetchWilayahs,
  filterReport,
};
