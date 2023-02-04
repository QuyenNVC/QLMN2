<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý kỳ tuyển sinh</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <el-button
                type="primary"
                size="small"
                @click="handleShowFormCreate()"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                >Thêm mới</el-button
              >
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <!-- <div
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" width="80%" style="max-width: 80%">
        <div class="modal-content">
          <div class="modal-body" style="padding: 0; padding-top: 15px">
            <from-wrapper
              :isErrorForm="isErrorForm"
              :titleMessage="titleMessage"
            >
              <template v-slot:form-title>{{ formTitle }}</template>
              <template>
                <el-input
                  placeholder="Mã kỳ tuyển sinh"
                  v-model="form.id"
                  type="hidden"
                ></el-input>
                <div class="col-sm-6">
                  <div class="form-group-input">
                    <span class="label-input">Kỳ tuyển sinh: </span>
                    <el-input
                      placeholder="Kỳ tuyển sinh"
                      v-model="form.name"
                      type="text"
                    ></el-input>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group-input">
                    <span class="label-input">Năm học: </span>
                    <el-select v-model="form.school_year">
                      <el-option
                        v-for="school_year in school_years"
                        :key="school_year.id"
                        :label="school_year.period"
                        :value="school_year.id"
                      ></el-option>
                    </el-select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group-input">
                    <span class="label-input">Ngày bắt đầu: </span>
                    <el-date-picker
                      v-model="form.date_start"
                      type="date"
                      placeholder="Ngày áp dụng"
                      format="dd/MM/yyyy"
                      :picker-options="pickerBeginDateBefore"
                    ></el-date-picker>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group-input">
                    <span class="label-input">Số lượng: </span>
                    <el-input-number
                      placeholder="Số lượng"
                      :min="0"
                      v-model="form.quantity"
                      type="text"
                    ></el-input-number>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group-input">
                    <span class="label-input">Ngày kết thúc: </span>
                    <el-date-picker
                      v-model="form.date_end"
                      type="date"
                      placeholder="Ngày áp dụng"
                      format="dd/MM/yyyy"
                      :picker-options="pickerBeginDateBefore"
                    ></el-date-picker>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group-input">
                    <span class="label-input">Tình trạng: </span>
                    <el-select v-model="form.status">
                      <el-option :value="1" label="Đang mở"></el-option>
                      <el-option :value="0" label="Kết thúc"></el-option>
                    </el-select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group-input">
                    <span class="label-input">Khối: </span>
                    <multiselect
                      v-model="form.grades"
                      tag-placeholder="Add this as new tag"
                      placeholder="Khối tuyển sinh"
                      label="name"
                      track-by="id"
                      :options="grades"
                      :multiple="true"
                      :taggable="true"
                      @tag="addTag"
                    ></multiselect>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group-input">
                    <span class="label-input">Ghi chú: </span>
                    <el-input
                      type="textarea"
                      placeholder="Ghi chú"
                      v-model="form.note"
                    ></el-input>
                  </div>
                </div>
              </template>
            </from-wrapper>
            <div class="form-group-button">
              <el-button
                id="btn_cancel"
                @click="isShowCreate = false"
                data-bs-dismiss="modal"
                ref="btn_cancel"
                v-show="false"
                >Hủy</el-button
              >
              <el-button type="primary" @click="save()">Lưu</el-button>
              <el-button type="primary" @click="saveAndNew()"
                >Lưu và thêm mới</el-button
              >
            </div>
          </div>
        </div>
      </div>
    </div> -->

    <main-modal widthModal="80%">
      <template>
        <from-wrapper :isErrorForm="isErrorForm" :titleMessage="titleMessage">
          <template v-slot:form-title>{{ formTitle }}</template>
          <template>
            <el-input
              placeholder="Mã kỳ tuyển sinh"
              v-model="form.id"
              type="hidden"
            ></el-input>
            <div class="col-sm-6">
              <div class="form-group-input">
                <span class="label-input">Kỳ tuyển sinh: </span>
                <el-input
                  placeholder="Kỳ tuyển sinh"
                  v-model="form.name"
                  type="text"
                ></el-input>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group-input">
                <span class="label-input">Năm học: </span>
                <el-select v-model="form.school_year">
                  <el-option
                    v-for="school_year in school_years"
                    :key="school_year.id"
                    :label="school_year.period"
                    :value="school_year.id"
                  ></el-option>
                </el-select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group-input">
                <span class="label-input">Ngày bắt đầu: </span>
                <el-date-picker
                  v-model="form.date_start"
                  type="date"
                  placeholder="Ngày áp dụng"
                  format="dd/MM/yyyy"
                  :picker-options="pickerBeginDateBefore"
                ></el-date-picker>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group-input">
                <span class="label-input">Số lượng: </span>
                <el-input-number
                  placeholder="Số lượng"
                  :min="0"
                  v-model="form.quantity"
                  type="text"
                ></el-input-number>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group-input">
                <span class="label-input">Ngày kết thúc: </span>
                <el-date-picker
                  v-model="form.date_end"
                  type="date"
                  placeholder="Ngày áp dụng"
                  format="dd/MM/yyyy"
                  :picker-options="pickerBeginDateBefore"
                ></el-date-picker>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group-input">
                <span class="label-input">Tình trạng: </span>
                <el-select v-model="form.status">
                  <el-option :value="1" label="Đang mở"></el-option>
                  <el-option :value="0" label="Kết thúc"></el-option>
                </el-select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group-input">
                <span class="label-input">Khối: </span>
                <multiselect
                  v-model="form.grades"
                  tag-placeholder="Add this as new tag"
                  placeholder="Khối tuyển sinh"
                  label="name"
                  track-by="id"
                  :options="grades"
                  :multiple="true"
                  :taggable="true"
                  @tag="addTag"
                ></multiselect>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group-input">
                <span class="label-input">Ghi chú: </span>
                <el-input
                  type="textarea"
                  placeholder="Ghi chú"
                  v-model="form.note"
                ></el-input>
              </div>
            </div>
          </template>
        </from-wrapper>
        <div class="form-group-button">
          <el-button
            id="btn_cancel"
            @click="isShowCreate = false"
            data-bs-dismiss="modal"
            ref="btn_cancel"
            v-show="false"
            >Hủy</el-button
          >
          <el-button type="primary" @click="save()">Lưu</el-button>
          <el-button type="primary" @click="saveAndNew()"
            >Lưu và thêm mới</el-button
          >
        </div>
      </template>
    </main-modal>

    <div class="container-fluid">
      <div class="row">
        <div class="wrap-table">
          <el-table
            :data="dataFilter"
            empty-text="Không có dữ liệu"
            style="width: 100%"
          >
            <el-table-column label="STT" prop="index" width="80" align="center">
            </el-table-column>
            <el-table-column label="Kỳ tuyển sinh" prop="name">
            </el-table-column>
            <el-table-column label="Ngày bắt đầu" prop="dateStartAfterFormat">
            </el-table-column>
            <el-table-column label="Ngày kết thúc" prop="dateEndAfterFormat">
            </el-table-column>
            <el-table-column label="Số lượng" prop="quantity" width="90">
            </el-table-column>
            <el-table-column align="center" width="250">
              <template slot="header" slot-scope="scope">
                <!-- <el-input v-model="search" size="mini" placeholder="Tìm kiếm" /> -->
                Cập nhật
              </template>
              <template slot-scope="scope">
                <el-button
                  size="mini"
                  type="primary"
                  @click="getRecords(scope.row)"
                  >Xem hồ sơ</el-button
                >
                <el-button
                  size="mini"
                  @click="handleEdit(scope.$index, scope.row)"
                  data-bs-toggle="modal"
                  data-bs-target="#exampleModal"
                  >Sửa</el-button
                >

                <el-button
                  slot="reference"
                  size="mini"
                  type="danger"
                  @click="handleDelete(scope.$index, scope.row)"
                  >Xóa</el-button
                >
              </template>
            </el-table-column>
          </el-table>
        </div>

        <el-pagination
          layout="prev, pager, next"
          :total="
            isSetPage && search == ''
              ? this.tableData.length
              : this.dataFilter.length
          "
          @current-change="setPage"
        >
        </el-pagination>
      </div>
    </div>

    <!-- footer -->
    <!-- ============================================================== -->
    <footer class="footer text-center">
      All Rights Reserved by SmartID. Designed and Developed by
      <a href="https://www.smartidads.com/">SmartID</a>.
    </footer>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
  </div>
</template>

<script>
import axios from "axios";
import mixin from "./mixin.js";
import Multiselect from "vue-multiselect";
import { errorMessage } from "../errors/error-code";
import FromWrapper from "../layouts/form-wrapper.vue";
import MainModal from "../layouts/main-modal.vue";
export default {
  name: "enrollment",
  mixins: [mixin],
  components: {
    multiselect: Multiselect,
    FromWrapper,
    MainModal,
  },
  data: function () {
    return {
      form: {
        id: "",
        name: "",
        school_year: "",
        quantity: "",
        date_start: "",
        date_end: "",
        grades: "",
        status: "",
        note: "",
      },
      school_years: [],
      grades: [],
      isShowCreate: false,
      isShowCreateTitle: false,
      isErrorForm: false,
      titleMessage: "",
      nameUpdate: "",
      yearUpdate: "",
      monthlyFees: [],
      tableData: [],
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      search: "",
      pickerBeginDateBefore: {
        disabledDate(time) {
          return time.getTime() + 86400000 < Date.now();
        },
      },
      formTitle: "Thêm kỳ tuyển sinh",
    };
  },
  methods: {
    // METHOD OF MULTISELECT
    addTag(newTag) {
      const tag = {
        name: newTag,
        id: newTag.substring(0, 2) + Math.floor(Math.random() * 10000000),
      };
      this.options.push(tag);
      this.form.grades.push(tag);
    },
    // END METHOD OF MULTISELECT
    handleEdit(index, row) {
      this.nameUpdate = row.name;
      this.form.id = row.id;
      this.form.name = row.name;
      this.form.school_year = row.school_year ? Number(row.school_year) : "";
      this.form.quantity = row.quantity;
      this.form.date_start = row.date_start;
      this.form.date_end = row.date_end;
      this.form.status = row.status ? Number(row.status) : "";
      this.form.note = row.note;
      var grades_array = [];
      if (row.grades != "") {
        var grades = row.grades.split(",");
        for (var grade_id of grades) {
          grades_array.push({
            id: grade_id,
            name: this.grades.find((grade) => grade.id == grade_id).name,
          });
        }
      }
      this.form.grades = grades_array;
      this.isShowCreate = true;
      this.isShowCreateTitle = false;
      this.isErrorForm = false;
      this.formTitle = "Cập nhật kỳ tuyển sinh " + this.nameUpdate;
    },
    getRecords(row) {
      var a = document.createElement("a");
      var event = new MouseEvent("click");
      a.href = `/ho-so-tuyen-sinh/${row.id}`;
      a.target = "_blank";
      a.dispatchEvent(event);
    },
    async fetchData() {
      await axios
        .get("getEntrollments")
        .then((response) => {
          this.tableData = response.data.enrollments;
          for (let i = 0; i < this.tableData.length; i++) {
            this.tableData[i]["index"] = i + 1;
            this.tableData[i]["dateStartAfterFormat"] = this.formatDateInTable(
              this.tableData[i]["date_start"]
            );
            this.tableData[i]["dateEndAfterFormat"] = this.formatDateInTable(
              this.tableData[i]["date_end"]
            );
            // this.tableData[i].sumFee = 0;
            // if(this.tableData[i].fees) {
            //     for(let j=0; j<this.monthlyFees.length;j++) {
            //         if(this.tableData[i].fees[j]) {
            //             this.tableData[i].sumFee = this.tableData[i].sumFee + Number(this.tableData[i].fees[j].cost);
            //         }
            //         this.tableData[i][this.monthlyFees[j].name] = this.tableData[i].fees[j] ? this.formatMoney(this.tableData[i].fees[j].cost) : "";
            //     }
            // }
            // this.tableData[i].sumFee = this.formatMoney(this.tableData[i].sumFee);
            // var curDate = new Date();
            // var effDate = new Date(this.tableData[i].effective_date);
            // this.tableData[i].disableBtn = curDate < effDate;
          }
          this.pagedTableData(1);
        })
        .catch();
    },
    formatDateInTable(date) {
      if (typeof date != "undefined") {
        var arr = String(date).split("-");
        arr = arr.reverse();
        return arr.join("/");
      }
      return;
    },
    formatMoney(num) {
      return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    },
    async getSchoolYears() {
      await axios
        .get("getSchoolYears")
        .then((promise) => {
          if (promise.data.status) {
            var currentDate = new Date();
            var month = currentDate.getMonth() + 1;
            var year =
              month > 6
                ? currentDate.getFullYear()
                : currentDate.getFullYear() - 1;
            this.school_years = promise.data.school_years.filter(function (e) {
              return e.name >= year;
            });
          }
          // this.school_years = promise.data.school_years;
        })
        .catch((rejected) => {});
    },
    async getGrades() {
      await axios
        .get("dataGrades")
        .then((promise) => {
          if (promise.data.status) {
            this.grades = [];
            for (let item of promise.data.grades) {
              var value = {
                id: item.id,
                name: item.name,
              };
              this.grades.push(value);
            }
          }
        })
        .catch((rejected) => {});
    },
    handleShowFormCreate() {
      this.isShowCreate = true;
      this.isShowCreateTitle = true;
      this.isErrorForm = false;
      this.resetForm();
    },
    resetForm() {
      this.form.id = "";
      this.form.name = "";
      this.form.quantity = "";
      this.form.school_year = "";
      this.form.date_start = "";
      this.form.date_end = "";
      this.form.grades = [];
      this.form.status = "";
      this.form.note = "";
    },
    formatBeforeSend() {
      this.form.date_start = this.formatDate(this.form.date_start);
      this.form.date_end = this.formatDate(this.form.date_end);
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
    handleCreate() {
      if (this.validateForm()) {
        if (this.isShowCreateTitle) {
          if (!this.checkExist()) {
            this.formatBeforeSend();
            return axios
              .post("createEnrollment", this.form)
              .then((response) => {
                if (response.data.status) {
                  this.fetchData();
                  this.$notify({
                    title: "Thông báo",
                    message: `Tạo thành công định mức học phí tháng cho khối lớp ${this.form.name} năm học ${schoolYear}!`,
                    type: "success",
                  });
                  // this.isErrorForm = false;
                  // this.titleMessage = "";
                  // this.isShowCreate = false;
                  return true;
                  // this.resetForm();
                }
              })
              .catch((e) => {
                this.$notify({
                  title: "Thông báo",
                  message: errorMessage[e.response.status],
                  type: "warning",
                });
              });
          } else {
            // this.isErrorForm = true;
            // this.titleMessage = "Kỳ tuyển sinh này đã tồn tại";
            this.$notify({
              title: "Thông báo",
              message: "Kỳ tuyển sinh này đã tồn tại",
              type: "warning",
            });
          }
        } else {
          if (!this.checkExist()) {
            this.formatBeforeSend();
            console.log(this.form);
            return axios
              .post("updateEnrollment", this.form)
              .then((response) => {
                if (response.data.status) {
                  this.fetchData();
                  this.$notify({
                    title: "Thông báo",
                    message: `Cập nhật thành công kỳ tuyển sinh ${this.nameUpdate}!`,
                    type: "success",
                  });
                  // this.isErrorForm = false;
                  // this.titleMessage = "";
                  // this.isShowCreate = false;
                  // this.resetForm();
                  return true;
                }
              })
              .catch((e) => {
                this.$notify({
                  title: "Thông báo",
                  message: errorMessage[e.response.status],
                  type: "warning",
                });
              });
          } else {
            this.isErrorForm = true;
            this.titleMessage = "Kỳ tuyển sinh này đã tồn tại";
          }
        }
      }
      return false;
    },
    async save() {
      let result = await this.handleCreate();
      // console.log(this.$refs.btn_cancel);
      if (result) {
        this.$refs.btn_cancel.$el.click();
      }

      // el.dispatchEvent(new Event("click"));
    },
    async saveAndNew() {
      let result = await this.handleCreate();
      // console.log(this.$refs.btn_cancel);
      if (result) {
        this.resetForm();
      }
    },
    handleDelete(index, row) {
      let name = row.name;
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      }).then(async () => {
        await axios
          .post("deleteEnrollment/" + row.id)
          .then((response) => {
            if (response.data.status == true) {
              this.$notify({
                title: "Thông báo",
                message: `Xóa kỳ tuyển sinh '${name}' thành công!`,
                type: "success",
              });
              this.fetchData();
            } else {
              this.$notify({
                title: "Thông báo",
                message: `Đã xảy ra lỗi trong quá trình thực thi, hãy thử lại sau!`,
                type: "danger",
              });
            }
          })
          .catch((e) => {
            this.$notify({
              title: "Thông báo",
              message: errorMessage[e.response.status],
              type: "warning",
            });
          });
      });
    },
    pagedTableData(feature = 1) {
      if (feature != 1) {
        this.dataFilter = this.tableData.filter(
          (data) =>
            !this.search ||
            data.name.toLowerCase().includes(this.search.toLowerCase())
        );
      } else {
        this.dataFilter = this.tableData.slice(
          this.pageSize * this.page - this.pageSize,
          this.pageSize * this.page
        );
      }
    },
    setPage(val) {
      this.page = val;
      this.isSetPage = true;
    },
    checkExist() {
      var checkName = null;
      checkName = this.tableData.find(
        (item) =>
          item.school_year == this.form.school_year && item.id != this.form.id
      );
      return checkName ? true : false;
    },
    validateForm() {
      this.titleMessage = "";
      if (this.form.name == "") {
        // this.isErrorForm = true;
        // this.titleMessage = "Tên kỳ tuyển sinh không được bỏ trống";
        this.$notify({
          title: "Cảnh báo",
          message: "Tên kỳ tuyển sinh không được bỏ trống",
          type: "warning",
        });
        return false;
      }

      if (this.form.school_year == "") {
        // this.isErrorForm = true;
        // this.titleMessage = "Năm học không được bỏ trống";
        this.$notify({
          title: "Cảnh báo",
          message: "Năm học không được bỏ trống",
          type: "warning",
        });
        return false;
      }

      if (this.form.quantity == "") {
        // this.isErrorForm = true;
        // this.titleMessage = "Số lượng tuyển sinh không được bỏ trống";
        this.$notify({
          title: "Cảnh báo",
          message: "Số lượng tuyển sinh không được bỏ trống",
          type: "warning",
        });
        return false;
      }

      if (this.form.grades == []) {
        // this.isErrorForm = true;
        // this.titleMessage = "Khối lớp tuyển sinh không được bỏ trống";
        this.$notify({
          title: "Cảnh báo",
          message: "Khối lớp tuyển sinh không được bỏ trống",
          type: "warning",
        });
        return false;
      }

      // if (this.form.effective_date === null || this.form.effective_date == '') {
      //     this.isErrorForm = true;
      //     this.titleMessage = "Ngày có hiệu lực không được để trống";
      //     return false;
      // }

      return true;
    },
  },
  created() {
    this.getSchoolYears();
    this.getGrades();
  },
  beforeMount() {
    this.fetchData();
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
</style>
<style lang="scss" scoped>
::v-deep {
  .wrap-table {
    padding: 15px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 6px #ccc;
    .el-table {
      box-shadow: 0 0 2px #ccc;
      border-radius: 5px;
      .el-input__inner {
        width: 155px;
      }
      .el-table_1_column_3 {
        // display: flex;
        .cell {
          width: 100%;
        }
      }
      .el-table_1_column_2 {
        .cell {
          text-align: left;
        }
      }
      .el-button {
        margin-bottom: 10px;
      }
    }
  }
  .el-table td,
  .el-table th.is-leaf {
    border: 0.5px solid #ebeef5;
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
    .label-input {
      flex-basis: 150px;
    }

    .el-date-editor.el-input,
    .el-date-editor.el-input__inner {
      width: 100%;
    }

    .el-input,
    .el-input__inner,
    .el-select,
    .el-input-number {
      width: 100%;
    }
  }

  .luu-y {
    color: #7d42dc;
    font-style: italic;
    text-align: center;
  }
}
.form-group-button {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding: 10px 50px;
}
</style>
<style lang="scss">
.fieldset_form {
  position: relative;
  .card-title {
    display: inline-block;
    position: absolute;
    top: 27px;
    left: 50px;
    background: #fff;
    padding: 5px;
  }
  .container {
    padding: 0 40px;
    & > .row {
      border: 1px solid grey;
      padding-top: 30px;
    }
  }
}
</style>