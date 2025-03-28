@extends('layouts.user_type.auth')

@section('content')

<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">

    </div>
</div>
<h2 class="text-black font-weight-bolder text-center"> PELAPORAN</h2>
</div>
<div class="row mt-4 justify-content-center">
    <div class="col-lg-3">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">INCIDENT & ACCIDENT</h5>
                    <!-- Logo ditambahkan di bawah judul -->
                    <div class="mb-3 text-center">
                        <img src="../assets/img/work-injury.png" alt="Falling Down Logo" class="img-fluid" style="max-width: 100px;">
                    </div>
                    <button class="btn btn-primary text-sm font-weight-bold mb-0 icon-move-right mt-auto" onclick="location.href='{{ route('adminsystem.incident.index') }}'">
                    Go
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">SAFETY BEHAVIOR & PPE COMPLIANCE</h5>
                    <div class="mb-3 text-center">
                        <img src="../assets/img/worker.png" alt="Falling Down Logo" class="img-fluid" style="max-width: 100px;">
                    </div>
                    <button class="btn btn-primary text-sm font-weight-bold mb-0 icon-move-right mt-auto" onclick="window.location.href='page.html';">
                        Go
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">NCR</h5>
                    <!-- Logo ditambahkan di bawah judul -->
                    <div class="mb-3 text-center">
                        <img src="../assets/img/lab-test.png" alt="Falling Down Logo" class="img-fluid" style="max-width: 100px;">
                    </div> <button class="btn btn-primary text-sm font-weight-bold mb-0 icon-move-right mt-auto" onclick="window.location.href='page.html';">
                        Go
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4 justify-content-center"></div>
    <div class="col-lg-3">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">TOOL & EQUIPMENT INSPECTION</h5>
                    <!-- Logo ditambahkan di bawah judul -->
                    <div class="mb-3 text-center">
                        <img src="../assets/img/settings.png" alt="Falling Down Logo" class="img-fluid" style="max-width: 100px;">
                    </div> <button class="btn btn-primary text-sm font-weight-bold mb-0 icon-move-right mt-auto" onclick="window.location.href='page.html';">
                        Go
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>

                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-3">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">DAILY ACTIVITIES & IMPROVEMENT</h5>
                    <!-- Logo ditambahkan di bawah judul -->
                    <div class="mb-3 text-center">
                        <img src="../assets/img/daily.png" alt="Falling Down Logo" class="img-fluid" style="max-width: 100px;">
                    </div>
                    <button class="btn btn-primary text-sm font-weight-bold mb-0 icon-move-right mt-auto" onclick="window.location.href='page.html';">
                        Go
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>


                </div>
            </div>
        </div>
    </div>

</div>
<div class="row mt-4">
    <h2 class="text-black font-weight-bolder text-center">INSTRUKSI</h2>
    <div class="row justify-content-center align-items-center">
        <div class="col-lg-11">
            <div class="card z-index-2">
                <div class="card-header pb-0">

                    <p class="text-lg">
                        <i class="fa fa-arrow-up text-success"></i>
                        <span class="font-weight-bold">Tindakan tidak aman adalah ketika seseorang yang memiliki pengetahuan dan kendali atas kondisi atau tindakan tidak aman yang ada, tetapi memilih untuk melakukan tindakan atau mengabaikan kondisi tersebut. Pekerja umumnya melakukan tindakan tidak aman dalam upaya menghemat waktu dan/atau tenaga.</span>
                    </p>
                    <br>
                    <br>
                    <br>

                    <p class="text-lg">
                        <i class="fa fa-arrow-up text-success"></i>
                        <span class="font-weight-bold">Perilaku tidak aman didefinisikan sebagai perilaku apa pun yang dilakukan karyawan tanpa memperhatikan aturan keselamatan, standar, prosedur, instruksi, dan kriteria khusus dalam sistem.</span>
                    </p>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="chart-line" class="chart-canvas" height="70"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection