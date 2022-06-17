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
          <?php if ($this->session->flashdata('success')): ?>
            <?= $this->session->flashdata('success') ?>
          <?php endif ?>
          <?php if ($this->session->flashdata('error')): ?>
            <?= $this->session->flashdata('error') ?>
          <?php endif ?>
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Penilaian Mahasiswa</h5>
              <div class="card-tools">
                <a href="javascript:void(0)" class="btn btn-tool" id="btn-back"><i class="fas fa-times"></i></a>
              </div>
            </div>
            <div class="card-body" style="padding-bottom: 0px;">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="id_pengguna">Pilih Mahasiswa</label>
                    <select name="id_pengguna" id="id_pengguna" class="form-control select2">
                      <option value="">Mahasiswa</option>
                      <?php foreach ($kelompok_mahasiswa as $key) {
                        echo '<option value="'. $key->id_pengguna .'">'. $key->no_induk .' - '. $key->nama_lengkap .'</option>';
                      } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="id_penilaian">Pilih Penilaian</label>
                    <select name="id_penilaian" id="id_penilaian" class="form-control select2">
                      <option value="">Penilaian</option>
                      <?php foreach ($penilaian_mahasiswa as $key) {
                        echo '<option value="'. $key->id_penilaian .'">'. $key->penilaian .'</option>';
                      } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-8">
                  <table class="table table-responsive" style="width: 100%;">
                    <tr>
                      <td style="width: 45%; padding-top: 0px; border: none; font-weight: bold;">NIM</td>
                      <td style="width: 5%; padding-top: 0px; border: none;">:</td>
                      <td style="padding-top: 0px; border: none;" id="nim"><?= @$nim ?></td>
                    </tr>
                    <tr>
                      <td style="border: none; font-weight: bold;">Nama<span style="color: #ffffff;">_</span>Mahasiswa</td>
                      <td style="border: none;">:</td>
                      <td style="border: none;" id="nama_mahasiswa"><?= @$nama_mahasiswa ?></td>
                    </tr>
                    <tr>
                      <td style="border: none; font-weight: bold;">Penilaian</td>
                      <td style="border: none;">:</td>
                      <td style="border: none;" id="penilaian"><?= @$penilaian ?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="card-body table-responsive" style="padding-top: 0px;">
              <table id="tb_penilaian" class="table" style="width: 100%;">
                <thead>
                  <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="text-align: left;">Pernyataan</th>
                    <th style="width: 30%; text-align: center;">Skor</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <td colspan="3">
                      <form action="" method="post" id="form_selesai">
                        <div class="form-group" style="display: none;">
                          <?php 
                          $input = '<input type="text" id="id_hasil_penilaian" name="id_hasil_penilaian" value="">';
                          $input .= '<input type="text" id="tahun_pelaksanaan_id" name="tahun_pelaksanaan_id" value="'. @$tahun_pelaksanaan_id .'">';
                          $input .= '<input type="text" id="id_pengguna_penilai" name="id_pengguna_penilai" value="'. @$id_pengguna_penilai .'">';
                          $input .= '<input type="text" id="penilaian_id" name="penilaian_id" value="'. @$penilaian_id .'">';
                          $input .= '<input type="text" id="id_pengguna_mhs" name="id_pengguna_mhs" value="'. @$id_pengguna_mhs .'">';
                          foreach ($kelompok_mahasiswa as $key) {
                            $input .= '<input type="text" id="nim_'. $key->id_pengguna .'" value="'. $key->no_induk .'">';
                            $input .= '<input type="text" id="nama_mahasiswa_'. $key->id_pengguna .'" value="'. $key->nama_lengkap .'">';
                            $input .= '<input type="text" id="id_pengguna_mhs_'. $key->id_pengguna .'" value="'. md5($key->id_pengguna) .'">';
                          }
                          foreach ($penilaian_mahasiswa as $key) {
                            $input .= '<input type="text" id="penilaian_'. $key->id_penilaian .'" value="'. $key->penilaian .'">';
                          }
                          echo $input;
                          ?>
                        </div>
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="selesai" class="custom-control-input" id="check-selesai">
                            <label class="custom-control-label" for="check-selesai">Selesai</label>
                            <button type="submit" id="btn-submit" class="btn btn-default btn-sm float-right"><i class="fas fa-save"></i> Simpan</button>
                          </div>
                        </div>
                      </form>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
      </div>
    </div>
  </div>

</div>

<script type="text/javascript">

var table_penilaian;

$(function () {
  
  table_penilaian();
  form_selesai();

  $('[name="id_pengguna"]').change(function() {
    if ($(this).val()) {
      var nim = $('#nim_'+ $(this).val() +'').val();
      var nama_mahasiswa = $('#nama_mahasiswa_'+ $(this).val() +'').val();
      $('#nim').text(nim);
      $('#nama_mahasiswa').text(nama_mahasiswa);
      $('[name="id_pengguna_mhs"]').val($(this).val()).change();
      tb_penilaian.ajax.reload();
    } 
  });

  $('[name="id_penilaian"]').change(function() {
    if ($(this).val()) {
      var penilaian = $('#penilaian_'+ $(this).val() +'').val();
      $('#penilaian').text(penilaian);
      $('[name="penilaian_id"]').val($(this).val()).change();
      tb_penilaian.ajax.reload();
    } 
  });

  $('#btn-back').click(function() {
    var id_pengguna = $('[name="id_pengguna"]').val() ? $('[name="id_pengguna"]').val() : $('[name="id_pengguna_mhs"]').val();
    var id_pengguna_mhs = $('#id_pengguna_mhs_'+ id_pengguna +'').val();
    window.location.href = '<?= site_url('teacher/examination/') ?>' + id_pengguna_mhs;
  });

});


function table_penilaian() {
  tb_penilaian = $('#tb_penilaian').DataTable({
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
      "url": "<?= site_url('teacher/show_rating/' . md5(time())) ?>",
      "type": "POST",
      "data": function(data) {
        data.penilaian_id         = $('[name="penilaian_id"]').val();
        data.tahun_pelaksanaan_id = $('[name="tahun_pelaksanaan_id"]').val();
        data.id_pengguna_mhs      = $('[name="id_pengguna_mhs"]').val();
        data.id_pengguna_penilai  = $('[name="id_pengguna_penilai"]').val();
      },
    },
    "drawCallback": function(settings) {
     $('#id_hasil_penilaian').val(settings.json.id_hasil_penilaian);
     if (settings.json.selesai == 1) {
       $('#check-selesai').attr('disabled', true);
       $('#btn-submit').attr('disabled', true);
     } else {
      $('#check-selesai').removeAttr('disabled');
      $('#btn-submit').removeAttr('disabled');
     }
     if (settings.json.checked == 1) {
      $('#check-selesai').attr('checked', true);
     } else {
      $('#check-selesai').removeAttr('checked');
      $('#form_selesai').attr('action', '<?= site_url('teacher/update_selesai/') ?>' + settings.json.checked);
     }
    },
    "columnDefs": [{ 
      "targets": [0],
      "orderable": false,
    }],
  });
}

function form_selesai() {
  $('#form_selesai').validate({
    rules: {
      selesai: {
        required: true
      },
    },
    messages: {
      selesai: ""
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
}

function add_data(skor, idp, ihp) {
  $.ajax({
    url: '<?= site_url('teacher/save_skor/' . md5(time())) ?>',
    type: 'POST',
    dataType: 'json',
    data: {
      detail_penilaian_id: idp,
      tahun_pelaksanaan_id: $('[name="tahun_pelaksanaan_id"]').val(),
      id_pengguna_mhs: $('[name="id_pengguna_mhs"]').val(),
      id_pengguna_penilai: $('[name="id_pengguna_penilai"]').val(),
      skor: skor,
      id_hasil_penilaian: ihp,
    },
    success: function(response) {
      tb_penilaian.ajax.reload();
    }
  });
}

</script>