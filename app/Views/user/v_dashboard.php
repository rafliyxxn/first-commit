<?= $this->extend('layout/v_template'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Styling khusus untuk Dashboard agar persis seperti inspirasi modern */
    .dash-wrapper {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #2c3e50;
    }
    
    /* Welcome Banner - Gradient Modern */
    .welcome-banner {
        background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
        border-radius: 20px;
        padding: 30px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 25px rgba(42, 82, 152, 0.3);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    
    .welcome-text h2 { margin: 0 0 10px 0; font-size: 28px; font-weight: 700; }
    .welcome-text p { margin: 0; font-size: 15px; opacity: 0.9; }
    
    /* Hiasan background abstrak di banner */
    .welcome-banner::after {
        content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px;
        background: rgba(255,255,255,0.1); border-radius: 50%;
    }

    /* Grid untuk 4 Kartu Statistik */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .icon-box {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
    }

    /* Varian Warna Icon */
    .icon-blue { background: #e0f2fe; color: #0284c7; }
    .icon-green { background: #dcfce7; color: #16a34a; }
    .icon-orange { background: #ffedd5; color: #ea580c; }
    .icon-purple { background: #f3e8ff; color: #9333ea; }

    .stat-info h4 { margin: 0; font-size: 24px; font-weight: 700; color: #1e293b; }
    .stat-info span { font-size: 13px; color: #64748b; font-weight: 500; }

    /* Grid Bawah (Grafik & Riwayat) */
    .bottom-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .content-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.04);
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-top: 0;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* List Riwayat */
    .history-list { list-style: none; padding: 0; margin: 0; }
    .history-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .history-item:last-child { border-bottom: none; }
    
    .history-left { display: flex; align-items: center; gap: 12px; }
    .history-icon { width: 40px; height: 40px; border-radius: 50%; background: #f8fafc; display: flex; justify-content: center; align-items: center; color: #475569; }
    .history-text h5 { margin: 0; font-size: 14px; font-weight: 600; }
    .history-text p { margin: 0; font-size: 12px; color: #94a3b8; }
    .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; }
    
    .status-acc { background: #dcfce7; color: #16a34a; }
    .status-pending { background: #fef08a; color: #ca8a04; }

    /* Responsif untuk layar kecil */
    @media (max-width: 992px) {
        .bottom-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="dash-wrapper">
    
    <div class="welcome-banner">
        <div class="welcome-text">
            <h2>Halo, <?= session()->get('nama_pegawai') ?? 'User' ?>! 👋</h2>
            <p>Selamat datang di Dashboard E-Persediaan TIK Pengadilan Negeri.</p>
        </div>
        <div style="font-size: 60px; opacity: 0.8;">
            <i class="fas fa-boxes"></i>
        </div>
    </div>

    <div class="stats-container">
        <div class="stat-card">
            <div class="icon-box icon-blue"><i class="fas fa-desktop"></i></div>
            <div class="stat-info">
                <h4>150</h4>
                <span>Total Inventaris TIK</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="icon-box icon-green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <h4>84</h4>
                <span>Permintaan Disetujui</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="icon-box icon-orange"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <h4>12</h4>
                <span>Menunggu Persetujuan</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="icon-box icon-purple"><i class="fas fa-box-open"></i></div>
            <div class="stat-info">
                <h4>45</h4>
                <span>Barang Keluar Bulan Ini</span>
            </div>
        </div>
    </div>

    <div class="bottom-grid">
        
        <div class="content-card">
            <div class="card-title">
                <span><i class="fas fa-chart-line" style="color:#2a5298; margin-right:8px;"></i> Tren Permintaan Barang</span>
                <select style="border:1px solid #e2e8f0; border-radius:5px; padding:3px 8px; font-size:12px; color:#64748b;">
                    <option>Tahun Ini</option>
                    <option>Bulan Ini</option>
                </select>
            </div>
            <canvas id="activityChart" height="100"></canvas>
        </div>

        <div class="content-card">
            <div class="card-title">
                <span><i class="fas fa-history" style="color:#2a5298; margin-right:8px;"></i> Permintaan Terbaru</span>
            </div>
            
            <ul class="history-list">
                <li class="history-item">
                    <div class="history-left">
                        <div class="history-icon"><i class="fas fa-print"></i></div>
                        <div class="history-text">
                            <h5>Kertas A4 (2 Rim)</h5>
                            <p>Bagian Pidana • 27 Apr 2026</p>
                        </div>
                    </div>
                    <span class="status-badge status-acc">Selesai</span>
                </li>

                <li class="history-item">
                    <div class="history-left">
                        <div class="history-icon"><i class="fas fa-pen"></i></div>
                        <div class="history-text">
                            <h5>Tinta Printer Epson</h5>
                            <p>Bagian Perdata • 26 Apr 2026</p>
                        </div>
                    </div>
                    <span class="status-badge status-pending">Pending</span>
                </li>

                <li class="history-item">
                    <div class="history-left">
                        <div class="history-icon"><i class="fas fa-mouse"></i></div>
                        <div class="history-text">
                            <h5>Mouse Wireless</h5>
                            <p>Ruang IT • 25 Apr 2026</p>
                        </div>
                    </div>
                    <span class="status-badge status-acc">Selesai</span>
                </li>
                
                <li class="history-item">
                    <div class="history-left">
                        <div class="history-icon"><i class="fas fa-keyboard"></i></div>
                        <div class="history-text">
                            <h5>Keyboard Logitech</h5>
                            <p>PTIP • 24 Apr 2026</p>
                        </div>
                    </div>
                    <span class="status-badge status-acc">Selesai</span>
                </li>
            </ul>
            
            <div style="text-align: center; margin-top: 15px;">
                <a href="#" style="font-size: 13px; color: #2a5298; text-decoration: none; font-weight: 600;">Lihat Semua <i class="fas fa-arrow-right" style="font-size: 10px;"></i></a>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('activityChart').getContext('2d');
        
        // Bikin efek gradient untuk area di bawah garis
        var gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(42, 82, 152, 0.4)'); // Biru transparan
        gradient.addColorStop(1, 'rgba(42, 82, 152, 0.0)'); // Pudar
        
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Permintaan',
                    data: [12, 19, 15, 25, 22, 30, 28, 35, 20, 40, 38, 45], // Angka dummy
                    borderColor: '#2a5298',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2a5298',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true, // Efek area berwarna di bawah garis
                    tension: 0.4 // Membuat garis melengkung smooth (smooth curve)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false } // Sembunyikan legend agar clean
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#f1f5f9' },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });
    });
</script>

<?= $this->endSection(); ?>