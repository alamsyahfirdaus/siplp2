<div class="content-wrapper">
  <div class="content-header">
    <div class="container">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><?= @$folder ? $folder : $title ?></h1>
        </div>
        <div class="col-sm-6">
          <!-- <ol class="breadcrumb float-sm-right">
             <li class="breadcrumb-item"><a href="<?= site_url() ?>"><?= @$folder ?></a></li>
            <li class="breadcrumb-item active"><?= @$title ?></li>
          </ol> -->
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container">
      <div class="row">

        <div class="col-md-12 col-xs-12">
          <?php if ($this->session->flashdata('success')): ?>
            <?= $this->session->flashdata('success') ?>
          <?php endif ?>
          <div id="response"></div>
        </div>

        <?php if ($this->session->id_jenis_pengguna  == 5): ?>

          <div class="col-lg-8">

            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Daftar Kegiatan</h5>
              </div>
              <div class="card-body table-responsive">
                <table id="table" class="table" style="width: 100%;">
                  <thead>
                    <tr>
                      <th style="width: 5%; text-align: center;">No</th>
                      <th style="width: 15%; text-align: left;">Tanggal</th>
                      <th style="text-align: left;">Kegiatan</th>
                      <th style="width: 25%; text-align: left;">Dokumentasi</th>
                      <th style="width: 5%; text-align: center;" id="aksi">Aksi</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
          <div class="col-lg-4">

            <div class="card">
              <div class="card-header">
                <h5 class="card-title" id="title-kegiatan_mahasiswa"><?= @$kegiatan_mahasiswa->id_kegiatan_mahasiswa ? 'Edit Kegiatan' : 'Tambah Kegiatan'; ?></h5>
                <?php if (@$id_kegiatan_mahasiswa): ?>
                  <div class="card-tools">
                    <a href="javascript:void(0)" class="btn btn-tool btn-refresh"><i class="fas fa-sync"></i></a>
                  </div>
                <?php endif ?>
              </div>
              <div class="card-body">
                <form action="" method="post" id="form-kegiatan_mahasiswa" enctype="multipart/form-data">
                  <div class="form-group" style="display: none;">
                    <input type="text" name="id_kegiatan_mahasiswa" value="<?= @$id_kegiatan_mahasiswa ?>">
                    <input type="text" class="form-control" name="dokumentasi" value="<?= @$kegiatan_mahasiswa->dokumentasi ?>">
                    <input type="file" name="foto" value="">
                  </div>
                  <div class="form-group">
                    <label for="tanggal"><span id="label-tanggal">Tanggal</span><span style="color: #dc3545; font-weight: bold;">*</span></label>
                    <select name="tanggal" id="tanggal" class="form-control select2">
                      <option value="">Tanggal</option>
                      <?php foreach ($tanggal as $key => $value) {
                        $selected = $key == @$kegiatan_mahasiswa->tanggal ? 'selected=""' : '';
                        echo '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
                      } ?>
                    </select>
                    <span id="error-tanggal" class="error invalid-feedback" style="display: none;"></span>
                  </div>
                  <div class="form-group">
                    <label for="kegiatan"><span id="label-kegiatan">Kegiatan</span><span style="color: #dc3545; font-weight: bold;">*</span></label>
                    <textarea name="kegiatan" id="kegiatan" rows="10" class="form-control" maxlength="250" placeholder="Catatan Kegiatan"><?= @$kegiatan_mahasiswa->kegiatan ?></textarea>
                    <span id="error-kegiatan" class="error invalid-feedback" style="display: none;"></span>
                  </div>
                  <div class="form-group">
                    <label for="dokumentasi"><span id="label-dokumentasi">Dokumentasi</span><span style="color: #dc3545; font-weight: bold;">*</span></label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="dokumentasi" placeholder="Foto Kegiatan" disabled="">
                      <span class="input-group-append">
                        <button type="button" class="btn btn-default" id="btn-dokumentasi"><i class="fas fa-image"></i> </button>
                      </span>
                    </div>
                    <span id="error-dokumentasi" class="error invalid-feedback" style="display: none;"></span>
                  </div>
                  <div class="form-group" id="row-dokumentasi" style="display: none;">
                    <img src="" id="preview-dokumentasi" style="width: 100%; max-height: 325px;">
                  </div>
                  <div class="form-group">
                    <button type="button" id="btn-kegiatan_mahasiswa" class="btn btn-default btn-sm float-right"><i class="fas fa-save"></i> Simpan</button>
                  </div>
                </form>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Resume Pembekalan</h5>
                <div class="card-tools">
                  <?php if (!$id_mahasiswa): ?>
                      <a href="javascript:void(0)" class="btn btn-tool" onclick="edit_resume_pembekalan();"><i class="fas fa-edit"></i></a>
                  <?php else: ?>
                    <a href="javascript:void(0)" class="btn btn-tool btn-refresh"><i class="fas fa-sync"></i></a>
                  <?php endif ?>
                </div>
              </div>
              <div class="card-body">
                <form action="" method="post" id="form-resume_pembekalan">
                  <input type="text" name="id_mahasiswa" value="" style="display: none;">
                  <?php if (isset($id_mahasiswa)): ?>
                    <div class="form-group">
                      <textarea name="resume_pembekalan" id="resume_pembekalan" rows="10" class="form-control" maxlength="250"><?= @$row->resume_pembekalan ?></textarea>
                       <span id="error-resume_pembekalan" class="error invalid-feedback" style="display: none;"></span>
                    </div>
                    <div class="form-group">
                      <button type="button" id="btn-resume_pembekalan" class="btn btn-default btn-sm float-right"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                  <?php else: ?>
                    <div class="form-group"><textarea name="" id="" rows="10" class="form-control" disabled=""><?= @$row->resume_pembekalan ?></textarea></div>
                  <?php endif ?>
                </form>
              </div>
            </div>
          </div>
          <script type="text/javascript">

            $(function() {
              $('#btn-resume_pembekalan').click(function() {
                var resume_pembekalan = $('[name="resume_pembekalan"]').val();
                if (resume_pembekalan) {
                  $('#form-resume_pembekalan').attr('action', '<?= site_url('student/resume_pembekalan/' . md5(@$row->id_pengguna)) ?>');
                  $('#form-resume_pembekalan').submit();
                } else {
                  $('[name="resume_pembekalan"]').addClass('is-invalid');
                  $('#error-resume_pembekalan').text('Resume Pembekalan harus diisi').show();
                  $('[name="resume_pembekalan"]').keyup(function() {
                    if ($(this).val()) {
                      $('[name="resume_pembekalan"]').removeClass('is-invalid');
                      $('#error-resume_pembekalan').text('').hide();
                    }
                  });
                }
              });

              table = $('#table').DataTable({
                "processing": false,
                "serverSide": true,
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": false,
                "language": { 
                  "infoFiltered": "",
                  "sZeroRecords": "",
                  "sEmptyTable": "",
                  "sSearch": "Cari:"
                },
                "ajax": {
                  "url": "<?= site_url('student/show_kegiatan/' . md5(@$row->id_pengguna)) ?>",
                  "type": "POST",
                  "data": function(data) {
                    data.id_kegiatan_mahasiswa = $('[name="id_kegiatan_mahasiswa"]').val();
                  },
                },
                "columnDefs": [{ 
                  "targets": [0],
                  "orderable": false,
                }],
              });

              $('.btn-refresh').click(function() {
                window.location.href = '<?= site_url('student') ?>';
              });

              $('#btn-dokumentasi').click(function() {
                $('[name="foto"]').click();
              });

              $('[name="foto"]').change(function() {
                if ($(this).val()) {
                  var dokumentasi = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
                  $('#dokumentasi').val(dokumentasi).change();
                  $('[name="dokumentasi"]').val(dokumentasi).change();
                  $('#row-dokumentasi').show();
                  if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                      $('#preview-dokumentasi').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                  }
                }
              });

              $('#btn-kegiatan_mahasiswa').click(function() {
                var errors = [];
                $('#form-kegiatan_mahasiswa .form-control').each(function() {
                  var field = $(this).attr('name');
                  if (field) {
                    if (!$('[name="'+ field +'"]').val()) {
                      errors.push(field);
                      $('#'+ field +'').addClass('is-invalid');
                      $('#error-'+ field +'').text($('#label-'+ field +'').text() + ' harus diisi').show();

                      $('#'+ field +'').change(function() {
                        if ($(this).val()) {
                          $('#'+ field +'').removeClass('is-invalid');
                          $('#error-'+ field +'').text('').hide();
                        }
                      });

                      $('#'+ field +'').keyup(function() {
                        if ($(this).val()) {
                          $('#'+ field +'').removeClass('is-invalid');
                          $('#error-'+ field +'').text('').hide();
                        }
                      });
                    }
                  }
                });

                if (errors.length < 1) {
                  $('#form-kegiatan_mahasiswa').attr('action', '<?= site_url('student/save_kegiatan/' . md5(@$row->id_pengguna)) ?>');
                  $('#form-kegiatan_mahasiswa').submit();
                }

              });

            });

            function edit_resume_pembekalan() {
              $('[name="id_mahasiswa"]').val('<?= md5(@$row->id_mahasiswa) ?>').change();
              $('#form-resume_pembekalan').submit();
            }
            function edit_kegiatan(id) {
              $('[name="id_kegiatan_mahasiswa"]').val(id).change();
              $('#form-kegiatan_mahasiswa').submit();
            }
          </script>
        <?php else: ?>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title"><?= @$title .' '. @$folder ?></h5>
                <div class="card-tools">
                  <a href="<?= site_url('teacher') ?>" class="btn btn-tool"><i class="fas fa-times"></i></a>
                </div>
              </div>
              <div class="card-body" style="padding-bottom: 0px;">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Pilih Mahasiswa</label>
                      <select name="pengguna_id" id="pengguna_id" class="form-control select2">
                        <option value="">Mahasiswa</option>
                        <?php foreach ($kelompok_mahasiswa as $key) {
                          echo '<option value="'. md5($key->id_pengguna) .'">'. $key->no_induk .' - '. $key->nama_lengkap .'</option>';
                        } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <table class="table table-responsive" style="width: 100%;">
                      <tr>
                        <td style="width: 45%; padding-top: 0px; border: none; font-weight: bold;">NIM</td>
                        <td style="width: 5%; padding-top: 0px; border: none;">:</td>
                        <td style="padding-top: 0px; border: none;"><?= @$nim ?></td>
                      </tr>
                      <tr>
                        <td style="border: none; font-weight: bold;">Nama<span style="color: #ffffff;">_</span>Mahasiswa</td>
                        <td style="border: none;">:</td>
                        <td style="border: none;"><?= @$nama_mahasiswa ?></td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card-body table-responsive" style="border-top: 1px solid #DEE2E6;">
                <table id="table" class="table" style="width: 100%;">
                  <thead>
                    <tr>
                      <th style="width: 5%; text-align: center;">No</th>
                      <th style="width: 15%; text-align: left;">Tanggal</th>
                      <th style="text-align: left;">Kegiatan</th>
                      <th style="width: 30%; text-align: left;">Dokumentasi</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            <script type="text/javascript">
              $(function () {
                $('[name="pengguna_id"]').change(function() {
                  if ($(this).val()) {
                    window.location.href = '<?= site_url('teacher/activity/') ?>' + $(this).val();
                  } 
                });
                var url_table = 'teacher/show_activity/<?= $pengguna_id ?>';
                set_datatable(url_table);
              });
            </script>
          </div>
        <?php endif ?>

      </div>
    </div>
  </div>

</div>

