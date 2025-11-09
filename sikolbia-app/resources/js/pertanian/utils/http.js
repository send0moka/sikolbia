// Shared HTTP utilities

// Parses a Fetch Response as JSON when possible; otherwise returns a diagnostic object
// with the raw text. Consumers can check for `._nonJson` to differentiate.
export async function parseJsonOrText(res) {
  const ct = res.headers?.get?.('content-type') || '';
  if (ct.includes('application/json')) {
    return await res.json();
  }
  const txt = await res.text();
  return { _nonJson: true, text: txt };
}

export default { parseJsonOrText };
