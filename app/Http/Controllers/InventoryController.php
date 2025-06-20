<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Incident;
use App\Models\MaterialGroup;
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
        $material_groups = MaterialGroup::all();
        $units = Unit::all();
        return view('adminsystem.inventory.barang.create', compact('barangs', 'units', 'material_groups'));
    }
    public function barang_edit($id)
    {
        $barang = Barang::findOrFail($id);
        $material_groups = MaterialGroup::all();
        $units = Unit::all();
        return view('adminsystem.inventory.barang.edit', compact('barang', 'units', 'material_groups'));
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
            'material_code' => 'required|string|max:50',
            'material_group_id' => 'required|exists:material_group,id',
            'material_group' => 'nullable|string|max:50', // akan diambil dari relasi
            'description' => 'required|string',
            'material_type' => 'required|string|max:50',
            'remark' => 'required|string',
            'unit_id' => 'required|exists:unit,id',
            'unit' => 'nullable|string|max:20', // akan diambil dari relasi
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Ambil relasi unit dan material group
        $unit = Unit::findOrFail($request->unit_id);
        $material_group = MaterialGroup::findOrFail($request->material_group_id);

        $barang = new Barang();
        $barang->material_code = $request->material_code;
        $barang->material_group_id = $request->material_group_id;
        $barang->material_group = $material_group->material_group;
        $barang->description = $request->description;
        $barang->material_type = $request->material_type;
        $barang->remark = $request->remark;
        $barang->unit_id = $request->unit_id;
        $barang->unit = $unit->unit;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/images'), $imageName);
            $barang->image = $imageName; // pastikan kolom image di DB adalah string, bukan binary
        }

        $barang->save();

        return redirect()->route('adminsystem.inventory.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function barang_update(Request $request, $id)
    {
        $request->validate([
            'material_code' => 'required|string|max:50',
            'material_group_id' => 'required|exists:material_group,id',
            'material_group' => 'nullable|string|max:50', // akan diambil dari relasi
            'description' => 'required|string',
            'material_type' => 'required|string|max:50',
            'remark' => 'required|string',
            'unit_id' => 'required|exists:unit,id',
            'unit' => 'nullable|string|max:20', // akan diambil dari relasi
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $barang = Barang::findOrFail($id);

        // Ambil relasi unit dan material group
        $unit = Unit::findOrFail($request->unit_id);
        $material_group = MaterialGroup::findOrFail($request->material_group_id);

        // Update field
        $barang->material_code = $request->material_code;
        $barang->material_group_id = $request->material_group_id;
        $barang->material_group = $material_group->material_group;
        $barang->description = $request->description;
        $barang->material_type = $request->material_type;
        $barang->remark = $request->remark;
        $barang->unit_id = $request->unit_id;
        $barang->unit = $unit->unit;

        // Jika ada gambar baru
        if ($request->hasFile('image')) {
            // Opsional: hapus gambar lama jika ada
            if ($barang->image && file_exists(public_path('storage/images/' . $barang->image))) {
                unlink(public_path('storage/images/' . $barang->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/images'), $imageName);
            $barang->image = $imageName;
        }

        $barang->save();

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
        $pemasukan = Pemasukan::findOrFail($id);
        $barangs = Barang::all();
        $material_groups = MaterialGroup::all();
        $units = Unit::all();
        return view('adminsystem.inventory.pemasukan.edit', compact('barangs', 'units', 'material_groups', 'pemasukan'));
    }
    public function pengeluaran_edit($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $barangs = Barang::all();
        $material_groups = MaterialGroup::all();
        $units = Unit::all();
        return view('adminsystem.inventory.pengeluaran.edit', compact('barangs', 'units', 'pengeluaran'));
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
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        // Ambil data barang hanya sekali
        $barang = Barang::findOrFail($request->barang_id);

        // Simpan ke tabel pemasukan
        $pemasukan = new Pemasukan();
        $pemasukan->barang_id = $barang->id;
        $pemasukan->quantity = $request->quantity;
        $pemasukan->unit = $barang->unit; // Diambil dari tabel barang, bukan dari input
        $pemasukan->keterangan = $request->keterangan;
        $pemasukan->tanggal = $request->tanggal;
        $pemasukan->save();

        // Update quantity barang
        $barang->quantity += $request->quantity;
        $barang->save();

        // Simpan ke tabel transaction
        $transaction = new Transaction();
        $transaction->barang_id = $barang->id;
        $transaction->quantity = $request->quantity;
        $transaction->unit = $barang->unit;
        $transaction->keterangan = $request->keterangan;
        $transaction->tanggal = $request->tanggal;
        $transaction->type = 'Pemasukan';
        $transaction->save();

        return redirect()->route('adminsystem.pemasukan.index')->with('success', 'Pemasukan Barang Berhasil');
    }


    public function pemasukan_update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'quantity' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        // Ambil data pemasukan lama
        $pemasukan = Pemasukan::findOrFail($id);
        $oldBarangId = $pemasukan->barang_id;
        $oldTanggal = $pemasukan->tanggal;

        // Kurangi stok lama
        $oldBarang = Barang::findOrFail($oldBarangId);
        $oldBarang->quantity -= $pemasukan->quantity;
        $oldBarang->save();

        // Jika barang_id diganti
        if ($request->barang_id != $oldBarangId) {
            $newBarang = Barang::findOrFail($request->barang_id);
        } else {
            $newBarang = $oldBarang;
        }

        // Tambahkan stok baru
        $newBarang->quantity += $request->quantity;
        $newBarang->save();

        // Update data pemasukan
        $pemasukan->barang_id = $request->barang_id;
        $pemasukan->quantity = $request->quantity;
        $pemasukan->unit = $request->unit;
        $pemasukan->keterangan = $request->keterangan;
        $pemasukan->tanggal = $request->tanggal;
        $pemasukan->save();

        // Update transaction terkait
        $transaction = Transaction::where('type', 'Pemasukan')
            ->where('barang_id', $oldBarangId)
            ->where('tanggal', $oldTanggal)
            ->first();

        if ($transaction) {
            $transaction->barang_id = $request->barang_id;
            $transaction->quantity = $request->quantity;
            $transaction->unit = $request->unit;
            $transaction->keterangan = $request->keterangan;
            $transaction->tanggal = $request->tanggal;
            $transaction->save();
        }

        return redirect()->route('adminsystem.pemasukan.index')->with('success', 'Data Pemasukan berhasil diperbarui');
    }


    public function pemasukan_destroy($id)
    {
        // Ambil data pemasukan
        $pemasukan = Pemasukan::findOrFail($id);

        // Ambil data barang terkait
        $barang = Barang::findOrFail($pemasukan->barang_id);

        // Kurangi jumlah stok barang
        $barang->quantity -= $pemasukan->quantity;

        // Pastikan quantity tidak negatif
        if ($barang->quantity < 0) {
            $barang->quantity = 0;
        }

        $barang->save();

        // Hapus data pemasukan
        $pemasukan->delete();

        // Hapus transaksi yang sesuai
        Transaction::where('barang_id', $pemasukan->barang_id)
            ->where('quantity', $pemasukan->quantity)
            ->where('tanggal', $pemasukan->tanggal)
            ->where('type', 'Pemasukan')
            ->first()?->delete();

        return redirect()->route('adminsystem.pemasukan.index')->with('success', 'Data pemasukan berhasil dihapus dan stok barang diperbarui.');
    }

    public function pengeluaran_store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'quantity' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        // Cek apakah quantity yang diminta lebih dari stok
        if ($request->quantity > $barang->quantity) {
            return redirect()->back()->with('error', 'Quantity melebihi stok barang yang tersedia.');
        }

        $pengeluaran = new Pengeluaran();
        $pengeluaran->barang_id = $barang->id;
        $pengeluaran->quantity = $request->quantity;
        $pengeluaran->unit = $barang->unit;
        $pengeluaran->keterangan = $request->keterangan;
        $pengeluaran->tanggal = $request->tanggal;
        $pengeluaran->save();

        $barang->quantity -= $request->quantity;
        $barang->save();

        $transaction = new Transaction();
        $transaction->barang_id = $barang->id;
        $transaction->quantity = $request->quantity;
        $transaction->unit = $barang->unit;
        $transaction->keterangan = $request->keterangan;
        $transaction->tanggal = $request->tanggal;
        $transaction->type = 'Pengeluaran';
        $transaction->save();

        return redirect()->route('adminsystem.pengeluaran.index')->with('success', 'Pengeluaran Barang Berhasil');
    }

    public function pengeluaran_update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'quantity' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $pengeluaran = Pengeluaran::findOrFail($id);
        $oldBarang = Barang::findOrFail($pengeluaran->barang_id);

        // Kembalikan stok lama
        $oldBarang->quantity += $pengeluaran->quantity;
        $oldBarang->save();

        // Ambil barang baru
        $newBarang = Barang::findOrFail($request->barang_id);

        // Cek stok cukup atau tidak
        if ($request->quantity > $newBarang->quantity) {
            return redirect()->back()->with('error', 'Quantity melebihi stok barang yang tersedia.');
        }

        // Kurangi stok baru
        $newBarang->quantity -= $request->quantity;
        $newBarang->save();

        $pengeluaran->barang_id = $request->barang_id;
        $pengeluaran->quantity = $request->quantity;
        $pengeluaran->unit = $newBarang->unit;
        $pengeluaran->keterangan = $request->keterangan;
        $pengeluaran->tanggal = $request->tanggal;
        $pengeluaran->save();

        // Update transaction
        $transaction = Transaction::where('type', 'Pengeluaran')
            ->where('barang_id', $pengeluaran->barang_id)
            ->where('tanggal', $pengeluaran->tanggal)
            ->first();

        if ($transaction) {
            $transaction->barang_id = $request->barang_id;
            $transaction->quantity = $request->quantity;
            $transaction->unit = $newBarang->unit;
            $transaction->keterangan = $request->keterangan;
            $transaction->tanggal = $request->tanggal;
            $transaction->save();
        }

        return redirect()->route('adminsystem.pengeluaran.index')->with('success', 'Data Pengeluaran berhasil diperbarui');
    }


    public function pengeluaran_destroy($id)
    {
        // Ambil data pemasukan
        $pengeluaran = Pengeluaran::findOrFail($id);

        // Ambil data barang terkait
        $barang = Barang::findOrFail($pengeluaran->barang_id);

        // Kurangi jumlah stok barang
        $barang->quantity += $pengeluaran->quantity;

        // Pastikan quantity tidak negatif
        if ($barang->quantity < 0) {
            $barang->quantity = 0;
        }

        $barang->save();

        // Hapus data pengeluaran
        $pengeluaran->delete();

        // Hapus transaksi yang sesuai
        Transaction::where('barang_id', $pengeluaran->barang_id)
            ->where('quantity', $pengeluaran->quantity)
            ->where('tanggal', $pengeluaran->tanggal)
            ->where('type', 'Pengeluaran')
            ->first()?->delete();

        return redirect()->route('adminsystem.pengeluaran.index')->with('success', 'Data pengeluaran berhasil dihapus dan stok barang diperbarui.');
    }
















    public function operator_index(Request $request)
    {
        $barangs = Barang::all();
        // Ambil input pencarian
        $search = $request->input('search');

        // Query untuk mengambil transaksi dengan filter pencarian
        $trans = Transaction::join('barang', 'transaction.barang_id', '=', 'barang.id')
            ->select('transaction.*', 'barang.material_code', 'barang.description')
            ->get();

        return view('operator.inventory.index', compact('barangs', 'trans', 'search'));
    }
    public function operator_pemasukan_index()
    {
        $barangs = Barang::all();
        $pemasukans = Pemasukan::all();
        return view('operator.inventory.pemasukan.index', compact('barangs', 'pemasukans'));
    }
    public function operator_pengeluaran_index()
    {
        $barangs = Barang::all();
        $pengeluarans = Pengeluaran::all();
        return view('operator.inventory.pengeluaran.index', compact('barangs', 'pengeluarans'));
    }
    public function operator_barang_create()
    {
        $barangs = Barang::all();
        $material_groups = MaterialGroup::all();
        $units = Unit::all();
        return view('operator.inventory.barang.create', compact('barangs', 'units', 'material_groups'));
    }
    public function operator_barang_edit($id)
    {
        $barang = Barang::findOrFail($id);
        $material_groups = MaterialGroup::all();
        $units = Unit::all();
        return view('operator.inventory.barang.edit', compact('barang', 'units', 'material_groups'));
    }
    public function operator_barang_detail($id)
    {
        $barangs = Barang::findOrFail($id);
        $trans = Transaction::join('barang', 'transaction.barang_id', '=', 'barang.id')
            ->select('transaction.*', 'barang.material_code', 'barang.description')
            ->where('transaction.barang_id', $id)
            ->get();

        return view('operator.inventory.barang.detail', compact('barangs', 'trans'));
    }
    public function operator_getBarangDetails($id)
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

    public function operator_barang_store(Request $request)
    {
        $request->validate([
            'material_code' => 'required|string|max:50',
            'material_group_id' => 'required|exists:material_group,id',
            'material_group' => 'nullable|string|max:50', // akan diambil dari relasi
            'description' => 'required|string',
            'material_type' => 'required|string|max:50',
            'remark' => 'required|string',
            'unit_id' => 'required|exists:unit,id',
            'unit' => 'nullable|string|max:20', // akan diambil dari relasi
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Ambil relasi unit dan material group
        $unit = Unit::findOrFail($request->unit_id);
        $material_group = MaterialGroup::findOrFail($request->material_group_id);

        $barang = new Barang();
        $barang->material_code = $request->material_code;
        $barang->material_group_id = $request->material_group_id;
        $barang->material_group = $material_group->material_group;
        $barang->description = $request->description;
        $barang->material_type = $request->material_type;
        $barang->remark = $request->remark;
        $barang->unit_id = $request->unit_id;
        $barang->unit = $unit->unit;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/images'), $imageName);
            $barang->image = $imageName; // pastikan kolom image di DB adalah string, bukan binary
        }

        $barang->save();

        return redirect()->route('operator.inventory.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function operator_barang_update(Request $request, $id)
    {
        $request->validate([
            'material_code' => 'required|string|max:50',
            'material_group_id' => 'required|exists:material_group,id',
            'material_group' => 'nullable|string|max:50', // akan diambil dari relasi
            'description' => 'required|string',
            'material_type' => 'required|string|max:50',
            'remark' => 'required|string',
            'unit_id' => 'required|exists:unit,id',
            'unit' => 'nullable|string|max:20', // akan diambil dari relasi
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $barang = Barang::findOrFail($id);

        // Ambil relasi unit dan material group
        $unit = Unit::findOrFail($request->unit_id);
        $material_group = MaterialGroup::findOrFail($request->material_group_id);

        // Update field
        $barang->material_code = $request->material_code;
        $barang->material_group_id = $request->material_group_id;
        $barang->material_group = $material_group->material_group;
        $barang->description = $request->description;
        $barang->material_type = $request->material_type;
        $barang->remark = $request->remark;
        $barang->unit_id = $request->unit_id;
        $barang->unit = $unit->unit;

        // Jika ada gambar baru
        if ($request->hasFile('image')) {
            // Opsional: hapus gambar lama jika ada
            if ($barang->image && file_exists(public_path('storage/images/' . $barang->image))) {
                unlink(public_path('storage/images/' . $barang->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/images'), $imageName);
            $barang->image = $imageName;
        }

        $barang->save();

        return redirect()->route('operator.inventory.index')->with('success', 'Barang berhasil diperbarui!');
    }

    public function operator_barang_destroy($id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->image) {
            $imagePath = public_path('storage/images/' . $barang->image);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $barang->delete();

        return redirect()->route('operator.inventory.index')->with('success', 'Barang berhasil dihapus!');
    }


    public function operator_pemasukan_edit($id)
    {
        $pemasukan = Pemasukan::findOrFail($id);
        $barangs = Barang::all();
        $material_groups = MaterialGroup::all();
        $units = Unit::all();
        return view('operator.inventory.pemasukan.edit', compact('barangs', 'units', 'material_groups', 'pemasukan'));
    }
    public function operator_pengeluaran_edit($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $barangs = Barang::all();
        $material_groups = MaterialGroup::all();
        $units = Unit::all();
        return view('operator.inventory.pengeluaran.edit', compact('barangs', 'units', 'pengeluaran'));
    }
    public function operator_pemasukan_create()
    {
        $barangs = Barang::all();
        $units = Unit::all();
        return view('operator.inventory.pemasukan.create', compact('barangs', 'units'));
    }
    public function operator_pengeluaran_create()
    {
        $barangs = Barang::all();
        $units = Unit::all();
        return view('operator.inventory.pengeluaran.create', compact('barangs', 'units'));
    }
    public function operator_pemasukan_store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'quantity' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        // Ambil data barang hanya sekali
        $barang = Barang::findOrFail($request->barang_id);

        // Simpan ke tabel pemasukan
        $pemasukan = new Pemasukan();
        $pemasukan->barang_id = $barang->id;
        $pemasukan->quantity = $request->quantity;
        $pemasukan->unit = $barang->unit; // Diambil dari tabel barang, bukan dari input
        $pemasukan->keterangan = $request->keterangan;
        $pemasukan->tanggal = $request->tanggal;
        $pemasukan->save();

        // Update quantity barang
        $barang->quantity += $request->quantity;
        $barang->save();

        // Simpan ke tabel transaction
        $transaction = new Transaction();
        $transaction->barang_id = $barang->id;
        $transaction->quantity = $request->quantity;
        $transaction->unit = $barang->unit;
        $transaction->keterangan = $request->keterangan;
        $transaction->tanggal = $request->tanggal;
        $transaction->type = 'Pemasukan';
        $transaction->save();

        return redirect()->route('operator.pemasukan.index')->with('success', 'Pemasukan Barang Berhasil');
    }

    public function operator_pemasukan_update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'quantity' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        // Ambil data pemasukan lama
        $pemasukan = Pemasukan::findOrFail($id);
        $oldBarangId = $pemasukan->barang_id;
        $oldTanggal = $pemasukan->tanggal;

        // Kurangi stok lama
        $oldBarang = Barang::findOrFail($oldBarangId);
        $oldBarang->quantity -= $pemasukan->quantity;
        $oldBarang->save();

        // Jika barang_id diganti
        if ($request->barang_id != $oldBarangId) {
            $newBarang = Barang::findOrFail($request->barang_id);
        } else {
            $newBarang = $oldBarang;
        }

        // Tambahkan stok baru
        $newBarang->quantity += $request->quantity;
        $newBarang->save();

        // Update data pemasukan
        $pemasukan->barang_id = $request->barang_id;
        $pemasukan->quantity = $request->quantity;
        $pemasukan->unit = $request->unit;
        $pemasukan->keterangan = $request->keterangan;
        $pemasukan->tanggal = $request->tanggal;
        $pemasukan->save();

        // Update transaction terkait
        $transaction = Transaction::where('type', 'Pemasukan')
            ->where('barang_id', $oldBarangId)
            ->where('tanggal', $oldTanggal)
            ->first();

        if ($transaction) {
            $transaction->barang_id = $request->barang_id;
            $transaction->quantity = $request->quantity;
            $transaction->unit = $request->unit;
            $transaction->keterangan = $request->keterangan;
            $transaction->tanggal = $request->tanggal;
            $transaction->save();
        }

        return redirect()->route('operator.pemasukan.index')->with('success', 'Data Pemasukan berhasil diperbarui');
    }


    public function operator_pemasukan_destroy($id)
    {
        // Ambil data pemasukan
        $pemasukan = Pemasukan::findOrFail($id);

        // Ambil data barang terkait
        $barang = Barang::findOrFail($pemasukan->barang_id);

        // Kurangi jumlah stok barang
        $barang->quantity -= $pemasukan->quantity;

        // Pastikan quantity tidak negatif
        if ($barang->quantity < 0) {
            $barang->quantity = 0;
        }

        $barang->save();

        // Hapus data pemasukan
        $pemasukan->delete();

        // Hapus transaksi yang sesuai
        Transaction::where('barang_id', $pemasukan->barang_id)
            ->where('quantity', $pemasukan->quantity)
            ->where('tanggal', $pemasukan->tanggal)
            ->where('type', 'Pemasukan')
            ->first()?->delete();

        return redirect()->route('operator.pemasukan.index')->with('success', 'Data pemasukan berhasil dihapus dan stok barang diperbarui.');
    }

    public function operator_pengeluaran_store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'quantity' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);
        $barang = Barang::findOrFail($request->barang_id);

        $pengeluaran = new Pengeluaran();
        $pengeluaran->barang_id = $request->barang_id;
        $pengeluaran->quantity = $request->quantity;
        $pengeluaran->unit = $barang->unit;
        $pengeluaran->keterangan = $request->keterangan;
        $pengeluaran->tanggal = $request->tanggal;
        $pengeluaran->save();
        $barang = Barang::findOrFail($request->barang_id);
        $barang->quantity -= $request->quantity;
        $barang->save();

        // Simpan ke tabel transaction
        $transaction = new Transaction();
        $transaction->barang_id = $request->barang_id;
        $transaction->quantity = $request->quantity;
        $transaction->unit = $barang->unit;
        $transaction->keterangan = $request->keterangan;
        $transaction->tanggal = $request->tanggal;
        $transaction->type = 'Pengeluaran';
        $transaction->save();
        return redirect()->route('operator.pengeluaran.index')->with('success', 'Pengeluaran Barang Berhasil');
    }
    public function operator_pengeluaran_update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        // Ambil data pengeluaran lama
        $pengeluaran = Pengeluaran::findOrFail($id);
        $barang = Barang::findOrFail($pengeluaran->barang_id);

        // Kembalikan stok lama terlebih dahulu
        $barang->quantity += $pengeluaran->quantity;

        // Jika barang diganti, update stok lama & ambil barang baru
        if ($request->barang_id != $pengeluaran->barang_id) {
            $barang->save();
            $barang = Barang::findOrFail($request->barang_id);
        }

        // Kurangi stok dengan quantity baru
        $barang->quantity -= $request->quantity;
        $barang->save();

        // Update data pengeluaran
        $pengeluaran->barang_id = $request->barang_id;
        $pengeluaran->quantity = $request->quantity;
        $pengeluaran->unit = $request->unit;
        $pengeluaran->keterangan = $request->keterangan;
        $pengeluaran->tanggal = $request->tanggal;
        $pengeluaran->save();

        // Update transaction terkait
        $transaction = Transaction::where('type', 'Pengeluaran')
            ->where('barang_id', $pengeluaran->barang_id)
            ->where('tanggal', $pengeluaran->tanggal)
            ->first();

        if ($transaction) {
            $transaction->barang_id = $request->barang_id;
            $transaction->quantity = $request->quantity;
            $transaction->unit = $request->unit;
            $transaction->keterangan = $request->keterangan;
            $transaction->tanggal = $request->tanggal;
            $transaction->save();
        }

        return redirect()->route('operator.pengeluaran.index')->with('success', 'Data Pengeluaran berhasil diperbarui');
    }

    public function operator_pengeluaran_destroy($id)
    {
        // Ambil data pemasukan
        $pengeluaran = Pengeluaran::findOrFail($id);

        // Ambil data barang terkait
        $barang = Barang::findOrFail($pengeluaran->barang_id);

        // Kurangi jumlah stok barang
        $barang->quantity += $pengeluaran->quantity;

        // Pastikan quantity tidak negatif
        if ($barang->quantity < 0) {
            $barang->quantity = 0;
        }

        $barang->save();

        // Hapus data pengeluaran
        $pengeluaran->delete();

        // Hapus transaksi yang sesuai
        Transaction::where('barang_id', $pengeluaran->barang_id)
            ->where('quantity', $pengeluaran->quantity)
            ->where('tanggal', $pengeluaran->tanggal)
            ->where('type', 'Pengeluaran')
            ->first()?->delete();

        return redirect()->route('operator.pengeluaran.index')->with('success', 'Data pengeluaran berhasil dihapus dan stok barang diperbarui.');
    }
}
