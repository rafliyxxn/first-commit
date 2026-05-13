<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 11pt; line-height: 1.3; }
        .header { text-align: center; font-weight: bold; text-transform: uppercase; }
        .line { border-bottom: 3px double black; margin: 10px 0; }
        .title { text-align: center; text-decoration: underline; font-weight: bold; font-size: 12pt; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid black; padding: 5px; font-size: 10pt; }
        .footer-table { border: none !important; margin-top: 30px; }
        .footer-table td { border: none !important; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        MAHKAMAH AGUNG REPUBLIK INDONESIA<br>
        DIREKTORAT JENDERAL BADAN PERADILAN UMUM<br>
        PENGADILAN TINGGI BANDUNG<br>
        PENGADILAN NEGERI BALE BANDUNG
    </div>
    <div class="line"></div>
    
    <div class="title">BERITA ACARA SERAH TERIMA ATK</div>
    <div style="text-align: center; font-weight: bold;">NOMOR : <?= $transaksi['no_nota'] ?></div>

    <p style="text-align: justify;">Pada hari ini telah dilakukan serah terima barang Alat Tulis Kantor (ATK) satuan kerja Pengadilan Negeri Bale Bandung Kelas 1A, dengan rincian sebagai berikut:</p>

    <table>
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th width="30%">Nama Ruangan</th>
                <th>Nama Barang</th>
                <th width="10%">Jumlah</th>
                <th width="10%">Satuan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $index => $item): ?>
            <tr>
                <?php if($index === 0): ?>
                <td rowspan="<?= count($items) ?>" style="vertical-align: top;">
                    <strong><?= $transaksi['nama_ruangan'] ?></strong>
                </td>
                <?php endif; ?>
                <td><?= $item['nama_barang'] ?></td>
                <td style="text-align: center;"><?= $item['qty'] ?></td>
                <td style="text-align: center;"><?= $item['satuan'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td width="50%">Petugas Persediaan<br><br><br><br><strong><?= $petugas ?></strong><br>NIP. <?= $nip_petugas ?></td>
            <td>Penerima Barang<br><br><br><br><strong>..........................</strong><br>NIP. ..........................</td>
        </tr>
    </table>
</body>
</html>