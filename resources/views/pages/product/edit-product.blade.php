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
                    <form action="{{ route('produk.update', $produk['data']['_id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h4>Edit Data Produk</h4>
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
                                <label>Nama Produk</label>
                                <input type="text" class="form-control @error('namaproduk') is-invalid @enderror"
                                    name="namaproduk" value="{{ old('namaproduk', $produk['data']['namaproduk']) }}">
                                @error('namaproduk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Kategori</label>
                                <select class="form-control @error('idKategori') is-invalid @enderror" name="idKategori">
                                    @foreach($kategoris as $k)
                                    <option value="{{ $k['idKategori'] }}" {{ old('idKategori', $produk['data']['idKategori']['_id']) == $k['idKategori'] ? 'selected' : '' }}>
                                            {{ $k['namakategori'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idKategori')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <input type="text" class="form-control @error('deskripsi') is-invalid @enderror"
                                    name="deskripsi" value="{{ old('deskripsi', $produk['data']['deskripsi']) }}">
                                @error('deskripsi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="text" class="form-control @error('stok') is-invalid @enderror"
                                    name="stok" value="{{ old('stok', $produk['data']['stok']) }}">
                                @error('stok')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Harga</label>
                                <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                    name="harga" value="{{ old('harga', $produk['data']['harga']) }}">
                                @error('harga')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" class="form-control-file @error('image') is-invalid @enderror"
                                    name="image">
                                @error('image')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Gambar Produk Saat Ini</label>
                                @if($produk['data']['image'])
                                    <div>
                                        <img src="http://localhost:5001/static/{{ $produk['data']['image'] }}" alt="Current Image" style="max-width: 200px;">
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
