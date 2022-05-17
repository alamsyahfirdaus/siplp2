<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?= $title ?><?php if(isset($tahun_pelaksanaan->id_tahun_pelaksanaan)) echo '<small style="font-size: 18px;"> '. $tahun_pelaksanaan->tahun_pelaksanaan .'</small>'; ?></h1>
      </div>
      <div class="col-sm-6"></div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4 col-6">
        <div class="small-box bg-white">
          <div class="inner">
            <h3><?= $dosen ?></h3>
            <p>Dosen</p>
          </div>
          <div class="icon">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-6">
        <div class="small-box bg-white">
          <div class="inner" style="">
            <h3><?= $guru_pamong ?></h3>
            <p>Guru Pamong</p>
          </div>
          <div class="icon">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="small-box bg-white">
          <div class="inner" style="">
            <h3><?= $mahasiswa ?></h3>
            <p>Mahasiswa</p>
          </div>
          <div class="icon">
            <i class="fas fa-users"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div id="response"></div>
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Pendaftaran Mahasiswa</h3>
          </div>
          <div class="card-body table-responsive">
            <table id="table" class="table" style="width: 100%;">
              <thead>
                <tr>
                  <th style="width: 5%; text-align: center;">No</th>
                  <th style="text-align: left;">Data<span style="color: #ffffff;">_</span>Mahasiswa</th>
                  <th style="width: 25%; text-align: left;">Kartu<span style="color: #ffffff;">_</span>Rencana<span style="color: #ffffff;">_</span>Studi</th>
                  <th style="width: 25%; text-align: left;">Bukti<span style="color: #ffffff;">_</span>Pembayaran</th>
                  <th style="width: 10%; text-align: center;">Aksi</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function() {
    var url_table = 'master/show_register/<?= md5(time()) ?>';
    set_datatable(url_table);
  });

  function terima_pendaftaran(id) {
    $.getJSON('<?= site_url('master/terima_pendaftaran/') ?>' + id, function(response) {
      if (response.status) {
        table.ajax.reload();
        set_flashdata(response.message);
      }
    });
  }
</script>

<style type="text/css">
  .inner {
    color: #222284;
    border-top: 3px solid #222284;
  }
  .inner>p {
    font-weight: bold;
  }

  .icon>i{
    color: #222284;
  }
</style>