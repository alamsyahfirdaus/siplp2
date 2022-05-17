<form class="user" action="" method="post" id="form">
    <div id="response"></div>
    <div class="form-group">
        <input type="password" class="form-control form-control-user" id="password1" name="password1" placeholder="Password" autocomplete="off" style="font-size: 14px;">
        <span id="error-password" class="error invalid-feedback" style="display: none;"></span>
    </div>
    <div class="form-group">
        <input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Konfirmasi Password (Ulangi)" autocomplete="off" style="font-size: 14px;">
        <span id="error-password" class="error invalid-feedback" style="display: none;"></span>
    </div>
    <button type="submit" class="btn btn-user btn-block" id="btn-submit">Reset Password</button>
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
          password1: {
            required: true,
            minlength: 6
          },
          password2: {
            required: true,
            equalTo: "#password1"
          },
        },
        messages: {
          password1: {
            required: "Password harus diisi",
            minlength: "Password minimal 6 karakter"
          },
          password2: {
            required: "Konfirmasi Password harus diisi",
            equalTo: "Konfirmasi Password tidak sama"
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
            url : "<?= site_url('login/update/' . @$id_pengguna) ?>",
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
                    $('#form')[0].reset();
                    var icon    = 'error';
                    var message = 'GAGAL MENGUBAH PASSWORD';
                    swal_fire(icon, message);
                }
            }
        });
    }
</script>