<div class="content-wrapper">
  <div class="content-header">
    <div class="container">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><?= $folder ?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <!-- <li class="breadcrumb-item"><a href="<?= site_url() ?>"><?= @$title ?></a></li> -->
            <!-- <li class="breadcrumb-item"><a href="javascript:void(0)">#</a></li> -->
            <!-- <li class="breadcrumb-item active">#</li> -->
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container">
      <div class="row">
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
                    <th style="text-align: left;">Penilaian</th>
                    <th style="width: 10%; text-align: center;">Status</th>
                    <th style="width: 5%; text-align: center;">Aksi</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<script type="text/javascript">
  $(function () {
    $('[name="pengguna_id"]').change(function() {
      if ($(this).val()) {
        window.location.href = '<?= site_url('teacher/examination/') ?>' + $(this).val();
      } 
    });
    var url_table = 'teacher/show_examination/<?= @$pengguna_id .'/'. md5(@$program_studi_id) ?>';
    set_datatable(url_table);
  });
</script>