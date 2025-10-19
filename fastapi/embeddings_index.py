import os
import threading
from typing import List, Dict, Any, Optional

import numpy as np

try:
    import faiss  # type: ignore
except Exception:  # pragma: no cover
    faiss = None

try:
    from sentence_transformers import SentenceTransformer
except Exception:
    SentenceTransformer = None  # type: ignore


class SemanticIndex:
    """Thread-safe semantic index using SentenceTransformer + FAISS (cosine similarity).

    Documents: list of dicts: { 'text': str, 'metadata': {...} }
    Persisted under base_dir with files: index.faiss + meta.npy
    """

    def __init__(self, base_dir: str = "ml_models/models/semantic_index",
                 model_name: str = "sentence-transformers/paraphrase-multilingual-MiniLM-L12-v2"):
        self.base_dir = base_dir
        self.model_name = model_name
        self._lock = threading.RLock()
        self._model = None
        self._index = None
        self._meta: List[Dict[str, Any]] = []

        os.makedirs(self.base_dir, exist_ok=True)

    def _ensure_model(self):
        if self._model is None:
            if SentenceTransformer is None:
                raise RuntimeError("sentence-transformers is not installed")
            self._model = SentenceTransformer(self.model_name)

    def _ensure_faiss(self):
        if faiss is None:
            raise RuntimeError("faiss-cpu is not installed")

    def load(self) -> bool:
        """Load index + metadata from disk if exists."""
        with self._lock:
            index_path = os.path.join(self.base_dir, "index.faiss")
            meta_path = os.path.join(self.base_dir, "meta.npy")
            if os.path.exists(index_path) and os.path.exists(meta_path):
                self._ensure_faiss()
                self._index = faiss.read_index(index_path)
                meta_arr = np.load(meta_path, allow_pickle=True)
                self._meta = meta_arr.tolist()
                return True
            return False

    def save(self):
        with self._lock:
            if self._index is None:
                return
            index_path = os.path.join(self.base_dir, "index.faiss")
            meta_path = os.path.join(self.base_dir, "meta.npy")
            faiss.write_index(self._index, index_path)
            np.save(meta_path, np.array(self._meta, dtype=object), allow_pickle=True)

    def build(self, documents: List[Dict[str, Any]], normalize: bool = True):
        """Build index from documents."""
        with self._lock:
            self._ensure_model()
            self._ensure_faiss()
            texts = [d.get('text', '') for d in documents]
            if not texts:
                # Initialize empty index
                self._index = None
                self._meta = []
                return
            embeddings = self._model.encode(texts, show_progress_bar=False, convert_to_numpy=True)
            if normalize:
                norms = np.linalg.norm(embeddings, axis=1, keepdims=True) + 1e-12
                embeddings = embeddings / norms
            d = embeddings.shape[1]
            self._index = faiss.IndexFlatIP(d)
            self._index.add(embeddings)
            self._meta = [d for d in documents]

    def is_ready(self) -> bool:
        with self._lock:
            return self._index is not None and len(self._meta) > 0

    def size(self) -> int:
        with self._lock:
            return len(self._meta)

    def search(self, query: str, top_k: int = 8, normalize: bool = True) -> List[Dict[str, Any]]:
        with self._lock:
            if not self.is_ready():
                return []
            self._ensure_model()
            q = self._model.encode([query], show_progress_bar=False, convert_to_numpy=True)
            if normalize:
                q = q / (np.linalg.norm(q, axis=1, keepdims=True) + 1e-12)
            scores, indices = self._index.search(q, min(top_k, len(self._meta)))
            scores = scores[0].tolist()
            idxs = indices[0].tolist()
            results: List[Dict[str, Any]] = []
            for s, i in zip(scores, idxs):
                if i == -1:
                    continue
                item = self._meta[i].copy()
                item['score'] = float(s)
                results.append(item)
            return results
