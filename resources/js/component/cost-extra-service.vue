<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý định mức dịch vụ cộng thêm</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <el-button
                type="primary"
                size="small"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                @click="resetForm()"
                >Thêm mới</el-button
              >
            </nav>
          </div>
        </div>
      </div>
    </div>
    <main-modal widthModal="60%">
      <template>
        <form-wrapper>
          <template v-slot:form-title>{{ title }}</template>
          <template>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group-input">
                  <span class="label-input form-label required">Năm học: </span>
                  <el-select placeholder="Năm học" v-model="form.school_year">
                    <el-option
                      v-for="school_year in school_years"
                      :key="school_year.id"
                      :label="school_year.period"
                      :value="school_year.id"
                    ></el-option>
                  </el-select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group-input">
                  <span class="label-input form-label required"
                    >Khối lớp:
                  </span>
                  <el-select placeholder="Khối lớp" v-model="form.grade_id">
                    <el-option
                      v-for="item in grades"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"
                    ></el-option>
                  </el-select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group-input">
                  <span class="label-input form-label required"
                    >Ngày áp dụng:
                  </span>
                  <el-date-picker
                    v-model="form.effective_date"
                    type="date"
                    placeholder="Ngày áp dụng"
                    format="dd/MM/yyyy"
                    :picker-options="pickerBeginDateBefore"
                  ></el-date-picker>
                </div>
              </div>
              <div class="col-md-6" v-for="item in fee_types" :key="item.id">
                <div class="form-group-input">
                  <span class="label-input form-label">{{ item.name }}: </span>
                  <input
                    type="text"
                    v-model="form[item.name]"
                    v-money="money"
                    class="el-input__inner el-input"
                    :placeholder="item.name"
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group-input">
                  <span
                    class="label-input form-label"
                    style="
                      margin-right: 54px;
                      white-space: nowrap;
                      flex-basis: unset !important;
                    "
                    >Ghi chú:
                  </span>
                  <el-input
                    placeholder="Ghi chú"
                    v-model="form.note"
                    type="textarea"
                  ></el-input>
                </div>
              </div>
            </div>
          </template>
        </form-wrapper>
        <div class="form-group-button">
          <el-button
            id="btn_cancel"
            data-bs-dismiss="modal"
            ref="btn_cancel"
            v-show="false"
            >Hủy</el-button
          >
          <el-button size="small" type="primary" @click="save()">Lưu</el-button>
          <el-button size="small" type="primary" @click="saveAndNew()"
            >Lưu và thêm mới</el-button
          >
        </div>
      </template>
    </main-modal>

    <div class="container-fluid">
      <div class="row">
        <div class="col-12" v-if="!id_coso">
          <div class="card filter_form">
            <div class="wrap-filter">
              <div class="col">
                <div class="form-group-input col-lg-3 col-sm-6 col-12">
                  <span class="text-nowrap">Cơ sở: </span
                  >&nbsp;&nbsp;&nbsp;&nbsp;
                  <el-select v-model="filter.id_coso" placeholder="Cơ sở">
                    <el-option
                      v-for="item in cosos"
                      :key="item.id"
                      :label="item.school_name"
                      :value="item.id"
                    >
                    </el-option>
                  </el-select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="wrap-table">
          <el-table
            :data="dataFilter"
            empty-text="Không có dữ liệu"
            style="width: 100%"
          >
            <el-table-column label="STT" prop="index" align="center" width="50">
            </el-table-column>
            <el-table-column
              label="Năm học"
              prop="school_year_name"
              align="center"
              width="100"
            >
            </el-table-column>
            <el-table-column
              label="Khối lớp"
              prop="grade_name"
              header-align="center"
              width="200"
            >
            </el-table-column>
            <el-table-column
              label="Ngày áp dụng"
              prop="effective_date_render"
              align="center"
              width="120"
            >
            </el-table-column>
            <el-table-column
              v-for="item in fee_types"
              :key="item.id"
              :label="item.name"
              :prop="item.name"
              header-align="center"
              align="right"
              width="150"
            ></el-table-column>
            <el-table-column
              label="Tổng cộng"
              prop="sumFee"
              header-align="center"
              align="right"
              width="120"
            >
            </el-table-column>
            <el-table-column
              label="Mô tả/Ghi chú"
              prop="note"
              header-align="center"
              align="left"
              min-width="180"
            >
            </el-table-column>
            <el-table-column align="right" width="150" fixed="right">
              <template slot="header" slot-scope="scope">
                <el-input v-model="search" size="mini" placeholder="Tìm kiếm" />
              </template>
              <template slot-scope="scope">
                <el-button
                  size="mini"
                  @click="handleEdit(scope.$index, scope.row)"
                  type="warning"
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
    <footer-qlmn></footer-qlmn>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
  </div>
</template>

<script>
import axios from "axios";
import mixin from "./mixin.js";
import { errorMessage } from "../errors/error-code";
import { formatDatedmY } from "../functions/formatFunctions.js";
import mainModal from "../layouts/main-modal.vue";
import FormWrapper from "../layouts/form-wrapper.vue";
import FooterQlmn from "../layouts/footer-qlmn.vue";
import { pickerBeginDateBefore } from "../validators/validators";
export default {
  name: "cost-extra-service",
  components: {
    FooterQlmn,
    mainModal,
    FormWrapper,
  },
  mixins: [mixin],
  props: ["id_coso"],
  data: function () {
    return {
      form: {
        id: "",
        school_year: "",
        effective_date: "",
        grade_id: "",
        note: "",
        fees: [],
      },
      school_years: [],
      grades: [],
      isShowCreateTitle: true,
      fee_types: [],
      tableData: [],
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      search: "",
      pickerBeginDateBefore,
      title: "Thêm định mức dịch vụ cộng thêm",
      gradeUpdate: "",
      yearUpdate: "",
      filter: {
        id_coso: "",
      },
      cosos: [],
    };
  },
  methods: {
    handleEdit(index, row) {
      this.gradeUpdate = row.grade_name;
      this.yearUpdate = row.school_year_name;
      this.form.id = row.id;
      this.form.school_year = Number(row.school_year);
      this.form.effective_date = row.effective_date;
      this.form.grade_id = Number(row.grade);
      this.form.note = row.note;
      for (let j = 0; j < this.fee_types.length; j++) {
        this.form[this.fee_types[j].name] = row[this.fee_types[j].name]
          ? row[this.fee_types[j].name].replace(/,/g, "")
          : "";
      }
      this.isShowCreateTitle = false;
      this.title = `Cập nhật định mức dịch vụ cộng thêm của khối lớp ${row.grade_name} năm học ${row.school_year_name}`;
    },
    fetchData() {
      axios
        .get("/getCostExtraService/" + this.filter.id_coso)
        .then((response) => {
          this.tableData = response.data.costExtraServices;
          for (let i = 0; i < this.tableData.length; i++) {
            this.tableData[i]["index"] = i + 1;
            this.tableData[i].sumFee = 0;
            if (this.tableData[i].fees) {
              for (let j = 0; j < this.fee_types.length; j++) {
                if (this.tableData[i].fees[j]) {
                  this.tableData[i].sumFee =
                    this.tableData[i].sumFee +
                    Number(this.tableData[i].fees[j].cost);
                }
                this.tableData[i][this.fee_types[j].name] = this.tableData[i]
                  .fees[j]
                  ? this.formatMoney(this.tableData[i].fees[j].cost)
                  : "";
                this.tableData[i].effective_date_render = this.tableData[i]
                  .effective_date
                  ? formatDatedmY(this.tableData[i].effective_date)
                  : "";
              }
            }
            this.tableData[i].sumFee = this.formatMoney(
              this.tableData[i].sumFee
            );
            var curDate = new Date();
            var effDate = new Date(this.tableData[i].effective_date);
            this.tableData[i].disableBtn = curDate < effDate;
          }
          this.pagedTableData(1);
        })
        .catch();
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
        })
        .catch((rejected) => {});
    },
    async getGrades() {
      await axios
        .get("dataGrades")
        .then((promise) => {
          if (promise.data.status) {
            this.grades = promise.data.grades;
          }
        })
        .catch((rejected) => {});
    },
    async getFeeTypes() {
      await axios
        .get("dataExtraServices", {
          params: {
            id_coso: this.filter.id_coso,
          },
        })
        .then((response) => {
          if (response.data.status == true) {
            this.fee_types = response.data.extraservices;
            for (let i = 0; i < this.fee_types.length; i++) {
              var fee = this.fee_types[i].name;
              this.form[fee] = "";
            }
          }
        });
    },
    resetForm() {
      this.form.id = "";
      this.form.school_year = "";
      this.form.grade_id = "";
      this.form.note = "";
      this.form.effective_date = "";
      for (let i = 0; i < this.fee_types.length; i++) {
        this.form[this.fee_types[i].name] = "";
      }
      this.form.fees = [];
      this.isShowCreateTitle = true;
      this.title = "Thêm định mức dịch vụ cộng thêm";
      this.yearUpdate = "";
      this.gradeUpdate = "";
    },
    formatBeforeSend() {
      this.form.fees = [];
      for (var i = 0; i < this.fee_types.length; i++) {
        var item = this.fee_types[i];
        this.form.effective_date = this.form.effective_date
          ? this.formatDate(this.form.effective_date)
          : "";
        this.form.fees.push({
          id: item.id,
          value: this.form[item.name].replace(/,/g, ""),
        });
      }
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
              .post("createCostExtraService", this.form)
              .then((response) => {
                if (response.data.status) {
                  this.fetchData();
                  var grade = this.grades.find(
                    (grade) => grade.id == this.form.grade_id
                  ).name;
                  var schoolYear = this.school_years.find(
                    (school_year) => school_year.id == this.form.school_year
                  ).period;
                  this.$notify({
                    title: "Thông báo",
                    message: `Tạo thành công định mức dịch vụ cộng thêm cho khối lớp ${grade} năm học ${schoolYear}!`,
                    type: "success",
                  });
                  return true;
                } else {
                  this.$notify({
                    title: "Cảnh báo",
                    message: response.data.message,
                    type: "warning",
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
          } else {
            this.$notify({
              title: "Thông báo",
              message: `Định mức dịch vụ này đã tồn tại`,
              type: "warning",
            });
          }
        } else {
          if (!this.checkExist()) {
            this.formatBeforeSend();
            return axios
              .post("updateCostExtraService", this.form)
              .then((response) => {
                if (response.data.status) {
                  this.fetchData();
                  this.$notify({
                    title: "Thông báo",
                    message: `Cập nhật thành công định mức dịch vụ cộng thêm cho khối lớp ${this.gradeUpdate}, năm học ${this.yearUpdate}!`,
                    type: "success",
                  });
                  return true;
                } else {
                  this.$notify({
                    title: "Cảnh báo",
                    message: response.data.message,
                    type: "warning",
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
          } else {
            this.$notify({
              title: "Thông báo",
              message: `Định mức dịch vụ này đã tồn tại`,
              type: "warning",
            });
          }
        }
      }
      return false;
    },
    async save() {
      let result = await this.handleCreate();
      if (result) {
        this.$refs.btn_cancel.$el.click();
      }
    },
    async saveAndNew() {
      let result = await this.handleCreate();
      if (result) {
        this.resetForm();
      }
    },
    handleDelete(index, row) {
      // let name = row.name;
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      }).then(async () => {
        await axios
          .post("deleteCostExtraService/" + row.id)
          .then((response) => {
            if (response.data.status == true) {
              this.$notify({
                title: "Thông báo",
                message: `Xóa định mức dịch vụ cộng thêm thành công!`,
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
          item.school_year == this.form.school_year &&
          item.grade == this.form.grade_id &&
          item.id != this.form.id
      );
      return checkName ? true : false;
    },
    validateForm() {
      if (this.form.school_year == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Năm học không được để trống",
          type: "warning",
        });
        return false;
      }

      if (this.form.grade_id == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Khối lớp không được để trống",
          type: "warning",
        });
        return false;
      }

      if (this.form.effective_date === null || this.form.effective_date == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Ngày có hiệu lực không được để trống",
          type: "warning",
        });
        return false;
      }

      return true;
    },
    async getCosos() {
      await axios
        .get("getCosos")
        .then((response) => {
          if (response.data.status) {
            this.cosos = response.data.items;
          }
        })
        .catch((e) => {
          this.$notify({
            title: "Thông báo",
            message: errorMessage[e.response.status],
            type: "warning",
          });
        });
    },
  },
  created() {
    Promise.all([
      this.getFeeTypes(),
      this.getSchoolYears(),
      this.getGrades(),
      this.getCosos(),
    ]).then(() => {
      if (this.id_coso) {
        this.filter.id_coso = this.id_coso;
      } else {
        this.filter.id_coso = this.cosos.length ? this.cosos[0].id : "";
      }
    });
  },
  watch: {
    "filter.id_coso": async function () {
      await this.getFeeTypes();
      this.fetchData();
    },
  },
};
</script>

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
    .label-input {
      flex-basis: 150px !important;
    }
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
}
</style>