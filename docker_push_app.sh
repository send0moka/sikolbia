#!/bin/bash

# Docker Hub Push Script for SIKOLBIA-APP
# Usage: ./docker_push_app.sh <dockerhub-username> <version>

set -e

DOCKERHUB_USER=${1:-"yourusername"}
VERSION=${2:-"latest"}
IMAGE_NAME="sikolbia-app"

echo "=========================================="
echo "🐋 Docker Hub Push - SIKOLBIA App"
echo "=========================================="
echo ""
echo "Docker Hub User: $DOCKERHUB_USER"
echo "Image Name: $IMAGE_NAME"
echo "Version: $VERSION"
echo ""

# Confirmation
read -p "⚠️  Ready to build and push? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Aborted."
    exit 1
fi

echo ""
echo "Step 1: Building Docker image..."
docker build -t $IMAGE_NAME:$VERSION .
docker build -t $IMAGE_NAME:latest .

echo ""
echo "Step 2: Tagging for Docker Hub..."
docker tag $IMAGE_NAME:$VERSION $DOCKERHUB_USER/$IMAGE_NAME:$VERSION
docker tag $IMAGE_NAME:latest $DOCKERHUB_USER/$IMAGE_NAME:latest

echo ""
echo "Step 3: Logging in to Docker Hub..."
docker login

echo ""
echo "Step 4: Pushing to Docker Hub..."
docker push $DOCKERHUB_USER/$IMAGE_NAME:$VERSION
docker push $DOCKERHUB_USER/$IMAGE_NAME:latest

echo ""
echo "=========================================="
echo "✅ Push Complete!"
echo "=========================================="
echo ""
echo "Images pushed:"
echo "  - $DOCKERHUB_USER/$IMAGE_NAME:$VERSION"
echo "  - $DOCKERHUB_USER/$IMAGE_NAME:latest"
echo ""
echo "To pull:"
echo "  docker pull $DOCKERHUB_USER/$IMAGE_NAME:latest"
echo ""
