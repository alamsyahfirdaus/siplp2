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
              <a href="<?= site_url('user/add/'. $jenis_pengguna_id) ?>" class="btn btn-default btn-sm"><i class="fas fa-plus"></i> Tambah</a>
            </div>
          </div>
          <div class="card-body table-responsive">
            <table id="table" class="table" style="width: 100%;">
              <thead>
                <tr>
                  <th style="width: 5%;">No</th>
                  <?= $thead ?>
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

<form action="" method="post" id="form" enctype="multipart/form-data" style="display: none;">
  <input type="text" name="id_pengguna" value="">
  <input type="text" name="email" value="">
  <input type="text" name="telepon" value="">
  <input type="text" name="jenis_pengguna_id" value="">
  <input type="text" name="foto_profil" value="">
  <input type="file" name="foto" value="">
</form>

<div class="modal fade" id="modal-foto">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group" id="form-conf" style="display: none;">
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <button type="button" id="btn-cancel" class="btn btn-secondary btn-flat"><i class="fas fa-angle-double-left"></i> Tidak</button>
            </span>
            <input type="text" class="form-control" value="Konfirmasi Hapus?" disabled="" style="font-weight: bold; background-color: #FFC107; border: 1px solid #EDB100; color: #333333;">
            <span class="input-group-append">
              <button type="button" id="btn-yes" class="btn btn-danger btn-flat" style="font-weight: bold;"><i class="fas fa-angle-double-right"></i> Ya</button>
            </span>
          </div>
        </div>
        <div class="form-group">
          <img id="image" src="" alt="" style="width: 100%; display: block;">
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger btn-sm" id="btn-conf" style="font-weight: bold;"><i class="fas fa-trash"></i> Hapus</button>
        <button type="button" id="btn-upload" class="btn btn-default btn-sm"><i class="fas fa-image"></i> Upload</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var url_table = 'user/showUser/<?= $jenis_pengguna_id ?>';

  $(function () {
    set_datatable(url_table);

    $('#btn-upload').click(function() {
      $('[name="foto"]').click();
      $('#modal-foto').modal('hide');
    });

    $('[name="foto"]').change(function() {
      if ($(this).val()) {
        $('[name="foto_profil"]').val('').change();
        save_image($('[name="id_pengguna"]').val());
      }
    });

    $('#btn-conf').click(function(event) {
      $('#form-conf').show();
    });

    $('#btn-cancel').click(function() {
      $('#form-conf').hide();
    });

    $('#btn-yes').click(function() {
      $('#modal-foto').modal('hide');
      save_image($('[name="id_pengguna"]').val());
    });


  });

  function upload_image(id) {
    var jenis_pengguna_id = $('#jenis_pengguna_id_'+ id +'').val();
    var email = $('#email_'+ id +'').val();
    var telepon = $('#telepon_'+ id +'').val();

    $('[name="id_pengguna"]').val(id).change();
    $('[name="jenis_pengguna_id"]').val(jenis_pengguna_id).change();
    $('[name="email"]').val(email).change();
    $('[name="telepon"]').val(telepon).change();
    $('[name="foto_profil"]').val('').change();
    $('[name="foto"]').click();
  }

  function save_image(id) {
    $.ajax({
        url: "<?= site_url('user/savePengguna/') ?>" + id,
        type: "POST",
        data: new FormData($('#form')[0]),
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(response) {
          var message = $('[name="foto_profil"]').val() ? 'Berhasil Menghapus Foto ' + title : 'Berhasil Mengubah Foto ' + title;
          set_flashdata(message);
          setTimeout(function() {
            if (response.id_pengguna == '<?= md5($this->session->id_pengguna) ?>') {
              window.location.reload();
            } else {
              $('#form')[0].reset();
              table.ajax.reload();
            }
          }, 3525);
        }
    });
  }

  function delete_image(id) {
    var jenis_pengguna_id = $('#jenis_pengguna_id_'+ id +'').val();
    var email = $('#email_'+ id +'').val();
    var telepon = $('#telepon_'+ id +'').val();
    var foto_profil = $('#foto_profil_'+ id +'').val();

    $('[name="id_delete"]').val('').change();
    $('.modal-title').text('Hapus Foto');
    $('#modal-delete').modal('show');
    $('[name="id_pengguna"]').val(id).change();
    $('[name="jenis_pengguna_id"]').val(jenis_pengguna_id).change();
    $('[name="email"]').val(email).change();
    $('[name="telepon"]').val(telepon).change();
    $('[name="foto_profil"]').val(foto_profil).change();
  }

  function foto_profile(id) {
    var jenis_pengguna_id = $('#jenis_pengguna_id_'+ id +'').val();
    var email             = $('#email_'+ id +'').val();
    var telepon           = $('#telepon_'+ id +'').val();
    var foto_profil       = $('#foto_profil_'+ id +'').val();
    var image             = $('#image_'+ id +'').val();

    $('[name="id_pengguna"]').val(id).change();
    $('[name="jenis_pengguna_id"]').val(jenis_pengguna_id).change();
    $('[name="email"]').val(email).change();
    $('[name="telepon"]').val(telepon).change();
    $('[name="foto_profil"]').val(foto_profil).change();
    $('#image').attr('src', image);
    $('.modal-title').text('Foto ' + title);
    $('#modal-foto').modal('show');
    $('#form-conf').hide();
  }

</script>