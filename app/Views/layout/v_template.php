<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'E-Persediaan TIK' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- RESET & BASIC SETUP --- */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f0f4f8; color: #333; display: flex; height: 100vh; overflow: hidden; }
        
        /* --- SIDEBAR KIRI --- */
        .sidebar { width: 250px; background: #1e3c72; color: white; display: flex; flex-direction: column; padding: 20px; height: 100%; transition: 0.3s; flex-shrink: 0; }
        .brand { display: flex; align-items: center; gap: 15px; font-size: 18px; font-weight: 700; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .nav-menu { list-style: none; flex-grow: 1; overflow-y: auto; }
        .nav-menu a { display: flex; align-items: center; gap: 15px; color: #b9c7df; text-decoration: none; padding: 12px 15px; border-radius: 10px; font-size: 14px; font-weight: 500; transition: all 0.3s; margin-bottom: 10px; }
        .nav-menu a:hover, .nav-menu a.active { background-color: rgba(255, 255, 255, 0.15); color: white; }
        
        /* --- PROFIL ADMIN/USER DI SIDEBAR --- */
        .profile-card { background: linear-gradient(135deg, #2a5298 0%, #1a3360 100%); padding: 15px; border-radius: 12px; text-align: center; margin-top: auto; }
        .profile-card h4 { font-size: 14px; margin-bottom: 2px; color: white; }
        .profile-card p { font-size: 11px; color: #b9c7df; margin-bottom: 10px; }
        .profile-card button { background: white; color: #1e3c72; border: none; padding: 8px 15px; border-radius: 5px; font-size: 12px; font-weight: 600; cursor: pointer; width: 100%; transition: 0.3s; }
        .profile-card button:hover { background: #f0f4f8; }
        
        /* --- KONTEN UTAMA (KANAN) --- */
        .main-content { flex-grow: 1; padding: 30px; overflow-y: auto; width: 100%; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .search-bar { background: white; padding: 10px 20px; border-radius: 20px; display: flex; align-items: center; width: 400px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        .search-bar input { border: none; outline: none; margin-left: 10px; width: 100%; font-size: 13px; font-family: 'Poppins', sans-serif;}
        
        /* --- STYLE KOTAK KONTEN (CARD PUTIH) --- */
        .card-container { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 25px; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .card-header h3 { color: #1e3c72; font-size: 18px; }

        /* --- TOMBOL MODERN --- */
        .btn-primary { background: #2a5298; color: white; border: none; padding: 8px 15px; border-radius: 8px; font-weight: 500; cursor: pointer; transition: 0.3s; font-size: 13px; font-family: 'Poppins', sans-serif; text-decoration: none; display: inline-block;}
        .btn-primary:hover { background: #1e3c72; }
        .btn-warning { background: #f39c12; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; font-size: 12px; text-decoration: none;}
        .btn-danger { background: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; font-size: 12px; text-decoration: none;}

        /* --- TABEL GAYA EXCEL MODERN --- */
        .table-excel-modern { width: 100%; border-collapse: collapse; font-size: 13px; margin-top: 10px; }
        .table-excel-modern th, .table-excel-modern td { border: 1px solid #dce4f0; padding: 12px 15px; text-align: center; vertical-align: middle; }
        .table-excel-modern th { background-color: #f0f4f8; color: #1e3c72; font-weight: 600; }
        .table-excel-modern tr:hover { background-color: #fcfcfc; }
        .text-left { text-align: left !important; }

        /* --- FITUR TOGGLE SIDEBAR --- */
/* Saat sidebar tertutup, lebarnya jadi 0 dan kontennya disembunyikan */
        .sidebar.closed {
        width: 0;
        padding: 0;
     overflow: hidden;
        margin-left: -20px; /* Menghilangkan sisa padding */
    }

/* Tombol toggle (hamburger menu) */
.toggle-btn {
    font-size: 20px;
    color: #1e3c72;
    cursor: pointer;
    margin-right: 20px;
    transition: 0.3s;
}

.toggle-btn:hover {
    color: #2a5298;
}

/* Transisi halus untuk konten utama saat sidebar menutup */
.sidebar {
    transition: all 0.3s ease;
}
    </style>
    <?= $this->renderSection('pageStyles'); ?>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-cubes"></i>
            <span>E-Persediaan TIK</span>
        </div>
        
        <ul class="nav-menu">
            <?php 
                $uri = service('uri')->getSegment(2, ''); 
                $role = session()->get('role'); 
            ?>

            <?php if ($role == 'admin_tik' || $role == 'admin') : ?>
                <li>
                    <a href="<?= base_url('admin'); ?>" class="<?= ($uri == 'dashboard' || $uri == '') ? 'active' : '' ?>">
                        <i class="fas fa-th-large"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/master_barang'); ?>" class="<?= ($uri == 'master_barang') ? 'active' : '' ?>">
                        <i class="fas fa-box-open"></i> Master Barang
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/tambah_stok'); ?>" class="<?= ($uri == 'tambah_stok' || $uri == 'review_nota') ? 'active' : '' ?>">
                        <i class="fas fa-file-import"></i> Input Stok (Nota)
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/persetujuan'); ?>" class="<?= ($uri == 'persetujuan') ? 'active' : '' ?>">
                        <i class="fas fa-check-circle"></i> Persetujuan
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/manajemen_user'); ?>" class="<?= ($uri == 'manajemen_user') ? 'active' : '' ?>">
                        <i class="fas fa-users"></i> Manajemen User
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/laporan'); ?>" class="...">
                    <i class="fas fa-file-alt"></i> <span>Laporan Sistem</span>
                    </a>
                </li>
                

            <?php else : ?>
                <li>
                    <a href="<?= base_url('user/dashboard'); ?>" class="<?= ($uri == 'dashboard' || $uri == '') ? 'active' : '' ?>">
                        <i class="fas fa-th-large"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/form_permohonan'); ?>" class="<?= ($uri == 'form_permohonan') ? 'active' : '' ?>">
                        <i class="fas fa-edit"></i> Form Permohonan
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/riwayat'); ?>" class="<?= ($uri == 'riwayat') ? 'active' : '' ?>">
                        <i class="fas fa-history"></i> Riwayat
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <div class="profile-card">
            <i class="fas fa-user-circle" style="font-size: 40px; color: white; margin-bottom: 10px;"></i>
            <h4><?= session()->get('nama') ?? 'Pengguna' ?></h4>
            <p><?= strtoupper(session()->get('role') ?? 'User') ?> TIK</p>
            <button onclick="window.location.href='<?= base_url('auth/logout'); ?>'"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </div>
    </div>

    <div class="main-content">
        <div class="top-bar">
    <div style="display: flex; align-items: center;">
        <i class="fas fa-bars toggle-btn" id="sidebarToggle"></i>
        <div class="search-bar">
            <i class="fas fa-search" style="color: #aaa;"></i>
            <input type="text" placeholder="Cari barang atau nomor request...">
        </div>
    </div>
    <div class="top-icons">
        <i class="fas fa-bell" style="font-size: 20px; color: #1e3c72; cursor: pointer;"></i>
    </div>
</div>

        <?= $this->renderSection('content'); ?>
        
    </div>
    
<script>
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('closed');
    });
</script>

</body>
</html>