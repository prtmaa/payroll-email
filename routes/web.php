<?php

use App\Http\Controllers\EmployeController;
use App\Http\Controllers\PayrollDailyController;
use App\Http\Controllers\PayrollsController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\RekapGajiController;
use App\Mail\PayrollSlipMail;
use App\Models\Payrolls;
use App\Models\Periode;
use App\Models\RekapGaji;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PeriodeController::class, 'index'])->name('periode.index');
Route::get('/periode/data', [PeriodeController::class, 'data'])->name('periode.data');
Route::resource('/periode', PeriodeController::class);

Route::get('/upload-gaji/data', [RekapGajiController::class, 'data'])->name('upload.data');
Route::get('/upload-gaji', [RekapGajiController::class, 'formUpload'])->name('form.upload');
Route::post('/upload-gaji', [RekapGajiController::class, 'uploadAndImport'])->name('upload.gaji');
Route::post('/send-email/{periode_id}', [RekapGajiController::class, 'sendPayrollEmails'])->name('send.email');

Route::get('/preview-email', function () {
    $payroll = RekapGaji::first();
    $periode = Periode::first();

    return new PayrollSlipMail($payroll, $periode);
});
