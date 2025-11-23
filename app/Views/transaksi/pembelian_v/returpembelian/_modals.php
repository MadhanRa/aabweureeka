<!-- Tempat modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalNotaPembelian" data-nota-url="<?= site_url('transaksi/pembelian/pembelian/') ?>">
    <input type="hidden" id="modal_nota_csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cari Nota Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-md" id="myTableNotaPembelian">
                        <thead>
                            <tr class="eureeka-table-header">
                                <th>Tanggal</th>
                                <th>Nota</th>
                                <th>Supplier</th>
                                <th>Tgl. Jatuh Tempo</th>
                                <th>No. Invoice</th>
                                <th>Hutang</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- Tempat modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalTambahItem" data-item-url="<?= site_url('setup_persediaan/stock/lookup-stock') ?>">
    <input type="hidden" id="modal_item_csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Item Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-md" id="myTableItem" width="100%">
                        <thead>
                            <tr class="eureeka-table-header">
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Group</th>
                                <th>Kelompok</th>
                                <th>Supplier</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- Tempat modal lookup -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalLookupReturPembelian" data-lookup-url="<?= site_url('transaksi/pembelian/returpembelian/lookup-returpembelian') ?>">
    <input type="hidden" id="modal_lookup_csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lookup Retur Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-md" id="myTableLookup" width="100%">
                        <thead>
                            <tr class="eureeka-table-header">
                                <th>Tanggal</th>
                                <th>Nota</th>
                                <th>Supplier</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>