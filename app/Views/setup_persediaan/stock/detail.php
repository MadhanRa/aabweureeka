<div class="modal fade" tabindex="-1" role="dialog" id="modalDetail">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table w-auto table-sm">
                    <tr>
                        <td><strong>Kode Stock</strong></td>
                        <td><?= $dtstock->kode ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Barang</strong></td>
                        <td><?= $dtstock->nama_barang ?></td>
                    </tr>
                    <tr>
                        <td><strong>Group</strong></td>
                        <td><?= $dtstock->nama_group ?></td>
                    </tr>
                    <tr>
                        <td><strong>Kelompok</strong></td>
                        <td><?= $dtstock->nama_kelompok ?></td>
                    </tr>
                    <tr>
                        <td><strong>Supplier</strong></td>
                        <td><?= $dtstock->nama_supplier ?></td>
                    </tr>
                    <tr>
                        <td><strong>Satuan 1 / Satuan 2</strong></td>
                        <td><?= $dtstock->kode_satuan . '/' . $dtstock->kode_satuan2 ?></td>
                    </tr>
                    <tr>
                        <td><strong>Conv Factor</strong></td>
                        <td><?= $dtstock->conv_factor ?></td>
                    </tr>
                    <tr>
                        <td><strong>Min Stock</strong></td>
                        <td><?= $dtstock->min_stock ?></td>
                    </tr>
                </table>
                <div class="table-wrapper table-responsive">
                    <table class="table table-striped table-bordered mt-3" id="myTable">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Lokasi</th>
                                <th><?= $dtstock->kode_satuan ?></th>
                                <th><?= $dtstock->kode_satuan2 ?></th>
                                <th>Jml Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dtlokasi as $key => $data) : ?>
                                <tr>
                                    <td><?= $data->kode_lokasi ?></td>
                                    <td><?= $data->nama_lokasi ?></td>
                                    <td><?= $data->qty1 ?></td>
                                    <td><?= $data->qty2 ?></td>
                                    <td><?= number_format($data->jml_harga, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>