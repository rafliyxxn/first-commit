<?= $this->extend('layout/v_template'); ?>
<?= $this->section('content'); ?>

<div class="card-container">
    <div class="card-header">
        <h3><i class="fas fa-file-import"></i> Review Hasil Scan Nota</h3>
    </div>

    <form action="<?= base_url('admin/proses_stok_final') ?>" method="post">
        <table class="table-excel-modern">
            <thead>
                <tr>
                    <th>Nama Barang (Hasil Deteksi)</th>
                    <th width="15%">Stok Saat Ini</th>
                    <th width="15%">Tambahan Stok (Dapat Diedit)</th>
                    <th width="20%">Total Stok Akhir</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="id_barang[]" class="form-control" style="width:100%; padding:5px;">
                            <?php foreach($barang as $b): ?>
                                <option value="<?= $b['id_barang'] ?>" <?= ($b['nama_barang'] == 'Kertas HVS Sinar Dunia') ? 'selected' : '' ?>>
                                    <?= $b['nama_barang'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>50 Rim</td>
                    <td>
                        <input type="number" name="qty[]" value="100" class="form-control" style="text-align:center; border: 1px solid #2a5298;">
                    </td>
                    <td style="background: #f0f4f8; font-weight: bold;">150 Rim</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: right;">
            <p style="color: red; font-size: 12px; margin-bottom: 10px;">*Mohon pastikan jumlah sudah sesuai dengan fisik nota sebelum menekan tombol update.</p>
            <button type="submit" class="btn-primary" style="padding: 10px 30px;">
                <i class="fas fa-sync-alt"></i> Update Stok Sekarang
            </button>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>