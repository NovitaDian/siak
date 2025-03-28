@extends('layouts.user_type.auth')

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin: 10px;">
        {{ session('success') }}
    </div>
@endif

<div class="nav-item d-flex align-self-end">
    <form action="{{ route('adminsystem.glaccount.create') }}" method="GET" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
            Tambah
        </button>
    </form>
</div>
<br>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>GL Account</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode GL Account</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama GL Account</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>

                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gls as $glAccount)
                            <tr>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $glAccount->gl_code }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $glAccount->gl_name }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $glAccount->description }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('adminsystem.glaccount.edit', $glAccount->id) }}"
                                        style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #FFA500, #FF6347); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px; margin-right: 8px;">
                                        <i style="margin-right: 4px; font-size: 12px;" class="fa fa-edit"></i> Edit
                                    </a>

                                    <!-- Tombol Send (Delete Action) -->
                                    <form action="{{ route('adminsystem.glaccount.destroy', $glAccount->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm"
                                            onclick="return confirm('Anda yakin akan menghapus dokumen?')"
                                            title="Kirim"
                                            style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(240, 57, 57),rgb(171, 57, 57)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;">
                                            <i style="margin-right: 4px; font-size: 12px;" class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($gls->isEmpty())
                    <div class="text-center p-4">
                        <p class="text-secondary">Tidak ada data GL Account.</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

@endsection
