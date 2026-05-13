<?= $this->extend('layout/v_template'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
        <h3 style="color: #1e3c72; font-size: 18px; margin: 0; font-weight: 600;">
            <i class="fas fa-box-open" style="margin-right: 8px;"></i> Daftar Inventaris TIK
        </h3>
        
        <button onclick="tambahBarang()" style="background: #2a5298; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 500; cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus"></i> Tambah Barang
        </button>
    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
        <thead>
            <tr>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;" width="5%">No</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: left;">Nama Barang</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;">Merk</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;">Satuan</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;">Stok</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;" width="18%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($barang as $b) : ?>
            <tr>
                <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center;"><?= $no++; ?></td>
                <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: left; font-weight: 500;"><?= $b['nama_barang']; ?></td>
                <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center;"><?= $b['merk']; ?></td>
                <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center;"><?= $b['satuan']; ?></td>
                <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center;">
                    <span style="background: #e0e8f5; color: #1e3c72; padding: 4px 10px; border-radius: 5px; font-weight: 600;"><?= ($b['stok'] ?? 0); ?></span>
                </td>
                <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center;">
                    <button onclick="editBarang('<?= $b['id_barang'] ?>', '<?= addslashes($b['nama_barang']) ?>', '<?= addslashes($b['merk']) ?>', '<?= $b['satuan'] ?>', '<?= $b['stok'] ?>')" style="background: #f39c12; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    
                    <a href="<?= base_url('admin/hapus_barang/'.$b['id_barang']) ?>" onclick="return confirm('Yakin ingin menghapus barang ini?')" style="background: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; font-size: 12px; text-decoration: none; display: inline-block;">
                        <i class="fas fa-trash"></i> Hapus
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function tambahBarang() {
    Swal.fire({
        title: 'Tambah Barang Baru',
        html: `
            <form id="formTambah" action="<?= base_url('admin/simpan_barang') ?>" method="POST" style="text-align:left; font-family: sans-serif;">
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Nama Barang</label>
                    <input type="text" name="nama_barang" class="swal2-input" placeholder="Contoh: Kertas HVS" required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Merk</label>
                    <input type="text" name="merk" class="swal2-input" placeholder="Contoh: Sinar Dunia" required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Satuan</label>
                    <input type="text" name="satuan" class="swal2-input" placeholder="Contoh: rim / buah" required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Stok Awal</label>
                    <input type="number" name="stok" class="swal2-input" value="0" required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
            </form>`,
        showCancelButton: true,
        confirmButtonText: 'Simpan Barang',
        confirmButtonColor: '#2a5298',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const form = document.getElementById('formTambah');
            if(form.checkValidity()) {
                form.submit();
            } else {
                Swal.showValidationMessage('Harap isi semua kolom!');
            }
        }
    });
}

function editBarang(id, nama, merk, satuan, stok) {
    Swal.fire({
        title: 'Edit Data Barang',
        html: `
            <form id="formEdit" action="<?= base_url('admin/update_barang/') ?>/${id}" method="POST" style="text-align:left; font-family: sans-serif;">
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Nama Barang</label>
                    <input type="text" name="nama_barang" class="swal2-input" value="${nama}" required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Merk</label>
                    <input type="text" name="merk" class="swal2-input" value="${merk}" required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Satuan</label>
                    <input type="text" name="satuan" class="swal2-input" value="${satuan}" required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Update Stok</label>
                    <input type="number" name="stok" class="swal2-input" value="${stok}" required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
            </form>`,
        showCancelButton: true,
        confirmButtonText: 'Simpan Perubahan',
        confirmButtonColor: '#f39c12',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const form = document.getElementById('formEdit');
            if(form.checkValidity()) {
                form.submit();
            } else {
                Swal.showValidationMessage('Harap isi semua kolom!');
            }
        }
    });
}
</script>

<?= $this->endSection(); ?>