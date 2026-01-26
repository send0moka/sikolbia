from typing import Optional


class SemanticIndex:
    """Minimal SemanticIndex stub used by the API when real index is absent.

    This provides `exists()`, `load()`, and `size()` so the API can start
    even when embeddings/indexing features are not used in the container.
    """
    def __init__(self, path: Optional[str] = None):
        self._path = path
        self._loaded = False
        self._docs = 0

    def exists(self) -> bool:
        return False

    def load(self):
        self._loaded = True

    def size(self) -> int:
        return self._docs
