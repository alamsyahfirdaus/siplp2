<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?= $this->mall->getSetting(4)['gambar'] ?>">
    <title><?= $this->mall->getSetting(2)['nama'] ?></title>
    <link href="<?= base_url('assets/auth/') ?>all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="<?= base_url('assets/auth/') ?>sb-admin-2.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= base_url('assets/auth/') ?>bootstrap-4.min.css">
</head>
<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/auth/') ?>jquery.min.js"></script>
<script src="<?= base_url('assets/auth/') ?>bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/auth/') ?>jquery.easing.min.js"></script>
<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/auth/') ?>sb-admin-2.min.js"></script>
<!-- jquery-validation -->
<script src="<?= base_url('assets/js/') ?>jquery.validate.min.js"></script>
<script src="<?= base_url('assets/js/') ?>additional-methods.min.js"></script>
<!-- SweetAlert2 -->
<script src="<?= base_url('assets/auth/') ?>sweetalert2.min.js"></script>
<body class="bg-gradient">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card o-hidden border-0 shadow-lg" style="margin-top: 100px;">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-4">
                                    <div class="text-center">
                                        <img src="<?= $this->mall->getSetting(4)['gambar'] ?>" class="mb-3" alt="" style="width: 75px;">
                                        <h1 class="h4 text-gray-900 mb-3" style="font-weight: bold;"><?= $this->mall->getSetting(2)['nama'] ?></h1>
                                    </div>
                                    <?= $content ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
            $('#form input').keyup(function() {
                $(this).removeClass('is-invalid');
                $(this).nextAll('.help-block').text('');
            });
        });

        function set_flashdata(type, message) {
            $(window).scrollTop(0);
            $('<div class="alert '+ type +' alert-dismissible">' + message + '</div>').show().appendTo('#response');
             $('.alert').delay(2750).slideUp('slow', function() {
                $(this).remove();
            });
        }

        function swal_fire(icon, title) {
            Swal.fire({
                icon: icon,
                title: '<span style="font-size: 14.2px; font-weight: bold;">'+ title +'</span>',
                showConfirmButton: false,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                },
                timer: 1750
            });
        }

        function logged_in() {
            window.location.href = '<?= site_url('login') ?>';
        }

        function forgot_password() {
            window.location.href = '<?= site_url('login/recovery') ?>';
        }
    </script>

    <style type="text/css">
        body {
            font-family: arial;
            background-color: #222284;
        }
        .error.invalid-feedback {
            font-size: 12.8px;
            line-height: 19.2px;
            color: #E74A3B;
        }
        #btn-submit {
            font-size: 14px;
            font-weight: bold;
            box-shadow: none;
            background-color: #222284; 
            border: 1px solid #222284;
            color: #FFFFFF;
        }
        a.small {
            text-decoration: none;
            font-size: 14px;
            color: #3A3B45;
            font-weight: bold;
        }
        .form-control:focus {
            color: #6e707e;
            background-color: #fff;
            border-color: #12a650;
            outline: 0;
            box-shadow: none;
            /*box-shadow: 0 0 0 0.2rem rgb(78 115 223 / 25%);*/
        }
        .form-control.is-invalid:focus, .was-validated .form-control:invalid:focus {
            border-color: #e74a3b;
            box-shadow: none;
            /*box-shadow: 0 0 0 0.2rem rgb(231 74 59 / 25%);*/
        }
        .alert-success {
            background-color: #00A65A;
            color: #FFFFFF;
            font-weight: bold;
        }
        .alert-danger {
            background-color: #DC3545;
            color: #FFFFFF;
            font-weight: bold;
        }
        #btn-register {
            font-size: 14px;
            font-weight: bold;
            box-shadow: none;
            background-color: #222284; 
            border: 1px solid #222284;
            color: #FFFFFF;
        }
    </style>
    
</body>
</html>