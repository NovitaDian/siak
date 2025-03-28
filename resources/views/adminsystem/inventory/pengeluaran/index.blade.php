@extends('layouts.user_type.auth')

@section('content')
<div class="nav-item d-flex align-self-end">
    <form action="{{ route('adminsystem.pengeluaran.create') }}" method="GET" style="display:inline;">
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
                <h6>Data Pengeluaran</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Barang</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengeluarans as $pengeluaran)
                            <tr>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pengeluaran->barang_id }}</p>
                                </td>


                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d/m/Y') }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pengeluaran->quantity }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pengeluaran->unit }}</p>
                                </td>

                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pengeluaran->keterangan }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <!-- Tombol Edit -->
                                    <a href="javascript:;"
                                        id="editBtn"
                                        onclick="editAction();"
                                        style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #FFA500, #FF6347); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px; margin-right: 8px;">
                                        <i style="margin-right: 4px; font-size: 12px;" class="fa fa-edit"></i> Edit
                                    </a>

                                    <!-- Tombol Send (Delete Action) -->
                                    <form action="{{ route('adminsystem.pengeluaran.destroy', $pengeluaran->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm"
                                            onclick="return confirm('Anda yakin akan menghapus dokumen?')"
                                            title="Hapus"
                                            style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #FF4C4C, #FF0000); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;">
                                            <i style="margin-right: 4px; font-size: 12px;" class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>


                                    <!-- Tambahkan font-awesome untuk ikon -->
                                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                                </td>

                                <script>
                                    function editAction() {
                                        // Redirect to the edit form for the item
                                        window.location.href = "{{ route('adminsystem.pengeluaran.edit', $pengeluaran->id) }}";
                                    }
                                </script>
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
@endsection