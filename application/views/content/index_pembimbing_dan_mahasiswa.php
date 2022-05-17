<div class="content-wrapper">
  <div class="content-header">
    <div class="container">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><?= $title ?></h1>
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

        <div class="col-md-12 col-12">
          <?php if ($this->session->flashdata('success')): ?>
            <?= $this->session->flashdata('success') ?>
          <?php endif ?>
          <div id="response"></div>
        </div>

        <div class="col-lg-7">
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Informasi Pelaksanaan</h5>
                  <?php if (isset($id_kelompok_sekolah) && $this->session->id_jenis_pengguna == 3): ?>
                    <div class="card-tools">
                      <div class="btn-group">
                        <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                          <i class="fas fa-edit"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                          <a href="javascript:void(0)" class="dropdown-item" onclick="edit_gpl('<?= md5($id_kelompok_sekolah) ?>')">Edit GPL</a>
                        </div>
                      </div>
                    </div>
                  <?php endif ?>
                </div>
                <div class="card-body table-responsive">
                  <table class="table" style="width: 100%;">
                    <?php 
                    $no = 1;
                    foreach ($kelompok_sekolah as $key => $value) {
                      $bp = $no == 1 ? 'border-top: none; padding-top: 0px;' : '';
                      $bb = $no == count($kelompok_sekolah) ? 'border-bottom: 1px solid #DEE2E6;' : '';
                      $tbody = '<tr>';
                      $tbody .= '<td style="'. $bp .'">'. $key .'</td>';
                      $tbody .= '<td style="text-align: right; '. $bp .'">'. $value .'</td>';
                      $tbody .= '</tr>';
                      echo $tbody;
                      $no++;
                    } ?>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-lg-12">

              <?php if ($this->session->id_jenis_pengguna != 5): ?>

                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Kegiatan Mahasiswa</h3>
                  </div>
                  <div class="card-body table-responsive" style="padding-top: 0px; padding-bottom: 0px;">
                    <table class="table" style="width: 100%;">
                      <tr>
                        <td style="border-top: none; border-bottom: 1px solid #DEE2E6; font-weight: bold;"><?= $this->include->days(date('w')) .', '. $this->include->date(date('Y-m-d')) ?></td>
                      </tr>
                    </table>
                  </div>
                  <div class="card-body table-responsive" style="padding-top: 10px;">
                    <table id="table" class="table" style="width: 100%;">
                      <thead>
                        <tr>
                          <th style="width: 5%; text-align: center;">No</th>
                          <th style="text-align: center;">NIM</th>
                          <th style="text-align: left;">Nama<span style="color: #ffffff;">_</span>Mahasiswa</th>
                          <th style="width: 5%; text-align: left;">Kegiatan</th>
                          <th style="width: 30%; text-align: left;">Dokumentasi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $no = 1;
                        $tbody = '<tr>';
                        if ($kelompok_mahasiswa != false) {
                          foreach ($kelompok_mahasiswa as $key) {

                            $kegiatan    = $key->kegiatan ? $key->kegiatan : false;
                            $dokumentasi  = $key->dokumentasi ? '<img src="'. site_url(IMAGE . $key->dokumentasi) .'" alt="" style="max-width: 300px;">' : '-';

                            $tbody .= '<td style="text-align: center;">'. $no++ .'</td>';
                            $tbody .= '<td style="text-align: center;" id="nim_'. $key->id_kegiatan_mahasiswa .'">'. $key->no_induk .'</td>';
                            $tbody .= '<td style="text-align: left;" id="nama_mhs_'. $key->id_kegiatan_mahasiswa .'">'. $key->nama_lengkap .'</td>';
                            $tbody .= '<td style="text-align: left;"><button type="button" class="btn btn-default btn-sm" onclick="lihat_kegiatan('. $key->id_kegiatan_mahasiswa .')">Lihat</button></td>';
                            $tbody .= '<td style="text-align: left;">'. $dokumentasi .'</td>';
                            $tbody .= '<input type="hidden" id="kegiatan_'. $key->id_kegiatan_mahasiswa .'" value="'. $kegiatan .'">';
                            echo $tbody;
                          }
                        }
                        $tbody .= '</tr>';
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>

              <?php else: ?>

                <?php if (!@$pengguna->kartu_rencana_studi || !@$pengguna->kwitansi_pembayaran): ?>
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Pendaftaran Peserta PLP II</h3>
                    </div>
                    <div class="card-body">
                      <form action="<?= site_url('home/update_register/' . md5(@$pengguna->id_pengguna)) ?>" method="post" id="form-registrasi" enctype="multipart/form-data" style="display: none;">
                        <input type="file" name="kartu_rencana_studi">
                        <input type="file" name="kwitansi_pembayaran">
                        <input type="text" name="text-kartu_rencana_studi" value="<?= @$pengguna->kartu_rencana_studi ?>">
                        <input type="text" name="text-kwitansi_pembayaran" value="<?= @$pengguna->kwitansi_pembayaran ?>">
                      </form>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label for="kartu_rencana_studi"><span id="label-kartu_rencana_studi">Kartu Rencana Studi</span><span style="color: #dc3545; font-weight: bold;">*</span></label>
                            <div class="input-group">
                              <input type="text" class="form-control" id="kartu_rencana_studi" placeholder="<?= @$pengguna->kartu_rencana_studi ? 'Selesai' : 'Format : Foto / Scan' ?>" disabled="">
                              <span class="input-group-append">
                                <button type="button" class="btn btn-default" id="btn-kartu_rencana_studi" <?= $mahasiswa > 0 || @$pengguna->kartu_rencana_studi ? 'disabled=""' : '' ?>><i class="fas fa-image"></i> </button>
                              </span>
                            </div>
                            <span id="error-kartu_rencana_studi" class="error invalid-feedback" style="display: none;"></span>
                          </div>
                          <?php if (@$pengguna->kartu_rencana_studi): ?>
                            <div class="form-group">
                              <img src="<?= site_url(IMAGE . $pengguna->kartu_rencana_studi) ?>" style="width: 100%; max-height: 345px;">
                            </div>
                          <?php else: ?>
                            <div class="form-group" id="row-kartu_rencana_studi" style="display: none;">
                              <img src="" id="preview-kartu_rencana_studi" style="width: 100%; max-height: 345px;">
                            </div>
                          <?php endif ?>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label for="kwitansi_pembayaran"><span id="label-kwitansi_pembayaran">Bukti Pembayaran</span><span style="color: #dc3545; font-weight: bold;">*</span></label>
                            <div class="input-group">
                              <input type="text" class="form-control" id="kwitansi_pembayaran" placeholder="<?= @$pengguna->kwitansi_pembayaran ? 'Selesai' : 'Format : Foto / Scan' ?>" disabled="">
                              <span class="input-group-append">
                                <button type="button" class="btn btn-default" id="btn-kwitansi_pembayaran" <?= $mahasiswa > 0 || @$pengguna->kwitansi_pembayaran ? 'disabled=""' : '' ?>><i class="fas fa-image"></i></button>
                              </span>
                            </div>
                            <span id="error-kwitansi_pembayaran" class="error invalid-feedback" style="display: none;"></span>
                          </div>
                          <?php if (@$pengguna->kwitansi_pembayaran): ?>
                            <div class="form-group">
                              <img src="<?= site_url(IMAGE . $pengguna->kwitansi_pembayaran) ?>" style="width: 100%; max-height: 345px;">
                            </div>
                          <?php else: ?>
                            <div class="form-group" id="row-kwitansi_pembayaran" style="display: none;">
                              <img src="" id="preview-kwitansi_pembayaran" style="width: 100%; max-height: 345px;">
                            </div>
                          <?php endif ?>
                          <div class="form-group">
                            <button type="button" class="btn btn-default btn-sm" value="<?= $mahasiswa ?>" id="btn-registrasi" style="float: right;"><i class="fas fa-upload"></i> Upload</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endif ?>

              <?php endif ?>

            </div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title"><?= @$pengguna->jenis_pengguna_id == 5 ? 'Data Mahasiswa' : 'Profile Pengguna' ?></h5>
              <div class="card-tools">
                <div class="btn-group">
                  <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-edit"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="javascript:void(0)" class="dropdown-item" id="edit-profile" onclick="edit_profile('<?= md5(@$pengguna->id_pengguna) ?>');">Edit <?= @$pengguna->jenis_pengguna_id == 5 ? 'Data Mahasiswa' : 'Profile' ?></a>
                    <?php if (isset($pengguna->foto_profil)): ?>
                      <a href="javascript:void(0)" class="dropdown-item" id="delete-foto">Hapus Foto</a>
                    <?php else: ?>
                      <a href="javascript:void(0)" class="dropdown-item" id="upload-foto">Upload Foto</a>
                    <?php endif ?>
                    <a href="javascript:void(0)" class="dropdown-item" id="edit-password">Edit Password</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body table-responsive">
              <table class="table" style="width: 100%;">
                <tr>
                  <td colspan="2" style="padding-top: 0px; border-top: none; text-align: center; width: 50%;">
                    <?php if (isset($pengguna->foto_profil)): ?>
                      <img class="" src="<?= site_url(IMAGE . $pengguna->foto_profil) ?>" alt="" style="max-width: 125px;">
                    <?php else: ?>
                      <?php
                      $nama_lengkap = explode(' ', $pengguna->nama_lengkap);
                      $foto_profile = isset($nama_lengkap[0]) ? substr(strtoupper($nama_lengkap[0]), 0, 1) : '';
                      $foto_profile .= isset($nama_lengkap[1]) ? substr(strtoupper($nama_lengkap[1]), 0, 1) : '';
                      ?>
                      <span class="foto_pengguna" style="width: 120px; height: 120px; font-size: 36px;"><?= $foto_profile ?></span>
                    <?php endif ?>
                  </td>
                </tr>
                <tr>
                  <td><?= $this->session->id_jenis_pengguna == 5 ? 'NIM' : 'NIDN' ?></td>
                  <td style="text-align: right;"><?= $this->include->null($pengguna->no_induk) ?></td>
                </tr>
                <tr>
                  <td>Nama<span style="color: #FFFFFF;">_</span>Lengkap</td>
                  <td style="text-align: right;"><?= $this->include->null($pengguna->nama_lengkap) ?></td>
                </tr>
                <tr>
                  <td>Jenis<span style="color: #FFFFFF;">_</span>Kelamin</td>
                  <td style="text-align: right;"><?= $this->include->jenis_kelamin($pengguna->jenis_kelamin) ?></td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td style="text-align: right;"><?= $this->include->null($pengguna->email) ?></td>
                </tr>
                <tr>
                  <td>Telepon</td>
                  <td style="text-align: right;"><?= $this->include->null($pengguna->telepon) ?></td>
                </tr>
                <?php if ($this->session->id_jenis_pengguna == 5): ?>
                  <tr>
                    <td>Program<span style="color: #FFFFFF;">_</span>Studi</td>
                    <td style="text-align: right;"><?= $this->include->null($pengguna->program_studi) ?></td>
                  </tr>
                  <tr>
                    <td>Angkatan</td>
                    <td style="text-align: right;"><?= $this->include->null($pengguna->angkatan) ?></td>
                  </tr>
                <?php endif ?>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>

<div class="modal fade" id="modal-kegiatan">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close cancel" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="padding-top: 0px; padding-bottom: 0px;">
        <table class="table" style="width: 100%;">
          <tr>
            <td style="border-top: none;">NIM</td>
            <td id="nim" style="border-top: none; text-align: right;">-</td>
          </tr>
          <tr>
            <td>Nama<span style="color: #ffffff;">_</span>Mahasiswa</td>
            <td id="nama_mhs" style="text-align: right;">-</td>
          </tr>
          <tr>
            <td colspan="2">
              <div class="form-group">
                <label for="">Catatan Kegiatan</label>
                <textarea id="kegiatan" class="form-control" disabled=""></textarea>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-pembimbing">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close cancel" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="padding-top: 0px; padding-bottom: 0px;">
        <table class="table" style="width: 100%;">
          <tr>
            <td colspan="2" id="foto_pembimbing" style="border-top: none; text-align: center;">-</td>
          </tr>
          <tr>
            <td class="txt_id">-</td>
            <td id="no_induk_gpl" style="text-align: right;">-</td>
          </tr>
          <tr>
            <td>Nama<span style="color: #ffffff;">_</span>Lengkap</td>
            <td id="nama_lengkap_gpl" style="text-align: right;">-</td>
          </tr>
          <tr>
            <td>Jenis<span style="color: #ffffff;">_</span>Kelamin</td>
            <td id="jenis_kelamin_gpl" style="text-align: right;">-</td>
          </tr>
          <tr>
            <td>Email</td>
            <td id="email_gpl" style="text-align: right;">-</td>
          </tr>
          <tr>
            <td>Telepon</td>
            <td id="telepon_gpl" style="text-align: right;">-</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-edit_gpl">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close cancel" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="post" id="form-edit_gpl">
        <div class="modal-body">
          <div class="form-group">
            <label for="id_pengguna_gpl">GPL<span style="color: #dc3545; font-weight: bold;">*</span></label>
            <select name="id_pengguna_gpl" id="id_pengguna_gpl" class="form-control select2">
              <option value="">Pilih GPL</option>
              <?php if (isset($guru_pamong)): ?>
                <?php foreach ($guru_pamong as $key) {
                  $selected = $key['id_pengguna'] == $id_pengguna_gpl ? 'selected=""' : '';
                  echo '<option value="'. md5($key['id_pengguna']) .'" '. $selected .'>'. $key['nama_lengkap'] .'</option>';
                } ?>
              <?php endif ?>
            </select>
            <span id="error-id_pengguna_gpl" class="error invalid-feedback" style="display: none;"></span>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <div class="btn-group">
            <button type="button" class="btn btn-secondary btn-sm cancel" data-dismiss="modal"><i class="fas fa-angle-double-left"></i> Batal</button>
            <button type="button" class="btn btn-success btn-sm" style="font-weight: bold;" onclick="tambah_gpl();"><i class="fas fa-user-plus"></i> Tambah GPL</button>
          </div>
          <button type="button" class="btn btn-default btn-sm" id="btn-edit_gpl"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-addedit_pengguna">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close cancel" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="post" id="form-addedit_pengguna">
        <div class="modal-body">
          <div class="form-group" style="display: none;">
            <input type="text" id="id_pengguna" name="id_pengguna" value="">
            <input type="text" id="sekolah_mitra_id" name="sekolah_mitra_id" value="">
          </div>
          <div class="form-group">
            <label for="no_induk" class="txt_id">-<span style="color: #dc3545; font-weight: bold;">*</span></label>
            <input type="text" class="form-control" id="no_induk" name="no_induk" placeholder="NIP/NUPTK" autocomplete="off" value="">
            <span id="error-no_induk" class="error invalid-feedback" style="display: none;"></span>
          </div>
          <div class="form-group">
            <label for="nama_lengkap">Nama Lengkap<span style="color: #dc3545; font-weight: bold;">*</span></label>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" autocomplete="off" value="">
            <span id="error-nama_lengkap" class="error invalid-feedback" style="display: none;"></span>
          </div>
          <div class="form-group">
            <label for="jenis_kelamin">Jenis Kelamin<span style="color: #dc3545; font-weight: bold;">*</span></label>
            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control select2">
              <option value="">Pilih Jenis Kelamin</option>
              <?php foreach (['L' => 'Laki-Laki', 'P' => 'Perempuan'] as $key => $value) {
                echo '<option value="'. $key .'">'. $value .'</option>';
              } ?>
            </select>
            <span id="error-jenis_kelamin" class="error invalid-feedback" style="display: none;"></span>
            </div>
          <div class="form-group">
            <label for="email">Email<span style="color: #dc3545; font-weight: bold;">*</span></label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" value="">
            <span id="error-email" class="error invalid-feedback" style="display: none;"></span>
          </div>
          <div class="form-group">
            <label for="telepon">Telepon<span style="color: #dc3545; font-weight: bold;">*</span></label>
            <input type="number" class="form-control" id="telepon" name="telepon" placeholder="Telepon" autocomplete="off" value="">
            <span id="error-telepon" class="error invalid-feedback" style="display: none;"></span>
          </div>
          <?php if (@$pengguna->jenis_pengguna_id == 5): ?>
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
          <?php endif ?>
          <div class="form-group password">
            <label for="password1">Password</label>
            <input type="password" class="form-control" id="password1" name="password1" placeholder="Password (Default: Email)">
          </div>
          <div class="form-group password">
            <label for="password2">Konformasi Password</label>
            <input type="password" class="form-control" id="password2" name="password2" placeholder="Konfirmasi Password (Ulangi)">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary btn-sm cancel" data-dismiss="modal"><i class="fas fa-angle-double-left"></i> Batal</button>
          <button type="submit" class="btn btn-default btn-sm"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-edit_password">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close cancel" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= site_url('home/update_password/' . md5(@$pengguna->id_pengguna)) ?>" method="post" id="form-edit_password">
        <div class="modal-body">
          <div class="form-group">
            <label for="password_sekarang">Password Sekarang</label>
            <input type="password" class="form-control" id="password_sekarang" name="password_sekarang" placeholder="Password Sekarang">
            <span id="error-password_sekarang" class="error invalid-feedback" style="display: none;"></span>
          </div>
          <div class="form-group">
            <label for="password_baru">Password Baru</label>
            <input type="password" class="form-control" id="password_baru" name="password_baru" placeholder="Password Baru">
            <span id="error-password_baru" class="error invalid-feedback" style="display: none;"></span>
          </div>
          <div class="form-group">
            <label for="konfirmasi_password">Konformasi Password</label>
            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" placeholder="Konfirmasi Password (Ulangi Password Baru)">
            <span id="error-konfirmasi_password" class="error invalid-feedback" style="display: none;"></span>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary btn-sm cancel" data-dismiss="modal"><i class="fas fa-angle-double-left"></i> Batal</button>
          <button type="submit" class="btn btn-default btn-sm"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<form action="<?= site_url('home/upload_foto/' . md5(@$pengguna->id_pengguna)) ?>" method="post" id="form-foto" enctype="multipart/form-data" style="display: none;">
  <input type="file" name="foto" value="">
  <input type="text" name="foto_profil" value="<?= @$pengguna->foto_profil ?>">
</form>

<script type="text/javascript">

  $(function() {
    
    $('#btn-edit_gpl').click(function() {
      if ($('[name="id_pengguna_gpl"]').val() && $('[name="id_pengguna_gpl"]').val() != '<?= md5(@$id_pengguna_gpl) ?>') {
        $('#form-edit_gpl').submit();
      } else {
        if (!$('[name="id_pengguna_gpl"]').val()) {
          $('[name="id_pengguna_gpl"]').addClass('is-invalid');
          $('#error-id_pengguna_gpl').text('GPL harus dipilih').show();
           $('[name="id_pengguna_gpl"]').change(function() {
            $('[name="id_pengguna_gpl"]').removeClass('is-invalid');
            $('#error-id_pengguna_gpl').text('').hide();
           });
        } else {
          $('#modal-edit_gpl').modal('hide');
        }
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

    $('#delete-foto').click(function() {
      $('[name="id_delete"]').val('').change();
      $('.modal-title').text('Hapus Foto');
      $('#modal-delete').modal('show');
    });

    $('#btn-delete').click(function() {
      $('#form-foto').submit();
    });

    $('#edit-password').click(function() {
      $('#form-edit_password')[0].reset();
      $('.modal-title').text('Edit Password');
      $('#modal-edit_password').modal('show');
    });

    $('#btn-kartu_rencana_studi').click(function() {
     $('[name="kartu_rencana_studi"]').click();
    });

    $('#btn-kwitansi_pembayaran').click(function() {
     $('[name="kwitansi_pembayaran"]').click();
    });

    $('[name="kartu_rencana_studi"]').change(function() {
      if ($(this).val()) {
        var kartu_rencana_studi = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
        $('#kartu_rencana_studi').val(kartu_rencana_studi).change();
        $('[name="text-kartu_rencana_studi"]').val(kartu_rencana_studi).change();
        $('#row-kartu_rencana_studi').show();
        if (this.files && this.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
            $('#preview-kartu_rencana_studi').attr('src', e.target.result);
          }
          reader.readAsDataURL(this.files[0]);
        }
      }
    });

    $('[name="kwitansi_pembayaran"]').change(function() {
      if ($(this).val()) {
        var kwitansi_pembayaran = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
        $('#kwitansi_pembayaran').val(kwitansi_pembayaran).change();
        $('[name="text-kwitansi_pembayaran"]').val(kwitansi_pembayaran).change();
        $('#row-kwitansi_pembayaran').show();
        if (this.files && this.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
            $('#preview-kwitansi_pembayaran').attr('src', e.target.result);
          }
          reader.readAsDataURL(this.files[0]);
        }
      }
    });

     $('#btn-registrasi').click(function() {
      $('#response').html('');
      if ($(this).attr('value') > 0) {
        var message = 'Data Mahasiswa Belum Lengkap';
        var type    = 'alert-danger';
        set_flashdata(message, type);
      } else {
        var errors = [];
        $.each(['kartu_rencana_studi', 'kwitansi_pembayaran'], function(index, val) {
          if (!$('[name="' + val + '"]').val()) {
            errors.push(index);
            $('#' + val + '').addClass('is-invalid');
            $('#error-' + val + '').text($('#label-' + val + '').text() + ' harus diupload').show();
            $('#' + val + '').change(function() {
              if ($(this).val()) {
                $('#' + val + '').removeClass('is-invalid');
                $('#error-' + val + '').text('').hide();
              }
            });
          } 
        });

        if (errors.length < 1) {
          update_register();
        }
        
      }
     });

    validation_password();

  });

  function lihat_kegiatan(id) {
    var nim       = $('#nim_'+ id +'').text();
    var nama_mhs  = $('#nama_mhs_'+ id +'').text();
    var kegiatan  = $('#kegiatan_'+ id +'').val();
    $('#nim').text(nim);
    $('#nama_mhs').text(nama_mhs);
    $('#kegiatan').val(kegiatan);
    $('.modal-title').text('Kegiatan Mahasiswa');
    $('#modal-kegiatan').modal('show');
  }

  function lihat_pembimbing(id) {
    $.getJSON('<?= site_url('home/get_pembimbing/') ?>' + id, function(data) {
      $('.txt_id').text(data.txt_id);
      $('#no_induk_gpl').text(data.no_induk);
      $('#nama_lengkap_gpl').text(data.nama_lengkap);
      $('#jenis_kelamin_gpl').text(data.jenis_kelamin);
      $('#email_gpl').text(data.email);
      $('#telepon_gpl').text(data.telepon);
      $('#foto_pembimbing').html(data.foto_profil);
      $('.modal-title').text(data.modal_title);
      $('#modal-pembimbing').modal('show');
    });
  }

  function edit_gpl(id) {
    $('#form-edit_gpl').attr('action', '<?= site_url('home/edit_gpl/') ?>' + id);
    $('.modal-title').text('Edit GPL');
    $('#modal-edit_gpl').modal('show');
  }

  function tambah_gpl() {
    $('#modal-edit_gpl').modal('hide');
    $('#form-addedit_pengguna')[0].reset();
    $('[name="jenis_kelamin"]').val('').change();
    $('[name="sekolah_mitra_id"]').val('<?= @$sekolah_mitra_id ?>').change();
    $('.txt_id').text('NIP/NUPTK');
    $('#no_induk').attr('placeholder', 'NIP/NUPTK');
    $('.password').show();
    var txt_id = 'NIP/NUPTK';
    validation_pengguna(txt_id);
    setTimeout(function() {
      $('.modal-title').text('Tambah GPL');
      $('#modal-addedit_pengguna').modal('show');
    }, 500);
  }

  function edit_profile(id) {
    $.getJSON('<?= site_url('home/get_pembimbing/') ?>' + id, function(data) {
      var title = $('#edit-profile').text();
      var email = data.email != '-' ? data.email : '';
      $('.txt_id').text(data.txt_id);
      $('#no_induk').attr('placeholder', data.txt_id);
      $('[name="id_pengguna"]').val(id).change();
      $('[name="no_induk"]').val(data.no_induk).change();
      $('[name="nama_lengkap"]').val(data.nama_lengkap).change();
      $('[name="jenis_kelamin"]').val(data.gender).change();
      $('[name="email"]').val(email).change();
      $('[name="telepon"]').val(data.telepon).change();
      $('[name="sekolah_mitra_id"]').val('').change();
      $('[name="program_studi_id"]').val(data.program_studi_id).change();
      $('[name="angkatan"]').val(data.angkatan).change();
      $('.password').hide();
      validation_pengguna(data.txt_id);
      $('.modal-title').text(title);
      $('#modal-addedit_pengguna').modal('show');
    });
  }

  function validation_pengguna(txt_id) {
    $.validator.setDefaults({
      submitHandler: function () {
        save_pengguna();
      }
    });
    var validator = $('#form-addedit_pengguna').validate({
      rules: {
        no_induk: {
          required: true,
          <?php if (@$pengguna->jenis_pengguna_id != 5): ?>
            number: true,
            min: 0,
          <?php endif ?>
        },
        nama_lengkap: {
          required: true,
        },
        jenis_kelamin: {
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
        password1: {
          minlength: 8,
        },
        password2: {
          equalTo: "#password1",
        },
        <?php if (@$pengguna->jenis_pengguna_id == 5): ?>
          program_studi_id: {
            required: true,
          },
          angkatan: {
            required: true,
          },
        <?php endif ?>
      },
      messages: {
        no_induk: {
          required: ''+ txt_id +' harus diisi',
          number: ''+ txt_id +' tidak valid',
          min: ''+ txt_id +' minimal angka 0',
        },
        nama_lengkap: {
          required: "Nama Lengkap harus diisi",
        },
        jenis_kelamin: {
          required: "Jenis Kelamin harus dipilih",
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
        program_studi_id: {
          required: "Program Studi harus dipilih",
        },
        angkatan: {
          required: "Angkatan harus dipilih",
        },
        password1: {
          minlength: "Password minimal 8 karakter",
        },
        password2: {
          equalTo: "Konfirmasi Password tidak sama",
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

  function save_pengguna() {
    $.ajax({
        url: "<?= site_url('home/save_pengguna/' . md5(time())) ?>",
        type: "POST",
        data: new FormData($('#form-addedit_pengguna')[0]),
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(response) {
          if (response.status) {
            $('#modal-addedit_pengguna').modal('hide');
            set_flashdata(response.message);
            setTimeout(function() {
              window.location.reload();
            }, 3275);
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

  function validation_password() {
    $.validator.setDefaults({
      submitHandler: function () {
        save_password();
      }
    });
    var validator = $('#form-edit_password').validate({
      rules: {
        password_sekarang: {
          required: true,
        },
        password_baru: {
          required: true,
          minlength: 8,
        },
        konfirmasi_password: {
          required: true,
          equalTo: "#password_baru",
        },
      },
      messages: {
        password_sekarang: {
          required: "Password Sekarang harus diisi",
        },
        password_baru: {
          required: "Password Baru harus diisi",
          minlength: "Password Baru minimal 8 karakter",
        },
        konfirmasi_password: {
          required: "Konfirmasi Password harus diisi",
          equalTo: "Konfirmasi Password tidak sama",
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

  function save_password() {
    $.ajax({
        url: $('#form-edit_password').attr('action'),
        type: "POST",
        data: new FormData($('#form-edit_password')[0]),
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(response) {
          if (response.status) {
            $('#modal-edit_password').modal('hide');
            set_flashdata(response.message);
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

  function update_register() {
    $.ajax({
        url: "<?= site_url('home/update_register/' . md5(@$pengguna->id_pengguna)) ?>",
        type: "POST",
        data: new FormData($('#form-registrasi')[0]),
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(response) {
          if (response.message) {
            set_flashdata(response.message);
            setTimeout(function() {
              window.location.reload();
            }, 3250);
          }
          if (response.errors) {
            $.each(response.errors, function (key, val) {
              if (val == '') {
                $('#' + key + '').removeClass('is-invalid');
                $('#error-'+ key +'').text('').hide();
                $('#row-'+ key +'').show();
              } else {
                $('#' + key + '').addClass('is-invalid');
                $('#error-'+ key +'').text(val).show();
                $('#row-'+ key +'').hide();
              }

              $('[name="' + key + '"]').change(function() {
                if (val == '') {
                  $('#' + key + '').removeClass('is-invalid');
                  $('#error-'+ key +'').text('').hide();
                  $('#row-'+ key +'').show();
                } else {
                  $('#' + key + '').addClass('is-invalid');
                  $('#error-'+ key +'').text(val).show();
                  $('#row-'+ key +'').hide();
                }

              });
              
            });
          }
        }
    });
  }


</script>

<style type="text/css">
  .foto_pengguna{
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