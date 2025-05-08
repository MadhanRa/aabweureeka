<script>
  // Document ready handler
  $(document).ready(function() {
    // Initialize form components
    initFormHandlers();
    initializeFormData();
    activateAutocomplete();
    initializeDateHandling();
    initPpnOptionHandling();
  });

  /**
   * Set up form event handlers
   */
  function initFormHandlers() {
    // Row counter for dynamic rows
    let rowCounter = <?= count($dtdetail) ?>; // Start from the last row index

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

  /**
   * Initialize form data when page loads
   */
  function initializeFormData() {
    // Format currency fields in header section
    formatHeaderFields();

    // Format and calculate row totals for existing rows
    $('#tabelDetail tbody tr').each(function() {
      formatRowData($(this));
    });

    // Calculate overall totals
    updateTotals();
  }

  /**
   * Format header currency fields
   */
  function formatHeaderFields() {
    const currencyFields = [
      '#sub_total',
      '#disc_cash_amount',
      'input[name="dpp"]',
      '#grand_total',
      '#tunai',
      '#hutang'
    ];

    currencyFields.forEach(selector => {
      const field = $(selector);
      const rawValue = parseFloat(field.val().replace(/[^\d,-]/g, '')) || 0;

      // Store raw value for calculations
      field.attr('data-raw-value', rawValue);

      // Format display value
      field.val(formatCurrency(rawValue));
    });

    // Initialize disc_cash_amount based on subtotal and disc_cash percent
    const subTotal = parseFloat($('#sub_total').attr('data-raw-value')) || 0;
    const discCashPerc = parseFloat($('#disc_cash').val()) || 0;
    const discCashAmount = (discCashPerc / 100) * subTotal;
    $('input[name="disc_cash_amount"]').val(formatCurrency(discCashAmount));
  }

  /**
   * Format numeric data in a row and set data attributes
   */
  function formatRowData(row) {
    // Set raw values for price and amounts
    const hargaSatuanField = row.find('input[name$="[harga_satuan]"]');
    const rawHargaSatuan = parseFloat(hargaSatuanField.val().replace(/[^\d,-]/g, '')) || 0;
    hargaSatuanField.attr('data-raw-value', rawHargaSatuan);

    // Format currency fields in the row
    const currencyFields = [
      'input[name$="[harga_satuan]"]',
      'input[name$="[jml_harga]"]',
      'input[name$="[disc_1_rp]"]',
      'input[name$="[disc_2_rp]"]',
      'input[name$="[total]"]'
    ];

    currencyFields.forEach(selector => {
      const field = row.find(selector);
      const rawValue = parseFloat(field.val().replace(/[^\d,-]/g, '')) || 0;

      // Store raw value for calculations
      field.attr('data-raw-value', rawValue);

      // Format display value
      field.val(formatCurrency(rawValue));
    });

    // Recalculate row total based on current values
    calculateRowTotal(row);
  }
</script>