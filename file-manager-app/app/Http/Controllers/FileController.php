<?php
// app/Http/Controllers/FileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * Tampilkan halaman daftar file
     */
    public function index()
    {
        // Ambil semua file dari storage/app/public/uploads
        $files = Storage::disk('public')->files('uploads');
        
        // Format file info
        $fileList = [];
        foreach ($files as $file) {
            $fileList[] = [
                'name' => basename($file),
                'path' => $file,
                'size' => Storage::disk('public')->size($file),
                'modified' => Storage::disk('public')->lastModified($file)
            ];
        }
        
        return view('files.index', compact('fileList'));
    }

    /**
     * Upload file
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Simpan file ke storage/app/public/uploads
            $file->storeAs('uploads', $filename, 'public');
            
            return redirect()->back()->with('success', 'File berhasil diupload!');
        }

        return redirect()->back()->with('error', 'Gagal upload file!');
    }

    /**
     * Download file
     */
    public function download($filename)
    {
        $filePath = 'uploads/' . $filename;
        
        // Cek apakah file ada
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Download file
        return Storage::disk('public')->download($filePath);
    }

    /**
     * Stream file (untuk preview)
     */
    public function stream($filename)
    {
        $filePath = 'uploads/' . $filename;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $file = Storage::disk('public')->get($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);

        return response($file, 200)
            ->header('Content-Type', $mimeType);
    }

    /**
     * Hapus file
     */
    public function delete($filename)
    {
        $filePath = 'uploads/' . $filename;
        
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return redirect()->back()->with('success', 'File berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'File tidak ditemukan!');
    }
}