<?php

namespace App\Http\Controllers;

use App\Models\tb_admin;
use App\Models\tb_pemberitahuan;
use App\Models\tb_pemberitahuan_category;
use Illuminate\Http\Request;
use App\Models\tb_pengumuman;
use Illuminate\Support\Facades\DB;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('show', 10);
        $pengumuman = tb_pemberitahuan::where(['type' => 2])->orderBy('date', 'desc')->paginate($perPage);

        $token = $request->session()->get('token') ?? $request->input('token');

        return view('admin.pengumuman.index', [
            'menu_active' => 'pengumuman',
            'token' => $token,
            'pengumuman' => $pengumuman,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $token = $request->session()->get('token') ?? $request->input('token');

        return view('admin.pengumuman.create', [
            'menu_active' => 'pengumuman',
            'pengumuman' => tb_pemberitahuan_category::where(['type' => 2])->get(),
            'token' => $token,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $token = $request->session()->get('token') ?? $request->input('token');

        $request->validate([
            'nama' => 'required',
            'id_pemberitahuan_category' => 'required',
            'target' => 'required',
            'text' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
        ], [
            'nama.required' => 'Kolom nama pengumuman harus diisi.',
            'id_pemberitahuan_category.required' => 'Kolom kategori pengumuman harus diisi.',
            'target.required' => 'Kolom target pengumuman harus diisi.',
            'text.required' => 'Kolom isi pengumuman harus diisi.',
            'date.required' => 'Kolom tanggal pengumuman harus diisi.',
            'date.date' => 'Kolom tanggal pengumuman harus dalam format tanggal yang benar.',
            'time.required' => 'Kolom waktu pengumuman harus diisi.',
            'thumbnail.required' => 'Kolom gambar wajib diisi',
            'thumbnail.max' => 'Ukuran gambar tidak boleh lebih dari 10MB'
        ]);

//        tb_pengumuman::create($request->all());
        $data = new tb_pemberitahuan();
        $data->nama = $request->nama;
        $data->category = $request->id_pemberitahuan_category;
        $data->target = $request->target;
        $data->text = $request->text;
        $data->date = $request->date;
        $data->time = $request->time;
        $data->type = 2;

        if ($request->hasFile('thumbnail')) {
            $fileContents = file_get_contents($request->file('thumbnail')->getRealPath());
            $imageName = hash('sha256', $fileContents) . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $request->file('thumbnail')->move('img/announcement', $imageName);
            $data->thumbnail = $imageName;
        }

        $data->save();
        return redirect()->route('pengumuman.index', ['token' => $token])->with('success', 'Pengumuman baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $id_pengumuman = $request->route("pengumuman");
        $token = $request->session()->get('token') ?? $request->input('token');
        $pengumuman = tb_pemberitahuan::where(['type' => 2])->findOrFail($id_pengumuman);

        return view('admin.pengumuman.show', [
            'menu_active' => 'pengumuman',
            'profile_active' => 'pengumuman',
            'token' => $token,
            'pengumuman' => $pengumuman,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $id_pengumuman = $request->route("pengumuman");
        $token = $request->session()->get('token') ?? $request->input('token');
        $pengumuman = tb_pemberitahuan::where(['type' => 2])->findOrFail($id_pengumuman);
        $categories = tb_pemberitahuan_category::where(["type" => 2])->get();

        return view('admin.pengumuman.edit', [
            'menu_active' => 'pengumuman',
            'token' => $token,
            'pengumuman' => $pengumuman,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id_pengumuman = $request->route("pengumuman");
        $request->validate([
            'nama' => 'required',
            'target' => 'required',
            'text' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
        ], [
            'nama.required' => 'Kolom nama pengumuman harus diisi.',
            'target.required' => 'Kolom target pengumuman harus diisi.',
            'text.required' => 'Kolom isi pengumuman harus diisi.',
            'date.required' => 'Kolom tanggal pengumuman harus diisi.',
            'date.date' => 'Kolom tanggal pengumuman harus dalam format tanggal yang benar.',
            'time.required' => 'Kolom waktu pengumuman harus diisi.',
            'thumbnail' => 'Kolom gambar wajib diisi',
            'thumbnail.max' => 'Ukuran gambar tidak boleh lebih dari 10MB'
        ]);

        $data = tb_pemberitahuan::where('tb_pemberitahuan.type', 1)
            ->findOrFail($id_pengumuman);

        if ($request->hasFile('thumbnail')) {
            // Hapus gambar sebelumnya jika ada
            if ($data->thumbnail !== null) {
                $oldImagePath = public_path('img/announcement/' . $data->thumbnail);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Simpan gambar baru
            $imageName = $request->file('thumbnail')->hashName();
            $request->file('thumbnail')->move('img/announcement', $imageName);
            $data->thumbnail = $imageName;
        }


        $data->update([
            'nama' => $request->nama,
            'target' => $request->target,
            'id_pemberitahuan_category' => $request->pengumuman_category,
            'date' => $request->date,
            'time' => $request->time,
            'text' => $request->text,
        ]);

        return redirect()->route('pengumuman.index', ['token' => $request->token])->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id_pengumuman = $request->route("pengumuman");
        $token = $request->session()->get('token') ?? $request->input('token');

       $pengumuman = tb_pemberitahuan::where(['type' => 2])->findOrFail($id_pengumuman);
       $pengumuman->delete();

       return redirect()->route('pengumuman.index', ['token' => $request->token])->with('success', 'Pengumuman berhasil dihapus.');

    }
}
