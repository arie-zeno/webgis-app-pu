<?php

namespace App\Http\Controllers;

use App\Models\Projek;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use SweetAlert2\Laravel\Swal;

class ProjekController2 extends Controller
{
    public function index(Request $request ){

        $title = "Projek";

        // daftar kolom yang boleh dipakai untuk sort
        $allowedSorts = ['created_at', 'nama_projek', 'updated_at', 'tanggal_projek', 'kadaluwarsa_projek'];
        $allowedOrders = ['asc', 'desc'];

        $sortBy = $request->get('sort_by', 'created_at');
        $order = $request->get('order', 'desc');
        $search = $request->get('search');

        // validasi agar hanya kolom & order yang ada di whitelist
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        if (!in_array($order, $allowedOrders)) {
            $order = 'desc';
        }

        $projek = Projek::when($search, function ($query, $search) {
                return $query->where('nama_projek', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy($sortBy, $order)
            ->paginate(10)->withQueryString();

        $projek->appends($request->all());

        return view('admin.projek2', compact('title','projek', 'sortBy', 'order', 'search'));
    }

    public function formTambahProjek(){
        $title = "Projek";
        return view('admin.formTambah2', compact('title'));
    }

    public function tambahProjek(Request $request){
        // $data = $request->caption;
        // $line = json_decode($request->line, true);       // array koordinat polyline
        // $markers = json_decode($request->markers, true);
        // dd([$line, $markers]);
        

        try{
            
        // dd($request->file('file_koordinat')->getMimeType());
            
            $request->validate([
                "nama_projek" => "required",
                "email_projek" => "required",
                "tanggal_mulai" => "required",
                "tanggal_akhir" => "required",                
                // "file_koordinat" => "nullable|file",
            ]);

            if ($request->hasFile('file_koordinat')) {
                $ext = strtolower($request->file('file_koordinat')->getClientOriginalExtension());
                if ($ext != 'kml') {
                    Swal::fire([
                        'title' => 'Gagal',
                        'text' => 'Terjadi kesalahan : File yang diupload harus berkestensi .kml',
                        'icon' => 'error',
                        'timer' => 5000,
                        'confirmButtonText' => 'Tutup'
                    ]);
                    return back();
                }
            }
            
            // return $request;
            
            $filePath = null;
            if ($request->hasFile('file_koordinat')) {
                $file = $request->file('file_koordinat');
                $filename = uniqid() . '_' . $request->nama_projek . '.kml';
                $filePath = $file->storeAs('uploads_kml', $filename, 'public');
            // otomatis ke storage/app/public/uploads
            }

            $tanggal_akhir = Carbon::parse($request->tanggal_mulai)->copy()->addYears(5);
            // return $tanggal_akhir;
            if($request->hasFile('file_koordinat')){
                $projek = Projek::create([
                    'nama_projek' => $request->nama_projek,
                    'email' => $request->email_projek,
                    'tanggal_projek' => $request->tanggal_mulai,
                    'kadaluwarsa_projek' => $request->tanggal_akhir,
                    'file_koordinat' => $filePath,
                ]);
            }else{

                $projek = Projek::create([
                    'nama_projek' => $request->nama_projek,
                    'email' => $request->email_projek,
                    'tanggal_projek' => $request->tanggal_mulai,
                    'kadaluwarsa_projek' => $request->tanggal_akhir,
                    'file_koordinat' => null,
                    'nama_jalur' => $request->nama_jalur,
                    'line' => $request->line,         // langsung simpan JSON string
                    'markers' => $request->markers,
                ]);
            }

            if ($request->hasFile('gambars')) {
                foreach ($request->file('gambars') as $index => $file) {
                    if($file){
                        $filename = $request->nama_projek . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('dokumentasi', $filename , 'public');
    
                        $projek->dokumentasi()->create([
                            'gambar' => $path,
                            'caption' => $request->caption[$index] ?? '',
                        ]);

                        // Dokumentasi::create([
                        //     'projek_id' => $request->id,
                        //     'gambar' => $path,
                        //     'caption' => $request->caption[$index] ?? '',
                        // ]);
                    }
                }
            }

            Swal::fire([
                'title' => 'Berhasil',
                'text' => 'Data berhasil ditambahkan.',
                'icon' => 'success',
                'timer' => 2000,
                'confirmButtonText' => 'Tutup'
            ]);
            return redirect()->route('admin.projek');

        }catch (\Exception $e) {
        // tampilkan alert gagal
            Swal::fire([
                'title' => 'Gagal',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'icon' => 'error',
                'timer' => 5000,
                'confirmButtonText' => 'Tutup'
            ]);
            return redirect()->back();
        }
    }

    public function detailProjek($id){
        $title = "Projek";
        $data = Projek::findOrFail($id);
        return view('admin.detail2', compact('data', 'title'));
    }
}
