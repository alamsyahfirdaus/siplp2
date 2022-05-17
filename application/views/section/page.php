<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?= $this->mall->getSetting(4)['gambar'] ?>">
  <title><?= $this->mall->getSetting(2)['nama'] ?></title>
  <?php 
    $this->load->view('section/css');
    $query = $this->mall->getUser();
    $nama_lengkap = explode(' ', $query->nama_lengkap);
    $foto_profil = isset($nama_lengkap[0]) ? substr(strtoupper($nama_lengkap[0]), 0, 1) : '';
    $foto_profil .= isset($nama_lengkap[1]) ? substr(strtoupper($nama_lengkap[1]), 0, 1) : '';
  ?>
</head>
<?php $this->load->view('section/js'); ?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav id="navbar" class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="javascript:void(0)" role="button" style="color: #FFFFFF;"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <?php if ($this->session->id_jenis_pengguna != 1): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)" style="padding-top: 4px; color: #FFFFFF;">
            <?php if (@$query->foto_profil): ?>
              <img src="<?= base_url(IMAGE . $this->include->image(@$query->foto_profil)) ?>" alt="" class="img-circle" style="max-width: 34px; max-height: 34px; margin-top: auto; margin-bottom: auto; vertical-align: middle;">
            <?php else: ?>
                <span id="foto_profile" style="width: 34px; height: 34px; font-size: 14px;"><?= $foto_profil ?></span>
            <?php endif ?>
          </a>
          <div class="dropdown-menu dropdown-menu dropdown-menu-right">
            <a href="<?= base_url('home/profile') ?>" class="dropdown-item"><i class="fas fa-user-alt mr-2"></i> Profile</a>
            <div class="dropdown-divider"></div>
            <a href="<?= base_url('logout') ?>" class="dropdown-item"><i class="fas fa-sign-out-alt mr-2"></i> Keluar</a>
          </div>
        </li>
      <?php endif ?>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-light elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url() ?>" class="brand-link">
      <img src="<?= $this->mall->getSetting(4)['gambar'] ?>" alt="" class="brand-image img-circle" style="max-width: 34px; max-height: 34px; margin-top: auto; margin-bottom: auto; vertical-align: middle;">
      <span class="brand-text font-weight-light" style="color: #FFFFFF; font-weight: bold;" title=""><?= $this->mall->getSetting(1)['nama'] ?></span>
    </a>

    <!-- Sidebar -->

    <div class="sidebar">
      <div class="user-panel mt-2 pb-3 mb-3 d-flex">
        <!-- <div class="image">
          <i class="fas fa-user-alt fa-2x mt-2" style="color: #FFFFFF;"></i>
        </div> -->
        <div class="image" style="padding-top: 8px;">
          <?php if (isset($query->foto_profil)): ?>
            <img src="<?= base_url(IMAGE . $this->include->image($query->foto_profil)) ?>" class="img-circle elevation-2" alt="" style="width: 34px; height: 34px;">
          <?php else: ?>
            <span id="foto_profile" style="width: 34px; height: 34px; font-size: 14px;"><?= $foto_profil ?></span>
          <?php endif ?>
        </div>
        <div class="info">
          <span style="font-size: 14px; display: block;"><a href="javascript:void(0)" style="color: #FFFFFF; font-weight: bold;"><?= substr(ucwords(@$query->nama_lengkap), 0, 20) ?></a></span>
          <span style="font-size: 12px; color: #FFFFFF; display: block;"><?= @$query->jenis_pengguna ?></span>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <?php 
          $menu = $this->db->join('menu', 'menu.id_menu = akses_menu.menu_id', 'left')->where('jenis_pengguna_id', $this->session->id_jenis_pengguna)->order_by('sort_by', 'asc')->get('akses_menu')->result();
          foreach ($menu as $m): ?>
            <?php if (!$this->db->get_where('sub_menu', ['menu_id' => $m->id_menu])->num_rows()): ?>
                <li class="nav-item">
                  <a href="<?= base_url($m->url) ?>" class="nav-link <?php if($m->menu == @$folder || $m->menu == @$title) echo 'active' ?>">
                    <i class="nav-icon <?= $m->icon ?>"></i>
                    <p><?= $m->menu ?></p>
                  </a>
                </li>
            <?php else: ?>
              <li class="nav-item <?php if($m->menu == @$folder) echo 'menu-open' ?>">
                <a href="javascript:void(0)" class="nav-link <?php if($m->menu == @$folder) echo 'active' ?>">
                  <i class="nav-icon <?= $m->icon ?>"></i>
                  <p><?= $m->menu ?><i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <?php 
                  $sub_menu = $this->db->order_by('sort_by', 'asc')->get_where('sub_menu', [
                    'menu_id'   =>  $m->id_menu,
                    'aktivasi'  => 1,
                  ])->result();
                  foreach ($sub_menu as $sm): ?>
                    <li class="nav-item">
                      <a href="<?= base_url($m->url .'/'. $sm->url) ?>" class="nav-link <?php if($sm->sub_menu == @$title) echo 'active' ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p><?= $sm->sub_menu ?></p>
                      </a>
                    </li>
                  <?php endforeach ?>
                </ul>
              </li>
            <?php endif ?>
          <?php endforeach ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?= $content ?>
  </div>
  <!-- /.content-wrapper -->
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
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark"></aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
</body>
</html>
