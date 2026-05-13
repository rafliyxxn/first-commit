<!DOCTYPE html>
<html>
<head>
    <title><?= $title; ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background-color: #f2f2f2; text-transform: uppercase; font-size: 10px; }
        .text-center { text-align: center; }
        .footer-ttd { float: right; width: 250px; text-align: center; margin-top: 40px; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>E-Persediaan TIK PN Bandung</h2>
        <p>Laporan Rekapitulasi Distribusi Permintaan Barang Per Ruangan</p>
        <strong>Periode: <?= $bulan_txt; ?> <?= $tahun; ?></strong>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">No. Permintaan</th>
                <th width="12%">Tgl Mohon</th>
                <th>Ruangan</th>
                <th>Nama Barang</th>
                <th width="10%">Jumlah</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($laporan)): ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada rekap data permohonan ruangan pada periode ini.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach($laporan as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center"><?= $row['no_permintaan']; ?></td>
                        <td class="text-center"><?= date('d-m-Y', strtotime($row['tgl_mohon'])); ?></td>
                        <td><strong><?= $row['ruangan']; ?></strong></td>
                        <td><?= $row['nama_barang']; ?></td>
                        <td class="text-center"><?= $row['jumlah']; ?> <?= $row['satuan']; ?></td>
                        <td class="text-center"><?= strtoupper($row['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer-ttd">
        <p>Bandung, <?= date('d F Y'); ?></p>
        <p style="margin-bottom: 70px;">Kasubag TIK,</p>
        <strong>( _________________________ )</strong>
    </div>

</body>
</html>