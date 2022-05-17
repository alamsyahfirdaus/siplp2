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
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Daftar <?= $title ?></h5>
            </div>
            <div class="card-body table-responsive">
              <table id="table" class="table" style="width: 100%;">
                <thead>
                  <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="text-align: center;">NIM</th>
                    <th style="text-align: left;">Nama<span style="color: #ffffff;">_</span>Mahasiswa</th>
                    <th style="text-align: left;">Jenis<span style="color: #ffffff;">_</span>Kelamin</th>
                    <th style="text-align: left;">Email</th>
                    <th style="text-align: left;">Telepon</th>
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
    var url_table = 'teacher/show_student/<?= md5(time()) ?>';
    set_datatable(url_table);
  });
</script>