<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BenihPupukImport;

class BenihPupukImportController extends Controller
{
    public function import(Request $request)
    {
        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:2048', // 2MB max (matches PHP upload_max_filesize)
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Get the uploaded file
            $file = $request->file('importFile');

            // Import the data directly using Laravel Excel with the uploaded file
            Excel::import(new BenihPupukImport, $file);

            return back()->with('message', 'Data berhasil diimpor!')->with('success', true);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
}
