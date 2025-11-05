// Light formatting helpers centralized here for consistency

export function formatNumberId(value, options = {}) {
  if (value == null || value === '') return '';
  const opts = { minimumFractionDigits: 0, maximumFractionDigits: 2, ...options };
  try { return Number(value).toLocaleString('id-ID', opts); } catch { return String(value); }
}

export function formatTimestampId(date = new Date()) {
  try { return date.toLocaleString('id-ID'); } catch { return String(date); }
}

export default { formatNumberId, formatTimestampId };
