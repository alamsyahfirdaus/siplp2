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
      <div class="col-md-8">
        <div id="response"></div>
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar <?= $title ?></h3>
            <div class="float-right">
              <a href="javascript:void(0)" onclick="add_edit();" class="btn btn-default btn-sm"><i class="fas fa-plus"></i> Tambah</a>
            </div>
          </div>
          <div class="card-body table-responsive">
            <table id="table" class="table" style="width: 100%;">
              <thead>
                <tr>
                  <th style="width: 5%; text-align: center;">No</th>
                  <th style="width: 35%; text-align: left;">Sekolah</th>
                  <th style="text-align: left;">Alamat</th>
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

<div class="modal fade" id="modal-form">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close cancel" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="post" id="form">
        <input type="text" name="id_sekolah_mitra" value="" style="display: none;">
        <div class="modal-body">
          <div class="form-group">
            <label for="nama_sekolah">Sekolah<span style="color: #dc3545; font-weight: bold;">*</span></label>
            <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" placeholder="Sekolah" autocomplete="off">
            <span id="error-nama_sekolah" class="error invalid-feedback" style="display: none;"></span>
          </div>
          <div class="form-group">
            <label for="alamat_sekolah">Alamat</label>
            <textarea class="form-control" rows="3" id="alamat_sekolah" name="alamat_sekolah" placeholder="Alamat" autocomplete="off"></textarea>
            <span id="error-alamat_sekolah" class="error invalid-feedback" style="display: none;"></span>
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

<script type="text/javascript">

  $(function () {
    var url_table = 'master/show_school/<?= md5(time()) ?>';
    set_datatable(url_table);

    $.validator.setDefaults({
      submitHandler: function () {
        save_data();
      }
    });

    var validator = $('#form').validate({
      rules: {
        nama_sekolah: {
          required: true,
        },
      },
      messages: {
        nama_sekolah: {
          required: "Sekolah harus diisi",
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

  });

  function add_edit(id) {
    $('#form')[0].reset();

    if (id) {
      var nama_sekolah    = $('#nama_sekolah_'+ id +'').text();
      var alamat_sekolah  = $('#alamat_sekolah_'+ id +'').val();

      $('.modal-title').text('Edit ' + title);
      $('[name="id_sekolah_mitra"]').val(id).change();
      $('[name="nama_sekolah"]').val(nama_sekolah).change();
      $('[name="alamat_sekolah"]').val(alamat_sekolah).change();
    } else {
      $('.modal-title').text('Tambah ' + title);
    }

    $('#modal-form').modal('show');
  }

  function save_data() {
    $.ajax({
        url: '<?= site_url('master/save_school/' . md5(time())) ?>',
        type: 'POST',
        data: new FormData($('#form')[0]),
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
          if (response.status) {
            $('#modal-form').modal('hide');
            table.ajax.reload();
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

</script>