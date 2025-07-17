<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route('operator.dashboard') }}">
      <img src="{{ asset('assets/img/logos/logo hse.png') }}" class="navbar-brand-img h-100" alt="...">
      <span class="ms-3 font-weight-bold">SIAK 3</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      @php
      $homePaths = [
      'operator/home',
      'operator/home/*',
      'operator/incident',
      'operator/incident/*',
      'operator/ppe',
      'operator/ppe/*',
      'operator/ncr',
      'operator/ncr/*',
      'operator/tool',
      'operator/tool/*',
      'operator/daily',
      'operator/daily/*',
      ];
      @endphp

      <li class="nav-item">
        <a class="nav-link 
        @foreach ($homePaths as $path)
            @if (Request::is($path))
                active
                @break
            @endif
        @endforeach" href="{{ route('operator.home') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <img src="{{ asset('../assets/img/house-chimney-user.png') }}" alt="home" width="16" height="16">
          </div>
          <span class="nav-link-text ms-1">Home</span>
        </a>
      </li>
      @php
      $dashboardPaths = [
      'operator/dashboard',
      'operator/dashboard/*',
      'operator/dashboard-*',
      ];
      @endphp

      <li class="nav-item">
        <a class="nav-link 
        @foreach ($dashboardPaths as $path)
            @if (Request::is($path))
                active
                @break
            @endif
        @endforeach" href="{{ url('operator/dashboard') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <img src="{{ asset('../assets/img/dashboard.png') }}" alt="dashboard" width="16" height="16">
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      @php
      $budgetPaths = [
      'operator/budget',
      'operator/budget/*',
      'operator/pr',
      'operator/pr/*',
      ];
      @endphp

      <li class="nav-item">
        <a class="nav-link 
        @foreach ($budgetPaths as $path)
            @if (Request::is($path))
                active
                @break
            @endif
        @endforeach " href="{{ url('operator/budget') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <img src="{{ asset('../assets/img/money.png') }}" alt="budget" width="16" height="16">
          </div>
          <span class="nav-link-text ms-1">Budget & PR</span>
        </a>
      </li>
      @php
      $inventoryPaths = [
      'operator/inventory',
      'operator/inventory/*',
      'operator/pemasukan',
      'operator/pemasukan/*',
      'operator/pengeluaran',
      'operator/pengeluaran/*',
      ];
      @endphp

      <li class="nav-item">
        <a class="nav-link 
        @foreach ($inventoryPaths as $path)
            @if (Request::is($path))
                active
                @break
            @endif
        @endforeach" href="{{ url('operator/inventory') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <img src="{{ asset('../assets/img/box.png') }}" alt="inventory" width="16" height="16">
          </div>
          <span class="nav-link-text ms-1">Inventory</span>
        </a>
      </li>
      @php
      $masterPaths = [
      'operator/master',
      'operator/master/*',
      'operator/glaccount',
      'operator/glaccount/*',
      'operator/material_group',
      'operator/material_group/*',
      'operator/unit',
      'operator/unit/*',
      'operator/hse_inspector',
      'operator/hse_inspector/*',
      'operator/hari_hilang',
      'operator/hari_hilang/*',
      ];
      @endphp

      <li class="nav-item">
        <a class="nav-link 
        @foreach ($masterPaths as $path)
            @if (Request::is($path))
                active
                @break
            @endif
        @endforeach
    " href="{{ url('operator/master') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <img src="{{ asset('../assets/img/newspaper.png') }}" alt="master" width="16" height="16">
          </div>
          <span class="nav-link-text ms-1">Master</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ Request::is('operator/references') ? 'active' : '' }}" href="{{ url('operator/references') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <img src="{{ asset('../assets/img/book.png') }}" alt="references" width="16" height="16">
          </div>
          <span class="nav-link-text ms-1">References</span>
        </a>
      </li>
      <li class="nav-item mt-2">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">User </h6>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('operator/info_user') ? 'active' : '') }} " href="{{ route('operator.info_user.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <svg width="12px" height="12px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <title>customer-support</title>
              <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Rounded-Icons" transform="translate(-1717.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                  <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                    <g id="customer-support" transform="translate(1.000000, 0.000000)">
                      <path class="color-background" d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z" id="Path" opacity="0.59858631"></path>
                      <path class="color-foreground" d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z" id="Path"></path>
                      <path class="color-foreground" d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z" id="Path"></path>
                    </g>
                  </g>
                </g>
              </g>
            </svg>
          </div>
          <span class="nav-link-text ms-1">User Profile</span>
        </a>
      </li>
      


    </ul>
  </div>
  <div class="sidenav-footer mx-3 mt-3">
    <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
      <div class="full-background" style="background-image: url('../assets/img/curved-images/white-curved.jpeg')"></div>
      <div class="card-body text-start p-3 w-100 mb-0">
        <div class="docs-info">
          <h6 class="text-white up mb-0">Butuh Bantuan?</h6>
          <p class="text-xs font-weight-bold">Lihat dokumentasi kami</p>
          <a href="/assets/manual_book/ADMIN.pdf" target="_blank" class="btn btn-white btn-sm w-100 mb-0">Documentation</a>
        </div>
      </div>
    </div>
  </div>
</aside>