<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $this->mall->getSetting(2)['nama'] ?></title>
  <?php 
    $this->load->view('section/css');
    $query = $this->mall->getUser();
  ?>
</head>
<?php $this->load->view('section/js'); ?>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav id="navbar" class="main-header navbar navbar-expand-md navbar-light navbar-dark">
    <div class="container">
      <a href="<?= base_url() ?>" class="navbar-brand">
        <img src="<?= $this->mall->getSetting(4)['gambar'] ?>" alt="" class="brand-image img-circle">
        <span class="brand-text font-weight-light" style="color: #FFFFFF; font-weight: bold;"><?= $this->mall->getSetting(1)['nama'] ?></span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation" style="color: #FFFFFF">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">

          <?php 

          $menu = $this->db->join('menu', 'menu.id_menu = akses_menu.menu_id', 'left')->where('jenis_pengguna_id', $this->session->id_jenis_pengguna)->order_by('sort_by', 'asc')->get('akses_menu')->result();

          foreach ($menu as $m): ?>
            <?php if (!$this->db->get_where('sub_menu', ['menu_id' => $m->id_menu])->num_rows()): ?>
                <li class="nav-item">
                  <a href="<?= base_url($m->url) ?>" class="nav-link <?php if($m->menu == @$folder || $m->menu == @$title) echo 'active' ?>"><i class="nav-icon <?= $m->icon ?>"></i> <?= $m->menu ?></a>
                </li>
            <?php else: ?>
            <li class="nav-item dropdown">
              <a id="menu<?= $m->id_menu ?>" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle <?php if($m->menu == @$folder || $m->menu == @$title) echo 'active' ?>"><i class="nav-icon <?= $m->icon ?>"></i> <?= $m->menu ?></a>
              <ul aria-labelledby="menu<?= $m->id_menu ?>" class="dropdown-menu border-0 shadow">
                <?php
                $sub_menu = $this->db->order_by('sort_by', 'asc')->get_where('sub_menu', [
                  'menu_id'   =>  $m->id_menu,
                  'aktivasi'  => 1,
                ])->result();
                $no = 1;
                foreach ($sub_menu as $sm): ?>
                  <li>
                    <a href="<?= base_url($m->url .'/'. $sm->url) ?>" class="dropdown-item"><?= $sm->sub_menu ?></a>
                  </li>

                  <?php if ($no < count($sub_menu)): ?>
                    <li class="dropdown-divider"></li>
                  <?php endif ?>
                  <?php $no++ ?>

                <?php endforeach ?>
              </ul>
            </li>
            <?php endif ?>
          <?php endforeach ?>

        </ul>

      </div>

      <!-- Right navbar links -->
      <ul class="order-md-3 navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)" style="padding-top: 4px; color: #FFFFFF;">

            <?php if (@$query->foto_profil): ?>
              <img src="<?= base_url(IMAGE . $this->include->image(@$query->foto_profil)) ?>" alt="" class="img-circle" style="width: 34px; height: 34px; margin-top: auto; margin-bottom: auto; vertical-align: middle;">
            <?php else: ?>
                <?php
                $nama_lengkap = explode(' ', $query->nama_lengkap);
                $foto_profile = isset($nama_lengkap[0]) ? substr(strtoupper($nama_lengkap[0]), 0, 1) : '';
                $foto_profile .= isset($nama_lengkap[1]) ? substr(strtoupper($nama_lengkap[1]), 0, 1) : '';
                ?>
                <span id="foto_profile" style="width: 34px; height: 34px; font-size: 14px;"><?= $foto_profile ?></span>
            <?php endif ?>
          </a>
          <div class="dropdown-menu dropdown-menu dropdown-menu-right">
            <!-- <a href="<?= base_url('home/profile') ?>" class="dropdown-item"><i class="fas fa-user-alt mr-2"></i> Profile</a>
            <div class="dropdown-divider"></div> -->
            <a href="<?= base_url('logout') ?>" class="dropdown-item"><i class="fas fa-sign-out-alt mr-2"></i> Keluar</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?= @$content ?>
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark"></aside>
  <!-- /.control-sidebar -->
  <?php $this->load->view('section/footer'); ?>
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <b>Version</b> 1.0
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2021-<?= date('Y') ?> <a href="javascript:void(0)" style="color: #869099;"><?= $this->mall->getSetting(3)['nama'] ?></a>.</strong>
  </footer>
</div>
<!-- ./wrapper -->

</body>
</html>
