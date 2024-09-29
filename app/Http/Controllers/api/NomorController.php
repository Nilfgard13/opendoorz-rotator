<?php

namespace App\Http\Controllers\api;

use App\Models\Nomor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NomorController extends Controller
{
    public function index()
    {
        $nomors = Nomor::all();
        return response()->json(['nomors' => $nomors]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'nomor' => 'required|numeric',
        ]);

        $nomor = Nomor::create($validatedData);

        return response()->json(['message' => 'Nomor added successfully', 'nomor' => $nomor], 201);
    }

    public function Update(Request $request, $id)
    {
        // Mencari nomor berdasarkan ID yang diberikan
        $nomor = Nomor::find($id);

        // Cek apakah nomor ditemukan
        if (!$nomor) {
            return response()->json(['message' => 'Nomor not found'], 404);
        }

        // Mengupdate nama dan nomor berdasarkan input
        $nomor->name = $request->input('name', $nomor->name);
        $nomor->nomor = $request->input('nomor', $nomor->nomor);
        $nomor->save();

        return response()->json(['message' => 'Nomor updated successfully']);
    }

    public function Delete($id)
    {
        // Menghapus item berdasarkan ID dan mengembalikan jumlah baris yang dihapus
        $deleted = Nomor::where('id', $id)->delete();

        // Cek apakah ada data yang berhasil dihapus
        if ($deleted) {
            return response()->json(['message' => 'Item deleted successfully']);
        } else {
            return response()->json(['message' => 'Item not found or already deleted'], 404);
        }
    }



    public function search($title)
    {
        $results = Nomor::where('name', 'LIKE', "%{$title}%")
            ->orWhere('nomor', 'LIKE', "%{$title}%")
            ->get();

        // Cek apakah hasil ditemukan atau tidak
        if ($results->isEmpty()) {
            // Mengembalikan respons jika tidak ada hasil, dengan status 404 (Not Found)
            return response()->json([
                'message' => 'No results found',
                'results' => []
            ], 404);
        }

        // Jika ada hasil, kembalikan respons dengan status 200 (OK)
        return response()->json([
            'message' => 'Search results found',
            'results' => $results
        ], 200);
    }

    public function generateLink()
    {
        $text = ""; // Ubah ini jika perlu menambahkan pesan yang diinginkan
        $admins = Nomor::pluck('nomor')->toArray(); // Mengambil semua nomor admin
        $indexFile = 'admin_index.txt';

        // Inisialisasi index jika file tidak ada
        if (!file_exists(storage_path($indexFile))) {
            $currentIndex = 0;
            file_put_contents(storage_path($indexFile), $currentIndex);
        } else {
            $currentIndex = (int)file_get_contents(storage_path($indexFile));
        }

        // Pilih admin berdasarkan index saat ini
        $adminNumber = $admins[$currentIndex];

        // Update index untuk admin selanjutnya
        $nextIndex = ($currentIndex + 1) % count($admins);
        file_put_contents(storage_path($indexFile), $nextIndex);

        // Generate link WhatsApp
        $url = "https://api.whatsapp.com/send?phone=" . $adminNumber . "&text=" . urlencode($text);

        return response()->json(['url' => $url]);
    }


    public function showLink()
    {
        $url = $this->generateLink()->original['url'];
        return response()->json(['redirect_url' => $url]);
    }
}
