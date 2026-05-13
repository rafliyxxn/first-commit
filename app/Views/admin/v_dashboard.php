<?= $this->extend('layout/v_template'); ?> <?= $this->section('content'); ?>
<div style="display: flex; gap: 20px; width: 100%; align-items: flex-start; font-family: 'Poppins', sans-serif;">

    <div style="flex: 2.2; min-width: 0;">
        
        <div class="banner-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; padding: 35px 30px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(30, 60, 114, 0.15);">
            <h2 style="margin: 0 0 10px 0; font-size: 24px; font-weight: 600;">Hai, Admin TIK PN BLB!</h2>
            <p style="margin: 0 0 20px 0; opacity: 0.9; font-size: 14px;">Pantau persediaan barang dan kelola permintaan ATK ruangan hari ini.</p>
            <a href="<?= base_url('admin/laporan'); ?>" class="btn-primary" style="background: white; color: #1e3c72; text-decoration: none; padding: 10px 22px; border-radius: 8px; font-weight: bold; display: inline-block; font-size: 13px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">Lihat Laporan Bulan Ini</a>
        </div>

        <h4 style="margin: 0 0 15px 0; font-size: 16px; color: #2d3748; font-weight: 600;">Kategori Barang</h4>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 25px;">
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <i class="fas fa-print" style="font-size: 24px; color: #1e3c72; margin-bottom: 8px;"></i>
                <p style="margin: 0; font-size: 11px; font-weight: bold; color: #4a5568;">Tinta/Toner</p>
            </div>
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <i class="fas fa-copy" style="font-size: 24px; color: #1e3c72; margin-bottom: 8px;"></i>
                <p style="margin: 0; font-size: 11px; font-weight: bold; color: #4a5568;">Kertas</p>
            </div>
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <i class="fas fa-pen" style="font-size: 24px; color: #1e3c72; margin-bottom: 8px;"></i>
                <p style="margin: 0; font-size: 11px; font-weight: bold; color: #4a5568;">ATK Umum</p>
            </div>
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <i class="fas fa-desktop" style="font-size: 24px; color: #1e3c72; margin-bottom: 8px;"></i>
                <p style="margin: 0; font-size: 11px; font-weight: bold; color: #4a5568;">Hardware</p>
            </div>
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <i class="fas fa-network-wired" style="font-size: 24px; color: #1e3c72; margin-bottom: 8px;"></i>
                <p style="margin: 0; font-size: 11px; font-weight: bold; color: #4a5568;">Jaringan</p>
            </div>
        </div>

        <h4 style="margin: 0 0 15px 0; font-size: 16px; color: #2d3748; font-weight: 600;">Permintaan Terbaru</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
            <?php if (!empty($permintaan_baru)) : ?>
                <?php foreach ($permintaan_baru as $pb) : ?>
                    <a href="<?= base_url('admin/persetujuan'); ?>" style="text-decoration: none; color: inherit;">
                        <div class="card-permohonan" style="background: #fff; border: 1px solid #e2e8f0; padding: 18px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: transform 0.2s, box-shadow 0.2s;">
                            <div>
                                <span style="font-size: 11px; color: #718096; font-weight: bold; letter-spacing: 0.5px;">
                                    <?= esc($pb['no_request'] ?? ($pb['id_permohonan'] ?? 'No Request')); ?>
                                </span>
                                <h5 style="margin: 5px 0; font-size: 14px; color: #2d3748; font-weight: 600;">
                                    <?= esc($pb['nama_ruangan'] ?? ($pb['ruangan'] ?? 'Ruangan Pengaju')); ?>
                                </h5>
                                <small style="color: #a0aec0; font-size: 11px;">
                                    <?php 
                                        $tanggal = $pb['tanggal_permohonan'] ?? ($pb['tanggal_pengajuan'] ?? ($pb['created_at'] ?? date('Y-m-d')));
                                        echo date('d M Y', strtotime($tanggal));
                                    ?>
                                </small>
                            </div>
                            <div>
                                <?php 
                                    $status = strtoupper($pb['status'] ?? 'PENDING');
                                    $badgeColor = '#ffc107'; 
                                    if ($status == 'DISETUJUI' || $status == 'SELESAI' || $status == 'ACC') $badgeColor = '#28a745';
                                    if ($status == 'DITOLAK') $badgeColor = '#dc3545';
                                ?>
                                <span class="badge" style="background: <?= $badgeColor; ?>; color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;"><?= $status; ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
                <div style="grid-column: span 2; background: #f8f9fa; padding: 25px; border-radius: 12px; text-align: center; color: #718096; font-size: 13px;">
                    <i class="fas fa-info-circle"></i> Belum ada data permintaan barang terbaru.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div style="flex: 1; min-width: 280px; display: flex; flex-direction: column; gap: 20px;">
        
        <div class="ringkasan-card" style="background: white; border: 1px solid #e2e8f0; padding: 20px; border-radius: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h4 style="margin-top: 0; margin-bottom: 12px; font-size: 15px; color: #2d3748; font-weight: 600;">Ringkasan Sistem</h4>
            <p style="color: #718096; font-size: 12px; margin: 0 0 4px 0;">Total Permintaan Menunggu</p>
            <h2 style="font-size: 26px; margin: 0 0 15px 0; color: #1e3c72; font-weight: bold;"><?= $total_pending; ?> Request</h2>
            <a href="<?= base_url('admin/persetujuan'); ?>" class="btn-primary" style="display: block; text-align: center; text-decoration: none; background: #1e3c72; color: white; padding: 11px; border-radius: 8px; font-weight: bold; font-size: 13px; transition: 0.2s;">Proses Sekarang</a>
        </div>

        <div class="stok-card" style="background: white; border: 1px solid #e2e8f0; padding: 20px; border-radius: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h4 style="margin-top: 0; margin-bottom: 15px; font-size: 15px; color: #2d3748; font-weight: 600;">Stok Hampir Habis</h4>
            <?php if (!empty($stok_hampir_habis)) : ?>
                <?php foreach ($stok_hampir_habis as $sh) : ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px dashed #e2e8f0;">
                        <div style="max-width: 65%;">
                            <strong style="font-size: 13px; color: #2d3748; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= esc($sh['nama_barang']); ?></strong>
                            <small style="color: #dc3545; font-weight: bold; font-size: 11px;">Sisa: <?= $sh['stok']; ?> <?= esc($sh['satuan'] ?? 'pcs'); ?></small>
                        </div>
                        <a href="<?= base_url('admin/tambah_stok'); ?>" style="text-decoration: none; background: #ffc107; color: #000; padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: bold; display: inline-block;">Restok</a>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p style="font-size: 12px; color: #28a745; font-weight: bold; margin: 0; text-align: center; padding: 10px 0;"><i class="fas fa-check-circle"></i> Semua stok aman.</p>
            <?php endif; ?>
        </div>

    </div>

</div>
<?= $this->endSection(); ?>