<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 11pt; line-height: 1.3; margin: 0; padding: 0; }
        .header { text-align: center; font-weight: bold; margin-bottom: 5px; }
        .address { text-align: center; font-size: 9pt; border-bottom: 3px double black; padding-bottom: 5px; margin-bottom: 20px; }
        .title { text-align: center; text-decoration: underline; font-weight: bold; font-size: 12pt; margin-bottom: 2px; }
        .subtitle { text-align: center; font-weight: bold; margin-bottom: 20px; }
        .opening { text-align: justify; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 5px; vertical-align: top; }
        th { background-color: #f2f2f2; text-align: center; }
        .sign-container { width: 100%; margin-top: 30px; }
        .sign-box { width: 50%; float: left; text-align: center; }
        .mengetahui { text-align: center; margin-top: 150px; clear: both; }
        .name-line { text-decoration: underline; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        MAHKAMAH AGUNG REPUBLIK INDONESIA<br>
        DIREKTORAT JENDERAL BADAN PERADILAN UMUM<br>
        PENGADILAN TINGGI BANDUNG<br>
        PENGADILAN NEGERI BALE BANDUNG
    </div>
    <div class="address">
        Jalan Jaksa Naranata Kel/Kec. Bale Endah Kabupaten Bandung, Jawa Barat 40375<br>
        Tlp/Fax. (022) 5940791 Website: pn-balebandung.go.id email: pn.balebandung@gmail.com
    </div>

    <div class="title">BERITA ACARA SERAH TERIMA ATK</div>
    <div class="subtitle">NOMOR : <?= $transaksi['no_nota'] ?? '...........................' ?></div>

    <div class="opening">
        Pada hari ini telah dilakukan serah terima barang Alat Tulis Kantor ( ATK ) satuan kerja Pengadilan Negeri Bale Bandung Kelas 1A, dengan pembagian per ruangan sebagai berikut :
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 30%;">Nama Ruangan</th>
                <th colspan="3">Uraian Barang</th>
            </tr>
            <tr>
                <th>Nama Barang</th>
                <th style="width: 10%;">Jumlah</th>
                <th style="width: 10%;">Satuan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($items)): ?>
                <?php foreach($items as $key => $item): ?>
                <tr>
                    <?php if($key == 0): ?>
                        <td rowspan="<?= count($items) ?>">
                            <strong><?= $transaksi['username'] ?? 'Ruangan' ?></strong><br><br>
                            <?php 
                            // Menampilkan daftar pegawai jika datanya dikirim dari controller
                            if(isset($pegawai) && !empty($pegawai)){
                                foreach($pegawai as $index => $p){
                                    echo ($index+1) . ". " . $p['nama_pegawai'] . "<br>";
                                }
                            }
                            ?>
                        </td>
                    <?php endif; ?>
                    <td><?= $item['nama_barang'] ?></td>
                    <td style="text-align: center;"><?= $item['qty'] ?></td>
                    <td style="text-align: center;"><?= $item['satuan'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Data barang tidak ditemukan</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="sign-container">
        <div class="sign-box">
            Petugas Persediaan<br><br><br><br><br>
            <span class="name-line"><?= $petugas ?? 'Nandang.K.' ?></span><br>
            NIP. <?= $nip_petugas ?? '19810123 202521 1 024' ?>
        </div>
        <div class="sign-box">
            Penerima Barang<br><br><br><br><br>
            <span class="name-line">.....................................</span><br>
            NIP. .....................................
        </div>
    </div>

    <div class="mengetahui">
        Mengetahui<br>
        KaSubBag Umum & Keuangan<br><br><br><br><br>
        <span class="name-line">Yenny Imelda Butar Butar, S.E. M.Ak.</span><br>
        NIP. 19790121 200904 2 002
    </div>
</body>
</html>