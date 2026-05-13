<!DOCTYPE html>
<html>
<head>
    <title><?= $title; ?></title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.5; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; text-transform: uppercase; }
        .header p { margin: 0; font-size: 10px; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { vertical-align: top; }
        
        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .content-table th, .content-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .content-table th { background-color: #f2f2f2; text-align: center; }
        
        .footer { width: 100%; margin-top: 50px; }
        .footer td { text-align: center; width: 50%; }
        .signature-space { height: 80px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PENGADILAN NEGERI BALE BANDUNG</h2>
        <p>Jl. Jaksa Naranata No.1, Baleendah, Kec. Baleendah, Kabupaten Bandung, Jawa Barat 40375</p>
        <p>Telepon: (022) 5940316 | Website: www.pn-balebandung.go.id</p>
    </div>

    <h3 style="text-align: center; text-decoration: underline;">FORMULIR PERMINTAAN BARANG (ATK/TIK)</h3>

    <table class="info-table">
        <tr>
            <td width="120">No. Permintaan</td>
            <td width="10">:</td>
            <td><strong><?= $no_permintaan; ?></strong></td>
            <td width="100">Tanggal Mohon</td>
            <td width="10">:</td>
            <td><?= date('d F Y', strtotime($data_permohonan[0]['tgl_mohon'])); ?></td>
        </tr>
        <tr>
            <td>Nama Pemohon</td>
            <td>:</td>
            <td><?= $data_permohonan[0]['nama_pegawai']; ?></td>
            <td>Ruangan</td>
            <td>:</td>
            <td><?= $data_permohonan[0]['ruangan']; ?></td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Nama Barang / Merk</th>
                <th width="80">Jumlah (ACC)</th>
                <th>Keterangan Admin</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($data_permohonan as $item) : ?>
                <tr>
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td>
                        <?= $item['nama_barang']; ?><br>
                        <small style="color: #666;"><?= $item['merk']; ?></small>
                    </td>
                    <td style="text-align: center;"><?= $item['jumlah']; ?> <?= $item['satuan']; ?></td>
                    <td><?= $item['keterangan_admin'] ?: '-'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td>
                Pemohon,<br><br>
                <div class="signature-space"></div>
                ( <strong><?= $data_permohonan[0]['nama_pegawai']; ?></strong> )
            </td>
            <td>
                Petugas IT / Admin,<br><br>
                <div class="signature-space"></div>
                ( ............................................ )
            </td>
        </tr>
    </table>

    <div style="margin-top: 20px; font-style: italic; font-size: 10px;">
        *Dokumen ini dicetak otomatis melalui Sistem Inventaris TIK PN Bale Bandung pada <?= date('d/m/Y H:i:s'); ?>.
    </div>
</body>
</html>