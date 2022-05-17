<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?= $title ?></h1>
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
      <div class="col-md-12">
        <div id="response"></div>
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar <?= $title ?></h3>
          </div>
          <div class="card-body table-responsive">
            <table id="table" class="table" style="width: 100%;">
              <thead>
                <tr style="text-align: center;">
                  <th style="width: 5%;">No</th>
                  <th>Menu</th>
                  <th style="width: 5%; text-align: center;">
                    <button type="button" class="btn btn-default btn-sm" style="font-weight: bold;" onclick="add_menu();"><i class="fas fa-plus"></i></button>
                  </th>
                  <th>Sub<span style="color: #FFFFFF;">_</span>Menu</th>
                  <th style="width: 5%; text-align: center;">
                    <button type="button" class="btn btn-default btn-sm" style="font-weight: bold;" onclick="add_sub_menu();"><i class="fas fa-plus"></i></button>
                  </th>
                  <th>Hak<span style="color: #FFFFFF;">_</span>Akses</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-menu">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close cancel" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= site_url('setting/saveMenu') ?>" method="post" id="form-menu">
        <input type="hidden" name="id_menu" value="">
        <div class="modal-body">
          <div class="form-group">
            <label for="menu">Menu</label>
            <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="icon">Icon</label>
            <input type="text" class="form-control" id="icon" name="icon" placeholder="Icon" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="url_menu">Url</label>
            <input type="text" class="form-control" id="url_menu" name="url" placeholder="Url" autocomplete="off">
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

<div class="modal fade" id="modal-sub_menu">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close cancel" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= site_url('setting/saveSubMenu') ?>" method="post" id="form-sub_menu">
        <input type="hidden" name="id_sub_menu" value="">
        <div class="modal-body">
          <div class="form-group">
            <label for="sub_menu">Sub Menu</label>
            <input type="text" class="form-control" id="sub_menu" name="sub_menu" placeholder="Sub Menu" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="url">Url</label>
            <input type="text" class="form-control" id="url" name="url" placeholder="Url" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="menu_id">Menu</label>
            <select name="menu_id" id="menu_id" class="form-control select2">
              <option value="">Pilih Menu</option>
            </select>
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
  var url_table = 'setting/showMenu';

  $(function() {
    set_datatable(url_table);
    form_menu();
    form_sub_menu();
    load_menu();
  });

  function hak_akses(id_menu, id_jenis_pengguna) {
    $.ajax({
        url: "<?= site_url('setting/hakAkses') ?>",
        type: "POST",
        data: {
          id_menu: id_menu,
          id_jenis_pengguna : id_jenis_pengguna
        },
        dataType: "JSON",
        success: function(response) {
          if (id_jenis_pengguna == 1) {
            window.location.reload();
          } else {
            table.ajax.reload();
          }
        }
    });
  }

  function change_aktivasi(id_sub_menu) {
    $.ajax({
        url: "<?= site_url('setting/changeAktivasi/') ?>" + id_sub_menu,
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          window.location.reload();
        }
    });
  }

  function sort_menu(id_menu) {
    $.ajax({
        url: "<?= site_url('setting/sortMenu/') ?>" + id_menu,
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          window.location.reload();
        }
    });
  }

  function sort_sub_menu(id_sub_menu) {
        $.ajax({
        url: "<?= site_url('setting/sortSubMenu/') ?>" + id_sub_menu,
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          window.location.reload();
        }
    });
  }

  function add_menu() {
    $('#form-menu')[0].reset();
    $('.modal-title').text('Tambah ' + title);
    $('#modal-menu').modal('show');
  }

  function form_menu() {
    $.validator.setDefaults({
      submitHandler: function () {
        save_menu();
      }
    });
    var validator = $('#form-menu').validate({
      rules: {
        menu: {
          required: true,
        },
        icon: {
          required: true,
        },
      },
      messages: {
        menu: {
          required: "Menu harus diisi",
        },
        icon: {
          required: "Icon harus diisi",
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

  function edit_menu(id) {
    var menu  = $('[name="menu_'+ id +'"]').val();
    var icon  = $('[name="icon_'+ id +'"]').val();
    var url   = $('[name="url_menu_'+ id +'"]').val();

    $('#form-menu')[0].reset();
    $('[name="id_menu"]').val(id);
    $('[name="menu"]').val(menu);
    $('[name="icon"]').val(icon);
    $('[name="url"]').val(url);
    $('.modal-title').text('Edit ' + title);
    $('#modal-menu').modal('show');
  }

  function save_menu() {
    $.ajax({
        url: $('#form-menu').attr('action'),
        type: "POST",
        data: new FormData($('#form-menu')[0]),
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(response) {
          $('#modal-menu').modal('hide');
          table.ajax.reload();
          if (response.message) {
            set_flashdata(response.message);
          }
        }
    });
  }

  function load_menu() {

      $('#menu_id').find('option').not(':first').remove();

      $.getJSON('<?= site_url('setting/getMenu') ?>', function (data) {
          var option = [];
          for (let i = 0; i < data.length; i++) {
              option.push({
                  id: data[i].id_menu,
                  text: data[i].menu
              });
          }
          $('#menu_id').select2({
              data: option
          })
      });
  }

  function add_sub_menu() {
    $('#form-sub_menu')[0].reset();
    $('.modal-title').text('Tambah Sub Menu');
    $('#modal-sub_menu').modal('show');
    $('.select2').select2(null, false);
    $('.select2').val('').trigger('change');
    load_menu();
  }

  function edit_sub_menu(id) {
    var sub_menu  = $('[name="sub_menu_'+ id +'"]').val();
    var url       = $('[name="url_'+ id +'"]').val();
    var menu_id   = $('[name="menu_id_'+ id +'"]').val();

    $('#form-sub_menu')[0].reset();
    $('.modal-title').text('Edit Sub Menu');
    $('#modal-sub_menu').modal('show');
    $('[name="id_sub_menu"]').val(id);
    $('[name="sub_menu"]').val(sub_menu);
    $('[name="url"]').val(url);
    $('[name="menu_id"]').val(menu_id).trigger('change');
  }


  function delete_sub_menu(url) {
    $('[name="id_delete"]').val(url);
    $('.modal-title').text('Hapus Sub Menu' );
    $('#modal-delete').modal('show');
  }

  function form_sub_menu() {
    $.validator.setDefaults({
      submitHandler: function () {
        save_sub_menu();
      }
    });
    var validator = $('#form-sub_menu').validate({
      rules: {
        sub_menu: {
          required: true,
        },
        url: {
          required: true,
        },
        menu_id: {
          required: true,
        },
      },
      messages: {
        sub_menu: {
          required: "Menu harus diisi",
        },
        url: {
          required: "Url harus diisi",
        },
        menu_id: {
          required: "Menu harus diisi",
        },
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
         if (!$('.select2').val()) {
          $('.select2-container--default .select2-selection--single').css('border', '1px solid #dc3545');
         }
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
        if ($('.select2').val()) {
          $('.select2-container--default .select2-selection--single').css('border', '1px solid #ced4da');
        }
      }
    });

    $('.cancel').click(function() {
      validator.resetForm();
      $('.select2-container--default .select2-selection--single').css('border', '1px solid #ced4da');
    });

    $('.select2').change(function() {
      $('.select2').removeClass('is-invalid');
      $('.select2-container--default .select2-selection--single').css('border', '1px solid #ced4da');
    });

  }

  function save_sub_menu() {
    $.ajax({
        url: $('#form-sub_menu').attr('action'),
        type: "POST",
        data: new FormData($('#form-sub_menu')[0]),
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(response) {
          $('#modal-sub_menu').modal('hide');
          table.ajax.reload();
          if (response.message) {
            set_flashdata(response.message);
          }
        }
    });
  }

</script>