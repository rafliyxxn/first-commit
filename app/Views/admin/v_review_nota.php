<?= $this->extend('layout/v_template'); ?>
<?= $this->section('content'); ?>

<div class="card" style="margin: 20px; padding: 25px; border-radius: 15px; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin:0; color: #2c3e50;"><i class="fas fa-check-circle" style="color: #27ae60;"></i> Tahap 2: Konfirmasi Data Nota</h3>
        <button type="button" onclick="tambahBarisManual()" class="btn" style="background: #27ae60; color: white; border-radius: 8px; padding: 10px 20px; border: none; cursor: pointer; font-weight: bold;">
            <i class="fas fa-plus"></i> Tambah Baris Manual
        </button>
    </div>

    <div style="display: flex; gap: 25px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <div style="border: 2px dashed #bdc3c7; padding: 10px; border-radius: 10px; background: #f9f9f9; position: sticky; top: 20px;">
                <img src="<?= base_url('uploads/nota/' . $file_nota); ?>" style="width: 100%; border-radius: 5px;">
            </div>
        </div>

        <div style="flex: 2; min-width: 500px;">
            <form action="<?= base_url('admin/simpan_stok_final') ?>" method="POST">
                <div style="display: flex; gap: 15px; margin-bottom: 20px;">
                    <div style="flex: 1;">
                        <label style="font-size: 12px; font-weight: bold;">NOMOR NOTA</label>
                        <input type="text" name="no_nota" required class="form-control" placeholder="Contoh: INV-001">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-size: 12px; font-weight: bold;">TANGGAL NOTA</label>
                        <input type="date" name="tgl_nota" value="<?= date('Y-m-d') ?>" class="form-control">
                    </div>
                </div>

                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #2a5298; color: white;">
                            <th style="padding: 12px; text-align: left;">Nama Barang</th>
                            <th style="padding: 12px; text-align: center;">Stok Saat Ini</th>
                            <th style="padding: 12px; text-align: center;">Jumlah</th>
                            <th style="padding: 12px; text-align: center;">Satuan</th>
                            <th style="padding: 12px; text-align: center;">Total Akhir</th>
                            <th style="padding: 12px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabelBody">
                        <?php if(empty($hasil_deteksi)): ?>
                            <tr id="rowKosong"><td colspan="6" style="text-align:center; padding: 30px; color: #999;">AI gagal mendeteksi. Klik tombol hijau di atas.</td></tr>
                        <?php endif; ?>

                        <?php foreach ($hasil_deteksi as $item) : ?>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                <select name="id_barang[]" class="select-barang" onchange="updateRow(this)" required style="width: 100%; padding: 8px;">
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($barang_all as $b) : ?>
                                        <option value="<?= $b['id_barang'] ?>" 
                                                data-stok="<?= $b['stok'] ?>" 
                                                data-satuan="<?= $b['satuan'] ?>" 
                                                <?= ($b['id_barang'] == $item['id_barang']) ? 'selected' : '' ?>>
                                            <?= $b['nama_barang'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="stok-lama-text" style="text-align: center; font-weight: bold;">-</td>
                            <td>
                                <input type="number" name="qty[]" value="<?= $item['qty'] ?>" class="qty-input" oninput="updateRow(this)" style="width: 60px; text-align: center; border: 1px solid #3498db; border-radius: 4px;">
                            </td>
                            <td class="satuan-text" style="text-align: center; color: #666;">-</td>
                            <td class="stok-akhir-text" style="text-align: center; font-weight: bold; background: #f0f7ff;">-</td>
                            <td style="text-align: center;">
                                <button type="button" onclick="this.closest('tr').remove()" style="background: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <a href="<?= base_url('admin/tambah_stok') ?>" style="color: #e74c3c; text-decoration: none;">Batal & Upload Ulang</a>
                    <button type="submit" style="background: #2a5298; color: white; border: none; padding: 12px 30px; border-radius: 8px; cursor: pointer; font-weight: bold;">
                        <i class="fas fa-save"></i> Konfirmasi & Simpan Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateRow(el) {
        const tr = el.closest('tr');
        const select = tr.querySelector('.select-barang');
        const qtyInput = tr.querySelector('.qty-input');
        
        if (select.value !== "") {
            const opt = select.options[select.selectedIndex];
            const stokLama = parseInt(opt.getAttribute('data-stok')) || 0;
            const satuan = opt.getAttribute('data-satuan') || '-';
            const qtyBaru = parseInt(qtyInput.value) || 0;

            tr.querySelector('.stok-lama-text').innerText = stokLama;
            tr.querySelector('.satuan-text').innerText = satuan;
            tr.querySelector('.stok-akhir-text').innerText = stokLama + qtyBaru;
        }
    }

    function tambahBarisManual() {
        const rowKosong = document.getElementById('rowKosong');
        if(rowKosong) rowKosong.remove();

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <select name="id_barang[]" class="select-barang" onchange="updateRow(this)" required style="width: 100%; padding: 8px;">
                    <option value="">-- Pilih Barang --</option>
                    <?php foreach ($barang_all as $b) : ?>
                        <option value="<?= $b['id_barang'] ?>" data-stok="<?= $b['stok'] ?>" data-satuan="<?= $b['satuan'] ?>"><?= $b['nama_barang'] ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td class="stok-lama-text" style="text-align: center; font-weight: bold;">-</td>
            <td>
                <input type="number" name="qty[]" value="1" class="qty-input" oninput="updateRow(this)" style="width: 60px; text-align: center; border: 1px solid #3498db; border-radius: 4px;">
            </td>
            <td class="satuan-text" style="text-align: center; color: #666;">-</td>
            <td class="stok-akhir-text" style="text-align: center; font-weight: bold; background: #f0f7ff;">-</td>
            <td style="text-align: center;">
                <button type="button" onclick="this.closest('tr').remove()" style="background: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;"><i class="fas fa-trash"></i></button>
            </td>
        `;
        document.getElementById('tabelBody').appendChild(tr);
    }

    // Jalankan kalkulasi otomatis saat halaman pertama kali dibuka
    window.onload = function() {
        document.querySelectorAll('.select-barang').forEach(s => updateRow(s));
    };
</script>

<?= $this->endSection(); ?>