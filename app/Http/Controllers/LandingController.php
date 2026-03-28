<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Menampilkan halaman utama pencarian
     */
    public function index()
    {
        return view('landing');
    }

    /**
     * Menjalankan logika pencarian via skrip Python TF-IDF
     */
    public function search(Request $request) 
    {
        // 1. Tangkap input dari request AJAX
        $q = $request->input('q', '');
        $rank = $request->input('rank', 10);
        $filter = $request->input('filter', 'all');

        // 2. Tentukan Path file yang dibutuhkan
        $scriptPath = base_path('web_scrap/query.py');
        $picklePath = base_path('web_scrap/index.pickle');
        
        // 3. Susun Command dengan urutan argumen yang benar:
        // python [script] [path_pickle] [jumlah_rank] [kata_kunci] [filter_bab]
        $command = "python " . 
                   escapeshellarg($scriptPath) . " " . 
                   escapeshellarg($picklePath) . " " . 
                   (int)$rank . " " . 
                   escapeshellarg($q) . " " . 
                   escapeshellarg($filter) . " 2>&1";

        // 4. Eksekusi perintah di shell
        $output = shell_exec($command);

        // 5. Olah output dan kirim sebagai JSON
        if ($output) {
            $decoded = json_decode(trim($output), true);
            
            // Jika json_decode gagal (output bukan format JSON yang valid)
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'Format output Python tidak valid',
                    'debug' => $output
                ], 500);
            }

            return response()->json($decoded);
        }
        
        // Kembalikan array kosong jika tidak ada output
        return response()->json([]);
    }
}