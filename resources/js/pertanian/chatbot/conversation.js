// Conversation helpers for chatbot: rendering bubbles, switching modes, and reset flow
import api from '../api/chatbotApi.js';
import { sanitizeHtml } from '../utils/dom.js';

export function renderBotText(ctx, text) {
  ctx.conversation.push({ sender: 'bot', type: 'text', text: sanitizeHtml(String(text || '')) });
}

export function switchChatMode(ctx, mode, { silent = false } = {}) {
  const m = String(mode || '').toLowerCase();
  if (!['natural', 'structured', 'guided'].includes(m)) return;
  ctx.chatMode = m;
  if (!silent) {
    const label = m === 'natural' ? 'chat bebas' : (m === 'structured' ? 'pencarian terstruktur' : 'pandu');
    renderBotText(ctx, `Mode diubah ke ${label}.`);
  }
  // If switching to guided, ensure the wizard has a prompt on screen
  if (m === 'guided' && (!ctx.conversation || ctx.conversation.length === 0)) {
    resetGuidedChat(ctx);
  }
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function resetGuidedChat(ctx) {
  ctx.wizard = { step: 'module', moduleType: null, topikId: null, variabelId: null, klasifikasiIds: [], tahunIds: [], bulanIds: [], provinsiIds: [], kabupatenIds: [] };
  ctx.conversation = [
    { sender: 'bot', type: 'text', text: sanitizeHtml('Saya bisa bantu mencari data <strong>Lahan</strong>, <strong>Benih & Pupuk</strong>, atau <strong>Iklim & OPT DPI</strong>. Mau mulai dari modulnya?') },
    { sender: 'bot', type: 'options', title: 'Pilih Modul', options: [
      { value: 'benih-pupuk', label: 'Benih & Pupuk' },
      { value: 'lahan', label: 'Lahan' },
      { value: 'iklim-opt-dpi', label: 'Iklim & OPT DPI' },
    ]},
  ];
  try {
    const quickStart = (ctx.getQuickStartTemplates?.() || []).map((t) => ({ value: t.id, label: t.label, quickStart: true, templateId: t.id, scope: 'quickstart' }));
    if (quickStart.length) ctx.conversation.push({ sender: 'bot', type: 'options', title: 'Mulai Cepat', options: quickStart });
  } catch {}
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function startGuidedInline(ctx) {
  ctx.wizard = { step: 'module', moduleType: null, topikId: null, variabelId: null, klasifikasiIds: [], tahunIds: [], bulanIds: [], provinsiIds: [], kabupatenIds: [] };
  ctx.conversation.push({ sender: 'bot', type: 'text', text: 'Silahkan Pilih Modul lagi untuk melanjutkan' });
  ctx.conversation.push({ sender: 'bot', type: 'options', title: 'Pilih Modul', options: [
    { value: 'benih-pupuk', label: 'Benih & Pupuk' },
    { value: 'lahan', label: 'Lahan' },
    { value: 'iklim-opt-dpi', label: 'Iklim & OPT DPI' },
  ] });
  ctx.$nextTick(() => { try { ctx.scrollChatToBottom(); } catch {} });
}

export function openResetConfirm(ctx) { ctx.showChatResetConfirm = true; }
export function cancelResetConfirm(ctx) { ctx.showChatResetConfirm = false; }

export async function confirmReset(ctx) {
  try { await api.resetConversation(); } catch {}
  resetGuidedChat(ctx);
  ctx.userMessage = '';
  ctx.showChatResetConfirm = false;
}

export default { renderBotText, switchChatMode, resetGuidedChat, startGuidedInline, openResetConfirm, cancelResetConfirm, confirmReset };
