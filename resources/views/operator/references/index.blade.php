@extends('layouts.user_type.operator')

@section('content')

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">References</h6>
                </div>

                <div class="card-body">

                    <form action="{{ route('operator.references.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <div class="form-group">
                            <label for="file">Pilih File:</label>
                            <input type="file" class="form-control" name="file" id="file">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary mt-2">Tambah</button>
                    </form>

                    <div class="table-responsive p-3">
                        <table class="table align-items-center mb-0" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama File</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ukuran File</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Unggah</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $document)
                                <tr>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank">
                                                {{ $document->file_name }}
                                            </a>
                                        </p>
                                    </td>

                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $document->file_size }} KB</p>
                                    </td>

                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $document->created_at->format('d-m-Y H:i') }}</p>
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('operator.references.destroy', $document->id) }}" method="POST" onsubmit="return confirm('Anda yakin akan menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs">
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
</main>

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