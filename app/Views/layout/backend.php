<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <?= $this->renderSection("title") ?>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?= base_url('template/node_modules/bootstrap/dist/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('template/node_modules/@fortawesome/fontawesome-free/css/all.css') ?>">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="<?= base_url('template/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('template/node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') ?>">
  <!-- CSS Libraries -->
  <link rel="stylesheet" href="<?= base_url('template/node_modules/izitoast/dist/css/iziToast.min.css') ?>">

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?= base_url('template/assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('template/assets/css/components.css') ?>">
  <link rel="stylesheet" href="<?= base_url('template/assets/css/custom.css') ?>">


  <!-- JQuery -->
  <script src="<?= base_url('template/node_modules/jquery/dist/jquery.min.js') ?>"></script>
  <script src="<?= base_url('template/node_modules/nicescroll/dist/jquery.nicescroll.js') ?>"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

  <!-- Data Tables -->
  <script src="<?= base_url('template/node_modules/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
  <script src="<?= base_url('template/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
  <script src="<?= base_url('template/node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js') ?>"></script>

  <style>
    .btn-read {
      pointer-events: none;
      /* Tidak bisa diklik */
      opacity: 0.5;
      /* Tampilan redup seperti disabled */
      cursor: not-allowed;
      /* Kursor larangan */
      position: relative;
      /* Untuk posisi ikon */
    }

    .btn-read .lock-icon {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 1rem;
      /* Ukuran ikon */
      color: #ffffff;
      /* Warna ikon */
    }

    .navbar-bg {
      background-color: #005328;
      /* Ganti dengan warna yang Anda inginkan */
    }

    .navbar .nav-link {
      color: #FFFFFF !important;
      /* Ganti warna teks di navbar */
    }
  </style>

</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <img alt="image" src="<?= base_url() ?>/template/assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
              <div class="d-sm-none d-lg-inline-block"><?= user()->username ?></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <?php if (logged_in()) { ?>
                <a href="/logout" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i> Logout
                <?php } else { ?>
                  <a href="/login" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i> Login
                  <?php } ?>
                  </a>
            </div>
          </li>
        </ul>
      </nav>
      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?= site_url() ?>">Akuntansi Eureeka</a>
          </div>
          <!-- <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">Akuntansi Eureeka</a>
          </div> -->
          <ul class="sidebar-menu">
            <!-- INI CARA MEMANGGIL MENU -->
            <?= $this->include("layout/menu") ?>
          </ul>
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <?= $this->renderSection("content") ?>
      </div>

      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; 2024 <div class="bullet"></div> Design By <a href="https://eureekagreatnusantara.com/">Eureeka Great Nusantara</a>
        </div>
        <div class="footer-right">
          2.3.0
        </div>
      </footer>
    </div>
  </div>

  <!-- General JS Scripts -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="<?= base_url('template/node_modules/bootstrap/dist/js/bootstrap.min.js') ?>"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

  <script src="<?= base_url('template/assets/js/stisla.js') ?>"></script>

  <!-- JS Library -->
  <script src="<?= base_url('template/node_modules/izitoast/dist/js/iziToast.min.js') ?>"></script>
  <script src="<?= base_url('template/node_modules/sweetalert/dist/sweetalert.min.js') ?>"></script>

  <!-- Template JS File -->
  <script src="<?= base_url('template/assets/js/scripts.js') ?>"></script>
  <script src="<?= base_url('template/assets/js/custom.js') ?>"></script>

  <!-- Page Specific JS File -->
  <?= $this->renderSection("pageScript") ?>

</body>

</html>