<?php

namespace App\ValueObjects;

class DetailItem
{
    public float $qty1;
    public float $qty2;
    public float $hargaSatuan;
    public float $disc1Rp;
    public float $disc1Perc;
    public float $disc2Rp;
    public float $disc2Perc;
    public float $total;
    public float $jmlHarga;
    public string $idStock;
    public string $kode;
    public string $nama_barang;
    public string $satuan;

    public function __construct(array $item)
    {
        $this->idStock = $item['id_stock'];
        $this->kode = $item['kode'] ?? '';
        $this->nama_barang = $item['nama_barang'] ?? '';
        $this->satuan = $item['satuan'] ?? '';
        $this->qty1 = floatval($item['qty1']);
        $this->qty2 = floatval($item['qty2']);
        $this->hargaSatuan = isset($item['harga_satuan_raw'])
            ? floatval($item['harga_satuan_raw'])
            : floatval(preg_replace('/[^\d.]/', '', $item['harga_satuan']));
        $this->disc1Rp = floatval($item['disc_1_rp_raw']);
        $this->disc1Perc = isset($item['disc_1_perc']) ? floatval($item['disc_1_perc']) : 0;
        $this->disc2Rp = floatval($item['disc_2_rp_raw']);
        $this->disc2Perc = isset($item['disc_2_perc']) ? floatval($item['disc_2_perc']) : 0;
        // Get raw values if available, otherwise parse the formatted values
        $this->jmlHarga = isset($item['jml_harga_raw']) ?
            floatval($item['jml_harga_raw']) :
            floatval(preg_replace('/[^\d]/', '', $item['jml_harga']));

        $this->total = isset($item['total_raw']) ?
            floatval($item['total_raw']) :
            floatval(preg_replace('/[^\d]/', '', $item['total']));
    }

    public function getRecords(): array
    {
        return [
            'id_stock' => $this->idStock,
            'kode' => $this->kode,
            'nama_barang' => $this->nama_barang,
            'satuan' => $this->satuan,
            'qty1' => $this->qty1,
            'qty2' => $this->qty2,
            'harga_satuan' => $this->hargaSatuan,
            'disc_1_rp' => $this->disc1Rp,
            'disc_1_perc' => $this->disc1Perc,
            'disc_2_rp' => $this->disc2Rp,
            'disc_2_perc' => $this->disc2Perc,
            'jml_harga' => $this->jmlHarga,
            'total' => $this->total,
        ];
    }
}
