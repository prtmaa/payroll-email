<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;
use App\Mail\PayrollSlipMail;
use App\Models\Payrolls;
use App\Models\RekapGaji;
use Illuminate\Support\Facades\Mail;

class PeriodeController extends Controller
{
    public function index()
    {
        return view('periode.index');
    }

    public function data()
    {
        $periode = Periode::orderBy('created_at', 'desc')
            ->get();

        return datatables()
            ->of($periode)
            ->addIndexColumn()
            ->addColumn('mulai', function ($row) {
                return formatTanggalIndo($row->mulai);
            })
            ->addColumn('akhir', function ($row) {
                return formatTanggalIndo($row->akhir);
            })
            ->addColumn('aksi', function ($periode) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`' . route('periode.update', $periode->id) . '`)" class="btn btn-sm btn-info btn-flat">
                        <i class="fa fa-pen"></i> Edit
                    </button>
                    <button type="button" onclick="deleteData(`' . route('periode.destroy', $periode->id) . '`)" class="btn btn-sm btn-danger btn-flat">
                        <i class="fa fa-trash"></i> Hapus
                    </button>
                    <a href="' . route('form.upload', ['periode_id' => $periode->id]) . '" class="btn btn-sm btn-success btn-flat">
                        <i class="fa fa-plus"></i> Input Gaji
                    </a>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {

        $periode = new Periode();

        $periode->mulai = $request->awal;
        $periode->akhir = $request->akhir;

        $periode->save();

        return response()->json('Data berhasil disimpan', 200);
    }


    public function show($id)
    {
        $periode =  Periode::find($id);

        return response()->json($periode);
    }

    public function update(Request $request, $id)
    {
        $periode = Periode::find($id);

        $periode->mulai = $request->awal;
        $periode->akhir = $request->akhir;

        $periode->update();

        return response()->json('Data berhasil disimpan', 200);
    }


    public function destroy($id)
    {
        $periode = Periode::find($id);
        RekapGaji::where('periode_id', $id)->delete();
        $periode->delete();

        return response(null, 204);
    }
}
