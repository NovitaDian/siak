@extends('layouts.user_type.auth')

@section('content')

<br><br><br>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Purchase Request</h6>
                    <form action="{{ route('adminsystem.pr.create') }}" method="GET">
                        <button type="submit" class="btn btn-primary mb-0">
                            Tambah
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <div class="card-header pb-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PR Date</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PR No</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material/Jasa</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Short Text</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valuation Price</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">GL Account</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($prs as $pr)
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
                                        <p class="text-xs font-weight-bold mb-0">{{ $pr->material }}</p>
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
                                        <p class="text-xs font-weight-bold mb-0">{{ $pr->gl_code }}-{{ $pr->gl_name}}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('adminsystem.pr.edit', $pr->id) }}"
                                            class="btn btn-sm btn-warning me-1"
                                            style="font-size: 10px; padding: 4px 8px;">
                                            <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit
                                        </a>

                                        <form action="{{ route('adminsystem.pr.destroy', $pr->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Anda yakin akan menghapus dokumen?')"
                                                style="font-size: 10px; padding: 4px 8px;">
                                                <i class="fas fa-trash-alt me-1" style="font-size: 12px;"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-secondary py-4">
                                        Tidak ada data Purchase Request.
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
</div>

<!-- Load Font Awesome once -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@endsection