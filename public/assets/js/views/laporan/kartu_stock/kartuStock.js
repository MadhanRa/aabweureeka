function pilihStock(id) {
    const url = $('#modalCariStock').data('stock-url');
    // Fungsi untuk mencari nota pembelian dan autofill data
    $.ajax({
        url: url + id,
        method: 'GET',
        dataType: 'json',
        beforeSend: function () {
            // Show loading indicator
            $('#btn_cari_' + id).html('<i class="fa fa-spinner fa-spin"></i>');
        },
        success: function (data) {
            if (data.status) {
                const dataStock = data.data;
                $('input[name="id_stock"]').val(dataStock.id_stock);
                $('input[name="kode_stock"]').val(dataStock.kode);
                $('input[name="nama_stock"]').val(dataStock.nama_barang);
                $('input[name="isi_stock"]').val(dataStock.conv_factor);

                // kembalikan button ke semula
                $('#btn_cari_' + id).html('pilih');

                // Close modal
                $('#modalCariStock').modal('hide');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching stock:', error);
        }
    });
}