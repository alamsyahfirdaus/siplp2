<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?= $folder ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href=""><?= $folder ?></a></li>
          <li class="breadcrumb-item active"><?= $title ?></li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <?php if ($this->session->flashdata('alert')): ?>
          <div class="alert alert-success alert-dismissible" style="font-weight: bold;"><?= $this->session->flashdata('alert') ?></div>
        <?php endif ?>
        <div id="response"></div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar <?= $folder ?></h3>
          </div>
          <div class="card-body table-responsive">
            <table id="table" class="table" style="width: 100%;">
              <thead>
                <tr style="text-align: center;">
                  <th style="width: 5%;">No</th>
                  <th>Pengaturan</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<form action="<?= site_url('setting/updatePengaturan') ?>" method="post" id="form" enctype="multipart/form-data" style="display: none;">
  <input type="text" name="id_pengaturan" value="">
  <input type="text" name="pengaturan" value="">
  <input type="file" name="gambar" value="">
</form>

<script type="text/javascript">

  $(function() {
    
    table = $('#table').DataTable({
        "processing": true,
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
          "sZeroRecords": "<b style='color: #777777;'>TIDAK DITEMUKAN</b>",
          "sSearch": "Cari:"
        },
        "ajax": {
          "url": "<?= site_url('setting/showPengaturan') ?>",
          "type": "POST",
          "data": function(data) {
            data.id             = "<?= md5(time()) ?>";
            data.id_pengaturan  = $('[name="id_pengaturan"]').val();
          },
        },
        "columnDefs": [{ 
          "targets": [0],
          "orderable": false,
        }],
    });

    $('[name="gambar"]').change(function() {
      $('#form').submit();
    });

  });

  function change_gambar(id) {
    $('[name="id_pengaturan"]').val(id);
    $('[name="gambar"]').click();
  }

  function change_pengaturan(id) {
    $('[name="id_pengaturan"]').val(id).trigger('change');
    table.ajax.reload();
  }

  function update_pengaturan(id) {
    var pengaturan      = $('#pengaturan_'+ id +'').val();
    var pengaturan_old  = $('#pengaturan_old_'+ id +'').val();

    if (pengaturan && pengaturan != pengaturan_old) {
      $('[name="pengaturan"]').val(pengaturan);
      $('#form').submit();
    } else {

      if (!pengaturan) {
        $('#pengaturan_'+ id +'').addClass('is-invalid');
        $('#pengaturan_'+ id +'-error').text('Pengaturan harus diisi');

        $('#pengaturan_'+ id +'').keyup(function() {
          $('#pengaturan_'+ id +'').removeClass('is-invalid');
          $('#pengaturan_'+ id +'-error').text('');
        });
      } else {
        $('[name="id_pengaturan"]').val('').trigger('change');
        table.ajax.reload();
      }

    }

  }
</script>
