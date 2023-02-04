<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4>Thống kê lý lịch trích ngang nhân viên</h4>
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
                    <span class="label-input">Họ tên: </span>
                    <el-input
                      placeholder="Họ tên"
                      v-model="filter.name"
                    ></el-input>
                  </div>
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="label-input">Phòng ban: </span>
                    <el-select
                      v-model="filter.department"
                      multiple
                      collapse-tags
                      placeholder="Select"
                    >
                      <el-option
                        v-for="item in departmentOptions"
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
                    <span class="label-input">Trạng thái làm việc: </span>
                    <el-select
                      v-model="filter.working_status"
                      clearable
                      placeholder="Select"
                    >
                      <el-option
                        v-for="item in optionStatusWorkings"
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
                stripe
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
                      :checked="checkSelected(scope.row.ma_nv)"
                      @change="changeSelectedRecord(scope.row.ma_nv)"
                    />
                  </template>
                </el-table-column>
                <el-table-column
                  label="Mã NV"
                  prop="ma_nv"
                  align="center"
                  width="100"
                >
                </el-table-column>
                <el-table-column
                  label="Họ tên"
                  prop="fullname"
                  header-align="center"
                  align="left"
                  width="200"
                >
                </el-table-column>
                <el-table-column
                  label="Giới tính"
                  prop="gender"
                  header-align="center"
                  width="100"
                >
                </el-table-column>
                <el-table-column
                  label="Ngày sinh"
                  prop="birthday"
                  header-align="center"
                  align="center"
                  width="120"
                >
                  <template slot-scope="scope" v-if="scope.row.birthday">
                    {{ scope.row.birthday | formatDate }}
                  </template>
                </el-table-column>
                <el-table-column
                  label="Dân tộc"
                  prop="dan_toc"
                  header-align="center"
                  width="120"
                >
                </el-table-column>
                <el-table-column
                  label="CMND"
                  align="center"
                  width="120"
                  prop="cmnd"
                >
                </el-table-column>
                <el-table-column
                  label="Nơi cấp"
                  prop="noi_cap"
                  align="center"
                  width="150"
                >
                </el-table-column>
                <el-table-column
                  label="Ngày cấp"
                  prop="ngay_cap"
                  align="center"
                  width="120"
                >
                  <template slot-scope="scope" v-if="scope.row.ngay_cap">
                    {{ scope.row.ngay_cap | formatDate }}
                  </template>
                </el-table-column>
                <el-table-column
                  label="SDT"
                  prop="phone"
                  align="center"
                  width="120"
                >
                </el-table-column>
                <el-table-column
                  label="Email"
                  prop="email"
                  header-align="center"
                  width="180"
                ></el-table-column>
                <el-table-column
                  label="Lương ngày"
                  prop="luong_ngay"
                  header-align="center"
                  width="150"
                >
                  <template slot-scope="scope" v-if="scope.row.luong_ngay">
                    {{ scope.row.luong_ngay | formatMoney }}
                  </template>
                </el-table-column>
                <el-table-column
                  label="Địa chỉ"
                  prop="address"
                  header-align="center"
                  width="200"
                ></el-table-column>
                <el-table-column
                  label="Ngày vào làm"
                  prop="ngay_vao_lam"
                  header-align="center"
                  align="center"
                  width="120"
                >
                  <template slot-scope="scope" v-if="scope.row.ngay_vao_lam">
                    {{ scope.row.ngay_vao_lam | formatDate }}
                  </template>
                </el-table-column>
                <el-table-column
                  label="Ngày nghỉ làm"
                  prop="ngay_nghi_lam"
                  header-align="center"
                  align="center"
                  width="120"
                >
                  <template
                    slot-scope="scope"
                    v-if="scope.row.status_nghi_viec"
                  >
                    {{ scope.row.ngay_nghi_lam | formatDate }}
                  </template>
                </el-table-column>
                <el-table-column
                  label="Phòng ban"
                  prop="department.name"
                  header-align="center"
                  width="200"
                ></el-table-column>
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

export default {
  name: "employee-resume",
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
        name: "",
        department: [],
        working_status: "",
      },
      // select status working
      optionStatusWorkings: [
        {
          value: 0,
          label: "Đang làm việc",
        },
        {
          value: 1,
          label: "Đã nghỉ việc",
        },
      ],
      // department
      departmentOptions: [],
      // check row
      selected: [],
      exclude: [],
    };
  },
  methods: {
    async getData() {
      this.loading = true;
      await axios
        .get("get-employee-resume", {
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
    async getDataDepartmentOptions() {
      await axios.get("dataPhongBan").then((response) => {
        if (response.data.status == true) {
          let departmentOptions = [];
          response.data.phong_ban.forEach((item) => {
            departmentOptions.push({
              value: item.id,
              label: item.name,
            });
          });
          this.departmentOptions = departmentOptions;
        }
      });
    },
    setPage(value) {
      this.page = value;
    },
    resetFilter() {
      this.filter = {
        name: "",
        department: [],
        working_status: "",
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
        .post("employee-resume/export-excel", {
          staff_ids: this.selected,
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
  },
  mounted() {
    this.getData();
    this.getDataDepartmentOptions();
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
}
</style>
