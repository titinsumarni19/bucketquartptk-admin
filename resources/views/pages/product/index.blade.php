@extends('layouts.base')
@section('title', 'Produk')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Daftar Produk</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{ route('produk.create') }}" class="btn btn-primary float-right">
            <i class="fa-solid fa-plus"></i>
            Tambah Produk
        </a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('produk') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request()->input('search') }}">
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
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Gambar</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produks as $p)
                    <tr>
                        <td>{{ $p['namaproduk'] }}</td>
                        <td>{{ $p['idKategori']['namakategori'] }}</td>
                        <td>{{ $p['deskripsi'] }}</td>
                        <td>{{ $p['stok'] }}</td>
                        <td>{{ $p['harga'] }}</td>
                        <td>{{ $p['image'] }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href='{{ route('produk.edit', $p['_id']) }}', 
                                    class="btn btn-sm btn-info btn-icon">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <form id="delete-form" action="{{ route('produk.delete', $p['_id']) }}" method="POST" class="ml-2">
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

<script>
    $(document).ready(function() {
        $('.confirm-delete').on('click', function(e) {
            e.preventDefault();
            var deleteForm = $(this).closest('td').find('delete-form');
            if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                deleteForm.submit();
            }
        });
    });
</script>

@endsection
