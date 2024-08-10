<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Carbon\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProdukController extends Controller
{
    public function index()
    {
        
        $client = new Client();
        $url = 'http://103.175.217.148/produk/getallproduk';

        try {
            $response = $client->request('GET', $url);
            $data = json_decode($response->getBody(), true);

            if ($data['status'] == 200) {
                return view('pages.product.index', ['produks' => $data['data']]);
            } else {

                return back()->with('error', 'Gagal mendapatkan data');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendapatkan data: ' . $e->getMessage());
        }
    }

    public function createProduk() {
        $kategoris = $this->getKategori();
        return view('pages.product.create-product', compact('kategoris'));
    }

    public function getKategori()
    {
        $client = new Client();
        $url = 'http://103.175.217.148/kategori/getallkategori';

        try {
            $response = $client->request('GET', $url);
            $data = json_decode($response->getBody(), true);

            if ($data['status'] == 200) {
                return array_map(function($item) {
                    return [
                        'idKategori' => $item['_id'],
                        'namakategori' => $item['namakategori']
                    ];
                }, $data['data']);  // Mengembalikan koleksi kategori
            } else {
                return []; // Jika gagal mendapatkan data, mengembalikan array kosong
            }
        } catch (\Exception $e) {
            return []; // Jika terjadi exception, mengembalikan array kosong
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaproduk' => 'required|string|max:30',
            'idKategori' => 'required',
            'deskripsi' => 'required|string|max:50',
            'stok' => 'required|integer',
            'harga' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
            $imagePath = $image->getPathname();
            $imageName = $image->getClientOriginalName();

        try {
    
            $response = Http::attach('image', file_get_contents($imagePath), $imageName)
                ->post('http://103.175.217.148/produk/create', [
                    'namaproduk' => $request->namaproduk,
                    'idKategori' => $request->idKategori,
                    'deskripsi' => $request->deskripsi,
                    'stok' => $request->stok,
                    'harga' => $request->harga,
                ]);
    
            if ($response->successful()) {

                $message = $response->json()['msg'];
                return redirect()->route('produk')->with('success', $message);
            } else {
                return back()->withErrors(['msg' => $message])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Gagal menambahkan Kategori: ' . $e->getMessage()])->withInput();
        }
        
    }


    public function editProduk($id) {
        $client =new Client();
        $response = $client->request('GET', 'http://103.175.217.148/produk/getprodukbyid/' . $id);
        $produk = json_decode($response->getBody()->getContents(), true);
        $kategoris = $this->getKategori();

        if($response->getStatusCode() !== 200) {
            return redirect()->back()->with('error', 'Produk Tidak Ditemukan');
        } 
        return view('pages.product.edit-product', compact('produk', 'kategoris'));
    }

    public function hapusProduk($id)
    {
        try {

            $response = Http::delete('http://103.175.217.148/produk/delete/' . $id);


            if ($response->status() === 200) {
                $message = $response->json()['msg'];

                return redirect()->route('produk')->with('success', $message);
            } elseif ($response->status() === 404) {
                $message = $response->json()['msg'];

                return redirect()->route('produk')->with('error', $message);
            } else {
                $message = $response->json()['msg'];

                return redirect()->route('produk')->with('error', $message);
            }
        } catch (\Exception $e) {
            return redirect()->route('produk')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateProduk(Request $request, $id)
    {
        $request->validate([
            'namaproduk' => 'required|string|max:30',
            'idKategori' => 'required',
            'deskripsi' => 'required|string|max:50',
            'stok' => 'required|integer',
            'harga' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // image tidak diwajibkan
        ]);

        $client = new Client();
        $url = 'http://103.175.217.148/produk/edit/' . $id;

        try {
            $data = [
                [
                    'name' => 'namaproduk',
                    'contents' => $request->input('namaproduk'),
                ],
                [
                    'name' => 'idKategori',
                    'contents' => $request->input('idKategori'),
                ],
                [
                    'name' => 'deskripsi',
                    'contents' => $request->input('deskripsi'),
                ],
                [
                    'name' => 'stok',
                    'contents' => $request->input('stok'),
                ],
                [
                    'name' => 'harga',
                    'contents' => $request->input('harga'),
                ],
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->getPathname();
                $imageName = $image->getClientOriginalName();

                $data[] = [
                    'name' => 'image',
                    'contents' => fopen($imagePath, 'r'),
                    'filename' => $imageName,
                ];
            }

            $response = $client->request('PUT', $url, [
                'multipart' => $data,
            ]);

            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody(), true);

            if ($statusCode == 200) {
                return redirect()->route('produk.edit', ['id' => $id])->with('success', $responseData['msg']);
            } else {
                return back()->withErrors(['msg' => $responseData['msg'] ?? 'Gagal memperbarui produk'])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Gagal memperbarui produk: ' . $e->getMessage()])->withInput();
        }
    }


}
