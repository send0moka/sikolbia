FROM python:3.10-slim-bookworm AS builder

WORKDIR /app

# Install build deps for NumPy/SciPy/TensorFlow
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    gcc \
    g++ \
    git \
    curl \
    libopenblas-dev \
    liblapack-dev \
    libatlas-base-dev \
    && rm -rf /var/lib/apt/lists/*

# Create venv
RUN python -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"

# Install prod requirements
COPY requirements.txt .
RUN pip install --upgrade pip \
    && pip install --no-cache-dir -r requirements.txt \
    # prune unneeded files
    && find /opt/venv -type d -name "tests" -exec rm -rf {} + \
    && find /opt/venv -type d -name "__pycache__" -exec rm -rf {} + \
    && rm -rf /root/.cache/pip


# =============================
# Stage 2: Runtime image
# =============================
FROM python:3.10-slim-bookworm

WORKDIR /app

# Runtime libs only (no compilers)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libopenblas0 \
    liblapack3 \
    libatlas3-base \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Copy venv
COPY --from=builder /opt/venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"

# Copy app source
COPY . .

# Create directories
RUN mkdir -p logs ml_models/models

# Health check
HEALTHCHECK --interval=30s --timeout=30s --start-period=60s --retries=3 \
    CMD curl -f http://localhost:8000/health || exit 1

# Expose ports
EXPOSE 8000 5000 8888

# Default: FastAPI
CMD ["uvicorn", "nbm_api:app", "--host", "0.0.0.0", "--port", "8000"]
