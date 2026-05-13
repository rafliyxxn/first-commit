<?= $this->extend('layout/v_template'); ?> 

<?= $this->section('content'); ?>
<div style="font-family: 'Poppins', sans-serif;">
    
    <div style="background: white; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
        <h3 style="margin-top: 0; color: #2d3748;"><i class="fas fa-file-alt"></i> Pusat Laporan Sistem</h3>
        <p style="color: #718096; font-size: 14px; margin-bottom: 0;">Silakan pilih jenis laporan di bawah ini dan tentukan filter data sebelum melakukan cetak dokumen.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        
        <div style="border: 1px solid #dce4f0; padding: 20px; border-radius: 12px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h4 style="color: #1e3c72; margin-top: 0; margin-bottom: 10px;"><i class="fas fa-file-import"></i> Laporan Barang Masuk</h4>
            <p style="font-size: 12px; color: #777; margin-bottom: 15px;">Mencetak rekapitulasi data barang masuk atau pembelian berdasarkan jangka tanggal tertentu.</p>
            <form action="<?= base_url('admin/laporan/barang_masuk'); ?>" method="GET" target="_blank">
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <input type="date" name="tgl_mulai" required style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 13px;">
                    <input type="date" name="tgl_selesai" required style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 13px;">
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; background: #1e3c72; color: #fff; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer;"><i class="fas fa-print"></i> Cetak PDF / Print</button>
            </form>
        </div>

        <div style="border: 1px solid #dce4f0; padding: 20px; border-radius: 12px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h4 style="color: #1e3c72; margin-top: 0; margin-bottom: 10px;"><i class="fas fa-balance-scale"></i> Laporan Stok Opname</h4>
            <p style="font-size: 12px; color: #777; margin-bottom: 15px;">Melihat catatan perbandingan kesesuaian antara jumlah stok fisik di gudang dengan stok sistem komputer.</p>
            <form action="<?= base_url('admin/laporan/stok_opname'); ?>" method="GET" target="_blank">
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <select name="bulan" required style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 13px;">
                        <?php for($m=1; $m<=12; $m++): ?>
                            <option value="<?= sprintf('%02d', $m); ?>" <?= date('m') == $m ? 'selected' : ''; ?>><?= date('F', mktime(0,0,0,$m,1)); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="tahun" required style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 13px;">
                        <?php for($y=date('Y')-2; $y<=date('Y'); $y++): ?>
                            <option value="<?= $y; ?>" <?= date('Y') == $y ? 'selected' : ''; ?>><?= $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; background: #1e3c72; color: #fff; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer;"><i class="fas fa-print"></i> Cetak Laporan Opname</button>
            </form>
        </div>

        <div style="border: 1px solid #dce4f0; padding: 20px; border-radius: 12px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h4 style="color: #1e3c72; margin-top: 0; margin-bottom: 10px;"><i class="fas fa-cubes"></i> Laporan Stok Akhir Bulan</h4>
            <p style="font-size: 12px; color: #777; margin-bottom: 15px;">Mengetahui sisa persediaan seluruh item barang per tanggal akhir bulan yang ditentukan.</p>
            <form action="<?= base_url('admin/laporan/stok_bulanan'); ?>" method="GET" target="_blank">
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <select name="bulan" required style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 13px;">
                        <?php for($m=1; $m<=12; $m++): ?>
                            <option value="<?= sprintf('%02d', $m); ?>" <?= date('m') == $m ? 'selected' : ''; ?>><?= date('F', mktime(0,0,0,$m,1)); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="tahun" required style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 13px;">
                        <?php for($y=date('Y')-2; $y<=date('Y'); $y++): ?>
                            <option value="<?= $y; ?>" <?= date('Y') == $y ? 'selected' : ''; ?>><?= $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; background: #1e3c72; color: #fff; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer;"><i class="fas fa-print"></i> Cetak Posisi Stok</button>
            </form>
        </div>

        <div style="border: 1px solid #dce4f0; padding: 20px; border-radius: 12px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h4 style="color: #1e3c72; margin-top: 0; margin-bottom: 10px;"><i class="fas fa-hotel"></i> Rekap Permintaan Ruangan</h4>
            <p style="font-size: 12px; color: #777; margin-bottom: 15px;">Cetak rekapitulasi data permintaan barang/ATK per divisi/ruangan yang diajukan selama satu bulan penuh.</p>
            <form action="<?= base_url('admin/laporan/rekap_permintaan'); ?>" method="GET" target="_blank">
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <select name="bulan" required style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 13px;">
                        <?php for($m=1; $m<=12; $m++): ?>
                            <option value="<?= sprintf('%02d', $m); ?>" <?= date('m') == $m ? 'selected' : ''; ?>><?= date('F', mktime(0,0,0,$m,1)); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="tahun" required style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 13px;">
                        <?php for($y=date('Y')-2; $y<=date('Y'); $y++): ?>
                            <option value="<?= $y; ?>" <?= date('Y') == $y ? 'selected' : ''; ?>><?= $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; background: #1e3c72; color: #fff; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer;"><i class="fas fa-print"></i> Cetak Rekap Permintaan</button>
            </form>
        </div>
<div style="border: 1px solid #dce4f0; padding: 20px; border-radius: 12px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border-left: 5px solid #27ae60;">
            <h4 style="color: #27ae60; margin-top: 0; margin-bottom: 10px;"><i class="fas fa-file-signature"></i> Berita Acara Serah Terima</h4>
            <p style="font-size: 12px; color: #777; margin-bottom: 15px;">Cetak dokumen serah terima ATK per nomor surat untuk ditandatangani oleh penerima barang.</p>
            
            <form action="<?= base_url('admin/laporan/cetak_berita_acara'); ?>" method="GET" target="_blank">
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 11px; font-weight: bold; color: #555;">MASUKKAN NOMOR NOTA / SURAT :</label>
                    <input type="text" name="no_nota" placeholder="Contoh: 4204/SEK.03..." required 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 13px; margin-top: 5px;">
                </div>
                <button type="submit" style="width: 100%; background: #27ae60; color: #fff; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.3s;">
                    <i class="fas fa-file-pdf"></i> Generate Berita Acara
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>