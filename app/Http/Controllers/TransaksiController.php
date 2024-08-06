<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Factory;
use Illuminate\Support\Facades\Http;

class TransaksiController extends Controller
{
    public function index() {
        $client = new Client();
        $url = '103.175.217.148/transaksi/getalltransaksi';

        try {
            $response = $client->request('GET', $url);
            $data = json_decode($response->getBody(), true);

            if ($data['status'] == 200) {
                $transaksis = array_filter($data['data'], function ($transaksi) {
                    return $transaksi['status'] >= 0 && $transaksi['status'] <= 5;
                });
                return view('pages.transaction.index', ['transaksis' => $transaksis]);
            } else {

                return back()->with('error', 'Gagal mendapatkan data');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendapatkan data: ' . $e->getMessage());
        }
    }

    public function editTransaksi($id)
    {
        $client =new Client();
        $url = '103.175.217.148/transaksi/gettransaksibyid/' . $id;

        try {
            $response = $client->request('GET', $url);
            $data = json_decode($response->getBody()->getContents(), true);

            if($response->getStatusCode() !== 200) {
                return redirect()->back()->with('error', 'Produk Tidak Ditemukan');
            }
            $transaksis = $data['data'];
            return view('pages.transaction.edit-transaction', ['transaksis' => $transaksis]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendapatkan data: ' . $e->getMessage());
        }

        

    }

    public function hapusTransaksi($id)
    {
        try {

            $response = Http::delete('103.175.217.148/transaksi/delete/' . $id);


            if ($response->status() === 200) {
                $message = $response->json()['msg'];

                return redirect()->route('transaksi')->with('success', $message);
            } elseif ($response->status() === 404) {
                $message = $response->json()['msg'];

                return redirect()->route('transaksi')->with('error', $message);
            } else {
                $message = $response->json()['msg'];

                return redirect()->route('transaksi')->with('error', $message);
            }
        } catch (\Exception $e) {
            return redirect()->route('transaksi')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        try {
            $client = new Client();
            $url = '103.175.217.148/transaksi/update/' . $id;

            $data = [
                'status' => $request->input('status'),
            ];

            $response = $client->request('PUT', $url, [
                'json' => $data,
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                return redirect()->route('transaksi')->with('success', $responseData['msg'] ?? 'Transaksi berhasil diperbarui');
            } else {
                return back()->withErrors(['msg' => $responseData['msg'] ?? 'Gagal memperbarui transaksi'])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Gagal memperbarui traksaksi: ' . $e->getMessage()])->withInput();
        }
    }
}
