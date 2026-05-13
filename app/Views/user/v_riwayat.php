<?= $this->extend('layout/v_template'); ?>

<?= $this->section('content'); ?>

<style>
    /* Styling khusus Badge Status */
    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }
    .badge-pending {
        background-color: #fff4e5;
        color: #b76e00;
        border: 1px solid #ffe5c4;
    }
    .badge-selesai {
        background-color: #e6fffa;
        color: #047857;
        border: 1px solid #b2f5ea;
    }
    .badge-ditolak {
        background-color: #fff5f5;
        color: #c53030;
        border: 1px solid #feb2b2;
    }

    /* Action Button */
    .btn-download {
        background-color: #1e3c72;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-download:hover {
        background-color: #2a5298;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #64748b;
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 10px;
        opacity: 0.3;
    }
</style>

<div class="card-container">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3><i class="fas fa-history"></i> Riwayat Permohonan Barang</h3>
        <a href="<?= base_url('user/form_permohonan') ?>" class="btn-primary" style="font-size: 12px; padding: 8px 15px;">
            <i class="fas fa-plus"></i> Tambah Baru
        </a>
    </div>

    <div class="card-body">
        <table class="table-excel-modern">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>No. Permintaan</th>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($riwayat)) : ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <p>Belum ada riwayat permohonan.</p>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php $no = 1; foreach ($riwayat as $r) : ?>
                        <tr>
                            <td style="text-align: center; color: #64748b;"><?= $no++; ?></td>
                            <td style="font-weight: 600; color: #2a5298;"><?= $r['no_permintaan']; ?></td>
                            <td>
                                <div style="font-size: 13px; font-weight: 500;"><?= date('d M Y', strtotime($r['tgl_mohon'])); ?></div>
                                <div style="font-size: 11px; color: #94a3b8;"><?= date('H:i', strtotime($r['tgl_mohon'])); ?> WIB</div>
                            </td>
                            <td>
                                <div style="font-weight: 500;"><?= $r['nama_barang']; ?></div>
                                <div style="font-size: 12px; color: #64748b;"><?= $r['merk']; ?> (<?= $r['jumlah']; ?> <?= $r['satuan']; ?>)</div>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($r['status'] == 'pending') : ?>
                                    <span class="badge badge-pending"><i class="fas fa-clock"></i> Pending</span>
                                <?php elseif ($r['status'] == 'selesai') : ?>
                                    <span class="badge badge-selesai"><i class="fas fa-check-circle"></i> Selesai</span>
                                <?php else : ?>
                                    <span class="badge badge-ditolak"><i class="fas fa-times-circle"></i> Ditolak</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <a href="<?= base_url('user/download_pdf/' . $r['no_permintaan']); ?>" class="btn-download">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>