<form class="user" action="" method="post" id="form">
    <div id="response"></div>
    <div class="form-group">
        <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Email" autocomplete="off" style="font-size: 14px;">
        <span id="error-email" class="error invalid-feedback" style="display: none;"></span>
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
          email: {
            required: true,
            email: true,
          },
        },
        messages: {
          email: {
            required: "Email harus diisi",
            email: "Email tidak valid"
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
            url : "<?= site_url('login/recovery') ?>",
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(response) {
                if (response.status) {
                  var icon = 'success';
                  swal_fire(icon, response.message);
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