<?= $this->extend("layout/backend") ?>

<?= $this->section("content") ?>

<section class="section">
  <div class="section-header">
    <!-- <h1>Pembelian</h1> -->
    <a href="<?= site_url('transaksi/pembelian/pembelian') ?>" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="section-body">
    <!-- HALAMAN DINAMIS -->
    <div class="card">
      <div class="card-header">
        <h4>Transaksi Pembelian</h4>
      </div>
      <div class="card-body">
        <form id="formPembelian">
          <?= csrf_field() ?>

          <div class="row">
            <div class="col-lg-2">
              <div class="form-group">
                <!-- Tanggal -->
                <label>Tanggal</label>
                <input type="date" class="form-control form-control-sm" name="tanggal" value="<?= old('tanggal') ?>" required>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <!-- Supplier -->
                <label>Supplier</label>
                <select class="form-control form-control-sm" name="id_setupsupplier" required>
                  <option value="" hidden>-- Pilih Supplier --</option>
                  <?php foreach ($dtsetupsupplier as $key => $value) : ?>
                    <option value="<?= esc($value->id_setupsupplier) ?>" <?= old('id_setupsupplier') == $value->id_setupsupplier ? 'selected' : '' ?>>
                      <?= esc($value->kode . ' - ' . $value->nama) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-lg-1">
              <div class="form-group">
                <!-- TOP -->
                <label>TOP</label>
                <input type="text" class="form-control form-control-sm" name="TOP" value="<?= old('TOP') ?>" required>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                <!-- Tanggal Jatuh Tempo -->
                <label>Tanggal Jatuh Tempo</label>
                <input type="date" class="form-control form-control-sm" name="tgl_jatuhtempo" value="<?= old('tgl_jatuhtempo') ?>" readonly>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                <!-- Tanggal Invoice -->
                <label>Tanggal Invoice</label>
                <input type="date" class="form-control form-control-sm" name="tgl_invoice" value="<?= old('tgl_invoice') ?>" required>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                <!-- No Invoice -->
                <label>No Invoice</label>
                <input type="text" class="form-control form-control-sm" name="no_invoice" value="<?= old('no_invoice') ?>" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Nota</label>
                <input type="text" class="form-control form-control-sm" name="nota" value="<?= old('nota') ?>" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Lokasi</label>
                <select class="form-control form-control-sm" name="id_lokasi" required>
                  <option value="" hidden>-- Pilih Lokasi --</option>
                  <?php foreach ($dtlokasi as $key => $value) : ?>
                    <option value="<?= esc($value->id_lokasi) ?>" <?= old('id_lokasi') == $value->id_lokasi ? 'selected' : '' ?>>
                      <?= esc($value->kode_lokasi . ' - ' . $value->nama_lokasi) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="responsive-table" style="width: 100%; overflow-x: auto;">
              <table class="table table-bordered table-sm w-100" id="tabelDetail">
                <thead>
                  <tr>
                    <th style="width: 100px;">Stock#</th>
                    <th style="width: auto; min-width: 200px;">Nama Stock</th>
                    <th style="width: 100px;">Satuan</th>
                    <th style="width: 60px;">Qty1</th>
                    <th style="width: 60px;">Qty2</th>
                    <th style="width: 160px;">Hrg.Sat</th>
                    <th style="width: 160px;">Jml.Harga</th>
                    <th style="width: 60px;">Dis.1(%)</th>
                    <th style="width: 160px;">Dis.1(Rp.)</th>
                    <th style="width: 60px;">Dis.2(%)</th>
                    <th style="width: 160px;">Dis.2(Rp.)</th>
                    <th style="width: 160px;">Total</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <input name="detail[0][id_stock]" hidden>
                      <input name="detail[0][kode]" class="form-control form-control-sm">
                      <input name="detail[0][conv_factor]" hidden class="form-control form-control-sm">
                    </td>
                    <td><input name="detail[0][nama_barang]" class="form-control form-control-sm" readonly></td>
                    <td><input name="detail[0][satuan]" class="form-control form-control-sm" readonly></td>
                    <td><input name="detail[0][qty1]" class="form-control form-control-sm"></td>
                    <td><input name="detail[0][qty2]" class="form-control form-control-sm"></td>
                    <td><input name="detail[0][harga_satuan]" class="form-control form-control-sm" readonly></td>
                    <td><input name="detail[0][jml_harga]" class="form-control form-control-sm" readonly></td>
                    <td><input name="detail[0][disc_1_perc]" class="form-control form-control-sm"></td>
                    <td><input name="detail[0][disc_1_rp]" class="form-control form-control-sm"></td>
                    <td><input name="detail[0][disc_2_perc]" class="form-control form-control-sm"></td>
                    <td><input name="detail[0][disc_2_rp]" class="form-control form-control-sm"></td>
                    <td><input name="detail[0][total]" class="form-control form-control-sm" readonly></td>
                    <td><button type="button" class="btn btn-danger btnRemove">X</button></td>
                  </tr>
                </tbody>
              </table>
              <button type="button" class="btn btn-sm btn-primary" id="btnAddRow">Tambah Baris</button>
            </div>
          </div>
          <div class="row mt-3 justify-content-between">
            <div class="col-md-4">
              <div class="form-group">
                <label>Rekening</label>
                <select class="form-control" name="id_setupbuku" required>
                  <option value="" hidden>-- Pilih Rekening --</option>
                  <?php foreach ($dtrekening as $key => $value) : ?>
                    <option value="<?= esc($value->id_setupbuku) ?>" <?= old('id_setupbuku') == $value->id_setupbuku ? 'selected' : '' ?>>
                      <?= esc($value->kode_setupbuku . '-' . $value->nama_setupbuku) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Sub Total</label>
                <input type="text" id="sub_total" class="form-control form-control-sm" name="sub_total" value="<?= number_format(old('sub_total') ?: 0, 0, ',', '.') ?>" readonly>
              </div>
              <div class="form-row">
                <div class="form-group col-lg-6">
                  <input type="number" id="disc_cash" class="form-control form-control-sm " name="disc_cash" placeholder="Discount cash %" value="<?= old('disc_cash') ?>">
                </div>
                <div class="form-group col-lg-6">
                  <input type="text" class="form-control form-control-sm" id="disc_cash_amount" name="disc_cash_amount" value="<?= number_format(old('disc_cash_amount') ?: 0, 0, ',', '.') ?>">
                </div>
              </div>
              <div class="form-group">
                <label>DPP</label>
                <input type="text" class="form-control form-control-sm" readonly name="dpp">
              </div>

              <div class="form-row justify-content-between">
                <div class="form-group col-lg-4 ">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="ppn_option" value="exclude" checked> Exclude
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="ppn_option" value="include"> Include
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="ppn_option" value="non_ppn"> Non PPN
                  </div>
                </div>
                <div class="form-group col-lg-6">
                  <label>PPN (%)</label>
                  <input type="number" id="ppn" class="form-control form-control-sm" name="ppn" value="<?= old('ppn') ?>">
                </div>
              </div>
              <div class="form-group">
                <label>Grand Total</label>
                <input type="text" id="grand_total" class="form-control form-control-sm" name="grand_total" value="<?= number_format(old('grand_total') ?: 0, 0, ',', '.') ?>" readonly>
              </div>

              <div class="form-group">
                <label>Tunai</label>
                <input type="text" id="tunai" class="form-control form-control-sm" name="tunai" value="<?= number_format(old('tunai') ?: 0, 0, ',', '.') ?>">
              </div>

              <div class="form-group">
                <label>Hutang</label>
                <input type="text" id="hutang" class="form-control form-control-sm" name="hutang" value="<?= number_format(old('hutang') ?: 0, 0, ',', '.') ?>" readonly>
              </div>
            </div>
          </div>

          <div class="form-group">
            <button type="reset" class="btn btn-danger mr-3">Reset</button>
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>


<script>
  // Document ready handler
  $(document).ready(function() {
    // Initialize form components
    initFormHandlers();
    activateAutocomplete();
    initializeDateHandling();
    initPpnOptionHandling();
  });


  /**
   * Handle toggling of the PPN input field based on the selected PPN option
   */
  function initPpnOptionHandling() {
    // Initial setup when page loads
    togglePpnField();

    // Add event listener for radio button changes
    $('input[name="ppn_option"]').on('change', function() {
      togglePpnField();
    });

    function togglePpnField() {
      const ppnOption = $('input[name="ppn_option"]:checked').val();
      const ppnInput = $('#ppn');

      if (ppnOption === 'non_ppn') {
        // Disable and clear PPN input when "Non PPN" is selected
        ppnInput.prop('disabled', true);
        ppnInput.val(0);
      } else {
        // Enable PPN input for "Include" or "Exclude" options
        ppnInput.prop('disabled', false);

        // Set default PPN value (11%) if it's empty
        if (!ppnInput.val()) {
          ppnInput.val(11);
        }
      }

      // Update totals when PPN option changes
      updateTotals();
    }
  }

  /**
   * Initialize date calculation for payment terms
   */
  function initializeDateHandling() {
    const tanggalInput = document.querySelector('input[name="tanggal"]');
    const jatuhTempoInput = document.querySelector('input[name="tgl_jatuhtempo"]');
    const topInput = document.querySelector('input[name="TOP"]');

    // Set up event listeners
    if (tanggalInput && jatuhTempoInput && topInput) {
      tanggalInput.addEventListener('change', updateJatuhTempo);
      topInput.addEventListener('change', updateJatuhTempo);
      updateJatuhTempo(); // Initial calculation
    }

    function updateJatuhTempo() {
      if (!tanggalInput.value) return;

      try {
        // Parse the input date
        const tanggal = new Date(tanggalInput.value);

        // Get TOP value (default to 0 if not a number)
        const topValue = parseInt(topInput.value) || 0;

        // Add TOP days to the date
        tanggal.setDate(tanggal.getDate() + topValue);

        // Format the date as YYYY-MM-DD for the input
        const year = tanggal.getFullYear();
        const month = String(tanggal.getMonth() + 1).padStart(2, '0');
        const day = String(tanggal.getDate()).padStart(2, '0');

        // Update the due date field
        jatuhTempoInput.value = `${year}-${month}-${day}`;
      } catch (error) {
        console.error('Error calculating due date:', error);
      }
    }
  }

  /**
   * Set up form event handlers
   */
  function initFormHandlers() {
    // Row counter for dynamic rows
    let rowCounter = 1;

    // Add row button handler
    $('#btnAddRow').click(function() {
      addNewRow(rowCounter++);
    });

    // Remove row button handler (using event delegation)
    $(document).on('click', '.btnRemove', function() {
      $(this).closest('tr').remove();
      updateTotals(); // Recalculate totals when a row is removed
    });

    // Form submission handler
    $('#formPembelian').submit(function(e) {
      e.preventDefault();
      submitForm($(this));
    });

    // Setup calculation handlers for qty, price and discount fields
    setupCalculationHandlers();
  }

  /**
   * Add calculation handlers for inputs that affect totals
   */
  function setupCalculationHandlers() {
    const calculationFields = [
      'input[name$="[qty1]"]',
      'input[name$="[qty2]"]',
      'input[name$="[harga_satuan]"]',
      'input[name$="[disc_1_perc]"]',
      'input[name$="[disc_1_rp]"]',
      'input[name$="[disc_2_perc]"]',
      'input[name$="[disc_2_rp]"]',
    ];

    // Use event delegation for all calculation fields
    $(document).on('change keyup', calculationFields.join(', '), function() {
      const row = $(this).closest('tr');
      calculateRowTotal(row);
      updateTotals();
    });

    $('#disc_cash').on('change blur', function() {
      updateTotals();
    });

    // Handle tunai input changes
    $('#tunai').on('change blur', function() {
      // Only format when leaving the field (on blur) or when change is complete
      const input = $(this).val().trim();
      const tunaiValue = parseCurrencyValue(input);
      $(this).attr('data-raw-value', tunaiValue); // Store raw value for calculations
      $(this).val(formatCurrency(tunaiValue));
      hitung_hutang(); // Recalculate remaining debt
    }).on('focus', function() {
      // When focusing on the field, show the raw number for easier editing
      const rawValue = parseCurrencyValue($(this).val());
      $(this).val(rawValue);
    });

  }

  // Helper function to parse currency formatted values
  function parseCurrencyValue(value) {
    if (!value) return 0;
    // Remove currency symbol, thousands separators and handle decimal point
    return parseFloat(value.replace(/[^\d,.-]/g, '').replace(/\./g, '').replace(/,/g, '.')) || 0;
  }

  /**
   * Calculate totals for a specific row
   */
  function calculateRowTotal(row) {
    const qty1 = parseFloat(row.find('input[name$="[qty1]"]').val()) || 0;
    const qty2 = parseFloat(row.find('input[name$="[qty2]"]').val()) || 0;
    const hargaSatuan = parseFloat(row.find('input[name$="[harga_satuan]"]').attr('data-raw-value')) || 0;

    // Calculate initial amount
    let jumlahHarga = qty1 * hargaSatuan;
    if (qty2 > 0) {
      const convFactor = parseFloat(row.find('input[name$="[conv_factor]"]').val()) || 1;
      const hargaQty2 = hargaSatuan / convFactor;
      jumlahHarga += qty2 * hargaQty2;
    }

    row.find('input[name$="[jml_harga]"]').val(jumlahHarga);

    // Calculate discount 1
    const disc1Perc = parseFloat(row.find('input[name$="[disc_1_perc]"]').val()) || 0;
    const disc1Rp = (disc1Perc / 100) * jumlahHarga;
    row.find('input[name$="[disc_1_rp]"]').val(disc1Rp);

    // Calculate discount 2
    const disc2Perc = parseFloat(row.find('input[name$="[disc_2_perc]"]').val()) || 0;
    const disc2Rp = (disc2Perc / 100) * (jumlahHarga - disc1Rp);
    row.find('input[name$="[disc_2_rp]"]').val(disc2Rp);

    // Calculate row total
    const rowTotal = jumlahHarga - disc1Rp - disc2Rp;
    row.find('input[name$="[total]"]').val(rowTotal);

    // Format the displayed values
    formatNumericFields(row);
  }

  /**
   * Update overall form totals
   */
  function updateTotals() {
    let subTotal = 0;

    // Sum all row totals
    $('#tabelDetail tbody tr').each(function() {
      const rowTotal = parseFloat($(this).find('input[name$="[total]"]').attr('data-raw-value')) || 0;
      subTotal += rowTotal;
    });

    // Update subtotal display
    $('#sub_total').attr('data-raw-value', subTotal);
    $('#sub_total').val(formatCurrency(subTotal));

    // Calculate cash discount
    const discCashPerc = parseFloat($('#disc_cash').val()) || 0;
    const discCashAmount = (discCashPerc / 100) * subTotal;
    $('input[name="disc_cash_amount"]').val(formatCurrency(discCashAmount));

    // Calculate DPP (base for tax)
    const dpp = subTotal - discCashAmount;
    $('input[name="dpp"]').attr('data-raw-value', dpp);
    $('input[name="dpp"]').val(formatCurrency(dpp));

    // Calculate PPN based on selected option
    const ppnOption = $('input[name="ppn_option"]:checked').val();
    let ppnRate = 0;
    let grandTotal = dpp;

    if (ppnOption === 'exclude') {
      ppnRate = 11; // Assuming 11% VAT
      const ppnAmount = (ppnRate / 100) * dpp;
      grandTotal = dpp + ppnAmount;
    } else if (ppnOption === 'include') {
      ppnRate = 11; // Assuming 11% VAT
      // PPN already included in the price
    }

    // Update PPN rate display
    $('#ppn').val(ppnRate);

    // Update grand total
    $('#grand_total').attr('data-raw-value', grandTotal);
    $('#grand_total').val(formatCurrency(grandTotal));

    hitung_hutang(); // Calculate remaining debt
  }

  function hitung_hutang() {
    const grandTotal = $('#grand_total').attr('data-raw-value') || 0;
    const tunai = $('#tunai').attr('data-raw-value') || 0;
    const hutang = grandTotal - tunai;
    $('#hutang').val(formatCurrency(hutang));
  }

  /**
   * Add a new row to the table
   */
  function addNewRow(rowIndex) {
    const tr = `<tr>
            <td>
                <input name="detail[${rowIndex}][id_stock]" hidden>
                <input name="detail[${rowIndex}][kode]" class="form-control form-control-sm">
                <input name="detail[${rowIndex}][conv_factor]" hidden>
            </td>
            <td><input name="detail[${rowIndex}][nama_barang]" class="form-control form-control-sm" readonly></td>
            <td><input name="detail[${rowIndex}][satuan]" class="form-control form-control-sm" readonly></td>
            <td><input name="detail[${rowIndex}][qty1]" class="form-control form-control-sm"></td>
            <td><input name="detail[${rowIndex}][qty2]" class="form-control form-control-sm"></td>
            <td><input name="detail[${rowIndex}][harga_satuan]" class="form-control form-control-sm" readonly></td>
            <td><input name="detail[${rowIndex}][jml_harga]" class="form-control form-control-sm" readonly></td>
            <td><input name="detail[${rowIndex}][disc_1_perc]" class="form-control form-control-sm"></td>
            <td><input name="detail[${rowIndex}][disc_1_rp]" class="form-control form-control-sm"></td>
            <td><input name="detail[${rowIndex}][disc_2_perc]" class="form-control form-control-sm"></td>
            <td><input name="detail[${rowIndex}][disc_2_rp]" class="form-control form-control-sm"></td>
            <td><input name="detail[${rowIndex}][total]" class="form-control form-control-sm" readonly></td>
            <td><button type="button" class="btn btn-danger btnRemove">X</button></td>
        </tr>`;
    $('#tabelDetail tbody').append(tr);
    activateAutocomplete();
  }

  /**
   * Submit the form via AJAX
   */
  function submitForm(form) {
    // Before submitting, update hidden fields with raw values
    $('input[data-raw-value]').each(function() {
      const name = $(this).attr('name');

      // Check if this is an array field with pattern detail[index][fieldname]
      if (name.includes('[') && name.includes(']')) {
        // Extract parts: field name without closing bracket
        const lastOpenBracket = name.lastIndexOf('[');
        const lastCloseBracket = name.lastIndexOf(']');

        if (lastOpenBracket !== -1 && lastCloseBracket !== -1) {
          // Build the new name with _raw inside the last brackets
          const newName = name.substring(0, lastCloseBracket) + '_raw' + name.substring(lastCloseBracket);

          const hiddenField = $('<input>').attr({
            type: 'hidden',
            name: newName,
            value: $(this).attr('data-raw-value')
          });
          form.append(hiddenField);
        }
      } else {
        // Handle non-array fields normally
        const hiddenField = $('<input>').attr({
          type: 'hidden',
          name: name + '_raw',
          value: $(this).attr('data-raw-value')
        });
        form.append(hiddenField);
      }
    });

    $.ajax({
      url: '<?= site_url('transaksi/pembelian/pembelian') ?>',
      method: 'POST',
      data: form.serialize(),
      dataType: 'json',
      beforeSend: function() {
        // Disable submit button to prevent double submission
        form.find('button[type="submit"]').prop('disabled', true);
      },
      success: function(response) {
        if (response.success) {
          iziToast.success({
            title: 'Sukses',
            message: response.message,
            position: 'topCenter',
            titleSize: '20',
            messageSize: '20',
            layout: 2,
          });
          window.location.href = '<?= site_url('transaksi/pembelian/pembelian') ?>';
        } else {
          iziToast.error({
            title: 'Error',
            message: response.message,
            position: 'topCenter',
            titleSize: '20',
            messageSize: '20',
            layout: 2,
          });
          form.find('button[type="submit"]').prop('disabled', false);
        }
      },
      error: function(xhr, status, error) {
        alert('Error: ' + error);
        form.find('button[type="submit"]').prop('disabled', false);
      }
    });
  }

  /**
   * Activate autocomplete on stock code inputs
   */
  function activateAutocomplete() {
    $('input[name$="[kode]"]').each(function() {
      if (!$(this).hasClass('ui-autocomplete-input')) {
        const rowIndex = $(this).attr('name').match(/\d+/)[0];

        $(this).autocomplete({
          source: function(request, response) {
            $.get('<?= site_url('transaksi/pembelian/pembelian/lookup-stock') ?>', {
              term: request.term
            }, function(data) {
              if (!data || !data.length) {
                return response([]);
              }

              // Transform the data for display
              const items = data.map(item => ({
                label: `${item.kode} - ${item.nama_barang}`,
                value: item.kode,
                item: item
              }));
              response(items);
            }).fail(function() {
              response([]);
            });
          },
          minLength: 2,
          select: function(event, ui) {
            if (!ui.item) return false;

            // Fill the form fields with the selected item's data
            const row = $(this).closest('tr');
            row.find('input[name$="[id_stock]"]').val(ui.item.item.id_stock);
            row.find('input[name$="[kode]"]').val(ui.item.item.kode);
            row.find('input[name$="[nama_barang]"]').val(ui.item.item.nama_barang);

            // Set conversion factor value
            const convFactor = ui.item.item.conv_factor || 1;
            row.find('input[name$="[conv_factor]"]').val(convFactor);

            const satuanDisplay = ui.item.item.satuan_1 +
              (ui.item.item.satuan_2 ? '/' + ui.item.item.satuan_2 : '');
            row.find('input[name$="[satuan]"]').val(satuanDisplay);

            const harga_satuan_field = row.find('input[name$="[harga_satuan]"]');
            harga_satuan_field.val(formatCurrency(ui.item.item.harga_beli));
            harga_satuan_field.attr('data-raw-value', ui.item.item.harga_beli); // Store raw value


            // Calculate row amounts
            calculateRowTotal(row);
            updateTotals();

            return false; // Prevent default behavior
          }
        }).autocomplete("instance")._renderItem = function(ul, item) {
          const formattedPrice = formatCurrency(item.item.harga_beli);

          // Custom rendering of dropdown items
          return $("<li>")
            .append(`<div><strong>${item.item.kode}</strong> - ${item.item.nama_barang} <br>
                             <small>Satuan: ${item.item.satuan_1}${item.item.satuan_2 ? '/' + item.item.satuan_2 : ''}, 
                             Harga: ${formattedPrice}</small></div>`)
            .appendTo(ul);
        };
      }
    });
  }

  /**
   * Format a number as Indonesian currency
   */
  function formatCurrency(number) {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }).format(number);
  }

  /**
   * Format numeric fields in a row to display properly
   */
  function formatNumericFields(row) {
    // Format currency display fields but keep raw values for calculations
    const currencyFields = ['input[name$="[jml_harga]"]', 'input[name$="[disc_1_rp]"]',
      'input[name$="[disc_2_rp]"]', 'input[name$="[total]"]'
    ];

    currencyFields.forEach(selector => {
      const field = row.find(selector);
      const rawValue = field.val() || 0;
      if (rawValue) {
        // Store the raw value as a data attribute
        field.attr('data-raw-value', rawValue);
        // Display formatted value
        field.val(formatCurrency(rawValue));
      }
    });
  }
</script>

<?= $this->endSection(); ?>