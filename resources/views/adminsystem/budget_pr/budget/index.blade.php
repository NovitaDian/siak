@extends('layouts.user_type.auth')

@section('content')
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin: 10px;">
    {{ session('success') }}
</div>
@endif
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Budget</h6>
                    <form action="{{ route('adminsystem.budget.create') }}" method="GET">
                        <button type="submit" class="btn btn-sm btn-primary mb-0">
                            Tambah
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <div class="card-header pb-0">
                        <table class="table align-items-center mb-0" id="search">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode GL Account</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama GL Account</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Internal Order</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total 1 Tahun</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($budgets as $budget)
                                <tr>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $budget->gl_code }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $budget->gl_name }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $budget->internal_order ??'-'}}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $budget->setahun_total}}</p>
                                    </td>

                                    <td class="align-middle text-center">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('adminsystem.budget.edit', $budget->id) }}"
                                            class="btn btn-warning btn-xs mb-2"> <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit

                                        </a>

                                        <!-- Tombol Send (Delete Action) -->
                                        <form action="{{ route('adminsystem.budget.destroy', $budget->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Anda yakin akan menghapus dokumen?')"
                                                title="Kirim"
                                                class="btn btn-danger btn-xs mb-2"> <i class="fas fa-trash me-1" style="font-size: 12px;"></i> Hapus
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

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize data tables
        $('#search').DataTable({
            pageLength: 5,
            searching: true,
            ordering: true,
            responsive: true
        });


    });
</script>
@endsection