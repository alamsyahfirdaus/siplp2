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
              <a href="javascript:void(0)" onclick="hasil_penilaian();" class="btn btn-default btn-sm"><i class="fas fa-print"></i> Cetak</a>
            </div>
          </div>
          <div class="card-body table-responsive">
            <div class="form-group row">
              <label for="program_studi_id" class="col-sm-2 col-form-label">Program Studi</label>
              <div class="col-sm-3">
                <select id="program_studi_id" class="form-control select2">
                  <option value="">-- Program Studi --</option>
                  <?php foreach ($program_studi as $row) {
                    echo '<option value="'. md5($row->id_program_studi) .'">'. $row->program_studi .'</option>';
                  } ?>
                </select>
                <span id="error-program_studi_id" class="error invalid-feedback" style="display: none;"></span>
              </div>
            </div>
            <table id="tb_hasil_penilaian" class="table" style="width: 100%;">
              <thead>
                <tr>
                  <th style="width: 5%; text-align: center;">No</th>
                  <th style="width: 15%; text-align: center;">NIM</th>
                  <th>Nama<span style="color: #ffffff;">_</span>Mahasiswa</th>
                  <th>Program<span style="color: #ffffff;">_</span>Studi</th>
                  <th style="text-align: center;">Skor</th>
                  <th style="text-align: center;">Bobot</th>
                  <th style="text-align: center;">Nilai</th>
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

<form action="<?= site_url('evaluation/print') ?>" method="post" id="form" target="_blank" style="display: none;">
  <input type="text" name="tahun_pelaksanaan_id" value="<?= $tahun_pelaksanaan_id ?>">
  <input type="text" name="id_pengguna_mhs" value="">
  <input type="text" name="program_studi_id" value="">
</form>

<script type="text/javascript">
  var tb_hasil_penilaian;
  $(function () {    
    tb_hasil_penilaian = $('#tb_hasil_penilaian').DataTable({
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
        "url": "<?= site_url('evaluation/show_score/'. md5(time())) ?>" ,
        "type": "POST",
        "data": function(data) {
            data.tahun_pelaksanaan_id = $('[name="tahun_pelaksanaan_id"]').val();
            data.program_studi_id = $('[name="program_studi_id"]').val();
        },
      },
      "drawCallback": function(settings) {
        $('[name="id_pengguna_mhs"]').val(settings.json.id_pengguna_mhs).change();
      },
      "columnDefs": [{ 
        "targets": [0],
        "orderable": false,
      }],
    });

    $('#program_studi_id').change(function() {
      if ($(this).val()) {
        $('[name="program_studi_id"]').val($(this).val()).change();
      } else {
        $('[name="program_studi_id"]').val('').change();
      }
      tb_hasil_penilaian.ajax.reload();
    });

  });

  function hasil_penilaian() {
    if ($('[name="program_studi_id"]').val()) {
      if ($('[name="id_pengguna_mhs"]').val()) {
        $('#form').submit();
      } else {
        $('#response').html('');
        var message = 'Gagal Mencetak Hasil Penilaian';
        var type    = 'alert-danger';
        set_flashdata(message, type);
      }
    } else {
      $('#program_studi_id').addClass('is-invalid');
      $('#error-program_studi_id').text('Program Studi harus dipilih').show();
      $('#program_studi_id').change(function(event) {
        if($(this).val()) {
          $('#program_studi_id').removeClass('is-invalid');
          $('#error-program_studi_id').text('').hide();
        }
      });
    }
  }

</script>