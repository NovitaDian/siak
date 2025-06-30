@extends('layouts.user_type.guest')

@section('content')


<main class="main-content  mt-0">
  <section>
    <div class="page-header min-vh-75">
      <div class="container">
        <div class="row">
          <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
            <div class="card card-plain mt-8">
              <div class="card-header pb-0 text-left bg-transparent">
                <div class="d-flex justify-content-between align-items-center mb-4">
                  <img src="{{ asset('assets/img/logos/logo_mitr_phol.png') }}" alt="Mitr Phol" style="height:30px;">
                  <img src="{{ asset('assets/img/logos/hse.png') }}" alt="HSE" style="height:40px;">
                  <img src="{{ asset('assets/img/logos/pt_dus.png') }}" alt="PT.DUS" style="height:40px;">
                </div>
                <h3 class="font-weight-bolder text-info text-gradient">Selamat Datang Kembali di SIAK3!</h3>
                <p class="mb-0">Buat akun baru<br></p>
                <p class="mb-0">Atau masuk dengan akun yang sudah ada</p>
              </div>
              <div class="card-body">
                <form role="form" method="POST" action="/session">
                  @csrf
                  <label>Email</label>
                  <div class="mb-3">
                    <input type="email" class="form-control" name="email" id="email" placeholder="example@gmail.com" aria-label="Email" aria-describedby="email-addon">
                    @error('email')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                  </div>
                  <label>Password</label>
                  <div class="mb-3">
                    <input class="form-control" type="password" id="password" name="password" required>
                    @error('password')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                    <label class="form-check-label" for="rememberMe">Ingat saya</label>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign in</button>
                  </div>
                </form>
              </div>
              <div class="card-footer text-center pt-0 px-lg-2 px-1">
                <small class="text-muted">Lupa password?
                  <a href="/login/forgot-password" class="text-info text-gradient font-weight-bold">Di sini</a>
                </small>
                <p class="mb-4 text-sm mx-auto">
                  Belum memiliki akun?
                  <a href="register" class="text-info text-gradient font-weight-bold">Sign up</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
              <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../assets/img/login.jpg')"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

@endsection