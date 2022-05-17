<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?= $title ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <!-- <li class="breadcrumb-item"><a href="javascript:void(0)" onclick="window.history.back();"><?= @$folder ?></a></li>
          <li class="breadcrumb-item active"><?= @$title ?></li> -->
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">

      <?php 
        if ($jenis_pengguna_id == 4) {
          $id_name = 'NIP/NUPTK';
        } elseif ($jenis_pengguna_id == 5) {
          $id_name = 'NIM';
        } else {
          $id_name = 'NIDN';
        }
      ?>

      <?php if ($jenis_pengguna_id == 1): ?>

        <div class="col-md-6">
          <div id="response"></div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?= $header ?></h3>
              <div class="card-tools">
                <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-tool"><i class="fas fa-times"></i></a>
              </div>
            </div>
            <form action="" method="post" id="form">
              <div class="card-body">
                <div class="form-group" style="display: none;">
                  <input type="text" name="jenis_pengguna_id" value="<?= $jenis_pengguna_id ?>">
                  <input type="text" name="jenis_pengguna" value="<?= $jenis_pengguna ?>">
                  <input type="text" name="id_name" value="<?= $id_name ?>">
                </div>
                <div class="form-group">
                  <label for="no_induk"><?= $id_name ?></label>
                  <input type="text" class="form-control" id="no_induk" name="no_induk" placeholder="<?= $id_name ?>" autocomplete="off" value="<?= @$row->no_induk ?>">
                  <span id="error-no_induk" class="error invalid-feedback" style="display: none;"></span>
                </div>
                <div class="form-group">
                  <label for="nama_lengkap">Nama Lengkap<span style="color: #dc3545; font-weight: bold;">*</span></label>
                  <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" autocomplete="off" value="<?= @$row->nama_lengkap ?>">
                  <span id="error-nama_lengkap" class="error invalid-feedback" style="display: none;"></span>
                </div>
                <div class="form-group">
                  <label for="email">Email<span style="color: #dc3545; font-weight: bold;">*</span></label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" value="<?= @$row->email ?>">
                  <span id="error-email" class="error invalid-feedback" style="display: none;"></span>
                </div>
                <div class="form-group">
                  <label for="telepon">Telepon<span style="color: #dc3545; font-weight: bold;">*</span></label>
                  <input type="number" class="form-control" id="telepon" name="telepon" placeholder="Telepon" autocomplete="off" value="<?= @$row->telepon ?>">
                  <span id="error-telepon" class="error invalid-feedback" style="display: none;"></span>
                </div>
                <div class="form-group">
                  <label for="password1">Password</label>
                  <input type="password" class="form-control" id="password1" name="password1" placeholder="Password <?php if(empty($row->id_pengguna)) echo '(Default: Email)' ?>">
                </div>
                <div class="form-group">
                  <label for="password2">Konformasi Password</label>
                  <input type="password" class="form-control" id="password2" name="password2" placeholder="Konfirmasi Password (Ulangi)">
                </div>
              </div>
              <div class="card-footer">
                <a href="javascript:void(0)" onclick="window.history.back();" type="button" class="btn btn-secondary btn-sm"><i class="fas fa-angle-double-left"></i> Batal</a>
                <button type="submit" id="btn-submit" class="btn btn-default btn-sm float-right"><i class="fas fa-save"></i> Simpan</button>
              </div>
            </form>
          </div>
        </div>

      <?php else: ?>

        <div class="col-md-12">
          <div id="response"></div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?= $header ?></h3>
              <div class="card-tools">
                <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-tool"><i class="fas fa-times"></i></a>
              </div>
            </div>
            <form action="" method="post" id="form">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" style="display: none;">
                      <input type="text" name="jenis_pengguna_id" value="<?= $jenis_pengguna_id ?>">
                      <input type="text" name="jenis_pengguna" value="<?= $jenis_pengguna ?>">
                      <input type="text" name="id_name" value="<?= $id_name ?>">
                    </div>

                    <div class="form-group">
                      <label for="no_induk"><?= $id_name ?><span style="color: #dc3545; font-weight: bold;">*</span></label>
                      <input type="text" class="form-control" id="no_induk" name="no_induk" placeholder="<?= $id_name ?>" autocomplete="off" value="<?= @$row->no_induk ?>">
                      <span id="error-no_induk" class="error invalid-feedback" style="display: none;"></span>
                    </div>
                    <div class="form-group">
                      <label for="nama_lengkap">Nama Lengkap<span style="color: #dc3545; font-weight: bold;">*</span></label>
                      <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" autocomplete="off" value="<?= @$row->nama_lengkap ?>">
                      <span id="error-nama_lengkap" class="error invalid-feedback" style="display: none;"></span>
                    </div>
                    <div class="form-group">
                      <label for="jenis_kelamin">Jenis Kelamin<span style="color: #dc3545; font-weight: bold;">*</span></label>
                      <select name="jenis_kelamin" id="jenis_kelamin" class="form-control select2">
                        <option value="">Pilih Jenis Kelamin</option>
                        <?php foreach (array('L' => 'Laki-Laki', 'P' => 'Perempuan') as $key => $value): ?>
                          <option value="<?= $key ?>" <?php if(@$row->jenis_kelamin == $key) echo 'selected=""' ?>><?= $value ?></option>
                        <?php endforeach ?>
                      </select>
                      <span id="error-jenis_kelamin" class="error invalid-feedback" style="display: none;"></span>
                    </div>
                    <?php if ($jenis_pengguna_id == 3): ?>
                      <div class="form-group">
                        <label for="program_studi_id">Program Studi<span style="color: #dc3545; font-weight: bold;">*</span></label>
                        <select name="program_studi_id" id="program_studi_id" class="form-control select2">
                          <option value="">Pilih Program Studi</option>
                          <?php foreach ($this->prodi->getData() as $key): ?>
                            <option value="<?= $key->id_program_studi ?>" <?php if(@$row->program_studi_id == $key->id_program_studi) echo 'selected=""' ?>><?= $key->program_studi ?></option>
                          <?php endforeach ?>
                        </select>
                        <span id="error-program_studi_id" class="error invalid-feedback" style="display: none;"></span>
                      </div>
                    <?php elseif ($jenis_pengguna_id == 4): ?>
                      <div class="form-group">
                        <label for="sekolah_mitra_id">Sekolah Mitra<span style="color: #dc3545; font-weight: bold;">*</span></label>
                        <select name="sekolah_mitra_id" id="sekolah_mitra_id" class="form-control select2">
                          <option value="">Pilih Sekolah Mitra</option>
                          <?php foreach ($this->sekolah->getData() as $key): ?>
                            <option value="<?= $key->id_sekolah_mitra ?>" <?php if(@$row->sekolah_mitra_id == $key->id_sekolah_mitra) echo 'selected=""' ?>><?= $key->nama_sekolah ?></option>
                          <?php endforeach ?>
                        </select>
                        <span id="error-sekolah_mitra_id" class="error invalid-feedback" style="display: none;"></span>
                      </div>
                    <?php else: ?>
                      <?php if (@$row->id_pengguna): ?>
                        <div class="form-group">
                          <label for="program_studi_id">Program Studi<span style="color: #dc3545; font-weight: bold;">*</span></label>
                          <select name="program_studi_id" id="program_studi_id" class="form-control select2">
                            <option value="">Pilih Program Studi</option>
                            <?php foreach ($this->prodi->getData() as $key): ?>
                              <option value="<?= $key->id_program_studi ?>" <?php if(@$row->program_studi_id == $key->id_program_studi) echo 'selected=""' ?>><?= $key->program_studi ?></option>
                            <?php endforeach ?>
                          </select>
                          <span id="error-program_studi_id" class="error invalid-feedback" style="display: none;"></span>
                        </div>
                        <div class="form-group">
                          <label for="angkatan">Angkatan<span style="color: #dc3545; font-weight: bold;">*</span></label>
                          <select name="angkatan" id="angkatan" class="form-control select2">
                            <option value="">Pilih Angkatan</option>
                            <?php for ($i = 2017; $i <= date('Y'); $i++): ?>
                              <option value="<?= $i ?>" <?php if(@$row->angkatan == $i) echo 'selected=""' ?>><?= $i ?></option>
                            <?php endfor ?>
                          </select>
                          <span id="error-angkatan" class="error invalid-feedback" style="display: none;"></span>
                        </div>
                      <?php else: ?>
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="program_studi_id">Program Studi<span style="color: #dc3545; font-weight: bold;">*</span></label>
                              <select name="program_studi_id" id="program_studi_id" class="form-control select2">
                                <option value="">Pilih Program Studi</option>
                                <?php foreach ($this->prodi->getData() as $key): ?>
                                  <option value="<?= $key->id_program_studi ?>" <?php if(@$row->program_studi_id == $key->id_program_studi) echo 'selected=""' ?>><?= $key->program_studi ?></option>
                                <?php endforeach ?>
                              </select>
                              <span id="error-program_studi_id" class="error invalid-feedback" style="display: none;"></span>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="angkatan">Angkatan<span style="color: #dc3545; font-weight: bold;">*</span></label>
                              <select name="angkatan" id="angkatan" class="form-control select2">
                                <option value="">Pilih Angkatan</option>
                                <?php for ($i = 2017; $i <= date('Y'); $i++): ?>
                                  <option value="<?= $i ?>" <?php if(@$row->angkatan == $i) echo 'selected=""' ?>><?= $i ?></option>
                                <?php endfor ?>
                              </select>
                              <span id="error-angkatan" class="error invalid-feedback" style="display: none;"></span>
                            </div>
                          </div>
                        </div>
                      <?php endif ?>
                    <?php endif ?>
                  </div>
                  <div class="col-md-6">
                    <?php if (@$row->jenis_pengguna_id == 5 && @$row->id_pengguna): ?>
                      <div class="form-group">
                        <label for="status_pendaftaran">Status Mahasiswa<span style="color: #dc3545; font-weight: bold;">*</span></label>
                        <select name="status_pendaftaran" id="status_pendaftaran" class="form-control select2">
                          <option value="">Pilih Status Mahasiswa</option>
                          <?php foreach (['1' => 'Aktif', '2' => 'Tidak Aktif'] as $key => $value): ?>
                            <option value="<?= $key ?>" <?php if($key == @$status_pendaftaran) echo 'selected=""' ?>><?= $value ?></option>
                          <?php endforeach ?>
                        </select>
                        <span id="error-program_studi_id" class="error invalid-feedback" style="display: none;"></span>
                      </div>
                    <?php endif ?>
                    <div class="form-group">
                      <label for="email">Email<span style="color: #dc3545; font-weight: bold;">*</span></label>
                      <input type="text" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" value="<?= @$row->email ?>">
                      <span id="error-email" class="error invalid-feedback" style="display: none;"></span>
                    </div>
                    <div class="form-group">
                      <label for="telepon">Telepon<span style="color: #dc3545; font-weight: bold;">*</span></label>
                      <input type="number" class="form-control" id="telepon" name="telepon" placeholder="Telepon" autocomplete="off" value="<?= @$row->telepon ?>">
                      <span id="error-telepon" class="error invalid-feedback" style="display: none;"></span>
                    </div>

                    <div class="form-group">
                      <label for="password1">Password</label>
                      <input type="password" class="form-control" id="password1" name="password1" placeholder="Password <?php if(empty($row->id_pengguna)) echo '(Default: Email)' ?>">
                    </div>
                    <div class="form-group">
                      <label for="password2">Konformasi Password</label>
                      <input type="password" class="form-control" id="password2" name="password2" placeholder="Konfirmasi Password (Ulangi)">
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <a href="javascript:void(0)" onclick="window.history.back();" type="button" class="btn btn-secondary btn-sm"><i class="fas fa-angle-double-left"></i> Batal</a>
                <button type="submit" id="btn-submit" class="btn btn-default btn-sm float-right"><i class="fas fa-save"></i> Simpan</button>
              </div>
            </form>
          </div>
        </div>

      <?php endif ?>

    </div>
  </div>
</div>

<script type="text/javascript">

$(function () {

  $.validator.setDefaults({
    submitHandler: function () {
      save_data();
    }
  });

  $('#form').validate({
    rules: {
      no_induk: {
        required: <?= $jenis_pengguna_id != 1 ? 'true' : 'false' ?>,
        number: <?= $jenis_pengguna_id != 5 ? 'true' : 'false' ?>,

        <?php if ($jenis_pengguna_id != 5): ?>
          min: 0,
        <?php endif ?>
      },
      nama_lengkap: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      telepon: {
        required: true,
        number: true,
        minlength: 11,
      },

      <?php if ($jenis_pengguna_id != 1): ?>
        jenis_kelamin: {
          required: true,
        },
      <?php endif ?>

      <?php if ($jenis_pengguna_id != 1 && $jenis_pengguna_id != 4): ?>
        program_studi_id: {
          required: true,
        },
      <?php endif ?>

      <?php if ($jenis_pengguna_id == 4): ?>
        sekolah_mitra_id: {
          required: true,
        },
      <?php endif ?>

      <?php if ($jenis_pengguna_id == 5): ?>
        angkatan: {
          required: true,
        },
        status_pendaftaran: {
          required: true,
        },
      <?php endif ?>

    },
    messages: {
      no_induk: {
        required: "<?= $id_name ?> harus diisi",
        number: "<?= $id_name ?> tidak valid",
        min: "<?= $id_name ?> minimal angka 0",
      },
      nama_lengkap: {
        required: "Nama Lengkap harus diisi",
      },
      email: {
        required: "Email harus diisi",
        email: "Email tidak valid"
      },
      telepon: {
        required: "Telepon harus diisi",
        number: "Telepon tidak valid",
        minlength: "Telepon minimal 11 angka",
      },
      jenis_kelamin: {
        required: "Jenis Kelamin harus dipilih",
      },
      program_studi_id: {
        required: "Program Studi harus dipilih",
      },
      sekolah_mitra_id: {
        required: "Sekolah Mitra harus dipilih",
      },
      angkatan: {
        required: "Angkatan harus dipilih",
      },
      status_pendaftaran: {
        required: "Status Mahasiswa harus dipilih",
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

});

function save_data() {
  $.ajax({
      url: "<?= site_url('user/savePengguna/' . md5(@$row->id_pengguna)) ?>",
      type: "POST",
      data: new FormData($('#form')[0]),
      contentType: false,
      processData: false,
      dataType: "JSON",
      success: function(response) {
        if (response.status) {
          $('#btn-submit').attr('disabled', true);
          set_flashdata(response.message);
          setTimeout(function() {
            $('#btn-submit').removeAttr('disabled');
            window.history.back();
          }, 3525);
        } else {

          $.each(response.errors, function (key, val) {
              $('[name="' + key + '"]').addClass('is-invalid');
              $('#error-'+ key +'').text(val).show();

              if (val === '') {
                  $('[name="' + key + '"]').removeClass('is-invalid');
                  $('#error-'+ key +'').text('').hide();
              }

              $('[name="' + key + '"]').keyup(function() {
                $('[name="' + key + '"]').removeClass('is-invalid');
                $('#error-'+ key +'').text('').hide();
              });
          });

        }
      }
  });
}

</script>

<style type="text/css">
  .select2-selection, .select2-selection--single {
      border: 1px solid #dc3545;
  }
</style>