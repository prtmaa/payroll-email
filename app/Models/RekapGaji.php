<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapGaji extends Model
{
    use HasFactory;
    protected $table = 'rekap_gaji';

    protected $fillable = [
        'nama',
        'gaji_pokok',
        'lembur',
        'bonus',
        'total_gaji',
        'email',
        'periode_id',
        'jumlah'
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
