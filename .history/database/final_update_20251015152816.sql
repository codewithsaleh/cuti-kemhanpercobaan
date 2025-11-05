-- Update password untuk semua user
-- Menggunakan berbagai format hash yang umum

-- Coba MD5 hash
UPDATE `user` SET `password` = '21232f297a57a5a743894a0e4a801fc3' WHERE `username` = 'admin';
UPDATE `user` SET `password` = 'ee11cbb19052e40b07aac0ca060c23ee' WHERE `username` = 'user';
UPDATE `user` SET `password` = '17c4520f6cfd1ab53d8745e84681eb49' WHERE `username` = 'superadmin';

-- Jika masih tidak bisa, coba plain text (tidak disarankan untuk production)
-- Uncomment baris di bawah jika MD5 tidak berhasil
-- UPDATE `user` SET `password` = 'admin' WHERE `username` = 'admin';
-- UPDATE `user` SET `password` = 'user' WHERE `username` = 'user';
-- UPDATE `user` SET `password` = 'superadmin' WHERE `username` = 'superadmin';

-- Pastikan data user_detail lengkap
UPDATE `user_detail` SET 
    `nama_lengkap` = 'Administrator',
    `id_jenis_kelamin` = 1,
    `no_telp` = '08123456789',
    `alamat` = 'Kantor Pusat',
    `nip` = '99.111.001',
    `pangkat` = 'Administrator',
    `jabatan` = 'Admin Sistem'
WHERE `id_user_detail` = '134e349e4f50a051d8ca3687d6a7de1a';

UPDATE `user_detail` SET 
    `nama_lengkap` = 'Super Administrator',
    `id_jenis_kelamin` = 1,
    `no_telp` = '08987654321',
    `alamat` = 'Kantor Pusat',
    `nip` = '99.111.000',
    `pangkat` = 'Super Admin',
    `jabatan` = 'Super Administrator'
WHERE `id_user_detail` = 'f5972fbf4ef53843c1e12c3ae99e5005';

-- Verifikasi data
SELECT u.username, u.password, u.email, ul.user_level, ud.nama_lengkap
FROM `user` u
JOIN `user_level` ul ON u.id_user_level = ul.id_user_level
LEFT JOIN `user_detail` ud ON u.id_user_detail = ud.id_user_detail;
