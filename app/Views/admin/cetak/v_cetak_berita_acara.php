<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Generate Berita Acara ATK</title>
    <style>
        @page { margin: 40px 50px; }
        body { font-family: "Times New Roman", Times, serif; font-size: 11pt; line-height: 1.3; color: #000; }
        
        /* KOP SURAT */
        .tabel-kop { width: 100%; border-bottom: 3px solid black; margin-bottom: 2px; }
        .tabel-kop td { padding: 5px; }
        .teks-kop { text-align: center; line-height: 1.1; }
        .kop-1 { font-size: 13pt; font-weight: bold; }
        .kop-2 { font-size: 14pt; font-weight: bold; }
        .kop-3 { font-size: 16pt; font-weight: bold; }
        .kop-alamat { font-size: 10pt; margin-top: 4px; font-weight: normal;}
        
        /* ISI SURAT */
        .garis-tipis { border-top: 1px solid black; width: 100%; margin-bottom: 15px; }
        .judul { text-align: center; font-size: 12pt; font-weight: bold; text-decoration: underline; margin-bottom: 2px; }
        .nomor { text-align: center; font-size: 11pt; margin-bottom: 20px; }
        .paragraf { text-align: justify; margin-bottom: 15px; }
        
        /* TABEL BARANG */
        table.tabel-barang { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.tabel-barang th, table.tabel-barang td { border: 1px solid black; padding: 6px 8px; vertical-align: top; }
        table.tabel-barang th { text-align: center; font-weight: normal; vertical-align: middle; }
        
        /* TANDA TANGAN */
        table.tabel-ttd { width: 100%; text-align: center; border: none; page-break-inside: avoid; }
        table.tabel-ttd td { border: none; vertical-align: top; padding: 0; width: 50%; }
        .nama-ttd { text-decoration: underline; margin-bottom: 2px; }
    </style>
</head>
<body>

    <table class="tabel-kop">
        <tr>
            <td style="width: 15%; text-align: center;">
                <img src="<?= base_url('assets/logo.png') ?>" style="width: 90px; height: auto;">
            </td>
            <td style="width: 85%;">
                <div class="teks-kop">
                    <div class="kop-1">MAHKAMAH AGUNG REPUBLIK INDONESIA</div>
                    <div class="kop-1">DIREKTORAT JENDERAL BADAN PERADILAN UMUM</div>
                    <div class="kop-2">PENGADILAN TINGGI BANDUNG</div>
                    <div class="kop-3">PENGADILAN NEGERI BALE BANDUNG</div>
                    <div class="kop-alamat">
                        Jalan Jaksa Naranata Kel/Kec. Bale Endah Kabupaten Bandung, Jawa Barat 40375<br>
                        Tlp/Fax. (022) 5940791 Website: pn-balebandung.go.id email: pn.balebandung@gmail.com
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div class="garis-tipis"></div>

    <div class="judul">BERITA ACARA SERAH TERIMA ATK</div>
    <div class="nomor">NOMOR : <?= htmlspecialchars($nomor_ba) ?></div>

    <div class="paragraf">
        Pada hari ini <?= $hari ?> tanggal <?= $tgl_angka ?> bulan <?= $bulan_huruf ?> tahun <?= $tahun_huruf ?> telah dilakukan serah terima barang Alat Tulis Kantor ( ATK ) satuan kerja Pengadilan Negeri Bale Bandung Kelas 1A, dengan pembagian per ruangan untuk periode <?= $periode ?> sebagai berikut :
    </div>

    <table class="tabel-barang">
        <thead>
            <tr>
                <th rowspan="2" style="width: 35%;">Nama Ruangan</th>
                <th colspan="3">Uraian Barang</th>
            </tr>
            <tr>
                <th>Nama Barang</th>
                <th style="width: 12%;">Jumlah</th>
                <th style="width: 12%;">Satuan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $key => $item): ?>
            <tr>
                <?php if($key == 0): ?>
                    <td rowspan="<?= count($items) ?>" style="text-align: left;">
                        <div style="text-align: center; margin-bottom: 10px;">
                            <?= htmlspecialchars($transaksi['username'] ?? 'Ruangan') ?>
                        </div>
                        <div style="padding-left: 10px;">
                            <?php 
                                if(!empty($list_pj)) {
                                    foreach($list_pj as $i => $pj) {
                                        echo ($i + 1) . ". " . htmlspecialchars($pj) . "<br>";
                                    }
                                } else {
                                    echo "1. ................................";
                                }
                            ?>
                        </div>
                    </td>
                <?php endif; ?>
                <td><?= htmlspecialchars($item['nama_barang']) ?></td>
                <td style="text-align: center;"><?= $item['qty_acc'] ?? $item['qty_diminta'] ?></td>
                <td style="text-align: center;"><?= htmlspecialchars($item['satuan']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="tabel-ttd">
        <tr>
            <td>
                Petugas Persediaan<br><br><br><br><br>
                <div class="nama-ttd">Nandang.K.</div>
                NIP. 19810123 202521 1 024
            </td>
            <td>
                Penerima Barang<br><br><br><br><br>
                <div class="nama-ttd"><?= !empty($list_pj) ? htmlspecialchars($list_pj[0]) : '.........................................' ?></div>
                NIP. .........................................
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 30px;">
                Mengetahui<br>
                KaSubBag Umum & Keuangan<br><br><br><br><br>
                <div class="nama-ttd">Yenny Imelda Butar Butar, S.E. M.Ak.</div>
                NIP. 19790121 200904 2 002
            </td>
        </tr>
    </table>

</body>
</html>