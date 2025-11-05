// Data loaders to populate wizardData and caches using the unified API client
import api from '../api/pertanianApi.js';

function normalizeArrayMaybeData(obj) {
  if (Array.isArray(obj)) return obj;
  if (obj && Array.isArray(obj.data)) return obj.data;
  return [];
}

export async function ensureModuleData(ctx, module) {
  const hasTopiks = Array.isArray(ctx.wizardData.topiks) && ctx.wizardData.topiks.length > 0;
  const hasYears = Array.isArray(ctx.wizardData.years) && ctx.wizardData.years.length > 0;
  const needsBulans = module !== 'lahan';
  const hasBulans = Array.isArray(ctx.wizardData.bulans) && ctx.wizardData.bulans.length > 0;
  if ((module === ctx.moduleType) && hasTopiks && hasYears && (!needsBulans || hasBulans)) {
    return;
  }
  try {
    ctx.isLoading = true;
    const [topiks, years, bulans] = await Promise.all([
      api.fetchTopiks(module),
      api.fetchYears(module),
      needsBulans ? api.fetchBulans(module) : Promise.resolve([]),
    ]);
    ctx.wizardData.topiks = Array.isArray(topiks) ? topiks : normalizeArrayMaybeData(topiks);
    ctx.wizardData.variabelsByTopik = {};
    ctx.wizardData.klasifikasisByVariabel = {};
    ctx.wizardData.years = normalizeArrayMaybeData(years);
    ctx.wizardData.bulans = needsBulans ? normalizeArrayMaybeData(bulans) : [];
  } finally {
    ctx.isLoading = false;
  }
}

export async function ensureVariabels(ctx, module, topikId) {
  const key = String(topikId);
  if (ctx.wizardData.variabelsByTopik[key]) return;
  const data = await api.fetchVariabels(module, topikId);
  ctx.wizardData.variabelsByTopik[key] = Array.isArray(data) ? data : normalizeArrayMaybeData(data);
}

export async function ensureKlasifikasis(ctx, module, variabelId) {
  const key = String(variabelId);
  if (ctx.wizardData.klasifikasisByVariabel[key]) return;
  const list = await api.fetchKlasifikasis(module, { variabel_ids: [variabelId] });
  ctx.wizardData.klasifikasisByVariabel[key] = Array.isArray(list) ? list : normalizeArrayMaybeData(list);
}

export async function ensureWilayahs(ctx) {
  if (ctx.wizardData.wilayahs && ctx.wizardData.wilayahs.length) return;
  const list = await api.fetchWilayahs();
  ctx.wizardData.wilayahs = Array.isArray(list) ? list : normalizeArrayMaybeData(list);
}

export default { ensureModuleData, ensureVariabels, ensureKlasifikasis, ensureWilayahs };
