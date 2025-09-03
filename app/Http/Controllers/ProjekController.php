<?php

namespace App\Http\Controllers;

use App\Models\Projek;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use SweetAlert2\Laravel\Swal;
use Illuminate\Support\Carbon;
use App\Mail\ProjectReminderMail;
use Illuminate\Support\Facades\Mail;

class ProjekController extends Controller
{
    public function dashboard(){
        $title = "Dashboard";
        $semuaProjek = Projek::all();
        $projekPerTahun = [];

        // Loop dari tahun 2013 sampai 2025
        for ($tahun = 2013; $tahun <= 2025; $tahun++) {
            // Filter koleksi data untuk mendapatkan projek pada tahun tersebut
            $dataTahunIni = $semuaProjek->filter(function ($projek) use ($tahun) {
                return Carbon::parse($projek->tanggal_projek)->year === $tahun;
            })->values(); // Gunakan values() untuk mereset key array

            // Simpan data ke dalam array dengan key tahun
            $projekPerTahun[$tahun] = $dataTahunIni;
        }

        $persenAktif = 0;
        $persenKadaluwarsa = 0;
        // Mengambil jumlah keseluruhan data
        $jumlahProjek = Projek::count();

        
        // Mengambil data aktif (tanggal_projek tidak kurang dari hari ini)
        // Gunakan Carbon untuk tanggal hari ini
        $hariIni = Carbon::today();
        $projekAktif = Projek::where('kadaluwarsa_projek', '>=', $hariIni)->count();
        
        // Mengambil data kadaluwarsa (kadaluwarsa_projek sudah lewat)
        $projekKadaluwarsa = Projek::where('kadaluwarsa_projek', '<', $hariIni)->count();
        
        if ($jumlahProjek > 0) {
            $persenAktif = ($projekAktif / $jumlahProjek) * 100;
            $persenKadaluwarsa = ($projekKadaluwarsa / $jumlahProjek) * 100;
        }

        return view('admin.index', compact('title', 'jumlahProjek', 'projekAktif', 'projekKadaluwarsa', 'persenAktif', 'persenKadaluwarsa', 'projekPerTahun'));
    }
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

    return view('admin.projek', compact('title','projek', 'sortBy', 'order', 'search'));
    }

    public function formTambahProjek(){
        $title = "Projek";
        return view('admin.formTambah', compact('title'));
    }

    public function tambahProjek(Request $request){
        // $data = $request->caption;
        

        try{
            
        // dd($request->file('file_koordinat')->getMimeType());
            
            $request->validate([
                "nama_projek" => "required",
                "email_projek" => "required",
                "tanggal_mulai" => "required",
                "tanggal_akhir" => "required",                
                "file_koordinat" => "nullable|file",
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
            $projek = Projek::create([
                'nama_projek' => $request->nama_projek,
                'email' => $request->email_projek,
                'tanggal_projek' => $request->tanggal_mulai,
                'kadaluwarsa_projek' => $request->tanggal_akhir,
                'file_koordinat' => $filePath,
            ]);

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
        return view('admin.detail', compact('data', 'title'));
    }

    public function hapusProjek($id){
        Projek::destroy($id);
        Swal::error([
            'title' => 'Data berhasil dihapus.',
        ]);

         Swal::fire([
                'title' => 'Berhasil',
                'text' => 'Data berhasil dihapus.',
                'icon' => 'error',
                'timer' => 2000,
                'confirmButtonText' => 'Tutup'
        ]);
        
        // Alert::error('Berhasil!', 'Data berhasil dihapus.');
        return redirect()->route('admin.projek');
    }

    // kirim email
    public function sendMail(Request $request){
        $data = Projek::findOrFail($request->id);
        // dd($data->nama_projek);

        $pesan = "Haloo selamat siang " . $data->email . ", projek " . $data->nama_projek . " berakhir pada " . $request->tanggal_akhir . " (". $request->sisa_waktu . ")";

        Mail::to($data->email)->send(new ProjectReminderMail($pesan));
        Swal::fire([
                'title' => 'Berhasil',
                'text' => 'Email berhasil dikirim ke ' . $data->email ,
                'icon' => 'info',
                'timer' => 2000,
                'confirmButtonText' => 'Tutup'
        ]);

        return redirect()->route('admin.projek');
    }

    // GIS
    public function gis(){
        $title = "Map";
        $data = Projek::all();
        return view('admin.gis', compact('data', 'title'));
    }
}
