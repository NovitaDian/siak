@extends('layouts.user_type.tamu')

@section('content')

<div>
    <div class="container-fluid">
        <!-- Page Header Section -->
        <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
            <span class="mask bg-gradient-primary opacity-6"></span>
        </div>

        <!-- Profile Card Section -->
        <div class="card card-body blur shadow-blur mx-4 mt-n6">
            <div class="row gx-4">
                <div class="col-auto">
                    
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <p class="mb-0 font-weight-bold text-sm">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Form Section -->
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Profile Information') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
            <form action="{{ route('tamu.info_user.store')}}" method="POST">
            @csrf
                    @if($errors->any())
                    <div class="mt-3  alert alert-primary alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">{{ $errors->first() }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                        <span class="alert-text text-white">{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif

                    <!-- Full Name Input -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('Full Name') }}</label>
                                <div class="@error('name') border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ old('name', auth()->user()->name) }}" type="text" placeholder="Name" id="user-name" name="name">
                                    @error('name')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Email Input -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-email" class="form-control-label">{{ __('Email') }}</label>
                                <div class="@error('email') border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ old('email', auth()->user()->email) }}" type="email" placeholder="@example.com" id="user-email" name="email">
                                    @error('email')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Phone Input -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-phone" class="form-control-label">{{ __('Phone') }}</label>
                                <div class="@error('phone') border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ old('phone', auth()->user()->phone) }}" type="tel" placeholder="40770888444" id="user-phone" name="phone">
                                    @error('phone')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Location Input -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-location" class="form-control-label">{{ __('Location') }}</label>
                                <div class="@error('location') border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ old('location', auth()->user()->location) }}" type="text" placeholder="Location" id="user-location" name="location">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- About Me Textarea -->
                    <div class="form-group">
                        <label for="about">{{ 'About Me' }}</label>
                        <div class="@error('about_me') border border-danger rounded-3 @enderror">
                            <textarea class="form-control" id="about" rows="3" placeholder="Say something about yourself" name="about_me">{{ old('about_me', auth()->user()->about_me) }}</textarea>
                        </div>
                    </div>

                    <!-- Save Changes Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-primary btn-lg">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
