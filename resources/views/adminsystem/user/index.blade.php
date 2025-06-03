@extends('layouts.user_type.auth')

@section('content')
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin: 10px;">
  {{ session('success') }}
</div>
@endif
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
  <div class="container-fluid py-4">
    <div class="nav-item d-flex align-self-end">
      <form action="{{ route('adminsystem.user.create') }}" method="GET" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-sm btn-primary active mb-0 text-white" role="button" aria-pressed="true">

          Tambah
        </button>
      </form>
    </div>
    <br>
    <div class="row">
      <div class="col-12">
        <div class="card mb-4">
          <div class="card-header pb-0">
            <h6>Users List</h6>
          </div>
          <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-3">
              <table class="table align-items-center mb-0" id="dataTable">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Password</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($users as $user)
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img src="{{ asset('assets/img/user.png') }}" class="avatar avatar-sm me-3" alt="user">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                          <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm @if($user->role == 'adminsystem') bg-gradient-success @elseif($user->role == 'operator') bg-gradient-primary @else bg-gradient-secondary @endif">
                        {{ ucfirst($user->role) }}
                      </span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <!-- Display the plain password if available from session -->
                      <p class="text-xs font-weight-bold mb-0">
                        {{ session('plain_password') ? session('plain_password') : '******' }}
                      </p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <a href="{{ route('adminsystem.user.edit', $user->id) }}"
                        class="btn btn-warning btn-xs mb-2"> <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit

                      </a>

                      <!-- Tombol Send (Delete Action) -->
                      <form action="{{ route('adminsystem.user.destroy', $user->id) }}" method="POST" style="display:inline;">
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