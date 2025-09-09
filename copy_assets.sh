#!/bin/bash

echo "🔧 Copying Livewire and Flux assets..."

# Create directories
mkdir -p public/livewire
mkdir -p public/flux

# Copy Livewire assets
cp vendor/livewire/livewire/dist/*.js public/livewire/
echo "✅ Livewire JS files copied"

# Copy Flux assets  
cp vendor/livewire/flux/dist/*.js public/flux/
cp vendor/livewire/flux/dist/*.css public/flux/ 2>/dev/null || echo "ℹ️  No Flux CSS files found"
echo "✅ Flux assets copied"

# Set correct permissions
chown -R www-data:www-data public/livewire public/flux 2>/dev/null || echo "ℹ️  Permission setting skipped (not running as root)"

echo "🎉 Assets copying completed!"
