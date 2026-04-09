@extends('layouts.master')

@section('tittle')
@endsection


@section('content')
    <div class="container-fluid">

        <div class="row">

            <section class="col-lg-12 connectedSortable">

                <div class="row">
                    <div class="col-md-12">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-check"></i>
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-ban"></i>
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-header">
                                <h4>Upload Rekap Gaji dari Excel</h4>
                            </div>

                            <div class="card-body table-responsive">

                                <form action="{{ route('upload.gaji') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="periode_id" value="{{ $periode->id }}">

                                    <label for="file">Pilih file Excel (.xlsx):</label><br>

                                    <div class="custom-file col-md-6">
                                        <input type="file" class="custom-file-input" id="customFile" name="file"
                                            accept=".xlsx">
                                        <label class="custom-file-label" for="customFile">Pilih file</label>
                                    </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-upload"></i>
                                    Upload dan Import</button>
                            </div>
                            </form>
                        </div>

                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-3">
                                <h4>Data Gaji Periode: {{ formatTanggalIndo($periode->mulai) }} s/d
                                    {{ formatTanggalIndo($periode->akhir) }} </h4>
                                <div class="btn-group ml-auto">
                                    {{-- <a href="{{ route('send.email', ['periode_id' => $periode->id]) }}"
                                        class="btn btn-primary btn-sm"
                                        onclick="return confirm('Kirim slip gaji ke semua karyawan pada periode ini?')">
                                        <i class="fas fa-paper-plane"></i> Kirim Email

                                    </a> --}}
                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                                        onclick="sendEmail('{{ route('send.email', ['periode_id' => $periode->id]) }}')">
                                        <i class="fas fa-paper-plane"></i> Kirim Email
                                    </a>


                                </div>
                            </div>
                            <div class="card-body">

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Jumlah Hari</th>
                                            <th>Gaji Pokok</th>
                                            <th>Lembur</th>
                                            <th>Uang Makan</th>
                                            <th>Insentif</th>
                                            <th>Total</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>

        </div>

        </section>
    </div>
@endsection

@push('js')
    <script>
        let table;
        $(function() {
            table = $('.table').DataTable({
                processing: true,
                autoWidth: false,
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                paging: true,
                lengthChange: false,
                "language": {
                    "sProcessing": "Sedang memproses...",
                    "sLengthMenu": "Tampilkan _MENU_ entri",
                    "sZeroRecords": "Tidak ditemukan data yang sesuai",
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "sSearch": "Pencarian:",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sPrevious": "Sebelumnya",
                        "sNext": "Selanjutnya",
                        "sLast": "Terakhir"
                    },
                },
                ajax: {
                    url: '{{ route('upload.data') }}',
                    data: {
                        periode_id: '{{ $periode->id }}'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'gaji_pokok'
                    },
                    {
                        data: 'lembur'
                    },
                    {
                        data: 'uang_makan'
                    },
                    {
                        data: 'bonus'
                    },
                    {
                        data: 'total_gaji'
                    },
                    {
                        data: 'email'
                    }
                ]

            });
        });

        $(document).ready(function() {
            bsCustomFileInput.init();
        });

        function sendEmail(url) {
            Swal.fire({
                title: 'Kirim Email?',
                text: "Slip gaji akan dikirim ke semua karyawan pada periode ini.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, kirim!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                            '_token': $('[name=csrf-token]').attr('content')
                        })
                        .done((response) => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Email berhasil dikirim.'
                            });

                            if (typeof table !== 'undefined') {
                                table.ajax.reload();
                            }
                        })
                        .fail((xhr) => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan saat mengirim email.'
                            });
                        });
                }
            });
        }
    </script>
@endpush
