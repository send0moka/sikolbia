# Laravel Livewire Flux Volt - Upload Gambar ke Storage Lokal

Panduan lengkap untuk membuat fitur upload gambar menggunakan Laravel Livewire dengan Flux dan Volt yang disimpan di storage lokal.

## Fitur yang Tersedia

- ✅ Upload gambar dengan validasi (JPEG, PNG, JPG, GIF, SVG, max 2MB)
- ✅ Preview gambar sebelum upload
- ✅ Penyimpanan di storage lokal (`storage/app/public/images/`)
- ✅ Tampilan gambar yang sudah diupload
- ✅ Hapus gambar dengan konfirmasi
- ✅ Loading state saat upload
- ✅ Pesan feedback untuk user

## Setup & Instalasi

### 1. Install Dependencies

```bash
composer require livewire/livewire
composer require livewire/flux
```

### 2. Buat Symbolic Link untuk Storage

```bash
php artisan storage:link
```

### 3. Set Permission Storage

```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

## Kode Component

### File: `resources/views/livewire/image-upload.blade.php`

```php
<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    #[Validate('required|image|mimes:jpeg,png,jpg,gif,svg|max:2048')]
    public $image;
    
    public $uploadedImage = null;
    public $uploadedImageUrl = null;
    public $uploadMessage = '';
    
    public function uploadImage()
    {
        $this->validate();
        
        try {
            // Simpan gambar ke storage lokal dengan nama unik
            $fileName = time() . '_' . $this->image->getClientOriginalName();
            $path = $this->image->storeAs('images', $fileName, 'public');
            
            // Set uploaded image info
            $this->uploadedImage = $path;
            $this->uploadedImageUrl = Storage::url($path);
            $this->uploadMessage = 'Gambar berhasil diupload!';
            
            // Reset image input
            $this->image = null;
            
        } catch (\Exception $e) {
            $this->uploadMessage = 'Terjadi kesalahan saat mengupload gambar: ' . $e->getMessage();
        }
    }
    
    public function deleteImage()
    {
        if ($this->uploadedImage) {
            // Hapus file dari storage
            Storage::disk('public')->delete($this->uploadedImage);
            
            // Reset variables
            $this->uploadedImage = null;
            $this->uploadedImageUrl = null;
            $this->uploadMessage = 'Gambar berhasil dihapus!';
        }
    }
    
    public function resetMessage()
    {
        $this->uploadMessage = '';
    }
}; ?>

<div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
    <flux:heading size="lg" class="mb-6 text-center">Upload Gambar</flux:heading>
    
    {{-- Form Upload --}}
    <form wire:submit.prevent="uploadImage" class="space-y-4">
        <div>
            <flux:field>
                <flux:label>Pilih Gambar</flux:label>
                <flux:input 
                    type="file" 
                    wire:model="image" 
                    accept="image/*"
                />
                <flux:error name="image" />
            </flux:field>
        </div>
        
        {{-- Preview Gambar yang akan diupload --}}
        @if ($image)
            <div class="mt-4">
                <flux:subheading>Preview:</flux:subheading>
                <div class="mt-2 border-2 border-dashed border-gray-300 rounded-lg p-4">
                    <img src="{{ $image->temporaryUrl() }}" 
                         alt="Preview" 
                         class="max-w-full h-auto max-h-48 mx-auto rounded-lg">
                </div>
            </div>
        @endif
        
        {{-- Upload Button --}}
        <flux:button 
            type="submit" 
            variant="primary" 
            class="w-full"
            :disabled="!$image"
            wire:loading.attr="disabled"
            wire:target="uploadImage"
        >
            <span wire:loading.remove wire:target="uploadImage">Upload Gambar</span>
            <span wire:loading wire:target="uploadImage">Mengupload...</span>
        </flux:button>
    </form>
    
    {{-- Pesan Upload --}}
    @if ($uploadMessage)
        <div class="mt-4">
            <flux:badge 
                :color="str_contains($uploadMessage, 'berhasil') ? 'emerald' : 'red'" 
                class="w-full justify-center"
            >
                {{ $uploadMessage }}
                <button wire:click="resetMessage" class="ml-2 text-xs">×</button>
            </flux:badge>
        </div>
    @endif
    
    {{-- Gambar yang sudah diupload --}}
    @if ($uploadedImageUrl)
        <div class="mt-6 border-t pt-6">
            <flux:subheading class="mb-3">Gambar yang Diupload:</flux:subheading>
            <div class="relative">
                <img src="{{ $uploadedImageUrl }}" 
                     alt="Uploaded Image" 
                     class="w-full h-auto max-h-64 object-cover rounded-lg shadow-md">
                
                {{-- Delete Button --}}
                <flux:button 
                    wire:click="deleteImage" 
                    variant="danger" 
                    size="sm"
                    class="absolute top-2 right-2"
                    wire:confirm="Apakah Anda yakin ingin menghapus gambar ini?"
                >
                    Hapus
                </flux:button>
            </div>
            
            <div class="mt-2 text-sm text-gray-600">
                <strong>Path:</strong> {{ $uploadedImage }}<br>
                <strong>URL:</strong> <a href="{{ $uploadedImageUrl }}" target="_blank" class="text-blue-600 hover:underline">{{ $uploadedImageUrl }}</a>
            </div>
        </div>
    @endif
</div>
```

## Konfigurasi

### File: `config/filesystems.php`

Pastikan konfigurasi disk 'public' sudah ada:

```php
return [
    'disks' => [
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
        // disk lainnya...
    ],
];
```

### File: `routes/web.php`

```php
use Livewire\Volt\Volt;

Volt::route('/upload', 'image-upload')->name('image.upload');

// Atau jika menggunakan blade file biasa:
// Route::get('/upload', function () {
//     return view('image-upload');
// });
```

## Optional: Model & Migration untuk Database

Jika Anda ingin menyimpan informasi gambar di database:

### Migration: `database/migrations/xxxx_create_images_table.php`

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->integer('file_size');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('images');
    }
};
```

### Model: `app/Models/Image.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',
        'file_name', 
        'file_path',
        'mime_type',
        'file_size'
    ];

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
```

## Cara Menggunakan

1. **Akses halaman upload** melalui route `/upload`
2. **Pilih gambar** menggunakan file input
3. **Lihat preview** gambar yang dipilih
4. **Klik tombol "Upload Gambar"** untuk menyimpan
5. **Gambar tersimpan** akan ditampilkan dengan opsi untuk menghapus

## Struktur File

- **Gambar disimpan di**: `storage/app/public/images/`
- **URL gambar**: `http://yourdomain.com/storage/images/filename.jpg`
- **Nama file**: Otomatis dengan timestamp untuk menghindari konflik

## Validasi yang Diterapkan

- **Format file**: JPEG, PNG, JPG, GIF, SVG
- **Ukuran maksimal**: 2MB (2048 KB)
- **Wajib diisi**: File harus dipilih

## Tips & Catatan

- Komponen menggunakan Flux UI untuk styling modern dan responsif
- Terintegrasi penuh dengan Livewire untuk reactive functionality
- File disimpan dengan nama unik menggunakan timestamp
- Mendukung preview gambar sebelum upload
- Loading state untuk user experience yang baik
- Pesan feedback yang informatif

Dengan setup ini, Anda akan memiliki sistem upload gambar yang lengkap dan user-friendly menggunakan Laravel Livewire, Flux, dan Volt.