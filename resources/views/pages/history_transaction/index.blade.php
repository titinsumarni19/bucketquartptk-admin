@extends('layouts.base')
@section('title', 'Riwayat Transaksi')
@section('content')
<h1 class="h3 mb-4 text-gray-800">Riwayat Transaksi</h1>
<div class="card shadow mb-4">

    <div class="card-body">
    <form method="GET" action="{{ route('riwayat-transaksi') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Transaksi..." value="{{ request()->input('search') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>No.Handphone & Alamat</th>
                        <th>Nama Produk</th>
                        <th>Total Transaksi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksis as $t)
                    <tr>
                        <td>{{ $t['idUser']['namalengkap'] }}</td>
                        <td>{{ $t['idUser']['telepon'] }} | {{ $t['idUser']['alamat'] }}</td>
                        <td>
                            @if(isset($t['produkItems']) && is_array($t['produkItems']))
                                @foreach($t['produkItems'] as $item)
                                    @if(isset($item['idProduk']) && is_array($item['idProduk']))
                                        {{ $item['idProduk']['namaproduk'] ?? 'Produk Tidak ada' }} ({{ $item['kuantitas'] ?? '0' }})<br>
                                    @else
                                        {{ 'Produk Tidak ada 1' }} ({{ $item['kuantitas'] ?? '0' }})<br>
                                    @endif
                                @endforeach
                            @else
                                Produk Tidak Ditemukan
                            @endif
                        </td>
                        <td>{{ $t['totaltransaksi'] }}</td>
                        <td>
                            @if( $t['status']  == 6)
                            Pesanan Selesai
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
