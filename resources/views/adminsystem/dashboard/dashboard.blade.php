@extends('layouts.user_type.auth')

@section('content')
<div class="row">
  <!-- Card 1: SAFETY PERFORMANCE BOARD -->
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">SAFETY PERFORMANCE BOARD</p>
              <h5 class="font-weight-bolder mb-0">
                <span class="text-success text-sm font-weight-bolder"></span>
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <a href="{{ route('adminsystem.dashboard-incident') }}" class="btn btn-primary icon icon-shape shadow text-center border-radius-md d-flex align-items-center justify-content-center"
              style="height: 50px; width: 50px; border-radius: 8px;">
              <i class="fas fa-hard-hat text-lg opacity-10" style="margin-bottom: 20px;" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 2: SAFETY PERFORMANCE INDICATOR -->
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">SAFETY PERFORMANCE INDICATOR</p>
            </div>
          </div>
          <div class="col-4 text-end">
            <a href="{{ route('adminsystem.dashboard-spi') }}" class="btn btn-primary icon icon-shape shadow text-center border-radius-md d-flex align-items-center justify-content-center"
              style="height: 50px; width: 50px; border-radius: 8px;">
              <i class="fas fa-chart-line text-lg opacity-10" style="margin-bottom: 20px;" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 3: NON CONFORMITY REPORT -->
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">NON CONFORMITY REPORT</p>
            </div>
          </div>
          <div class="col-4 text-end">
            <a href="{{ route('adminsystem.dashboard-ncr') }}" class="btn btn-primary icon icon-shape shadow text-center border-radius-md d-flex align-items-center justify-content-center"
              style="height: 50px; width: 50px; border-radius: 8px;">
              <i class="fas fa-exclamation-triangle text-lg opacity-10" style="margin-bottom: 20px;" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 4: BUDGETING -->
  <div class="col-xl-3 col-sm-6">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">BUDGETING</p>
            </div>
          </div>
          <div class="col-4 text-end">
            <a href="{{ route('adminsystem.dashboard-budget') }}" class="btn btn-primary icon icon-shape shadow text-center border-radius-md d-flex align-items-center justify-content-center"
              style="height: 50px; width: 50px; border-radius: 8px; ">
              <i class="fas fa-coins text-lg opacity-10" style="margin-bottom: 20px;" style="margin-bottom: 20px;" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div>


<div class="row mt-4">
  <div class="row mb-3 align-items-center">
    <div class="col-md-6">
      <form method="GET" action="{{ route('adminsystem.dashboard') }}">
        <label for="year" class="form-label fw-bold">Filter Tahun:</label>
        <select name="year" id="year" class="form-select" onchange="this.form.submit()">
          <option value="" @selected(empty($year))>All</option>
          @foreach($years as $y)
          <option value="{{ $y }}" @selected($y==$year)>{{ $y }}</option>
          @endforeach
        </select>
      </form>
    </div>
    <div class="col-md-6">
      <form method="GET" action="{{ route('adminsystem.dashboard') }}">
        <label for="month" class="form-label fw-bold">Filter Bulan:</label>
        <select name="month" id="month" class="form-select" onchange="this.form.submit()">
          <option value="" @selected(empty($month))>All</option>
          @foreach($months as $m)
          <option value="{{ $m }}" @selected($m==$month)>{{ $m }}</option>
          @endforeach
        </select>
      </form>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-lg-6">
      <div class="card z-index-2">
        <div class="card-header pb-0">
          <h6>% PPE COMPLIANCE OF EMPLOYEE ON SCREENING</h6>
          <p class="text-sm">
            <i class="fa fa-arrow-up text-success"></i>
          </p>
        </div>
        <div class="card-body p-3">
          <div class="chart">
            <!-- Form Target Compliance for Employee -->
            <form method="POST" action="{{ route('adminsystem.set-compliance-target') }}" class="mb-3">
              @csrf
              <input type="hidden" name="type" value="employee">
              <div class="row">
                <div class="col-md-6 d-flex align-items-end">
                  <div class="me-3 w-100">
                    <label for="target_employee" class="form-label fw-bold">Target % Patuh Karyawan:</label>
                    <input type="number" name="target" id="target_employee" class="form-control" value="{{ $targetEmployee ?? '' }}" min="0" max="100" required>
                  </div>
                  <div>
                    <button type="submit" class="btn btn-success mb-1">Update Target</button>
                  </div>
                </div>
              </div>

            </form>
            <canvas id="chart-line-1" class="chart-canvas" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card z-index-2">
        <div class="card-header pb-0">
          <h6>% PPE COMPLIANCE OF CONTRACTOR ON SCREENING</h6>
          <p class="text-sm">
            <i class="fa fa-arrow-up text-success"></i>
          </p>
        </div>
        <div class="card-body p-3">
          <div class="chart">
            <!-- Form Target Compliance for Contractor -->
            <form method="POST" action="{{ route('adminsystem.set-compliance-target') }}" class="mb-3">
              @csrf
              <input type="hidden" name="type" value="contractor">
              <div class="row">
                <div class="col-md-6 d-flex align-items-end">
                  <div class="me-3 w-100">
                   <label for="target_contractor" class="form-label fw-bold">Target % Patuh Kontraktor:</label>
                    <input type="number" name="target" id="target_contractor" class="form-control" value="{{ $targetContractor ?? '' }}" min="0" max="100">
                  </div>
                  <div>
                    <button type="submit" class="btn btn-success mb-1">Update Target</button>
                  </div>
                </div>
              </div>
            </form>
            <canvas id="chart-line-2" class="chart-canvas" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-lg-6">
      <div class="card z-index-2">
        <div class="card-header pb-0">
          <h6>EMPLOYEE NON-COMPLIANCE AND COMPLIANCE TARGET</h6>
          <p class="text-sm">
            <i class="fa fa-arrow-up text-success"></i>
          </p>
        </div>
        <div class="card-body p-3">
          <div class="chart">
            <canvas id="chart-line-3" class="chart-canvas" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card z-index-2">
        <div class="card-header pb-0">
          <h6>CONTRACTOR NON-COMPLIANCE AND COMPLIANCE TARGET</h6>
          <p class="text-sm">
            <i class="fa fa-arrow-up text-success"></i>
          </p>
        </div>
        <div class="card-body p-3">
          <div class="chart">
            <canvas id="chart-line-4" class="chart-canvas" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-lg-6">
      <div class="card z-index-2">
        <div class="card-header pb-0">
          <h6>KARYAWAN TIDAK PATUH</h6>
          <p class="text-sm">
            <i class="fa fa-arrow-up text-success"></i>
          </p>
        </div>
        <div class="card-body p-3">
          <div class="chart">
            <canvas id="chart-line-5" class="chart-canvas" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card z-index-2">
        <div class="card-header pb-0">
          <h6>KONTRAKTOR TIDAK PATUH</h6>
          <p class="text-sm">
            <i class="fa fa-arrow-up text-success"></i>
          </p>
        </div>
        <div class="card-body p-3">
          <div class="chart">
            <canvas id="chart-line-6" class="chart-canvas" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>


  @endsection
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
    .chart-canvas {
      max-height: 250px;
      height: 100%;
    }
  </style>



  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @push('dashboard')
  <script>
    const labels = @json($labels);

    //(% PPE Compliance Employee)
    const employeeData = @json($employeeData);
    const targetEmployeeValue = @json($targetEmployee);
    const targetEmployee = labels.map(() => targetEmployeeValue);
    const ctx = document.getElementById("chart-line-1").getContext("2d");

    new Chart(ctx, {
      type: "line",
      data: {
        labels: labels,
        datasets: [{
            label: "% PPE Compliance Employee",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 4,
            pointBackgroundColor: "#4CAF50",
            borderColor: "#4CAF50",
            backgroundColor: "rgba(76, 175, 80, 0.2)",
            fill: true,
            data: employeeData
          },
          {
            label: "% Target Compliance Employee",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 4,
            pointBackgroundColor: "#2196F3",
            borderColor: "#2196F3",
            backgroundColor: "rgba(33, 150, 243, 0.2)",
            fill: true,
            data: targetEmployee
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            ticks: {
              callback: value => value + "%"
            }
          }
        }
      }
    });

    //(% PPE Compliance Contractor)
    const contractorData = @json($contractorData);
    const targetContractor = @json($targetContractor);
    const ctx2 = document.getElementById("chart-line-2").getContext("2d");

    new Chart(ctx2, {
      type: "line",
      data: {
        labels: labels,
        datasets: [{
            label: "% PPE Compliance Contractor",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 4,
            pointBackgroundColor: "#FF9800",
            borderColor: "#FF9800",
            backgroundColor: "rgba(255, 152, 0, 0.2)",
            fill: true,
            data: contractorData
          },
          {
            label: "% Target Compliance Contractor",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 4,
            pointBackgroundColor: "#2196F3",
            borderColor: "#2196F3",
            backgroundColor: "rgba(33, 150, 243, 0.2)",
            fill: true,
            data: Array(labels.length).fill(targetContractor)
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            ticks: {
              callback: value => value + "%"
            }
          }
        }
      }
    });

    //(Daily Employee Compliant/Noncompliant)
    const tidak_patuh_karyawan = @json($tidak_patuh_karyawan);
    const patuh_karyawan = @json($patuh_karyawan);
    const ctx3 = document.getElementById("chart-line-3").getContext("2d");

    new Chart(ctx3, {
      type: "line",
      data: {
        labels: labels,
        datasets: [{
            label: "Jumlah Tidak Patuh Karyawan",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 4,
            pointBackgroundColor: "#4CAF50",
            borderColor: "#4CAF50",
            backgroundColor: "rgba(76, 175, 80, 0.2)",
            fill: true,
            data: tidak_patuh_karyawan
          },
          {
            label: "Patuh Karyawan",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 4,
            pointBackgroundColor: "#2196F3",
            borderColor: "#2196F3",
            backgroundColor: "rgba(33, 150, 243, 0.2)",
            fill: true,
            data: patuh_karyawan
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: value => value
            }
          }
        }
      }
    });

    //(Daily Contractor Compliant/Noncompliant)
    const tidak_patuh_kontraktor = @json($tidak_patuh_kontraktor);
    const patuh_kontraktor = @json($patuh_kontraktor);
    const ctx4 = document.getElementById("chart-line-4").getContext("2d");

    new Chart(ctx4, {
      type: "line",
      data: {
        labels: labels,
        datasets: [{
            label: "Jumlah Tidak Patuh Kontraktor",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 4,
            pointBackgroundColor: "#FF9800",
            borderColor: "#FF9800",
            backgroundColor: "rgba(255, 152, 0, 0.2)",
            fill: true,
            data: tidak_patuh_kontraktor
          },
          {
            label: "Patuh Kontraktor",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 4,
            pointBackgroundColor: "#2196F3",
            borderColor: "#2196F3",
            backgroundColor: "rgba(33, 150, 243, 0.2)",
            fill: true,
            data: patuh_kontraktor
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: value => value
            }
          }
        }
      }
    });

    //(Noncompliance by Type - Employee)
    const employeeHelmData = @json($employeeHelmData);
    const employeeSepatuData = @json($employeeSepatuData);
    const ctx5 = document.getElementById("chart-line-5").getContext("2d");

    new Chart(ctx5, {
      type: "line",
      data: {
        labels: labels,
        datasets: [{
            label: "Karyawan Tidak Patuh Helm",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 4,
            pointBackgroundColor: "#4CAF50",
            borderColor: "#4CAF50",
            backgroundColor: "rgba(76, 175, 80, 0.2)",
            fill: true,
            data: employeeHelmData
          },
          {
            label: "Karyawan Tidak Patuh Sepatu",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 4,
            pointBackgroundColor: "#2196F3",
            borderColor: "#2196F3",
            backgroundColor: "rgba(33, 150, 243, 0.2)",
            fill: true,
            data: employeeSepatuData
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: value => value
            }
          }
        }
      }
    });

    //(Noncompliance by Type - Contractor)
    const contractorHelmData = @json($contractorHelmData);
    const contractorSepatuData = @json($contractorSepatuData);
    const ctx6 = document.getElementById("chart-line-6").getContext("2d");

    new Chart(ctx6, {
      type: "line",
      data: {
        labels: labels,
        datasets: [{
            label: "Kontraktor Tidak Patuh Helm",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 4,
            pointBackgroundColor: "#FF9800",
            borderColor: "#FF9800",
            backgroundColor: "rgba(255, 152, 0, 0.2)",
            fill: true,
            data: contractorHelmData
          },
          {
            label: "Kontraktor Tidak Patuh Sepatu",
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 4,
            pointBackgroundColor: "#2196F3",
            borderColor: "#2196F3",
            backgroundColor: "rgba(33, 150, 243, 0.2)",
            fill: true,
            data: contractorSepatuData
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: value => value
            }
          }
        }
      }
    });
  </script>
  @endpush