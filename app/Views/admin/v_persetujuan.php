<?= $this->extend('layout/v_template'); ?>

<?= $this->section('content'); ?>

<div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
        <h3 style="color: #1e3c72; font-size: 18px; margin: 0; font-weight: 600;">
            <i class="fas fa-clipboard-check" style="margin-right: 8px;"></i> Persetujuan Permintaan
        </h3>
    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
        <thead>
            <tr>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;" width="5%">No</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;">No Permohonan</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;">Ruangan</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;">Penanggung Jawab</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;">Tanggal</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;">Status</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;" width="25%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($permohonan)) : ?>
                <?php $no = 1; foreach($permohonan as $p) : ?>
                <tr>
                    <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center;"><?= $no++; ?></td>
                    <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center; font-weight: bold; color: #2a5298;">
                        <?= $p['no_permintaan']; ?>
                    </td>
                    <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center;">
                        <?= $p['ruangan']; ?>
                    </td>
                    <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center; font-weight: 500;">
                        <?= $p['penanggung_jawab'] ?: '-'; ?> 
                    </td>
                    <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center;">
                        <?= date('d/m/Y', strtotime($p['tgl_mohon'])); ?>
                    </td>
                    <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center;">
                        <?php if(strtolower($p['status']) == 'pending'): ?>
                            <span style="background: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 5px; font-weight: 600; font-size: 11px;"><i class="fas fa-clock"></i> Pending</span>
                        <?php elseif(strtolower($p['status']) == 'selesai'): ?>
                            <span style="background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 5px; font-weight: 600; font-size: 11px;"><i class="fas fa-check"></i> Selesai</span>
                        <?php else: ?>
                            <span style="background: #f8d7da; color: #721c24; padding: 4px 10px; border-radius: 5px; font-weight: 600; font-size: 11px;"><i class="fas fa-times"></i> Ditolak</span>
                        <?php endif; ?>
                    </td>
                    <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center;">
                        <a href="<?= base_url('admin/tindak_lanjut/'.$p['no_permintaan']) ?>" style="background: #2a5298; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; font-size: 12px; text-decoration: none; display: inline-block; margin-right: 5px; margin-bottom: 5px;">
                            <i class="fas fa-tasks"></i> Tindak Lanjut
                        </a>
                        
                        <a href="<?= base_url('admin/kirim_notif_wa/' . $p['no_permintaan']) ?>" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; font-size: 12px; text-decoration: none; display: inline-block; margin-bottom: 5px;">
                            <i class="fab fa-whatsapp"></i> WA ACC
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" style="border: 1px solid #dce4f0; padding: 20px; text-align: center; color: #999;">
                        Belum ada permohonan barang saat ini.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>