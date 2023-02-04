<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('phong_ban')->insert([
            array('name'=>'Phòng ban 01'),
            array('name'=>'Phòng ban 02'),
            array('name'=>'Phòng ban 03'),
            array('name'=>'Phòng ban 04'),
            array('name'=>'Phòng ban 05'),
            array('name'=>'Phòng ban 06'),
            array('name'=>'Phòng ban 07'),
            array('name'=>'Phòng ban 08'),
            array('name'=>'Phòng ban 09'),
            array('name'=>'Phòng ban 10'),
          ]);

        DB::table('users')->insert([
            array('name'=>'Admin', 'username' => 'admin', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '-1'),
            array('name'=>'Đổ Trọng Hảo', 'username' => 'dotronghao', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '1'),
            array('name'=>'Trần Thị Mỹ Duyên', 'username' => 'tranthimyduyen', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '1'),
            array('name'=>'Võ Lê Khánh Duy', 'username' => 'volekhanhduy', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '1'),
            array('name'=>'Dương Quốc Tuấn', 'username' => 'duongquoctuan', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '1'),
            // array('name'=>'Nguyễn Minh Thư', 'username' => 'nguyenminhthu', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '1'),
            // array('name'=>'Phạm Huỳnh Việt Tú', 'username' => 'phamhuynhviettu', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '5'),
            // array('name'=>'Đoàn Thị Yến Nhi', 'username' => 'doanthiyennhi', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '5'),
            // array('name'=>'Nguyễn Huỳnh Công Minh', 'username' => 'nguyenhuynhcongminh', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '5'),
            // array('name'=>'Nguyễn Thanh Hải', 'username' => 'nguyenthanhhai', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '5'),
            // array('name'=>'Nguyễn Tấm Thạch', 'username' => 'nguyentamthach', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '8'),
            // array('name'=>'Nguyễn Thị Thùy Dương', 'username' => 'nguyenthithuyduong', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '8'),
            // array('name'=>'Võ Minh Châu', 'username' => 'vominhchau', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '8'),
            // array('name'=>'Thạch Visal', 'username' => 'thachvisal', 'password' => '$2y$10$CV1hLs8njk1R4mgdMv7vcepqA51afPniQZ5K68o12N00Wh94rdnqy', 'id_phong_ban' => '10'),
          ]);

          DB::table('phu_cap')->insert([
            array('name'=>'Xăng dầu', 'phu_cap' => '200000', 'don_vi_tinh' => 'vnd', 'ghi_chu' => ''),
            array('name'=>'Cơm', 'phu_cap' => '300000', 'don_vi_tinh' => 'vnd', 'ghi_chu' => ''),
            array('name'=>'Tăng ca', 'phu_cap' => '10', 'don_vi_tinh' => '%', 'ghi_chu' => ''),
            array('name'=>'Nước uống', 'phu_cap' => '100000', 'don_vi_tinh' => 'vnd', 'ghi_chu' => ''),
            array('name'=>'Điện', 'phu_cap' => '200000', 'don_vi_tinh' => 'vnd', 'ghi_chu' => ''),
          ]);

          DB::table('tang_giam_luong')->insert([
            array('name'=>'Đi trễ', 'tang_giam' => '-200000', 'ghi_chu' => ''),
            array('name'=>'Nhân viên xuất sắc', 'tang_giam' => '300000', 'ghi_chu' => ''),
            array('name'=>'Thưởng', 'tang_giam' => '100000', 'ghi_chu' => ''),
            array('name'=>'Sinh nhật', 'tang_giam' => '100000', 'ghi_chu' => ''),
            array('name'=>'Bonus', 'tang_giam' => '200000', 'ghi_chu' => ''),
          ]);

          DB::table('hinh_thuc_cham_cong')->insert([
            array('name'=>'Làm ngày thường','ngay_cong' => '1', 'ghi_chu' => ''),
            array('name'=>'Nghỉ bù','ngay_cong' => '0', 'ghi_chu' => ''),
            array('name'=>'Làm ngày nghỉ lễ','ngay_cong' => '2', 'ghi_chu' => ''),
            array('name'=>'Làm nữa ngày','ngay_cong' => '0.5', 'ghi_chu' => ''),
            array('name'=>'Làm + Tăng ca','ngay_cong' => '1.5', 'ghi_chu' => ''),
            array('name'=>'Nghỉ ốm đau','ngay_cong' => '0', 'ghi_chu' => ''),
            array('name'=>'Nghỉ thai sản','ngay_cong' => '0', 'ghi_chu' => ''),
            array('name'=>'Nghỉ phép','ngay_cong' => '0', 'ghi_chu' => ''),
            array('name'=>'Nghỉ lễ Tết','ngay_cong' => '0', 'ghi_chu' => ''),
            array('name'=>'Nghỉ không lương','ngay_cong' => '0', 'ghi_chu' => ''),
          ]);

          DB::table('nhan_vien')->insert([
            array(
                'ma_nv' => 'NV1',
                'fullname' => 'Nguyễn Minh Thư',
                'gender' => 'Nữ',
                'birthday' => '1999-01-01',
                'dan_toc' => 'Kinh',
                'cmnd' => '330991901',
                'noi_cap' => 'CA Trà Vinh',
                'ngay_cap' => '2014-01-01',
                'phone' => '0706663781',
                'email' => 'minhthu@gmail.com',
                'luong_ngay' => '100000',
                'address' => 'Tiểu Cần Trà Vinh',
                'ngay_vao_lam' => '2021-01-01',
                'ngay_nghi_lam' => '2021-04-01',
                'id_phong_ban' => '1',
                'status_nghi_viec' => '1'
            ),
            array(
                'ma_nv' => 'NV2',
                'fullname' => 'Phạm Huỳnh Việt Tú',
                'gender' => 'Nam',
                'birthday' => '1999-01-01',
                'dan_toc' => 'Kinh',
                'cmnd' => '330991902',
                'noi_cap' => 'CA Trà Vinh',
                'ngay_cap' => '2014-01-01',
                'phone' => '0706663782',
                'email' => 'phamtu@gmail.com',
                'luong_ngay' => '100000',
                'address' => 'Thành phố Trà Vinh',
                'ngay_vao_lam' => '2021-01-01',
                'ngay_nghi_lam' => null,
                'id_phong_ban' => '1',
                'status_nghi_viec' => '0'
            ),
            array(
                'ma_nv' => 'NV3',
                'fullname' => 'Đoàn Thị Yến Nhi',
                'gender' => 'Nữ',
                'birthday' => '1999-01-01',
                'dan_toc' => 'Kinh',
                'cmnd' => '330991903',
                'noi_cap' => 'CA Trà Vinh',
                'ngay_cap' => '2014-01-01',
                'phone' => '0706663783',
                'email' => 'doannhi@gmail.com',
                'luong_ngay' => '100000',
                'address' => 'Thành phố Trà Vinh',
                'ngay_vao_lam' => '2021-01-01',
                'ngay_nghi_lam' => null,
                'id_phong_ban' => '1',
                'status_nghi_viec' => '0'
            ),
            array(
                'ma_nv' => 'NV4',
                'fullname' => 'Nguyễn Thanh Hải',
                'gender' => 'Nam',
                'birthday' => '1999-01-01',
                'dan_toc' => 'Kinh',
                'cmnd' => '330991904',
                'noi_cap' => 'CA Trà Vinh',
                'ngay_cap' => '2014-01-01',
                'phone' => '0706663784',
                'email' => 'nguyenhai@gmail.com',
                'luong_ngay' => '100000',
                'address' => 'Thành phố Trà Vinh',
                'ngay_vao_lam' => '2021-01-01',
                'ngay_nghi_lam' => null,
                'id_phong_ban' => '2',
                'status_nghi_viec' => '0'
            ),
            array(
                'ma_nv' => 'NV5',
                'fullname' => 'Nguyễn Huỳnh Công Minh',
                'gender' => 'Nam',
                'birthday' => '1999-01-01',
                'dan_toc' => 'Kinh',
                'cmnd' => '330991905',
                'noi_cap' => 'CA Trà Vinh',
                'ngay_cap' => '2014-01-01',
                'phone' => '0706663785',
                'email' => 'nguyenminh@gmail.com',
                'luong_ngay' => '100000',
                'address' => 'Thành phố Trà Vinh',
                'ngay_vao_lam' => '2021-01-01',
                'ngay_nghi_lam' => null,
                'id_phong_ban' => '2',
                'status_nghi_viec' => '0'
            ),
            array(
                'ma_nv' => 'NV6',
                'fullname' => 'Nguyễn Tấm Thạch',
                'gender' => 'Nam',
                'birthday' => '1999-01-01',
                'dan_toc' => 'Kinh',
                'cmnd' => '330991906',
                'noi_cap' => 'CA Trà Vinh',
                'ngay_cap' => '2014-01-01',
                'phone' => '0706663786',
                'email' => 'nguyenthach@gmail.com',
                'luong_ngay' => '100000',
                'address' => 'Thành phố Trà Vinh',
                'ngay_vao_lam' => '2021-01-01',
                'ngay_nghi_lam' => null,
                'id_phong_ban' => '5',
                'status_nghi_viec' => '1'
            ),
            array(
                'ma_nv' => 'NV7',
                'fullname' => 'Thạch Visal',
                'gender' => 'Nam',
                'birthday' => '1998-07-01',
                'dan_toc' => 'Khmer',
                'cmnd' => '330991907',
                'noi_cap' => 'CA Trà Vinh',
                'ngay_cap' => '2014-01-01',
                'phone' => '0706663787',
                'email' => 'thachvisal@gmail.com',
                'luong_ngay' => '100000',
                'address' => 'Thành phố Trà Vinh',
                'ngay_vao_lam' => '2021-01-01',
                'ngay_nghi_lam' => null,
                'id_phong_ban' => '10',
                'status_nghi_viec' => '1'
            ),
          ]);

          DB::table('cham_cong')->insert([
            array(
                'data' => '{ "ma_nv" : "NV1", "1": "1", "2": "2", "3": "3", "4": "4", "5": "5", "6": "6", "7": "7", "8": "8", "9": "1",  "10": "2",}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV1',
            ),
            array(
                'data' => '{"ma_nv" : "NV2","11": "1","12": "1","13": "3","14": "4","15": "5","16": "6","17": "7","18": "8","19": "1","20": "2",}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV2',
            ),
            array(
                'data' => '{ "ma_nv" : "NV3", "1": "1", "2": "2", "3": "3", "4": "1", "5": "5", "6": "1", "7": "7", "8": "8", "9": "1", "10": "2",}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV3',
            ),
            array(
                'data' => '{ "ma_nv" : "NV4", "11": "1", "12": "2", "13": "3", "14": "4", "15": "5", "16": "6", "17": "7", "18": "8", "19": "1", "20": "2",}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV4',
            ),
            array(
                'data' => '{ "ma_nv" : "NV5", "1": "1", "2": "2", "3": "3", "4": "4", "5": "5", "6": "6", "7": "7", "8": "8", "9": "1", "10": "2",}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV5',
            ),
          ]);

          DB::table('phu_cap_nhan_vien')->insert([
            array(
                'data' => '{ "ma_nv" : "NV1", "1": true, "2": false, "3": true, "4": false, "5": true}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV1',
            ),
            array(
                'data' => '{ "ma_nv" : "NV2", "1": false, "2": false, "3": true, "4": true, "5": true}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV2',
            ),
            array(
                'data' => '{ "ma_nv" : "NV3", "1": false, "2": false, "3": false, "4": false, "5": true}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV3',
            ),
            array(
                'data' => '{ "ma_nv" : "NV4", "1": true, "2": true, "3": true, "4": false, "5": true}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV4',
            ),
            array(
                'data' => '{ "ma_nv" : "NV5", "1": true, "2": false, "3": false, "4": false, "5": false}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV5',
            ),
          ]);

          DB::table('tang_giam_luong_nhan_vien')->insert([
            array(
                'data' => '{ "ma_nv" : "NV1", "1": true, "2": false, "3": true, "4": false, "5": true}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV1',
            ),
            array(
                'data' => '{ "ma_nv" : "NV2", "1": false, "2": false, "3": true, "4": true, "5": true}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV2',
            ),
            array(
                'data' => '{ "ma_nv" : "NV3", "1": false, "2": false, "3": false, "4": false, "5": true}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV3',
            ),
            array(
                'data' => '{ "ma_nv" : "NV4", "1": true, "2": true, "3": true, "4": false, "5": true}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV4',
            ),
            array(
                'data' => '{ "ma_nv" : "NV5", "1": true, "2": false, "3": false, "4": false, "5": false}',
                'month' => '4',
                'year' => '2021',
                'ma_nv' => 'NV5',
            ),
          ]);


          DB::table('ung_luong_nhan_vien')->insert([
            array('tien_ung' => '500000', 'month' => '4', 'year' => '2021', 'ma_nv' => 'NV1',),
            array('tien_ung' => '300000', 'month' => '4', 'year' => '2021', 'ma_nv' => 'NV2',),
            array('tien_ung' => '200000', 'month' => '4', 'year' => '2021', 'ma_nv' => 'NV3',),
            array('tien_ung' => '', 'month' => '4', 'year' => '2021', 'ma_nv' => 'NV4',),
            array('tien_ung' => '100000', 'month' => '4', 'year' => '2021', 'ma_nv' => 'NV5',),
          ]);

    }
}
