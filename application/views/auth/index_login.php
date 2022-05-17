<form class="user" action="" method="post" id="form">
    <div class="form-group">
        <input type="text" class="form-control form-control-user" id="id_pengguna" name="id_pengguna" placeholder="Akun Pengguna" autocomplete="off" style="font-size: 14px;">
        <span id="error-id_pengguna" class="error invalid-feedback" style="display: none;"></span>
    </div>
    <div class="form-group">
        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" autocomplete="off" style="font-size: 14px;">
        <span id="error-password" class="error invalid-feedback" style="display: none;"></span>
    </div>
    <button type="submit" class="btn btn-user btn-block" id="btn-submit">Login</button>
</form>

<?php $tahun_pelaksanaan = $this->db->get_where('tahun_pelaksanaan', [
    'status_pelaksanaan'    => 1,
    'pendaftaran_mahasiswa' => 1
])->row() ?>

<?php if ($tahun_pelaksanaan): ?>
<hr style="margin-bottom: 8px;">
<div class="text-center">
    <a href="<?= site_url('register') ?>" style="font-weight: bold; text-decoration: none; color: #3A3B45; font-size: 14px;">Daftar Peserta PLP II</a>
</div>
<hr style="margin-top: 8px;">
<?php else: ?>   
<hr>
<?php endif ?>

<div class="text-center">
    <a class="small" href="javascript:void(0)" onclick="forgot_password();">Lupa Password?</a>
</div>

<script type="text/javascript">
    $(function () {
      $.validator.setDefaults({
        submitHandler: function () {
          submit_form();
        }
      });
      $('#form').validate({
        rules: {
          id_pengguna: {
            required: true,
          },
          password: {
            required: true,
          },
        },
        messages: {
          id_pengguna: {
            required: "Akun Pengguna harus diisi",
          },
          password: {
            required: "Password harus diisi",
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

    });

    function submit_form() {
        $.ajax({
            url : "<?= site_url('login') ?>",
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(response) {
                if (response.status) {
                    window.location.href = "<?= site_url('home') ?>";
                } else {
                    if (response.errors) {
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
                    } else {
                        $('#form')[0].reset();
                        var icon = 'error';
                        swal_fire(icon, response.message);
                    }

                }
            }
        });
    }

</script>
