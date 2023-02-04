<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4>Thống kê theo dõi sự phát triển</h4>
          <div class="ms-auto text-end">
            <nav class="breadcrumb">
              <el-button
                type="primary"
                size="small"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                @click="exportExcel()"
                >Xuất excel</el-button
              >
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid" v-loading="loading">
      <div class="record-wrapper">
        <div class="row">
          <div class="col-12">
            <div class="card filter_form">
              <div class="card-body">
                <h5 class="card-title mb-0">Lọc</h5>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="label-input month-options">Tháng: </span>
                    <el-select
                      class="month-select"
                      v-model="filter.month"
                      placeholder="Select"
                    >
                      <el-option
                        v-for="item in monthOptions"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                      >
                      </el-option>
                    </el-select>
                    <el-select v-model="filter.year" placeholder="Select">
                      <el-option
                        v-for="item in yearOptions"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                      >
                      </el-option>
                    </el-select>
                  </div>
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="label-input">Khối lớp: </span>
                    <el-select
                      v-model="filter.grades"
                      multiple
                      collapse-tags
                      placeholder="Select"
                    >
                      <el-option
                        v-for="item in gradeOptions"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                      >
                      </el-option>
                    </el-select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="label-input">Lớp học: </span>
                    <el-select
                      v-model="filter.class"
                      multiple
                      collapse-tags
                      placeholder="Select"
                    >
                      <el-option
                        v-for="item in classOptions"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                      >
                      </el-option>
                    </el-select>
                  </div>
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="label-input">Trạng thái: </span>
                    <el-select
                      v-model="filter.status"
                      collapse-tags
                      placeholder="Select"
                    >
                      <el-option
                        v-for="item in statusOptions"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                      >
                      </el-option>
                    </el-select>
                  </div>
                </div>
              </div>

              <div
                class="form-group-button action-button"
                style="justify-content: center"
              >
                <el-button type="" @click="resetFilter()">Làm mới</el-button>
                <el-button type="primary" @click="getData()">Lọc</el-button>
              </div>
            </div>
            <div class="wrap-table table-record">
              <el-table
                :data="tableData"
                empty-text="Không có dữ liệu"
                style="width: 100%"
              >
                <el-table-column width="40" align="center">
                  <template slot="header" slot-scope="scope">
                    <input
                      type="checkbox"
                      :checked="selected.includes(-1)"
                      @change="checkAll()"
                    />
                  </template>
                  <template slot-scope="scope">
                    <input
                      type="checkbox"
                      :checked="checkSelected(scope.row.student_id)"
                      @change="changeSelectedRecord(scope.row.student_id)"
                    />
                  </template>
                </el-table-column>
                <el-table-column
                  label="Mã HS"
                  prop="student_id"
                  align="center"
                  width="80"
                >
                </el-table-column>
                <el-table-column
                  label="Tên học sinh"
                  prop="name"
                  header-align="center"
                  align="left"
                  width="200"
                >
                </el-table-column>
                <el-table-column
                  label="Có đi học"
                  prop="go_to_school"
                  header-align="center"
                  align="left"
                >
                  <template slot-scope="scope">
                    <div
                      style="white-space: pre-line"
                      v-html="scope.row.go_to_school"
                    ></div>
                  </template>
                </el-table-column>
                <el-table-column
                  label="Không đi học"
                  prop="dont_go_to_school"
                  header-align="left"
                >
                </el-table-column>
                <el-table-column
                  label="Bảo lưu"
                  prop="reserve"
                  header-align="center"
                  align="center"
                  width="100"
                >
                </el-table-column>
              </el-table>
            </div>
          </div>
        </div>
      </div>
      <el-pagination
        layout="prev, pager, next"
        :total="total"
        :page-size="pageSize"
        :current-page.sync="page"
        :page-sizes="[10, 20, 30, 50]"
        @current-change="setPage"
      >
      </el-pagination>
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
import mixin from "../mixin.js";
import FooterQlmn from "../../layouts/footer-qlmn.vue";
import multiselect from "vue-multiselect";
import moment from "moment";

export default {
  name: "attendance",
  components: {
    multiselect,
    FooterQlmn,
  },
  mixins: [mixin],
  data() {
    return {
      tableData: [],
      exist: false,
      titleMessage: "",
      isErrorForm: false,
      page: 1,
      pageSize: 10,
      total: 0,
      edit: false,
      loading: false,
      // filter
      filter: {
        grades: [],
        class: [],
        month: null,
        year: null,
        status: "",
      },
      // grades
      gradeOptions: [],
      // class
      classOptions: [],
      // select year filter option
      monthOptions: [],
      yearOptions: [],
      // check row
      selected: [],
      exclude: [],
      //statusOptions
      statusOptions: [],
      // hardcode address
      classRooms: [
        {
          id: 1,
          name: "Tầng 1",
        },
        {
          id: 2,
          name: "Tầng 2",
        },
        {
          id: 3,
          name: "Tầng 3",
        },
        {
          id: 4,
          name: "Tầng 4",
        },
      ],
    };
  },
  methods: {
    async getData() {
      this.loading = true;
      await axios
        .get("get-data-attendance", {
          params: {
            page: this.page,
            page_size: this.pageSize,
            ...this.filter,
          },
        })
        .then((response) => {
          let resultData = response.data.result;
          this.tableData = resultData.data;
          this.total = resultData.total;
        })
        .catch((error) => {
          this.$notify.error({
            title: "Error",
            message: error.data.result.data,
          });
        });
      this.loading = false;
    },
    async getDataClassOptions() {
      await axios.get("/dataClasses").then((response) => {
        if (response.data.status == true) {
          let classes = [];
          response.data.lops.forEach((item) => {
            classes.push({
              value: item.id,
              label: item.name,
            });
          });
          this.classOptions = classes;
        }
      });
    },
    async getDataGradeOptions() {
      await axios.get("/dataGrades").then((response) => {
        if (response.data.status == true) {
          let grades = [];
          response.data.grades.forEach((item) => {
            grades.push({
              value: item.id,
              label: item.name,
            });
          });
          this.gradeOptions = grades;
        }
      });
    },
    async getDataYearOptions() {
      await axios
        .get("getYears")
        .then((response) => {
          if (response.data.status) {
            let years = [];
            response.data.years.forEach((item) => {
              years.push({
                value: item,
                label: item,
              });
            });
            this.yearOptions = years;
          }
        })
        .catch();
    },
    getMonthOptions() {
      let monthOptions = [];
      for (let index = 1; index <= 12; index++) {
        monthOptions.push({
          value: index,
          label: index,
        });
      }
      this.monthOptions = monthOptions;
    },
    getStatusOptions() {
      let statusOptions = [
        {
          value: 1,
          label: "Có đi học",
        },
        {
          value: 2,
          label: "Nghỉ học",
        },
        {
          value: 3,
          label: "Bảo lưu",
        },
      ];
      this.statusOptions = statusOptions;
    },
    setPage(value) {
      this.page = value;
    },
    resetFilter() {
      this.filter = {
        grades: [],
        class: [],
        month: null,
        year: null,
        status: "",
      };
    },
    checkSelected(id) {
      if (this.selected.includes(-1)) {
        if (this.exclude.includes(id)) {
          return false;
        } else {
          return true;
        }
      } else {
        if (this.selected.includes(id)) {
          return true;
        } else {
          return false;
        }
      }
    },
    changeSelectedRecord(id) {
      if (this.selected.includes(-1)) {
        if (this.exclude.includes(id)) {
          const index = this.exclude.indexOf(id);
          if (index > -1) {
            this.exclude.splice(index, 1); // 2nd parameter means remove one item only
          }
        } else {
          this.exclude.push(id);
        }
      } else {
        if (this.selected.includes(id)) {
          const index = this.selected.indexOf(id);
          if (index > -1) {
            this.selected.splice(index, 1); // 2nd parameter means remove one item only
          }
        } else {
          this.selected.push(id);
        }
      }
    },
    checkAll() {
      if (this.selected.includes(-1)) {
        this.exclude = [];
        this.selected = [];
      } else {
        this.selected = [-1];
        this.exclude = [];
      }
    },
    async exportExcel() {
      this.loading = true;
      await axios
        .post("report-attendance/export-excel", {
          monitor_physical__ids: this.selected,
          excludes_ids: this.exclude,
          ...this.filter,
        })
        .then((response) => {
          if (response.data.status == true) {
            this.loading = false;
            let result = response.data.result;
            this.downloadURI(result.url, result.filename);
          }
        });
    },
    downloadURI(uri, name) {
      var link = document.createElement("a");
      // If you don't know the name or want to use
      // the webserver default set name = ''
      link.setAttribute("download", name);
      link.href = uri;
      document.body.appendChild(link);
      link.click();
      link.remove();
    },
    renderAddress(id) {
      let address = "";
      this.classRooms.forEach((item) => {
        if (item.id == parseInt(id)) {
          address = item.name;
        }
      });
      return address;
    },
    renderMonthAge(date) {
      return Math.floor(moment().diff(moment(date), "months", true));
    },
  },
  mounted() {
    this.getData();
    this.getDataClassOptions();
    this.getDataGradeOptions();
    this.getMonthOptions();
    this.getDataYearOptions();
    this.getStatusOptions();
  },
  watch: {
    page: function () {
      this.getData();
    },
    selected: function () {
      this.$forceUpdate();
    },
  },
};
</script>

<style lang="scss" scoped>
.ml-2 {
  margin-left: 2% !important;
}

.float-right {
  float: right !important;
}

.text-right {
  text-align: right !important;
}

.d-inline-block {
  display: inline-block;
}

.m-auto {
  margin: auto;
}

.breadcrumb {
  flex-wrap: nowrap;
}

::v-deep {
  .el-select,
  .el-date-editor {
    width: 100% !important;
  }
  .el-input__inner,
  .el-checkbox__inner {
    border: 1px solid #bdc3d4;
  }
  .el-button:focus {
    outline: none;
  }
  .el-input-number {
    width: 100%;
  }
}
</style>
<style lang="scss" scoped>
::v-deep {
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
      padding: 0;
      padding-bottom: 15px;
      width: 100%;
      span {
        white-space: nowrap;
        margin-bottom: 0;
        margin-right: 5px;
      }
      .el-date-editor.el-input {
        width: 100%;
      }
      .el-input-number {
        width: 100%;
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
}
ul {
  padding: 0;
}

.wrap-table {
  box-shadow: 0 0 8px #0003;
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
      .cell {
        width: 100%;
      }
    }
    .el-button {
      margin-bottom: 10px;
    }
  }
}
.record-wrapper.wrap-content-right {
  // padding: 15px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 6px #ccc;
  margin-top: 15px;
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
      hr {
        width: 100%;
        margin: 0;
        margin-bottom: 15px;
      }
      h6 {
        width: 100%;
        margin-top: 15px;
      }
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
      .medical_history_group {
        textarea {
          height: unset;
          line-height: unset;
        }
      }
      .note {
        flex-basis: 100%;
        textarea {
          height: unset;
          line-height: unset;
        }
      }
    }
  }
}

.ho-so-ung-vien {
  margin-bottom: 10px;
  .card {
    margin-bottom: 0;
  }
  .avatar {
    width: 100%;
  }
  .name-under-avatar {
    margin-top: 15px;
    font-size: 18px;
    text-align: center;
    font-weight: bold;
  }
  .content-right {
    border: none !important;
    .label-wrap {
      padding: 10px 0;
      &.wrap-button-hide-ho-so {
        text-align: right;
      }
    }
    .label-name {
      font-weight: bold;
      margin-right: 12px;
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
    padding: 0;
    padding-bottom: 15px;
    width: 100%;
    span {
      white-space: nowrap;
      margin-bottom: 0;
      margin-right: 5px;
    }
    .el-date-editor.el-input {
      width: 100%;
    }
    .el-input-number {
      width: 100%;
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
.filter_form {
  .form-group-input {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px 10%;
    .label-input {
      flex-basis: 150px;
    }
  }
  .action-button {
    margin-top: 10px;
  }
}
.form-group-button {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding: 10px 0;
  margin-top: -20px;
}

td:nth-child(3),
th:nth-child(3) {
  min-width: 200px;
}

.hideUpload > div {
  display: none;
}

.hidden {
  display: none;
}
</style>

<style lang="scss">
.el-upload--picture {
  width: 100% !important;
  font-size: 60px;
}
.upload-image-student-form {
  .el-upload-list--picture {
    .el-upload-list__item {
      width: 100% !important;
      height: unset !important;
      aspect-ratio: 1;
      padding-left: 10px;
      margin-top: 0;
      border: none;
      img {
        height: 100%;
        width: 100%;
        margin-left: unset;
      }
      a {
        display: none;
      }
      .el-icon-close {
        z-index: 1;
      }
    }
  }
}

.table-record {
  .el-table td,
  .el-table th.is-leaf {
    border: 0.5px solid #ebeef5;
  }
  // .el-table__body-wrapper {
  //     .el-table__row:nth-child(2n + 1) {
  //         background: #f2f2f2;
  //     }
  // }
}

.month-options {
  flex-basis: 800px !important;
}

.month-select {
  margin-right: 5px;
}
</style>
