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
        $prs = PurchaseRequest::with(['glAccount', 'budget'])->get();
        return view('adminsystem.budget_pr.index', compact('budgets', 'danaTerpakai', 'danaTersisa', 'prs','budget_fixs'));
    }

    public function create()
    {
        // Fetch data for GL Account, PR, and Budget tables
        $gl_accounts = Gl_Account::all(); // Fetch all GL accounts
        $material_groups = MaterialGroup::all(); // Assuming you have a MaterialGroup model
        $units = Unit::all(); // Assuming you have a Unit model
        $purs = PurchasingGroup::all(); // Assuming you have a Purchasing Group model

        // Return the view with the fetched data
        return view('adminsystem.budget_pr.create', compact('gl_accounts', 'material_groups', 'units', 'purs'));
    }

    // Store the new Purchase Request
    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'gl_code' => 'required|string|max:255',
            'internal_order' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'bg_approve' => 'required|boolean',
            'usage' => 'required|numeric',
            'plan' => 'required|numeric',
            'percentage_usage' => 'required|numeric',
            'year' => 'required|integer',
        ]);

        // Fetch relevant data from PR and Budget tables for calculation
        $gl_account = Gl_Account::where('gl_code', $request->gl_code)->first();
        $pr = PurchaseRequest::where('gl_account', $request->gl_code)->first();  // Assumes PR table has gl_account column
        $budget = Budget::where('gl_code', $request->gl_code)->first();

        // Calculate the percentage_usage
        $percentage_usage = ($request->usage / $request->bg_approve) * 100;

        // Save the new Purchase Request in the database
        // Assuming a PurchaseRequest model exists to store the data
        PurchaseRequest::create([
            'gl_code' => $request->gl_code,
            'internal_order' => $request->internal_order,
            'description' => $request->description,
            'bg_approve' => $request->bg_approve,
            'usage' => $request->usage,
            'plan' => $request->plan,
            'percentage_usage' => $percentage_usage,
            'year' => $request->year,
            // Add other fields as needed
        ]);

        // Redirect back with success message
        return redirect()->route('adminsystem.budget_pr.create')->with('success', 'Purchase Request successfully created!');
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
            'setahun_qty' => 'required|numeric',
            'kategori' => 'required|string|max:100',
            'year' => 'required|string|max:100',
        ]);

        // Store the data in the database
        Budget::create([
            'internal_order' => $request->internal_order,
            'gl_code' => $request->gl_code,
            'gl_name' => $request->gl_name,
            'setahun_total' => $request->setahun_total,
            'setahun_qty' => $request->setahun_qty,
            'kategori' => $request->kategori,
            'year' => $request->year
        ]);
          
        return redirect()->route('adminsystem.budget.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function budget_destroy(Document $id)
    {
        $budget = PurchaseRequest::findOrFail($id);
        $budget->delete();

        return redirect()->route('adminsystem.budget_pr.bug=dget.index')->with('success', 'Budget Plan berhasil dihapus.');
    }
    public function budget_update(Request $request, $id)
    {
        $budget = PurchaseRequest::findOrFail($id);

        $request->validate([
            'gl_code' => 'required|string|max:50',
            'gl_name' => 'required|string|max:100',
            'setahun_total' => 'required|numeric',
            'setahun_qty' => 'required|numeric',
            'kategori' => 'required|string|max:100',
           
        ]);
        $budget->update($request->all());

        return redirect()->route('adminsystem.budget_pr.pr.index')->with('success', 'Purchase Request berhasil diperbarui.');
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
        $request->validate([
            'pr_date' => 'required|date',
            'pr_no' => 'required|string|unique:pr,pr_no',
            'pr_category' => 'required|string',
            'account_assignment' => 'required|string',
            'item_category' => 'required|string',
            'purchase_for' => 'required|string',
            'material_code' => 'required|string',
            'short_text' => 'required|string',
            'quantity' => 'required|numeric',
            'unit' => 'required|string',
            'valuation_price' => 'required|numeric',
            'io_assetcode' => 'nullable|string',
            'gl_account' => 'required|string',
            'cost_center' => 'required|string',
            'matl_group' => 'required|string',
            'purchasing_group' => 'required|string',
        ]);

        PurchaseRequest::create($request->all());

        return redirect()->route('adminsystem.pr.index')->with('success', 'Purchase Request berhasil dibuat.');
    }

    public function pr_edit($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        return view('adminsystem.budget_pr.pr.edit', compact('pr'));
    }

    public function pr_update(Request $request, $id)
    {
        $pr = PurchaseRequest::findOrFail($id);

        $request->validate([
            'pr_date' => 'required|date',
            'pr_no' => 'required|string|unique:pr,pr_no',
            'pr_category' => 'required|string',
            'account_assignment' => 'required|string',
            'item_category' => 'required|string',
            'purchase_for' => 'required|string',
            'material_code' => 'required|string',
            'short_text' => 'required|string',
            'quantity' => 'required|numeric',
            'unit' => 'required|string',
            'valuation_price' => 'required|numeric',
            'io_assetcode' => 'nullable|string',
            'gl_account' => 'required|string',
            'cost_center' => 'required|string',
            'matl_group' => 'required|string',
            'purchasing_group' => 'required|string',
        ]);

        $pr->update($request->all());

        return redirect()->route('adminsystem.budget_pr.pr.index')->with('success', 'Purchase Request berhasil diperbarui.');
    }

    public function pr_destroy($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        $pr->delete();

        return redirect()->route('adminsystem.budget_pr.pr.index')->with('success', 'Purchase Request berhasil dihapus.');
    }


    //UNIT
    public function unit_index()
    {
        $units = Unit::all();
        return view('adminsystem.budget_pr.master_menu.unit.index', compact('units'));
    }

    public function unit_create()
    {
        return view('adminsystem.budget_pr.master_menu.unit.create');
    }

    public function unit_store(Request $request)
    {
        $request->validate([
            'unit' => 'required|string',
            'description' => 'required|string'
        ]);

        Unit::create($request->all());

        return redirect()->route('adminsystem.unit.index')->with('success', 'Purchase Request berhasil dibuat.');
    }

    public function unit_edit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('adminsystem.budget_pr.master_menu.unit.edit', compact('unit'));
    }

    public function unit_update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'unit' => 'required|string',
            'description' => 'required|string',
        ]);

        $unit->update($request->all());

        return redirect()->route('adminsystem.unit.index')->with('success', 'Purchase Request berhasil diperbarui.');
    }

    public function unit_destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('adminsystem.unit.index')->with('success', 'Purchase Request berhasil dihapus.');
    }


    //PUR_GROUP
    public function purchasinggroup_index()
    {
        $purs = PurchasingGroup::all();
        return view('adminsystem.budget_pr.master_menu.purchasinggroup.index', compact('purs'));
    }

    public function purchasinggroup_create()
    {
        return view('adminsystem.budget_pr.master_menu.purchasinggroup.create');
    }

    public function purchasinggroup_store(Request $request)
    {
        $request->validate([
            'pur_grp' => 'required|string',
            'department' => 'required|string'
        ]);

        PurchasingGroup::create($request->all());

        return redirect()->route('adminsystem.purchasinggroup.index')->with('success', 'Purchase Request berhasil dibuat.');
    }

    public function purchasinggroup_edit($id)
    {
        $pur = PurchasingGroup::findOrFail($id);
        return view('adminsystem.budget_pr.master_menu.purchasinggroup.edit', compact('pur'));
    }

    public function purchasinggroup_update(Request $request, $id)
    {
        $pur = PurchasingGroup::findOrFail($id);

        $request->validate([
            'pur_grp' => 'required|string',
            'department' => 'required|string',
        ]);

        $pur->update($request->all());

        return redirect()->route('adminsystem.purchasinggroup.index')->with('success', 'Purchase Request berhasil diperbarui.');
    }

    public function purchasinggroup_destroy($id)
    {
        $pur = PurchasingGroup::findOrFail($id);
        $pur->delete();

        return redirect()->route('adminsystem.purchasinggroup.index')->with('success', 'Purchase Request berhasil dihapus.');
    }

    // PLANT
    public function material_group_index()
    {
        $material_groups = MaterialGroup::all();
        return view('adminsystem.budget_pr.master_menu.material_group.index', compact('material_groups'));
    }

    public function material_group_create()
    {
        return view('adminsystem.budget_pr.master_menu.material_group.create');
    }

    public function material_group_store(Request $request)
    {
        $request->validate([
            'material_group_code' => 'required|string',
            'description' => 'required|string'
        ]);

        MaterialGroup::create($request->all());

        return redirect()->route('adminsystem.material_group.index')->with('success', 'Purchase Request berhasil dibuat.');
    }

    public function material_group_edit($id)
    {
        $material_group = MaterialGroup::findOrFail($id);
        return view('adminsystem.budget_pr.master_menu.material_group.edit', compact('material_group'));
    }

    public function material_group_update(Request $request, $id)
    {
        $material_group = MaterialGroup::findOrFail($id);

        $request->validate([
            'material_group_code' => 'required|string',
            'description' => 'required|string',
        ]);

        $material_group->update($request->all());

        return redirect()->route('adminsystem.material_group.index')->with('success', 'Purchase Request berhasil diperbarui.');
    }

    public function material_group_destroy($id)
    {
        $material_group = MaterialGroup::findOrFail($id);
        $material_group->delete();

        return redirect()->route('adminsystem.material_group.index')->with('success', 'Purchase Request berhasil dihapus.');
    }


    // GL ACCOUNT
    public function glaccount_index()
    {
        $gls = Gl_Account::all();
        return view('adminsystem.budget_pr.master_menu.glaccount.index', compact('gls'));
    }

    public function glaccount_create()
    {
        return view('adminsystem.budget_pr.master_menu.glaccount.create');
    }

    public function glaccount_store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'gl_code' => 'required|string|unique|max:50',
            'gl_name' => 'required|string|max:100',
            'description' => 'required|string|max:100',

        ]);

        // Store the data in the database
        Gl_Account::create([
            'gl_code' => $request->gl_code,
            'gl_name' => $request->gl_name,
            'description' => $request->description,

        ]);

        // Redirect back with a success message
        return redirect()->route('adminsystem.glaccount.index')->with('success', ' berhasil dibuat.');
    }


    public function glaccount_edit($id)
    {
        $gl = Gl_Account::findOrFail($id);
        return view('adminsystem.budget_pr.master_menu.glaccount.edit', compact('gl'));
    }

    public function glaccount_update(Request $request, $id)
    {
        $gl = Gl_Account::findOrFail($id);

        $request->validate([
            'gl_code' => 'required|string|unique|max:50',
            'gl_name' => 'required|string|max:100',
            'description' => 'required|string|max:100',

        ]);

        $gl->update($request->all());

        return redirect()->route('adminsystem.glaccount.index')->with('success', 'GL Account berhasil diperbarui.');
    }

    public function glaccount_destroy($id)
    {
        $gl = Gl_Account::findOrFail($id);
        $gl->delete();

        return redirect()->route('adminsystem.glaccount.index')->with('success', 'Purchase Request berhasil dihapus.');
    }
}
