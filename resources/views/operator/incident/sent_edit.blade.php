@extends('layouts.user_type.operator')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div>
    <div class="container-fluid ">
        <h2 class="text-black font-weight-bolder text-center">INCIDENT & ACCIDENT REPORT</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('DATA UMUM') }}</h6>
            </div>
            <div class="card-body pt-2 p-3">
                <form action="{{ route('operator.incident.sent_update', $incident_fix->id) }}" method="POST" role="form text-left">
                    @csrf
                    @method('PUT')
                    @if($errors->any())
                    <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">{{ $errors->first() }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="m-3 alert alert-success alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgl_kejadiannya">{{ __('Tanggal Shift Kerja') }}</label>
                                <input class="form-control" type="date" id="shift_date" name="shift_date" value="{{ old('shift_date', \Carbon\Carbon::parse($incident_fix->shift_date)->format('Y-m-d')) }}"
                                    required>
                                @error('shift_date')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shift">{{ __('Shift Kerja') }}</label>
                                <select class="form-control" id="shift" name="shift" required>
                                    <option value="Shift 1" {{ old('shift', $incident_fix->shift) == 'Shift 1' ? 'selected' : '' }}>SHIFT I</option>
                                    <option value="Shift 2" {{ old('shift', $incident_fix->shift) == 'Shift 2' ? 'selected' : '' }}>SHIFT II</option>
                                    <option value="Shift 3" {{ old('shift', $incident_fix->shift) == 'Shift 3' ? 'selected' : '' }}>SHIFT III</option>
                                    <option value="Nonshift" {{ old('shift', $incident_fix->shift) == 'Nonshift' ? 'selected' : '' }}>NONSHIFT</option>
                                </select>
                                @error('shift')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hse_inspector_id">{{ __('Safety Officer') }}</label>
                                <select class="form-control" id="hse_inspector_id" name="hse_inspector_id">
                                    <option value="" disabled selected>Pilih Safety Officer</option>
                                    @foreach($officers as $officer)
                                    <option value="{{ $officer->id }}" {{ old('hse_inspector_id', $incident_fix->hse_inspector_id) == $officer->id ? 'selected' : '' }}>
                                        {{ $officer->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('hse_inspector_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <h6 class="mb-0">{{ __('INFORMASI JUMLAH PEKERJA') }}</h6>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jml_employee">{{ __('Employee') }}</label>
                                <input class="form-control" type="number" id="jml_employee" name="jml_employee" min="0" value="{{ old('jml_employee', $incident_fix->jml_employee) }}" required>
                                @error('jml_employee')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jml_outsources">{{ __('Outsources') }}</label>
                                <input class="form-control" type="number" id="jml_outsources" name="jml_outsources" min="0" value="{{ old('jml_outsources', $incident_fix->jml_outsources) }}" required>
                                @error('jml_outsources')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jml_security">{{ __('Security') }}</label>
                                <input class="form-control" type="number" id="jml_security" name="jml_security" min="0" value="{{ old('jml_security', $incident_fix->jml_security) }}" required>
                                @error('jml_security')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jml_loading_stacking">{{ __('Loading/Stacing') }}</label>
                                <input class="form-control" type="number" id="jml_loading_stacking" name="jml_loading_stacking" min="0" value="{{ old('jml_loading_stacking', $incident_fix->jml_loading_stacking) }}" required>
                                @error('jml_loading_stacking')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jml_contractor">{{ __('Kontraktor') }}</label>
                                <input class="form-control" type="number" id="jml_contractor" name="jml_contractor" min="0" value="{{ old('jml_contractor', $incident_fix->jml_contractor) }}" required>
                                @error('jml_contractor')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status_kejadian">{{ __('Apakah Ada Kejadian?') }}</label>
                                <select class="form-control" id="status_kejadian" name="status_kejadian" onchange="toggleForms()" required>
                                    <option value="Ada" {{ old('status_kejadian', $incident_fix->status_kejadian) == 'Ada' ? 'selected' : '' }}>Ada</option>
                                    <option value="Tidak" {{ old('status_kejadian', $incident_fix->status_kejadian) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @error('status_kejadian')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div id="additional-forms" style="display: {{ old('status_kejadian', $incident_fix->status_kejadian) == 'Ada' ? 'block' : 'none' }};">
                            <h6 class="mb-0">{{ __('DATA KEJADIAN') }}</h6>

                            <div class="card-body pt-4 p-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tgl_kejadiannya">{{ __('Tanggal Kejadian') }}</label>
                                            <input class="form-control" type="date" id="tgl_kejadiannya" name="tgl_kejadiannya" value="{{ old('tgl_kejadiannya', \Carbon\Carbon::parse($incident_fix->tgl_kejadiannya)->format('Y-m-d')) }}">
                                            @error('tgl_kejadiannya')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jam_kejadiannya">{{ __('Jam Kejadian') }}</label>
                                            <input class="form-control" type="time" id="jam_kejadiannya" name="jam_kejadiannya" value="{{ old('jam_kejadiannya', $incident_fix->jam_kejadiannya) }}">
                                            @error('jam_kejadiannya')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lokasi_kejadiannya">{{ __('Lokasi Kejadian') }}</label>
                                            <input class="form-control" type="text" id="lokasi_kejadiannya" name="lokasi_kejadiannya" value="{{ old('lokasi_kejadiannya', $incident_fix->lokasi_kejadiannya) }}">
                                            @error('lokasi_kejadiannya')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ada_korban">{{ __('Apakah Ada Korban?') }}</label>
                                            <select class="form-control" id="ada_korban" name="ada_korban">
                                                <option value="" {{ old('ada_korban', $incident_fix->ada_korban) == '' ? 'selected' : '' }}>Pilih Opsi</option>
                                                <option value="Ada" {{ old('ada_korban', $incident_fix->ada_korban) == 'ada' ? 'selected' : '' }}>Ada</option>
                                                <option value="Tidak" {{ old('ada_korban', $incident_fix->ada_korban) == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                            </select>
                                            @error('ada_korban')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="klasifikasi_kejadiannya">{{ __('Klasifikasi Kejadian') }}</label>
                                        <select class="form-control" id="klasifikasi_kejadiannya" name="klasifikasi_kejadiannya">
                                            <option value="">Pilih Klasifikasi</option>
                                            <option value="Near Miss" {{ old('klasifikasi_kejadiannya', $incident_fix->klasifikasi_kejadiannya) == 'Near Miss' ? 'selected' : '' }}>Near Miss</option>
                                            <option value="First Aid" {{ old('klasifikasi_kejadiannya', $incident_fix->klasifikasi_kejadiannya) == 'First Aid' ? 'selected' : '' }}>First Aid</option>
                                            <option value="Illness/Sick" {{ old('klasifikasi_kejadiannya', $incident_fix->klasifikasi_kejadiannya) == 'Illness/Sick' ? 'selected' : '' }}>Illness/Sick</option>
                                            <option value="Medical Treatment Case (MTC)" {{ old('klasifikasi_kejadiannya', $incident_fix->klasifikasi_kejadiannya) == 'Medical Treatment Case (MTC)' ? 'selected' : '' }}>Medical Treatment Case (MTC)</option>
                                            <option value="Restricted Work Case (RWC)" {{ old('klasifikasi_kejadiannya', $incident_fix->klasifikasi_kejadiannya) == 'Restricted Work Case (RWC)' ? 'selected' : '' }}>Restricted Work Case (RWC)</option>
                                            <option value="Lost Workdays Case (LWC)" {{ old('klasifikasi_kejadiannya', $incident_fix->klasifikasi_kejadiannya) == 'Lost Workdays Case (LWC)' ? 'selected' : '' }}>Lost Workdays Case (LWC)</option>
                                            <option value="Permanent Partial Disability (PPD)" {{ old('klasifikasi_kejadiannya', $incident_fix->klasifikasi_kejadiannya) == 'Permanent Partial Disability (PPD)' ? 'selected' : '' }}>Permanent Partial Disability (PPD)</option>
                                            <option value="Permanent Total Disability (PTD)" {{ old('klasifikasi_kejadiannya', $incident_fix->klasifikasi_kejadiannya) == 'Permanent Total Disability (PTD)' ? 'selected' : '' }}>Permanent Total Disability (PTD)</option>
                                            <option value="Fatality" {{ old('klasifikasi_kejadiannya', $incident_fix->klasifikasi_kejadiannya) == 'Fatality' ? 'selected' : '' }}>Fatality</option>
                                            <option value="Fire Incident" {{ old('klasifikasi_kejadiannya', $incident_fix->klasifikasi_kejadiannya) == 'Fire Incident' ? 'selected' : '' }}>Fire Incident</option>
                                            <option value="Road Incident" {{ old('klasifikasi_kejadiannya', $incident_fix->klasifikasi_kejadiannya) == 'Road Incident' ? 'selected' : '' }}>Road Incident</option>
                                            <option value="Property Loss/Damage" {{ old('klasifikasi_kejadiannya', $incident_fix->klasifikasi_kejadiannya) == 'Property Loss/Damage' ? 'selected' : '' }}>Property Loss/Damage</option>
                                            <option value="Environmental Incident" {{ old('klasifikasi_kejadiannya', $incident_fix->klasifikasi_kejadiannya) == 'Environmental Incident' ? 'selected' : '' }}>Environmental Incident</option>
                                        </select>
                                        @error('incident')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">

                                    <center>
                                        <label for="">JENIS LUKA/SAKIT</label>

                                    </center>
                                </div>
                                <div class="form-group d-flex justify-content-between">
                                    <div class="me-2" style="flex: 1;">
                                        <label for="jenis_luka_sakit">{{ __('Luka/Sakit 1') }}</label>
                                        <select class="form-control" id="jenis_luka_sakit" name="jenis_luka_sakit">
                                            <option value="">Pilih Jenis Luka</option>
                                            @foreach($hilangs as $luka)
                                            <option value="{{ $luka->jenis_luka }}" {{ old('jenis_luka_sakit', $incident_fix->jenis_luka_sakit) == $luka->jenis_luka ? 'selected' : '' }}>
                                                {{ $luka->jenis_luka }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('jenis_luka_sakit')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="ms-2" style="flex: 1;">
                                        <label for="jenis_luka_sakit2">{{ __('Luka/Sakit 2') }}</label>
                                        <select class="form-control" id="jenis_luka_sakit2" name="jenis_luka_sakit2">
                                            <option value="">Pilih Jenis Luka</option>
                                            @foreach($hilangs as $luka)
                                            <option value="{{ $luka->jenis_luka }}" {{ old('jenis_luka_sakit2', $incident_fix->jenis_luka_sakit2) == $luka->jenis_luka ? 'selected' : '' }}>
                                                {{ $luka->jenis_luka }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('jenis_luka_sakit2')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="ms-2" style="flex: 1;">
                                        <label for="jenis_luka_sakit3">{{ __('Luka/Sakit 3') }}</label>
                                        <select class="form-control" id="jenis_luka_sakit3" name="jenis_luka_sakit3">
                                            <option value="">Pilih Jenis Luka</option>
                                            @foreach($hilangs as $luka)
                                            <option value="{{ $luka->jenis_luka }}" {{ old('jenis_luka_sakit3', $incident_fix->jenis_luka_sakit3) == $luka->jenis_luka ? 'selected' : '' }}>
                                                {{ $luka->jenis_luka }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('jenis_luka_sakit3')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">

                                    <center>
                                        <label for="">DATA BAGIAN TUBUH YANG LUKA/SAKIT</label>

                                    </center>
                                </div>
                                <div class="form-group d-flex justify-content-between">
                                    <div class="me-2" style="flex: 1;">
                                        <label for="bagian_tubuh_luka">{{ __('Bagian Tubuh 1') }}</label>
                                        <input class="form-control" id="bagian_tubuh_luka" rows="3" name="bagian_tubuh_luka" value="{{ old('bagian_tubuh_luka', $incident_fix->bagian_tubuh_luka) }}"></input>
                                        @error('bagian_tubuh_luka')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="ms-2" style="flex: 1;">
                                        <label for="bagian_tubuh_luka2">{{ __('Bagian Tubuh 2') }}</label>
                                        <input class="form-control" id="bagian_tubuh_luka2" rows="3" name="bagian_tubuh_luka2" value="{{ old('bagian_tubuh_luka2', $incident_fix->bagian_tubuh_luka2) }}"></input>
                                        @error('bagian_tubuh_luka2')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="ms-2" style="flex: 1;">
                                        <label for="bagian_tubuh_luka3">{{ __('Bagian Tubuh 3') }}</label>
                                        <input class="form-control" id="bagian_tubuh_luka3" rows="3" name="bagian_tubuh_luka3" value="{{ old('bagian_tubuh_luka3', $incident_fix->bagian_tubuh_luka3) }}"></input>
                                        @error('bagian_tubuh_luka3')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <img src="{{ asset('assets/img/bagiantubuh.png') }}" alt="Body Map" class="body-map">
                                <!-- Example hotspots -->
                                <div class="hotspot" style="position: absolute; top: 3%; left: 23%; width: 3%; height: 3%;" data-value="Crown"></div>
                                <div class="hotspot" style="position: absolute; top: 7%; left: 23%; width: 3%; height: 3%;" data-value="Back Of Head"></div>
                                <div class="hotspot" style="position: absolute; top: 10%; left: 17%; width: 3%; height: 3%;" data-value="Left Ear"></div>
                                <div class="hotspot" style="position: absolute; top: 10%; left: 29%; width: 3%; height: 3%;" data-value="Right Ear"></div>
                                <div class="hotspot" style="position: absolute; top: 20%; left: 13%; width: 3%; height: 3%;" data-value="Left Shoulder"></div>
                                <div class="hotspot" style="position: absolute; top: 20%; left: 32%; width: 3%; height: 3%;" data-value="Right Shoulder"></div>
                                <div class="hotspot" style="position: absolute; top: 14%; left: 23%; width: 3%; height: 3%;" data-value="Neck"></div>
                                <div class="hotspot" style="position: absolute; top: 30%; left: 22%; width: 3%; height: 3%;" data-value="Back"></div>
                                <div class="hotspot" style="position: absolute; top: 35%; left: 8%; width: 3%; height: 3%;" data-value="Left Arm"></div>
                                <div class="hotspot" style="position: absolute; top: 35%; left: 38%; width: 3%; height: 3%;" data-value="Right Arm"></div>
                                <div class="hotspot" style="position: absolute; top: 40%; left: 7%; width: 3%; height: 3%;" data-value="Left Elbow"></div>
                                <div class="hotspot" style="position: absolute; top: 40%; left: 39%; width: 3%; height: 3%;" data-value="Right Elbow"></div>
                                <div class="hotspot" style="position: absolute; top: 41%; left: 17%; width: 3%; height: 3%;" data-value="Left Loin"></div>
                                <div class="hotspot" style="position: absolute; top: 41%; left: 28%; width: 3%; height: 3%;" data-value="Right Loin"></div>
                                <div class="hotspot" style="position: absolute; top: 47%; left: 6%; width: 3%; height: 3%;" data-value="Left Forearm"></div>
                                <div class="hotspot" style="position: absolute; top: 46%; left: 41%; width: 3%; height: 3%;" data-value="Right Forearm"></div>
                                <div class="hotspot" style="position: absolute; top: 52%; left: 5%; width: 3%; height: 3%;" data-value="Left Wrist"></div>
                                <div class="hotspot" style="position: absolute; top: 51%; left: 42%; width: 3%; height: 3%;" data-value="Right Wrist"></div>
                                <div class="hotspot" style="position: absolute; top: 55%; left: 6%; width: 3%; height: 3%;" data-value="Left Hand"></div>
                                <div class="hotspot" style="position: absolute; top: 55%; left: 43%; width: 3%; height: 3%;" data-value="Right Hand"></div>
                                <div class="hotspot" style="position: absolute; top: 51%; left: 17%; width: 3%; height: 3%;" data-value="Left Buttock"></div>
                                <div class="hotspot" style="position: absolute; top: 51%; left: 30%; width: 3%; height: 3%;" data-value="Right Buttock"></div>
                                <div class="hotspot" style="position: absolute; top: 60%; left: 30%; width: 3%; height: 3%;" data-value="Right Thigh"></div>
                                <div class="hotspot" style="position: absolute; top: 60%; left: 17%; width: 3%; height: 3%;" data-value="Left Thigh"></div>
                                <div class="hotspot" style="position: absolute; top: 72%; left: 19%; width: 3%; height: 3%;" data-value="Left Ham"></div>
                                <div class="hotspot" style="position: absolute; top: 72%; left: 30%; width: 3%; height: 3%;" data-value="Right Ham"></div>
                                <div class="hotspot" style="position: absolute; top: 78%; left: 20%; width: 3%; height: 3%;" data-value="Left Leg"></div>
                                <div class="hotspot" style="position: absolute; top: 78%; left: 30%; width: 3%; height: 3%;" data-value="Right Leg"></div>
                                <div class="hotspot" style="position: absolute; top: 81%; left: 20%; width: 3%; height: 3%;" data-value="Left Calf"></div>
                                <div class="hotspot" style="position: absolute; top: 81%; left: 30%; width: 3%; height: 3%;" data-value="Right Calf"></div>
                                <div class="hotspot" style="position: absolute; top: 96%; left: 29%; width: 3%; height: 3%;" data-value="Right Heel"></div>
                                <div class="hotspot" style="position: absolute; top: 96%; left: 21%; width: 3%; height: 3%;" data-value="Left Heel"></div>
                                <div class="hotspot" style="position: absolute; top: 5%; right: 19.5%; width: 3%; height: 3%;" data-value="Forehead"></div>
                                <div class="hotspot" style="position: absolute; top: 9%; right: 22%; width: 3%; height: 3%;" data-value="Right Eye"></div>
                                <div class="hotspot" style="position: absolute; top: 9%; right: 17.5%; width: 3%; height: 3%;" data-value="Left Eye"></div>
                                <div class="hotspot" style="position: absolute; top: 11%; right: 19.5%; width: 3%; height: 3%;" data-value="Nose"></div>
                                <div class="hotspot" style="position: absolute; top: 13%; right: 19.5%; width: 3%; height: 3%;" data-value="Mouth"></div>
                                <div class="hotspot" style="position: absolute; top: 30%; right: 19.5%; width: 3%; height: 3%;" data-value="Thorax"></div>
                                <div class="hotspot" style="position: absolute; top: 27%; right: 26.5%; width: 3%; height: 3%;" data-value="Right Breast"></div>
                                <div class="hotspot" style="position: absolute; top: 27%; right: 14.5%; width: 3%; height: 3%;" data-value="Left Breast"></div>
                                <div class="hotspot" style="position: absolute; top: 32%; right: 35%; width: 3%; height: 3%;" data-value="Right Extremity"></div>
                                <div class="hotspot" style="position: absolute; top: 32%; right: 6%; width: 3%; height: 3%;" data-value="Left Extremity"></div>
                                <div class="hotspot" style="position: absolute; top: 45%; right: 4%; width: 3%; height: 3%;" data-value="Right Upper"></div>
                                <div class="hotspot" style="position: absolute; top: 45%; right: 35%; width: 3%; height: 3%;" data-value="Left Upper"></div>
                                <div class="hotspot" style="position: absolute; top: 45%; right: 12%; width: 3%; height: 3%;" data-value="Right Groin"></div>
                                <div class="hotspot" style="position: absolute; top: 45%; right: 28%; width: 3%; height: 3%;" data-value="Left Groin"></div>
                                <div class="hotspot" style="position: absolute; top: 48%; right: 29%; width: 3%; height: 3%;" data-value="Right Hip"></div>
                                <div class="hotspot" style="position: absolute; top: 48%; right: 11%; width: 3%; height: 3%;" data-value="Left Hip"></div>
                                <div class="hotspot" style="position: absolute; top: 52%; right: 19.5%; width: 3%; height: 3%;" data-value="Gental Organs"></div>
                                <div class="hotspot" style="position: absolute; top: 60%; right: 26.5%; width: 3%; height: 3%;" data-value="Right Extermity"></div>
                                <div class="hotspot" style="position: absolute; top: 60%; right: 16.5%; width: 3%; height: 3%;" data-value="Left Extermity"></div>
                                <div class="hotspot" style="position: absolute; top: 71%; right: 26.5%; width: 3%; height: 3%;" data-value="Right Knee"></div>
                                <div class="hotspot" style="position: absolute; top: 71%; right: 16.5%; width: 3%; height: 3%;" data-value="Left Knee"></div>
                                <div class="hotspot" style="position: absolute; top: 81%; right: 26.5%; width: 3%; height: 3%;" data-value="Right Lower"></div>
                                <div class="hotspot" style="position: absolute; top: 81%; right: 16.5%; width: 3%; height: 3%;" data-value="Left Lower"></div>
                                <div class="hotspot" style="position: absolute; top: 94.5%; right: 26.5%; width: 3%; height: 3%;" data-value="Right Foot"></div>
                                <div class="hotspot" style="position: absolute; top: 94.5%; right: 16.5%; width: 3%; height: 3%;" data-value="Left Foot"></div>
                                <div class="tooltip" id="tooltip" style="position: absolute; display: none; background: rgba(0, 0, 0, 0.7); color: #fff; padding: 5px; border-radius: 5px;"></div>
                            </div>


                            <h6 class="mb-0">{{ __('DATA KORBAN') }}</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nama_korban">{{ __('Nama') }}</label>
                                        <input class="form-control" type="text" id="nama_korban" name="nama_korban" value="{{ old('nama_korban', $incident_fix->nama_korban) }}">
                                        @error('nama_korban')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">{{ __('Status') }}</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="">{{ __('Pilih Status') }}</option>
                                            <option value="Karyawan" {{ old('status', $incident_fix->status) == 'Karyawan' ? 'selected' : '' }}>{{ __('Karyawan') }}</option>
                                            <option value="Outsourcing/Borongan" {{ old('status', $incident_fix->status) == 'Outsourcing/Borongan' ? 'selected' : '' }}>{{ __('Outsourcing/Borongan') }}</option>
                                            <option value="Pekerja Borongan" {{ old('status', $incident_fix->status) == 'Pekerja Borongan' ? 'selected' : '' }}>{{ __('Pekerja Borongan') }}</option>
                                            <option value="Sub Kontraktor" {{ old('status', $incident_fix->status) == 'Sub Kontraktor' ? 'selected' : '' }}>{{ __('Sub Kontraktor') }}</option>
                                            <option value="Kontraktor" {{ old('status', $incident_fix->status) == 'Kontraktor' ? 'selected' : '' }}>{{ __('Kontraktor') }}</option>
                                            <option value="Magang" {{ old('status', $incident_fix->status) == 'Magang' ? 'selected' : '' }}>{{ __('Magang') }}</option>
                                            <option value="Tamu" {{ old('status', $incident_fix->status) == 'Tamu' ? 'selected' : '' }}>{{ __('Tamu') }}</option>
                                            <option value="Masyarakat Umum" {{ old('status', $incident_fix->status) == 'Masyarakat Umum' ? 'selected' : '' }}>{{ __('Masyarakat Umum') }}</option>
                                        </select> @error('status')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jenis_kelamin">{{ __('Jenis Kelamin') }}</label>
                                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                                            <option value="">{{ __('Pilih Jenis Kelamin') }}</option>
                                            <option value="Perempuan" {{ old('jenis_kelamin', $incident_fix->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>{{ __('Perempuan') }}</option>
                                            <option value="Laki-Laki" {{ old('jenis_kelamin', $incident_fix->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>{{ __('Laki-Laki') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Dropdown Perusahaan -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="perusahaan_id">Perusahaan</label>
                                        <select class="form-control" id="perusahaan_id" name="perusahaan_id">
                                            <option value="" disabled {{ old('perusahaan_id', $incident_fix->perusahaan_id ?? '') ? '' : 'selected' }}>Pilih Perusahaan</option>
                                            @foreach($perusahaans as $perusahaan)
                                            <option value="{{ $perusahaan->id }}"
                                                {{ old('perusahaan_id', $incident_fix->perusahaan_id ?? '') == $perusahaan->id ? 'selected' : '' }}>
                                                {{ $perusahaan->perusahaan_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('perusahaan_id')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>


                                <!-- Dropdown Bagian -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bagian">{{ __('Bagian') }}</label>
                                        <select class="form-control" id="bagian" name="bagian">
                                            <option value="" disabled selected>Pilih Bagian</option>
                                        </select>
                                        @error('bagian')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jabatan">{{ __('Jabatan') }}</label>
                                        <input class="form-control" type="text" id="jabatan" name="jabatan"
                                            value="{{ old('jabatan', $incident_fix->jabatan ?? '') }}">
                                        @error('jabatan')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="masa_kerja">{{ __('Masa Kerja (Tahun)') }}</label>
                                        <input class="form-control" type="number" id="masa_kerja" name="masa_kerja"
                                            value="{{ old('masa_kerja', $incident_fix->masa_kerja ?? '') }}">
                                        @error('masa_kerja')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tgl_lahir">{{ __('Tanggal Lahir') }}</label>
                                        <input class="form-control" type="date" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir', \Carbon\Carbon::parse($incident_fix->tgl_lahir)->format('Y-m-d')) }}">
                                        @error('tgl_lahir')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <h6 class="mb-0">{{ __('INFORMASI KEJADIAN DAN TINDAKAN') }}</h6>

                            <!-- Jenis Kejadian -->
                            <div class="form-group">
                                <label for="jenis_kejadiannya">{{ __('Jenis Kejadian') }}</label>
                                <select class="form-control" id="jenis_kejadiannya" name="jenis_kejadiannya">
                                    <option value="">{{ __('Pilih Kategori') }}</option>
                                    @foreach([
                                    'Berhantaman / menghantam (Berturutan / Menabrak / Bertabrakan / Kandas / Tumbukan)',
                                    'Terkena oleh Benda Bergerak',
                                    'Jatuh dari Ketinggian (Orang/Peralatan/Bahan)',
                                    'Jatuh di Tingkat yang Sama (Tergelincir&Jatuh/Tersandung)',
                                    'Terjebak Didalam, Diluar, Antara atau Di Bawah',
                                    'Kontak dengan Suhu Ekstrem (Panas/Dingin)',
                                    'Kontak dengan Listrik',
                                    'Kontak dengan Kebisingan',
                                    'Kontak dengan Getaran',
                                    'Kontak dengan Radiasi',
                                    'Kegagalan fungsi tubuh manusia',
                                    'Overstress (oleh Overload / Overpressure / Faktor Ergonomis)',
                                    'Kontak dengan Zat Berbahaya/Dosis (Beracun / Korosif / Karsinogenik / Biologis / Virus)',
                                    'Hilangnya Penahanan Primer',
                                    'Pelepasan (ke Udara/Air/ke Tanah/ke Struktur)',
                                    'Api (Api Kolam/Api Jet/Api Kilat)',
                                    'Ledakan (Awan Uap/Debu/Ledakan Tekanan/BLEVE)',
                                    'Kegagalan Peralatan Mekanik',
                                    'Kegagalan Sistem Listrik',
                                    'Kegagalan Instrumentasi/Logika/Loop',
                                    'Kegagalan Struktur',
                                    'Gangguan Operasi/Proses Abnormal',
                                    'Ketidakstabilan'
                                    ] as $jenis)
                                    <option value="{{ $jenis }}"
                                        {{ old('jenis_kejadiannya', $incident_fix->jenis_kejadiannya ?? '') == $jenis ? 'selected' : '' }}>
                                        {{ $jenis }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Penjelasan Kejadian -->
                            <div class="form-group">
                                <label for="penjelasan_kejadiannya">{{ __('Penjelasan Kejadian') }}</label>
                                <textarea class="form-control" id="penjelasan_kejadiannya" rows="3" name="penjelasan_kejadiannya">{{ old('penjelasan_kejadiannya', $incident_fix->penjelasan_kejadiannya ?? '') }}</textarea>
                                @error('penjelasan_kejadiannya')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tindakan Pengobatan -->
                            <div class="form-group">
                                <label for="tindakan_pengobatan">{{ __('Pengobatan yang Diterapkan') }}</label>
                                <textarea class="form-control" id="tindakan_pengobatan" rows="3" name="tindakan_pengobatan">{{ old('tindakan_pengobatan', $incident_fix->tindakan_pengobatan ?? '') }}</textarea>
                                @error('tindakan_pengobatan')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tindakan Segera -->
                            <div class="form-group">
                                <label for="tindakan_segera_yang_dilakukan">{{ __('Tindakan Segera yang Dilakukan') }}</label>
                                <textarea class="form-control" id="tindakan_segera_yang_dilakukan" rows="3" name="tindakan_segera_yang_dilakukan">{{ old('tindakan_segera_yang_dilakukan', $incident_fix->tindakan_segera_yang_dilakukan ?? '') }}</textarea>
                                @error('tindakan_segera_yang_dilakukan')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>


                            <center>
                                <label for="">ANALISA PENYEBAB MASALAH</label>
                            </center>

                            <div class="form-group">
                                <label for="rincian_dari_pemeriksaan">{{ __('Rincian Dari Pemeriksaan') }}</label>
                                <textarea class="form-control" id="rincian_dari_pemeriksaan" rows="3" name="rincian_dari_pemeriksaan">{{ old('rincian_dari_pemeriksaan', $incident_fix->rincian_dari_pemeriksaan ?? '') }}</textarea>
                                @error('rincian_dari_pemeriksaan')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                                <center>
                                    <label for="">KEMUNGKINAN PENYEBAB LANGSUNG</label>
                                </center>
                                @php
                                $oldA = old('penyebab_langsung_1_a', $incident_fix->penyebab_langsung_1_a ?? '');
                                $oldB = old('penyebab_langsung_1_b', $incident_fix->penyebab_langsung_1_b ?? '');
                                $oldA2 = old('penyebab_langsung_2_a', $incident_fix->penyebab_langsung_2_a ?? '');
                                $oldB2 = old('penyebab_langsung_2_b', $incident_fix->penyebab_langsung_2_b ?? '');
                                $oldA3 = old('penyebab_langsung_3_a', $incident_fix->penyebab_langsung_3_a ?? '');
                                $oldB3 = old('penyebab_langsung_3_b', $incident_fix->penyebab_langsung_3_b ?? '');
                                @endphp
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="penyebab_langsung_1_a" class="form-control-label">{{ __('Penyebab Langsung 1 A') }}</label>
                                            <select class="form-control" id="penyebab_langsung_1_a" name="penyebab_langsung_1_a" onchange="updateSecondDropdown('1')">
                                                <option value="">{{ __('Pilih Kategori') }}</option>
                                                <option value="Tindakan Tidak Standar" {{ $oldA == 'Tindakan Tidak Standar' ? 'selected' : '' }}>Tindakan Tidak Standar</option>
                                                <option value="Kondisi Tidak Standar" {{ $oldA == 'Kondisi Tidak Standar' ? 'selected' : '' }}>Kondisi Tidak Standar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="penyebab_langsung_1_b" class="form-control-label">{{ __('Penyebab Langsung 1 B') }}</label>
                                            <select class="form-control" id="penyebab_langsung_1_b" name="penyebab_langsung_1_b">
                                                <option value="">{{ __('Pilih Sub-Kategori') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Bagian 2 -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="penyebab_langsung_2_a">{{ __('Penyebab Langsung 2 A') }}</label>
                                            <select class="form-control" id="penyebab_langsung_2_a" name="penyebab_langsung_2_a" onchange="updateSecondDropdown('2')">
                                                <option value="">{{ __('Pilih Kategori') }}</option>
                                                <option value="Tindakan Tidak Standar" {{ $oldA2 == 'Tindakan Tidak Standar' ? 'selected' : '' }}>Tindakan Tidak Standar</option>
                                                <option value="Kondisi Tidak Standar" {{ $oldA2 == 'Kondisi Tidak Standar' ? 'selected' : '' }}>Kondisi Tidak Standar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="penyebab_langsung_2_b">{{ __('Penyebab Langsung 2 B') }}</label>
                                            <select class="form-control" id="penyebab_langsung_2_b" name="penyebab_langsung_2_b">
                                                <option value="">{{ __('Pilih Kategori') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="penyebab_langsung_3_a">{{ __('Penyebab Langsung 3 A') }}</label>
                                            <select class="form-control" id="penyebab_langsung_3_a" name="penyebab_langsung_3_a" onchange="updateSecondDropdown('3')">
                                                <option value="">{{ __('Pilih Kategori') }}</option>
                                                <option value="Tindakan Tidak Standar" {{ $oldA3 == 'Tindakan Tidak Standar' ? 'selected' : '' }}>Tindakan Tidak Standar</option>
                                                <option value="Kondisi Tidak Standar" {{ $oldA3 == 'Kondisi Tidak Standar' ? 'selected' : '' }}>Kondisi Tidak Standar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="penyebab_langsung_3_b">{{ __('Penyebab Langsung 3 B') }}</label>
                                            <select class="form-control" id="penyebab_langsung_3_b" name="penyebab_langsung_3_b">
                                                <option value="">{{ __('Pilih Kategori') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <center>
                                    <label for="">DESKRIPSI TINDAKAN KEJADIAN</label>

                                </center>
                            </div>
                            @php
                            $oldPDA1 = old('penyebab_dasar_1_a', $incident_fix->penyebab_dasar_1_a ?? '');
                            $oldPDB1 = old('penyebab_dasar_1_b', $incident_fix->penyebab_dasar_1_b ?? '');
                            $oldPDC1 = old('penyebab_dasar_1_c', $incident_fix->penyebab_dasar_1_c ?? '');

                            $oldPDA2 = old('penyebab_dasar_2_a', $incident_fix->penyebab_dasar_2_a ?? '');
                            $oldPDB2 = old('penyebab_dasar_2_b', $incident_fix->penyebab_dasar_2_b ?? '');
                            $oldPDC2 = old('penyebab_dasar_2_c', $incident_fix->penyebab_dasar_2_c ?? '');

                            $oldPDA3 = old('penyebab_dasar_3_a', $incident_fix->penyebab_dasar_3_a ?? '');
                            $oldPDB3 = old('penyebab_dasar_3_b', $incident_fix->penyebab_dasar_3_b ?? '');
                            $oldPDC3 = old('penyebab_dasar_3_c', $incident_fix->penyebab_dasar_3_c ?? '');
                            @endphp

                            <div class="row">
                                <!-- Dropdown 1a -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="penyebab_dasar_1_a" class="form-control-label">{{ __('Kemungkinan Penyebab Dasar 1 A') }}</label>
                                        <select class="form-control" id="penyebab_dasar_1_a" name="penyebab_dasar_1_a" onchange="updateKemungkinanSecondDropdown('1')">
                                            <option value="">{{ __('Pilih Kategori') }}</option>
                                            <option value="Faktor Pribadi" {{ $oldPDA1 == 'Faktor Pribadi' ? 'selected' : '' }}>{{ __('Faktor Pribadi') }}</option>
                                            <option value="Faktor Pekerjaan atau Sistem" {{ $oldPDA1 == 'Faktor Pekerjaan atau Sistem' ? 'selected' : '' }}>{{ __('Faktor Pekerjaan atau Sistem') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Dropdown 1b -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="penyebab_dasar_1_b" class="form-control-label">{{ __('Kemungkinan Penyebab Dasar 1 B') }}</label>
                                        <select class="form-control" id="penyebab_dasar_1_b" name="penyebab_dasar_1_b" onchange="updateKemungkinanThirdDropdown('1')">
                                            <option value="">{{ __('Pilih Penyebab') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Dropdown 1c -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="penyebab_dasar_1_c" class="form-control-label">{{ __('Kemungkinan Penyebab Dasar 1 C') }}</label>
                                        <select class="form-control" id="penyebab_dasar_1_c" name="penyebab_dasar_1_c">
                                            <option value="">{{ __('Pilih Penyebab') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="penyebab_dasar_2_a" class="form-control-label">{{ __('Kemungkinan Penyebab Dasar 2 A') }}</label>
                                        <select class="form-control" id="penyebab_dasar_2_a" name="penyebab_dasar_2_a" onchange="updateKemungkinanSecondDropdown('2')">
                                            <option value="">{{ __('Pilih Kategori') }}</option>
                                            <option value="Faktor Pribadi" {{ $oldPDA2 == 'Faktor Pribadi' ? 'selected' : '' }}>{{ __('Faktor Pribadi') }}</option>
                                            <option value="Faktor Pekerjaan atau Sistem" {{ $oldPDA2 == 'Faktor Pekerjaan atau Sistem' ? 'selected' : '' }}>{{ __('Faktor Pekerjaan atau Sistem') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="penyebab_dasar_2_b" class="form-control-label">{{ __('Kemungkinan Penyebab Dasar 2 B') }}</label>
                                        <select class="form-control" id="penyebab_dasar_2_b" name="penyebab_dasar_2_b" onchange="updateKemungkinanThirdDropdown('2')">
                                            <option value="">{{ __('Pilih Penyebab') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="penyebab_dasar_2_c" class="form-control-label">{{ __('Kemungkinan Penyebab Dasar 2 C') }}</label>
                                        <select class="form-control" id="penyebab_dasar_2_c" name="penyebab_dasar_2_c">
                                            <option value="">{{ __('Pilih Penyebab') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="penyebab_dasar_3_a" class="form-control-label">{{ __('Kemungkinan Penyebab Dasar 3 A') }}</label>
                                        <select class="form-control" id="penyebab_dasar_3_a" name="penyebab_dasar_3_a" onchange="updateKemungkinanSecondDropdown('3')">
                                            <option value="">{{ __('Pilih Kategori') }}</option>
                                            <option value="Faktor Pribadi" {{ $oldPDA3 == 'Faktor Pribadi' ? 'selected' : '' }}>{{ __('Faktor Pribadi') }}</option>
                                            <option value="Faktor Pekerjaan atau Sistem" {{ $oldPDA3 == 'Faktor Pekerjaan atau Sistem' ? 'selected' : '' }}>{{ __('Faktor Pekerjaan atau Sistem') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="penyebab_dasar_3_b" class="form-control-label">{{ __('Kemungkinan Penyebab Dasar 3 B') }}</label>
                                        <select class="form-control" id="penyebab_dasar_3_b" name="penyebab_dasar_3_b" onchange="updateKemungkinanThirdDropdown('3')">
                                            <option value="">{{ __('Pilih Penyebab') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="penyebab_dasar_3_c" class="form-control-label">{{ __('Kemungkinan Penyebab Dasar 3 C') }}</label>
                                        <select class="form-control" id="penyebab_dasar_3_c" name="penyebab_dasar_3_c">
                                            <option value="">{{ __('Pilih Penyebab') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <center>
                                <label for="">AREA KENDALI UNTUK TINDAKAN PENINGKATAN</label>
                            </center>


                            <div class="form-group d-flex">
                                {{-- Bagian 1 --}}

                                <div class="me-2" style="flex: 1;">
                                    <label for="tindakan_kendali_untuk_peningkatan_1_a">{{ __('Tindakan Kendali Untuk Peningkatan 1 A') }}</label>
                                    @php
                                    $value1a = old('tindakan_kendali_untuk_peningkatan_1_a', $incident_fix->tindakan_kendali_untuk_peningkatan_1_a ?? '');
                                    @endphp
                                    <select name="tindakan_kendali_untuk_peningkatan_1_a" class="form-control">
                                        <option value="" {{ $value1a == '' ? 'selected' : '' }}>Pilih Kategori</option>
                                        <option value="Sistem tidak memadai" {{ $value1a == 'Sistem tidak memadai' ? 'selected' : '' }}>Sistem tidak memadai</option>
                                        <option value="Standar kinerja tidak memadai" {{ $value1a == 'Standar kinerja tidak memadai' ? 'selected' : '' }}>Standar kinerja tidak memadai</option>
                                        <option value="Kepatuhan terhadap standar kinerja tidak memadai" {{ $value1a == 'Kepatuhan terhadap standar kinerja tidak memadai' ? 'selected' : '' }}>Kepatuhan terhadap standar kinerja tidak memadai</option>
                                    </select>

                                </div>


                                @php
                                $value1b = old('tindakan_kendali_untuk_peningkatan_1_b', $incident_fix->tindakan_kendali_untuk_peningkatan_1_b ?? '');
                                $value2b = old('tindakan_kendali_untuk_peningkatan_2_b', $incident_fix->tindakan_kendali_untuk_peningkatan_2_b ?? '');
                                $value3b = old('tindakan_kendali_untuk_peningkatan_3_b', $incident_fix->tindakan_kendali_untuk_peningkatan_3_b ?? '');
                                $optionsB = [
                                "Kepemimpinan",
                                "Perencanaan dan Administrasi",
                                "Evaluasi Risiko",
                                "Sumber Daya Manusia",
                                "Jaminan Kepatuhan",
                                "Manajemen Proyek",
                                "Pelatihan dan Kompetensi",
                                "Komunikasi dan Promosi",
                                "Pengendalian Risiko",
                                "Manajemen Aset",
                                "Manajemen Kontraktor dan Pembelian",
                                "Kesiapsiagaan Darurat",
                                "Belajar dari Peristiwa",
                                "Pemantauan Risiko",
                                "Hasil dan Tinjauan"
                                ];
                                @endphp

                                <div class="ms-2" style="flex: 1;">
                                    <label for="tindakan_kendali_untuk_peningkatan_1_b">{{ __('Tindakan Kendali Untuk Peningkatan 1 B') }}</label>
                                    <select class="form-control" id="tindakan_kendali_untuk_peningkatan_1_b" name="tindakan_kendali_untuk_peningkatan_1_b" onchange="updatePeningkatanDropdown('1')">
                                        <option value="" {{ $value1b == '' ? 'selected' : '' }}>{{ __('Pilih Kategori') }}</option>
                                        @foreach ($optionsB as $option)
                                        <option value="{{ $option }}" {{ $value1b == $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="ms-2" style="flex: 1;">
                                    <label for="tindakan_kendali_untuk_peningkatan_1_c">{{ __('Tindakan Kendali Untuk Peningkatan 1 C') }}</label>
                                    <select class="form-control" id="tindakan_kendali_untuk_peningkatan_1_c" name="tindakan_kendali_untuk_peningkatan_1_c">
                                        <option value="">{{ __('Pilih Sub-Kategori') }}</option>
                                        @if(old('tindakan_kendali_untuk_peningkatan_1_c'))
                                        <option value="{{ old('tindakan_kendali_untuk_peningkatan_1_c') }}" selected>{{ old('tindakan_kendali_untuk_peningkatan_1_c') }}</option>
                                        @elseif(!empty($incident_fix->tindakan_kendali_untuk_peningkatan_1_c))
                                        <option value="{{ $incident_fix->tindakan_kendali_untuk_peningkatan_1_c }}" selected>{{ $incident_fix->tindakan_kendali_untuk_peningkatan_1_c }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            {{-- Bagian 2 --}}
                            <div class="form-group d-flex mt-3">
                                <div class="me-2" style="flex: 1;">
                                    <label for="tindakan_kendali_untuk_peningkatan_2_a">{{ __('Tindakan Kendali Untuk Peningkatan 2 A') }}</label>
                                    @php
                                    $value2a = old('tindakan_kendali_untuk_peningkatan_2_a', $incident_fix->tindakan_kendali_untuk_peningkatan_2_a ?? '');
                                    @endphp
                                    <select name="tindakan_kendali_untuk_peningkatan_2_a" class="form-control">
                                        <option value="" {{ $value2a == '' ? 'selected' : '' }}>Pilih Kategori</option>
                                        <option value="Sistem tidak memadai" {{ $value2a == 'Sistem tidak memadai' ? 'selected' : '' }}>Sistem tidak memadai</option>
                                        <option value="Standar kinerja tidak memadai" {{ $value2a == 'Standar kinerja tidak memadai' ? 'selected' : '' }}>Standar kinerja tidak memadai</option>
                                        <option value="Kepatuhan terhadap standar kinerja tidak memadai" {{ $value2a == 'Kepatuhan terhadap standar kinerja tidak memadai' ? 'selected' : '' }}>Kepatuhan terhadap standar kinerja tidak memadai</option>
                                    </select>
                                </div>

                                <div class="ms-2" style="flex: 1;">
                                    <label for="tindakan_kendali_untuk_peningkatan_2_b">{{ __('Tindakan Kendali Untuk Peningkatan 2 B') }}</label>
                                    <select class="form-control" id="tindakan_kendali_untuk_peningkatan_2_b" name="tindakan_kendali_untuk_peningkatan_2_b" onchange="updatePeningkatanDropdown('2')">
                                        <option value="" {{ $value2b == '' ? 'selected' : '' }}>{{ __('Pilih Kategori') }}</option>
                                        @foreach ($optionsB as $option)
                                        <option value="{{ $option }}" {{ $value2b == $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="ms-2" style="flex: 1;">
                                    <label for="tindakan_kendali_untuk_peningkatan_2_c">{{ __('Tindakan Kendali Untuk Peningkatan 2 C') }}</label>
                                    <select class="form-control" id="tindakan_kendali_untuk_peningkatan_2_c" name="tindakan_kendali_untuk_peningkatan_2_c">
                                        <option value="">{{ __('Pilih Sub-Kategori') }}</option>
                                        @if(old('tindakan_kendali_untuk_peningkatan_2_c'))
                                        <option value="{{ old('tindakan_kendali_untuk_peningkatan_2_c') }}" selected>{{ old('tindakan_kendali_untuk_peningkatan_2_c') }}</option>
                                        @elseif(!empty($incident_fix->tindakan_kendali_untuk_peningkatan_2_c))
                                        <option value="{{ $incident_fix->tindakan_kendali_untuk_peningkatan_2_c }}" selected>{{ $incident_fix->tindakan_kendali_untuk_peningkatan_2_c }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            {{-- Bagian 3 --}}
                            <div class="form-group d-flex mt-3">
                                <div class="me-2" style="flex: 1;">
                                    <label for="tindakan_kendali_untuk_peningkatan_3_a">{{ __('Tindakan Kendali Untuk Peningkatan 3 A') }}</label>
                                    @php
                                    $value3a = old('tindakan_kendali_untuk_peningkatan_3_a', $incident_fix->tindakan_kendali_untuk_peningkatan_3_a ?? '');
                                    @endphp
                                    <select name="tindakan_kendali_untuk_peningkatan_3_a" class="form-control">
                                        <option value="" {{ $value3a == '' ? 'selected' : '' }}>Pilih Kategori</option>
                                        <option value="Sistem tidak memadai" {{ $value3a == 'Sistem tidak memadai' ? 'selected' : '' }}>Sistem tidak memadai</option>
                                        <option value="Standar kinerja tidak memadai" {{ $value3a == 'Standar kinerja tidak memadai' ? 'selected' : '' }}>Standar kinerja tidak memadai</option>
                                        <option value="Kepatuhan terhadap standar kinerja tidak memadai" {{ $value3a == 'Kepatuhan terhadap standar kinerja tidak memadai' ? 'selected' : '' }}>Kepatuhan terhadap standar kinerja tidak memadai</option>
                                    </select>
                                </div>

                                <div class="ms-2" style="flex: 1;">
                                    <label for="tindakan_kendali_untuk_peningkatan_3_b">{{ __('Tindakan Kendali Untuk Peningkatan 3 B') }}</label>
                                    <select class="form-control" id="tindakan_kendali_untuk_peningkatan_3_b" name="tindakan_kendali_untuk_peningkatan_3_b" onchange="updatePeningkatanDropdown('3')">
                                        <option value="" {{ $value3b == '' ? 'selected' : '' }}>{{ __('Pilih Kategori') }}</option>
                                        @foreach ($optionsB as $option)
                                        <option value="{{ $option }}" {{ $value3b == $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="ms-2" style="flex: 1;">
                                    <label for="tindakan_kendali_untuk_peningkatan_3_c">{{ __('Tindakan Kendali Untuk Peningkatan 3 C') }}</label>
                                    <select class="form-control" id="tindakan_kendali_untuk_peningkatan_1_c" name="tindakan_kendali_untuk_peningkatan_1_c">
                                        <option value="">{{ __('Pilih Sub-Kategori') }}</option>
                                        @if(old('tindakan_kendali_untuk_peningkatan_3_c'))
                                        <option value="{{ old('tindakan_kendali_untuk_peningkatan_3_c') }}" selected>{{ old('tindakan_kendali_untuk_peningkatan_3_c') }}</option>
                                        @elseif(!empty($incident_fix->tindakan_kendali_untuk_peningkatan_3_c))
                                        <option value="{{ $incident_fix->tindakan_kendali_untuk_peningkatan_3_c }}" selected>{{ $incident_fix->tindakan_kendali_untuk_peningkatan_3_c }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <center>
                                    <label for="">DESKRIPSI TINDAKAN KEJADIAN</label>

                                </center>
                            </div>
                            <div class="form-group d-flex justify-content-between">
                                <div class="me-2" style="flex: 1;">
                                    <label for="deskripsi_tindakan_pencegahan_1">Deskripsi Tindakan Pencegahan 1</label>
                                    <textarea class="form-control" id="deskripsi_tindakan_pencegahan_1" rows="3" name="deskripsi_tindakan_pencegahan_1">{{ old('deskripsi_tindakan_pencegahan_1', $incident_fix->deskripsi_tindakan_pencegahan_1 ?? '') }}</textarea>
                                    @error('deskripsi_tindakan_pencegahan_1')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="ms-2" style="flex: 1;">
                                    <label for="deskripsi_tindakan_pencegahan_2">Deskripsi Tindakan Pencegahan 2</label>
                                    <textarea class="form-control" id="deskripsi_tindakan_pencegahan_2" rows="3" name="deskripsi_tindakan_pencegahan_2">{{ old('deskripsi_tindakan_pencegahan_2', $incident_fix->deskripsi_tindakan_pencegahan_2 ?? '') }}</textarea>
                                    @error('deskripsi_tindakan_pencegahan_2')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="ms-2" style="flex: 1;">
                                    <label for="deskripsi_tindakan_pencegahan_3">Deskripsi Tindakan Pencegahan 3</label>
                                    <textarea class="form-control" id="deskripsi_tindakan_pencegahan_3" rows="3" name="deskripsi_tindakan_pencegahan_3">{{ old('deskripsi_tindakan_pencegahan_3', $incident_fix->deskripsi_tindakan_pencegahan_3 ?? '') }}</textarea>
                                    @error('deskripsi_tindakan_pencegahan_3')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <center><label for="">PIC</label></center>
                            </div>
                            <div class="form-group d-flex justify-content-between">
                                <div class="me-2" style="flex: 1;">
                                    <label for="pic_1">PIC 1</label>
                                    <input type="text" class="form-control" id="pic_1" name="pic_1" value="{{ old('pic_1', $incident_fix->pic_1 ?? '') }}">
                                    @error('pic_1')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- PIC 2 -->
                                <div class="ms-2" style="flex: 1;">
                                    <label for="pic_2">PIC 2</label>
                                    <input type="text" class="form-control" id="pic_2" name="pic_2" value="{{ old('pic_2', $incident_fix->pic_2 ?? '') }}">
                                    @error('pic_2')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- PIC 3 -->
                                <div class="ms-2" style="flex: 1;">
                                    <label for="pic_3">PIC 3</label>
                                    <input type="text" class="form-control" id="pic_3" name="pic_3" value="{{ old('pic_3', $incident_fix->pic_3 ?? '') }}">
                                    @error('pic_3')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mt-3">
                                <!-- Waktu 1 -->
                                <div class="col-12 col-md-4 mb-3">
                                    <label for="timing_1">Waktu 1</label>
                                    <input type="date" class="form-control" id="timing_1" name="timing_1" value="{{ old('timing_1', $incident_fix->timing_1 ?? '') }}">
                                    @error('timing_1')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Waktu 2 -->
                                <div class="col-12 col-md-4 mb-3">
                                    <label for="timing_2">Waktu 2</label>
                                    <input type="date" class="form-control" id="timing_2" name="timing_2" value="{{ old('timing_2', $incident_fix->timing_2 ?? '') }}">
                                    @error('timing_2')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Waktu 3 -->
                                <div class="col-12 col-md-4 mb-3">
                                    <label for="timing_3">Waktu 3</label>
                                    <input type="date" class="form-control" id="timing_3" name="timing_3" value="{{ old('timing_3', $incident_fix->timing_3 ?? '') }}">
                                    @error('timing_3')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Jumlah Hari Hilang -->
                            <div class="ms-2 mt-3" style="flex: 1;">
                                <label for="jml_hari_hilang">Jumlah Hari Hilang</label>
                                <input class="form-control" id="jml_hari_hilang" type="text" name="jml_hari_hilang" readonly value="{{ old('jml_hari_hilang', $incident_fix->jml_hari_hilang ?? '') }}">
                                @error('jml_hari_hilang')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Save Report') }}</button>
                        </div>
                </form>
            </div>

            <style>
                /* Mengatur ulang semua elemen agar tidak ada margin/padding bawaan */
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                /* Container utama */
                .container {
                    position: relative;
                    max-width: 600px;
                    /* Batas maksimum lebar container */
                    width: 100%;
                    /* Lebar penuh berdasarkan layar */
                    margin: auto;
                    /* Pusatkan container */
                    padding: 0;
                    /* Hilangkan padding bawaan */
                    overflow: hidden;
                    /* Hindari elemen yang meluap */
                }

                /* Gambar peta tubuh */
                .body-map {
                    width: 100%;
                    /* Sesuaikan gambar dengan lebar container */
                    height: auto;
                    /* Proporsional dengan lebar */
                }

                /* Hotspot pada peta tubuh */
                .hotspot {
                    position: absolute;
                    /* Posisi absolut berdasarkan container */
                    width: 3%;
                    /* Ukuran hotspot menggunakan persentase */
                    height: 3%;
                    /* Ukuran hotspot menggunakan persentase */
                    background-color: rgba(255, 0, 0, 0.5);
                    /* Warna semi transparan untuk debugging */
                    border-radius: 50%;
                    /* Membuat hotspot berbentuk lingkaran */
                    transform: translate(-50%, -50%);
                    /* Pusatkan elemen berdasarkan koordinat */
                    cursor: pointer;
                    /* Menunjukkan elemen interaktif */
                }

                /* Tooltip untuk menunjukkan detail hotspot */
                .tooltip {
                    position: absolute;
                    display: none;
                    /* Default tooltip tidak terlihat */
                    background: rgba(0, 0, 0, 0.7);
                    /* Latar belakang gelap semi transparan */
                    color: #fff;
                    /* Warna teks putih */
                    padding: 5px;
                    /* Ruang dalam */
                    border-radius: 5px;
                    /* Sudut membulat */
                    font-size: 0.8em;
                    /* Ukuran font kecil */
                }

                /* Responsif untuk layar kecil */
                @media screen and (max-width: 480px) {
                    .hotspot {
                        width: 5%;
                        /* Ukuran hotspot lebih besar untuk layar kecil */
                        height: 5%;
                    }

                    .tooltip {
                        font-size: 0.7em;
                        /* Font tooltip lebih kecil untuk layar kecil */
                    }
                }

                /* Tambahkan gaya debugging jika diperlukan */
                /* Anda dapat menghapus ini setelah memastikan semuanya bekerja */
                .hotspot:hover {
                    background-color: rgba(255, 0, 0, 0.8);
                    /* Perubahan warna saat di-hover */
                }
            </style>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <script>
                function toggleForms() {
                    const statusKejadian = document.getElementById('status_kejadian').value;
                    const additionalForms = document.getElementById('additional-forms');
                    if (statusKejadian === 'Ada') {
                        additionalForms.style.display = 'block';
                    } else {
                        additionalForms.style.display = 'none';
                    }
                }
                // Initialize the form visibility based on the current selection
                document.addEventListener('DOMContentLoaded', function() {
                    toggleForms();
                });
            </script>
        </div>
        <script>
            function updateKemungkinanSecondDropdown(phase, selectedValue = '') {
                const firstDropdown = document.getElementById(`penyebab_dasar_${phase}_a`);
                const secondDropdown = document.getElementById(`penyebab_dasar_${phase}_b`);


                const optionsMap = {
                    "Faktor Pribadi": [
                        "Kemampuan fisik atau fisiologis yang tidak memadai",
                        "Kemampuan Mental atau Psikologis yang Tidak Memadai",
                        "Stres Fisik atau Fisiologis",
                        "Mental atau Psikologis",
                        "Kurangnya Pengetahuan",
                        "Kurangnya Skill",
                        "Improper Motivation",
                        "Unclear Organizational Structure"
                    ],
                    "Faktor Pekerjaan atau Sistem": [
                        "Kepemimpinan yang Tidak Memadai",
                        "Pengawasan atau Pembinaan yang kurang memadai",
                        "Perubahan yang Tidak Memadai",
                        "Manajemen Rantai Pasokan yang Tidak Memadai",
                        "Pemeliharaan atau pemeriksaan yang tidak memadai",
                        "Keausan atau sobekan yang berlebihan",
                        "Alat atau Peralatan atau Mesin atau Perangkat yg Tidak Memadai",
                        "Produk yang Tidak Memadai",
                        "Standar Kerja atau Produksi yang Tidak Memadai",
                        "Komunikasi atau informasi yang Tidak Memadai"
                    ]
                };

                secondDropdown.innerHTML = '<option value="">{{ __("Pilih Penyebab") }}</option>';

                const selectedFirst = firstDropdown.value;
                const options = optionsMap[selectedFirst] || [];

                options.forEach(text => {
                    const opt = document.createElement('option');
                    opt.value = text;
                    opt.innerText = text;
                    if (selectedValue === text) opt.selected = true;
                    secondDropdown.appendChild(opt);
                });

                updateKemungkinanThirdDropdown(phase);
            }



            function updateKemungkinanThirdDropdown(phase, selectedValue = '') {
                const secondDropdown = document.getElementById(`penyebab_dasar_${phase}_b`);
                const thirdDropdown = document.getElementById(`penyebab_dasar_${phase}_c`);

                const secondValue = secondDropdown.value;

                const optionsMap = {
                    "Kemampuan fisik atau fisiologis yang tidak memadai": [
                        "Sensitivitas terhadap ekstrem sensorik (suhu / suara / dll.)",
                        "Kekurangan penglihatan",
                        "Kekurangan pendengaran",
                        "Kekurangan sensorik lain (sentuh/rasa/bau/seimbang)",
                        "Ketidakmampuan pernapasan",
                        "Cacat fisik permanen lainnya",
                        "Cacat sementara"
                    ],
                    "Kemampuan Mental atau Psikologis yang Tidak Memadai": [
                        "Ketakutan dan fobia",
                        "Gangguan emosional",
                        "Penyakit mental",
                        "Tingkat intelligence",
                        "Kemampuan untuk memahami",
                        "Koordinasi yang buruk",
                        "Waktu reaksi lambat",
                        "Aplilude mekanis rendah",
                        "Bakat belajar yang rendah",
                        "Kegagalan/selang memori"
                    ],
                    "Stres Fisik atau Fisiologis": [
                        "Paparan bahaya Kesehatan",
                        "Paparan suhu ekstrim",
                        "Kekurangan oksigen",
                        "Variasi tekanan atmosfer",
                        "Gerakan terkendala",
                        "Kekurangan gula darah",
                        "Alkohol/Narkoba/Stres Yang Dipaksakan Sendiri Lainnya"
                    ],
                    "Mental atau Psikologis": [
                        "Tuntutan konsentrasi/persepsi yang ekstrem",
                        "Kegiatan Tidak berarti/merendahkan",
                        "Arah / tuntutan yang membingungkan",
                        "Tuntutan / arah yang bertentangan",
                        "Keasyikan dengan masalah/Gangguan oleh kekhawatiran",
                        "Frustrasi",
                        "Penyakit mental"
                    ],
                    "Kurangnya Pengetahuan": [

                        "Instruksi/informasi yang disalahpahami",
                        "Kurangnya kesadaran situasional/persepsi risiko/kesadaran risiko"
                    ],
                    "Kurangnya Skill": [
                        "Inadequate  review instruction"
                    ],
                    "Improper Motivation": [
                        "Improper performance/behavior is tolerated/rewarded",
                        "Proper performance/behavior is discouraged/punished",
                        "Lack of incentive",
                        "Improper production incentive",
                        "Excessive frustration",
                        "Inappropriate aggression",
                        "Improper attempt to save time/effort",
                        "Improper attempt to avoid discomfort",
                        "Improper attempt to gain attention",
                        "Inadequate discipline",
                        "Inappropriate peer pressure",
                        "Improper leadership example",
                        "Inadequate performance feedback",
                        "Inadequate reinforcement of proper behavior",
                        "Abuse (intentionally)",
                        "Misuse (unintentionally)"
                    ],
                    "Unclear Organizational Structure": [
                        "Unclear/conflicting reporting relationship",
                        "Unclear/conflicting assignment of function/role",
                        "Unclear/conflicting accountability/responsibility/task"
                    ],
                    "Kepemimpinan yang Tidak Memadai": [
                        "HSEQ/strategi aset yang tidak memadai",
                        "pengembangan kepemimpinan yang tidak memadai",
                        "delegatiosi yang tidak memadai",
                        "standard yang tidak memadai",
                        "Komunikasi/implementasi kebijakan/prosedur/praktik yang tidak memadai",
                        "Kebijakan/prosedur/praktik yang bertentangan",
                        "Inadequate work/process planning/programming",
                        "Membenarkan penyimpangan dari kebijakan/prosedur/praktik",
                        "Memaafkan penyalahgunaan peralatan/alat",
                        "Memaafkan perilaku yang tidak pantas/tidak perlu",
                        "Informasi manajemen yang tidak memadai"
                    ],
                    "Pengawasan atau Pembinaan yang kurang memadai": [
                        "Umpan Balik Kinerja yang tidak memadai"
                    ],
                    "Perubahan yang Tidak Memadai": [
                        "Instruksi/orientasi/pelatihan yang tidak memadai",
                        "dokumen informasi yang memadai dalam supervisi/pembinaan",
                        "pengetahuan pengawasan/manajemen kurang",
                        "Kesesuaian yang tidak memadai antara kualifikasi dan persyaratan pekerjaan/tugas",
                        "Pengukuran dan evaluasi kinerja yang tidak memadai",
                        "Umpan Balik Kinerja yang tidak memadai"
                    ],
                    "Manajemen Rantai Pasokan yang Tidak Memadai": [
                        "lnadequate HSEQ identifikasi bahaya/evaluasi risiko dalam desain",
                        "Identifikasi mode kegagalan yang tidak memadai",
                        "Evaluasi yang tidak memadai terhadap persyaratan pelanggan/pemangku kepentingan",
                        "Identifikasi persyaratan hukum yang tidak memadai",
                        "Pertimbangan faktor manusia/ergonomis yang tidak memadai dalam desain",
                        "Proses desain / standar / spesifikasi / kriteria yang tidak memadai",
                        "Otomatisasi kontrol proses yang tidak memadai",
                        "Standar / spesifikasi (teknis) yang tidak memadai atau tidak ada",
                        "Ulasan proyek HSEQ yang tidak memadai",
                        "lnpemantauan yang memadai dari konstruksi / fabrikasi / perakitan",
                        "Penilaian kesiapan operasional yang tidak memadai",
                        "Proses commissioning/serah terima yang tidak memadai",
                        "Pemantauan operasi awal yang tidak memadai",
                        "Manajemen perubahan yang tidak memadai"
                    ],
                    "Pemeliharaan atau pemeriksaan yang tidak memadai": [
                        "Pengangkutan material yang tidak tepat",
                        "lnadequate umur simpan / validasi untuk penggunaan kembali Bahan / peralatan",
                        "Identifikasi materi yang tidak memadai",
                        "Pembuangan sisa/limbah yang tidak benar",
                        "Pemilihan kontraktor/pemasok yang tidak memadai"
                    ],
                    "Pengawasan atau Pembinaan yang kurang memadai": [
                        "Metode/interval inspeksi yang memadai",
                        "Tidak dapat memeriksa"
                    ],
                    "Keausan atau sobekan yang berlebihan": [
                        ""
                    ],
                    "Alat atau Peralatan atau Mesin atau Perangkat yg Tidak Memadai": [
                        "Penghapusan & penggantian yang tidak sesuai",
                    ],
                    "Produk yang Tidak Memadai": [
                        ""
                    ],
                    "Standar Kerja atau Produksi yang Tidak Memadai": [
                        "Terjemahan bahasa yang tidak memadai",
                        "Penggunaan bahasa yang benar",
                        "Pelatihan standar yang memadai",
                        "Penguatan standar yang memadai dengan tanda, kode warna dan alat bantu kerja",
                        "Pemantauan kepatuhan standar yang memadai"
                    ],
                    "Komunikasi atau informasi yang Tidak Memadai": [
                        "Metode/teknik komunikasi yang digunakan cukup memadai"
                    ],
                }

                thirdDropdown.innerHTML = '<option value="">{{ __("Pilih Penyebab") }}</option>';
                const options = optionsMap[secondValue] || [];

                options.forEach(text => {
                    const opt = document.createElement('option');
                    opt.value = text;
                    opt.innerText = text;
                    if (selectedValue === text) opt.selected = true;
                    thirdDropdown.appendChild(opt);
                });
            }

            function initializeDropdowns() {
                const oldData = {
                    '1': {
                        a: '{{ $oldPDA1 }}',
                        b: '{{ $oldPDB1 }}',
                        c: '{{ $oldPDC1 }}'
                    },
                    '2': {
                        a: '{{ $oldPDA2 }}',
                        b: '{{ $oldPDB2 }}',
                        c: '{{ $oldPDC2 }}'
                    },
                    '3': {
                        a: '{{ $oldPDA3 }}',
                        b: '{{ $oldPDB3 }}',
                        c: '{{ $oldPDC3 }}'
                    },
                };

                Object.entries(oldData).forEach(([phase, values]) => {
                    const dropdownA = document.getElementById(`penyebab_dasar_${phase}_a`);
                    dropdownA.value = values.a;
                    updateKemungkinanSecondDropdown(phase, values.b);
                    updateKemungkinanThirdDropdown(phase, values.c);
                });
            }

            document.addEventListener('DOMContentLoaded', initializeDropdowns);
        </script>
        <script>
            const hotspots = document.querySelectorAll('.hotspot');
            const tooltip = document.getElementById('tooltip');

            const formInputs = [
                document.getElementById('bagian_tubuh_luka'),
                document.getElementById('bagian_tubuh_luka2'),
                document.getElementById('bagian_tubuh_luka3')
            ];

            // Fungsi untuk mengisi input kosong pertama
            function fillNextEmptyInput(value) {
                for (let i = 0; i < formInputs.length; i++) {
                    if (!formInputs[i].value) {
                        formInputs[i].value = value;
                        return;
                    }
                }
                alert('Semua form sudah terisi!');
            }

            hotspots.forEach(hotspot => {
                const value = hotspot.getAttribute('data-value');

                hotspot.addEventListener('click', () => {
                    fillNextEmptyInput(value);
                });

                // Tooltip saat hover
                hotspot.addEventListener('mouseover', (event) => {
                    tooltip.textContent = value;
                    tooltip.style.left = event.pageX + 10 + 'px';
                    tooltip.style.top = event.pageY + 10 + 'px';
                    tooltip.style.display = 'block';
                });

                hotspot.addEventListener('mouseout', () => {
                    tooltip.style.display = 'none';
                });
            });

            formInputs.forEach((input, index) => {
                input.addEventListener('input', () => {

                });
            });
        </script>
        <script>
            const oldValues = {
                1: {
                    b: "{{ addslashes(old('tindakan_kendali_untuk_peningkatan_1_b')) }}",
                    c: "{{ addslashes(old('tindakan_kendali_untuk_peningkatan_1_c')) }}"
                },
                2: {
                    b: "{{ addslashes(old('tindakan_kendali_untuk_peningkatan_2_b')) }}",
                    c: "{{ addslashes(old('tindakan_kendali_untuk_peningkatan_2_c')) }}"
                },
                3: {
                    b: "{{ addslashes(old('tindakan_kendali_untuk_peningkatan_3_b')) }}",
                    c: "{{ addslashes(old('tindakan_kendali_untuk_peningkatan_3_c')) }}"
                }
            };

            const optionsC = {
                "Kepemimpinan": [
                    "Tujuan dan Nilai", "Sasaran", "Kebijakan", "Strategi",
                    "Keterlibatan Pemangku Kepentingan", "Proses Bisnis", "Risiko Bisnis",
                    "Tanggung Jawab", "Komitmen Manajemen", "Kepemimpinan Keselamatan Proses"
                ],
                "Perencanaan dan Administrasi": [
                    "Perencanaan Bisnis", "Perencanaan dan Pengendalian Pekerjaan",
                    "Pelacakan Tindakan", "Dokumentasi Sistem Manajemen", "Rekaman", "Perencanaan Keselamatan Proses"
                ],
                "Evaluasi Risiko": [
                    "Identifikasi dan Evaluasi Bahaya Kesehatan", "Identifikasi dan Evaluasi Bahaya Keselamatan",
                    "Identifikasi dan Evaluasi Bahaya Keamanan", "Identifikasi dan Evaluasi Bahaya Lingkungan",
                    "Identifikasi dan Evaluasi Harapan Pelanggan", "Evaluasi Risiko Tugas",
                    "Informasi Keselamatan Proses", "Analisis Bahaya Proses"
                ],
                "Sumber Daya Manusia": [
                    "Sistem Sumber Daya Manusia", "Rekrutmen", "Manajemen Kinerja Individu",
                    "Pengakuan dan Disiplin", "Keluar dari Organisasi", "Manajemen Perubahan Organisasi",
                    "Sumber Daya Manusia dalam Keselamatan Proses"
                ],
                "Jaminan Kepatuhan": [
                    "Peraturan", "Izin Operasi Eksternal", "Kode dan Standar Industri",
                    "Pelaporan ke Otoritas", "Keamanan Informasi", "Penjaminan Produk",
                    "Penilaian Kepatuhan", "Peraturan Keselamatan Proses", "Keamanan Informasi Proses"
                ],
                "Manajemen Proyek": [
                    "Koordinasi Proyek", "Perencanaan Proyek", "Pelaksanaan Proyek",
                    "Pengendalian Proyek", "Penutupan Proyek", "Tinjauan Keselamatan Proses Proyek"
                ],
                "Pelatihan dan Kompetensi": [
                    "Pelatihan dan Kompetensi Umum", "Pelatihan Keselamatan Proses",
                    "Pelatihan Khusus", "Pelatihan Pelaksanaan Darurat", "Evaluasi Kompetensi"
                ],
                "Komunikasi dan Promosi": [
                    "Komunikasi Internal", "Komunikasi Eksternal", "Promosi Keselamatan Proses",
                    "Pelibatan Karyawan", "Pelibatan Pemangku Kepentingan"
                ],
                "Pengendalian Risiko": [
                    "Pengendalian Risiko Operasi", "Pengendalian Risiko Perawatan",
                    "Pengendalian Risiko Perubahan", "Pengendalian Risiko Kontraktor",
                    "Pengendalian Risiko Darurat"
                ],
                "Manajemen Aset": [
                    "Inventaris Aset", "Pemeliharaan Aset", "Perencanaan Pemeliharaan",
                    "Pemeliharaan Berbasis Kondisi", "Penggantian Aset"
                ],
                "Manajemen Kontraktor dan Pembelian": [
                    "Evaluasi Kontraktor", "Pemilihan Kontraktor", "Pengawasan Kontraktor",
                    "Pengendalian Pembelian", "Pengendalian Material"
                ],
                "Kesiapsiagaan Darurat": [
                    "Rencana Darurat", "Latihan Darurat", "Pelaporan Darurat",
                    "Tanggap Darurat", "Peralatan Darurat"
                ],
                "Belajar dari Peristiwa": [
                    "Pelaporan Peristiwa", "Investigasi Peristiwa",
                    "Tindakan Korektif", "Pembelajaran dan Perbaikan"
                ],
                "Pemantauan Risiko": [
                    "Pemantauan Risiko Operasional", "Audit dan Inspeksi",
                    "Pemantauan Kinerja", "Pengujian Keselamatan"
                ],
                "Hasil dan Tinjauan": [
                    "Tinjauan Manajemen", "Laporan Kinerja",
                    "Evaluasi Program Keselamatan", "Tindakan Perbaikan"
                ],
            };

            function updatePeningkatanDropdown(index) {
                const bSelect = document.getElementById(`tindakan_kendali_untuk_peningkatan_${index}_b`);
                const cSelect = document.getElementById(`tindakan_kendali_untuk_peningkatan_${index}_c`);
                const selectedB = bSelect.value;

                // Kosongkan C dulu
                cSelect.innerHTML = '<option value="">{{ __("Pilih Sub-Kategori") }}</option>';

                if (optionsC[selectedB]) {
                    optionsC[selectedB].forEach(option => {
                        const opt = document.createElement('option');
                        opt.value = option;
                        opt.textContent = option;

                        if (oldValues[index].c === option) {
                            opt.selected = true;
                        }

                        cSelect.appendChild(opt);
                    });
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                [1, 2, 3].forEach(i => {
                    if (oldValues[i].b) {
                        updatePeningkatanDropdown(i);
                    }
                });
            });
        </script>


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const oldB = "{{ addslashes($oldB) }}"; // aman karena escape tanda kutip
                const firstDropdown = document.getElementById('penyebab_langsung_1_a');
                const secondDropdown = document.getElementById('penyebab_langsung_1_b');

                updateSecondDropdown('1', oldB); // pasang old value ke dropdown ke-2
            });

            function updateSecondDropdown(phase, selectedB = '') {
                const firstDropdown = document.getElementById(`penyebab_langsung_${phase}_a`);
                const secondDropdown = document.getElementById(`penyebab_langsung_${phase}_b`);
                secondDropdown.innerHTML = '<option value="">Pilih Sub-Kategori</option>';

                const options = {
                    "Tindakan Tidak Standar": [
                        "Mengoperasikan Peralatan Tanpa Wewenang",
                        "Kegagalan untuk Menginformasikan / Memperingatkan",
                        "Gagal Mengamankan",
                        "Mengoperasikan pada Kecepatan yang Tidak Benar",
                        "Membuat Perangkat HSEQ Kritis Tidak Beroperasi",
                        "Menggunakan Alat/Peralatan/Mesin/Perangkat yang Cacat",
                        "Pengoperasian Peralatan/Peralatan/Mesin/Perangkat yang Tidak Benar",
                        "Inadequate Servicing of Equipment/Machinery in Using Incorrect/Improper Material",
                        "Operation",
                        "Gagal Menggunakan Alat Pelindung Diri dengan Benar",
                        "Pemuatan yang tidak benar",
                        "Penempatan yang Tidak Tepat",
                        "Pengangkatan yang Tidak Tepat",
                        "Posisi yang Tidak Tepat untuk Tugas",
                        "Perilaku Tidak Pantas/Tidak Perlu",
                        "Di Bawah Pengaruh Obat/Alkohol/Obat-obatan",
                        "Kegagalan untuk Mengikuti Prosedur / Instruksi",
                        "Kegagalan untuk Mengidentifikasi Bahaya",
                        "Undang-undang Di Bawah Standar oleh Pihak Eksternal (tidak di bawah kendali sendiri)"
                    ],
                    "Kondisi Tidak Standar": [
                        "Kondisi Lantai/Permukaan yang Tidak Memadai",
                        "Alat/Peralatan Rusak",
                        "Alat/Perangkat yang tidak benar/tidak memadai",
                        "Integritas Peralatan yang Tidak Memadai",
                        "Mode Operasional yang Tidak Memadai",
                        "Kegagalan untuk mendeteksi / mengukur",
                        "Pengukuran / konversi sinyal yang tidak tepat",
                        "Material yang Salah",
                        "Komposisi Material/Gas yang Salah",
                        "Penjaga/Penghalang yang Tidak Memadai",
                        "Perlindungan Diri yang Tidak Memadai/Tidak Tepat Peralatan",
                        "Kemacetan/Ruang Terbatas untuk Beraksi",
                        "Sistem Peringatan yang Tidak Memadai",
                        "Adanya Atmosfer yang Mudah Terbakar/Meledak",
                        "Keberadaan Bahan Berbahaya yang Tidak Sah",
                        "Rumah Tangga / Ketertiban yang Buruk",
                        "Bahaya Radiasi",
                        "Penerangan Kurang/Berlebihan",
                        "Bahaya Getaran",
                        "Suhu ekstrim",
                        "Tekanan ekstrim",
                        "Ventilasi yang Tidak Memadai",
                        "Informasi yang Tidak Memadai",
                        "Kondisi/Lingkungan Kerja Berbahaya Lainnya",
                        "Bahaya dari Sumber Eksternal",
                        "Paparan Kondisi Cuaca Buruk"
                    ]
                };

                const selectedCategory = firstDropdown.value;
                if (options[selectedCategory]) {
                    options[selectedCategory].forEach(optionText => {
                        const option = document.createElement("option");
                        option.value = optionText;
                        option.innerText = optionText;
                        if (optionText === selectedB) {
                            option.selected = true;
                        }
                        secondDropdown.appendChild(option);
                    });
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                updateSecondDropdown('2', "{{ addslashes($oldB2) }}");
                updateSecondDropdown('3', "{{ addslashes($oldB3) }}");
            });
        </script>
        <script>
            $(document).ready(function() {
                const oldPerusahaan = "{{ old('perusahaan_id', $incident_fix->perusahaan_id ?? '') }}";
                const oldBagian = "{{ old('bagian', $incident_fix->bagian ?? '') }}";

                // Fungsi load Bagian berdasarkan perusahaan
                function loadBagian(code, selectedBagian = '') {
                    if (code) {
                        $.ajax({
                            url: '/operator/master/perusahaan/get-bagian/' + encodeURIComponent(code),
                            type: 'GET',
                            success: function(data) {
                                $('#bagian').empty();
                                $('#bagian').append('<option value="" disabled>Pilih Bagian</option>');
                                $.each(data, function(index, bagian) {
                                    let selected = (bagian.nama_bagian === selectedBagian) ? 'selected' : '';
                                    $('#bagian').append('<option value="' + bagian.nama_bagian + '" ' + selected + '>' + bagian.nama_bagian + '</option>');
                                });

                                // Kalau belum dipilih (e.g., saat halaman pertama kali load)
                                if (selectedBagian) {
                                    $('#bagian').val(selectedBagian);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("Gagal ambil bagian:", error);
                            }
                        });
                    } else {
                        $('#bagian').empty();
                        $('#bagian').append('<option value="" disabled selected>Pilih Bagian</option>');
                    }
                }

                // Saat user ganti perusahaan
                $('#perusahaan_id').on('change', function() {
                    const code = $(this).val();
                    loadBagian(code);
                });

                // Trigger otomatis saat halaman load, jika ada old value
                if (oldPerusahaan) {
                    $('#perusahaan_id').val(oldPerusahaan);
                    loadBagian(oldPerusahaan, oldBagian);
                }
            });
        </script>

        <script>
            function hitungHariHilang() {
                $.ajax({
                    url: "{{ url('operator/incident/get-jumlah-hari-hilang') }}",
                    method: 'POST',
                    data: {
                        jenis1: $('#jenis_luka_sakit').val(),
                        jenis2: $('#jenis_luka_sakit2').val(),
                        jenis3: $('#jenis_luka_sakit3').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#jml_hari_hilang').val(response.total);
                    }
                });
            }

            $('#jenis_luka_sakit, #jenis_luka_sakit2, #jenis_luka_sakit3').on('change', hitungHariHilang);
        </script>

    </div>
    @endsection