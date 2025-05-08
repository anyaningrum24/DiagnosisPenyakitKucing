<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use App\Models\Gejala;
use App\Models\Pasien;
use App\Models\Penyakit;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminDiagnosaController extends Controller
{
    //
    public function index()
    {
        //
        $data = [
            'title'     => 'Diagnosa Penyakit',
            'content'   => '/diagnosa/index'
        ];
        return view('admin.layouts.wrapper', $data);
    }

    function createPasien(Request $request)
    {
        $data = [
            'name'      => $request->name,
            'umur'      => $request->umur,
        ];
        $pasien = Pasien::create($data);
        session()->put('pasien_id', $pasien->id);
        return redirect('/diagnosa/pilih-gejala');
    }

    public function pilihGejala()
{
    $pasien_id = session()->get('pasien_id');

    // Ambil semua gejala
    $semuaGejala = Gejala::all();

    // Ambil gejala yang sudah dipilih
    $gejalaTerpilih = Diagnosa::wherePasienId($pasien_id)->pluck('gejala_id')->toArray();

    // Cek apakah G01 sudah dipilih
    $g01Terpilih = in_array('G01', Gejala::whereIn('id', $gejalaTerpilih)->pluck('kode_gejala')->toArray());

    if ($g01Terpilih) {
        // Ambil penyakit yang terkait dengan G01
        $penyakitTerkait = Role::whereHas('gejala', function($q) {
            $q->where('kode_gejala', 'G01');
        })->pluck('penyakit_id')->unique();

        // Ambil semua gejala yang hanya terkait dengan penyakit-penyakit itu
        $filteredGejala = Role::whereIn('penyakit_id', $penyakitTerkait)->pluck('gejala_id')->unique();
        
        // Filter gejala yang belum dipilih DAN termasuk dalam filteredGejala
        $gejalaBelumDijawab = Gejala::whereNotIn('id', $gejalaTerpilih)
            ->whereIn('id', $filteredGejala)
            ->get();
    } else {
        // Default: semua gejala yang belum dijawab
        $gejalaBelumDijawab = Gejala::whereNotIn('id', $gejalaTerpilih)->get();
    }

    $data = [
        'title'     => 'Diagnosa Penyakit',
        'pasien'    => Pasien::find($pasien_id),
        'gejala'    => $gejalaBelumDijawab,
        'gejelaTerpilih' => Diagnosa::with('gejala')->wherePasienId($pasien_id)->groupBy('gejala_id')->get(),
        'content'   => '/diagnosa/pilihgejala'
    ];
    return view('admin.layouts.wrapper', $data);
}




    function pilih()
    {
        $gejala_id = request('gejala_id');
        $cf_user = request('nilai');

        $role = Role::whereGejalaId($gejala_id)->get();
        foreach ($role as $r) {
            $data  = [
                'pasien_id' => session()->get('pasien_id'),
                'penyakit_id' => $r->penyakit_id,
                'gejala_id' => $gejala_id,
                'nilai_cf' => $cf_user,
                'cf_hasil'  => $cf_user * $r->bobot_cf
            ];
            Diagnosa::create($data);
        }
        return redirect('/diagnosa/pilih-gejala');
    }

    function hapusGejalaTerpilih()
    {
        $gejala_id = request('gejala_id');
        $pasien_id = session()->get('pasien_id');

        // dd($pasien_id);
        $diagnosa = Diagnosa::whereGejalaId($gejala_id)->wherePasienId($pasien_id)->get();
        foreach ($diagnosa as $item) {
            $d = Diagnosa::find($item->id);
            $d->delete();
        }
        return redirect('/diagnosa/pilih-gejala');
    }

    function prosesDiagnosa()
    {

        $pasien_id = session()->get('pasien_id');
        $hasil = 0;
        $penyakit_id = '';


        $role = Role::get();
        foreach ($role as $r) {
            $diagnosa = Diagnosa::wherePasienId($pasien_id)->wherePenyakitId($r->penyakit_id)->whereGejalaId($r->gejala_id)->first();

            if ($diagnosa == null) {
                $data = [
                    'pasien_id' => session()->get('pasien_id'),
                    'penyakit_id' => $r->penyakit_id,
                    'gejala_id' => $r->gejala_id,
                    'nilai_cf' => 0,
                    'cf_hasil'  => 0
                ];

                Diagnosa::create($data);
            }
        }

        $penyakit = Penyakit::get();
        foreach ($penyakit as $p) {
            $diagnosa = Diagnosa::wherePenyakitId($p->id)->wherePasienId($pasien_id)->get();
            $diagnosa_hasil = $this->hitung_cf($diagnosa);
            if ($diagnosa_hasil > $hasil) {
                $hasil = $diagnosa_hasil;
                $penyakit_id = $p->id;
            }
        }

        $pasien = Pasien::find($pasien_id);
        $pasien->akumulasi_cf = $hasil;
        $pasien->persentase  = round($hasil * 100);
        $pasien->penyakit_id = $penyakit_id;
        $pasien->save();
        return redirect('/diagnosa/keputusan/' . $pasien_id);
    }

    function hitung_cf($data)
    {
        $cf_old = 0;
        foreach ($data as $key => $value) {
            if ($key == 0) {
                $cf_old =  0;
            } else {
                $cf_old = $cf_old + $value->cf_hasil * (1 - $cf_old);
            }
        }

        return $cf_old;
    }

    public function keputusan($pasien_id)
    {
        //

        if ($pasien_id == null) {
            $pasien_id = session()->get('pasien_id');
        }
        $data = [
            'title'     => 'Hasil Diagnosa',
            'pasien'    => Pasien::with('penyakit')->find($pasien_id),
            'gejala'    => Diagnosa::with('gejala')->wherePasienId($pasien_id)->get(),
            'content'   => '/diagnosa/keputusan'
        ];
        return view('admin.layouts.wrapper', $data);
    }
}
