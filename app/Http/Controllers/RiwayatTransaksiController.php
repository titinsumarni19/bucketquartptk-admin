<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Factory;
use Illuminate\Support\Facades\Http;

class RiwayatTransaksiController extends Controller
{
    public function index() {
        $client = new Client();
        $url = 'http://103.175.217.148/transaksi/getalltransaksi';

        try {
            $response = $client->request('GET', $url);
            $data = json_decode($response->getBody(), true);

            if ($data['status'] == 200) {
                $transaksis = array_filter($data['data'], function ($transaksi) {
                    return $transaksi['status'] == 6;
                });
                return view('pages.history_transaction.index', ['transaksis' => $transaksis]);
            } else {

                return back()->with('error', 'Gagal mendapatkan data');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendapatkan data: ' . $e->getMessage());
        }
    }

    public function hapusRiwayat($id)
    {
        try {

            $response = Http::delete('http://103.175.217.148/transaksi/delete/' . $id);


            if ($response->status() === 200) {
                $message = $response->json()['msg'];

                return back()->with('success', $message);
            } elseif ($response->status() === 404) {
                $message = $response->json()['msg'];

                return back()->with('error', $message);
            } else {
                $message = $response->json()['msg'];

                return back()->with('error', $message);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
}
