<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StructuredSearchService;

class StructuredSearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate(['q' => 'required|string|max:2000']);
        $svc = new StructuredSearchService();
        $result = $svc->search((string)$request->input('q'));
        return response()->json($result);
    }
}
