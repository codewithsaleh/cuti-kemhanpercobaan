-- Script untuk mereset password user
-- Gunakan password hash yang sudah di-generate sebelumnya

-- Update password untuk user tertentu
-- Ganti 'username' dengan username yang sebenarnya
-- Ganti 'hashed_password' dengan hash password yang benar

UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE username = 'admin';

-- Atau reset untuk semua user (password default: 'password')
-- UPDATE users 
-- SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';