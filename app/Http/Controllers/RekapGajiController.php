<?php

namespace App\Http\Controllers;

use App\Mail\PayrollSlipMail;
use App\Models\RekapGaji;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log; // Untuk logging
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendPayrollEmail;
use App\Models\Periode;

class RekapGajiController extends Controller
{
    public function formUpload(Request $request)
    {
        $periode = Periode::findOrFail($request->periode_id);
        $rekapGaji = RekapGaji::where('periode_id', $periode->id)->get();

        return view('upload-gaji', compact('periode', 'rekapGaji'));
    }

    public function data(Request $request)
    {
        $periode = Periode::findOrFail($request->periode_id);
        $rekapGaji = RekapGaji::where('periode_id', $periode->id)->get();

        return datatables()
            ->of($rekapGaji)
            ->addIndexColumn()
            ->editColumn('gaji_pokok', function ($item) {
                return 'Rp.' . number_format($item->gaji_pokok, 0, ',', '.');
            })
            ->editColumn('lembur', function ($item) {
                return 'Rp.' . number_format($item->lembur, 0, ',', '.');
            })
            ->editColumn('uang_makan', function ($item) {
                return 'Rp.' . number_format($item->uang_makan, 0, ',', '.');
            })
            ->editColumn('bonus', function ($item) {
                return 'Rp.' . number_format($item->bonus, 0, ',', '.');
            })
            ->editColumn('total_gaji', function ($item) {
                return '<strong>Rp.' . number_format($item->total_gaji, 0, ',', '.') . '</strong>';
            })
            ->rawColumns(['total_gaji'])
            ->make(true);
    }

    public function uploadAndImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        // Cek apakah data sudah ada untuk periode ini
        $periodeId = $request->periode_id;
        $sudahAda = RekapGaji::where('periode_id', $periodeId)->exists();

        if ($sudahAda) {
            return redirect()->route('form.upload', ['periode_id' => $periodeId])
                ->with('error', 'Data rekap gaji untuk periode ini sudah diupload. Tidak bisa upload ulang.');
        }

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();


        $inserted = 0;
        $skipped = 0;

        $highestRow = $sheet->getHighestRow();

        $periodeId = $request->periode_id;

        for ($rowIndex = 2; $rowIndex <= $highestRow; $rowIndex++) {
            $nama       = trim($sheet->getCell("B$rowIndex")->getFormattedValue());
            $gaji_pokok = str_replace([',', '.'], '', $sheet->getCell("BR$rowIndex")->getFormattedValue());
            $lembur     = str_replace([',', '.'], '', $sheet->getCell("BS$rowIndex")->getFormattedValue());
            $uang_makan     = str_replace([',', '.'], '', $sheet->getCell("BT$rowIndex")->getFormattedValue());
            $bonus      = str_replace([',', '.'], '', $sheet->getCell("BV$rowIndex")->getFormattedValue());
            $total      = str_replace([',', '.', 'Rp'], '', $sheet->getCell("BP$rowIndex")->getFormattedValue());
            $email      = trim($sheet->getCell("BX$rowIndex")->getFormattedValue());
            $jumlah     = trim($sheet->getCell("BQ$rowIndex")->getFormattedValue());

            // Cek nama dan pastikan gaji pokok numerik
            if (!$nama || !is_numeric($gaji_pokok)) {
                $skipped++;
                continue;
            }

            try {
                RekapGaji::create([
                    'nama'        => $nama,
                    'gaji_pokok'  => $gaji_pokok,
                    'lembur'      => is_numeric($lembur) ? $lembur : 0,
                    'uang_makan'  => is_numeric($uang_makan) ? $uang_makan : 0,
                    'bonus'       => is_numeric($bonus) ? $bonus : 0,
                    'total_gaji'  => is_numeric($total) ? $total : 0,
                    'email'       => $email,
                    'periode_id'  => $periodeId,
                    'jumlah'      => $jumlah,
                ]);
                $inserted++;
            } catch (\Exception $e) {
                Log::error("Gagal insert baris $rowIndex: " . $e->getMessage());
                $skipped++;
            }
        }

        // return back()->with('success', "Import selesai. $inserted baris dimasukkan, $skipped baris dilewati.");
        return redirect()->route('form.upload', ['periode_id' => $periodeId])
            ->with('success', "Import selesai. $inserted baris dimasukkan, $skipped baris dilewati.");
    }


    public function sendPayrollEmails($periode_id)
    {
        $periode = Periode::findOrFail($periode_id);

        $rekap_gaji = RekapGaji::where('periode_id', $periode_id)->get();

        $queued = 0;

        foreach ($rekap_gaji as $payroll) {
            if ($payroll->email && filter_var($payroll->email, FILTER_VALIDATE_EMAIL)) {
                SendPayrollEmail::dispatch($payroll, $periode);
                $queued++;
            }
        }

        return redirect()->route('form.upload', ['periode_id' => $periode_id])
            ->with('success', "Berhasil mengantrikan {$queued} email ke sistem.");
    }
}
