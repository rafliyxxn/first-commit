<?= $this->extend('layout/v_template'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
        <h3 style="color: #1e3c72; font-size: 18px; margin: 0; font-weight: 600;">
            <i class="fas fa-users" style="margin-right: 8px;"></i> Manajemen Data User
        </h3>
        
        <button onclick="tambahUser()" style="background: #2a5298; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 500; cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-user-plus"></i> Tambah User
        </button>
    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
        <thead>
            <tr>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;" width="5%">No</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: left;">User</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;">Password</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: left;">Penanggung Jawab</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;">No HP</th>
                <th style="border: 1px solid #dce4f0; padding: 12px 15px; background-color: #f0f4f8; color: #1e3c72; text-align: center;" width="18%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($users as $u) : ?>
            <tr>
                <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center;"><?= $no++; ?></td>
                
                <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: left; font-weight: bold; color: #2a5298;">
                    <?= $u['username']; ?>
                </td>
                
                <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center; color: #888;">
                    ******
                </td>
                
                <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: left;">
                    <?= $u['nama_pegawai']; ?>
                </td>
                
                <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center;">
                    <?= $u['no_hp']; ?>
                </td>
                
                <td style="border: 1px solid #dce4f0; padding: 12px 15px; text-align: center;">
                    <button onclick="editUser('<?= $u['id_user'] ?>', '<?= $u['username'] ?>', '<?= $u['nama_pegawai'] ?>', '<?= $u['no_hp'] ?>')" style="background: #f39c12; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    
                    <a href="<?= base_url('admin/hapus_user/'.$u['id_user']) ?>" onclick="return confirm('Yakin ingin menghapus user ini?')" style="background: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; font-size: 12px; text-decoration: none; display: inline-block;">
                        <i class="fas fa-trash"></i> Hapus
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function tambahUser() {
    Swal.fire({
        title: 'Tambah Data User',
        html: `
            <form id="formTambahUser" action="<?= base_url('admin/simpan_user') ?>" method="POST" style="text-align:left; font-family: sans-serif;">
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">User (Username)</label>
                    <input type="text" name="username" class="swal2-input" placeholder="Masukkan username" required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Password</label>
                    <input type="password" name="password" class="swal2-input" placeholder="Masukkan password" required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Penanggung Jawab</label>
                    <input type="text" name="nama_pegawai" class="swal2-input" placeholder="Nama Lengkap..." required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">No HP</label>
                    <input type="text" name="no_hp" class="swal2-input" placeholder="0812..." required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
            </form>`,
        showCancelButton: true,
        confirmButtonText: 'Simpan User',
        confirmButtonColor: '#2a5298',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const form = document.getElementById('formTambahUser');
            if(form.checkValidity()) {
                form.submit();
            } else {
                Swal.showValidationMessage('Harap isi semua kolom!');
            }
        }
    });
}

function editUser(id, username, nama, no_hp) {
    Swal.fire({
        title: 'Edit Data User',
        html: `
            <form id="formEditUser" action="<?= base_url('admin/update_user/') ?>/${id}" method="POST" style="text-align:left; font-family: sans-serif;">
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">User (Username)</label>
                    <input type="text" name="username" class="swal2-input" value="${username}" required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Password Baru (Abaikan jika tidak diubah)</label>
                    <input type="password" name="password" class="swal2-input" placeholder="Ketik sandi baru..." style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Penanggung Jawab</label>
                    <input type="text" name="nama_pegawai" class="swal2-input" value="${nama}" required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">No HP</label>
                    <input type="text" name="no_hp" class="swal2-input" value="${no_hp}" required style="width: 80%; margin: 5px 0 0 0; font-size: 14px;">
                </div>
            </form>`,
        showCancelButton: true,
        confirmButtonText: 'Update Data',
        confirmButtonColor: '#f39c12',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const form = document.getElementById('formEditUser');
            if(form.checkValidity()) {
                form.submit();
            } else {
                Swal.showValidationMessage('Harap isi kolom yang diwajibkan!');
            }
        }
    });
}
</script>

<?= $this->endSection(); ?>