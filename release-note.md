# Start HaoDT

INSERT INTO `permissions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES (NULL, 'Thống kê lý lịch trích ngang nhân viên', 'ReportController@employeeResume', NULL, NULL);

ALTER TABLE `nhan_vien` ADD PRIMARY KEY(`ma_nv`);

create folder public/excel

INSERT INTO `permissions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES (NULL, 'Thống kê lý lịch trích ngang học sinh', 'ReportController@studentResume', NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES (NULL, 'Thống kê sinh nhật học sinh', 'ReportController@studentBirthday', NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES (NULL, 'Thống kê giáo viên phụ trách lớp', 'ReportController@teacherClass', NULL, NULL);

ALTER TABLE `enrollment_records` CHANGE `status` `status` TINYINT(1) NOT NULL COMMENT '0: submit student records\r\n1: divided class\r\n2: Reserve\r\n3: dropped out of school ';

# End HaoDT

# Start HaoDT 2

INSERT INTO `permissions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES (NULL, 'Thống kê theo dõi sự phát triển', 'ReportController@monitorPhysicalConditionStudent', NULL, NULL);

# End HaoDT 2

# Start HaoDT 3

INSERT INTO `permissions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES (NULL, 'Thống kê bảng chấm công nhân viên', 'ReportController@timekeepingEmployee', NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES (NULL, 'Thống kê chi lương nhân viên', 'ReportController@salary', NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES (NULL, 'Thống kê điểm danh học sinh', 'ReportController@attendance', NULL, NULL);

# End HaoDT 3

# Start HaoDT 4

INSERT INTO `permissions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES (NULL, 'Cập nhật hình thức chấm công', 'HinhThucChamCongController@updateHinhThucChamCong', NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES (NULL, 'Xóa hình thức chấm công', 'HinhThucChamCongController@deleteHinhThucChamCong', NULL, NULL);

# End HaoDT 4

# Start Quyen 1

# bổ sung các tham số sau đây vào file .env

LOAI_CHI_PHI_TIEN_AN_HANG_NGAY = 5
AMOUNT_OF_DATE = 5
ROLE_ID_NHAN_VIEN_NAU_AN = 10
ID_LOAI_HOC_PHI_THANG = 2
ID_SUAT_AN = 1

# Cập nhật permission trong database

INSERT INTO `permissions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES (NULL, 'Thêm mới hình thức điểm danh', 'HinhThucDiemDanhController@createHinhThucDiemDanh', NULL, NULL), (NULL, 'Cập nhật hình thức điểm danh', 'HinhThucDiemDanhController@updateHinhThucDiemDanh', NULL, NULL);

# End Quyen 1

# Start Quyen 2

ALTER TABLE `users` ADD `id_coso` TINYINT(2) NULL AFTER `remember_token`;
ALTER TABLE `info` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;

Tạo thêm role Super Administrators
Thêm ID_ROLE_SUPER_ADMIN = 11 vào file .env

Các permission cần bỏ ra, chỉ có ở admin tổng:

-   RoleController@index, RoleController@store, RoleController@update, RoleController@destroy, RoleController@handleAuthorize

-   NangKhieuController@index, NangKhieuController@create, NangKhieuController@update, NangKhieuController@delete

-   DoituongController@index, DoituongController@create, DoituongController@update, DoituongController@delete

-   TieuChuanPhatTrienController@index, TieuChuanPhatTrienController@update,

-   NhuCauDinhDuongController@index, NhuCauDinhDuongController@update

-   NhuCauNangLuongController@index, NhuCauNangLuongController@store, NhuCauNangLuongController@destroy

-   EnrollmentController@index, EnrollmentController@store, EnrollmentController@update, EnrollmentController@destroy

-   NhomCoSoVatChatController@index, NhomCoSoVatChatController@store, NhomCoSoVatChatController@destroy

-   NhomThucPhamController@index, NhomThucPhamController@store, NhomThucPhamController@destroy

ALTER TABLE `daily_fee` ADD `id_coso` INT(2) NULL AFTER `note`;
ALTER TABLE `monthly_fee` ADD `id_coso` TINYINT(2) NULL AFTER `note`;
ALTER TABLE `annual_fee` ADD `id_coso` TINYINT(2) NULL AFTER `note`;
ALTER TABLE `extra_service` ADD `id_coso` TINYINT(2) NULL AFTER `note`;
ALTER TABLE `class` ADD `id_coso` TINYINT(2) NULL AFTER `note`;

nhớ làm lại phần get giáo viên trong class.vue theo cơ sở
ALTER TABLE `enrollment_records` ADD `id_coso` TINYINT(2) NULL AFTER `id_class`;

INSERT INTO `permissions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES (NULL, 'Cập nhật nhân viên', 'NhanVienController@updateNhanVien', NULL, NULL);

#install momentphp
composer require fightbulc/moment

ALTER TABLE `hinh_thuc_diem_danh` ADD `id_coso` TINYINT NULL AFTER `updated_at`;
ALTER TABLE `danh_muc_chi_phi` ADD `id_coso` TINYINT NULL AFTER `isDeleted`;
ALTER TABLE `nha_cung_cap` ADD `id_coso` TINYINT(2) NULL AFTER `bank`;
ALTER TABLE `quan_ly_thu` ADD `id_coso` TINYINT(2) NULL AFTER `isDeleted`;
ALTER TABLE `quan_ly_chi` ADD `id_coso` TINYINT(2) NULL AFTER `isDeleted`;
ALTER TABLE `danh_muc_co_so_vat_chat` ADD `id_coso` TINYINT(2) NULL AFTER `note`;
ALTER TABLE `thanh_phan_dinh_duong` ADD `id_coso` TINYINT(2) NULL AFTER `note`;
ALTER TABLE `suat_an_dinh_duong` ADD `id_coso` TINYINT(2) NULL AFTER `isDeleted`;
ALTER TABLE `thuc_don` ADD `id_coso` TINYINT(2) NULL AFTER `nguoi_duyet`;
ALTER TABLE `permissions` ADD `forSuperAdmin` BOOLEAN NULL AFTER `code`;
UPDATE `permissions` SET `forSuperAdmin` = 1 where `code` like 'RoleController@%'
UPDATE `permissions` SET `forSuperAdmin` = 1 where `code` like 'NangKhieuController@%'
UPDATE `permissions` SET `forSuperAdmin` = 1 where `code` like 'DoituongController@%'
UPDATE `permissions` SET `forSuperAdmin` = 1 where `code` like 'TieuChuanPhatTrienController@%'
UPDATE `permissions` SET `forSuperAdmin` = 1 where `code` like 'NhuCauDinhDuongController@%'
UPDATE `permissions` SET `forSuperAdmin` = 1 where `code` like 'NhuCauNangLuongController@%'
UPDATE `permissions` SET `forSuperAdmin` = 1 where `code` like 'EnrollmentController@%'
UPDATE `permissions` SET `forSuperAdmin` = 1 where `code` like 'NhomCoSoVatChatController@%'
UPDATE `permissions` SET `forSuperAdmin` = 1 where `code` like 'NhomThucPhamController@%'

ALTER TABLE `phong_ban` ADD `id_coso` TINYINT(2) NULL AFTER `isDeleted`;
ALTER TABLE `phong_ban` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;
ALTER TABLE `nhan_vien` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;

Sửa 1 permission updatePhongBan thành createPhongBan
INSERT INTO `permissions` (`id`, `name`, `code`, `forSuperAdmin`, `created_at`, `updated_at`) VALUES (NULL, 'Xóa nhân viên', 'NhanVienController@deleteNhanVien', NULL, NULL, NULL);

INSERT INTO `permissions` (`id`, `name`, `code`, `forSuperAdmin`, `created_at`, `updated_at`) VALUES (NULL, 'Quản lý tăng giảm lương nhân viên', 'TangGiamLuongController@getTangGiamLuongNhanVien', NULL, NULL, NULL);

UPDATE `permissions` SET `forSuperAdmin` = 1 where `code` like 'PhuCapController%'
INSERT INTO `permissions` (`id`, `name`, `code`, `forSuperAdmin`, `created_at`, `updated_at`) VALUES (NULL, 'Quản lý phụ cấp của nhân viên', 'PhuCapController@getPhuCapNhanVien', NULL, NULL, NULL);

UPDATE `permissions` SET `forSuperAdmin` = '1' WHERE `permissions`.`id` = 84;
UPDATE `permissions` SET `forSuperAdmin` = '1' WHERE `permissions`.`id` = 102;
UPDATE `permissions` SET `forSuperAdmin` = '1' WHERE `permissions`.`id` = 103;
UPDATE `permissions` SET `forSuperAdmin` = '1' WHERE `permissions`.`id` = 104;

INSERT INTO `permissions` (`id`, `name`, `code`, `forSuperAdmin`, `created_at`, `updated_at`) VALUES (NULL, 'Quản lý tăng giảm lương nhân viên', 'TangGiamLuongController@getTangGiamLuongNhanVien', NULL, NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `code`, `forSuperAdmin`, `created_at`, `updated_at`) VALUES (NULL, 'Cập nhật tăng giảm lương nhân viên', 'TangGiamLuongController@updateTangGiamLuongNhanVien', NULL, NULL, NULL);

UPDATE `permissions` SET `forSuperAdmin` = '1' WHERE `permissions`.`id` = 85;
UPDATE `permissions` SET `forSuperAdmin` = '1' WHERE `permissions`.`id` = 105;
