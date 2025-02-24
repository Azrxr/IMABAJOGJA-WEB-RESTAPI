CREATE TABLE `users` (
  `id` int PRIMARY KEY,
  `username` VARCHAR(255) UNIQUE,
  `email` VARCHAR(255) UNIQUE,
  `password` VARCHAR(255),
  `role` ENUM ('admin', 'member'),
  `banned` boolean DEFAULT false,
  `ban_reason` text COMMENT 'alasan diban/freeze'
);

CREATE TABLE `admins` (
  `id` int PRIMARY KEY,
  `user_id` int UNIQUE,
  `fullname` VARCHAR(255),
  `phone_number` VARCHAR(255),
  `province_id` int,
  `regency_id` int,
  `district_id` int,
  `full_address` text,
  `profile_img_path` VARCHAR(255) COMMENT 'path to image'
);

CREATE TABLE `members` (
  `id` int PRIMARY KEY,
  `user_id` int UNIQUE,
  `no_member` VARCHAR(255) UNIQUE,
  `angkatan` VARCHAR(255),
  `fullname` VARCHAR(255),
  `phone_number` VARCHAR(255),
  `province_id` int,
  `regency_id` int,
  `district_id` int,
  `full_address` text,
  `agama` VARCHAR(255),
  `nisn` int COMMENT 'opsioanal',
  `tempat` VARCHAR(255),
  `tanggal_lahir` date,
  `gender` ENUM ('perempuan', 'laki_laki'),
  `kode_pos` int,
  `member_type` ENUM ('camaba', 'pengurus', 'anggota', 'demissioner'),
  `profile_img_path` VARCHAR(255) COMMENT 'path to image',
  `scholl_origin` VARCHAR(255),
  `tahun_lulus` int,
  `is_studyng` boolean COMMENT 'TRUE jika sudah kuliah, FALSE jika masih memilih'
);

CREATE TABLE `provincies` (
  `id` int PRIMARY KEY,
  `name` VARCHAR(255)
);

CREATE TABLE `regencies` (
  `id` int PRIMARY KEY,
  `province_id` int,
  `name` VARCHAR(255)
);

CREATE TABLE `districts` (
  `id` int PRIMARY KEY,
  `regencies_id` int,
  `name` VARCHAR(255)
);

CREATE TABLE `universities` (
  `id` int PRIMARY KEY,
  `name` VARCHAR(255)
);

CREATE TABLE `faculties` (
  `id` int PRIMARY KEY,
  `university_id` int,
  `name` VARCHAR(255)
);

CREATE TABLE `program_study` (
  `id` int UNIQUE,
  `university_id` int,
  `name` VARCHAR(255),
  `jenjang_pendidikan` ENUM ('D3', 'D4', 'S1', 'S2', 'S3', 'Profesi')
);

CREATE TABLE `study_plane` (
  `id` int PRIMARY KEY,
  `member_id` int,
  `university_id` int,
  `program_study_id` int,
  `status` ENUM ('pending', 'accepted', 'rejected') COMMENT 'default pending'
);

CREATE TABLE `member_study` (
  `id` int PRIMARY KEY,
  `member_id` int,
  `university_id` int,
  `fakulty_id` int
);

CREATE TABLE `documents` (
  `id` int PRIMARY KEY,
  `member_id` int UNIQUE,
  `ktp_path` VARCHAR(255) COMMENT 'path to KTP document',
  `kk_path` VARCHAR(255) COMMENT 'path to KK document',
  `ijazah_skl_path` VARCHAR(255) COMMENT 'path to IJAZAH or SKL',
  `raport_path` VARCHAR(255) COMMENT 'path to RAPORT',
  `photo_3x4_path` VARCHAR(255) COMMENT 'path to 3x4 photo',
  `kk_legalisir_path` VARCHAR(255) COMMENT 'path to legalized KK',
  `akte_legalisir_path` VARCHAR(255) COMMENT 'path to legalized Birth Certificate',
  `skhu_legalisir_path` VARCHAR(255) COMMENT 'path to legalized SKHU',
  `raport_legalisir_path` VARCHAR(255) COMMENT 'path to legalized RAPORT',
  `surat_baik_path` VARCHAR(255) COMMENT 'path to Good Conduct Letter',
  `surat_rekom_kades_path` VARCHAR(255) COMMENT 'path to Village Recommendation Letter',
  `surat_keterangan_baik_path` VARCHAR(255) COMMENT 'path to Good Behavior Letter',
  `surat_penghasilan_ortu_path` VARCHAR(255) COMMENT 'path to Parent Income Letter',
  `surat_tidak_mampu` VARCHAR(255) COMMENT 'path to Certificate of Poverty',
  `surat_pajak_bumi_bangunan` VARCHAR(255) COMMENT 'path to Land Tax Letter',
  `surat_tidak_pdam_path` VARCHAR(255) COMMENT 'path to Non-PDAM Subscription Letter',
  `token_listrik_path` VARCHAR(255) COMMENT 'path to Electricity Token',
  `skck_path` VARCHAR(255) COMMENT 'path to Police Clearance Certificate (SKCK)',
  `sertifikat_prestasi_path` VARCHAR(255) COMMENT 'path to Achievement Certificate',
  `foto_keluarga_path` VARCHAR(255) COMMENT 'path to Family Photo',
  `kartu_kip_path` VARCHAR(255) COMMENT 'path to KIP Card (if available)',
  `kartu_pkh_path` VARCHAR(255) COMMENT 'path to PKH Card (if available)',
  `kartu_kks_path` VARCHAR(255) COMMENT 'path to KKS Card (if available)'
);

CREATE TABLE `home_photos` (
  `id` int PRIMARY KEY,
  `document_id` int,
  `photo_title` VARCHAR(255) COMMENT 'judul bagian rumah',
  `photo_img_path` VARCHAR(255) COMMENT 'path to img'
);

CREATE TABLE `organization_profile` (
  `id` int PRIMARY KEY,
  `title` VARCHAR(255),
  `description` VARCHAR(255),
  `vision` VARCHAR(255),
  `mission` VARCHAR(255),
  `contact_email` VARCHAR(255),
  `contact_phone` VARCHAR(255),
  `contact_phone2` VARCHAR(255),
  `address` TEXT
);

CREATE TABLE `organization_files` (
  `id` int PRIMARY KEY,
  `organization_profile_id` int,
  `title` VARCHAR(255),
  `description` VARCHAR(255),
  `file_path` VARCHAR(255) NOT NULL
);

ALTER TABLE `admins` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `admins` ADD FOREIGN KEY (`province_id`) REFERENCES `provincies` (`id`);

ALTER TABLE `admins` ADD FOREIGN KEY (`regency_id`) REFERENCES `regencies` (`id`);

ALTER TABLE `admins` ADD FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`);

ALTER TABLE `members` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `members` ADD FOREIGN KEY (`province_id`) REFERENCES `provincies` (`id`);

ALTER TABLE `members` ADD FOREIGN KEY (`regency_id`) REFERENCES `regencies` (`id`);

ALTER TABLE `members` ADD FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`);

ALTER TABLE `regencies` ADD FOREIGN KEY (`province_id`) REFERENCES `provincies` (`id`);

ALTER TABLE `districts` ADD FOREIGN KEY (`regencies_id`) REFERENCES `regencies` (`id`);

ALTER TABLE `faculties` ADD FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`);

ALTER TABLE `program_study` ADD FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`);

ALTER TABLE `study_plane` ADD FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);

ALTER TABLE `study_plane` ADD FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`);

ALTER TABLE `study_plane` ADD FOREIGN KEY (`program_study_id`) REFERENCES `program_study` (`id`);

ALTER TABLE `member_study` ADD FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);

ALTER TABLE `member_study` ADD FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`);

ALTER TABLE `member_study` ADD FOREIGN KEY (`fakulty_id`) REFERENCES `faculties` (`id`);

ALTER TABLE `documents` ADD FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);

ALTER TABLE `home_photos` ADD FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`);

ALTER TABLE `organization_files` ADD FOREIGN KEY (`organization_profile_id`) REFERENCES `organization_profile` (`id`);
