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
        $budget_fixs = BudgetFix::orderBy('created_at', 'desc')->get();
        $danaTerpakai = Budget::all();
        $danaTersisa = Budget::all();
        return view('adminsystem.budget_pr.index', compact('budgets', 'danaTerpakai', 'danaTersisa', 'prs', 'budget_fixs'));
    }
    // budget
    public function budget_index()
    {
        $budgets = Budget::with('prs')->get();
        return view('adminsystem.budget_pr.budget.index', compact('budgets'));
    }

    public function budget_create()
    {

        $gls = Gl_Account::all();
        return view('adminsystem.budget_pr.budget.create', compact('gls'));
    }
    public function pr($id)
    {
        $budget = Budget::with('prs')->findOrFail($id); // pastikan relasi sudah didefinisikan
        $prs = PurchaseRequest::where('budget_id', $id)->get();
        $gls = Gl_Account::all();
        return view('adminsystem.budget_pr.pr.index', compact('prs', 'gls', 'budget'));
    }
    public function budget_destroy($id)
    {
        // Ambil data Budget
        $budget = Budget::findOrFail($id);
        $glCode = $budget->gl_code;
        $year = $budget->year;

        // Cek apakah ada PR yang menggunakan gl_code dan year ini
        $prCount = PurchaseRequest::where('gl_code', $glCode)
            ->whereYear('pr_date', $year)
            ->count();

        if ($prCount > 0) {
            return redirect()->route('adminsystem.budget.index')
                ->with('error', 'Tidak dapat menghapus budget karena masih ada PR yang berhubungan.');
        }

        // Hapus Budget
        $budget->delete();

        // Opsional: Hapus BudgetFix jika usage = 0
        $budgetFix = BudgetFix::where('gl_code', $glCode)
            ->where('year', $year)
            ->latest()
            ->first();

        if ($budgetFix && $budgetFix->usage == 0) {
            $budgetFix->delete();
        }

        return redirect()->route('adminsystem.budget.index')->with('success', 'Data budget berhasil dihapus.');
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

        return redirect()->route('adminsystem.budget.index')->with('success', 'Dokumen berhasil diunggah.');
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

    public function pr_create(Request $request)
    {
        $budgetId = $request->query('budget_id');
        $budget = Budget::findOrFail($budgetId);

        $purs = PurchasingGroup::all();
        $budgets = Budget::all();
        $units = Unit::all();
        $material_groups = MaterialGroup::all();
        $materials = Barang::all();

        return view('adminsystem.budget_pr.pr.create', compact(
            'purs',
            'units',
            'material_groups',
            'materials',
            'budget',
            'budgets'
        ));
    }


    public function pr_store(Request $request)
    {
        $validated = $request->validate([
            'budget_id' => 'required|exists:budget,id',
            'pr_date' => 'required|date',
            'pr_no' => 'required|string|unique:pr,pr_no',
            'purchase_for' => 'required|string',
            'material' => 'required|string',
            'quantity' => 'required|numeric',
            'unit' => 'required|string',
            'valuation_price' => 'required|numeric',
            'io_assetcode' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Ambil gl_code dan gl_name dari budget_id
        $budget = Budget::findOrFail($validated['budget_id']);

        PurchaseRequest::create([
            'budget_id' => $validated['budget_id'],
            'pr_date' => $validated['pr_date'],
            'pr_no' => $validated['pr_no'],
            'purchase_for' => $validated['purchase_for'],
            'material' => $validated['material'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'valuation_price' => $validated['valuation_price'],
            'io_assetcode' => $validated['io_assetcode'] ?? null,
            'gl_code' => $budget->gl_code,
            'gl_name' => $budget->gl_name,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('adminsystem.budget.pr', $validated['budget_id'])
            ->with('success', 'PR berhasil ditambahkan');
    }

    public function pr_update(Request $request, $id)
    {
        $validated = $request->validate([
            'budget_id' => 'required|exists:budget,id',
            'pr_date' => 'required|date',
            'pr_no' => 'required|string|unique:pr,pr_no,' . $id,
            'purchase_for' => 'required|string',
            'material' => 'required|string',
            'quantity' => 'required|numeric',
            'unit' => 'required|string',
            'valuation_price' => 'required|numeric',
            'io_assetcode' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Ambil gl_code dan gl_name dari budget terkait
        $budget = Budget::findOrFail($validated['budget_id']);

        // Update PR
        $pr = PurchaseRequest::findOrFail($id);
        $pr->update([
            'budget_id'       => $validated['budget_id'],
            'pr_date'         => $validated['pr_date'],
            'pr_no'           => $validated['pr_no'],
            'purchase_for'    => $validated['purchase_for'],
            'material'        => $validated['material'],
            'quantity'        => $validated['quantity'],
            'unit'            => $validated['unit'],
            'valuation_price' => $validated['valuation_price'],
            'io_assetcode'    => $validated['io_assetcode'] ?? null,
            'gl_code'         => $budget->gl_code,
            'gl_name'         => $budget->gl_name,
            'description'     => $validated['description'] ?? null,
        ]);

        return redirect()->route('adminsystem.budget.pr', $validated['budget_id'])
            ->with('success', 'PR berhasil diperbarui');
    }





    public function pr_edit($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        $units = Unit::all();
        $budgets = Budget::all();
        $gls = Gl_Account::all();
        return view('adminsystem.budget_pr.pr.edit', compact('pr', 'units', 'budgets', 'gls'));
    }


    public function pr_destroy($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        $budgetId = $pr->budget_id;
        $pr->delete();

        return redirect()->route('adminsystem.budget.pr.detail', $budgetId)
            ->with('success', 'PR berhasil dihapus dan budget diperbarui.');
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



    public function operator_budget_destroy($id)
    {
        // Ambil data Budget
        $budget = Budget::findOrFail($id);
        $glCode = $budget->gl_code;
        $year = $budget->year;

        // Cek apakah ada PR yang menggunakan gl_code dan year ini
        $prCount = PurchaseRequest::where('gl_code', $glCode)
            ->whereYear('pr_date', $year)
            ->count();

        if ($prCount > 0) {
            return redirect()->route('operator.budget.index')
                ->with('error', 'Tidak dapat menghapus budget karena masih ada PR yang berhubungan.');
        }

        // Hapus Budget
        $budget->delete();

        // Opsional: Hapus BudgetFix jika usage = 0
        $budgetFix = BudgetFix::where('gl_code', $glCode)
            ->where('year', $year)
            ->latest()
            ->first();

        if ($budgetFix && $budgetFix->usage == 0) {
            $budgetFix->delete();
        }

        return redirect()->route('operator.budget.index')->with('success', 'Data budget berhasil dihapus.');
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

        $glaccount = Gl_Account::where('gl_code', $validated['gl_code'])->firstOrFail();
        PurchaseRequest::create($validated);

        $budgetFix = BudgetFix::where('gl_code', $validated['gl_code'])->latest()->first();

        if ($budgetFix) {
            $usage = $validated['valuation_price'];
            $pr_date = $validated['pr_date'];
            $bg_approve = $budgetFix->bg_approve ?? 0;
            $internal_order = $validated['io_assetcode'] ?? null;
            $sisa_usage = $budgetFix->sisa - $usage;
            $percentage_usage = ($bg_approve > 0) ? ($usage / $bg_approve) * 100 : 0;

            if ($budgetFix->usage > 0) {
                BudgetFix::create([
                    'gl_code' => $validated['gl_code'],
                    'usage' => $usage,
                    'internal_order' => $internal_order,
                    'gl_name' => $glaccount->gl_name,
                    'percentage_usage' => $percentage_usage,
                    'sisa' => $sisa_usage,
                    'description' => $validated['description'] ?? null,
                    'bg_approve' => $budgetFix->bg_approve,
                    'plan' => $budgetFix->plan,
                    'kategori' => $budgetFix->kategori,
                    'year' => $budgetFix->year,
                    'pr_date' => $pr_date,
                ]);
            } else {
                $budgetFix->update([
                    'usage' => $usage,
                    'internal_order' => $internal_order,
                    'percentage_usage' => $percentage_usage,
                    'sisa' => $sisa_usage,
                    'pr_date' => $pr_date,
                ]);
            }
        }

        return redirect()->route('operator.pr.index')->with('success', 'Purchase Request berhasil dibuat.');
    }

    public function operator_pr_update(Request $request, $id)
    {
        $validated = $request->validate([
            'pr_date' => 'required|date',
            'pr_no' => 'required|string|unique:pr,pr_no,' . $id,
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
        $oldValuation = $pr->valuation_price;
        $pr->update($validated);

        $budgetFix = BudgetFix::where('gl_code', $validated['gl_code'])->latest()->first();
        $glaccount = Gl_Account::where('gl_code', $validated['gl_code'])->firstOrFail();

        if ($budgetFix) {
            $newUsage = $validated['valuation_price'];
            $diffUsage = $newUsage - $oldValuation;

            $bg_approve = $budgetFix->bg_approve ?? 0;
            $internal_order = $validated['io_assetcode'] ?? null;
            $pr_date = $validated['pr_date'] ?? null;
            $newSisa = $budgetFix->sisa - $diffUsage;
            $newPercentageUsage = ($bg_approve > 0) ? (($budgetFix->usage + $diffUsage) / $bg_approve) * 100 : 0;

            if ($budgetFix->usage > 0 && $diffUsage != 0) {
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
                    'pr_date' => $pr_date,
                ]);
            } elseif ($budgetFix->usage == 0) {
                $budgetFix->update([
                    'usage' => $newUsage,
                    'internal_order' => $internal_order,
                    'percentage_usage' => $newPercentageUsage,
                    'sisa' => $newSisa,
                    'pr_date' => $pr_date,
                ]);
            }
        }
        return redirect()->route('operator.pr.index')->with('success', 'Purchase Request berhasil diperbarui.');
    }




    public function operator_pr_edit($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        $units = Unit::all();
        $budgets = Budget::all();
        $gls = Gl_Account::all();
        return view('operator.budget_pr.pr.edit', compact('pr', 'units', 'budgets', 'gls'));
    }


    public function operator_pr_destroy($id)
    {
        $pr = PurchaseRequest::findOrFail($id);

        // Ambil data terkait untuk penyesuaian budget
        $valuation = $pr->valuation_price;
        $gl_code = $pr->gl_code;
        $internal_order = $pr->io_assetcode;

        // Hapus PR terlebih dahulu
        $pr->delete();

        // Ambil semua budgetFix terkait
        $query = BudgetFix::where('gl_code', $gl_code)
            ->where('usage', $valuation);

        if ($internal_order) {
            $query->where('internal_order', $internal_order);
        }

        $matchingBudgetFixes = $query->get();

        if ($matchingBudgetFixes->count() === 1) {
            // Kalau hanya ada 1, update sisa dan usage saja, jangan hapus
            $fix = $matchingBudgetFixes->first();

            $newUsage = 0;
            $newSisa = $fix->bg_approve;
            $newPercentageUsage = 0;

            $fix->update([
                'usage' => $newUsage,
                'sisa' => $newSisa,
                'percentage_usage' => $newPercentageUsage,
            ]);
        } elseif ($matchingBudgetFixes->count() > 1) {
            // Kalau ada lebih dari 1, hapus baris yang sesuai
            foreach ($matchingBudgetFixes as $fix) {
                $fix->delete();
            }
        }

        return redirect()->route('operator.pr.index')
            ->with('success', 'Purchase Request dan data budget terkait berhasil diperbarui.');
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
}
