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
              <a href="javascript:void(0)" onclick="add_data();" id="btn-add" class="btn btn-default btn-sm"><i class="fas fa-plus"></i> Tambah</a>
            </div>
          </div>

          <div class="card-body table-responsive">
            <table id="table" class="table" style="width: 100%;">
              <thead>
                <tr>
                  <th style="width: 5%; text-align: center;">No</th>
                  <th style="text-align: left;">Tahun<span style="color: #ffffff;">_</span>Pelaksanaan</th>
                  <th style="width: 25%; text-align: left;">Tanggal<span style="color: #ffffff;">_</span>Mulai</th>
                  <th style="width: 15%; text-align: left;">Pendaftaran<span style="color: #ffffff;">_</span>Mahasiswa</th>
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
    var url_table = 'master/show_year/<?= md5(time()) ?>';
    set_datatable(url_table);
  });

  function add_data() {
    $('#response').html('');
    $.getJSON('<?= site_url('master/add_year/') ?>' + $('#tahun_pelaksanaan-1').val(), function(response) {
      if (response.status) {
        table.ajax.reload();
        set_flashdata(response.message);
      }
    });
  }

  function update_data(id) {
    $('#response').html('');
    $.getJSON('<?= site_url('master/update_year/') ?>' + id, function(response) {
      if (response.status) {
        table.ajax.reload();
        set_flashdata(response.message);
      }
    });
  }

  function pendaftaran_mahasiswa(id) {
    $('#response').html('');
    $.getJSON('<?= site_url('master/pendaftaran_mahasiswa/') ?>' + id, function(response) {
      if (response.status) {
        table.ajax.reload();
      }
    });
  }

  function tanggal_mulai(id) {
    var tanggal_mulai = $('[name="tanggal_mulai_'+ id +'"]').val();
    if (tanggal_mulai) {
      $.ajax({
        url: '<?= site_url('master/change_tanggal_mulai/') ?>' + id,
        type: 'POST',
        dataType: 'json',
        data: {tanggal_mulai: tanggal_mulai},
        success: function(response) {
          if (response.status) {
            table.ajax.reload();
            set_flashdata(response.message);
          }
        }
      });
    }
  }

</script>