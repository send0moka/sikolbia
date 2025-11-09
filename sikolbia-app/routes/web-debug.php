<?php

use App\Models\TbKelompokbps;

Route::get('/debug/kelompok', function () {
    $count = TbKelompokbps::count();
    $items = TbKelompokbps::take(5)->get();
    
    return response()->json([
        'count' => $count,
        'items' => $items->toArray()
    ]);
});
