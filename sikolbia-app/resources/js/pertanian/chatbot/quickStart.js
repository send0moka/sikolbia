// Quick Start flow for guided wizard

export async function runQuickStart(ctx, templateId) {
  const templates = ctx.getQuickStartTemplates();
  const t = templates.find(x => x.id === templateId);
  if (!t) { ctx.conversation.push({ sender:'bot', type:'text', text:'Template Mulai Cepat tidak tersedia.'}); return; }
  try {
    ctx.isLoading = true;
    ctx.wizard.moduleType = t.module;
    await ctx.ensureModuleData(t.module);
    // Pick topik by includes
    const topiks = ctx.wizardData.topiks || [];
    const tMatch = (name, s) => String(name||'').toLowerCase().includes(String(s||'').toLowerCase());
    let topik = topiks.find(tp => tMatch(tp.nama, t.topikMatch)) || topiks[0];
    if (!topik) { ctx.conversation.push({ sender:'bot', type:'text', text:'Tidak ada topik pada modul ini.'}); return; }
    ctx.wizard.topikId = topik.id;
    await ctx.ensureVariabels(t.module, topik.id);
    // Pick variabel
    const vars = ctx.wizardData.variabelsByTopik[String(topik.id)] || [];
    let varObj = null;
    if (Array.isArray(t.variabelMatches)) {
      varObj = vars.find(v => t.variabelMatches.some(key => tMatch(v.nama, key)));
    }
    if (!varObj) varObj = vars[0];
    if (!varObj) { ctx.conversation.push({ sender:'bot', type:'text', text:'Tidak ada variabel pada topik terpilih.'}); return; }
    ctx.wizard.variabelId = varObj.id;
    await ctx.ensureKlasifikasis(t.module, varObj.id);
    // Klasifikasi: pick a few
    const klasList = ctx.wizardData.klasifikasisByVariabel[String(varObj.id)] || [];
    ctx.wizard.klasifikasiIds = (t.pick?.klasifikasi === 'few') ? (klasList.slice(0,3).map(k => k.id)) : [];
    // Years
    const years = ctx.wizardData.years || [];
    const latest = years.length ? Math.max(...years) : null;
    ctx.wizard.tahunIds = latest ? [latest] : (years[0] ? [years[0]] : []);
    // Months (not for lahan)
    if (t.module !== 'lahan') {
      const bulans = ctx.wizardData.bulans || [];
      ctx.wizard.bulanIds = (t.pick?.months === 'all') ? bulans.map(b => b.id) : (bulans.slice(-3).map(b => b.id));
    } else { ctx.wizard.bulanIds = []; }
    // Wilayah: pick top 5 provinces
    await ctx.ensureWilayahs();
    const provs = (ctx.wizardData.wilayahs || []).slice(0,5);
    ctx.wizard.provinsiIds = provs.map(p => p.id);
    ctx.wizard.kabupatenIds = [];
    // Inform user
    const label = `${(varObj.nama || 'Variabel')} — ${ctx.wizard.tahunIds.join(', ')}${(ctx.wizard.bulanIds && ctx.wizard.bulanIds.length)? ', semua bulan':''} (Top 5 provinsi)`;
    ctx.conversation.push({ sender:'bot', type:'text', text:`Menjalankan Mulai Cepat untuk: <strong>${label}</strong>` });
    await ctx.finishPreview();
  } catch (e) {
    ctx.conversation.push({ sender:'bot', type:'text', text:'Gagal menjalankan Mulai Cepat: ' + (e.message || e) });
  } finally {
    ctx.isLoading = false;
  }
}

export default { runQuickStart };
