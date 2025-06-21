<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\GLAccountController;
use App\Http\Controllers\ReferencesController;
use App\Http\Controllers\DailyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HseInspectorController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\NcrController;
use App\Http\Controllers\PpeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MaterialGroupController;
use App\Http\Controllers\NonCompliantController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\PurchasingGroupController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Models\Ppe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => 'auth'], function () {


	Route::get('/admin/home', [HomeController::class, 'adminsystem'])->name('adminsystem.home')->middleware('userAkses:adminsystem');
	Route::get('/operator/home', [HomeController::class, 'operator'])->name('operator.home')->middleware('userAkses:operator');
	Route::get('/tamu/home', [HomeController::class, 'tamu'])->name('tamu.home')->middleware('userAkses:tamu');

	Route::get('billing', function () {
		return view('billing');
	})->name('billing');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

	Route::get('rtl', function () {
		return view('rtl');
	})->name('rtl');

	Route::get('user-management', function () {
		return view('laravel-examples/user-management');
	})->name('user-management');

	Route::get('tables', function () {
		return view('tables');
	})->name('tables');

	Route::get('virtual-reality', function () {
		return view('virtual-reality');
	})->name('virtual-reality');

	Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('sign-in');

	Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('sign-up');

	Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');

	Route::group(['middleware' => 'role:adminsystem'], function () {
		// Incident management
		Route::prefix('adminsystem/incident')->name('adminsystem.incident.')->group(function () {
			Route::get('/', [IncidentController::class, 'index'])->name('index');
			Route::post('/approve/{id}', [IncidentController::class, 'approve'])->name('approve');
			Route::post('/reject/{id}', [IncidentController::class, 'reject'])->name('reject');
			Route::get('/export', [IncidentController::class, 'export'])->name('export');
			Route::get('/export-pdf', [IncidentController::class, 'exportPdf'])->name('exportPdf');
			Route::get('/pdf-satuan/{id}/', [IncidentController::class, 'downloadSatuan'])->name('downloadSatuan');
			Route::get('/create', [IncidentController::class, 'create'])->name('create');
			Route::get('/master', [IncidentController::class, 'master'])->name('master');
			Route::post('/', [IncidentController::class, 'store'])->name('store');
			Route::get('/{id}/edit', [IncidentController::class, 'edit'])->name('edit');
			Route::get('/sent/{id}', [IncidentController::class, 'sent_show'])->name('sent_show');
			Route::get('/{id}/sent_edit', [IncidentController::class, 'sent_edit'])->name('sent_edit');
			Route::put('/sent{id}', [IncidentController::class, 'sent_update'])->name('sent_update');
			Route::put('/{id}', [IncidentController::class, 'update'])->name('update');
			Route::get('/{id}', [IncidentController::class, 'show'])->name('show');
			Route::delete('/{id}', [IncidentController::class, 'destroy'])->name('destroy');
			Route::delete('/sent_destroy/{id}', [IncidentController::class, 'sent_destroy'])->name('sent_destroy');
			Route::delete('/draft_destroy/{id}', [IncidentController::class, 'draft_destroy'])->name('draft_destroy');
			Route::get('/search', [IncidentController::class, 'search'])->name('search');
			Route::post('/incident-request', [IncidentController::class, 'storeRequest'])->name('storeRequest');
			Route::post('/request', [IncidentController::class, 'submitRequest'])->name('request');
			Route::get('/get-bagian/{perusahaan_name}', [IncidentController::class, 'getBagian'])->name('getBagian');
			Route::post('/get-jumlah-hari-hilang', [IncidentController::class, 'getJumlahHariHilang'])->name('getJumlahHariHilang');
		});

		Route::prefix('adminsystem/ppe')->name('adminsystem.ppe.')->group(function () {
			Route::get('/', [PpeController::class, 'index'])->name('index');
			Route::get('/export', [PpeController::class, 'export'])->name('export');
			Route::get('/export-pdf', [PpeController::class, 'exportPdf'])->name('exportPdf');
			Route::get('/create', [PpeController::class, 'create'])->name('create');
			Route::post('/', [PpeController::class, 'store'])->name('store');
			Route::get('/{id}/edit', [PpeController::class, 'edit'])->name('edit');
			Route::get('/show{id}', [PpeController::class, 'show'])->name('show');
			Route::put('/{id}', [PpeController::class, 'update'])->name('update');
			Route::delete('/{id}', [PpeController::class, 'destroy'])->name('destroy');
			Route::get('/search', [PpeController::class, 'search'])->name('search');
			Route::get('/sent_edit/{id}', [PpeController::class, 'sent_edit'])->name('sent_edit');
			Route::put('/sent_update/{id}', [PpeController::class, 'sent_update'])->name('sent_update');
			Route::delete('/sent_destroy/{id}', [PpeController::class, 'sent_destroy'])->name('sent_destroy');
			Route::post('/ppe-request', [PpeController::class, 'storeRequest'])->name('storeRequest');
			Route::post('/request', [PpeController::class, 'submitRequest'])->name('request');
			Route::post('/approve/{id}', [PpeController::class, 'approve'])->name('approve');
			Route::post('/reject/{id}', [PpeController::class, 'reject'])->name('reject');
		});
		Route::prefix('adminsystem/non_compliant')->name('adminsystem.non_compliant.')->group(function () {
			Route::get('/create/{id}', [NonCompliantController::class, 'create'])->name('create');
			Route::post('/storepelanggar', [NonCompliantController::class, 'store'])->name('store');
			Route::get('edit/{id}', [NonCompliantController::class, 'edit'])->name('edit');
			Route::put('/{id}', [NonCompliantController::class, 'update'])->name('update');
			Route::get('/show{id}', [NonCompliantController::class, 'show'])->name('show');
			Route::get('/sent_show{id}', [NonCompliantController::class, 'sent_show'])->name('sent_show');
			Route::delete('/{id}', [NonCompliantController::class, 'destroy'])->name('destroy');
			Route::get('/search', [NonCompliantController::class, 'search'])->name('search');
			Route::post('/non_compliant-request', [NonCompliantController::class, 'storeRequest'])->name('storeRequest');
			Route::post('/request', [NonCompliantController::class, 'submitRequest'])->name('request');
			Route::post('/approve/{id}', [NonCompliantController::class, 'approve'])->name('approve');
			Route::post('/reject/{id}', [NonCompliantController::class, 'reject'])->name('reject');
			Route::get('/get-bagian/{perusahaan_name}', [NonCompliantController::class, 'getBagian'])->name('getBagian');
		});
		Route::prefix('adminsystem/ncr')->name('adminsystem.ncr.')->group(function () {
			Route::get('/', [NcrController::class, 'index'])->name('index');
			Route::get('/export', [NcrController::class, 'export'])->name('export');
			Route::get('/export-pdf', [NcrController::class, 'exportPdf'])->name('exportPdf');
			Route::post('/request', [NcrController::class, 'submitRequest'])->name('request');
			Route::post('/approve/{sent_ncr_id}', [NcrController::class, 'approve'])->name('approve');
			Route::post('/reject/{sent_ncr_id}', [NcrController::class, 'reject'])->name('reject');
			Route::get('/create', [NcrController::class, 'create'])->name('create');
			Route::get('/close/{id}', [NcrController::class, 'close'])->name('close');
			Route::put('/closed/{id}', [NcrController::class, 'close_ncr'])->name('close_ncr');
			Route::post('/', [NcrController::class, 'store'])->name('store');
			Route::get('/edit/{id}', [NcrController::class, 'edit'])->name('edit');
			Route::get('/edit_closed/{id}', [NcrController::class, 'edit_close'])->name('edit_closed');
			Route::get('/sent_edit/{id}', [NcrController::class, 'sent_edit'])->name('sent_edit');
			Route::put('/{id}', [NcrController::class, 'update'])->name('update');
			Route::put('/sent_update/{id}', [NcrController::class, 'sent_update'])->name('sent_update');
			Route::put('/update_closed/{id}', [NcrController::class, 'updateClose'])->name('update_closed');
			Route::get('/sent_show{id}', [NcrController::class, 'sent_show'])->name('sent_show');
			Route::get('/show{id}', [NcrController::class, 'show'])->name('show');
			Route::delete('/{id}', [NcrController::class, 'destroy'])->name('destroy');
			Route::delete('/draft_destroy/{id}', [NcrController::class, 'draft_destroy'])->name('draft_destroy');
			Route::delete('/sent_destroy/{id}', [NcrController::class, 'sent_destroy'])->name('sent_destroy');
			Route::get('/search', [NcrController::class, 'search'])->name('search');
			Route::post('/ncr-request', [NcrController::class, 'storeRequest'])->name('storeRequest');
			Route::get('/get-bagian/{perusahaan_name}', [NcrController::class, 'getBagian'])->name('getBagian');
		});
		Route::get('adminsystem/master/ncr', [NcrController::class, 'master'])->name('adminsystem.ncr.master');

		Route::prefix('adminsystem/master/perusahaan')->name('adminsystem.perusahaan.')->group(function () {
			Route::get('/', [PerusahaanController::class, 'index'])->name('index');
			Route::get('/create', [PerusahaanController::class, 'create'])->name('create');
			Route::post('/', [PerusahaanController::class, 'store'])->name('store');
			Route::get('/edit/{id}', [PerusahaanController::class, 'edit'])->name('edit');
			Route::put('/{id}', [PerusahaanController::class, 'update'])->name('update');
			Route::get('/{id}', [PerusahaanController::class, 'show'])->name('show');
			Route::delete('/{id}', [PerusahaanController::class, 'destroy'])->name('destroy');
		});
		Route::prefix('adminsystem/master/bagian')->name('adminsystem.bagian.')->group(function () {
			Route::get('/', [BagianController::class, 'index'])->name('index');
			Route::get('/create', [BagianController::class, 'create'])->name('create');
			Route::post('/', [BagianController::class, 'store'])->name('store');
			Route::get('/{id}/edit', [BagianController::class, 'edit'])->name('edit');
			Route::put('/{id}', [BagianController::class, 'update'])->name('update');
			Route::get('/{id}', [BagianController::class, 'show'])->name('show');
			Route::delete('/{id}', [BagianController::class, 'destroy'])->name('destroy');
		});
		Route::prefix('adminsystem/master/detail-alat')->name('adminsystem.detail_alat.')->group(function () {
			Route::get('/create', [AlatController::class, 'alat_create'])->name('create');
			Route::post('/', [AlatController::class, 'alat_store'])->name('store');
			Route::get('/{id}/edit', [AlatController::class, 'alat_edit'])->name('edit');
			Route::put('/{id}', [AlatController::class, 'alat_update'])->name('update');
			Route::get('/{id}', [AlatController::class, 'alat_show'])->name('show');
			Route::delete('/{id}', [AlatController::class, 'alat_destroy'])->name('destroy');
		});
		Route::prefix('adminsystem/master/nama-alat')->name('adminsystem.nama_alat.')->group(function () {
			Route::get('/create', [AlatController::class, 'nama_alat_create'])->name('create');
			Route::post('/', [AlatController::class, 'nama_alat_store'])->name('store');
			Route::get('/{id}/edit', [AlatController::class, 'nama_alat_edit'])->name('edit');
			Route::put('/{id}', [AlatController::class, 'nama_alat_update'])->name('update');
			Route::get('/{id}', [AlatController::class, 'nama_alat_show'])->name('show');
			Route::delete('/{id}', [AlatController::class, 'nama_alat_destroy'])->name('destroy');
		});
		Route::prefix('adminsystem/master/alat')->name('adminsystem.alat.')->group(function () {
			Route::get('/', [AlatController::class, 'index'])->name('index');
		});
		Route::prefix('adminsystem/daily')->name('adminsystem.daily.')->group(function () {
			Route::get('/', [DailyController::class, 'index'])->name('index');
			Route::get('/export', [DailyController::class, 'export'])->name('export');
			Route::get('/export-pdf', [DailyController::class, 'exportPdf'])->name('exportPdf');
			Route::get('/create', [DailyController::class, 'create'])->name('create');
			Route::post('/', [DailyController::class, 'store'])->name('store');
			Route::get('/{id}/edit', [DailyController::class, 'edit'])->name('edit');
			Route::put('/{id}', [DailyController::class, 'update'])->name('update');
			Route::get('/{id}', [DailyController::class, 'show'])->name('show');
			Route::get('/sent_show/{id}', [DailyController::class, 'sent_show'])->name('sent_show');
			Route::delete('/draft/{id}', [DailyController::class, 'draft_destroy'])->name('draft_destroy');
			Route::delete('/{id}', [DailyController::class, 'destroy'])->name('destroy');
			Route::get('/search', [DailyController::class, 'search'])->name('search');
			Route::get('/sent_edit/{id}', [DailyController::class, 'sent_edit'])->name('sent_edit');
			Route::put('/sent_update/{id}', [DailyController::class, 'sent_update'])->name('sent_update');
			Route::delete('/sent_destroy/{id}', [DailyController::class, 'sent_destroy'])->name('sent_destroy');
			Route::post('/daily-request', [DailyController::class, 'storeRequest'])->name('storeRequest');
			Route::post('/request', [DailyController::class, 'submitRequest'])->name('request');
			Route::post('/approve/{id}', [DailyController::class, 'approve'])->name('approve');
			Route::post('/reject/{id}', [DailyController::class, 'reject'])->name('reject');
		});
		Route::prefix('adminsystem/references')->name('adminsystem.references.')->group(function () {
			Route::get('/', [ReferencesController::class, 'index'])->name('index');
			Route::post('/', [ReferencesController::class, 'store'])->name('store');
			Route::delete('/{document}', [ReferencesController::class, 'destroy'])->name('destroy');
			Route::get('/search', [ReferencesController::class, 'search'])->name('search');
		});
		Route::prefix('adminsystem/budget_pr')->name('adminsystem.budget_pr.')->group(function () {
			Route::get('/', [BudgetController::class, 'index'])->name('index');
			Route::post('/', [BudgetController::class, 'store'])->name('store');
			Route::delete('/{document}', [BudgetController::class, 'destroy'])->name('destroy');
			Route::get('/search', [BudgetController::class, 'search'])->name('search');
			Route::get('/create', [BudgetController::class, 'create'])->name('create');
		});

		Route::prefix('adminsystem/pr')->name('adminsystem.pr.')->group(function () {
			Route::get('/', [BudgetController::class, 'pr_index'])->name('index');
			Route::get('/master', [BudgetController::class, 'pr_master'])->name('master');
			Route::post('/', [BudgetController::class, 'pr_store'])->name('store');
			Route::put('/{id}', [BudgetController::class, 'pr_update'])->name('update');
			Route::delete('/{document}', [BudgetController::class, 'pr_destroy'])->name('destroy');
			Route::get('/search', [BudgetController::class, 'pr_search'])->name('search');
			Route::get('/create', [BudgetController::class, 'pr_create'])->name('create');
			Route::get('/{id}/edit', [BudgetController::class, 'pr_edit'])->name('edit');
		});
		Route::prefix('adminsystem/material_group')->name('adminsystem.material_group.')->group(function () {
			Route::get('/', [MaterialGroupController::class, 'index'])->name('index');
			Route::get('/master', [MaterialGroupController::class, 'master'])->name('master');
			Route::post('/', [MaterialGroupController::class, 'store'])->name('store');
			Route::delete('/{document}', [MaterialGroupController::class, 'destroy'])->name('destroy');
			Route::get('/search', [MaterialGroupController::class, 'search'])->name('search');
			Route::get('/create', [MaterialGroupController::class, 'create'])->name('create');
			Route::get('/{id}/edit', [MaterialGroupController::class, 'edit'])->name('edit');
			Route::put('/{id}', [MaterialGroupController::class, 'update'])->name('update');
		});
		Route::prefix('adminsystem/unit')->name('adminsystem.unit.')->group(function () {
			Route::get('/', [UnitController::class, 'index'])->name('index');
			Route::get('/master', [UnitController::class, 'master'])->name('master');
			Route::post('/', [UnitController::class, 'store'])->name('store');
			Route::delete('/{document}', [UnitController::class, 'destroy'])->name('destroy');
			Route::get('/search', [UnitController::class, 'search'])->name('search');
			Route::get('/create', [UnitController::class, 'create'])->name('create');
			Route::get('/{id}/edit', [UnitController::class, 'edit'])->name('edit');
			Route::put('/{id}', [UnitController::class, 'update'])->name('update');
		});
		Route::prefix('adminsystem/hse_inspector')->name('adminsystem.hse_inspector.')->group(function () {
			Route::get('/', [HseInspectorController::class, 'index'])->name('index');
			Route::post('/', [HseInspectorController::class, 'store'])->name('store');
			Route::delete('/{document}', [HseInspectorController::class, 'destroy'])->name('destroy');
			Route::get('/search', [HseInspectorController::class, 'search'])->name('search');
			Route::get('/create', [HseInspectorController::class, 'create'])->name('create');
			Route::get('/{id}/edit', [HseInspectorController::class, 'edit'])->name('edit');
			Route::put('/{id}', [HseInspectorController::class, 'update'])->name('update');
		});
		Route::prefix('adminsystem/glaccount')->name('adminsystem.glaccount.')->group(function () {
			Route::get('/', [GLAccountController::class, 'index'])->name('index');
			Route::get('/master', [GLAccountController::class, 'master'])->name('master');
			Route::post('/', [GLAccountController::class, 'store'])->name('store');
			Route::delete('/{document}', [GLAccountController::class, 'destroy'])->name('destroy');
			Route::get('/search', [GLAccountController::class, 'search'])->name('search');
			Route::get('/create', [GLAccountController::class, 'create'])->name('create');
			Route::get('/{id}/edit', [GLAccountController::class, 'edit'])->name('edit');
			Route::put('/{id}', [GLAccountController::class, 'update'])->name('update');
		});
		Route::prefix('adminsystem/purchasinggroup')->name('adminsystem.purchasinggroup.')->group(function () {
			Route::get('/', [PurchasingGroupController::class, 'index'])->name('index');
			Route::get('/master', [PurchasingGroupController::class, 'master'])->name('master');
			Route::post('/', [PurchasingGroupController::class, 'store'])->name('store');
			Route::delete('/{document}', [PurchasingGroupController::class, 'destroy'])->name('destroy');
			Route::get('/search', [PurchasingGroupController::class, 'search'])->name('search');
			Route::get('/create', [PurchasingGroupController::class, 'create'])->name('create');
			Route::get('/{id}/edit', [PurchasingGroupController::class, 'edit'])->name('edit');
			Route::put('/{id}', [PurchasingGroupController::class, 'update'])->name('update');
		});
		Route::prefix('adminsystem/budget')->name('adminsystem.budget.')->group(function () {
			Route::get('/', [BudgetController::class, 'budget_index'])->name('index');
			Route::get('/master', [BudgetController::class, 'budget_master'])->name('master');
			Route::post('/', [BudgetController::class, 'budget_store'])->name('store');
			Route::delete('/{id}', [BudgetController::class, 'budget_destroy'])->name('destroy');
			Route::get('/search', [BudgetController::class, 'budget_search'])->name('search');
			Route::get('/create', [BudgetController::class, 'budget_create'])->name('create');
			Route::get('/{id}/edit', [BudgetController::class, 'budget_edit'])->name('edit');
			Route::put('/{id}', [BudgetController::class, 'budget_update'])->name('update');
			Route::get('/get-gl-name/{gl_code}', [BudgetController::class, 'getGlName'])->name('getGlName');
		});
		Route::prefix('adminsystem/inventory')->name('adminsystem.inventory.')->group(function () {
			Route::get('/', [InventoryController::class, 'index'])->name('index');
			Route::post('/', [InventoryController::class, 'store'])->name('store');
			Route::delete('/{document}', [InventoryController::class, 'destroy'])->name('destroy');
			Route::get('/search', [InventoryController::class, 'search'])->name('search');
			Route::get('/create', [InventoryController::class, 'create'])->name('create');
			Route::put('/{id}', [InventoryController::class, 'update'])->name('update');
		});
		Route::prefix('adminsystem/pemasukan')->name('adminsystem.pemasukan.')->group(function () {
			Route::get('/', [InventoryController::class, 'pemasukan_index'])->name('index');
			Route::post('/', [InventoryController::class, 'pemasukan_store'])->name('store');
			Route::delete('/{id}', [InventoryController::class, 'pemasukan_destroy'])->name('destroy');
			Route::get('/search', [InventoryController::class, 'pemasukan_search'])->name('search');
			Route::get('/create', [InventoryController::class, 'pemasukan_create'])->name('create');
			Route::get('/{id}', [InventoryController::class, 'pemasukan_edit'])->name('edit');
			Route::put('/{id}', [InventoryController::class, 'pemasukan_update'])->name('update');
			Route::get('/get-barang-details/{id}', [InventoryController::class, 'getBarangDetails'])->name('get.barang.details');
		});
		Route::prefix('adminsystem/pengeluaran')->name('adminsystem.pengeluaran.')->group(function () {
			Route::get('/', [InventoryController::class, 'pengeluaran_index'])->name('index');
			Route::post('/', [InventoryController::class, 'pengeluaran_store'])->name('store');
			Route::delete('/{id}', [InventoryController::class, 'pengeluaran_destroy'])->name('destroy');
			Route::get('/search', [InventoryController::class, 'pengeluaran_search'])->name('search');
			Route::get('/create', [InventoryController::class, 'pengeluaran_create'])->name('create');
			Route::get('/{id}', [InventoryController::class, 'pengeluaran_edit'])->name('edit');
			Route::put('/{id}', [InventoryController::class, 'pengeluaran_update'])->name('update');
		});
		Route::prefix('adminsystem/barang')->name('adminsystem.barang.')->group(function () {
			Route::get('/', [InventoryController::class, 'barang_index'])->name('index');
			Route::post('/', [InventoryController::class, 'barang_store'])->name('store');
			Route::delete('/{id}', [InventoryController::class, 'barang_destroy'])->name('destroy');
			Route::get('/search', [InventoryController::class, 'barang_search'])->name('search');
			Route::get('/create', [InventoryController::class, 'barang_create'])->name('create');
			Route::get('/edit/{id}', [InventoryController::class, 'barang_edit'])->name('edit');
			Route::get('/detail/{id}', [InventoryController::class, 'barang_detail'])->name('detail');
			Route::put('/{id}', [InventoryController::class, 'barang_update'])->name('update');
		});
		Route::prefix('adminsystem/user')->name('adminsystem.user.')->group(function () {
			Route::get('/', [UserController::class, 'index'])->name('index');
			Route::post('/', [UserController::class, 'store'])->name('store');
			Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
			Route::get('/search', [UserController::class, 'search'])->name('search');
			Route::get('/create', [UserController::class, 'create'])->name('create');
			Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
			Route::get('/detail/{id}', [UserController::class, 'detail'])->name('detail');
			Route::put('/{id}', [UserController::class, 'update'])->name('update');
		});
		Route::prefix('adminsystem/info_user')->name('adminsystem.info_user.')->group(function () {
			Route::get('/', [InfoUserController::class, 'index'])->name('index');
			Route::post('/', [InfoUserController::class, 'store'])->name('store');
			Route::delete('/{id}', [InfoUserController::class, 'destroy'])->name('destroy');
			Route::get('/search', [InfoUserController::class, 'search'])->name('search');
			Route::get('/create', [InfoUserController::class, 'create'])->name('create');
			Route::get('/edit/{id}', [InfoUserController::class, 'edit'])->name('edit');
			Route::get('/detail/{id}', [InfoUserController::class, 'detail'])->name('detail');
			Route::put('/{id}', [InfoUserController::class, 'update'])->name('update');
		});
		Route::prefix('adminsystem/tool')->name('adminsystem.tool.')->group(function () {
			Route::get('/', [ToolController::class, 'index'])->name('index');
			Route::post('/', [ToolController::class, 'store'])->name('store');
			Route::get('/export', [ToolController::class, 'export'])->name('export');
			Route::get('/export-pdf', [ToolController::class, 'exportPdf'])->name('exportPdf');
			Route::post('/request', [ToolController::class, 'storeRequest'])->name('storeRequest');
			Route::post('/approve/{id}', [ToolController::class, 'approve'])->name('approve');
			Route::post('/reject/{id}', [ToolController::class, 'reject'])->name('reject');
			Route::delete('/{id}', [ToolController::class, 'destroy'])->name('destroy');
			Route::delete('/draft/{id}', [ToolController::class, 'draft_destroy'])->name('draft_destroy');
			Route::get('/search', [ToolController::class, 'search'])->name('search');
			Route::get('/create', [ToolController::class, 'create'])->name('create');
			Route::get('/edit/{id}', [ToolController::class, 'edit'])->name('edit');
			Route::get('/detail/{id}', [ToolController::class, 'detail'])->name('detail');
			Route::put('/{id}', [ToolController::class, 'update'])->name('update');
			Route::get('/sent_{id}', [ToolController::class, 'show'])->name('show');
			Route::get('/{id}', [ToolController::class, 'sent_show'])->name('sent_show');
			Route::get('/sent_edit/{id}', [ToolController::class, 'sent_edit'])->name('sent_edit');
			Route::put('/sent_update/{id}', [ToolController::class, 'sent_update'])->name('sent_update');
			Route::delete('/sent_destroy/{id}', [ToolController::class, 'sent_destroy'])->name('sent_destroy');
		});



		Route::prefix('adminsystem/master')->name('adminsystem.master.')->group(function () {
			Route::get('/', [MasterController::class, 'index'])->name('index');
		});

		// Admin dashboard
		Route::prefix('adminsystem')->name('adminsystem.')->group(function () {
			Route::get('/home', [HomeController::class, 'index'])->name('home');
			Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
			Route::post('/dashboard/set-compliance-target', [HomeController::class, 'setComplianceTarget'])->name('set-compliance-target');
			Route::post('/dashboard/update-target-manhours', [HomeController::class, 'updateTargetManHours'])->name('update-target-manhours');
			Route::get('/dashboard-incident', [HomeController::class, 'incident'])->name('dashboard-incident');
			Route::get('/dashboard-spi', [HomeController::class, 'spi'])->name('dashboard-spi');
			Route::get('/dashboard-ncr', [HomeController::class, 'ncr'])->name('dashboard-ncr');
			Route::get('/dashboard-budget', [HomeController::class, 'budget'])->name('dashboard-budget');
			Route::post('/home', [HomeController::class, 'store'])->name('store');
			Route::delete('/{note}', [HomeController::class, 'destroy'])->name('destroy');
		});
	});

































	Route::group(['middleware' => 'role:operator'], function () {
		Route::prefix('operator/incident')->name('operator.incident.')->group(function () {
			Route::get('/', [IncidentController::class, 'operator_index'])->name('index');
			Route::get('/export', [IncidentController::class, 'operator_export'])->name('export');
			Route::get('/export-pdf', [IncidentController::class, 'operator_exportPdf'])->name('exportPdf');
			Route::get('/pdf-satuan/{id}/', [IncidentController::class, 'operator_downloadSatuan'])->name('downloadSatuan');
			Route::get('/create', [IncidentController::class, 'operator_create'])->name('create');
			Route::get('/master', [IncidentController::class, 'operator_master'])->name('master');
			Route::post('/', [IncidentController::class, 'operator_store'])->name('store');
			Route::get('/{id}/edit', [IncidentController::class, 'operator_edit'])->name('edit');
			Route::get('/sent/{id}', [IncidentController::class, 'operator_sent_show'])->name('sent_show');
			Route::get('/{id}/sent_edit', [IncidentController::class, 'operator_sent_edit'])->name('sent_edit');
			Route::put('/sent{id}', [IncidentController::class, 'operator_sent_update'])->name('sent_update');
			Route::put('/{id}', [IncidentController::class, 'operator_update'])->name('update');
			Route::get('/show{id}', [IncidentController::class, 'operator_show'])->name('show');
			Route::delete('/{id}', [IncidentController::class, 'operator_destroy'])->name('destroy');
			Route::delete('/sent_destroy/{id}', [IncidentController::class, 'operator_sent_destroy'])->name('sent_destroy');
			Route::delete('/draft_destroy/{id}', [IncidentController::class, 'operator_draft_destroy'])->name('draft_destroy');
			Route::get('/search', [IncidentController::class, 'operator_search'])->name('search');
			Route::post('/incident-request', [IncidentController::class, 'operator_storeRequest'])->name('storeRequest');
			Route::post('/request', [IncidentController::class, 'operator_submitRequest'])->name('request');
			Route::post('/approve/{id}', [IncidentController::class, 'operator_approve'])->name('approve');
			Route::post('/reject/{id}', [IncidentController::class, 'operator_reject'])->name('reject');
			Route::get('/get-bagian/{perusahaan_name}', [IncidentController::class, 'operator_getBagian'])->name('getBagian');
			Route::post('/get-jumlah-hari-hilang', [IncidentController::class, 'operator_getJumlahHariHilang'])->name('getJumlahHariHilang');
		});

		Route::prefix('operator/ppe')->name('operator.ppe.')->group(function () {
			Route::get('/', [PpeController::class, 'operator_index'])->name('index');
			Route::get('/export', [PpeController::class, 'operator_export'])->name('export');
			Route::get('/export-pdf', [PpeController::class, 'operator_exportPdf'])->name('exportPdf');
			Route::get('/create', [PpeController::class, 'operator_create'])->name('create');
			Route::post('/', [PpeController::class, 'operator_store'])->name('store');
			Route::get('/{id}/edit', [PpeController::class, 'operator_edit'])->name('edit');
			Route::put('/{id}', [PpeController::class, 'operator_update'])->name('update');
			Route::get('/{id}', [PpeController::class, 'operator_show'])->name('show');
			Route::delete('/{id}', [PpeController::class, 'operator_destroy'])->name('destroy');
			Route::get('/search', [PpeController::class, 'operator_search'])->name('search');
			Route::get('/sent_edit/{id}', [PpeController::class, 'operator_sent_edit'])->name('sent_edit');
			Route::put('/sent_update/{id}', [PpeController::class, 'operator_sent_update'])->name('sent_update');
			Route::delete('/sent_destroy/{id}', [PpeController::class, 'operator_sent_destroy'])->name('sent_destroy');
			Route::post('/ppe-request', [PpeController::class, 'operator_storeRequest'])->name('storeRequest');
			Route::post('/request', [PpeController::class, 'operator_submitRequest'])->name('request');
			Route::post('/approve/{id}', [PpeController::class, 'operator_approve'])->name('approve');
			Route::post('/reject/{id}', [PpeController::class, 'operator_reject'])->name('reject');
		});
		Route::prefix('operator/non_compliant')->name('operator.non_compliant.')->group(function () {
			Route::get('/create/{id}', [NonCompliantController::class, 'operator_create'])->name('create');
			Route::post('/storepelanggar', [NonCompliantController::class, 'operator_store'])->name('store');
			Route::get('edit/{id}', [NonCompliantController::class, 'operator_edit'])->name('edit');
			Route::put('/{id}', [NonCompliantController::class, 'operator_update'])->name('update');
			Route::get('/show{id}', [NonCompliantController::class, 'operator_show'])->name('show');
			Route::get('/sent_show{id}', [NonCompliantController::class, 'operator_sent_show'])->name('sent_show');
			Route::delete('/{id}', [NonCompliantController::class, 'operator_destroy'])->name('destroy');
			Route::get('/search', [NonCompliantController::class, 'operator_search'])->name('search');
			Route::post('/non_compliant-request', [NonCompliantController::class, 'operator_storeRequest'])->name('storeRequest');
			Route::post('/request', [NonCompliantController::class, 'operator_submitRequest'])->name('request');
			Route::post('/approve/{id}', [NonCompliantController::class, 'operator_approve'])->name('approve');
			Route::post('/reject/{id}', [NonCompliantController::class, 'operator_reject'])->name('reject');
			Route::get('/get-bagian/{perusahaan_name}', [NonCompliantController::class, 'operator_getBagian'])->name('getBagian');
		});
		Route::prefix('operator/ncr')->name('operator.ncr.')->group(function () {
			Route::get('/', [NcrController::class, 'operator_index'])->name('index');
			Route::get('/export', [NcrController::class, 'operator_export'])->name('export');
			Route::get('/export-pdf', [NcrController::class, 'operator_exportPdf'])->name('exportPdf');
			Route::post('/request', [NcrController::class, 'operator_submitRequest'])->name('request');
			Route::post('/approve/{sent_ncr_id}', [NcrController::class, 'operator_approve'])->name('approve');
			Route::post('/reject/{sent_ncr_id}', [NcrController::class, 'operator_reject'])->name('reject');
			Route::get('/create', [NcrController::class, 'operator_create'])->name('create');
			Route::get('/close/{id}', [NcrController::class, 'operator_close'])->name('close');
			Route::put('/closed/{id}', [NcrController::class, 'operator_close_ncr'])->name('close_ncr');
			Route::post('/', [NcrController::class, 'operator_store'])->name('store');
			Route::get('/edit/{id}', [NcrController::class, 'operator_edit'])->name('edit');
			Route::get('/edit_closed/{id}', [NcrController::class, 'operator_edit_close'])->name('edit_closed');
			Route::get('/sent_edit/{id}', [NcrController::class, 'operator_sent_edit'])->name('sent_edit');
			Route::put('/{id}', [NcrController::class, 'operator_update'])->name('update');
			Route::put('/sent_update/{id}', [NcrController::class, 'operator_sent_update'])->name('sent_update');
			Route::put('/update_closed/{id}', [NcrController::class, 'operator_updateClose'])->name('update_closed');
			Route::get('/show{id}', [NcrController::class, 'operator_show'])->name('show');
			Route::get('/sent_show{id}', [NcrController::class, 'operator_sent_show'])->name('sent_show');

			Route::delete('/{id}', [NcrController::class, 'operator_destroy'])->name('destroy');
			Route::delete('/draft_destroy/{id}', [NcrController::class, 'operator_draft_destroy'])->name('draft_destroy');
			Route::delete('/sent_destroy/{id}', [NcrController::class, 'operator_sent_destroy'])->name('sent_destroy');
			Route::get('/search', [NcrController::class, 'operator_search'])->name('search');
			Route::post('/ncr-request', [NcrController::class, 'operator_storeRequest'])->name('storeRequest');
			Route::get('/get-bagian/{perusahaan_name}', [NcrController::class, 'operator_getBagian'])->name('getBagian');
		});
		Route::get('operator/master/ncr', [NcrController::class, 'operator_master'])->name('operator.ncr.master');

		Route::prefix('operator/master/perusahaan')->name('operator.perusahaan.')->group(function () {
			Route::get('/', [PerusahaanController::class, 'operator_index'])->name('index');
			Route::get('/create', [PerusahaanController::class, 'operator_create'])->name('create');
			Route::post('/', [PerusahaanController::class, 'operator_store'])->name('store');
			Route::get('/edit/{perusahaan_code}', [PerusahaanController::class, 'operator_edit'])->name('edit');
			Route::put('/{id}', [PerusahaanController::class, 'operator_update'])->name('update');
			Route::get('/{id}', [PerusahaanController::class, 'operator_show'])->name('show');
			Route::delete('/{id}', [PerusahaanController::class, 'operator_destroy'])->name('destroy');
		});
		Route::prefix('operator/master/bagian')->name('operator.bagian.')->group(function () {
			Route::get('/', [BagianController::class, 'operator_index'])->name('index');
			Route::get('/create', [BagianController::class, 'operator_create'])->name('create');
			Route::post('/', [BagianController::class, 'operator_store'])->name('store');
			Route::get('/{id}/edit', [BagianController::class, 'operator_edit'])->name('edit');
			Route::put('/{id}', [BagianController::class, 'operator_update'])->name('update');
			Route::get('/{id}', [BagianController::class, 'operator_show'])->name('show');
			Route::delete('/{id}', [BagianController::class, 'operator_destroy'])->name('destroy');
		});
		Route::prefix('operator/master/detail-alat')->name('operator.detail_alat.')->group(function () {
			Route::get('/create', [AlatController::class, 'operator_alat_create'])->name('create');
			Route::post('/', [AlatController::class, 'operator_alat_store'])->name('store');
			Route::get('/{id}/edit', [AlatController::class, 'operator_alat_edit'])->name('edit');
			Route::put('/{id}', [AlatController::class, 'operator_alat_update'])->name('update');
			Route::get('/{id}', [AlatController::class, 'operator_alat_show'])->name('show');
			Route::delete('/{id}', [AlatController::class, 'operator_alat_destroy'])->name('destroy');
		});
		Route::prefix('operator/master/nama-alat')->name('operator.nama_alat.')->group(function () {
			Route::get('/create', [AlatController::class, 'operator_nama_alat_create'])->name('create');
			Route::post('/', [AlatController::class, 'operator_nama_alat_store'])->name('store');
			Route::get('/{id}/edit', [AlatController::class, 'operator_nama_alat_edit'])->name('edit');
			Route::put('/{id}', [AlatController::class, 'operator_nama_alat_update'])->name('update');
			Route::get('/{id}', [AlatController::class, 'operator_nama_alat_show'])->name('show');
			Route::delete('/{id}', [AlatController::class, 'operator_nama_alat_destroy'])->name('destroy');
		});
		Route::prefix('operator/master/alat')->name('operator.alat.')->group(function () {
			Route::get('/', [AlatController::class, 'operator_index'])->name('index');
		});
		Route::prefix('operator/daily')->name('operator.daily.')->group(function () {
			Route::get('/', [DailyController::class, 'operator_index'])->name('index');
			Route::get('/export', [DailyController::class, 'operator_export'])->name('export');
			Route::get('/export-pdf', [DailyController::class, 'operator_exportPdf'])->name('exportPdf');
			Route::get('/create', [DailyController::class, 'operator_create'])->name('create');
			Route::post('/', [DailyController::class, 'operator_store'])->name('store');
			Route::get('/{id}/edit', [DailyController::class, 'operator_edit'])->name('edit');
			Route::put('/{id}', [DailyController::class, 'operator_update'])->name('update');
			Route::get('/{id}', [DailyController::class, 'operator_show'])->name('show');
			Route::get('/sent_show/{id}', [DailyController::class, 'operator_sent_show'])->name('sent_show');
			Route::delete('/draft/{id}', [DailyController::class, 'operator_draft_destroy'])->name('draft_destroy');
			Route::delete('/{id}', [DailyController::class, 'operator_destroy'])->name('destroy');
			Route::get('/search', [DailyController::class, 'operator_search'])->name('search');
			Route::get('/sent_edit/{id}', [DailyController::class, 'operator_sent_edit'])->name('sent_edit');
			Route::put('/sent_update/{id}', [DailyController::class, 'operator_sent_update'])->name('sent_update');
			Route::delete('/sent_destroy/{id}', [DailyController::class, 'operator_sent_destroy'])->name('sent_destroy');
			Route::post('/daily-request', [DailyController::class, 'operator_storeRequest'])->name('storeRequest');
			Route::post('/request', [DailyController::class, 'operator_submitRequest'])->name('request');
			Route::post('/approve/{id}', [DailyController::class, 'operator_approve'])->name('approve');
			Route::post('/reject/{id}', [DailyController::class, 'operator_reject'])->name('reject');
		});
		Route::prefix('operator/references')->name('operator.references.')->group(function () {
			Route::get('/', [ReferencesController::class, 'operator_index'])->name('index');
			Route::post('/', [ReferencesController::class, 'operator_store'])->name('store');
			Route::delete('/{document}', [ReferencesController::class, 'operator_destroy'])->name('destroy');
			Route::get('/search', [ReferencesController::class, 'operator_search'])->name('search');
		});
		Route::prefix('operator/budget_pr')->name('operator.budget_pr.')->group(function () {
			Route::get('/', [BudgetController::class, 'operator_index'])->name('index');
			Route::post('/', [BudgetController::class, 'operator_store'])->name('store');
			Route::delete('/{document}', [BudgetController::class, 'operator_destroy'])->name('destroy');
			Route::get('/search', [BudgetController::class, 'operator_search'])->name('search');
			Route::get('/create', [BudgetController::class, 'operator_create'])->name('create');
		});
		Route::prefix('operator/budget')->name('operator.budget.')->group(function () {
			Route::get('/', [BudgetController::class, 'operator_budget_index'])->name('index');
			Route::post('/', [BudgetController::class, 'operator_budget_store'])->name('store');
			Route::delete('/{document}', [BudgetController::class, 'operator_budget_destroy'])->name('destroy');
			Route::get('/search', [BudgetController::class, 'operator_budget_search'])->name('search');
		});
		Route::prefix('operator/pr')->name('operator.pr.')->group(function () {
			Route::get('/', [BudgetController::class, 'operator_pr_index'])->name('index');
			Route::get('/master', [BudgetController::class, 'operator_pr_master'])->name('master');
			Route::post('/', [BudgetController::class, 'operator_pr_store'])->name('store');
			Route::delete('/{document}', [BudgetController::class, 'operator_pr_destroy'])->name('destroy');
			Route::get('/search', [BudgetController::class, 'operator_pr_search'])->name('search');
			Route::get('/create', [BudgetController::class, 'operator_pr_create'])->name('create');
			Route::get('/{id}/edit', [BudgetController::class, 'operator_pr_edit'])->name('edit');
		});
		Route::prefix('operator/material_group')->name('operator.material_group.')->group(function () {
			Route::get('/', [MaterialGroupController::class, 'operator_index'])->name('index');
			Route::get('/master', [MaterialGroupController::class, 'operator_master'])->name('master');
			Route::post('/', [MaterialGroupController::class, 'operator_store'])->name('store');
			Route::delete('/{document}', [MaterialGroupController::class, 'operator_destroy'])->name('destroy');
			Route::get('/search', [MaterialGroupController::class, 'operator_search'])->name('search');
			Route::get('/create', [MaterialGroupController::class, 'operator_create'])->name('create');
			Route::get('/{id}/edit', [MaterialGroupController::class, 'operator_edit'])->name('edit');
			Route::put('/{id}', [MaterialGroupController::class, 'operator_update'])->name('update');
		});
		Route::prefix('operator/unit')->name('operator.unit.')->group(function () {
			Route::get('/', [UnitController::class, 'operator_index'])->name('index');
			Route::get('/master', [UnitController::class, 'operator_master'])->name('master');
			Route::post('/', [UnitController::class, 'operator_store'])->name('store');
			Route::delete('/{document}', [UnitController::class, 'operator_destroy'])->name('destroy');
			Route::get('/search', [UnitController::class, 'operator_search'])->name('search');
			Route::get('/create', [UnitController::class, 'operator_create'])->name('create');
			Route::get('/{id}/edit', [UnitController::class, 'operator_edit'])->name('edit');
			Route::put('/{id}', [UnitController::class, 'operator_update'])->name('update');
		});
		Route::prefix('operator/hse_inspector')->name('operator.hse_inspector.')->group(function () {
			Route::get('/', [HseInspectorController::class, 'operator_index'])->name('index');
			Route::post('/', [HseInspectorController::class, 'operator_store'])->name('store');
			Route::delete('/{document}', [HseInspectorController::class, 'operator_destroy'])->name('destroy');
			Route::get('/search', [HseInspectorController::class, 'operator_search'])->name('search');
			Route::get('/create', [HseInspectorController::class, 'operator_create'])->name('create');
			Route::get('/{id}/edit', [HseInspectorController::class, 'operator_edit'])->name('edit');
			Route::put('/{id}', [HseInspectorController::class, 'operator_update'])->name('update');
		});
		Route::prefix('operator/glaccount')->name('operator.glaccount.')->group(function () {
			Route::get('/', [GLAccountController::class, 'operator_index'])->name('index');
			Route::get('/master', [GLAccountController::class, 'operator_master'])->name('master');
			Route::post('/', [GLAccountController::class, 'operator_store'])->name('store');
			Route::delete('/{document}', [GLAccountController::class, 'operator_destroy'])->name('destroy');
			Route::get('/search', [GLAccountController::class, 'operator_search'])->name('search');
			Route::get('/create', [GLAccountController::class, 'operator_create'])->name('create');
			Route::get('/{id}/edit', [GLAccountController::class, 'operator_edit'])->name('edit');
			Route::put('/{id}', [GLAccountController::class, 'operator_update'])->name('update');
		});
		Route::prefix('operator/purchasinggroup')->name('operator.purchasinggroup.')->group(function () {
			Route::get('/', [PurchasingGroupController::class, 'operator_index'])->name('index');
			Route::get('/master', [PurchasingGroupController::class, 'operator_master'])->name('master');
			Route::post('/', [PurchasingGroupController::class, 'operator_store'])->name('store');
			Route::delete('/{document}', [PurchasingGroupController::class, 'operator_destroy'])->name('destroy');
			Route::get('/search', [PurchasingGroupController::class, 'operator_search'])->name('search');
			Route::get('/create', [PurchasingGroupController::class, 'operator_create'])->name('create');
			Route::get('/{id}/edit', [PurchasingGroupController::class, 'operator_edit'])->name('edit');
			Route::put('/{id}', [PurchasingGroupController::class, 'operator_update'])->name('update');
		});
		Route::prefix('operator/budget')->name('operator.budget.')->group(function () {
			Route::get('/', [BudgetController::class, 'operator_budget_index'])->name('index');
			Route::get('/master', [BudgetController::class, 'operator_budget_master'])->name('master');
			Route::post('/', [BudgetController::class, 'operator_budget_store'])->name('store');
			Route::delete('/{id}', [BudgetController::class, 'operator_budget_destroy'])->name('destroy');
			Route::get('/search', [BudgetController::class, 'operator_budget_search'])->name('search');
			Route::get('/create', [BudgetController::class, 'operator_budget_create'])->name('create');
			Route::get('/{id}/edit', [BudgetController::class, 'operator_budget_edit'])->name('edit');
			Route::put('/{id}', [BudgetController::class, 'operator_budget_update'])->name('update');
			Route::get('/get-gl-name/{gl_code}', [BudgetController::class, 'operator_getGlName'])->name('getGlName');
		});
		Route::prefix('operator/inventory')->name('operator.inventory.')->group(function () {
			Route::get('/', [InventoryController::class, 'operator_index'])->name('index');
			Route::post('/', [InventoryController::class, 'operator_store'])->name('store');
			Route::delete('/{document}', [InventoryController::class, 'operator_destroy'])->name('destroy');
			Route::get('/search', [InventoryController::class, 'operator_search'])->name('search');
			Route::get('/create', [InventoryController::class, 'operator_create'])->name('create');
			Route::put('/{id}', [InventoryController::class, 'operator_update'])->name('update');
		});
		Route::prefix('operator/pemasukan')->name('operator.pemasukan.')->group(function () {
			Route::get('/', [InventoryController::class, 'operator_pemasukan_index'])->name('index');
			Route::post('/', [InventoryController::class, 'operator_pemasukan_store'])->name('store');
			Route::delete('/{id}', [InventoryController::class, 'operator_pemasukan_destroy'])->name('destroy');
			Route::get('/search', [InventoryController::class, 'operator_pemasukan_search'])->name('search');
			Route::get('/create', [InventoryController::class, 'operator_pemasukan_create'])->name('create');
			Route::get('/{id}', [InventoryController::class, 'operator_pemasukan_edit'])->name('edit');
			Route::put('/{id}', [InventoryController::class, 'operator_pemasukan_update'])->name('update');
			Route::get('/get-barang-details/{id}', [InventoryController::class, 'operator_getBarangDetails'])->name('get.barang.details');
		});
		Route::prefix('operator/pengeluaran')->name('operator.pengeluaran.')->group(function () {
			Route::get('/', [InventoryController::class, 'operator_pengeluaran_index'])->name('index');
			Route::post('/', [InventoryController::class, 'operator_pengeluaran_store'])->name('store');
			Route::delete('/{id}', [InventoryController::class, 'operator_pengeluaran_destroy'])->name('destroy');
			Route::get('/search', [InventoryController::class, 'operator_pengeluaran_search'])->name('search');
			Route::get('/create', [InventoryController::class, 'operator_pengeluaran_create'])->name('create');
			Route::get('/{id}', [InventoryController::class, 'operator_pengeluaran_edit'])->name('edit');
			Route::put('/{id}', [InventoryController::class, 'operator_pengeluaran_update'])->name('update');
		});
		Route::prefix('operator/barang')->name('operator.barang.')->group(function () {
			Route::get('/', [InventoryController::class, 'operator_barang_index'])->name('index');
			Route::post('/', [InventoryController::class, 'operator_barang_store'])->name('store');
			Route::delete('/{id}', [InventoryController::class, 'operator_barang_destroy'])->name('destroy');
			Route::get('/search', [InventoryController::class, 'operator_barang_search'])->name('search');
			Route::get('/create', [InventoryController::class, 'operator_barang_create'])->name('create');
			Route::get('/edit/{id}', [InventoryController::class, 'operator_barang_edit'])->name('edit');
			Route::get('/detail/{id}', [InventoryController::class, 'operator_barang_detail'])->name('detail');
			Route::put('/{id}', [InventoryController::class, 'operator_barang_update'])->name('update');
		});
		Route::prefix('operator/user')->name('operator.user.')->group(function () {
			Route::get('/', [UserController::class, 'operator_index'])->name('index');
			Route::post('/', [UserController::class, 'operator_store'])->name('store');
			Route::delete('/{id}', [UserController::class, 'operator_destroy'])->name('destroy');
			Route::get('/search', [UserController::class, 'operator_search'])->name('search');
			Route::get('/create', [UserController::class, 'operator_create'])->name('create');
			Route::get('/edit/{id}', [UserController::class, 'operator_edit'])->name('edit');
			Route::get('/detail/{id}', [UserController::class, 'operator_detail'])->name('detail');
			Route::put('/{id}', [UserController::class, 'operator_update'])->name('update');
		});
		Route::prefix('operator/info_user')->name('operator.info_user.')->group(function () {
			Route::get('/', [InfoUserController::class, 'operator_index'])->name('index');
			Route::post('/', [InfoUserController::class, 'operator_store'])->name('store');
			Route::delete('/{id}', [InfoUserController::class, 'operator_destroy'])->name('destroy');
			Route::get('/search', [InfoUserController::class, 'operator_search'])->name('search');
			Route::get('/create', [InfoUserController::class, 'operator_create'])->name('create');
			Route::get('/edit/{id}', [InfoUserController::class, 'operator_edit'])->name('edit');
			Route::get('/detail/{id}', [InfoUserController::class, 'operator_detail'])->name('detail');
			Route::put('/{id}', [InfoUserController::class, 'operator_update'])->name('update');
		});
		Route::prefix('operator/tool')->name('operator.tool.')->group(function () {
			Route::get('/', [ToolController::class, 'operator_index'])->name('index');
			Route::post('/', [ToolController::class, 'operator_store'])->name('store');
			Route::get('/export', [ToolController::class, 'operator_export'])->name('export');
			Route::get('/export-pdf', [ToolController::class, 'operator_exportPdf'])->name('exportPdf');
			Route::post('/request', [ToolController::class, 'operator_storeRequest'])->name('storeRequest');
			Route::post('/approve/{id}', [ToolController::class, 'operator_approve'])->name('approve');
			Route::post('/reject/{id}', [ToolController::class, 'operator_reject'])->name('reject');
			Route::delete('/{id}', [ToolController::class, 'operator_destroy'])->name('destroy');
			Route::delete('/draft/{id}', [ToolController::class, 'operator_draft_destroy'])->name('draft_destroy');
			Route::get('/search', [ToolController::class, 'operator_search'])->name('search');
			Route::get('/create', [ToolController::class, 'operator_create'])->name('create');
			Route::get('/edit/{id}', [ToolController::class, 'operator_edit'])->name('edit');
			Route::get('/detail/{id}', [ToolController::class, 'operator_detail'])->name('detail');
			Route::put('/{id}', [ToolController::class, 'operator_update'])->name('update');
			Route::get('/{id}', [ToolController::class, 'operator_show'])->name('show');
			Route::get('/sent_edit/{id}', [ToolController::class, 'operator_sent_edit'])->name('sent_edit');
			Route::put('/sent_update/{id}', [ToolController::class, 'operator_sent_update'])->name('sent_update');
			Route::delete('/sent_destroy/{id}', [ToolController::class, 'operator_sent_destroy'])->name('sent_destroy');
		});



		Route::prefix('operator/master')->name('operator.master.')->group(function () {
			Route::get('/', [MasterController::class, 'operator_index'])->name('index');
		});

		// Admin dashboard
		Route::prefix('operator')->name('operator.')->group(function () {
			Route::get('/home', [HomeController::class, 'index'])->name('home');
			Route::get('/dashboard', [HomeController::class, 'operator_dashboard'])->name('dashboard');
			Route::post('/dashboard/set-compliance-target', [HomeController::class, 'operator_setComplianceTarget'])->name('set-compliance-target');
			Route::post('/dashboard/update-target-manhours', [HomeController::class, 'operator_updateTargetManHours'])->name('update-target-manhours');
			Route::get('/dashboard-incident', [HomeController::class, 'operator_incident'])->name('dashboard-incident');
			Route::get('/dashboard-spi', [HomeController::class, 'operator_spi'])->name('dashboard-spi');
			Route::get('/dashboard-ncr', [HomeController::class, 'operator_ncr'])->name('dashboard-ncr');
			Route::get('/dashboard-budget', [HomeController::class, 'operator_budget'])->name('dashboard-budget');
			Route::post('/home', [HomeController::class, 'operator_store'])->name('store');
			Route::delete('/{note}', [HomeController::class, 'operator_destroy'])->name('destroy');
		});
	});
	Route::group(['middleware' => 'role:tamu'], function () {

		Route::prefix('tamu')->name('tamu.')->group(function () {
			Route::get('/home', [HomeController::class, 'index'])->name('home');
			Route::get('/dashboard', [HomeController::class, 'tamu_dashboard'])->name('dashboard');
			Route::post('/dashboard/set-compliance-target', [HomeController::class, 'tamu_setComplianceTarget'])->name('set-compliance-target');
			Route::post('/dashboard/update-target-manhours', [HomeController::class, 'tamu_updateTargetManHours'])->name('update-target-manhours');
			Route::get('/dashboard-incident', [HomeController::class, 'tamu_incident'])->name('dashboard-incident');
			Route::get('/dashboard-spi', [HomeController::class, 'tamu_spi'])->name('dashboard-spi');
			Route::get('/dashboard-ncr', [HomeController::class, 'tamu_ncr'])->name('dashboard-ncr');
			Route::get('/dashboard-budget', [HomeController::class, 'tamu_budget'])->name('dashboard-budget');
			Route::post('/home', [HomeController::class, 'tamu_store'])->name('store');
			Route::delete('/{note}', [HomeController::class, 'tamu_destroy'])->name('destroy');
		});
		Route::prefix('tamu/info_user')->name('tamu.info_user.')->group(function () {
			Route::get('/', [InfoUserController::class, 'tamu_index'])->name('index');
			Route::post('/', [InfoUserController::class, 'tamu_store'])->name('store');
			Route::delete('/{id}', [InfoUserController::class, 'tamu_destroy'])->name('destroy');
			Route::get('/search', [InfoUserController::class, 'tamu_search'])->name('search');
			Route::get('/create', [InfoUserController::class, 'tamu_create'])->name('create');
			Route::get('/edit/{id}', [InfoUserController::class, 'tamu_edit'])->name('edit');
			Route::get('/detail/{id}', [InfoUserController::class, 'tamu_detail'])->name('detail');
			Route::put('/{id}', [InfoUserController::class, 'tamu_update'])->name('update');
		});
	});
});

Route::group(['middleware' => 'guest'], function () {
	Route::get('/register', [RegisterController::class, 'create']);
	Route::post('/register', [RegisterController::class, 'store']);
	Route::get('/login', [SessionsController::class, 'create']);
	Route::post('/session', [SessionsController::class, 'login']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});


Route::get('/', function () {
	return view('session/login-session');
})->name('login');
