@extends('layouts.base')
@section('title', 'Transaksi')
@section('content')
<h1 class="h3 mb-4 text-gray-800">Transaksi</h1>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Transaksi</h6>
    </div>

    <div class="card-body">
    <form method="GET" action="{{ route('transaksi') }}" class="mb-4">
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
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksis as $t)
                    <tr>
                        <td>{{ $t['idUser']['namalengkap'] }}</td>
                        <td>{{ $t['idUser']['telepon'] }} | {{ $t['idUser']['alamat'] }}</td>
                        <td>@if(isset($t['produkItems']) && is_array($t['produkItems']))
                                @foreach($t['produkItems'] as $item)
                                    @if(isset($item['idProduk']) && is_array($item['idProduk']))
                                        {{ $item['idProduk']['namaproduk'] ?? 'Produk Tidak ada' }} ({{ $item['kuantitas'] ?? '0' }})<br>
                                    @else
                                        {{ 'Produk Tidak ada 1' }} ({{ $item['kuantitas'] ?? '0' }})<br>
                                    @endif
                                @endforeach
                            @else
                                Produk Tidak Ditemukan
                            @endif</td>
                        <td>Rp.{{ $t['totaltransaksi'] }}</td>
                        <td>
                            @if( $t['status']  == 0)
                            Pesanan bucket diterima
                            @endif
                            @if( $t['status']  == 1)
                            Bucket Sedang Disiapkan
                            @endif
                            @if( $t['status']  == 2)
                            Bucket Sedang Dikemas
                            @endif
                            @if( $t['status']  == 3)
                            Bucket Sudah Diserahkan ke Kurir
                            @endif
                            @if( $t['status']  == 4)
                            Bucket Sedang dalam perjalanan
                            @endif
                            @if( $t['status']  == 5)
                            Pesanan Sampai di Lokasi Pengiriman
                            @endif
                            @if( $t['status']  == 6)
                            Pesanan Selesai
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href='{{ route('transaksi.edit', $t['_id']) }}'
                                    class="btn btn-sm btn-info btn-icon">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <form id="delete-form" action="{{ route('transaksi.delete', $t['_id']) }}" method="POST" class="ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger btn-icon confirm-delete">
                                        <i class="fas fa-times"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
