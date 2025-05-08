<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $beritas = Berita::all();
        return view('pages.berita.index', compact('beritas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
            'tanggal_publish' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('berita', 'public');
        }
    
        Berita::create($validated);
    
        return redirect()->route('berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Berita $berita)
    {
        return view('pages.berita.edit', compact('berita'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi data yang dikirim
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
            'tanggal_publish' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
    
        // Cari berita berdasarkan ID
        $berita = Berita::find($id);
    
        if (!$berita) {
            return redirect()->route('berita.index')->with('error', 'Berita tidak ditemukan');
        }
    
        // Update data berita
        $berita->judul = $validated['judul'];
        $berita->konten = $validated['konten'];
        $berita->tanggal_publish = $validated['tanggal_publish'];
    
        // Jika ada foto baru, hapus foto lama dan upload yang baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($berita->foto && Storage::disk('public')->exists($berita->foto)) {
                Storage::disk('public')->delete($berita->foto);
            }
    
            // Upload foto baru
            $fotoPath = $request->file('foto')->store('berita_foto', 'public');
            $berita->foto = $fotoPath;
        }
    
        $berita->save();
    
        return redirect()->route('berita.index')->with('success', 'Berita berhasil diperbarui');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $berita = Berita::find($id);
    
        if ($berita) {
            if ($berita->foto && Storage::disk('public')->exists($berita->foto)) {
                Storage::disk('public')->delete($berita->foto);
            }
    
            $berita->delete();
    
            return redirect()->route('berita.index')->with('success', 'Berita berhasil dihapus');
        }
    
        return redirect()->route('berita.index')->with('error', 'Berita tidak ditemukan');
    }
    
}
