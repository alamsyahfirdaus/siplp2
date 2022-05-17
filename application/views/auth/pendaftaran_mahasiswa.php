<form class="user" action="" method="post" id="form">
    <div id="response"></div>
    <div class="form-group">
        <input type="text" class="form-control form-control-user" id="no_induk" name="no_induk" placeholder="NIM" autocomplete="off" style="font-size: 14px;">
        <span id="error-no_induk" class="error invalid-feedback" style="display: none;"></span>
    </div>
    <div class="form-group">
        <input type="text" class="form-control form-control-user" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" autocomplete="off" style="font-size: 14px;">
        <span id="error-nama_lengkap" class="error invalid-feedback" style="display: none;"></span>
    </div>
    <div class="form-group">
        <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Email" autocomplete="off" style="font-size: 14px;">
        <span id="error-email" class="error invalid-feedback" style="display: none;"></span>
    </div>
    <div class="form-group">
        <input type="password" class="form-control form-control-user" id="password1" name="password1" placeholder="Password" autocomplete="off" style="font-size: 14px;">
        <span id="error-password1" class="error invalid-feedback" style="display: none;"></span>
    </div>
    <div class="form-group">
        <input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Konfirmasi Password (Ulangi)" autocomplete="off" style="font-size: 14px;">
        <span id="error-password2" class="error invalid-feedback" style="display: none;"></span>
    </div>
    <button type="submit" class="btn btn-user btn-block" id="btn-submit">Daftar Peserta PLP II</button>
</form>
<hr>
<div class="text-center">
    <a class="small" href="javascript:void(0)" onclick="logged_in();">Sudah punya akun? Login!</a>
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
          no_induk: {
            required: true,
          },
          nama_lengkap: {
            required: true,
          },
          email: {
            required: true,
            email: true,
          },
          password1: {
            required: true,
            minlength: 8,
          },
          password2: {
            required: true,
            equalTo: "#password1",
          },
        },
        messages: {
          no_induk: {
            required: "NIM harus diisi",
          },
          nama_lengkap: {
            required: "Nama Lengkap harus diisi",
          },
          email: {
            required: "Email harus diisi",
            email: "Email tidak valid"
          },
          password1: {
            required: "Password harus diisi",
            minlength: "Password minimal 8 karakter",
          },
          password2: {
            required: "Konfirmasi Password harus diisi",
            equalTo: "Konfirmasi Password tidak sama",
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
            url : "<?= site_url('login/add_student/' . md5(time())) ?>",
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(response) {
                if (response.status) {
                  var icon = 'success';
                  swal_fire(icon, response.message);
                  setTimeout(function(){
                      window.location.href = '<?= site_url('login') ?>';
                  }, 2350);
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