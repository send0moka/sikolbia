// Chatbot flow handlers: stepBack and handleOption
// These functions operate on the Alpine ctx and delegate to modular prompt/render helpers.

/**
 * Step back one wizard step and re-render the previous prompt.
 * @param {any} ctx - Alpine component context
 */
export async function stepBack(ctx) {
  const step = ctx.wizard?.step;
  const prevMap = {
    topik: 'module',
    variabel: 'topik',
    klasifikasi: 'variabel',
    waktu_tahun: 'klasifikasi',
    waktu_bulan: 'waktu_tahun',
    wilayah_level: (ctx.wizard?.moduleType === 'lahan' ? 'waktu_tahun' : 'waktu_bulan'),
    wilayah_provinsi: 'wilayah_level',
    wilayah_pilih_provinsi: 'wilayah_level',
    wilayah_kabupaten: 'wilayah_pilih_provinsi',
    choose_preview_style: 'wilayah_level',
  };
  const prev = prevMap[step] || 'module';
  ctx.wizard.step = prev;

  if (prev === 'module') {
    ctx.conversation.push({
      sender: 'bot',
      type: 'options',
      title: 'Pilih Modul',
      options: [
        { value: 'benih-pupuk', label: 'Benih & Pupuk' },
        { value: 'lahan', label: 'Lahan' },
        { value: 'iklim-opt-dpi', label: 'Iklim & OPT DPI' },
      ],
    });
  } else if (prev === 'topik') {
    await ctx.ensureModuleData(ctx.wizard.moduleType || ctx.moduleType);
    ctx.loadWizardTopiks();
  } else if (prev === 'variabel') {
    await ctx.ensureVariabels(ctx.wizard.moduleType || ctx.moduleType, ctx.wizard.topikId);
    ctx.loadWizardVariabels();
  } else if (prev === 'klasifikasi') {
    await ctx.ensureKlasifikasis(ctx.wizard.moduleType || ctx.moduleType, ctx.wizard.variabelId);
    ctx.loadWizardKlasifikasis();
  } else if (prev === 'waktu_tahun') {
    ctx.askYears();
  } else if (prev === 'waktu_bulan') {
    ctx.loadWizardBulans();
  } else if (prev === 'wilayah_level') {
    ctx.askWilayah();
  } else if (prev === 'wilayah_provinsi') {
    await ctx.ensureWilayahs();
    ctx.askProvinces();
  } else if (prev === 'wilayah_pilih_provinsi') {
    await ctx.ensureWilayahs();
    ctx.askProvinces(true);
  } else if (prev === 'wilayah_kabupaten') {
    await ctx.ensureWilayahs();
    if (ctx.wizard.provinsiIds && ctx.wizard.provinsiIds.length) {
      ctx.askKabupaten(ctx.wizard.provinsiIds[0]);
    } else if (ctx.selectedProvinsiId) {
      ctx.askKabupaten(ctx.selectedProvinsiId);
    }
  }

  try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch (_) { /* noop */ }
}

/**
 * Handle a user's option click within the guided chatbot flow.
 * @param {any} ctx - Alpine component context
 * @param {number} index - conversation index (unused, but kept for signature compatibility)
 * @param {any} opt - option payload with { value, label, ... }
 */
export async function handleOption(ctx, index, opt) {
  // Quick Start templates are handled regardless of step
  if (opt && opt.scope === 'quickstart' && opt.quickStart) {
    ctx.conversation.push({ sender: 'user', type: 'text', text: opt.label });
    if (typeof ctx.runQuickStart === 'function') {
      await ctx.runQuickStart(opt.templateId);
    }
    try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch (_) {}
    return;
  }

  // Structured search decision buttons
  if (opt && opt.value === 'use_structured' && ctx.structuredSuggestion) {
    ctx.conversation.push({ sender: 'user', type: 'text', text: opt.label || 'Gunakan hasil terstruktur' });
    ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Baik, sedang saya ambilkan datanya...' });
    try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch (_) {}
    await ctx.applyStructuredSuggestion(null);
    try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch (_) {}
    return;
  }
  if (opt && opt.value === 'skip_structured' && ctx.structuredSuggestion) {
    ctx.conversation.push({ sender: 'user', type: 'text', text: opt.label || 'Lanjutkan dengan chatbot' });
    ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Baik, lanjutkan dengan chatbot. Silakan ketik pertanyaan berikutnya.' });
    ctx.structuredSuggestion = null;
    ctx.useStructuredAfterModule = false;
    try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch (_) {}
    return;
  }

  // If user is choosing module after structured suggestion asked for module selection
  if (
    ctx.useStructuredAfterModule &&
    opt && opt.value && ['lahan', 'benih-pupuk', 'iklim-opt-dpi'].includes(opt.value)
  ) {
    ctx.conversation.push({ sender: 'user', type: 'text', text: opt.label || opt.value });
    ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Baik, sedang saya ambilkan datanya...' });
    try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch (_) {}
    await ctx.applyStructuredSuggestion(opt.value);
    ctx.useStructuredAfterModule = false;
    try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch (_) {}
    return;
  }

  const step = ctx.wizard.step;
  if (step === 'module') {
    ctx.wizard.moduleType = opt.value;
    ctx.wizard.step = 'topik';
    ctx.conversation.push({ sender: 'user', type: 'text', text: opt.label });
    await ctx.ensureModuleData(ctx.wizard.moduleType);
    ctx.loadWizardTopiks();
  } else if (step === 'topik') {
    ctx.wizard.topikId = opt.value;
    ctx.wizard.step = 'variabel';
    ctx.conversation.push({ sender: 'user', type: 'text', text: opt.label });
    await ctx.ensureVariabels(ctx.wizard.moduleType, ctx.wizard.topikId);
    ctx.loadWizardVariabels();
  } else if (step === 'variabel') {
    ctx.wizard.variabelId = opt.value;
    ctx.wizard.step = 'klasifikasi';
    ctx.conversation.push({ sender: 'user', type: 'text', text: opt.label });
    await ctx.ensureKlasifikasis(ctx.wizard.moduleType, ctx.wizard.variabelId);
    // Data Dictionary bubble for variabel info
    try {
      const list = ctx.wizardData?.variabelsByTopik?.[String(ctx.wizard.topikId)] || [];
      const varObj = list.find(v => String(v.id) === String(ctx.wizard.variabelId));
      if (varObj) {
        const nama = varObj.nama || 'Variabel';
        const satuan = varObj.satuan ? ` (${varObj.satuan})` : '';
        const desc = varObj.deskripsi || varObj.keterangan || 'Deskripsi tidak tersedia.';
        const html = `<div><div><strong>Variabel:</strong> ${nama}${satuan}</div><div class="mt-1 text-neutral-700">${desc}</div></div>`;
        ctx.conversation.push({ sender: 'bot', type: 'text', text: ctx.sanitizeHtml(html) });
      }
    } catch (_) { /* noop */ }
    ctx.loadWizardKlasifikasis();
  } else if (step === 'waktu_tahun') {
    const id = opt.value;
    if (!ctx.wizard.tahunIds.includes(id)) ctx.wizard.tahunIds.push(id);
    ctx.conversation.push({ sender: 'user', type: 'text', text: String(opt.label || id) });
    if (ctx.wizard.moduleType !== 'lahan') {
      ctx.wizard.step = 'waktu_bulan';
      ctx.loadWizardBulans();
    } else {
      ctx.wizard.step = 'wilayah';
      ctx.askWilayah();
    }
  } else if (step === 'waktu_bulan_choice') {
    ctx.conversation.push({ sender: 'user', type: 'text', text: opt.label });
    if (opt.value === 'bulan_all') {
      const bulans = ctx.wizardData?.bulans || [];
      ctx.wizard.bulanIds = bulans.map(b => b.id);
      ctx.wizard.step = 'wilayah';
      ctx.askWilayah();
    } else {
      ctx.renderBulanChecklist();
      ctx.wizard.step = 'waktu_bulan';
    }
  } else if (step === 'wilayah_level') {
    ctx.conversation.push({ sender: 'user', type: 'text', text: opt.label });
    if (opt.value === 'nasional') {
      ctx.wizard.step = 'wilayah_provinsi';
      await ctx.ensureWilayahs();
      ctx.askProvinces();
    } else {
      ctx.wizard.step = 'wilayah_pilih_provinsi';
      await ctx.ensureWilayahs();
      ctx.askProvinces(true);
    }
  } else if (step === 'wilayah_pilih_provinsi') {
    ctx.wizard.provinsiIds = [opt.value];
    ctx.conversation.push({ sender: 'user', type: 'text', text: opt.label });
    ctx.wizard.step = 'wilayah_kabupaten';
    await ctx.ensureWilayahs();
    ctx.askKabupaten(opt.value);
  } else if (step === 'choose_preview_style') {
    ctx.conversation.push({ sender: 'user', type: 'text', text: opt.label });
    ctx.presentPreview(opt.value === 'summary' ? 'summary' : 'table');
  } else if (step === 'post_preview_help') {
    ctx.conversation.push({ sender: 'user', type: 'text', text: opt.label });
    if (opt.value === 'yes') {
      ctx.startGuidedInline();
    } else {
      ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Baik, silakan ketik pertanyaan Anda di bawah.' });
      ctx.wizard.step = 'free_text';
    }
  }

  try { ctx.$nextTick(() => ctx.scrollChatToBottom()); } catch (_) { /* noop */ }
}
