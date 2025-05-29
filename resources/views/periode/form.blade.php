    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="modal-form" style="overflow:hidden;" role="dialog"
        aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

            <form action="" method="post" enctype="multipart/form-data" data-toggle="validator"
                class="form-horizontal">
                @csrf
                @method('post')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group row">
                            <label for="awal" class="col-md-2 col-md-offset-1 control-label">Awal</label>
                            <div class="col-md 6">
                                <input type="text" name="awal" id="awal" class="form-control awal" required
                                    autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="akhir" class="col-md-2 col-md-offset-1 control-label">Akhir</label>
                            <div class="col-md 6">
                                <input type="text" name="akhir" id="akhir" class="form-control akhir" required
                                    autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i
                                class="fa fa-xmark"></i> Batal</button>
                        <button class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
