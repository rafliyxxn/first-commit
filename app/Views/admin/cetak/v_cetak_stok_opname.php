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
        .text-bold { font-weight: bold; }
        .footer-ttd { float: right; width: 250px; text-align: center; margin-top: 50px; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>E-Persediaan TIK PN Bandung</h2>
        <p>Laporan Hasil Penyesuaian Stok (Stok Opname)</p>
        <strong>Periode: <?= $bulan_txt; ?> <?= $tahun; ?></strong>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal Opname</th>
                <th>Nama Barang</th>
                <th width="12%">Stok Sistem</th>
                <th width="12%">Stok Fisik</th>
                <th width="12%">Selisih</th>
                <th>Keterangan / Alasan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($laporan)): ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data stok opname pada periode bulan ini.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach($laporan as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center"><?= date('d-m-Y', strtotime($row['tanggal_opname'])); ?></td>
                        <td><?= $row['nama_barang']; ?></td>
                        <td class="text-center"><?= $row['stok_sistem']; ?> pcs</td>
                        <td class="text-center"><?= $row['stok_physical'] ?? $row['stok_fisik']; ?> pcs</td>
                        <td class="text-center text-bold" style="color: <?= ($row['selisih'] < 0) ? 'red' : (($row['selisih'] > 0) ? 'green' : 'black'); ?>;">
                            <?= ($row['selisih'] > 0) ? '+'.$row['selisih'] : $row['selisih']; ?>
                        </td>
                        <td><?= !empty($row['keterangan']) ? $row['keterangan'] : '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer-ttd">
        <p>Bandung, <?= date('d F Y'); ?></p>
        <p style="margin-bottom: 70px;">Petugas Inventaris TIK,</p>
        <strong>_________________________</strong>
    </div>

</body>
</html>