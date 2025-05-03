@extends('layouts.user_type.auth')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">

        <br>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>NAMA ALAT</h6>
                        <div class="nav-item">
                            <form action="{{ route('adminsystem.nama_alat.create') }}" method="GET" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
                                    Tambah
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Alat</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($nama_alats as $nama)
                                    <tr>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $nama->nama_alat }}</p>
                                        </td>


                                        <td class="align-middle text-center">
                                            <!-- Tombol Edit -->
                                            <a href="javascript:;"
                                                id="editBtn"
                                                onclick="editAction();"
                                                style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #FFA500, #FF6347); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px; margin-right: 8px;">
                                                <i style="margin-right: 4px; font-size: 12px;" class="fa fa-edit"></i> Edit
                                            </a>

                                            <!-- Tombol Delete (Send Action) -->
                                            <form action="{{ route('adminsystem.nama_alat.destroy', $nama->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Anda yakin akan menghapus nama alat ini?')"
                                                    title="Hapus nama alat"
                                                    style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(175, 48, 48),rgb(139, 46, 46)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;">
                                                    <i style="margin-right: 4px; font-size: 12px;" class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>

                                            <!-- Tambahkan font-awesome untuk ikon -->
                                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                                        </td>

                                        <script>
                                            function editAction() {
                                                // Redirect to the edit form for the item
                                                window.location.href = "{{ route('adminsystem.nama_alat.edit', $nama->id) }}";
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
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>DETAIL ALAT</h6>
                        <div class="nav-item">
                            <form action="{{ route('adminsystem.detail_alat.create') }}" method="GET" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
                                    Tambah
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Alat</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nomor Alat</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Inspeksi Terakhir</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Durasi Inspeksi</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Alat</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alats as $alat)
                                    <tr>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $alat->nama_alat }}</p>
                                        </td>

                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $alat->nomor }}</p>
                                        </td>

                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $alat->waktu_inspeksi }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $alat->durasi_inspeksi }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $alat->status }}</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <!-- Tombol Edit -->
                                            <a href="javascript:;"
                                                id="editBtn"
                                                onclick="editAction();"
                                                style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #FFA500, #FF6347); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px; margin-right: 8px;">
                                                <i style="margin-right: 4px; font-size: 12px;" class="fa fa-edit"></i> Edit
                                            </a>

                                            <!-- Tombol Delete (Send Action) -->
                                            <form action="{{ route('adminsystem.detail_alat.destroy', $alat->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Anda yakin akan menghapus alat ini?')"
                                                    title="Hapus Alat"
                                                    style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(175, 48, 48),rgb(139, 46, 46)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;">
                                                    <i style="margin-right: 4px; font-size: 12px;" class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>

                                            <!-- Tambahkan font-awesome untuk ikon -->
                                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                                        </td>

                                        <script>
                                            function editAction() {
                                                // Redirect to the edit form for the item
                                                window.location.href = "{{ route('adminsystem.detail_alat.edit', $alat->id) }}";
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
    </div>
  


</main>

@endsection