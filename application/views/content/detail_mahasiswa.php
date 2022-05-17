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

      <div class="col-md-12 col-12">
        <?php if ($this->session->flashdata('success')): ?>
          <?= $this->session->flashdata('success') ?>
        <?php endif ?>
        <div id="response"></div>
      </div>

      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><?= 'Data '. $title ?></h3>
            <!-- <div class="card-tools">
              <a href="<?= site_url('user/edit/' . md5(@$row->id_pengguna)) ?>" class="btn btn-tool"><i class="fas fa-edit"></i></a>
            </div> -->
          </div>
          <div class="card-body" style="border-bottom: 1px solid #DEE2E6; padding-bottom: 0px;">
            <div class="table-responsive">
              <table class="table" style="width: 100%;">
                <tr>
                  <td style="padding: 0px; border-top: none;">
                    <div class="input-group">
                      <span class="input-group-prepend">
                        <a href="<?= site_url('user/student') ?>" class="btn btn-secondary"><i class="fas fa-angle-double-left"></i></a>
                      </span>
                      <select name="id_pengguna" id="id_pengguna" class="form-control select2">
                        <option value="">Cari Mahasiswa</option>
                        <?php foreach ($mahasiswa as $key) {
                          echo '<option value="'. md5($key->id_pengguna) .'">'. $key->no_induk .' - '. $key->nama_lengkap .'</option>';
                        } ?>
                      </select>
                    </div>
                  </td>
                  <td style="padding: 0px; border-top: none; width: 5%;">
                    <button type="button" class="btn btn-default" id="btn-search"><i class="fas fa-search"></i></button>
                  </td>
                  <td style="padding-right: 0px; padding-top: 0px; border-top: none; width: 5%;">
                    <a href="<?= site_url('user/edit/' . md5(@$row->id_pengguna)) ?>" class="btn btn-default"><i class="fas fa-edit"></i></a>
                  </td>
                  <td style="padding-right: 0px; padding-top: 0px; border-top: none; width: 5%;">
                    <a href="javascript:void(0)" class="btn btn-default" id="upload-foto"><i class="fas fa-image"></i></a>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <div class="card-body">
            <table class="table table-responsive" style="width: 100%;">
              <tr>
                <td colspan="2" style="padding-top: 0px; border-top: none; text-align: center; width: 50%;">
                  <?php if (isset($row->foto_profil)): ?>
                    <img class="" src="<?= site_url(IMAGE . $row->foto_profil) ?>" alt="" style="width: 50%; max-height: 275px;">
                  <?php else: ?>
                    <?php
                    $nama_lengkap = explode(' ', $row->nama_lengkap);
                    $foto_profile = isset($nama_lengkap[0]) ? substr(strtoupper($nama_lengkap[0]), 0, 1) : '';
                    $foto_profile .= isset($nama_lengkap[1]) ? substr(strtoupper($nama_lengkap[1]), 0, 1) : '';
                    ?>
                    <span id="foto_mahasiswa" style="width: 120px; height: 120px; font-size: 36px;"><?= $foto_profile ?></span>
                  <?php endif ?>
                </td>
              </tr>
              <tr>
                <td>NIDN</td>
                <td style="text-align: right;"><?= $this->include->null($row->no_induk) ?></td>
              </tr>
              <tr>
                <td>Nama<span style="color: #FFFFFF;">_</span>Lengkap</td>
                <td style="text-align: right;"><?= $this->include->null($row->nama_lengkap) ?></td>
              </tr>
              <tr>
                <td>Jenis<span style="color: #FFFFFF;">_</span>Kelamin</td>
                <td style="text-align: right;"><?= $this->include->jenis_kelamin($row->jenis_kelamin) ?></td>
              </tr>
              <tr>
                <td>Email</td>
                <td style="text-align: right;"><?= $this->include->null($row->email) ?></td>
              </tr>
              <tr>
                <td>Telepon</td>
                <td style="text-align: right;"><?= $this->include->null($row->telepon) ?></td>
              </tr>
              <tr>
                <td>Program<span style="color: #FFFFFF;">_</span>Studi</td>
                <td style="text-align: right;"><?= $this->include->null($row->program_studi) ?></td>
              </tr>
              <tr>
                <td>Angkatan</td>
                <td style="text-align: right;"><?= $this->include->null($row->angkatan) ?></td>
              </tr>
              <tr>
                <td>Status<span style="color: #FFFFFF;">_</span>Mahasiswa</td>
                <td style="text-align: right;"><?= $row->status_pendaftaran == 1 ? 'Aktif' : 'Tidak Aktif' ?></td>
              </tr>
              <tr>
                <td>Tanggal<span style="color: #FFFFFF;">_</span>Pendaftaran</td>
                <td style="text-align: right;"><?= $this->include->date($row->tanggal_pendaftaran) ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Detail Mahasiswa</h3>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="">Resume Pembekalan</label>
              <textarea name="" id="" rows="10" class="form-control" disabled=""><?= @$row->resume_pembekalan ?></textarea>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Kartu Rencana Studi</label>
                  <?php if (@$row->kartu_rencana_studi): ?>
                    <img src="<?= site_url(IMAGE . @$row->kartu_rencana_studi) ?>" style="width: 100%; max-height: 275px;">
                  <?php else: ?>
                    <textarea name="" id="" class="form-control" disabled=""></textarea>
                  <?php endif ?>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Bukti Pembayaran</label>
                  <?php if (@$row->kwitansi_pembayaran): ?>
                    <img src="<?= site_url(IMAGE . @$row->kwitansi_pembayaran) ?>" style="width: 100%; max-height: 325px;">
                  <?php else: ?>
                    <textarea name="" id="" class="form-control" disabled=""></textarea>
                  <?php endif ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Kegiatan Mahasiwa</h3>
          </div>
          <div class="card-body table-responsive">
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
      </div>

    </div>
  </div>
</div>

<form action="<?= site_url('home/upload_foto/' . md5(@$row->id_pengguna)) ?>" method="post" id="form-foto" enctype="multipart/form-data" style="display: none;">
  <input type="file" name="foto" value="">
  <input type="text" name="foto_profil" value="<?= @$row->foto_profil ?>"> 
  <input type="text" name="redirect" value="<?= site_url('user/detail/' . md5(@$row->id_pengguna)) ?>">
</form>

<script type="text/javascript">
  $(function() {

    var url_table = 'master/show_activity/<?=md5(@$row->id_pengguna) ?>';
    set_datatable(url_table);

    $('#btn-search').click(function() {
      var id_pengguna = $('[name="id_pengguna"]').val();
      if (id_pengguna) {
        window.location.href = '<?= site_url('user/detail/') ?>' + id_pengguna;
      }
    });

    $('[name="foto"]').change(function() {
      if ($(this).val()) {
        $('#form-foto').submit();
      }
    });

    $('#upload-foto').click(function() {
      $('[name="foto"]').click();
    });

  });
</script>

<style type="text/css">
  #foto_mahasiswa{
    background: #ffffff;
    color: #222284;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    border: 3px solid #DEE2E6;
  }
</style>