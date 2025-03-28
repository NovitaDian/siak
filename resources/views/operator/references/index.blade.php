@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Dokumen Referensi</h1>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('adminsystem.references.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="file">Pilih File:</label>
                    <input type="file" class="form-control-file" name="file" id="file">
                </div>
                <button type="submit" class="btn btn-primary">Unggah</button>
            </form>

            <hr>

            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama File</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Unggah</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>

                                <th class="text-secondary opacity-7"></th>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                            <tr>
                                <td><a href="{{ asset('storage/' . $document->file_path) }}" target="_blank">{{ $document->file_name }}</a></td>
                                <td>{{ $document->file_size }} KB</td> {{-- Format ukuran file --}}
                                <td>{{ $document->created_at }}</td>
                                <td>
                                    <form action="{{ route('adminsystem.references.destroy', $document->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection