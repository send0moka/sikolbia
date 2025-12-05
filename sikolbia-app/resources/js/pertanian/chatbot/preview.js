// Chatbot preview rendering using utils
import { buildSummaryLines } from './utils.js';
import chatbotApi from '../api/chatbotApi.js';

export function presentPreview(ctx, style) {
  const pend = ctx.wizard._pendingPreview; if (!pend) return;
  // Force summary always, consistent with compact assistant behavior
  const tryServer = async () => {
    try {
      const res = await chatbotApi.summarizePreview({ headers: pend.results.headers || [], rows: pend.results.rows || [], meta: pend.meta || {} });
      const lines = Array.isArray(res?.summaryLines) ? res.summaryLines : buildSummaryLines(pend.results);
      ctx.conversation.push({ sender: 'bot', type: 'summary', title: 'Ringkasan', summaryLines: lines, insights: res?.insights || null, meta: pend.meta, payload: pend });
    } catch {
      const lines = buildSummaryLines(pend.results);
      ctx.conversation.push({ sender: 'bot', type: 'summary', title: 'Ringkasan', summaryLines: lines, meta: pend.meta, payload: pend });
    }
  };
  // Execute and ignore await since chat push is fine async
  tryServer();
  ctx.wizard.step = 'preview';
  ctx.pendingPrompt = { type: 'tutorial', module: pend.moduleType || (ctx.wizard.moduleType), preview: pend };
  const labelMod = (pend.meta?.module) || (ctx.wizard.moduleType?.replace('benih-pupuk', 'Benih & Pupuk')?.replace('iklim-opt-dpi', 'Iklim & OPT DPI')?.replace('lahan', 'Lahan'));
  ctx.renderBotText && ctx.renderBotText(`Perlu panduan mencari tabel lengkap di halaman ${labelMod}? Ketik "ya" untuk pandu, atau ketik bebas untuk lanjut.`);
}

export function showAsTable(ctx, chat) {
  if (chat && chat.type === 'summary') {
    ctx.conversation.push({ sender: 'bot', type: 'table', title: 'Pratinjau Hasil', results: chat.payload.results, meta: chat.meta, payload: chat.payload });
  }
}

export default { presentPreview, showAsTable };
