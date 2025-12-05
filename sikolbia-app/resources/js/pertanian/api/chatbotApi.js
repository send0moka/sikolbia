// Chatbot API client for natural, structured, and reset operations

import { post } from './http.js';

export function sendMessage(message, mode = 'natural', extra = {}) {
  const payload = { message, mode, ...extra };
  return post('/api/chatbot', payload);
}

export function resetConversation() {
  return post('/api/chatbot/reset', {});
}

export function summarizePreview(tablePayload) {
  // tablePayload: { headers: [...], rows: [...], meta?: {...} }
  return post('/api/chatbot/summary', tablePayload);
}

export default {
  sendMessage,
  resetConversation,
  summarizePreview,
};
