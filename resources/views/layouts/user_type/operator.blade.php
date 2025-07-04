@extends('layouts.app')

@section('operator')


    @if(\Request::is('static-sign-up')) 
        @include('layouts.navbars.guest.nav')
        @yield('content')
    
    @elseif (\Request::is('static-sign-in')) 
        @include('layouts.navbars.guest.nav')
            @yield('content')
    
    @else
        @if (\Request::is('rtl'))  
            @include('layouts.navbars.operator.sidebar-rtl')
            <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg overflow-hidden">
                @include('layouts.navbars.operator.nav-rtl')
                <div class="container-fluid py-4">
                    @yield('content')
                    @include('layouts.footers.operator.footer')
                </div>
            </main>

        @elseif (\Request::is('profile'))  
            @include('layouts.navbars.operator.sidebar')
            <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
                @include('layouts.navbars.operator.nav')
                @yield('content')
            </div>

        @elseif (\Request::is('virtual-reality')) 
            @include('layouts.navbars.operator.nav')
            <div class="border-radius-xl mt-3 mx-3 position-relative" style="background-image: url('../assets/img/vr-bg.jpg') ; background-size: cover;">
                @include('layouts.navbars.operator.sidebar')
                <main class="main-content mt-1 border-radius-lg">
                    @yield('content')
                </main>
            </div>
            @include('layouts.footers.operator.footer')

        @else
            @include('layouts.navbars.operator.sidebar')
            <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg {{ (Request::is('rtl') ? 'overflow-hidden' : '') }}">
                @include('layouts.navbars.operator.nav')
                <div class="container-fluid py-4">
                    @yield('content')
                    @include('layouts.footers.operator.footer')
                </div>
            </main>
        @endif

        @include('components.fixed-plugin')
    @endif

    

@endsection