<?= $this->extend("layout/backend") ?>;

<?= $this->section("content") ?>
<title>Akuntansi Eureeka &mdash; Users</title>
<?= $this->endSection(); ?>

<?= $this->section("content") ?>

<!-- <section class="section">
  <div class="section-header">
    <h1>APA INI</h1> -->
<!-- <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
  </div> -->

<!-- untuk menangkap session success dengan bawaan with -->

<?php if (session()->getFlashdata('Sukses')) : ?>
  <div class="alert alert-success alert-dismissible show fade">
    <div class="alert-body">
      <button class="close" data-dismiss="alert">
        <span>&times;</span>
      </button>
      <?= session()->getFlashdata('Sukses') ?>
    </div>
  </div>
<?php endif; ?>

<div class="section-body">
  <div class="alert alert-info" style="background-color:#005328;">
    <h5>Informasi Aktivasi</h5>
    <p>
      <strong>Password:</strong> <?= $password ?><br>
      <strong>Kode Aktivasi:</strong> <?= $kode_aktivasi ?>
    </p>
  </div>
</div>

<div class="section-body">
  <!-- HALAMAN DINAMIS -->
  <div class="card">
    <div class="card-header">
      <h4>Data Pengguna</h4>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-md" id="myTable">
          <thead>
            <tr>
              <th>No</th>
              <th>Username</th>
              <th>Email</th>
              <th>Role</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            <?php foreach ($users as $user) : ?>
              <tr>
                <td><?= $i++ ?></td>
                <td><?= $user->username ?></td>
                <td><?= $user->email ?></td>
                <td><?= $user->name ?></td>

                <td class="text-center">
                  <!-- Tombol Edit Data -->
                  <a href="<?= site_url('user/edit/' . $user->userid) ?>" class="btn btn-warning"><i class="fas fa-pencil-alt btn-small"></i> Edit</a>
                </td>
                <!-- <td><a href="#" class="btn btn-secondary">Detail</a></td> -->
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</div>
</section>

<?= $this->endSection(); ?>