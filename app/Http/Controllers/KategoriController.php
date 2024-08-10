<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Carbon\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KategoriController extends Controller
{
    public function index()
    {
        $client = new Client();
        $url = 'http://103.175.217.148/kategori/getallkategori';

        try {
            $response = $client->request('GET', $url);
            $data = json_decode($response->getBody(), true);

            if ($data['status'] == 200) {

                return view('pages.categories.index', ['kategoris' => $data['data']]);
            } else {
                return back()->with('error', 'Gagal mendapatkan data');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendapatkan data: ' . $e->getMessage());
        }
    }

    public function createKategori() {
        return view('pages.categories.create-categories');
    }

    public function store(Request $request)
    {
        $request->validate([
            'namakategori' => 'required|string|max:30',
            'deskripsi' => 'required|string|max:50',
        ]);

        try {
            $response = Http::post('http://103.175.217.148/kategori/create', [
                'namakategori' => $request->namakategori,
                'deskripsi' => $request->deskripsi,
            ]);

            if ($response->successful()) {

                $message = $response->json()['msg'];
                return redirect()->route('kategori')->with('success', $message);
            } else {

                $message = $response->json()['msg'];
                return back()->withErrors(['msg' => $message])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Gagal menambahkan Kategori: ' . $e->getMessage()])->withInput();
        }
        


        
    }

    

    public function editKategori($id) {
        $client =new Client();
        $response = $client->request('GET', 'http://103.175.217.148/kategori/getkategoribyid/' . $id);
        $kategori = json_decode($response->getBody()->getContents(), true);

        if($response->getStatusCode() !== 200) {
            return redirect()->back()->with('error', 'Kategori Tidak Ditemukan');
        }

        return view('pages.categories.edit-categories', compact('kategori'));
    }

    public function hapusKategori($id)
    {
        try {

            $response = Http::delete('http://103.175.217.148/kategori/delete/' . $id);


            if ($response->status() === 200) {
                $message = $response->json()['msg'];

                return redirect()->route('kategori')->with('success', $message);
            } elseif ($response->status() === 404) {
                $message = $response->json()['msg'];

                return redirect()->route('kategori')->with('error', $message);
            } else {
                $message = $response->json()['msg'];

                return redirect()->route('kategori')->with('error', $message);
            }
        } catch (\Exception $e) {
            return redirect()->route('kategori')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateKategori(Request $request, $id)
    {
        $request->validate([
            'namakategori' => 'required',
            'deskripsi' => 'required',
        ]);

        try {
            $client = new Client();
            $url = 'http://103.175.217.148/kategori/edit/' . $id;

            $response = $client->request('PUT', $url, [
                'json' => $request->all(),
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                return redirect()->route('kategori')->with('success', $responseData['msg'] ?? 'Kategori berhasil diperbarui');
            } else {
                return back()->withErrors(['msg' => $responseData['msg'] ?? 'Gagal memperbarui kategori'])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Gagal memperbarui kategori: ' . $e->getMessage()])->withInput();
        }
    }
}