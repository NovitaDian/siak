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
                <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
            </form>

            <hr>

            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-3">
                    <table class="table align-items-center mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama File</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ukuran File</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Unggah</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>

                                <th class="text-secondary opacity-7"></th>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                            <tr>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0"><a href="{{ asset('storage/' . $document->file_path) }}" target="_blank">{{ $document->file_name }}</a></p>
                                </td>

                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $document->file_size }} KB
                                </td>

                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $document->created_at }}
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('adminsystem.references.destroy', $document->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-danger btn-xs"
                                            onclick="return confirm('Anda yakin akan menghapus data ini?')">
                                            <i class="fas fa-trash-alt me-1" style="font-size: 12px;"></i> Delete
                                        </button>
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
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            ordering: true,
            searching: true,
            info: true,
            paging: true,
            responsive: true
        });
    });
</script>
@endsection