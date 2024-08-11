@extends('layouts.base')

@section('title', 'Edit Produk')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="card">
                    <form action="{{ route('transaksi.update', $transaksis['_id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h4>Edit Data Transaksi</h4>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="form-group">
                                <label>Nama Pelanggan</label>
                                <input type="text" class="form-control @error('namalengkap') is-invalid @enderror"
                                    name="namalengkap" value="{{ old('idUser', $transaksis['idUser']['namalengkap']) }}" readonly>
                                @error('namalengkap')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Detail Lengkap</label>
                                @php
                                    $penerima = $transaksis['penerimaBaru'] ?? $transaksis['idUser']['namalengkap'];
                                    $telepon = $transaksis['teleponBaru'] ?? $transaksis['idUser']['telepon'];
                                    $alamat = $transaksis['alamatBaru'] ?? $transaksis['idUser']['alamat'];
                                    $detailLengkap = $penerima . ' | ' . $telepon . ' | ' . $alamat;
                                @endphp
                                <input type="text" class="form-control @error('detailLengkap') is-invalid @enderror"
                                    name="detailLengkap" value="{{ old('detailLengkap', $detailLengkap) }}" readonly>
                                @error('telpon&alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            @php
                                $produkpembelian = '';

                                if (isset($transaksis['produkItems']) && is_array($transaksis['produkItems'])) {
                                    foreach ($transaksis['produkItems'] as $item) {
                                        if (isset($item['idProduk']) && is_array($item['idProduk'])) {
                                            $produkpembelian .= $item['idProduk']['namaproduk'] ?? 'Produk Tidak ada';
                                        } else {
                                            $produkpembelian .= 'Produk Tidak ada 1';
                                        }
                                        $produkpembelian .= ' -- Jumlah:' . ($item['kuantitas'] ?? '0') . "\n";
                                    }
                                } else {
                                    $produkpembelian = 'Produk Tidak Ditemukan';
                                }
                            @endphp
                            <div class="form-group">
                                <label>Produk Pembelian</label>
                                <textarea id="produk" name="produk" class="form-control @error('produkpembelian') is-invalid @enderror"
                                readonly>{!! old('produkpembelian', $produkpembelian) !!}</textarea>                
                                @error('produkpembelian')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Total Transaksi</label>
                                <input type="text" class="form-control @error('totaltransaksi') is-invalid @enderror"
                                    name="totaltransaksi" value="Rp.{{ old('totaltransaksi', $transaksis['totaltransaksi']) }}" readonly>
                                @error('totaltransaksi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control select2" name="status">
                                    <option value="0">Pesanan Bucket Diterima</option>
                                    <option value="1">Bucket Sedang Disiapkan</option>
                                    <option value="2">Bucket Sedang Dikemas</option>
                                    <option value="3">Bucket Sudah Diserahkan ke Kurir</option>
                                    <option value="4">Bucket Sedang dalam perjalanan</option>
                                    <option value="5">Pesanan Sampai di Lokasi Pengiriman</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Bukti Pembayaran</label>
                                @if($transaksis['buktiPembayaran'])
                                    <div>
                                        <img src="http://localhost:5001/static/{{ $transaksis['buktiPembayaran'] }}" alt="Current Image" style="max-width: 200px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->

    <!-- Page Specific JS File -->
@endpush
