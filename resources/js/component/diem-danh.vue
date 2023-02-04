<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Điểm danh học sinh</h4>
        </div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
      <!-- ============================================================== -->
      <!-- Start Page Content -->
      <!-- ============================================================== -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="form-group-input" v-show="isErrorForm">
              <el-alert :title="titleMessage" type="warning" effect="dark">
              </el-alert>
            </div>
            <div class="wrap-filter">
              <div class="form-group-input">
                <span class="label-input">Tháng/năm: </span>
                <el-date-picker
                  v-model="filter.month"
                  type="month"
                  placeholder="Chọn tháng/năm"
                  format="MM/yyyy"
                ></el-date-picker>
              </div>
              <div class="form-group-input">
                <span class="label-input">Năm học: </span>
                <el-select v-model="filter.school_year" placeholder="Năm học">
                  <el-option
                    v-for="school_year in school_years"
                    :key="school_year.id"
                    :label="school_year.period"
                    :value="school_year.id"
                  >
                  </el-option>
                </el-select>
              </div>
              <div class="form-group-input">
                <span class="label-input">Khối lớp: </span>
                <el-select v-model="filter.grade" placeholder="Khối lớp">
                  <el-option
                    v-for="grade in grades"
                    :key="grade.id"
                    :label="grade.name"
                    :value="grade.id"
                  >
                  </el-option>
                </el-select>
              </div>
              <div class="form-group-input">
                <span class="label-input">Lớp: </span>
                <el-select
                  v-model="filter.class"
                  placeholder="Lớp"
                  filterable=""
                >
                  <el-option
                    v-for="lop in lops"
                    :key="lop.id"
                    :label="lop.name"
                    :value="lop.id"
                  >
                  </el-option>
                </el-select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row wrap-content-right">
        <div class="col-12 content-right">
          <div class="label-thong-tin-nhan-vien">
            {{ className }}
          </div>
          <hr />
          <el-alert
            class="show-error"
            :title="alert.title"
            :type="alert.type"
            v-show="alert.show"
            effect="dark"
          >
          </el-alert>
          <div class="wrap-form">
            <table>
              <tr>
                <th><input type="checkbox" name="checkAll" /></th>
                <th class="ma-nv">Mã học sinh</th>
                <th class="ho-ten" style="min-width: 300px">Họ Tên</th>
                <th v-if="hienThiNgayHomNay">
                  {{ getDateOfWeek(dateOfMonth) }} {{ dateOfMonth }}
                </th>
                <th v-for="date in dateOfMonth" :key="'Dateth' + date" v-else>
                  {{ getDateOfWeek(date) }} {{ date }}
                </th>
              </tr>
              <tr v-for="(item, index) in students" :key="item.index">
                <td><input type="checkbox" :data-id="item.id" /></td>
                <td>{{ item.id }}</td>
                <td style="min-width: 300px">{{ item.name }}</td>
                <td
                  v-if="hienThiNgayHomNay"
                  @dblclick="showSelectDiemDanh(item.id, dateOfMonth)"
                >
                  <span
                    :id="'select' + item.id + dateOfMonth"
                    v-if="getDiemDanhOfStudent(item.id).length == 0"
                  >
                  </span>
                  <span
                    v-for="diemDanh in getDiemDanhOfStudent(item.id)"
                    :key="diemDanh.ma_hs"
                    :id="'select' + item.id + dateOfMonth"
                    v-else
                  >
                    {{ diemDanh[dateOfMonth] ? diemDanh[dateOfMonth] : "" }}
                  </span>
                  <div
                    class="closeselect"
                    :class="'closeselect' + item.id + dateOfMonth"
                    v-show="false"
                    @click="updateStatusDiemDanh(item.id, dateOfMonth)"
                  >
                    x
                  </div>
                  <el-select
                    v-model="test"
                    placeholder=""
                    v-show="false"
                    :class="'select' + item.id + dateOfMonth"
                    @change="updateStatusDiemDanh(item.id, dateOfMonth)"
                    style="width: 65px"
                  >
                    <el-option
                      v-for="htcc in hinhThucDiemDanhs"
                      :key="htcc.id"
                      :label="htcc.name"
                      :value="htcc.id"
                    >
                    </el-option>
                  </el-select>
                </td>
                <td
                  v-for="date in dateOfMonth"
                  :key="'Datetd' + date"
                  @dblclick="showSelectDiemDanh(item.id, date)"
                  v-else
                >
                  <span
                    :id="'select' + item.id + date"
                    v-if="getDiemDanhOfStudent(item.id).length == 0"
                  >
                  </span>
                  <span
                    v-for="diemDanh in getDiemDanhOfStudent(item.id)"
                    :key="diemDanh.ma_hs"
                    :id="'select' + item.id + date"
                    v-else
                  >
                    {{ diemDanh[date] ? diemDanh[date] : "" }}
                  </span>
                  <div
                    class="closeselect"
                    :class="'closeselect' + item.id + date"
                    v-show="false"
                    @click="updateStatusDiemDanh(item.id, date)"
                  >
                    x
                  </div>
                  <el-select
                    v-model="test"
                    placeholder=""
                    v-show="false"
                    :class="'select' + item.id + date"
                    @change="updateStatusDiemDanh(item.id, date)"
                    style="width: 65px"
                  >
                    <el-option
                      v-for="htcc in hinhThucDiemDanhs"
                      :key="htcc.id"
                      :label="htcc.name"
                      :value="htcc.id"
                    >
                    </el-option>
                  </el-select>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="col-12">
          <div class="row wrap-footer-content">
            <div class="col-6 hinh-thuc-cham-cong">
              <div
                class="item-hinh-thuc-cham-cong"
                v-for="item in hinhThucDiemDanhs"
                :key="item.id"
              >
                [{{ item.id }}] = {{ item.name }}
              </div>
            </div>
            <div class="col-6 btn-process">
              <el-button type="primary" @click="setHienThiNgayHomNay()"
                >Hiển thị hôm nay</el-button
              >
              <el-button type="warning">Xuất Excel</el-button>
            </div>
          </div>
        </div>
      </div>
      <!-- ============================================================== -->
      <!-- End PAge Content -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Right sidebar -->
      <!-- ============================================================== -->
      <!-- .right-sidebar -->
      <!-- ============================================================== -->
      <!-- End Right sidebar -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <footer-qlmn></footer-qlmn>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
  </div>
</template>

<script>
import VTreeview from "v-treeview";
import axios from "axios";
import { mask } from "vue-the-mask";
import moment from "moment";
import { errorMessage } from "../errors/error-code";
import FooterQlmn from "../layouts/footer-qlmn.vue";

export default {
  name: "diem-danh",
  components: {
    VTreeview,
    FooterQlmn,
  },
  props: {
    phongBanUser: {
      type: String,
    },
  },
  directives: { mask },
  data() {
    return {
      alert: {
        title: "",
        type: "success",
        show: false,
      },
      titleMessage: "",
      isErrorForm: false,
      months: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
      // years: [],
      school_years: [],
      grades: [],
      lops: [],
      filter: {
        school_year: "",
        grade: "",
        class: "",
        month: "",
        year: "",
      },
      className: "",
      dateOfMonth: 0,
      students: [],
      test: "",
      diemDanhs: [],
      hienThiNgayHomNay: false,
      hinhThucDiemDanhs: [],
    };
  },
  methods: {
    async getAllSchoolYears() {
      await axios
        .get("/getSchoolYears")
        .then((promise) => {
          if (promise.data.status) {
            this.school_years = promise.data.school_years;
            let d = new Date();
            if (d.getMonth() + 1 < 6) {
              this.filter.school_year = this.school_years.find(
                (item) => item.name == d.getFullYear() - 1
              ).id;
            } else {
              this.filter.school_year = this.school_years.find(
                (item) => item.name == d.getFullYear()
              ).id;
            }
          }
        })
        .catch((rejected) => {});
    },
    async getGrades() {
      await axios
        .get("/dataGrades")
        .then((promise) => {
          if (promise.data.status) {
            this.grades = promise.data.grades;
            this.filter.grade = this.grades[0].id;
          }
        })
        .catch();
    },
    async getClasses() {
      await axios
        .post("/getClassesDiemDanh", this.filter)
        .then((promise) => {
          if (promise.data.status) {
            this.lops = promise.data.lops;
          }
        })
        .catch();
    },
    async getDataDiemDanh() {
      await axios
        .post("/getDataDiemDanh", this.filter)
        .then((response) => {
          if (response.data.status) {
            this.diemDanhs = response.data.diemDanhs;
            this.diemDanhs.forEach((item, index) => {
              this.diemDanhs[index] = JSON.parse(`${this.diemDanhs[index]}`);
              this.$forceUpdate();
            });
          }
        })
        .catch();
    },
    async getHinhThucDiemDanhs(id_coso = null) {
      await axios
        .get("dataHinhThucDiemDanh", {
          params: {
            id_coso,
          },
        })
        .then((response) => {
          if (response.data.status == true) {
            this.hinhThucDiemDanhs = response.data.hinhThucs;
          }
        });
    },
    showAlert(title, type) {
      this.alert.title = title;
      this.alert.type = type;
      this.alert.show = true;
    },
    setAlertDefault() {
      this.alert.title = "";
      this.alert.type = "success";
      this.alert.show = false;
    },
    formatDate(date) {
      var d = new Date(date),
        month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear();

      if (month.length < 2) month = "0" + month;
      if (day.length < 2) day = "0" + day;

      return [year, month, day].join("-");
    },
    async getStudent() {
      await axios
        .get("dataStudentActive/" + this.filter.class)
        .then((response) => {
          if (response.data.status == true) {
            this.students = response.data.students;
          }
        });
    },
    setDateOfMonth() {
      let month = moment(this.filter.month);
      this.dateOfMonth = month.daysInMonth();
    },
    getDateOfWeek(date) {
      let month = moment(this.filter.month);
      if (this.hienThiNgayHomNay) {
        month = moment();
      }

      let dt = moment(
        month.format("Y") + "-" + month.format("M") + "-" + date,
        "YYYY-MM-DD HH:mm:ss"
      );
      return this.convertDayNameEnToVi(dt.format("dddd"));
    },
    convertDayNameEnToVi(text) {
      switch (text) {
        case "Monday":
          return "T2";
        case "Tuesday":
          return "T3";
        case "Wednesday":
          return "T4";
        case "Thursday":
          return "T5";
        case "Friday":
          return "T6";
        case "Saturday":
          return "T7";
        case "Sunday":
          return "CN";
      }
    },
    showSelectDiemDanh(ma_hs, date) {
      if (this.nhoHonNgayHienTai(date)) {
        if (this.diemDanhs.length > 0) {
          let flag = 0;
          this.diemDanhs.forEach((item) => {
            if (item.ma_hs) {
              if (item.ma_hs == ma_hs) {
                if (item[date]) {
                  this.test = item[date];
                } else {
                  this.test = "";
                }
                flag = 1;
              }
            }
          });
          if (flag == 0) {
            this.test = "";
          }
        } else {
          this.test = "";
        }

        let ref = "select" + ma_hs + date;
        let refclose = "closeselect" + ma_hs + date;
        let select = document.getElementsByClassName(ref);
        select[0].style.display = "block";
        let closeselect = document.getElementsByClassName(refclose);
        closeselect[0].style.display = "block";
        document.getElementById(ref).style.display = "none";
      }
    },
    async updateStatusDiemDanh(ma_hs, date) {
      if (this.diemDanhs.length > 0) {
        let flag = 0;
        this.diemDanhs.forEach((item, index) => {
          if (item.ma_hs) {
            if (item.ma_hs == ma_hs) {
              if (item[date]) {
                this.diemDanhs[index][date] = this.test;
              } else {
                this.diemDanhs[index][date] = this.test;
              }
              flag = 1;
            }
          }
        });
        if (flag == 0) {
          let diemDanh = {
            ma_hs: ma_hs,
          };

          diemDanh[date] = this.test;
          this.diemDanhs.push(diemDanh);
        }
      } else {
        let diemDanh = {
          ma_hs: ma_hs,
        };

        diemDanh[date] = this.test;
        this.diemDanhs.push(diemDanh);
      }
      await axios
        .post("updateDiemDanh", {
          diemDanhs: this.diemDanhs,
          month: this.filter.month,
          year: this.filter.year,
        })
        .then((response) => {})
        .catch((e) => {
          this.$notify({
            title: "Thông báo",
            message: errorMessage[e.response.status],
            type: "warning",
          });
        });

      let ref = "select" + ma_hs + date;
      let refclose = "closeselect" + ma_hs + date;
      let select = document.getElementsByClassName(ref);
      select[0].style.display = "none";
      let closeselect = document.getElementsByClassName(refclose);
      closeselect[0].style.display = "none";
      document.getElementById(ref).style.display = "block";
      document.getElementById(ref).innerHTML = this.test;
      this.$forceUpdate();
    },
    nhoHonNgayHienTai(date) {
      let dateNow = new Date();
      var a = moment(
        [dateNow.getFullYear(), dateNow.getMonth() + 1, dateNow.getDate()],
        "YYYY-MM-DD"
      );
      var b = moment(
        [
          this.filter.month.getFullYear(),
          this.filter.month.getMonth() + 1,
          date,
        ],
        "YYYY-MM-DD"
      );
      if (a.diff(b, "days") >= 0) {
        // if(a-b > 0) {
        return true;
      } else {
        return false;
      }
    },
    setClassName() {
      var lop = this.lops.find((item) => item.id == this.filter.class);
      this.className = lop ? lop.name : "";
    },
    getDiemDanhOfStudent(ma_hs) {
      let dataMoi = [];
      if (this.diemDanhs.length > 0) {
        dataMoi = this.diemDanhs.filter((item) => {
          return item.ma_hs == ma_hs;
        });
      }

      return dataMoi;
    },
    setHienThiNgayHomNay() {
      this.hienThiNgayHomNay = true;
      let date = moment(new Date());
      this.dateOfMonth = date.format("DD");
      this.month = date.format("MM");
      this.year = date.format("YYYY");
      this.getDataDiemDanh();
    },
    setNotHienThiNgayHomNay() {
      this.hienThiNgayHomNay = false;
      this.setDateOfMonth();
      this.getDataDiemDanh();
    },
  },
  async created() {
    this.filter.month = new Date();
    this.setDateOfMonth();
    Promise.all([
      this.getGrades(),
      // this.getHinhThucDiemDanhs(),
      this.getAllSchoolYears(),
    ]).then(async () => {
      await this.getClasses();
      this.filter.class = this.lops.length ? this.lops[0].id : "";
    });
  },
  watch: {
    "filter.month": function () {
      if (this.hienThiNgayHomNay) {
        this.setNotHienThiNgayHomNay();
      } else {
        this.setDateOfMonth();
        this.getDataDiemDanh();
      }
    },
    "filter.school_year": function () {
      if (this.filter.school_year !== "" && this.filter.grade !== "") {
        this.filter.class = "";
        this.getClasses();
      }
    },
    "filter.grade": function () {
      if (this.filter.school_year !== "" && this.filter.grade !== "") {
        this.filter.class = "";
        this.getClasses();
      }
    },
    "filter.class": async function () {
      if (this.filter.class) {
        let id_coso = this.lops.find(
          (item) => item.id === this.filter.class
        ).id_coso;
        await this.getHinhThucDiemDanhs(id_coso);
        this.setClassName();
        this.getStudent();
        this.getDataDiemDanh();
      }
    },
  },
};
</script>

<style lang="scss" scoped>
::v-deep {
  .el-input__inner,
  .el-checkbox__inner {
    border: 1px solid #bdc3d4;
  }
  .el-button:focus {
    outline: none;
  }

  .el-select {
    margin-right: 5px;
    &.chon-phong-ban {
      flex-basis: 40%;
    }
    &.thang {
      flex-basis: 35%;
      min-width: 65px;
    }
  }
}
</style>
<style lang="scss" scoped>
div[data-v-12b157c7] {
  display: none;
}
ul {
  padding: 0;
}
.wrap-content-right {
  padding: 15px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 6px #ccc;
  &.create-phong-ban {
    margin-bottom: 10px;
  }
  .content-right {
    border: 1px solid #947474;
    padding: 10px 20px;
    border-radius: 5px;
    height: fit-content;
    .label-thong-tin-nhan-vien {
      font-size: 17px;
      font-family: serif;
      font-weight: bold;
    }
    .show-error {
      margin-bottom: 10px;
    }

    .wrap-form {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      overflow: auto;
      .form-group {
        flex-basis: 30%;
        &.checkbox {
          display: flex;
          justify-content: flex-start;
          align-items: center;
          padding-top: 23px;
        }
        &.button-luu {
          flex-basis: 100%;
          text-align: right;
        }
        &.dia-chi {
          flex-basis: 60%;
        }
      }

      table {
        width: 100%;
        td,
        th {
          border: 1px solid #929191;
          padding: 5px 10px;
          .ma-nv {
            width: 30px;
          }
          .ho-ten {
            width: 50px;
          }
        }

        th {
          background-color: #f6f6f6;
        }

        td {
          .closeselect {
            text-align: right;
            cursor: pointer;
            padding-right: 5px;
          }
          &.tong {
            text-align: center;
            font-weight: bold;
            color: blue;
          }
        }
      }
    }
  }
  .wrap-footer-content {
    margin-top: 10px;
    .hinh-thuc-cham-cong {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      .item-hinh-thuc-cham-cong {
        flex-basis: 30%;
        margin: 5px 0;
      }
    }
    .btn-process {
      display: flex;
      justify-content: flex-end;
      align-items: center;
    }
  }
}

.card {
  margin-bottom: 20px;
  margin-top: 10px;
  .card-body {
    .strong-name {
      font-weight: bold;
      color: #2626e6;
    }
  }

  .form-group-input {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px 20%;
  }

  .form-group-button {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 10px 25%;
  }

  .luu-y {
    color: #7d42dc;
    font-style: italic;
    text-align: center;
  }

  .wrap-filter {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    .form-group-input {
      padding: 0;
      // flex: 1;
      .label-input {
        text-align: right;
        padding-right: 15px;
      }
    }
  }
}
</style>