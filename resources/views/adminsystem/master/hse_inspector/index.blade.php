@extends('layouts.user_type.auth')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">

        <br>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>DATA INSPEKTOR HSE</h6>
                        <div class="nav-item">
                            <form action="{{ route('adminsystem.hse_inspector.create') }}" method="GET" style="display:inline;">
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
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jabatan</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hse_inspectors as $inspector)
                                    <tr>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $inspector->name }}</p>
                                        </td>

                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $inspector->jabatan }}</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('adminsystem.hse_inspector.edit', $inspector->id) }}"
                                                style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #FFA500, #FF6347); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px; margin-right: 8px;">
                                                <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit

                                            </a>

                                            <!-- Tombol Delete -->
                                            <form action="{{ route('adminsystem.hse_inspector.destroy', $inspector->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Anda yakin ingin menghapus data ini?')"
                                                    style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(175, 48, 48),rgb(139, 46, 46)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;">
                                                    <i style="margin-right: 4px; font-size: 12px;"></i> Delete
                                                </button>
                                            </form>

                                            <!-- Font Awesome -->
                                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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