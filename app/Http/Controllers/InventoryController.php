<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Incident;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Transaction;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $barangs = Barang::all();
        // Ambil input pencarian
        $search = $request->input('search');

        // Query untuk mengambil transaksi dengan filter pencarian
        $trans = Transaction::join('barang', 'transaction.barang_id', '=', 'barang.id')
            ->select('transaction.*', 'barang.material_code', 'barang.description')
            ->get();

        return view('adminsystem.inventory.index', compact('barangs', 'trans', 'search'));
    }
    public function pemasukan_index()
    {
        $barangs = Barang::all();
        $pemasukans = Pemasukan::all();
        return view('adminsystem.inventory.pemasukan.index', compact('barangs', 'pemasukans'));
    }
    public function pengeluaran_index()
    {
        $barangs = Barang::all();
        $pengeluarans = Pengeluaran::all();
        return view('adminsystem.inventory.pengeluaran.index', compact('barangs', 'pengeluarans'));
    }
    public function barang_create()
    {
        $barangs = Barang::all();
        $units = Unit::all();
        return view('adminsystem.inventory.barang.create', compact('barangs', 'units'));
    }
    public function barang_edit($id)
    {
        $barangs = Barang::findOrFail($id);
        return view('adminsystem.inventory.barang.edit', compact('barangs'));
    }
    public function barang_detail($id)
    {
        $barangs = Barang::findOrFail($id);
        $trans = Transaction::join('barang', 'transaction.barang_id', '=', 'barang.id')
            ->select('transaction.*', 'barang.material_code', 'barang.description')
            ->where('transaction.barang_id', $id)
            ->get();

        return view('adminsystem.inventory.barang.detail', compact('barangs', 'trans'));
    }
    public function getBarangDetails($id)
    {
        // Find the barang by ID
        $barang = Barang::find($id);

        if ($barang) {
            // Return unit and keterangan
            return response()->json([
                'barang_id' => $barang->barang_id, 
                'unit' => $barang->unit,
            ]);
        }

        return response()->json([], 404);  // Return an empty response if the barang is not found
    }

    public function barang_store(Request $request)
    {
        $request->validate([
            'material_code' => 'required|string|max:255',
            'material_group' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'material_type' => 'required|string|max:255',
            'remark' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $barang = new Barang();
        $barang->material_code = $request->material_code;
        $barang->material_group = $request->material_group;
        $barang->description = $request->description;
        $barang->material_type = $request->material_type;
        $barang->remark = $request->remark;
        $barang->unit = $request->unit;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/images'), $imageName);
            $barang->image = $imageName;
        }

        $barang->save();

        return redirect()->route('adminsystem.inventory.index')->with('success', 'Barang berhasil ditambahkan!');
    }
    public function barang_update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'material_code' => 'required|string|max:255',
            'material_group' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'material_type' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image validation
        ]);

        // Find the existing barang by ID
        $barang = Barang::findOrFail($id);

        // Update the fields
        $barang->material_code = $request->material_code;
        $barang->material_group = $request->material_group;
        $barang->description = $request->description;
        $barang->material_type = $request->material_type;
        $barang->quantity = $request->quantity; // Optional if you have quantity field
        $barang->unit = $request->unit;

        // Check if the user has uploaded a new image
        if ($request->hasFile('image')) {
            // Delete the old image from the storage folder (optional, if you want to replace the old image)
            if ($barang->image && file_exists(public_path('storage/images/' . $barang->image))) {
                unlink(public_path('storage/images/' . $barang->image));  // Delete the old image
            }

            // Store the new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/images'), $imageName);

            // Update the image name in the database
            $barang->image = $imageName;
        }

        // Save the updated barang to the database
        $barang->save();

        // Redirect the user with a success message
        return redirect()->route('adminsystem.inventory.index')->with('success', 'Barang berhasil diperbarui!');
    }
    public function barang_destroy($id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->image) {
            $imagePath = public_path('storage/images/' . $barang->image);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $barang->delete();

        return redirect()->route('adminsystem.inventory.index')->with('success', 'Barang berhasil dihapus!');
    }


    public function pemasukan_edit($id)
    {
        $pemasukans = Pemasukan::findOrFail($id);
        $barangs = Barang::all();
        $units = Unit::all();
        return view('adminsystem.inventory.pemasukan.edit', compact('barangs', 'units', 'pemasukans'));
    }
    public function pengeluaran_edit($id)
    {
        $pengeluarans = Pengeluaran::findOrFail($id);
        $barangs = Barang::all();
        $units = Unit::all();
        return view('adminsystem.inventory.pengeluaran.edit', compact('barangs', 'units', 'pengeluarans'));
    }
    public function pemasukan_create()
    {
        $barangs = Barang::all();
        $units = Unit::all();
        return view('adminsystem.inventory.pemasukan.create', compact('barangs', 'units'));
    }
    public function pengeluaran_create()
    {
        $barangs = Barang::all();
        $units = Unit::all();
        return view('adminsystem.inventory.pengeluaran.create', compact('barangs', 'units'));
    }
    public function pemasukan_store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $pemasukan = new Pemasukan();
        $pemasukan->barang_id = $request->barang_id;
        $pemasukan->quantity = $request->quantity;
        $pemasukan->unit = $request->unit;
        $pemasukan->keterangan = $request->keterangan;
        $pemasukan->tanggal = $request->tanggal;
        $pemasukan->save();

        return redirect()->route('adminsystem.pemasukan.index')->with('success', 'Pemasukan Barang Berhasil');
    }
    public function pemasukan_update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        // Find the existing pemasukan by ID
        $pemasukan = Pemasukan::findOrFail($id);
        $pemasukan->barang_id = $request->barang_id;
        $pemasukan->quantity = $request->quantity;
        $pemasukan->unit = $request->unit;
        $pemasukan->keterangan = $request->keterangan;
        $pemasukan->tanggal = $request->tanggal;
        $pemasukan->save();

        return redirect()->route('adminsystem.pemasukan.index')->with('success', 'Pemasukan Barang Berhasil Diperbarui');
    }

    public function pemasukan_destroy($id)
    {
        $pemasukan = Pemasukan::find($id);
        $pemasukan->delete();
        return redirect()->route('adminsystem.pemasukan.index')->with('success', 'Pemasukan Berhasil Dihapus');
    }

    public function pengeluaran_store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $pemasukan = new Pengeluaran();
        $pemasukan->barang_id = $request->barang_id;
        $pemasukan->quantity = $request->quantity;
        $pemasukan->unit = $request->unit;
        $pemasukan->keterangan = $request->keterangan;
        $pemasukan->tanggal = $request->tanggal;
        $pemasukan->save();
        return redirect()->route('adminsystem.pengeluaran.index')->with('success', 'Pemasukan Barang Berhasil');
    }
    public function pengeluaran_update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        // Find the existing pengeluaran by ID
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->barang_id = $request->barang_id;
        $pengeluaran->quantity = $request->quantity;
        $pengeluaran->unit = $request->unit;
        $pengeluaran->keterangan = $request->keterangan;
        $pengeluaran->tanggal = $request->tanggal;
        $pengeluaran->save();

        return redirect()->route('adminsystem.pengeluaran.index')->with('success', 'Pengeluaran Barang Berhasil Diperbarui');
    }

    public function pengeluaran_destroy($id)
    {
        $pengeluaran = Pengeluaran::find($id);
        $pengeluaran->delete();
        return redirect()->route('adminsystem.pengeluaran.index')->with('success', 'Pengeluaran Berhasil Dihapus');
    }
    
}
