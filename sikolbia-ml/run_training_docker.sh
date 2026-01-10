#!/bin/bash
# Script untuk run training di Docker (no need install Python lokal)

echo "🚀 Building Docker image untuk training..."
docker build -f Dockerfile.training -t sikolbia-training .

echo "🔥 Running training dengan metrics logging..."
docker run --rm \
  -v "$(pwd)/data:/app/data" \
  -v "$(pwd)/models:/app/models" \
  -v "$(pwd)/training_results:/app/training_results" \
  sikolbia-training

echo "✅ Training selesai! Cek folder training_results/"
