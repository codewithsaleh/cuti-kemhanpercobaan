-- Update tabel cuti
-- Contoh: Menambahkan kolom atau mengubah struktur tabel

-- Menambahkan kolom baru
ALTER TABLE cuti ADD COLUMN status_approval VARCHAR(20) DEFAULT 'pending';

-- Mengubah tipe data kolom
ALTER TABLE cuti MODIFY COLUMN jumlah_hari INT;

-- Menambahkan index
CREATE INDEX idx_user_id ON cuti(user_id);

-- Update data
UPDATE cuti 
SET status = 'approved' 
WHERE tanggal_pengajuan < CURDATE() - INTERVAL 30 DAY 
AND status = 'pending';