#!/usr/bin/env bash
# Helper: copy a SavedModel dir into the ensemble folder and restart the ML container
# Usage: ./deploy_saved_lstm.sh /full/path/to/extracted/saved_lstm

set -euo pipefail

SRC_DIR="$1"
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
# On Windows host the repo layout may differ; allow running script from repo root too
if [ -z "${SIKOLBIA_ROOT:-}" ]; then
  SIK_ROOT="$ROOT"
else
  SIK_ROOT="$SIKOLBIA_ROOT"
fi
DEST_DIR="$SIK_ROOT/ml_models/ensemble/saved_lstm_clean"

if [ ! -d "$SRC_DIR" ]; then
  echo "Source directory does not exist: $SRC_DIR"
  exit 2
fi

echo "Copying $SRC_DIR -> $DEST_DIR"
rm -rf "$DEST_DIR"
mkdir -p "$DEST_DIR"
cp -r "$SRC_DIR"/* "$DEST_DIR"/

echo "Restarting ML service via docker-compose (sikolbia-app/docker-compose.yml)"
docker-compose -f "$SIK_ROOT/../sikolbia-app/docker-compose.yml" restart sikolbia-ml-api

echo "Waiting 5s for service to initialize..."
sleep 5

echo "Checking /health"
curl -sS http://localhost:8082/health || echo "health check failed"

echo "Done"
