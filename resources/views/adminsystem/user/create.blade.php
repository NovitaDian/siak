@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">Create User</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Create New User') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('adminsystem.user.store') }}" method="POST" role="form text-left">
                    @csrf

                    @if($errors->any())
                    <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">{{ $errors->first() }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif

                    <!-- Name Input -->
                    <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>
                        <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email">{{ __('Email') }}</label>
                        <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input class="form-control" type="password" id="password" name="password" required>
                        @error('password')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation Input -->
                    <div class="form-group">
                        <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                        <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Select -->
                    <div class="form-group">
                        <label for="role">{{ __('Role') }}</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="adminsystem" {{ old('role') == 'adminsystem' ? 'selected' : '' }}>Admin System</option>
                            <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                            <option value="tamu" {{ old('role') == 'tamu' ? 'selected' : '' }}>Guest</option>
                        </select>
                        @error('role')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Create User') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection