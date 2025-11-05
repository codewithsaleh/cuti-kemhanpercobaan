-- Script untuk update database sistem cuti Kemhan

-- Tambah kolom jika belum ada
ALTER TABLE users ADD COLUMN IF NOT EXISTS photo VARCHAR(255);
ALTER TABLE users ADD COLUMN IF NOT EXISTS position VARCHAR(100);

-- Update struktur tabel cuti
ALTER TABLE leave_requests ADD COLUMN IF NOT EXISTS attachment VARCHAR(255);
ALTER TABLE leave_requests ADD COLUMN IF NOT EXISTS notes TEXT;

-- Buat index untuk performa
CREATE INDEX IF NOT EXISTS idx_leave_requests_user_id ON leave_requests(user_id);
CREATE INDEX IF NOT EXISTS idx_leave_requests_status ON leave_requests(status);
CREATE INDEX IF NOT EXISTS idx_leave_requests_created_at ON leave_requests(created_at);

-- Update default values
UPDATE users SET role = 'employee' WHERE role IS NULL;
UPDATE leave_requests SET status = 'pending' WHERE status IS NULL;