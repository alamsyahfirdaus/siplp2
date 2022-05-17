<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?= $title ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <!-- <li class="breadcrumb-item"><a href="javascript:void(0)"><?= @$folder ?></a></li> -->
          <!-- <li class="breadcrumb-item active"><?= @$title ?></li> -->
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <?php if (isset($row['id_kelompok_sekolah'])): ?>
          <?php if ($this->session->flashdata('success')): ?>
            <?= $this->session->flashdata('success') ?>
          <?php endif ?>
          <?php if ($this->session->flashdata('error')): ?>
            <?= $this->session->flashdata('error') ?>
          <?php endif ?>
        <?php endif ?>
        <div id="response"></div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <?php if (isset($row['id_kelompok_sekolah'])): ?>
            <div class="card-header">
              <a href="<?= site_url('master/setgroup') ?>" class="btn btn-default btn-sm"><i class="fas fa-plus"></i> Tambah <?= $title ?></a>
            </div>
          <?php endif ?>
          <div class="card-body table-responsive" style="padding-top: 10px;">
            <table id="add_edit" class="table" style="width: 100%;">
              <thead>
                <th style="padding-left: 0px; font-size: 17px;"><?= $header .' '. $title ?></th>
              </thead>
            </table>
          </div>
          <div class="card-footer">
            <form action="" method="post">
              <?php foreach ($this->db->list_fields('kelompok_sekolah') as $key) {
                if ($key == 'tahun_pelaksanaan_id') {
                  $val = isset($row['id_kelompok_sekolah']) ? $row[$key] : $tahun_pelaksanaan_id;
                  echo '<input type="hidden" name="'. $key .'" value="'. $val .'">';
                } else {
                  echo '<input type="hidden" name="'. $key .'" value="'. @$row[$key] .'">';
                }
              } ?>
              <a href="<?= site_url('master/group') ?>" type="button" class="btn btn-secondary btn-sm"><i class="fas fa-angle-double-left"></i> Batal</a>
              <button type="submit" id="btn-submit" class="btn btn-default btn-sm float-right"><i class="fas fa-save"></i> Simpan</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <?php if (isset($row['id_kelompok_sekolah'])): ?>
          <div class="card">
            <div class="card-header">
              <button type="button" id="add-student" class="btn btn-default btn-sm"><i class="fas fa-user-plus"></i> Tambahkan Mahasiswa</button>
            </div>
            <div class="card-body table-responsive" style="border-bottom: 1px solid #DEE2E6;">
              <table class="table" style="width: 100%;">
                <tr>
                  <td style="padding: 0px; border-top: none;">
                    <select id="id_kelompok_sekolah" class="form-control select2">
                      <option value="">Cari Kelompok Sekolah</option>
                      <?php foreach ($kelompok_sekolah as $key) {
                        echo '<option value="'. md5($key->id_kelompok_sekolah) .'">'. $key->program_studi .' - '. $key->nama_sekolah .'</option>';
                      } ?>
                    </select>
                  </td>
                  <td style="width: 5%; padding: 0px; border-top: none;">
                    <button type="button" class="btn btn-default" id="btn-search"><i class="fas fa-search"></i></button>
                  </td>
                </tr>
              </table>
            </div>
            <div class="card-body table-responsive">
              <table id="table" class="table" style="width: 100%;">
                <thead>
                  <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 25%; text-align: center;">NIM</th>
                    <th style="text-align: left;">Nama<span style="color: #ffffff;">_</span>Mahasiswa</th>
                    <th style="width: 10%; text-align: center;">Hapus</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        <?php endif ?>
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
      <div class="modal-body table-responsive">
        <div id="response1"></div>
        <table id="tbl_mhs" class="table" style="width: 100%;">
          <thead>
            <tr>
              <th style="width: 5%; text-align: center;">No</th>
              <th style="width: 25%; text-align: center;">NIM</th>
              <th style="text-align: left;">Nama<span style="color: #ffffff;">_</span>Mahasiswa</th>
              <th style="width: 10%; text-align: center;">Tambah</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var add_edit;
  var tbl_mhs;
  var url_table = 'master/show_studentgroup/<?= md5(@$row['id_kelompok_sekolah']) ?>';

  $(function() {

    form_add_edit();
    set_datatable(url_table);
    table_mahasiswa();

    $('#add-student').click(function() {
      $('.modal-title').text('Tambah Mahasiswa');
      $('#modal-form').modal('show');
      $('#response1').html('');
      tbl_mhs.ajax.reload();
    });

    $('#btn-search').click(function() {
      var id_kelompok_sekolah = $('#id_kelompok_sekolah').val();
      if (id_kelompok_sekolah) {
        window.location.href = "<?= site_url('master/setgroup/') ?>" + id_kelompok_sekolah;
      } else {
        table.ajax.reload();
        tbl_mhs.ajax.reload();
        add_edit.ajax.reload();
      }
    });

  });
  
  function form_add_edit() {

    add_edit = $('#add_edit').DataTable({
      "processing": false,
      "serverSide": true,
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": false,
      "autoWidth": false,
      "responsive": false,
      "language": { 
        "infoFiltered": "",
        "sZeroRecords": "",
        "sEmptyTable": "",
        "sSearch": "Cari:"
      },
      "ajax": {
        "url": "<?= site_url('master/show_setgroup/' . md5(@$row['id_kelompok_sekolah'])) ?>",
        "type": "POST",
        "data": function(data) {
          <?php foreach ($this->db->list_fields('kelompok_sekolah') as $key): ?>
            data.<?= $key ?> = $('[name="<?= $key ?>"]').val();
          <?php endforeach ?>
        },
      },
      "drawCallback": function(settings) {
       if (settings.json.post > 0) {
        $('#btn-submit').removeAttr('disabled');
       } else {
        $('#btn-submit').attr('disabled', true);
       }
      },
      "columnDefs": [{ 
        "targets": [0],
        "orderable": false,
      }],
    });

    <?php foreach ($this->db->list_fields('kelompok_sekolah') as $key): ?>
      $('[name="<?= $key ?>"]').change(function() {
        <?php if ($key == 'tahun_pelaksanaan_id'): ?>
          if ($(this).val()) {
            add_edit.ajax.reload();
            load_kelompok_sekolah($(this).val());
          }
        <?php elseif ($key == 'program_studi_id'): ?>
          if ($(this).val()) {
            $('#add-student').removeAttr('disabled');
          } else {
            $('#add-student').attr('disabled', true);
          }
        <?php else: ?>
          if ($(this).val()) {
            add_edit.ajax.reload();
          }
        <?php endif ?>
      });
    <?php endforeach ?>

  }

  <?php foreach ($this->db->list_fields('kelompok_sekolah') as $key): ?>
    function <?= $key ?>() {
      var <?= $key ?> = $('[name="<?= md5($key) ?>"]').val();
      $('[name="<?= $key ?>"]').val(<?= $key ?>).change();
    }
  <?php endforeach ?>

  function table_mahasiswa() {
    tbl_mhs = $('#tbl_mhs').DataTable({
      "processing": false,
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
        "sZeroRecords": "",
        "sEmptyTable": "",
        "sSearch": "Cari:"
      },
      "ajax": {
        "url": "<?= site_url('master/get_student/' . md5(@$row['id_kelompok_sekolah'])) ?>",
        "type": "POST",
        "data": function(data) {
          data.program_studi_id = $('[name="program_studi_id"]').val();
        },
      },
      "columnDefs": [{ 
        "targets": [0],
        "orderable": false,
      }],
    });

  }

  function save_mahasiswa(pengguna_id) {
    $('#response1').html('');
    $.ajax({
      url: '<?= site_url('master/save_mahasiswa/' . md5(time())) ?>',
      type: 'POST',
      dataType: 'json',
      data: {
        kelompok_sekolah_id: '<?= @$row['id_kelompok_sekolah'] ?>',
        pengguna_id: pengguna_id
      },
      success: function(response) {
        $('#response').html('');
        if (response.status) {
          table.ajax.reload();
          tbl_mhs.ajax.reload();
          $('<div class="alert alert-success alert-dismissible" style="font-weight: bold;">' + response.message + '</div>').show().appendTo('#response1');
          $('.alert').delay(2750).slideUp('slow', function(){
            $(this).remove();
          });
        }
      }
    });

  }

  function load_kelompok_sekolah(tahun_pelaksanaan_id) {
    var tpi = tahun_pelaksanaan_id ? tahun_pelaksanaan_id : md5(time());

    $('#id_kelompok_sekolah').find('option').not(':first').remove();

    $.getJSON('<?= site_url('master/get_group/') ?>' + tpi, function(data) {
        var option = [];
        for (let i = 0; i < data.length; i++) {
            option.push({
                id: data[i].id_kelompok_sekolah,
                text: data[i].kelompok_sekolah
            });
        }
        $('#id_kelompok_sekolah').select2({
            data: option
        });
    });
  }

</script>