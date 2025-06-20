<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Budget;
use App\Models\BudgetFix;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Gl_Account;
use App\Models\MaterialGroup;
use App\Models\PurchasingGroup;
use App\Models\Unit;
use Illuminate\Support\Facades\Storage;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::all();
        $prs = PurchaseRequest::all();
        $budget_fixs = BudgetFix::all();
        $danaTerpakai = Budget::all();
        $danaTersisa = Budget::all();
        return view('adminsystem.budget_pr.index', compact('budgets', 'danaTerpakai', 'danaTersisa', 'prs', 'budget_fixs'));
    }

    public function create()
    {
        $gl_accounts = Gl_Account::all();
        $material_groups = MaterialGroup::all();
        $units = Unit::all();
        $purs = PurchasingGroup::all();
        return view('adminsystem.budget_pr.create', compact('gl_accounts', 'material_groups', 'units', 'purs'));
    }

    // Store the new Purchase Request
    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'gl_code' => 'required|string|max:255',
            'io_assetcode' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'bg_approve' => 'required|numeric',
            'usage' => 'required|numeric',
            'plan' => 'required|numeric',
            'percentage_usage' => 'required|numeric',
            'year' => 'required|integer',
        ]);

        // Hitung persentase pemakaian
        $percentage_usage = ($request->usage / $request->bg_approve) * 100;

        // Simpan ke tabel PurchaseRequest
        PurchaseRequest::create([
            'gl_code' => $request->gl_code,
            'io_assetcode' => $request->io_assetcode,
            'description' => $request->description,
            'bg_approve' => $request->bg_approve,
            'usage' => $request->usage,
            'plan' => $request->plan,
            'percentage_usage' => $percentage_usage,
            'year' => $request->year,
        ]);

        // Update budget_fix berdasarkan internal_order
        BudgetFix::where('internal_order', $request->io_assetcode)
            ->update([
                'usage' => $request->plan
            ]);

        return redirect()->route('adminsystem.budget_pr.create')
            ->with('success', 'Purchase Request successfully created!');
    }


    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('adminsystem.budget_pr.index')->with('success', 'Dokumen berhasil dihapus.');
    }




    // budget
    public function budget_index()
    {
        $budgets = Budget::all();
        return view('adminsystem.budget_pr.budget.index', compact('budgets'));
    }
    public function budget_create()
    {

        $gls = Gl_Account::all();
        return view('adminsystem.budget_pr.budget.create', compact('gls'));
    }
    public function budget_store(Request $request)
    {
        $request->validate([
            'internal_order' => 'nullable|string|max:50',
            'gl_code' => 'required|string|max:50',
            'gl_name' => 'required|string|max:100',
            'setahun_total' => 'required|numeric',
            'kategori' => 'required|string|max:100',
            'year' => 'required|string|max:100',
        ]);

        // Store the data in the database
        Budget::create([
            'internal_order' => $request->internal_order,
            'gl_code' => $request->gl_code,
            'gl_name' => $request->gl_name,
            'setahun_total' => $request->setahun_total,
            'kategori' => $request->kategori,
            'year' => $request->year
        ]);
        BudgetFix::create([
            'internal_order'   => $request->internal_order,
            'gl_code'          => $request->gl_code,
            'gl_name'          => $request->gl_name,
            'year'             => $request->year,
            'kategori'         => $request->kategori,
            'bg_approve'       => $request->setahun_total,
            'sisa'       => $request->setahun_total,
        ]);
        return redirect()->route('adminsystem.budget.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function budget_destroy($id)
    {
        $budget = Budget::findOrFail($id);
        $budget->delete();

        return redirect()->route('adminsystem.budget_pr.bug=dget.index')->with('success', 'Budget Plan berhasil dihapus.');
    }
    public function budget_update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'internal_order' => 'nullable|string|max:50',
            'gl_code' => 'required|string|max:50',
            'gl_name' => 'required|string|max:100',
            'setahun_total' => 'required|numeric',
            'kategori' => 'required|string|max:100',
            'year' => 'required|string|max:100',
        ]);

        // Cari dan update data di tabel Budget
        $budget = Budget::findOrFail($id);
        $budget->update([
            'internal_order' => $request->internal_order,
            'gl_code'        => $request->gl_code,
            'gl_name'        => $request->gl_name,
            'setahun_total'  => $request->setahun_total,
            'kategori'       => $request->kategori,
            'year'           => $request->year,
        ]);

        // Cari BudgetFix yang cocok berdasarkan GL Code dan Year
        $budgetFix = BudgetFix::where('gl_code', $request->gl_code)
            ->where('year', $request->year)
            ->latest()
            ->first();

        if ($budgetFix) {
            // Update budget_fix sesuai data baru
            $used = $budgetFix->usage ?? 0;
            $bgApprove = $request->setahun_total;
            $sisa = $bgApprove - $used;

            $budgetFix->update([
                'internal_order' => $request->internal_order,
                'gl_name'        => $request->gl_name,
                'bg_approve'     => $bgApprove,
                'sisa'           => $sisa,
                'kategori'       => $request->kategori,
            ]);
        }

        return redirect()->route('adminsystem.budget.index')->with('success', 'Data budget berhasil diperbarui.');
    }
    public function budget_edit($id)
    {
        $budget = Budget::findOrFail($id);
        $gls = Gl_Account::all();
        return view('adminsystem.budget_pr.budget.edit', compact('budget', 'gls'));
    }

    public function getGlName($gl_code)
    {
        $gl = Gl_Account::where('gl_code', $gl_code)->first(); // Assuming Gl_Account is your model

        if ($gl) {
            return response()->json(['gl_name' => $gl->gl_name]);
        }

        return response()->json(['gl_name' => ''], 404); // Return empty if not found
    }








    // pr
    public function pr_index()
    {
        $prs = PurchaseRequest::all();
        $gls = Gl_Account::all();
        return view('adminsystem.budget_pr.pr.index', compact('prs', 'gls'));
    }

    public function pr_create()
    {
        $purs = PurchasingGroup::all();
        $units = Unit::all();
        $budgets = Budget::all();
        $material_groups = MaterialGroup::all();
        $materials = Barang::all();
        $gls = Gl_Account::all();
        return view('adminsystem.budget_pr.pr.create', compact('purs', 'budgets', 'units', 'material_groups', 'gls', 'materials'));
    }

    public function pr_store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'pr_date' => 'required|date',
            'pr_no' => 'required|string|unique:pr,pr_no',
            'purchase_for' => 'required|string',
            'material' => 'required|string',
            'quantity' => 'required|numeric',
            'unit' => 'required|string',
            'valuation_price' => 'required|numeric',
            'io_assetcode' => 'nullable|string',
            'gl_code' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Cari data GL Account, jika tidak ketemu akan gagal
        $glaccount = Gl_Account::where('gl_code', $validated['gl_code'])->firstOrFail();

        // Simpan PR
        PurchaseRequest::create($validated);

        // Proses BudgetFix jika ada GL Code
        $budgetFix = BudgetFix::where('gl_code', $validated['gl_code'])->latest()->first();

        if ($budgetFix) {
            $usage = $validated['valuation_price'];
            $bg_approve = $budgetFix->bg_approve ?? 0;
            $internal_order = $validated['io_assetcode'] ?? null;
            $sisa_usage = $budgetFix->sisa - $usage;
            $percentage_usage = ($bg_approve > 0) ? ($usage / $bg_approve) * 100 : 0;

            if ($budgetFix->usage > 0) {
                // Jika sudah ada usage sebelumnya, buat baris baru
                BudgetFix::create([
                    'gl_code' => $validated['gl_code'],
                    'usage' => $usage,
                    'internal_order' => $internal_order,
                    'gl_name' => $glaccount->gl_name,
                    'percentage_usage' => $percentage_usage,
                    'sisa' => $sisa_usage, // simpan sisa terbaru
                    'description' => $validated['description'] ?? null,
                    'bg_approve' => $budgetFix->bg_approve,
                    'plan' => $budgetFix->plan,
                    'kategori' => $budgetFix->kategori,
                    'year' => $budgetFix->year,
                ]);
                // Update sisa pada record sebelumnya (budgetFix lama)
                $budgetFix->update([
                    'sisa' => $sisa_usage,
                ]);
            } else {
                // Jika usage belum pernah diisi, update baris sekarang
                $budgetFix->update([
                    'usage' => $usage,
                    'internal_order' => $internal_order,
                    'percentage_usage' => $percentage_usage,
                    'sisa' => $sisa_usage, // update dengan sisa yang benar
                ]);
            }
        }

        return redirect()->route('adminsystem.pr.index')->with('success', 'Purchase Request berhasil dibuat.');
    }

    public function pr_edit($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        $units = Unit::all();
        $budgets = Budget::all();
        $gls = Gl_Account::all();
        return view('adminsystem.budget_pr.pr.edit', compact('pr', 'units', 'budgets', 'gls'));
    }


    public function pr_update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'pr_date' => 'required|date',
            'pr_no' => 'required|string|unique:pr,pr_no,' . $id, // abaikan unique untuk ID ini
            'purchase_for' => 'required|string',
            'material' => 'required|string',
            'quantity' => 'required|numeric',
            'unit' => 'required|string',
            'valuation_price' => 'required|numeric',
            'io_assetcode' => 'nullable|string',
            'gl_code' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $pr = PurchaseRequest::findOrFail($id);

        // Simpan nilai lama untuk perhitungan ulang budget jika valuation_price berubah
        $oldValuation = $pr->valuation_price;
        $pr->update($validated);

        // Proses BudgetFix
        $budgetFix = BudgetFix::where('gl_code', $validated['gl_code'])->latest()->first();
        $glaccount = Gl_Account::where('gl_code', $validated['gl_code'])->firstOrFail();

        if ($budgetFix) {
            $newUsage = $validated['valuation_price'];
            $diffUsage = $newUsage - $oldValuation;

            $bg_approve = $budgetFix->bg_approve ?? 0;
            $internal_order = $validated['io_assetcode'] ?? null;

            $newSisa = $budgetFix->sisa - $diffUsage;
            $newPercentageUsage = ($bg_approve > 0) ? (($budgetFix->usage + $diffUsage) / $bg_approve) * 100 : 0;

            if ($budgetFix->usage > 0 && $diffUsage != 0) {
                // Jika usage sudah pernah ada dan ada perubahan nilai
                BudgetFix::create([
                    'gl_code' => $validated['gl_code'],
                    'usage' => $newUsage,
                    'internal_order' => $internal_order,
                    'gl_name' => $glaccount->gl_name,
                    'percentage_usage' => $newPercentageUsage,
                    'sisa' => $newSisa,
                    'description' => $validated['description'] ?? null,
                    'bg_approve' => $budgetFix->bg_approve,
                    'plan' => $budgetFix->plan,
                    'kategori' => $budgetFix->kategori,
                    'year' => $budgetFix->year,
                ]);
            } elseif ($budgetFix->usage == 0) {
                // Jika belum ada usage, update baris yang sama
                $budgetFix->update([
                    'usage' => $newUsage,
                    'internal_order' => $internal_order,
                    'percentage_usage' => $newPercentageUsage,
                    'sisa' => $newSisa,
                ]);
            }
        }

        return redirect()->route('adminsystem.pr.index')->with('success', 'Purchase Request berhasil diperbarui.');
    }


    public function pr_destroy($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        $pr->delete();

        return redirect()->route('adminsystem.budget_pr.pr.index')->with('success', 'Purchase Request berhasil dihapus.');
    }























    public function operator_index()
    {
        $budgets = Budget::all();
        $prs = PurchaseRequest::all();
        $budget_fixs = BudgetFix::all();
        $danaTerpakai = Budget::all();
        $danaTersisa = Budget::all();
        return view('operator.budget_pr.index', compact('budgets', 'danaTerpakai', 'danaTersisa', 'prs', 'budget_fixs'));
    }

    public function operator_create()
    {
        $gl_accounts = Gl_Account::all();
        $material_groups = MaterialGroup::all();
        $units = Unit::all();
        $purs = PurchasingGroup::all();
        return view('operator.budget_pr.create', compact('gl_accounts', 'material_groups', 'units', 'purs'));
    }

    // Store the new Purchase Request
    public function operator_store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'gl_code' => 'required|string|max:255',
            'io_assetcode' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'bg_approve' => 'required|numeric',
            'usage' => 'required|numeric',
            'plan' => 'required|numeric',
            'percentage_usage' => 'required|numeric',
            'year' => 'required|integer',
        ]);

        // Hitung persentase pemakaian
        $percentage_usage = ($request->usage / $request->bg_approve) * 100;

        // Simpan ke tabel PurchaseRequest
        PurchaseRequest::create([
            'gl_code' => $request->gl_code,
            'io_assetcode' => $request->io_assetcode,
            'description' => $request->description,
            'bg_approve' => $request->bg_approve,
            'usage' => $request->usage,
            'plan' => $request->plan,
            'percentage_usage' => $percentage_usage,
            'year' => $request->year,
        ]);

        // Update budget_fix berdasarkan internal_order
        BudgetFix::where('internal_order', $request->io_assetcode)
            ->update([
                'usage' => $request->plan
            ]);

        return redirect()->route('operator.budget_pr.create')
            ->with('success', 'Purchase Request successfully created!');
    }


    public function operator_destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('operator.budget_pr.index')->with('success', 'Dokumen berhasil dihapus.');
    }




    // budget
    public function operator_budget_index()
    {
        $budgets = Budget::all();
        return view('operator.budget_pr.budget.index', compact('budgets'));
    }
    public function operator_budget_create()
    {

        $gls = Gl_Account::all();
        return view('operator.budget_pr.budget.create', compact('gls'));
    }
    public function operator_budget_store(Request $request)
    {
        $request->validate([
            'internal_order' => 'nullable|string|max:50',
            'gl_code' => 'required|string|max:50',
            'gl_name' => 'required|string|max:100',
            'setahun_total' => 'required|numeric',
            'kategori' => 'required|string|max:100',
            'year' => 'required|string|max:100',
        ]);

        // Store the data in the database
        Budget::create([
            'internal_order' => $request->internal_order,
            'gl_code' => $request->gl_code,
            'gl_name' => $request->gl_name,
            'setahun_total' => $request->setahun_total,
            'kategori' => $request->kategori,
            'year' => $request->year
        ]);
        BudgetFix::create([
            'internal_order'   => $request->internal_order,
            'gl_code'          => $request->gl_code,
            'gl_name'          => $request->gl_name,
            'year'             => $request->year,
            'kategori'         => $request->kategori,
            'bg_approve'       => $request->setahun_total,
            'sisa'       => $request->setahun_total,
        ]);
        return redirect()->route('operator.budget.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function operator_budget_destroy(Document $id)
    {
        $budget = Budget::findOrFail($id);
        $budget->delete();

        return redirect()->route('operator.budget_pr.bug=dget.index')->with('success', 'Budget Plan berhasil dihapus.');
    }
    public function operator_budget_update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'internal_order' => 'nullable|string|max:50',
            'gl_code' => 'required|string|max:50',
            'gl_name' => 'required|string|max:100',
            'setahun_total' => 'required|numeric',
            'kategori' => 'required|string|max:100',
            'year' => 'required|string|max:100',
        ]);

        // Cari dan update data di tabel Budget
        $budget = Budget::findOrFail($id);
        $budget->update([
            'internal_order' => $request->internal_order,
            'gl_code'        => $request->gl_code,
            'gl_name'        => $request->gl_name,
            'setahun_total'  => $request->setahun_total,
            'kategori'       => $request->kategori,
            'year'           => $request->year,
        ]);

        // Cari BudgetFix yang cocok berdasarkan GL Code dan Year
        $budgetFix = BudgetFix::where('gl_code', $request->gl_code)
            ->where('year', $request->year)
            ->latest()
            ->first();

        if ($budgetFix) {
            // Update budget_fix sesuai data baru
            $used = $budgetFix->usage ?? 0;
            $bgApprove = $request->setahun_total;
            $sisa = $bgApprove - $used;

            $budgetFix->update([
                'internal_order' => $request->internal_order,
                'gl_name'        => $request->gl_name,
                'bg_approve'     => $bgApprove,
                'sisa'           => $sisa,
                'kategori'       => $request->kategori,
            ]);
        }

        return redirect()->route('operator.budget.index')->with('success', 'Data budget berhasil diperbarui.');
    }
    public function operator_budget_edit($id)
    {
        $budget = Budget::findOrFail($id);
        $gls = Gl_Account::all();
        return view('operator.budget_pr.budget.edit', compact('budget', 'gls'));
    }

    public function operator_getGlName($gl_code)
    {
        $gl = Gl_Account::where('gl_code', $gl_code)->first(); // Assuming Gl_Account is your model

        if ($gl) {
            return response()->json(['gl_name' => $gl->gl_name]);
        }

        return response()->json(['gl_name' => ''], 404); // Return empty if not found
    }








    // pr
    public function operator_pr_index()
    {
        $prs = PurchaseRequest::all();
        $gls = Gl_Account::all();
        return view('operator.budget_pr.pr.index', compact('prs', 'gls'));
    }

    public function operator_pr_create()
    {
        $purs = PurchasingGroup::all();
        $units = Unit::all();
        $budgets = Budget::all();
        $material_groups = MaterialGroup::all();
        $materials = Barang::all();
        $gls = Gl_Account::all();
        return view('operator.budget_pr.pr.create', compact('purs', 'budgets', 'units', 'material_groups', 'gls', 'materials'));
    }

    public function operator_pr_store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'pr_date' => 'required|date',
            'pr_no' => 'required|string|unique:pr,pr_no',
            'purchase_for' => 'required|string',
            'material' => 'required|string',
            'quantity' => 'required|numeric',
            'unit' => 'required|string',
            'valuation_price' => 'required|numeric',
            'io_assetcode' => 'nullable|string',
            'gl_code' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Cari data GL Account, jika tidak ketemu akan gagal
        $glaccount = Gl_Account::where('gl_code', $validated['gl_code'])->firstOrFail();

        // Simpan PR
        PurchaseRequest::create($validated);

        // Proses BudgetFix jika ada GL Code
        $budgetFix = BudgetFix::where('gl_code', $validated['gl_code'])->latest()->first();

        if ($budgetFix) {
            $usage = $validated['valuation_price'];
            $bg_approve = $budgetFix->bg_approve ?? 0;
            $internal_order = $validated['io_assetcode'] ?? null;
            $sisa_usage = $budgetFix->sisa - $usage;
            $percentage_usage = ($bg_approve > 0) ? ($usage / $bg_approve) * 100 : 0;

            if ($budgetFix->usage > 0) {
                // Jika sudah ada usage sebelumnya, buat baris baru
                BudgetFix::create([
                    'gl_code' => $validated['gl_code'],
                    'usage' => $usage,
                    'internal_order' => $internal_order,
                    'gl_name' => $glaccount->gl_name,
                    'percentage_usage' => $percentage_usage,
                    'sisa' => $sisa_usage, // simpan sisa terbaru
                    'description' => $validated['description'] ?? null,
                    'bg_approve' => $budgetFix->bg_approve,
                    'plan' => $budgetFix->plan,
                    'kategori' => $budgetFix->kategori,
                    'year' => $budgetFix->year,
                ]);
                // Update sisa pada record sebelumnya (budgetFix lama)
                $budgetFix->update([
                    'sisa' => $sisa_usage,
                ]);
            } else {
                // Jika usage belum pernah diisi, update baris sekarang
                $budgetFix->update([
                    'usage' => $usage,
                    'internal_order' => $internal_order,
                    'percentage_usage' => $percentage_usage,
                    'sisa' => $sisa_usage, // update dengan sisa yang benar
                ]);
            }
        }

        return redirect()->route('operator.pr.index')->with('success', 'Purchase Request berhasil dibuat.');
    }

    public function operator_pr_edit($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        $units = Unit::all();
        $budgets = Budget::all();
        $gls = Gl_Account::all();
        return view('operator.budget_pr.pr.edit', compact('pr', 'units', 'budgets', 'gls'));
    }


    public function operator_pr_update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'pr_date' => 'required|date',
            'pr_no' => 'required|string|unique:pr,pr_no,' . $id, // abaikan unique untuk ID ini
            'purchase_for' => 'required|string',
            'material' => 'required|string',
            'quantity' => 'required|numeric',
            'unit' => 'required|string',
            'valuation_price' => 'required|numeric',
            'io_assetcode' => 'nullable|string',
            'gl_code' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $pr = PurchaseRequest::findOrFail($id);

        // Simpan nilai lama untuk perhitungan ulang budget jika valuation_price berubah
        $oldValuation = $pr->valuation_price;
        $pr->update($validated);

        // Proses BudgetFix
        $budgetFix = BudgetFix::where('gl_code', $validated['gl_code'])->latest()->first();
        $glaccount = Gl_Account::where('gl_code', $validated['gl_code'])->firstOrFail();

        if ($budgetFix) {
            $newUsage = $validated['valuation_price'];
            $diffUsage = $newUsage - $oldValuation;

            $bg_approve = $budgetFix->bg_approve ?? 0;
            $internal_order = $validated['io_assetcode'] ?? null;

            $newSisa = $budgetFix->sisa - $diffUsage;
            $newPercentageUsage = ($bg_approve > 0) ? (($budgetFix->usage + $diffUsage) / $bg_approve) * 100 : 0;

            if ($budgetFix->usage > 0 && $diffUsage != 0) {
                // Jika usage sudah pernah ada dan ada perubahan nilai
                BudgetFix::create([
                    'gl_code' => $validated['gl_code'],
                    'usage' => $newUsage,
                    'internal_order' => $internal_order,
                    'gl_name' => $glaccount->gl_name,
                    'percentage_usage' => $newPercentageUsage,
                    'sisa' => $newSisa,
                    'description' => $validated['description'] ?? null,
                    'bg_approve' => $budgetFix->bg_approve,
                    'plan' => $budgetFix->plan,
                    'kategori' => $budgetFix->kategori,
                    'year' => $budgetFix->year,
                ]);
            } elseif ($budgetFix->usage == 0) {
                // Jika belum ada usage, update baris yang sama
                $budgetFix->update([
                    'usage' => $newUsage,
                    'internal_order' => $internal_order,
                    'percentage_usage' => $newPercentageUsage,
                    'sisa' => $newSisa,
                ]);
            }
        }

        return redirect()->route('operator.pr.index')->with('success', 'Purchase Request berhasil diperbarui.');
    }


    public function operator_pr_destroy($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        $pr->delete();

        return redirect()->route('operator.budget_pr.pr.index')->with('success', 'Purchase Request berhasil dihapus.');
    }
}
