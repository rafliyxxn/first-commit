<!DOCTYPE html>
<html>
<head>
    <title><?= $title; ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 13px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #000; }
        th { background-color: #f2f2f2; padding: 10px; text-align: center; text-transform: uppercase; font-size: 11px; }
        td { padding: 8px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer-ttd { float: right; width: 250px; text-align: center; margin-top: 50px; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>E-Persediaan TIK PN Bandung</h2>
        <p>Laporan Rekapitulasi Barang Masuk & Pembelian</p>
        <strong>Periode Tanggal: <?= date('d M Y', strtotime($tgl_mulai)); ?> s/d <?= date('d M Y', strtotime($tgl_selesai)); ?></strong>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal Nota</th>
                <th>No Nota</th>
                <th>Nama Barang</th>
                <th width="10%">Jumlah Masuk</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($laporan)): ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data barang masuk pada periode tanggal ini.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; $total_seluruh = 0; foreach($laporan as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center"><?= date('d-m-Y', strtotime($row['tgl_nota'])); ?></td>
                        <td class="text-center"><?= $row['no_nota']; ?></td>
                        <td><?= $row['nama_barang']; ?></td>
                        <td class="text-center"><?= $row['jumlah_masuk']; ?></td>
                        <td class="text-right">Rp <?= number_format($row['harga_satuan'], 0, ',', '.'); ?></td>
                        <td class="text-right">Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
                    </tr>
                <?php $total_seluruh += $row['total']; endforeach; ?>
                <tr style="font-weight: bold; background: #f9f9f9;">
                    <td colspan="6" class="text-right">TOTAL KESELURUHAN:</td>
                    <td class="text-right">Rp <?= number_format($total_seluruh, 0, ',', '.'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer-ttd">
        <p>Bandung, <?= date('d F Y'); ?></p>
        <p style="margin-bottom: 70px;">Petugas Logistik TIK,</p>
        <strong>_________________________</strong>
    </div>

</body>
</html>