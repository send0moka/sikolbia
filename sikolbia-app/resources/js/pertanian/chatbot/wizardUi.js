// Wizard UI helpers

export function renderBulanChecklist(ctx) {
  const bulans = (ctx.wizardData?.bulans || []).map(b => ({ value: b.id, label: b.nama }));
  ctx.conversation.push({ sender: 'bot', type: 'checklist', title: 'Pilih Bulan', options: bulans, selected: [] });
}

export function toggleChecklist(ctx, chat, value) {
  if (!Array.isArray(chat.selected)) chat.selected = [];
  const i = chat.selected.indexOf(value);
  if (i >= 0) chat.selected.splice(i, 1); else chat.selected.push(value);
}

export function clearChecklist(ctx, chat) { chat.selected = []; }

export function confirmChecklist(ctx, index) {
  const chat = ctx.conversation[index];
  const labels = (chat.options || []).filter(o => chat.selected?.includes(o.value)).map(o => o.label);
  const step = ctx.wizard.step;
  ctx.conversation.push({ sender: 'user', type: 'text', text: labels.length ? labels.join(', ') : '(tidak ada)' });
  if (step === 'klasifikasi') {
    ctx.wizard.klasifikasiIds = chat.selected || [];
    ctx.wizard.step = 'waktu_tahun';
    ctx.askYears();
  } else if (step === 'waktu_tahun') {
    // Proceed after choosing years via checklist
    ctx.wizard.tahunIds = (chat.selected || []).map(v => Number(v));
    if (ctx.wizard.moduleType !== 'lahan') {
      ctx.wizard.step = 'waktu_bulan_choice';
      ctx.loadWizardBulans();
    } else {
      ctx.wizard.step = 'wilayah_level';
      ctx.askWilayah();
    }
  } else if (step === 'waktu_bulan') {
    ctx.wizard.bulanIds = chat.selected || [];
    ctx.wizard.step = 'wilayah_level';
    ctx.askWilayah();
  } else if (step === 'wilayah_provinsi') {
    ctx.wizard.provinsiIds = chat.selected || [];
    ctx.finishPreview();
  } else if (step === 'wilayah_kabupaten') {
    ctx.wizard.kabupatenIds = chat.selected || [];
    ctx.finishPreview();
  }
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export default { renderBulanChecklist, toggleChecklist, clearChecklist, confirmChecklist };
