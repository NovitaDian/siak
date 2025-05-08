@extends('layouts.user_type.auth')

@section('content')

<div class="row">
    <!-- First row with 1 column for Master Data -->
    <div class="row">
        <!-- First row with 1 column for Master Data -->
        <div class="col-12">
            <div class="card h-100 p-3">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">MASTER DATA</h6>
                </div>
                <div class="card-body pt-4 p-3">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-primary w-100" onclick="location.href='{{ route('adminsystem.material_group.index') }}'">
                                <i class="fas fa-building"></i> Material Group
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-primary w-100" onclick="location.href='{{ route('adminsystem.unit.index') }}'">
                                <i class="fas fa-cogs"></i> Unit
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-primary w-100" onclick="location.href='{{ route('adminsystem.glaccount.index') }}'">
                                <i class="fas fa-money-check-alt"></i> GL Account
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-primary w-100" onclick="location.href='{{ route('adminsystem.purchasinggroup.index') }}'">
                                <i class="fas fa-shopping-cart"></i> Purchasing Group
                            </button>
                        </div>
                    </div>
                    <p>Detail master data di sini...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</div>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<br>
<br>
<div class="nav-item d-flex align-self-end">
    <form action="{{ route('adminsystem.pr.create') }}" method="GET" style="display:inline;">
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
                <h6>Purchase Request</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PR Date</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material Group</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PR No</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material Code</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Short Text</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valuation Price</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">GL Account</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prs as $pr)
                            <tr>
                                <td class="text-center">
                                    <h6 class="mb-0 text-sm">{{ \Carbon\Carbon::parse($pr->pr_date)->format('d/m/Y') }}</h6>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pr->material_group }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pr->pr_no }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pr->material_code }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pr->short_text }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pr->quantity }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pr->unit }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ number_format($pr->valuation_price, 2) }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pr->gl_account }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('adminsystem.pr.edit', $pr->id) }}"
                                        style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #FFA500, #FF6347); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px; margin-right: 8px;">
                                        <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit

                                    </a>

                                    <!-- Tombol Send (Delete Action) -->
                                    <form action="{{ route('adminsystem.pr.destroy', $pr->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm"
                                            onclick="return confirm('Anda yakin akan menghapus dokumen?')"
                                            title="Kirim"
                                            style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(240, 57, 57),rgb(171, 57, 57)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;">
                                            <i style="margin-right: 4px; font-size: 12px;"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($prs->isEmpty())
                    <div class="text-center p-4">
                        <p class="text-secondary">Tidak ada data Purchase Request.</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">





        @endsection