@extends('layouts.user_type.operator')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin: 10px;">
        {{ session('success') }}
    </div>
@endif

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">

                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">GL Account</h6>
                        <form action="{{ route('operator.glaccount.create') }}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary text-white">
                                Tambah
                            </button>
                        </form>
                    </div>

                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table class="table align-items-center mb-0" id="dataTable">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode GL Account</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama GL Account</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($gls as $glAccount)
                                    <tr>
                                        <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $glAccount->gl_code }}</p></td>
                                        <td class="text-center"><p class="text-xs font-weight-bold mb-0">{{ $glAccount->gl_name }}</p></td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Tombol Edit -->
                                                <a href="{{ route('operator.glaccount.edit', $glAccount->id) }}" class="btn btn-warning btn-xs">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>

                                                <!-- Tombol Delete -->
                                                <form action="{{ route('operator.glaccount.destroy', $glAccount->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs"
                                                        onclick="return confirm('Anda yakin ingin menghapus GL Account ini?')">
                                                        <i class="fas fa-trash-alt me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-3">
                                            Tidak ada data GL Account.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

</main>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- DataTables & jQuery -->
<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
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
