// Compare intent handlers: build chips and execute comparison
import api from '../api/pertanianApi.js';

export function injectCompareChips(ctx, payload) {
  try {
    const years = Array.isArray(payload?.years) ? payload.years.map(Number).filter(Boolean) : [];
    const wilayahs = Array.isArray(payload?.wilayahs) ? payload.wilayahs : [];
    const options = [];
    if (years.length >= 2) options.push({ value: 'cmp_years', label: 'Bandingkan Tahun' });
    if (wilayahs.length >= 2) options.push({ value: 'cmp_wilayahs', label: 'Bandingkan Wilayah' });
    // Placeholder for months; invoked when structured has months
    const hasMonths = Array.isArray(ctx.structuredSuggestion?.months) && ctx.structuredSuggestion.months.length >= 2;
    if (hasMonths) options.push({ value: 'cmp_bulan', label: 'Bandingkan Bulan' });
    if (!options.length) {
      // Guidance chip when compare intent present but insufficient entities
      ctx.conversation.push({ sender: 'bot', type: 'options', variant: 'compare', title: 'Tambahkan detail untuk perbandingan:', options: [
        { value: 'guidance_compare', label: 'Tambahkan tahun atau wilayah kedua' }
      ] });
      ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
      return;
    }
    ctx.conversation.push({ sender: 'bot', type: 'options', variant: 'compare', title: 'Perbandingan tersedia:', options });
    ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
  } catch (e) { /* ignore */ }
}

export async function executeComparison(ctx, kind, structured) {
  try {
    ctx.isLoading = true;
    const s = structured || ctx.structuredSuggestion || {};
    const module = (Array.isArray(s.modules) && s.modules[0]) || ctx.wizard?.moduleType || ctx.moduleType || 'benih-pupuk';
    await ctx.ensureModuleData(module);
    // Reuse existing selection logic lightly; pick first topik/variabel
    const topiks = ctx.wizardData.topiks || [];
    const topikId = topiks[0]?.id; if (!topikId) { throw new Error('Topik tidak ditemukan untuk perbandingan.'); }
    await ctx.ensureVariabels(module, topikId);
    const variabels = ctx.wizardData.variabelsByTopik[String(topikId)] || [];
    const variabelId = variabels[0]?.id; if (!variabelId) { throw new Error('Variabel tidak ditemukan untuk perbandingan.'); }
  await ctx.ensureKlasifikasis(module, variabelId);
  const klasList = ctx.wizardData.klasifikasisByVariabel[String(variabelId)] || [];
  const chosenKlas = klasList.slice(0, 1);
  const klasIds = chosenKlas.map(k => k.id); // keep minimal for speed

    const compareYears = (kind === 'cmp_years') ? (Array.isArray(s.years) ? s.years.map(Number).filter(Boolean).slice(0,2) : []) : [];
    const compareWilayahs = (kind === 'cmp_wilayahs') ? (Array.isArray(s.wilayah_hits) ? s.wilayah_hits.slice(0,2) : []) : [];
    const selections = [];
    if (compareYears.length >= 2) {
      // For years comparison, use same wilayah (first hit if exists)
      const wilayahId = compareWilayahs[0]?.id || (s.wilayah_hits?.[0]?.id);
      for (const yr of compareYears) {
        selections.push({ variabel_id: variabelId, klasifikasi_ids: klasIds, tahun_ids: [yr], bulan_ids: module === 'lahan' ? [] : (ctx.wizardData.bulans || []).map(b=>b.id).slice(0,1) });
      }
      const config = { tata_letak: 'tipe_1', provinsi_ids: wilayahId ? [wilayahId] : [], kabupaten_ids: [] };
      const data = await api.filterReport(module, { selections, config });
        // Resolve readable meta
        const varObj = (variabels || []).find(v => String(v.id) === String(variabelId)) || variabels?.[0] || null;
        const bulanName = (ctx.wizardData.bulans || [])[0]?.nama || null;
        const wilayahName = wilayahId ? ((s.wilayah_hits || []).find(w => w.id === wilayahId)?.nama || null) : null;
        return buildCompareSummary(ctx, kind, data, {
          module,
          variabel: varObj ? (varObj.satuan ? `${varObj.nama} (${varObj.satuan})` : varObj.nama) : 'Variabel',
          klasifikasi: chosenKlas[0]?.nama || null,
          compareYears,
          bulan: bulanName,
          wilayah: wilayahName,
        });
    }
    if (compareWilayahs.length >= 2) {
      const yr = (Array.isArray(s.years) ? s.years.map(Number).filter(Boolean).sort((a,b)=>b-a)[0] : null) || (new Date().getFullYear());
      for (const w of compareWilayahs) {
        const config = { tata_letak: 'tipe_1', provinsi_ids: [w.id], kabupaten_ids: [] };
        const selectionsLocal = [{ variabel_id: variabelId, klasifikasi_ids: klasIds, tahun_ids: [yr], bulan_ids: module === 'lahan' ? [] : (ctx.wizardData.bulans || []).map(b=>b.id).slice(0,1) }];
        const data = await api.filterReport(module, { selections: selectionsLocal, config });
        selections.push({ wilayah: w.nama, rows: data.rows || [], headers: data.headers || [] });
      }
      const varObj = (variabels || []).find(v => String(v.id) === String(variabelId)) || variabels?.[0] || null;
      const bulanName = (ctx.wizardData.bulans || [])[0]?.nama || null;
      return buildCompareSummary(ctx, kind, selections, {
        module,
        variabel: varObj ? (varObj.satuan ? `${varObj.nama} (${varObj.satuan})` : varObj.nama) : 'Variabel',
        klasifikasi: chosenKlas[0]?.nama || null,
        year: yr,
        bulan: bulanName,
      });
    }
    throw new Error('Jenis perbandingan belum didukung atau entitas kurang.');
  } catch (e) {
    ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Gagal melakukan perbandingan: ' + (e.message || e) });
  } finally {
    ctx.isLoading = false;
    ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
  }
}

function formatNum(n){
  const nn = Number(n);
  if (!Number.isFinite(nn)) return '-';
  return nn.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function buildCompareSummary(ctx, kind, raw, meta) {
  try {
    let lines = [];
    let paragraph = '';
    if (kind === 'cmp_years') {
      const rows = raw.rows || [];
      const headers = raw.headers || [];
      const last = headers[headers.length -1] || [];
      const colNames = last.map(h=>h.name).filter(n=>n && n.toLowerCase() !== 'wilayah');
      const firstRow = rows[0] || {};
      const vals = firstRow.values || [];
      const aYear = meta.compareYears?.[0];
      const bYear = meta.compareYears?.[1];
      const aVal = Number(vals?.[0]);
      const bVal = Number(vals?.[1]);
      const wilayah = meta.wilayah ? ` di wilayah ${meta.wilayah}` : '';
      const bulan = meta.bulan ? ` bulan ${meta.bulan}` : '';
      const varLabel = meta.variabel || 'Variabel';
      const klas = meta.klasifikasi ? ` klasifikasi ${meta.klasifikasi}` : '';

      // Build paragraph with delta and percentage
      const diff = (Number.isFinite(aVal) && Number.isFinite(bVal)) ? (aVal - bVal) : null;
      const pct = (Number.isFinite(aVal) && Number.isFinite(bVal) && bVal !== 0) ? ((diff / bVal) * 100) : null;
      const trend = (diff === null) ? '' : (diff > 0 ? 'kenaikan' : (diff < 0 ? 'penurunan' : 'tidak berubah'));

      paragraph = `Data untuk ${varLabel}${klas}${wilayah} pada tahun ${aYear}${bulan} adalah ${formatNum(aVal)}; sedangkan pada tahun ${bYear}${bulan} adalah ${formatNum(bVal)}. `;
      if (diff === null) {
        paragraph += 'Nilai tidak lengkap untuk menghitung perbedaan.';
      } else if (diff === 0) {
        paragraph += 'Kesimpulan: tidak terdapat perbedaan antara kedua periode.';
      } else {
        paragraph += `Kesimpulannya terdapat perbedaan sebesar ${formatNum(Math.abs(diff))}, dengan persentase ${trend} sekitar ${formatNum(Math.abs(pct))}%.`;
      }
      lines.push(`Perbandingan tahun: ${aYear} vs ${bYear}`);
    } else if (kind === 'cmp_wilayahs') {
      // Expect raw to be array of two payloads with wilayah + rows/headers
      const partA = raw?.[0];
      const partB = raw?.[1];
      const aName = partA?.wilayah || 'Wilayah A';
      const bName = partB?.wilayah || 'Wilayah B';
      const aVal = Number(partA?.rows?.[0]?.values?.[0]);
      const bVal = Number(partB?.rows?.[0]?.values?.[0]);
      const bulan = meta.bulan ? ` bulan ${meta.bulan}` : '';
      const varLabel = meta.variabel || 'Variabel';
      const klas = meta.klasifikasi ? ` klasifikasi ${meta.klasifikasi}` : '';
      const year = meta.year ? ` tahun ${meta.year}` : '';

      const diff = (Number.isFinite(aVal) && Number.isFinite(bVal)) ? (aVal - bVal) : null;
      const pct = (Number.isFinite(aVal) && Number.isFinite(bVal) && bVal !== 0) ? ((diff / bVal) * 100) : null;
      const trend = (diff === null) ? '' : (diff > 0 ? 'kenaikan' : (diff < 0 ? 'penurunan' : 'tidak berubah'));

      paragraph = `Data untuk ${varLabel}${klas} di ${aName}${year}${bulan} adalah ${formatNum(aVal)}; sedangkan di ${bName}${year}${bulan} adalah ${formatNum(bVal)}. `;
      if (diff === null) {
        paragraph += 'Nilai tidak lengkap untuk menghitung perbedaan.';
      } else if (diff === 0) {
        paragraph += 'Kesimpulan: tidak terdapat perbedaan antara kedua wilayah.';
      } else {
        paragraph += `Kesimpulannya terdapat perbedaan sebesar ${formatNum(Math.abs(diff))}, dengan persentase ${trend} sekitar ${formatNum(Math.abs(pct))}%.`;
      }
      lines.push(`Perbandingan wilayah: ${aName} vs ${bName}`);
    }
    if (!lines.length) lines.push('Tidak ada hasil perbandingan yang dapat diringkas.');
  ctx.conversation.push({ sender: 'bot', type: 'summary-compare', title: 'Ringkasan Perbandingan', summaryLines: lines, paragraph, meta });
  } catch (e) {
    ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Gagal membangun ringkasan perbandingan.' });
  }
}

export default { injectCompareChips, executeComparison };