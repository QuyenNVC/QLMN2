<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// DANH SÁCH CÁC ROUTE KHÔNG CẦN ĐĂNG NHẬP
Route::get('/', function () {
    return redirect(route('login'));
});


Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});


Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('dang-nhap', 'UserController@formLogin')->name('login');
    Route::post('login', 'UserController@postLogin'); //api
    // Tính năng quên mật khẩu
    Route::get('quen-mat-khau', 'UserController@forgetPassword')->name('quen_mat_khau');
    Route::post('submitForgetPassword', 'UserController@submitForgetPassword');
    Route::get('reset-password/{token}', 'UserController@resetPasswordForm');
    Route::post('submitResetPassword', 'UserController@submitResetPassword');

    // Nhóm đối tượng phụ huynh
    Route::namespace('parents')->group(function () {
        Route::get('phu-huynh/dang-ky', 'ParentsController@formDangKy')->name('parents.formDangKy');
        Route::post('/phu-huynh/signup', 'ParentsController@signup'); //api
        // test gửi mail
        // Route::get('test-gui-mail', 'parents\ParentsController@testMail');
        Route::get('verify/{token}', 'ParentsController@verify');
        Route::get('phu-huynh/dang-nhap', 'ParentsController@formDangNhap')->name('parents.formDangNhap');
        Route::post('phu-huynh/login', 'ParentsController@login'); //api
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('dashboard', 'HomeController@index')->name('dashboard');
        // Các function dùng chung
        //Năm học
        Route::get('getSchoolYears', 'SchoolYearController@index'); //API
        Route::post('dataUser', 'UserController@getList'); //api
        // Route::get('dataUser', 'UserController@getList'); //api
        Route::get('doi-mat-khau', 'UserController@getDoiMatKhau')->name('nguoidung.doi-mat-khau');
        Route::post('doi-mat-khau', 'UserController@postDoiMatKhau');
        Route::get('dataPhongBan', 'PhongBanController@getList'); //api
        Route::get('dataPhuCap', 'PhuCapController@getList'); //api
        Route::get('dataTangGiamLuong', 'TangGiamLuongController@getList'); //api
        Route::get('dataHinhThucChamCong', 'HinhThucChamCongController@getList'); //api
        Route::get('dataLog', 'LogController@getList'); //api
        Route::get('dataNhanVien', 'NhanVienController@getList'); //api
        Route::get('dataNhanVien2', 'NhanVienController@getList2'); //api
        Route::get('dataNhanVienDangLamViec', 'NhanVienController@getListDangLamViec'); //api
        Route::get('dataNhanVien/{idPhongBan}', 'NhanVienController@getNhanViensByIdPhongBan'); //api
        Route::get('dataNhanVienDangLamViec/{idPhongBan}', 'NhanVienController@getNhanViensByIdPhongBanDangLamViec'); //api
        Route::get('dataNhanVienNghiViec/{idPhongBan?}', 'NhanVienController@getListNhanVienNghiViec'); //api
        Route::get('dataNhanVienDangLamViecPhuCap/{thang}/{nam}/{idPhongBan?}', 'NhanVienController@getListNhanVienDangLamViecPhuCap'); //api
        Route::get('dataNhanVienDangLamViecPhuCapBaoCao/{thang}/{nam}/{idPhongBan?}', 'NhanVienController@getListNhanVienDangLamViecPhuCapBaoCao'); //api
        Route::get('dataNhanVienDangLamViecTangGiamLuong/{thang}/{nam}/{idPhongBan?}', 'NhanVienController@getListNhanVienDangLamViecTangGiamLuong'); //api
        Route::get('dataNhanVienDangLamViecUngLuong/{thang}/{nam}/{idPhongBan?}', 'NhanVienController@getListNhanVienDangLamViecUngLuong'); //api
        Route::get('dataNhanVienDangLamViecUngLuongBaoCao/{thang}/{nam}/{idPhongBan?}', 'NhanVienController@getListNhanVienDangLamViecUngLuongBaoCao'); //api
        Route::get('dataNhanVienGetOne/{idPhongBan}', 'NhanVienController@getNhanViensByMaNV'); //api
        Route::get('dataChamCong/{month}/{year}', 'ChamCongController@getList');
        Route::get('getEntrollments', 'EnrollmentController@getList'); //API
        Route::get('dataRecords/{id_enrollment}', 'RecordController@getList'); //api
        Route::get('dataClass/{id}', 'ClassController@getClass'); //api
        Route::get('dataClasses', 'ClassController@getList'); //api
        Route::get('dataGrades', 'GradeController@getList'); //api
        Route::get('dataDoituongs', 'DoituongController@getList'); //api
        Route::get('dataNangKhieus', 'NangKhieuController@getList'); //api
        // Nghề nghiệp
        Route::get('dataJobs', 'JobController@getList'); //api
        Route::post('addJob', 'JobController@store'); //api
        // Dân tộc
        Route::get('dataEthnicities', 'EthnicityController@getList'); //api
        Route::post('addEthnicity', 'EthnicityController@store'); //api
        Route::get('dataDailyFees', 'DailyFeeController@getList'); //api
        Route::get('getCostDailyFee', 'CostDailyFeeController@getList'); //api
        Route::get('dataMonthlyFees', 'MonthlyFeeController@getList'); //api
        Route::get('getCostMonthlyFee/{id_coso}', 'CostMonthlyFeeController@getList'); //api
        Route::get('dataAnnualFees', 'AnnualFeeController@getList'); //api
        Route::get('getCostAnnualFee/{id_coso}', 'CostAnnualFeeController@getList'); //api
        Route::get('dataExtraServices', 'ExtraServiceController@getList'); //api
        Route::get('getCostExtraService/{id_coso}', 'CostExtraServiceController@getList'); //api
        Route::get('dataHinhThucDiemDanh', 'HinhThucDiemDanhController@getList'); //api
        Route::get('getYears', 'DiemDanhController@getYears'); //api
        Route::get('dataStudentActive/{id_class}', 'DiemDanhController@dataStudentActive'); //api
        Route::post('getStudentsFees', 'StudentFeeController@getList'); //api
        Route::post('getDataNhatKySucKhoe', 'NhatKySucKhoeController@getList'); //api
        Route::post('getSiblings', 'RecordController@getSiblings'); //api
        Route::post('checkExistRecord', 'RecordController@checkExistRecord'); //api
        Route::post('updateRecordFee', 'RecordController@updateRecordFee'); //api
        Route::get('getLastMHS', 'RecordController@getLastMHS'); //Tự động sinh mã hồ sơ

        // Dòng test
        Route::post('dataTreeStudents', 'StudentController@getTreeList'); //api

        Route::post('getClassesDiemDanh', 'DiemDanhController@getClasses'); //api
        Route::post('getDataDiemDanh', 'DiemDanhController@getList');
        Route::post('hocPhiThangNay', 'StudentFeeController@hocPhiThangNay'); //api
        Route::post('getPhysicalStandard', 'TieuChuanPhatTrienController@getList'); //api
        Route::post('dataTheoDoiSucKhoe', 'TheoDoiSucKhoeController@getList'); //api
        Route::get('dataRoles', 'RoleController@getList'); //api
        Route::get('dataPermissions', 'PermissionController@getList'); //api
        Route::get('dataRolePermission/{id}', 'RoleController@getPermissions'); //api
        Route::get('dataInfo/{id}', 'InfoController@getInfo'); //api
        Route::get('getCosos', 'InfoController@getList'); //api
        // Kết thúc các function dùng chung

        // CÁC FUNCTION PHÂN QUYỀN
        //phong ban
        Route::view('phong-ban', 'admin.phong_ban')->middleware('can:PhongBanController@quanLy')->name('phongban.quanly');

        Route::get('phu-cap', 'PhuCapController@quanLy')->middleware('can:superAdmin')->name('phucap.quanly');

        Route::get('tang-giam-luong', 'TangGiamLuongController@quanLy')->middleware('can:superAdmin')->name('tanggiamluong.quanly');

        Route::get('hinh-thuc-cham-cong', 'HinhThucChamCongController@quanLy')->middleware('can:superAdmin')->name('hinhthucchamcong.quanly');

        Route::get('log', 'LogController@quanLy')->middleware('can:LogController@quanLy')->name('log.quanly');

        Route::view('nhan-vien', 'admin.nhan_vien')->middleware('can:NhanVienController@create')->name('nhanvien.quanly');

        //bao cao nghi viec
        Route::get('bao-cao-nghi-viec', 'BaoCaoController@getBaoCaoNghiViec')->middleware('can:BaoCaoController@getBaoCaoNghiViec')->name('admin.baoCaoNghiViec');
        Route::get('bao-cao-phu-cap', 'BaoCaoController@getBaoCaoPhuCap')->middleware('can:BaoCaoController@getBaoCaoPhuCap')->name('admin.baoCaoPhuCap');
        Route::get('bao-cao-tam-ung', 'BaoCaoController@getBaoCaoTamUng')->middleware('can:BaoCaoController@getBaoCaoTamUng')->name('admin.baoCaoTamUng');
        Route::get('bao-cao-ngay-cong', 'BaoCaoController@getBaoCaoNgayCong')->middleware('can:BaoCaoController@getBaoCaoNgayCong')->name('admin.baoCaoNgayCong');
        Route::get('bao-cao-chi-luong', 'BaoCaoController@getBaoCaoChiLuong')->middleware('can:BaoCaoController@getBaoCaoChiLuong')->name('admin.baoCaoChiLuong');

        //user
        Route::post('createUser', 'UserController@createUser')->middleware('can:UserController@createUser'); //api
        Route::post('updateUser', 'UserController@updateUser')->middleware('can:UserController@updateUser'); //api
        Route::post('deleteUser', 'UserController@deleteUser')->middleware('can:UserController@deleteUser'); //api


        //phong ban
        Route::post('createPhongBan', 'PhongBanController@store')->middleware('can:PhongBanController@createPhongBan'); //api
        Route::put('updatePhongBan', 'PhongBanController@update')->middleware('can:PhongBanController@updatePhongBan'); //api
        Route::delete('deletePhongBan/{item}', 'PhongBanController@destroy')->middleware('can:PhongBanController@deletePhongBan'); //api

        //phu cap
        Route::post('createPhuCap', 'PhuCapController@createPhuCap')->middleware('can:PhuCapController@createPhuCap'); //api
        Route::post('updatePhuCap', 'PhuCapController@updatePhuCap')->middleware('can:PhuCapController@updatePhuCap'); //api
        Route::post('deletePhuCap/{id}', 'PhuCapController@deletePhuCap')->middleware('can:PhuCapController@deletePhuCap'); //api

        //tang-giam-luong
        Route::post('createTangGiamLuong', 'TangGiamLuongController@createTangGiamLuong')->middleware('can:superAdmin'); //api
        Route::post('updateTangGiamLuong', 'TangGiamLuongController@updateTangGiamLuong')->middleware('can:superAdmin'); //api
        Route::post('deleteTangGiamLuong/{id}', 'TangGiamLuongController@deleteTangGiamLuong')->middleware('can:superAdmin'); //api


        //hinh thuc cham cong
        Route::post('createHinhThucChamCong', 'HinhThucChamCongController@createHinhThucChamCong')->middleware('can:superAdmin'); //api
        Route::post('updateHinhThucChamCong', 'HinhThucChamCongController@updateHinhThucChamCong')->middleware('can:superAdmin'); //api
        Route::post('deleteHinhThucChamCong/{id}', 'HinhThucChamCongController@deleteHinhThucChamCong')->middleware('can:superAdmin'); //api

        //nhat ky hoat dong
        Route::post('createLog', 'LogController@createLog')->middleware('can:LogController@createLog'); //api
        Route::post('updateLog', 'LogController@updateLog')->middleware('can:LogController@updateLog'); //api
        Route::post('deleteLog/{id}', 'LogController@deleteLog')->middleware('can:LogController@deleteLog'); //api
        Route::get('deleteLogAll', 'LogController@deleteLogAll')->middleware('can:LogController@deleteLogAll'); //api

        //nhan vien
        Route::get('createMaNV', 'NhanVienController@createMaNV');
        Route::post('updateNhanVien', 'NhanVienController@updateNhanVien')->middleware('can:NhanVienController@updateNhanVien');
        Route::post('deleteNhanVien/{id}', 'NhanVienController@deleteNhanVien')->middleware('can:NhanVienController@deleteNhanVien'); //api
        //cham cong
        Route::get('cham-cong', 'ChamCongController@getForm')->middleware('can:ChamCongController@getForm')->name('chamcong.quanly');
        Route::post('updateChamCong', 'ChamCongController@updateChamCong')->middleware('can:ChamCongController@updateChamCong');

        //phu cap nhan vien
        Route::get('phu-cap-nhan-vien', 'PhuCapController@getPhuCapNhanVien')->middleware('can:PhuCapController@getPhuCapNhanVien')->name('phucap.phuCapNhanVien');
        Route::post('updatePhuCapNhanVien', 'PhuCapController@updatePhuCapNhanVien')->middleware('can:superAdmin');

        //tang giam luong nhan vien
        Route::get('tang-giam-luong-nhan-vien', 'TangGiamLuongController@getTangGiamLuongNhanVien')->middleware('can:TangGiamLuongController@getTangGiamLuongNhanVien')->name('tanggiamluong.tangGiamLuongNhanVien');
        Route::post('updateTangGiamLuongNhanVien', 'TangGiamLuongController@updateTangGiamLuongNhanVien')->middleware('can:TangGiamLuongController@updateTangGiamLuongNhanVien');

        //tang giam luong nhan vien
        Route::get('ung-luong-nhan-vien', 'UngLuongController@getUngLuongNhanVien')->middleware('can:UngLuongController@getUngLuongNhanVien')->name('ungluong.ungLuongNhanVien');
        Route::post('updateUngLuongNhanVien', 'UngLuongController@updateUngLuongNhanVien')->middleware('can:UngLuongController@updateUngLuongNhanVien');

        //chi luong nhan vien
        Route::get('chi-luong-nhan-vien', 'ChiLuongController@getChiLuongNhanVien')->middleware('can:ChiLuongController@getChiLuongNhanVien')->name('chiluong.chiLuongNhanVien');
        Route::get('print-pdf/{ma_nv}/{thang}/{nam}', 'ChiLuongController@print')->middleware('can:ChiLuongController@print');

        // kỳ tuyển sinh
        Route::get('ky-tuyen-sinh', 'EnrollmentController@index')->middleware('can:superAdmin')->name('enrollment.quanly');
        Route::post('createEnrollment', 'EnrollmentController@store')->middleware('can:superAdmin'); //API
        Route::post('updateEnrollment', 'EnrollmentController@update')->middleware('can:superAdmin'); //API
        Route::post('deleteEnrollment/{id}', 'EnrollmentController@destroy')->middleware('can:superAdmin'); //API

        //  hồ sơ nhập học
        Route::get('ho-so-tuyen-sinh', 'RecordController@quanly')->middleware('can:RecordController@quanly')->name('record.quanly');
        Route::get('ho-so-tuyen-sinh/{id}', 'RecordController@quanly')->middleware('can:RecordController@quanly');
        Route::get('them-ho-so-nhap-hoc', 'RecordController@getForm')->middleware('can:RecordController@getForm');
        Route::get('cap-nhat-ho-so-tuyen-sinh/{id}', 'RecordController@getForm')->middleware('can:RecordController@getForm');
        Route::get('getRecordById/{id}', 'RecordController@getRecordById')->middleware('can:RecordController@getRecordById');
        Route::post('createRecord', 'RecordController@store'); //api
        Route::post('deleteRecord/{id}', 'RecordController@deleteRecord')->middleware('can:RecordController@deleteRecord'); //api
        Route::post('existRecord', 'RecordController@existRecord');
        Route::post('arrangeClassAuto', 'RecordController@arrangeClassMultiRecord')->middleware('can:RecordController@arrangeClassMultiRecord'); //Chia lớp tự động
        Route::post('submitStudent', 'RecordController@submitStudent')->middleware('can:RecordController@submitStudent'); //Submit student from Student's form

        // học sinh
        Route::get('danh-sach-hoc-sinh', 'StudentController@index')->name('student.quanly');
        //    Route::get('danh-sach-hoc-sinh/{id}', 'StudentController@index')->name('student.quanly')->middleware('can:StudentController@index');

        // lớp học
        Route::get('lop-hoc', 'ClassController@index')->middleware(['can:ClassController@index'])->name('class.quanly');
        Route::post('createClass', 'ClassController@create')->middleware('can:ClassController@create'); //api
        Route::post('updateClass', 'ClassController@update')->middleware('can:ClassController@update'); //api
        Route::delete('deleteClass/{class}', 'ClassController@destroy')->middleware('can:ClassController@delete'); //api

        // Đối tượng
        Route::get('doi-tuong-hoc-sinh', 'DoituongController@index')->middleware('can:superAdmin')->name('doituong.quanly');
        Route::post('createDoituong', 'DoituongController@create')->middleware('can:superAdmin'); //api
        Route::post('updateDoituong', 'DoituongController@update')->middleware('can:superAdmin'); //api
        Route::post('deleteDoituong/{id}', 'DoituongController@delete')->middleware('can:superAdmin'); //api

        // Năng khiếu
        Route::get('nang-khieu', 'NangKhieuController@index')->middleware('can:superAdmin')->name('nangkhieu.quanly');
        Route::post('createNangKhieu', 'NangKhieuController@create')->middleware('can:superAdmin'); //api
        Route::post('updateNangKhieu', 'NangKhieuController@update')->middleware('can:superAdmin'); //api
        Route::post('deleteNangKhieu/{id}', 'NangKhieuController@delete')->middleware('can:superAdmin'); //api

        // Học phí theo ngày
        Route::get('loai-hoc-phi-theo-ngay', 'DailyFeeController@index')->middleware('can:DailyFeeController@index')->name('dailyfee.quanly');
        Route::post('createDailyFee', 'DailyFeeController@create')->middleware('can:DailyFeeController@create'); //api
        Route::post('updateDailyFee', 'DailyFeeController@update')->middleware('can:DailyFeeController@update'); //api
        Route::post('deleteDailyFee/{id}', 'DailyFeeController@delete')->middleware('can:DailyFeeController@delete'); //api

        // Định mức theo ngày
        Route::get('dinh-muc-theo-ngay', 'CostDailyFeeController@index')->middleware('can:CostDailyFeeController@index')->name('costdailyfee.quanly');
        Route::post('updateCostDailyFee', 'CostDailyFeeController@update')->middleware('can:CostDailyFeeController@update'); //api

        // Học phí theo tháng
        Route::get('loai-hoc-phi-theo-thang', 'MonthlyFeeController@index')->middleware('can:MonthlyFeeController@index')->name('monthlyfee.quanly');
        Route::post('createMonthlyFee', 'MonthlyFeeController@create')->middleware('can:MonthlyFeeController@create'); //api
        Route::post('updateMonthlyFee', 'MonthlyFeeController@update')->middleware('can:MonthlyFeeController@update'); //api
        Route::post('deleteMonthlyFee/{id}', 'MonthlyFeeController@delete')->middleware('can:MonthlyFeeController@delete'); //api

        // Định mức học phí theo tháng
        Route::get('dinh-muc-hoc-phi-thang', 'CostMonthlyFeeController@index')->middleware('can:CostMonthlyFeeController@index')->name('costmonthlyfee.quanly');
        Route::post('createCostMonthlyFee', 'CostMonthlyFeeController@store')->middleware('can:CostMonthlyFeeController@store'); //api
        Route::post('updateCostMonthlyFee', 'CostMonthlyFeeController@update')->middleware('can:CostMonthlyFeeController@update'); //api
        Route::post('deleteCostMonthlyFee/{id}', 'CostMonthlyFeeController@destroy')->middleware('can:CostMonthlyFeeController@destroy'); //api

        // Học phí theo năm
        Route::get('loai-hoc-phi-hang-nam', 'AnnualFeeController@index')->middleware('can:AnnualFeeController@index')->name('annualfee.quanly');
        Route::post('createAnnualFee', 'AnnualFeeController@create')->middleware('can:AnnualFeeController@create'); //api
        Route::post('updateAnnualFee', 'AnnualFeeController@update')->middleware('can:AnnualFeeController@update'); //api
        Route::post('deleteAnnualFee/{id}', 'AnnualFeeController@delete')->middleware('can:AnnualFeeController@delete'); //api

        // Định mức học phí theo năm
        Route::get('dinh-muc-hoc-phi-hang-nam', 'CostAnnualFeeController@index')->middleware('can:CostAnnualFeeController@index')->name('costannualfee.quanly');
        Route::post('createCostAnnualFee', 'CostAnnualFeeController@store')->middleware('can:CostAnnualFeeController@store'); //api
        Route::post('updateCostAnnualFee', 'CostAnnualFeeController@update')->middleware('can:CostAnnualFeeController@update'); //api
        Route::post('deleteCostAnnualFee/{id}', 'CostAnnualFeeController@destroy')->middleware('can:CostAnnualFeeController@destroy'); //api

        // Dịch vụ cộng thêm
        Route::get('dich-vu-cong-them', 'ExtraServiceController@index')->middleware('can:ExtraServiceController@index')->name('extraservice.quanly');
        Route::post('createExtraService', 'ExtraServiceController@create')->middleware('can:ExtraServiceController@create'); //api
        Route::post('updateExtraService', 'ExtraServiceController@update')->middleware('can:ExtraServiceController@update'); //api
        Route::post('deleteExtraService/{id}', 'ExtraServiceController@delete')->middleware('can:ExtraServiceController@delete'); //api

        // Định mức dịch vụ cộng thêm
        Route::get('dinh-muc-dich-vu-cong-them', 'CostExtraServiceController@index')->middleware('can:CostExtraServiceController@index')->name('costextraservice.quanly');
        Route::post('createCostExtraService', 'CostExtraServiceController@store')->middleware('can:CostExtraServiceController@store'); //api
        Route::post('updateCostExtraService', 'CostExtraServiceController@update')->middleware('can:CostExtraServiceController@update'); //api
        Route::post('deleteCostExtraService/{id}', 'CostExtraServiceController@destroy')->middleware('can:CostExtraServiceController@destroy'); //api

        //Hình thức điểm danh
        Route::get('hinh-thuc-diem-danh', 'HinhThucDiemDanhController@quanLy')->middleware('can:HinhThucDiemDanhController@quanLy')->name('HinhThucDiemDanh.quanly');
        Route::post('createHinhThucDiemDanh', 'HinhThucDiemDanhController@createHinhThucDiemDanh')->middleware('can:HinhThucDiemDanhController@createHinhThucDiemDanh'); //api
        Route::post('updateHinhThucDiemDanh', 'HinhThucDiemDanhController@updateHinhThucDiemDanh')->middleware('can:HinhThucDiemDanhController@updateHinhThucDiemDanh'); //api
        Route::post('deleteHinhThucDiemDanh/{id}', 'HinhThucDiemDanhController@deleteHinhThucDiemDanh')->middleware('can:HinhThucDiemDanhController@deleteHinhThucDiemDanh'); //api

        //Điểm danh
        Route::get('diem-danh', 'DiemDanhController@index')->middleware('can:DiemDanhController@index')->name('diemdanh.quanly');
        Route::post('updateDiemDanh', 'DiemDanhController@updateDiemDanh')->middleware('can:DiemDanhController@updateDiemDanh');

        // Thanh toán học phí hàng tháng
        Route::get('thanh-toan-hoc-phi', 'StudentFeeController@index')->middleware('can:StudentFeeController@index')->name('thanhtoanhocphi.quanly');
        Route::post('tinhHocPhi', 'StudentFeeController@tinhHocPhi')->middleware('can:StudentFeeController@tinhHocPhi'); //api
        Route::post('dongHocPhi', 'StudentFeeController@dongHocPhi')->middleware('can:StudentFeeController@dongHocPhi'); //api

        // Nhật ký sức khỏe
        Route::get('nhat-ky-suc-khoe', 'NhatKySucKhoeController@index')->middleware('can:NhatKySucKhoeController@index')->name('nhatkysuckhoe.quanly');
        Route::post('updateNhatKySucKhoe', 'NhatKySucKhoeController@update')->middleware('can:NhatKySucKhoeController@update'); //api

        // Danh mục tiêu chuẩn phát triển
        Route::get('tieu-chuan-phat-trien', 'TieuChuanPhatTrienController@index')->middleware('can:superAdmin')->name('tieuchuanphattrien.quanly');
        Route::post('updatePhysicalStandard', 'TieuChuanPhatTrienController@update')->middleware('can:superAdmin'); //api

        // Theo dõi phát triển thể chất - nhận thức
        Route::get('theo-doi-phat-trien-the-chat-nhan-thuc', 'TheoDoiSucKhoeController@index')->name('theodoisuckhoe.quanly')->middleware('can:TheoDoiSucKhoeController@index');
        Route::post('updateTheoDoiSucKhoe', 'TheoDoiSucKhoeController@update')->middleware('can:TheoDoiSucKhoeController@update'); //api

        //user
        Route::get('quan-ly-nguoi-dung', 'UserController@quanLy')->middleware('can:UserController@quanLy')->name('nguoidung.quanly');
        Route::post('activeAccount', 'UserController@activeAccount')->middleware('can:UserController@activeAccount'); //api
        Route::post('disableAccount', 'UserController@disableAccount')->middleware('can:UserController@disableAccount'); //api
        //user
        // Route::get('quan-ly-nguoi-dung', 'UserController@quanLy')->name('nguoidung.quanly');

        // role
        Route::get('quan-ly-nhom-nguoi-dung', 'RoleController@index')->middleware('can:superAdmin')->name('nhomnguoidung.quanly');
        Route::post('createRole', 'RoleController@store')->middleware('can:superAdmin'); //api
        Route::post('updateRole', 'RoleController@update')->middleware('can:superAdmin'); //api
        Route::post('deleteRole', 'RoleController@destroy')->middleware('can:superAdmin'); //api

        // permission
        Route::post('authorize', 'RoleController@handleAuthorize')->middleware('can:superAdmin'); //api

        Route::get('thong-tin-truong-mam-non', 'InfoController@index')->middleware('can:InfoController@index')->name('thongtin.quanly');
        Route::get('them-co-so', 'InfoController@getForm')->middleware("can:superAdmin");
        Route::get('cap-nhat-co-so/{id}', 'InfoController@getForm')->middleware("can:superAdmin");
        Route::post('submitInfo', 'InfoController@store')->middleware('can:InfoController@store');
        Route::post('deleteInfo', "InfoController@destroy")->middleware("can:superAdmin");

        // làm lại phần quản lý học sinh
        Route::get('dataClasses/{school_year}/{grade}', 'ClassController@getClassesByFilter');
        Route::get('getStudentsByIdClass/{id_class}', 'RecordController@getStudentsByIdClass');
        Route::get('getStudentsByIdClass', 'RecordController@getStudentsByIdClass');
        Route::post('deleteStudent', 'RecordController@deleteStudent')->middleware('can:RecordController@deleteStudent');
        // Route::get('hoc-sinh/{id}', 'StudentController@edit');

        // Quản lý thu
        Route::get('quan-ly-thu', 'QuanLyThuController@index')->middleware('can:QuanLyThuController@index')->name('quanlythu.index');
        Route::post('dataPhieuThus', 'QuanLyThuController@getList'); // lấy phiếu thu
        Route::post('submitPhieuThu', 'QuanLyThuController@submit')->middleware('can:QuanLyThuController@submit'); // Thêm, cập nhật phiếu thu
        Route::get('lastChungTuSoThu', 'QuanLyThuController@generateSoChungTu');

        // Quản lý chi
        Route::get('quan-ly-chi', 'QuanLyChiController@index')->middleware('can:QuanLyChiController@index')->name('quanlychi.index');
        Route::post('dataPhieuChis', 'QuanLyChiController@getList');
        Route::get('lastChungTuSoChi', 'QuanLyChiController@generateSoChungTu');
        Route::post('submitPhieuChi', 'QuanLyChiController@submit')->middleware('can:QuanLyChiController@submit'); // Thêm, cập nhật phiếu chi

        // Danh mục các loại chi phí
        Route::get('danh-muc-cac-loai-chi-phi', 'DanhMucChiPhiController@index')->middleware('can:DanhMucChiPhiController@index')->name('danhmucchiphi.quanly');
        Route::get('dataDanhMucChiPhi', 'DanhMucChiPhiController@getList'); //api
        Route::post('submitDanhMucChiPhi', 'DanhMucChiPhiController@store')->middleware('can:DanhMucChiPhiController@store'); //api
        Route::post('deleteDanhMucChiPhi', 'DanhMucChiPhiController@destroy')->middleware('can:DanhMucChiPhiController@destroy'); //api

        // Nhóm cơ sở vật chất
        Route::get('nhom-co-so-vat-chat', 'NhomCoSoVatChatController@index')->middleware('can:superAdmin')->name('nhomcosovatchat.quanly');
        Route::get('dataNhomCoSoVatChat', 'NhomCoSoVatChatController@getList'); //api
        Route::post('submitNhomCoSoVatChat', 'NhomCoSoVatChatController@store')->middleware('can:superAdmin'); //api
        Route::post('deleteNhomCoSoVatChat', 'NhomCoSoVatChatController@destroy')->middleware('can:superAdmin'); //api

        // Nhà cung cấp
        Route::get('danh-muc-nha-cung-cap', 'NhaCungCapController@index')->middleware('can:NhaCungCapController@index')->name('nhacungcap.quanly');
        Route::get('dataNhaCungCap', 'NhaCungCapController@getList'); //api
        Route::post('submitNhaCungCap', 'NhaCungCapController@store')->middleware('can:NhaCungCapController@store'); //api
        Route::post('deleteNhaCungCap', 'NhaCungCapController@destroy')->middleware('can:NhaCungCapController@destroy'); //api

        // Danh mục cơ sở vật chất
        Route::get('danh-muc-co-so-vat-chat', 'DanhMucCoSoVatChatController@index')->middleware('can:DanhMucCoSoVatChatController@index')->name('danhmuccosovatchat.quanly');
        Route::post('dataDanhMucCoSoVatChat', 'DanhMucCoSoVatChatController@getList'); //api
        Route::post('submitDanhMucCoSoVatChat', 'DanhMucCoSoVatChatController@store')->middleware('can:DanhMucCoSoVatChatController@store'); //api
        Route::post('deleteDanhMucCoSoVatChat', 'DanhMucCoSoVatChatController@destroy')->middleware('can:DanhMucCoSoVatChatController@destroy'); //api

        // Nhóm thực phẩm
        Route::get('nhom-thuc-pham', 'NhomThucPhamController@index')->middleware('can:superAdmin')->name('nhomthucpham.quanly');
        Route::get('dataNhomThucPham', 'NhomThucPhamController@getList'); //api
        Route::post('submitNhomThucPham', 'NhomThucPhamController@store')->middleware('can:superAdmin'); //api
        Route::post('deleteNhomThucPham', 'NhomThucPhamController@destroy')->middleware('can:superAdmin'); //api

        // nhu cầu dinh dưỡng
        Route::get('nhu-cau-dinh-dung-cho-tre', 'NhuCauDinhDuongController@index')->name('nhucaudinhduong.quanly')->middleware('can:superAdmin');
        Route::get('dataNhuCauDinhDuong', 'NhuCauDinhDuongController@getList');
        Route::post('updateNhuCauDinhDuong', 'NhuCauDinhDuongController@update')->middleware('can:superAdmin');

        // thành phần dinh dưỡng
        Route::get('thanh-phan-dinh-duong', 'ThanhPhanDinhDuongController@index')->middleware('can:ThanhPhanDinhDuongController@index')->name('thanhphandinhduong.quanly');
        Route::post('dataThanhPhanDinhDuong', 'ThanhPhanDinhDuongController@getList');
        Route::post('updateThanhPhanDinhDuong', 'ThanhPhanDinhDuongController@store')->middleware('can:ThanhPhanDinhDuongController@store');
        Route::post('deleteThanhPhanDinhDuong', 'ThanhPhanDinhDuongController@destroy')->middleware('can:ThanhPhanDinhDuongController@destroy');

        // Nhu cầu năng lượng
        Route::get('nhu-cau-nang-luong-cua-tre', 'NhuCauNangLuongController@index')->middleware('can:superAdmin')->name('nhucaunangluong.quanly');
        Route::get('dataNhuCauNangLuong', 'NhuCauNangLuongController@getList');
        Route::post('updateNhuCauNangLuong', 'NhuCauNangLuongController@store')->middleware('can:superAdmin');
        Route::post('deleteNhuCauNangLuong', 'NhuCauNangLuongController@destroy')->middleware('can:superAdmin');

        // Quản lý suất ăn
        Route::get('quan-ly-suat-an', 'SuatAnController@index')->middleware('can:SuatAnController@index')->name('suatan.quanly');
        Route::post('/dataSuatAn', 'SuatAnController@getList');
        Route::post('/getTieuChuanSuatAn', 'SuatAnController@getTieuChuanSuatAn');
        Route::post('submitSuatAn', 'SuatAnController@store')->middleware('can:SuatAnController@store');
        Route::get('/editSuatAn/{id}', 'SuatAnController@edit');
        Route::post('deleteSuatAn', 'SuatAnController@destroy')->middleware('can:SuatAnController@destroy');
        
        // Quản lý thực đơn
        Route::get('quan-ly-thuc-don', 'ThucDonController@index')->middleware('can:ThucDonController@index')->name('thucdon.quanly');
        Route::get('getNhanVienNauAn', 'ThucDonController@getNhanVienNauAn');
        Route::post('/thucDonInfo', 'ThucDonController@thucDonInfo');
        Route::post('/dataThucDons', 'ThucDonController@getList');
        Route::post('/submitThucDon', 'ThucDonController@store')->middleware('can:ThucDonController@store');
        // report employee resume
        Route::get('employee-resume', 'ReportController@employeeResume')->name('report.employeeResume');
        Route::get('get-employee-resume', 'NhanVienController@getEmployeeResume')->name('api.report.getEmployeeResume');
        Route::post('employee-resume/export-excel', 'ReportController@exportExcelEmployeeResume')->name('api.report.employeeResume.exportExcel');

        // report student resume
        Route::get('student-resume', 'ReportController@studentResume')->name('report.studentResume');
        Route::get('get-student-resume', 'RecordController@getEmployeeResume')->name('report.getStudentResume');
        Route::post('student-resume/export-excel', 'ReportController@exportExcelStudentResume')->name('api.report.studentResume.exportExcel');
        
        // report student birthday
        Route::get('student-birthday', 'ReportController@studentBirthday')->name('report.studentBirthday');

        // report student birthday
        Route::get('teacher-class', 'ReportController@teacherClass')->name('report.teacherClass');
        Route::get('get-data-teacher-class', 'ClassController@getDataTeacherClass')->name('api.report.getDataTeacherClass');
        Route::post('teacher-class/export-excel', 'ReportController@exportExcelTeacherClass')->name('api.report.teacherClass.exportExcel');

        // report monitor physical condition student
        Route::get('monitor-physical-condition-student', 'ReportController@monitorPhysicalConditionStudent')->name('report.monitorPhysicalConditionStudent');
        Route::get('get-monitor-physical', 'TheoDoiSucKhoeController@getDataMonitorPhysical')->name('api.report.getDataMonitorPhysical');
        Route::post('monitor-physical/export-excel', 'ReportController@exportExcelMonitorPhysical')->name('api.report.monitorPhysical.exportExcel');

        // report timekeeping employee
        Route::get('timekeeping-employee', 'ReportController@timekeepingEmployee')->name('report.timekeepingEmployee');
        Route::post('timekeeping-employee/export-excel', 'ReportController@exportExcelTimekeepingEmployee')->name('api.report.timekeepingEmployee.exportExcel');

        // report salaries
        Route::get('report-salary', 'ReportController@salary')->name('report.salary');
        Route::get('get-data-salary', 'ChiLuongController@getDataSalary')->name('api.report.getDataSalary');
        Route::post('report-salary/export-excel', 'ReportController@exportExcelReportSalary')->name('api.report.reportSalary.exportExcel');

        // report attendance
        Route::get('attendance', 'ReportController@attendance')->name('report.attendance');
        Route::get('get-data-attendance', 'DiemDanhController@getDataAttendance')->name('api.report.getDataAttendance');
        Route::post('report-attendance/export-excel', 'ReportController@exportExcelReportAttendance')->name('api.report.reportAttendance.exportExcel');

        // Route::fallback(function() {
        //     return redirect('dashboard');
        // });
    });
});
