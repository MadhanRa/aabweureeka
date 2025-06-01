$(document).ready(function () {
    // Initialize form components
    initFormHandlers();
    activateAutocomplete();
    initializeDateHandling();
    initPpnOptionHandling();
});

const SELECTORS = {
    ppnOption: 'input[name="ppn_option"]',
    kodeInput: 'input[name$="[kode]"]',
    form: '#formPenjualan',
    tabelDetail: '#tabelDetail tbody',
};

/**
 * Handle toggling of the PPN input field based on the selected PPN option
 */
function initPpnOptionHandling() {
    handlePPNInputToggle();
    // Add event listener for radio button changes
    $(SELECTORS.ppnOption).on('change', handlePPNInputToggle);
    // Add event listener for PPN input changes
    $('#ppn').on('blur', handlePPNInputToggle);
}

function handlePPNInputToggle() {

    const ppnOption = $(SELECTORS.ppnOption + ':checked').val();
    const ppnInput = $('#ppn');

    if (ppnOption === 'non_ppn') {
        // Disable and clear PPN input when "Non PPN" is selected
        ppnInput.prop('readonly', true).val(0);
    } else {
        // Enable PPN input for "Include" or "Exclude" options
        ppnInput.prop('readonly', false);
    }
    updateProductPrices(ppnOption);

    updateTotals();
}

function updateProductPrices(ppnOption) {
    $(SELECTORS.tabelDetail + ' tr').each(function () {
        const row = $(this);
        let newPrice;

        // Get the appropriate price based on the option
        if (ppnOption === 'exclude' || ppnOption === 'non_ppn') {
            newPrice = parseFloat(row.find('input[name$="[harga_satuan_exclude]"]').val()) || 0;
        } else {
            newPrice = parseFloat(row.find('input[name$="[harga_satuan_include]"]').val()) || 0;
        }

        // Update the visible price field
        row.find('input[name$="[harga_satuan]"]')
            .val(formatCurrency(newPrice))
            .attr('data-raw-value', newPrice);

        // Recalculate row total with the new price
        calculateRowTotal(row);
    });
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
            const topValue = parseInt(topInput.value) || 0;
            tanggal.setDate(tanggal.getDate() + topValue);
            jatuhTempoInput.value = formatDate(tanggal);
        } catch (error) {
            console.error('Error calculating due date:', error);
        }
    }
}

function formatDate(date) {
    // Format the date as YYYY-MM-DD for the input
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

/**
 * Set up form event handlers
 */
function initFormHandlers() {
    // Row counter for dynamic rows
    let rowCounter = 1;
    $('#btnAddRow').click(() => addNewRow(rowCounter++));

    // Remove row button handler (using event delegation)
    $(document).on('click', '.btnRemove', function () {
        $(this).closest('tr').remove();
        updateTotals();
    });

    // Form submission handler
    $(SELECTORS.form).submit(function (e) {
        e.preventDefault();
        submitForm($(this));
    });

    attachCalculationEvents();
}

/**
 * Add calculation handlers for inputs that affect totals
 */
function attachCalculationEvents() {
    const calculationFields = [
        'input[name$="[qty1]"]',
        'input[name$="[qty2]"]',
        'input[name$="[disc_1_perc]"]',
        'input[name$="[disc_1_rp]"]',
        'input[name$="[disc_2_perc]"]',
        'input[name$="[disc_2_rp]"]',
    ];

    // Use event delegation for all calculation fields
    $(document).on('blur', calculationFields.join(', '), function () {
        const row = $(this).closest('tr');
        calculateRowTotal(row);
        updateTotals();
    });
}

function parseCurrencyValue(value) {
    if (value == 0) return 0;
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
        jumlahHarga += qty2 * (hargaSatuan / convFactor);
    }

    row.find('input[name$="[jml_harga]"]').val(jumlahHarga);

    // calculate discounts
    const disc1 = computeDiscount(row, 1, jumlahHarga);

    if (disc1 > 0) {
        row.find('input[name$="[disc_2_perc]"]').prop('readonly', false);
        row.find('input[name$="[disc_2_rp]"]').prop('readonly', false);
    } else {
        row.find('input[name$="[disc_2_perc]"]').prop('readonly', true).val(0);
        row.find('input[name$="[disc_2_rp]"]').prop('readonly', true).val(0);
    }

    const disc2 = computeDiscount(row, 2, jumlahHarga - disc1);

    // Calculate row total
    const rowTotal = jumlahHarga - disc1 - disc2;
    row.find('input[name$="[total]"]').val(rowTotal);

    formatNumericFields(row);
}

function computeDiscount(row, num, base) {
    const perc = parseFloat(row.find(`input[name$="[disc_${num}_perc]"]`).val()) || 0;
    const rpField = row.find(`input[name$="[disc_${num}_rp]"]`);
    const rp = parseCurrencyValue(rpField.val() || 0);

    if (num === 1) {
        // Reset disabled status first
        row.find(`input[name$="[disc_${num}_rp]"]`).prop('readonly', false);
        row.find(`input[name$="[disc_${num}_perc]"]`).prop('readonly', false);
    }

    if (perc > 0) {
        row.find(`input[name$="[disc_${num}_rp]"]`).prop('readonly', true);
        return (perc / 100) * base;
    } else if (rp > 0) {
        row.find(`input[name$="[disc_${num}_perc]"]`).prop('readonly', true);
        return rp;
    }
    return 0;
}

/**
 * Format numeric fields in a row to display properly
 */
function formatNumericFields(row) {
    // Format currency display fields but keep raw values for calculations
    ['[jml_harga]', '[disc_1_rp]',
        '[disc_2_rp]', '[total]'
    ].forEach(suffix => {
        const field = row.find(`input[name$="${suffix}"]`);
        let val = 0;
        if (field.val().includes('Rp')) {
            val = parseCurrencyValue(field.val())
        } else {
            val = parseFloat(field.val()) || 0;
        }
        field.attr('data-raw-value', val).val(formatCurrency(val));
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
 * Update overall form totals
 */
function updateTotals() {
    let subTotal = 0;

    // Sum all row totals
    $(SELECTORS.tabelDetail + ' tr').each(function () {
        subTotal += parseFloat($(this).find('input[name$="[total]"]').attr('data-raw-value')) || 0;
    });

    // Update subtotal display
    $('#sub_total').attr('data-raw-value', subTotal).val(formatCurrency(subTotal));

    // Calculate cash discount
    const discCashPerc = parseFloat($('#disc_cash').val()) || 0;
    const discCashAmount = (discCashPerc / 100) * subTotal;
    $('#disc_cash_rp').val(formatCurrency(discCashAmount)).attr('data-raw-value', discCashAmount);

    handleDiscountExclusivity();

    // Calculate Netto (base for tax)
    const netto = subTotal - discCashAmount;
    $('input[name="netto"]').attr('data-raw-value', netto).val(formatCurrency(netto));

    // Calculate PPN based on selected option
    const ppnOption = $(SELECTORS.ppnOption + ':checked').val();
    let ppnRate = $('#ppn').val() || 0;
    let grandTotal = netto;

    if (ppnOption === 'exclude') grandTotal += (ppnRate / 100) * netto;

    // Update grand total
    $('#grand_total').attr('data-raw-value', grandTotal).val(formatCurrency(grandTotal));

}

/**
 * Handle exclusivity between percentage and rupiah discount inputs
 */
function handleDiscountExclusivity() {
    const discCashPerc = parseFloat($('#disc_cash').val()) || 0;
    const discCashRp = ($('#disc_cash_rp').val().includes('Rp')) ? parseCurrencyValue($('#disc_cash_rp').val() || 0) : parseFloat($('#disc_cash_rp').val() || 0);

    if (discCashPerc == 0 && discCashRp == 0) {
        $('#disc_cash').val(0);
        $('#disc_cash_rp').val(0).attr('data-raw-value', 0);
    }
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
            <td>
            <input name="detail[${rowIndex}][harga_satuan]" class="form-control form-control-sm" readonly>
                <input name="detail[${rowIndex}][harga_satuan_include]" type="hidden">
                <input name="detail[${rowIndex}][harga_satuan_exclude]" type="hidden">
            </td>
            <td><input name="detail[${rowIndex}][jml_harga]" class="form-control form-control-sm" readonly></td>
            <td><input name="detail[${rowIndex}][disc_1_perc]" class="form-control form-control-sm"></td>
            <td><input name="detail[${rowIndex}][disc_1_rp]" class="form-control form-control-sm"></td>
            <td><input name="detail[${rowIndex}][disc_2_perc]" class="form-control form-control-sm" readonly></td>
            <td><input name="detail[${rowIndex}][disc_2_rp]" class="form-control form-control-sm" readonly></td>
            <td><input name="detail[${rowIndex}][total]" class="form-control form-control-sm" readonly></td>
            <td><button type="button" class="btn btn-danger btnRemove">X</button></td>
        </tr>`;
    $(SELECTORS.tabelDetail).append(tr);
    activateAutocomplete();
}

/**
 * Submit the form via AJAX
 */
function submitForm(form) {
    // Before submitting, update hidden fields with raw values
    $('input[data-raw-value]').each(function () {
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
        url: form.attr('action'),
        method: 'POST',
        data: form.serialize(),
        dataType: 'json',
        beforeSend: function () {
            // Disable submit button to prevent double submission
            form.find('button[type="submit"]').prop('disabled', true);
        },
        success: res => handleAjaxResponse(res, form),
        error: function (xhr, status, error) {
            alert('Error: ' + error);
            form.find('button[type="submit"]').prop('disabled', false);
        }
    });
}

function handleAjaxResponse(response, form) {
    if (response.success) {
        iziToast.success({
            title: 'Sukses',
            message: response.message,
            position: 'topCenter',
            titleSize: '20',
            messageSize: '20',
            layout: 2,
        });
        window.location.href = response.redirect_url;
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
}


/**
 * Activate autocomplete on stock code inputs
 */
function activateAutocomplete() {
    const url = $(SELECTORS.form).data('stock-url');

    $(SELECTORS.kodeInput).each(function () {
        if ($(this).hasClass('ui-autocomplete-input')) return;

        $(this).autocomplete({
            source: function (req, res) {
                $.get(url, {
                    term: req.term,
                }, data => {
                    if (!data || !data.length) return res([]);

                    // Transform the data for display
                    res(data.map(item => ({
                        label: `${item.kode} - ${item.nama_barang}`,
                        value: item.kode,
                        item: item
                    })));
                }).fail(() => res([]));
            },
            minLength: 2,
            select: function (event, ui) {
                if (!ui.item) return false;
                const ppnOption = $(SELECTORS.ppnOption + ':checked').val();
                // Fill the form fields with the selected item's data
                const row = $(this).closest('tr');
                fillAutoCompleteFields(row, ui.item.item, ppnOption);
                calculateRowTotal(row);
                updateTotals();
                return false; // Prevent default behavior
            }
        }).autocomplete("instance")._renderItem = (ul, item) => {
            const ppnOption = $(SELECTORS.ppnOption + ':checked').val();
            let hargaJual = item.item.harga_jualexc
            if (ppnOption === 'include') {
                hargaJual = item.item.harga_jualinc;
            }

            return $("<li>")
                .append(`
                <div><strong>${item.item.kode}</strong> - ${item.item.nama_barang} <br>
                <small>Satuan: ${item.item.satuan_1}${item.item.satuan_2 ? '/' + item.item.satuan_2 : ''},
                Harga: ${formatCurrency(hargaJual)}</small></div>`)
                .appendTo(ul);
        }
    });
}

function fillAutoCompleteFields(row, item, ppnOption) {
    row.find('input[name$="[harga_satuan_include]"]').val(item.harga_jualinc || 0);
    row.find('input[name$="[harga_satuan_exclude]"]').val(item.harga_jualexc || 0);

    let hargaJual = item.harga_jualexc
    if (ppnOption === 'include') {
        hargaJual = item.harga_jualinc;
    }

    row.find('input[name$="[id_stock]"]').val(item.id_stock);
    row.find('input[name$="[kode]"]').val(item.kode);
    row.find('input[name$="[nama_barang]"]').val(item.nama_barang);
    row.find('input[name$="[conv_factor]"]').val(item.conv_factor || 1);
    row.find('input[name$="[satuan]"]').val(item.satuan_1 + (item.satuan_2 ? '/' + item.satuan_2 : ''));
    row.find('input[name$="[harga_satuan]"]').val(formatCurrency(hargaJual)).attr('data-raw-value', hargaJual);
    row.find('input[name$="[disc_1_perc]"]').val(0).attr('data-raw-value', 0);
    row.find('input[name$="[disc_1_rp]"]').val(0).attr('data-raw-value', 0);
    row.find('input[name$="[disc_2_perc]"]').val(0).attr('data-raw-value', 0);
    row.find('input[name$="[disc_2_rp]"]').val(0).attr('data-raw-value', 0);
    // Clear quantity fields
    row.find('input[name$="[qty1]"]').val(0);
    row.find('input[name$="[qty2]"]').val(0);
}