<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?= $title ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <!-- <li class="breadcrumb-item"><a href=""><?= @$folder ?></a></li>
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
        <div id="response"></div>
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar <?= $title ?></h3>
            <div class="float-right">
              <a href="<?= site_url('master/setgroup') ?>" class="btn btn-default btn-sm"><i class="fas fa-plus"></i> Tambah</a>
            </div>
          </div>
          <div class="card-body table-responsive">
            <table id="table" class="table" style="width: 100%;">
              <thead>
                <tr>
                  <th style="width: 5%; text-align: center;">No</th>
                  <!-- <th style="width: 10%; text-align: center;">Tahun<span style="color: #ffffff;">_</span>Pelaksanaan</th> -->
                  <th style="text-align: left;">Program<span style="color: #ffffff;">_</span>Studi</th>
                  <th style="text-align: left;">Sekolah<span style="color: #ffffff;">_</span>Mitra</th>
                  <th style="text-align: left;">DPL</th>
                  <th style="text-align: left;">GPL</th>
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
  $(function () {
    var url_table = 'master/show_group/<?= md5(time()) ?>';
    set_datatable(url_table);
  });
</script>