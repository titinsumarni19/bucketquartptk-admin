@extends('layouts.base')
@section('title','Kategori')
@section('content')
<h1 class="h3 mb-4 text-gray-800">Daftar Kategori</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Kategori</h6>
        <a href="{{ route('kategori.create') }}" class="btn btn-primary float-right">
            <i class="fa-solid fa-plus"></i>
            Tambah Kategori
        </a>
    </div>
    <div class="card-body">
         <form method="GET" action="{{ route('kategori') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Kategori..." value="{{ request()->input('search') }}">
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
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategoris as $k)
                    <tr>
                        <td>{{ $k['namakategori'] }}</td>
                        <td>{{ $k['deskripsi'] }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('kategori.edit', $k['_id']) }}"
                                    class="btn btn-sm btn-info btn-icon">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>

                                <form id="delete-form" action="{{ route('kategori.delete', $k['_id']) }}" method="POST" class="ml-2">
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
