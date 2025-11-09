<a href="/admin" class="text-black dark:text-white text-xs -mb-2 hover:underline">
    ← Kembali
</a>
<div class="flex items-center gap-2">
    <div
        class="flex aspect-square size-8 items-center justify-center rounded-md bg-white dark:bg-neutral-800 text-neutral-800 dark:text-white">
        <x-app-logo-icon class="size-5 fill-current" />
    </div>
    <span class="text-balance text-sm leading-tight font-semibold">
        Basis Data 
        @if(request()->is('admin/konsumsi-pangan*'))
            Konsumsi Pangan
        @elseif(request()->is('admin/lahan*'))
            Lahan
        @elseif(request()->is('admin/iklim-opt-dpi*'))
            Iklim OPT DPI
        @elseif(request()->is('admin/daftar-alamat*'))
            Daftar Alamat
        @elseif(request()->is('admin/benih-pupuk*'))
            Benih Pupuk
        @else
            ...
        @endif
    </span>
</div>
