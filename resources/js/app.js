import Vue from "vue";

// component
import Login from "./component/Login.vue";
import User from "./component/User.vue";
import PhongBan from "./component/phong-ban.vue";
import PhuCap from "./component/phu-cap.vue";
import TangGiamLuong from "./component/tang-giam-luong.vue";
import HinhThucChamCong from "./component/hinh-thuc-cham-cong.vue";
import NhanVien from "./component/nhan-vien.vue";
import Record from "./component/record.vue";
import ChamCong from "./component/cham-cong.vue";
import Log from "./component/log.vue";
import BaoCaoNghiViec from "./component/bao-cao-nghi-viec.vue";
import PhuCapNhanVien from "./component/phu-cap-nhan-vien.vue";
import TangGiamLuongNhanVien from "./component/tang-giam-luong-nhan-vien.vue";
import UngLuongNhanVien from "./component/ung-luong-nhan-vien.vue";
import ChiLuongNhanVien from "./component/chi-luong-nhan-vien.vue";
import BaoCaoPhuCapNhanVien from "./component/bao-cao-phu-cap-nhan-vien.vue";
import BaoCaoTamUngNhanVien from "./component/bao-cao-tam-ung-nhan-vien.vue";
import BaoCaoNgayCongNhanVien from "./component/bao-cao-ngay-cong-nhan-vien.vue";
import DoiMatKhau from "./component/doi-mat-khau.vue";
import PrintPdf from "./component/print-pdf.vue";
import Enrollment from "./component/enrollment.vue";
import StudentList from "./component/student-list.vue";
import LopHoc from "./component/class.vue";
import DoiTuong from "./component/doi-tuong.vue";
import NangKhieu from "./component/nang-khieu.vue";
import DailyFee from "./component/daily-fee.vue";
import CostDailyFee from "./component/cost-daily-fee.vue";
import MonthlyFee from "./component/monthly-fee.vue";
import CostMonthlyFee from "./component/cost-monthly-fee.vue";
import AnnualFee from "./component/annual-fee.vue";
import CostAnnualFee from "./component/cost-annual-fee.vue";
import ExtraService from "./component/extra-service.vue";
import CostExtraService from "./component/cost-extra-service.vue";
import HinhThucDiemDanh from "./component/hinh-thuc-diem-danh.vue";
import DiemDanh from "./component/diem-danh.vue";
import StudentFee from "./component/student-fee";
import NhatKySucKhoe from "./component/nhat-ky-suc-khoe";
import TieuChuanPhatTrien from "./component/tieu-chuan-phat-trien";
import TheoDoiSucKhoe from "./component/theo-doi-suc-khoe";
import Role from "./component/role";
import ParentsSignUp from "./component/parents/sign-up";
import ParentsLogin from "./component/parents/login";
import InfoList from "./component/info-list";
import Info from "./component/info";
import ForgetPassword from "./component/forget-password";
import ResetPassword from "./component/reset-password";
import QuanLyThu from "./component/quan-ly-thu";
import QuanLyChi from "./component/quan-ly-chi";
import DanhMucChiPhi from "./component/danh-muc-chi-phi";
import NhomCoSoVatChat from "./component/nhom-co-so-vat-chat";
import NhomThucPham from "./component/nhom-thuc-pham";
import DanhMucCoSoVatChat from "./component/danh-muc-co-so-vat-chat";
import NhaCungCap from "./component/nha-cung-cap";
import ThanhPhanDinhDuong from "./component/thanh-phan-dinh-duong";
import NhuCauDinhDuong from "./component/nhu-cau-dinh-duong";
import EmployeeResume from "./component/report/employee-resume";
import StudentBirthday from "./component/report/student-birthday";
import StudentResume from "./component/report/student-resume";
import TeacherClass from "./component/report/teacher-class";
import MonitorPhysicalConditionStudent from "./component/report/monitor-physical-condition-student";
import TimekeepingEmployee from "./component/report/timekeeping-employee";
import ReportSalary from "./component/report/report-salary";
import Attendance from "./component/report/attendance";
import NhuCauNangLuong from "./component/nhu-cau-nang-luong";
import SuatAn from "./component/suat-an";
import ThucDon from "./component/thuc-don";

import ElementUI from "element-ui";
import "element-ui/lib/theme-chalk/index.css";
import elementLocale from "element-ui/lib/locale/lang/vi";
import VueTheMask from "vue-the-mask";
import money from "v-money";
import VueRouter from "vue-router";

// register directive v-money and component <money>
Vue.use(money, { precision: 4 });
Vue.use(VueTheMask);
Vue.use(ElementUI, { locale: elementLocale });
Vue.use(require("vue-moment"));
Vue.use(VueRouter);

const app = new Vue({
    el: "#app",
    components: {
        Login,
        User,
        PhongBan,
        PhuCap,
        HinhThucChamCong,
        Log,
        NhanVien,
        Record,
        ChamCong,
        BaoCaoNghiViec,
        PhuCapNhanVien,
        TangGiamLuong,
        TangGiamLuongNhanVien,
        UngLuongNhanVien,
        ChiLuongNhanVien,
        BaoCaoPhuCapNhanVien,
        BaoCaoTamUngNhanVien,
        BaoCaoNgayCongNhanVien,
        DoiMatKhau,
        PrintPdf,
        Enrollment,
        LopHoc,
        DoiTuong,
        NangKhieu,
        DailyFee,
        CostDailyFee,
        MonthlyFee,
        CostMonthlyFee,
        AnnualFee,
        CostAnnualFee,
        ExtraService,
        CostExtraService,
        HinhThucDiemDanh,
        DiemDanh,
        StudentFee,
        NhatKySucKhoe,
        TieuChuanPhatTrien,
        TheoDoiSucKhoe,
        ParentsSignUp,
        ParentsLogin,
        Role,
        InfoList,
        Info,
        ForgetPassword,
        ResetPassword,
        StudentList,
        QuanLyThu,
        QuanLyChi,
        DanhMucChiPhi,
        NhomCoSoVatChat,
        NhaCungCap,
        DanhMucCoSoVatChat,
        NhomThucPham,
        ThanhPhanDinhDuong,
        NhuCauDinhDuong,
        NhuCauNangLuong,
        SuatAn,
        ThucDon,
        EmployeeResume,
        StudentResume,
        StudentBirthday,
        TeacherClass,
        MonitorPhysicalConditionStudent,
        TimekeepingEmployee,
        ReportSalary,
        Attendance
    }
});

let logo_wrapper = document.querySelector(".logo_wrapper");
if (logo_wrapper) {
    let logo_wrapper_width = logo_wrapper.offsetWidth;
    logo_wrapper.style.height = logo_wrapper_width + "px";
}
