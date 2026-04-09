@component('mail::message')
    # Slip Gaji Periode {{ formatTanggalIndo($periode->mulai) }} s/d {{ formatTanggalIndo($periode->akhir) }}

    Halo {{ $payroll->nama }},

    Berikut rincian gaji Anda:

    - jumlah Hari Kerja: {{ $payroll->jumlah }} hari
    - Gaji Pokok: Rp.{{ number_format($payroll->gaji_pokok, 0, ',', '.') }}
    - Lembur: Rp.{{ number_format($payroll->lembur, 0, ',', '.') }}
    - Uang Makan: Rp.{{ number_format($payroll->uang_makan, 0, ',', '.') }}
    - Insentif : Rp.{{ number_format($payroll->bonus, 0, ',', '.') }}
    - **Total Gaji**: Rp.{{ number_format($payroll->total_gaji, 0, ',', '.') }}

    Terima kasih.
@endcomponent
