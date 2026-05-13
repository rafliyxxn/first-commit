<?= $this->extend('layout/v_template'); ?>
<?= $this->section('content'); ?>

<style>
    .card-sm { background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); padding: 15px; }
    .table-sm th { background: #f8fafc; font-size: 13px; color: #64748b; padding: 10px; border-bottom: 2px solid #edf2f7; }
    .table-sm td { padding: 8px 10px; font-size: 13px; border-bottom: 1px solid #edf2f7; vertical-align: middle; }
    .stok-mini { background: #f1f5f9; color: #475569; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: bold; }
    .input-qty { width: 70px; padding: 5px; border: 1px solid #cbd5e1; border-radius: 4px; text-align: center; }
    .input-note { width: 100%; padding: 5px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 12px; }
</style>

<div class="card-sm">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
        <h4 style="margin:0;">Tindak Lanjut: <span style="color:#3b82f6;"><?= $no_permintaan; ?></span></h4>
        <a href="<?= base_url('admin/persetujuan') ?>" class="btn btn-light btn-sm" style="font-size:12px;">Kembali</a>
    </div>

    <form action="<?= base_url('admin/proses_tindak_lanjut') ?>" method="POST">
        <?= csrf_field(); ?>
        <input type="hidden" name="no_permintaan" value="<?= $no_permintaan; ?>">
        
        <table class="table-sm" style="width:100%;">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Barang</th>
                    <th width="10%" class="text-center">Minta</th>
                    <th width="15%">ACC</th>
                    <th>Catatan Admin</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($permohonan as $p) : ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td>
                        <input type="hidden" name="id_barang[]" value="<?= $p['id_barang']; ?>">
                        <strong><?= $p['nama_barang']; ?></strong> <span class="stok-mini">Stok: <?= $p['stok_gudang']; ?></span>
                        <div style="font-size:11px; color:#94a3b8;"><?= $p['merk']; ?></div>
                    </td>
                    <td class="text-center"><b><?= $p['jumlah']; ?></b></td>
                    <td>
                        <input type="number" name="qty_acc[]" class="input-qty" value="<?= $p['jumlah']; ?>" max="<?= $p['stok_gudang']; ?>" min="0">
                    </td>
                    <td>
                        <input type="text" name="catatan[]" class="input-note" placeholder="Tambahkan keterangan...">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
            <button type="submit" name="status_aksi" value="selesai" class="btn btn-success btn-sm" style="padding: 8px 20px;">Setujui & Proses</button>
            <button type="submit" name="status_aksi" value="ditolak" class="btn btn-danger btn-sm" style="padding: 8px 20px;">Tolak</button>
        </div>
    </form>
</div>
<?= $this->endSection(); ?>