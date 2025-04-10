<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('penyesuaianstock') ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Edit Transaksi Penyesuaian Stock</h4>
            </div>
            <div class="card-body">
                <form method="post" action="<?= site_url('penyesuaianstock/' . $dtpenyesuaianstock->id_penyesuaianstock) ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="<?= old('tanggal', $dtpenyesuaianstock->tanggal) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Nota</label>
                        <input type="text" class="form-control" name="nota" value="<?= old('nota', $dtpenyesuaianstock->nota) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Rekening</label>
                        <select class="form-control" name="id_setupbank" required>
                            <option value="" hidden>-- Pilih Rekening --</option>
                            <?php foreach ($dtsetupbank as $key => $value) : ?>
                                <option value="<?= esc($value->id_setupbank) ?>" <?= old('id_setupbank', $dtpenyesuaianstock->id_setupbank) == $value->id_setupbank ? 'selected' : '' ?>>
                                    <?= esc($value->nama_setupbank) ?>
                                </option>
                            <?php endforeach; ?>    
                        </select>    
                    </div>

                    <div class="form-group">
                        <label>Lokasi</label>
                        <select class="form-control" name="id_lokasi" required>
                            <option value="" hidden>-- Pilih Lokasi --</option>
                            <?php foreach ($dtlokasi as $key => $value) : ?>
                                <option value="<?= esc($value->id_lokasi) ?>" <?= old('id_lokasi', $dtpenyesuaianstock->id_lokasi) == $value->id_lokasi ? 'selected' : '' ?>>
                                    <?= esc($value->nama_lokasi) ?>
                                </option>
                            <?php endforeach; ?>    
                        </select>    
                    </div>

                    <div class="form-group">
                        <label>Group</label>
                        <select class="form-control" name="id_group" required>
                            <option value="" hidden>-- Pilih Group --</option>
                            <?php foreach ($dtgroup as $key => $value) : ?>
                                <option value="<?= esc($value->id_group) ?>" <?= old('id_group', $dtpenyesuaianstock->id_group) == $value->id_group ? 'selected' : '' ?>>
                                    <?= esc($value->nama_group) ?>
                                </option>
                            <?php endforeach; ?>    
                        </select>    
                    </div>

                    <div class="form-group">
                        <label>Nama Stock</label>
                        <input type="text" class="form-control" name="nama_stock" value="<?= old('nama_stock', $dtpenyesuaianstock->nama_stock) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Satuan</label>
                        <select class="form-control" name="id_satuan" required>
                            <option value="" hidden>-- Pilih Satuan --</option>
                            <?php foreach ($dtsatuan as $key => $value) : ?>
                                <option value="<?= esc($value->id_satuan) ?>" <?= old('id_satuan', $dtpenyesuaianstock->id_satuan) == $value->id_satuan ? 'selected' : '' ?>>
                                    <?= esc($value->kode_satuan) ?>
                                </option>
                            <?php endforeach; ?>    
                        </select>    
                    </div>

                    <div class="form-group">
                        <label>Saldo (Qty1/Qty2)</label>
                        <input type="text" class="form-control" name="saldo" value="<?= old('saldo', $dtpenyesuaianstock->saldo) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Qty 1(F)</label>
                        <input type="number" class="form-control" name="qty_1" value="<?= old('qty_1', $dtpenyesuaianstock->qty_1) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Qty 2(F)</label>
                        <input type="number" class="form-control" name="qty_2" value="<?= old('qty_2', $dtpenyesuaianstock->qty_2) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Penyesuaian</label>
                        <input type="text" class="form-control" name="penyesuaian" value="<?= old('penyesuaian', $dtpenyesuaianstock->penyesuaian) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" class="form-control" name="keterangan" value="<?= old('keterangan', $dtpenyesuaianstock->keterangan) ?>" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Update Data</button>
                        <a href="<?= site_url('penyesuaianstock') ?>" class="btn btn-danger">Batal</a>
                    </div>
                </form>          
            </div>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>
