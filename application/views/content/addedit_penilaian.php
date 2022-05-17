<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?= $title ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <!-- <li class="breadcrumb-item"><a href="javascript:void(0)"><?= @$folder ?></a></li>
          <li class="breadcrumb-item active"><?= @$title ?></li> -->
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <?php if ($this->session->flashdata('success')): ?>
          <?= $this->session->flashdata('success') ?>
        <?php endif ?>
        <?php if ($this->session->flashdata('error')): ?>
          <?= $this->session->flashdata('error') ?>
        <?php endif ?>
        <div id="response"></div>
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><?= $header .' '. $title ?></h3>
          </div>
          <form action="" method="post" id="form">
            <div class="card-body">
              <div class="form-group">
                <label for="penilaian">Nama Penilaian<span style="color: #dc3545; font-weight: bold;">*</span></label>
                <textarea name="penilaian" id="penilaian" class="form-control" placeholder="Nama Penilaian"><?= @$row->penilaian ?></textarea>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="program_studi_id">Program Studi<span style="color: #dc3545; font-weight: bold;">*</span></label>
                    <select name="program_studi_id" id="program_studi_id" class="form-control select2">
                      <option value="">Pilih Program Studi</option>
                      <?php foreach ($this->db->get('program_studi')->result() as $key) {
                        $selected = @$row->program_studi_id == $key->id_program_studi ? 'selected=""' : '';
                        echo '<option value="'. $key->id_program_studi .'" '. $selected .'>'. $key->program_studi .'</option>';
                      } ?>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="jenis_pengguna_id">Penilai<span style="color: #dc3545; font-weight: bold;">*</span></label>
                    <select name="jenis_pengguna_id" id="jenis_pengguna_id" class="form-control select2">
                      <option value="">Pilih Penilai</option>
                      <?php $penilai = array('3' => 'DPL', '4' => 'GPL', '1' => 'DPL & GPL');
                      foreach ($penilai as $key => $value) {
                        $selected = @$row->jenis_pengguna_id == $key ? 'selected=""' : '';
                        echo '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
                      } ?>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="skala">Skala Penilaian<span style="color: #dc3545; font-weight: bold;">*</span></label>
                    <select name="skala" id="skala" class="form-control select2">
                      <option value="">Pilih Skala Penilaian</option>
                      <?php for ($i=3; $i <= 5 ; $i++) { 
                        $selected = @$row->skala == $i ? 'selected=""' : '';
                        echo '<option value="'. $i .'" ' . $selected .'>'. $i .'</option>';
                      } ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <a href="<?= site_url('evaluation/instrument') ?>" type="button" class="btn btn-secondary btn-sm"><i class="fas fa-angle-double-left"></i> Batal</a>
              <button type="submit" id="btn-submit" class="btn btn-default btn-sm float-right"><i class="fas fa-save"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>

      <?php if (isset($row->id_penilaian)): ?>
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Daftar Komponen Penilaian</h3>
              <div class="float-right">

                <?php if ($this->db->get_where('detail_penilaian', ['penilaian_id' => $row->id_penilaian])->num_rows() > 0): ?>

                  <?php if ($detail_penilaian): ?>
                    <a href="javascript:void(0)" onclick="add_aspek();" class="btn btn-default btn-sm"><i class="fas fa-plus"></i> Tambah</a>
                    <?php else: ?>
                    <a href="javascript:void(0)" onclick="add_pernyataan();" class="btn btn-default btn-sm"><i class="fas fa-plus"></i> Tambah</a>
                  <?php endif ?>

                  <?php else: ?>
                  <button type="button" class="btn btn-default btn-sm " data-toggle="dropdown"><i class="fas fa-plus"></i> Tambah</button>
                  <ul class="dropdown-menu">
                    <li class="dropdown-item"><a href="javascript:void(0)" onclick="add_aspek();">Tambah Aspek</a></li>
                    <li class="dropdown-divider"></li>
                    <li class="dropdown-item"><a href="javascript:void(0)" onclick="add_pernyataan();">Tambah Pernyataan</a></li>
                  </ul>
                <?php endif ?>

              </div>
            </div>
            <div class="card-body table-responsive">
              <table id="table" class="table" style="width: 100%;">
                <thead>
                  <tr>
                    <?php if ($detail_penilaian): ?>
                      <th>
                        <table class="table" style="margin-bottom: 0px;">
                          <tr>
                            <th style="width: 5%; text-align: center; border-top: none; padding-top: 0px;">No</th>
                            <th style="text-align: center; border-top: none; padding-top: 0px;">Komponen<span style="color: #ffffff;">_</span>Penilaian</th>
                            <th style="width: 10%; text-align: center; border-top: none; padding-top: 0px;">Aksi</th>
                          </tr>
                        </table>
                      </th>
                    <?php else: ?>                    
                      <th style="width: 5%; text-align: center;">No</th>
                      <th style="text-align: center;">Komponen<span style="color: #ffffff;">_</span>Penilaian</th>
                      <th style="width: 10%; text-align: center;">Aksi</th>
                    <?php endif ?>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      <?php endif ?>

    </div>
  </div>
</div>

<div class="modal fade" id="modal-aspek">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close cancel" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= site_url('evaluation/save_komponen/' . @$row->id_penilaian) ?>" method="post" id="form-aspek">
        <div class="modal-body">
          <div class="form-group">
            <label for="aspek">Aspek<span style="color: #dc3545; font-weight: bold;">*</span></label>
            <input type="text" name="aspek" id="aspek" class="form-control" value="" placeholder="Aspek" autocomplete="off">
          </div>
          <input type="text" name="id_aspek" value="" style="display: none;">
          <input type="text" name="isi" value="<?= @$row->id_penilaian ?>" style="display: none;">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary btn-sm cancel" data-dismiss="modal"><i class="fas fa-angle-double-left"></i> Batal</button>
          <button type="submit" class="btn btn-default btn-sm"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-indikator">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close cancel" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= site_url('evaluation/save_komponen/' . @$row->id_penilaian) ?>" method="post" id="form-indikator">
        <div class="modal-body">
          <div class="form-group">
            <label for="indikator">Indikator<span style="color: #dc3545; font-weight: bold;">*</span></label>
            <input type="text" name="indikator" id="indikator" class="form-control" value="" placeholder="Indikator" autocomplete="off">
          </div>
          <input type="text" name="id_indikator" value="" style="display: none;">
          <input type="text" name="aspek_id" value="" style="display: none;">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary btn-sm cancel" data-dismiss="modal"><i class="fas fa-angle-double-left"></i> Batal</button>
          <button type="submit" class="btn btn-default btn-sm"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-pernyataan">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close cancel" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= site_url('evaluation/save_komponen/' . @$row->id_penilaian) ?>" method="post" id="form-pernyataan">
        <div class="modal-body">
          <div class="form-group">
            <label for="pernyataan">Pernyataan<span style="color: #dc3545; font-weight: bold;">*</span></label>
            <textarea name="pernyataan" id="pernyataan" class="form-control" placeholder="Pernyataan" autocomplete="off"></textarea>
          </div>
          <input type="text" name="id_pernyataan" value="" style="display: none;">
          <input type="text" name="indikator_id" value="" style="display: none;">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary btn-sm cancel" data-dismiss="modal"><i class="fas fa-angle-double-left"></i> Batal</button>
          <button type="submit" class="btn btn-default btn-sm"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
$(function () {
  var url_table = 'evaluation/show_komponen/<?= md5(@$row->id_penilaian) ?>';
  set_datatable(url_table);
  form_validation();
  form_aspek();
  form_indikator();
  form_pernyataan();
});

function form_validation() {
  $('#form').validate({
    rules: {
      penilaian: {
        required: true,
      },
      program_studi_id: {
        required: true,
      },
      jenis_pengguna_id: {
        required: true,
      },
      skala: {
        required: true,
      },
    },
    messages: {
      penilaian: {
        required: "Nama Penilaian harus diisi",
      },
      program_studi_id: {
        required: "Program Studi harus dipilih",
      },
      jenis_pengguna_id: {
        required: "Penilai harus dipilih",
      },
      skala: {
        required: "Skala Penilaian harus dipilih",
      },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
}

function add_aspek() {
  $('#form-aspek')[0].reset();
  $('.modal-title').text('Tambah Aspek');
  $('#modal-aspek').modal('show');
}

function edit_aspek(id) {
  var aspek = $('#aspek_'+ id +'').text();
  
  $('#form-aspek')[0].reset();
  $('.modal-title').text('Edit Aspek');
  $('#modal-aspek').modal('show');
  $('[name="id_aspek"]').val(id).change();
  $('[name="aspek"]').val(aspek).change();
}

function form_aspek() {
  var validator = $('#form-aspek').validate({
    rules: {
      aspek: {
        required: true,
      },
    },
    messages: {
      aspek: {
        required: "Aspek harus diisi",
      },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });

  $('.cancel').click(function() {
    validator.resetForm();
  });
}

function add_indikator(id) {
  $('#form-indikator')[0].reset();
  $('.modal-title').text('Tambah Indikator');
  $('#modal-indikator').modal('show');
  $('[name="aspek_id"]').val(id).change();
}

function edit_indikator(id) {
  var indikator = $('#indikator_'+ id +'').text();
  var aspek_id  = $('#aspek_id_'+ id +'').val();
  
  $('#form-indikator')[0].reset();
  $('.modal-title').text('Edit Indikator');
  $('#modal-indikator').modal('show');
  $('[name="id_indikator"]').val(id).change();
  $('[name="indikator"]').val(indikator).change();
  $('[name="aspek_id"]').val(aspek_id).change();
}

function form_indikator() {
  var validator = $('#form-indikator').validate({
    rules: {
      indikator: {
        required: true,
      },
    },
    messages: {
      indikator: {
        required: "Indikator harus diisi",
      },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });

  $('.cancel').click(function() {
    validator.resetForm();
  });
}

function add_pernyataan(id) {
  $('#form-pernyataan')[0].reset();
  $('.modal-title').text('Tambah Pernyataan');
  $('#modal-pernyataan').modal('show');
  $('[name="indikator_id"]').val(id).change();
}

function edit_pernyataan(id) {
  var pernyataan    = $('#pernyataan_'+ id +'').text();
  var indikator_id  = $('#aspek_id_'+ id +'').val();
  
  $('#form-pernyataan')[0].reset();
  $('.modal-title').text('Edit Pernyataan');
  $('#modal-pernyataan').modal('show');
  $('[name="id_pernyataan"]').val(id).change();
  $('[name="pernyataan"]').val(pernyataan).change();
  $('[name="indikator_id"]').val(indikator_id).change();
}

function form_pernyataan() {
  var validator = $('#form-pernyataan').validate({
    rules: {
      pernyataan: {
        required: true,
      },
    },
    messages: {
      pernyataan: {
        required: "Pernyataan harus diisi",
      },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });

  $('.cancel').click(function() {
    validator.resetForm();
  });
}

</script>