<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?= $title ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <!-- <li class="breadcrumb-item"><a href="<?= site_url() ?>"><?= @$folder ?></a></li>
          <li class="breadcrumb-item active"><?= @$title ?></li> -->
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <?php if ($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" style="font-weight: bold;"><?= $this->session->flashdata('success') ?></div>
        <?php endif ?>
        <div id="response"></div>
      </div>
    </div>
    
  </div>
</div>