<?= $this->extend('layout/v_template'); ?>
<?= $this->section('content'); ?>
<div class="card-container">
    <div class="card-header">
        <h3><i class="fas fa-file-upload"></i> Tahap 1: Upload Nota Pembelian</h3>
    </div>
    
    <div style="padding: 30px; text-align: center; border: 2px dashed #dce4f0; border-radius: 10px; background: #fafbfc;">
        
        <form action="<?= base_url('index.php/admin/review_nota') ?>" method="POST" enctype="multipart/form-data">
            
            <?= csrf_field() ?> <i class="fas fa-file-invoice" style="font-size: 60px; color: #1e3c72; margin-bottom: 20px;"></i>
            <h4 style="color: #333; margin-bottom: 10px;">Pilih File Nota Pembelian</h4>
            <p style="font-size: 13px; color: #777; margin-bottom: 20px;">Format yang didukung: JPG, PNG, atau PDF.</p>
            
            <input type="file" name="nota" required style="margin-bottom: 25px; font-size: 14px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%; max-width: 400px; cursor: pointer;" accept="image/*,.pdf">
            
            <br>
            
            <button type="submit" class="btn-primary" style="padding: 12px 40px; font-size: 15px; cursor: pointer;">
                <i class="fas fa-search"></i> Deteksi Barang Otomatis
            </button>
            
        </form>

    </div>
</div>
<?= $this->endSection(); ?>