@extends('layouts.user_type.auth')

@section('content')

<div class="row">
    <!-- First row with 3 columns -->
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">INCIDENT & ACCIDENT</h5>
                    <br>
                    <div class="mb-3 text-center">
                        <img src="../assets/img/work-injury.png" alt="Work Injury Logo" class="img-fluid" style="max-width: 100px;">
                    </div>
                    <!-- MASTER Button (Red) -->
                    <button class="btn text-sm font-weight-bold mb-0 icon-move-right mt-auto" style="background-color: #8E1616; color: white;" onclick="location.href='{{ route('adminsystem.incident.master') }}'">
                        MASTER
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>
                    <br>
                    <!-- DATA Button (Green) -->
                    <button class="btn text-sm font-weight-bold mb-0 icon-move-right mt-auto" style="background-color: #3E7B27; color: white;" onclick="location.href='{{ route('adminsystem.incident.index') }}'">
                        DATA
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>

                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">SAFETY BEHAVIOR & PPE COMPLIANCE</h5>
                    <div class="mb-3 text-center">
                        <img src="../assets/img/worker.png" alt="Safety Behavior Logo" class="img-fluid" style="max-width: 100px;">
                    </div>
                    <button class="btn text-sm font-weight-bold mb-0 icon-move-right mt-auto" style="background-color: #8E1616; color: white;" onclick="location.href='{{ route('adminsystem.ppe.index') }}'">
                        MASTER
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>
                    <br>
                    <!-- DATA Button (Green) -->
                    <button class="btn text-sm font-weight-bold mb-0 icon-move-right mt-auto" style="background-color: #3E7B27; color: white;" onclick="location.href='{{ route('adminsystem.ppe.index') }}'">
                        DATA
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">NCR</h5>
                    <br>
                    <div class="mb-3 text-center">
                        <img src="../assets/img/lab-test.png" alt="NCR Logo" class="img-fluid" style="max-width: 100px;">
                    </div>
                    <button class="btn text-sm font-weight-bold mb-0 icon-move-right mt-auto" style="background-color: #8E1616; color: white;" onclick="location.href='{{ route('adminsystem.ncr.master') }}'">
                        MASTER
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>
                    <br>
                    <!-- DATA Button (Green) -->
                    <button class="btn text-sm font-weight-bold mb-0 icon-move-right mt-auto" style="background-color: #3E7B27; color: white;" onclick="location.href='{{ route('adminsystem.ncr.index') }}'">
                        DATA
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 justify-content-center">
    <!-- Second row with 2 columns -->
    <div class="col-xl-4 col-sm-6 mb-xl-0">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">TOOL & EQUIPMENT INSPECTION</h5>
                    <div class="mb-3 text-center">
                        <img src="../assets/img/settings.png" alt="Tool Inspection Logo" class="img-fluid" style="max-width: 100px;">
                    </div>
                    <button class="btn btn-primary text-sm font-weight-bold mb-0 icon-move-right mt-auto" onclick="window.location.href='page.html';">
                        Go
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-xl-0">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">DAILY ACTIVITIES & IMPROVEMENT</h5>
                    <div class="mb-3 text-center">
                        <img src="../assets/img/daily.png" alt="Daily Activities Logo" class="img-fluid" style="max-width: 100px;">
                    </div>
                    <button class="btn btn-primary text-sm font-weight-bold mb-0 icon-move-right mt-auto" onclick="location.href='{{ route('adminsystem.daily.index') }}'">
                        Go
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection