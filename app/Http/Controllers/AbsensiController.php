<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    function __construct()
    {
        $absensis = Absensi::get();
        foreach ($absensis as $absensi) {
            if ($absensi->time_end < date("h:i")) {
                $absen = Absensi::where('tgl_absen', '<', date("Y-m-d"));
                $absen->update([
                    'keterangan' => 'tutup',
                ]);
                $absen = Absensi::where('time_end', '<', date("h:i"));
                $absen->update([
                    'keterangan' => 'tutup',
                ]);
            }
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $absensis = DB::table('tb_absensi')
            ->orderBy('tgl_absen', 'asc')
            ->get();
        $pegawais = User::get();

        // dd(date("Y-m-d"));
        return view('admin.absensi.index', compact('absensis', 'pegawais'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tgl_absen' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'keterangan' => 'required',
        ]);

        $absen = Absensi::create([
            'id_user' => Auth::user()->id_user,
            'tgl_absen' => $request->tgl_absen,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'keterangan' => $request->keterangan,
        ]);

        if ($absen) {
            return redirect()
                ->route('absensi.index')
                ->with([
                    Alert::success('Berhasil', 'Absensi Berhasil Ditambahkan')
                ]);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    Alert::error('Gagal', 'Absensi Gagal Ditambahkan')
                ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'tgl_absen' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'keterangan' => 'required',
        ]);

        $absen = Absensi::where('id_absensi', $request->id_absensi);
        $absen->update([
            'tgl_absen' => $request->tgl_absen,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'keterangan' => $request->keterangan,
        ]);

        if ($absen) {
            return redirect()
                ->route('absensi.index')
                ->with([
                    Alert::success('Berhasil', 'Absensi Berhasil Diubah')
                ]);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    Alert::error('Gagal', 'Absensi Gagal Diubah')
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $absen = Absensi::where('id_absensi', $request->id_absensi);
        $absen->delete();

        if ($absen) {
            return redirect()
                ->route('absensi.index')
                ->with([
                    Alert::success('Berhasil', 'Absensi Berhasil Dihapus')
                ]);
        } else {
            return redirect()
                ->route('absensi.index')
                ->with([
                    Alert::error('Gagal', 'Absensi Gagal Dihapus')
                ]);
        }
    }
}
