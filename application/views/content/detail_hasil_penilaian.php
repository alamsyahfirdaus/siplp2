<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?= $title ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <!-- <li class="breadcrumb-item"><a href=""><?= @$folder ?></a></li>
          <li class="breadcrumb-item"><?= @$title ?></li>
          <li class="breadcrumb-item active"><?= @$header ?></li> -->
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
            <h3 class="card-title">Detail <?= $title ?></h3>
            <div class="card-tools">
              <a href="<?= site_url('evaluation/score') ?>" class="btn btn-tool"><i class="fas fa-times"></i></a>
            </div>
          </div>
          <div class="card-body table-responsive">
            <table class="table">
              <tr>
                <td style="width: 15%; padding-top: 0px; border-top: none;">NIM</td>
                <td style="width: 5%; padding-top: 0px; border-top: none;">:</td>
                <td style="width: 25%; padding-top: 0px; border-top: none;"><?= $row['nim'] ?></td>
                <td style="width: 15%; padding-top: 0px; border-top: none;">Sekolah<span style="color: #ffffff;">_</span>Mitra</td>
                <td style="width: 5%; padding-top: 0px; border-top: none;">:</td>
                <td style="width: 25%; padding-top: 0px; border-top: none;"><?= $row['sekolah_mitra'] ?></td>
              </tr>
              <tr>
                <td>Nama<span style="color: #ffffff;">_</span>Mahasiswa</td>
                <td>:</td>
                <td><?= $row['nama_mhs'] ?></td>
                <td>DPL</td>
                <td>:</td>
                <td><?= $row['nama_dpl'] ?></td>
              </tr>
              <tr>
                <td style="border-bottom: 1px solid #DEE2E6;">Program<span style="color: #ffffff;">_</span>Studi</td>
                <td style="border-bottom: 1px solid #DEE2E6;">:</td>
                <td style="border-bottom: 1px solid #DEE2E6;"><?= $row['program_studi'] ?></td>
                <td style="border-bottom: 1px solid #DEE2E6;">GPL</td>
                <td style="border-bottom: 1px solid #DEE2E6;">:</td>
                <td style="border-bottom: 1px solid #DEE2E6;"><?= $row['nama_gpl'] ?></td>
              </tr>
            </table>
          </div>
          <div class="card-body table-responsive">
            <table id="table" class="table" style="width: 100%;">
              <thead>
                <tr>
                  <th style="width: 5%; text-align: center;">No</th>
                  <th>Nama<span style="color: #ffffff;">_</span>Penilaian</th>
                  <th>Penilai<span style="color: #ffffff;">_</span>(DPL/GPL)</th>
                  <th style="width: 10%; text-align: center;">Total<span style="color: #ffffff;">_</span>Skor</th>
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
  var url_table = 'evaluation/show_report/<?= $row['tahun_pelaksanaan_id'] .'/'. @$id_pengguna_mhs ?>';

  $(function () {
    set_datatable(url_table);
  });

</script>