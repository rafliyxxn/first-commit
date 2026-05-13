<?= $this->extend('layout/v_template'); ?>

<?= $this->section('content'); ?>

<style>
    /* Styling khusus Form agar rapi */
    .form-section {
        display: grid;
        grid-template-columns: 1px 1px;
        gap: 20px;
        margin-bottom: 30px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #1e3c72;
    }
    .form-control {
        padding: 10px 15px;
        border: 1px solid #dce4f0;
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        transition: 0.3s;
        background: #fcfcfc;
    }
    .form-control:focus {
        border-color: #2a5298;
        box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
    }
    .form-control[readonly] {
        background: #f0f4f8;
        cursor: not-allowed;
    }
    
    /* Grid system sederhana */
    .row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; }
    .col-6 { flex: 1; min-width: 300px; }

    /* Button Styling */
    .btn-add { background: #27ae60; color: white; border: none; padding: 10px 18px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 500; transition: 0.3s; }
    .btn-add:hover { background: #219150; }
    .btn-remove { background: #e74c3c; color: white; border: none; width: 35px; height: 35px; border-radius: 8px; cursor: pointer; transition: 0.3s; }
    .btn-remove:hover { background: #c0392b; }
    .btn-submit { background: #1e3c72; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; width: 100%; margin-top: 20px; transition: 0.3s; }
    .btn-submit:hover { background: #152a50; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(30, 60, 114, 0.3); }

    select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%231e3c72' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 15px center; }
</style>

<div class="card-container">
    <div class="card-header">
        <h3><i class="fas fa-file-signature"></i> Buat Permohonan Baru</h3>
    </div>

    <form action="<?= base_url('user/kirim_permohonan') ?>" method="POST">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label>No. Permintaan</label>
                    <input type="text" name="no_permintaan" class="form-control" value="<?= $no_permintaan ?>" readonly>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label>Ruangan / Bagian</label>
                    <input type="text" name="ruangan" class="form-control" placeholder="Contoh: Bagian Pidana / Ruang IT" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label>Nama Penanggung Jawab</label>
                    <input type="text" class="form-control" value="<?= $nama ?>" readonly>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label>No. WhatsApp (Aktif)</label>
                    <input type="number" name="no_wa" class="form-control" placeholder="Contoh: 08123456789" required>
                </div>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h4 style="color: #1e3c72; font-size: 15px;">Daftar Barang yang Diminta</h4>
            <button type="button" class="btn-add" onclick="addRow()"><i class="fas fa-plus"></i> Tambah Baris</button>
        </div>

        <table class="table-excel-modern">
            <thead>
                <tr>
                    <th style="width: 50%;">Nama Barang & Merk</th>
                    <th style="width: 20%;">Jumlah</th>
                    <th style="width: 20%;">Satuan</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody id="item-list">
                <tr>
                    <td>
                        <select name="id_barang[]" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php foreach($barang as $b) : ?>
                                <option value="<?= $b['id_barang'] ?>"><?= $b['nama_barang'] ?> (<?= $b['merk'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="jumlah[]" class="form-control" min="1" placeholder="0" required>
                    </td>
                    <td>
                        <select name="satuan[]" class="form-control" required>
                            <option value="buah">Buah</option>
                            <option value="rim">Rim</option>
                            <option value="box">Box</option>
                            <option value="pack">Pack</option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Kirim Permohonan Sekarang</button>
    </form>
</div>

<script>
    function addRow() {
        const tbody = document.getElementById('item-list');
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>
                <select name="id_barang[]" class="form-control" required>
                    <option value="">-- Pilih Barang --</option>
                    <?php foreach($barang as $b) : ?>
                        <option value="<?= $b['id_barang'] ?>"><?= $b['nama_barang'] ?> (<?= $b['merk'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="number" name="jumlah[]" class="form-control" min="1" placeholder="0" required>
            </td>
            <td>
                <select name="satuan[]" class="form-control" required>
                    <option value="buah">Buah</option>
                    <option value="rim">Rim</option>
                    <option value="box">Box</option>
                    <option value="pack">Pack</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    }

    function removeRow(btn) {
        const tbody = document.getElementById('item-list');
        if (tbody.rows.length > 1) {
            btn.closest('tr').remove();
        } else {
            alert('Minimal harus ada 1 baris barang!');
        }
    }
</script>

<?= $this->endSection(); ?>