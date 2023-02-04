<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý phụ cấp nhân viên</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb"></nav>
          </div>
        </div>
      </div>
    </div>
    <main-modal widthModal="60%">
      <template>
        <div class="row">
          <div class="col-12">
            <form-wrapper>
              <template v-slot:form-title>
                Phụ cấp nhân viên
                <span class="strong-name">"{{ nameUpdate }}"</span>
              </template>
              <template>
                <div class="row wrap-form">
                  <div
                    class="col-md-6 col-lg-4"
                    v-for="item in phuCaps"
                    :key="item.id"
                  >
                    <div class="form-group-input">
                      <el-checkbox
                        :key="item.id"
                        v-model="form[item.id]"
                        @change="changeCheck()"
                      >
                        {{ item.name }} ( {{ item.phu_cap | formatMoney
                        }}{{ item.don_vi_tinh }} )
                      </el-checkbox>
                    </div>
                  </div>
                </div>
              </template>
            </form-wrapper>
          </div>
        </div>
        <div class="form-group-button">
          <el-button
            id="btn_cancel"
            data-bs-dismiss="modal"
            ref="btn_cancel"
            @click="handleCancelEdit()"
            >Hủy</el-button
          >
          <el-button type="primary" @click="handleCreate()">Lưu</el-button>
        </div>
      </template>
    </main-modal>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
      <!-- ============================================================== -->
      <!-- Start Page Content -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="wrap-filter">
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group-input">
                  <span class="label-input">Tháng/năm: </span>
                  <el-date-picker
                    v-model="filter.month"
                    type="month"
                    placeholder="Chọn tháng/năm"
                    format="MM/yyyy"
                  ></el-date-picker>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12" v-if="!id_coso">
                <div class="form-group-input">
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
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group-input">
                  <span class="label-input">Chọn phòng ban: </span>
                  <el-select
                    class="chon-phong-ban"
                    v-model="phongBan"
                    placeholder="Chọn phòng ban"
                  >
                    <el-option label="Tất cả" value=""> </el-option>
                    <el-option
                      v-for="phong_ban in phongBans"
                      :key="phong_ban.id"
                      :label="phong_ban.name"
                      :value="phong_ban.id"
                    >
                    </el-option>
                  </el-select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- ============================================================== -->
      <div class="row">
        <div class="col-12">
          <div class="card" v-show="false">
            <div class="card-body">
              <h5 class="card-title mb-0">
                Phụ cấp nhân viên
                <span class="strong-name">"{{ nameUpdate }}"</span>
              </h5>
            </div>
            <div class="form-group-input" v-show="isErrorForm">
              <el-alert :title="titleMessage" type="warning" effect="dark">
              </el-alert>
            </div>

            <div class="form-group-input">
              <el-checkbox
                v-for="item in phuCaps"
                :key="item.id"
                v-model="form[item.id]"
                @change="changeCheck()"
              >
                {{ item.name }} ( {{ item.phu_cap | formatMoney
                }}{{ item.don_vi_tinh }} )
              </el-checkbox>
            </div>
            <div class="form-group-button">
              <el-button @click="handleCancelEdit()">Hủy</el-button>
              <el-button type="primary" @click="handleCreate()">Lưu</el-button>
            </div>
          </div>
          <div class="wrap-table">
            <el-table
              :data="dataFilter"
              empty-text="Không có dữ liệu"
              style="width: 100%"
              stripe=""
              v-loading="loading"
            >
              <el-table-column
                label="STT"
                prop="index"
                align="center"
                width="80"
                sortable=""
              >
              </el-table-column>
              <el-table-column
                label="Mã NV"
                prop="ma_nv"
                align="center"
                sortable=""
                width="100"
              >
              </el-table-column>
              <el-table-column
                label="Họ Tên"
                prop="fullname"
                sortable=""
                header-align="center"
              >
              </el-table-column>
              <el-table-column
                label="Phòng Ban"
                prop="phong_ban"
                header-align="center"
              >
              </el-table-column>
              <el-table-column
                label="Phụ cấp"
                header-align="center"
                width="200"
              >
                <template slot-scope="scope">
                  {{ renderListPhuCap(scope.row.data) }}
                </template>
              </el-table-column>
              <el-table-column align="right" width="150">
                <template slot="header" slot-scope="scope">
                  <el-input
                    v-model="search"
                    size="mini"
                    placeholder="Tìm kiếm"
                  />
                </template>
                <template slot-scope="scope">
                  <el-button
                    size="mini"
                    type="warning"
                    data-bs-toggle="modal"
                    data-bs-target="#exampleModal"
                    @click="handleEdit(scope.$index, scope.row)"
                    >Phụ cấp</el-button
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
    <footerQLMN></footerQLMN>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
  </div>
</template>

<script>
import axios from "axios";
import mixin from "./mixin.js";
import { errorMessage } from "../errors/error-code";
import footerQLMN from "../layouts/footer-qlmn.vue";
import MainModal from "../layouts/main-modal.vue";
import SubModal from "../layouts/sub-modal.vue";
import FormWrapper from "../layouts/form-wrapper.vue";
import { convertDateToMonthAndYear } from "../functions/formatFunctions";
export default {
  name: "phu-cap-nhan-vien",
  mixins: [mixin],
  components: {
    footerQLMN,
    MainModal,
    FormWrapper,
    SubModal,
  },
  props: {
    phongBanUser: {
      type: String,
    },
  },
  data() {
    return {
      tableData: [],
      search: "",
      isShowCreate: false,
      isShowCreateTitle: true,
      form: {
        ma_nv: "",
      },
      titleMessage: "",
      isErrorForm: false,
      nameUpdate: "",
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      months: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
      years: [2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030],
      month: 1,
      year: 2020,
      phongBan: this.phongBanUser != -1 ? parseInt(this.phongBanUser) : "",
      phongBanName: "",
      phongBans: [],
      phuCaps: [],
      test: "",
      filter: {
        month: "",
        id_coso: "",
      },
      id_coso: "",
      cosos: [],
      loading: false,
    };
  },
  watch: {
    phongBan: function () {
      // this.setNamePhongBan();
      this.getAllNhanVienDangLamViec();
    },
    month: function () {
      this.getAllNhanVienDangLamViec();
      //this.setDateOfMonth();
      //this.getAllChamCong();
    },
    year: function () {
      this.getAllNhanVienDangLamViec();
      //this.setDateOfMonth();
      //this.getAllChamCong();
    },
    search: function () {
      if (this.search != "") {
        this.pagedTableData(5);
        this.isSetPage = false;
      } else {
        this.pagedTableData(1);
        this.isSetPage = true;
      }
    },
    page: function () {
      this.pagedTableData(1);
    },
    "filter.month": function () {
      let result = convertDateToMonthAndYear(this.filter.month);
      this.month = result.month;
      this.year = result.year;
    },
    "filter.id_coso": async function () {
      await this.getAllPhongBan();
      this.phongBan = "";
      this.getAllNhanVienDangLamViec();
    },
  },
  methods: {
    setPage(val) {
      this.page = val;
      this.isSetPage = true;
    },
    handleEdit(index, row) {
      console.log(index, row);
      this.isShowCreate = true;
      this.isShowCreateTitle = false;
      this.form.name = row.fullname;
      this.form.ma_nv = row.ma_nv;
      this.nameUpdate = row.fullname;
      this.setPhuCap(row.ma_nv);
    },
    setPhuCap(ma_nv) {
      this.tableData.forEach((item) => {
        if (item.ma_nv == ma_nv) {
          if (item.data) {
            for (const [key, value] of Object.entries(item.data)) {
              if (value == true || value == false) {
                this.form[key] = value;
              }
            }
          } else {
            this.resetFormForUpDate();
          }
        }
      });
    },
    handleDelete(index, row) {
      let name = row.name;
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      })
        .then(async () => {
          await axios
            .post("deletePhongBan/" + row.id)
            .then((response) => {
              if (response.data.status == true) {
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa phòng ban "${name}" thành công!`,
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
        })
        .catch();
    },
    async getAllPhongBan() {
      await axios
        .get("dataPhongBan", {
          params: {
            id_coso: this.filter.id_coso,
          },
        })
        .then((response) => {
          if (response.data.status == true) {
            this.phongBans = response.data.phong_ban;
          }
        });
    },
    async getAllNhanVienDangLamViec() {
      this.setPage(1);
      this.loading = true;
      await axios
        .get(
          `dataNhanVienDangLamViecPhuCap/${this.month}/${this.year}/${this.phongBan}`,
          {
            params: {
              id_coso: this.filter.id_coso,
            },
          }
        )
        .then((response) => {
          if (response.data.status == true) {
            this.tableData = response.data.nhan_vien;
            for (let i = 0; i < this.tableData.length; i++) {
              this.tableData[i]["index"] = i + 1;
              if (this.tableData[i].data) {
                this.tableData[i].data = JSON.parse(this.tableData[i].data);
              }
            }
            this.pagedTableData(1);
          }
        });
      this.loading = false;
    },
    async handleCreate() {
      if (this.validateForm()) {
        await axios
          .post("updatePhuCapNhanVien", {
            thang: this.month,
            nam: this.year,
            phuCap: this.form,
          })
          .then((response) => {
            if (response.data.status == true) {
              this.isErrorForm = false;
              this.titleMessage = "";
              this.getAllNhanVienDangLamViec();
              this.$notify({
                title: "Thông báo",
                message: `Cập nhật phụ cấp cho nhân viên "${this.form.name}" thành công!`,
                type: "success",
              });
              this.resetForm();
              this.$refs.btn_cancel.$el.click();
            } else {
              this.isErrorForm = true;
              this.titleMessage = response.data.message;
            }
          })
          .catch((e) => {
            this.$notify({
              title: "Thông báo",
              message: errorMessage[e.response.status],
              type: "warning",
            });
          });
        this.isShowCreate = false;
      }
    },
    validateForm() {
      if (this.form.name == "") {
        this.isErrorForm = true;
        this.titleMessage = "Tên phòng ban không được để trống";
        return false;
      }

      return true;
    },
    resetForm() {
      this.form.ma_nv = "";
      this.getAllPhuCap();
    },
    resetFormForUpDate() {
      this.getAllPhuCap();
    },
    handleShowFormCreate() {
      this.isShowCreate = true;
      this.isShowCreateTitle = true;
      this.resetForm();
    },
    pagedTableData(feature = 1) {
      if (feature != 1) {
        this.dataFilter = this.tableData.filter(
          (data) =>
            !this.search ||
            data.fullname.toLowerCase().includes(this.search.toLowerCase()) ||
            data.ma_nv.toLowerCase().includes(this.search.toLowerCase())
        );
      } else {
        this.dataFilter = this.tableData.slice(
          this.pageSize * this.page - this.pageSize,
          this.pageSize * this.page
        );
      }
    },
    async getAllPhuCap() {
      await axios.get("dataPhuCap").then((response) => {
        if (response.data.status == true) {
          this.phuCaps = response.data.phu_cap;
          this.phuCaps.forEach((item, index) => {
            this.form[item.id] = false;
          });
        }
      });
    },
    changeCheck() {
      this.$forceUpdate();
    },
    handleCancelEdit() {
      this.isShowCreate = false;
      this.getAllPhuCap();
    },
    renderListPhuCap(data) {
      // console.log(data);
      let str = "";
      if (data == null) return str;

      for (const [key, value] of Object.entries(data)) {
        if (value == true) {
          this.phuCaps.forEach((item) => {
            if (key == item.id) {
              if (str == "") {
                str += item.name + `(${item.phu_cap}${item.don_vi_tinh})`;
              } else {
                str +=
                  ", " +
                  item.name +
                  `(${this.formatMoney(item.phu_cap)}${item.don_vi_tinh})`;
              }
            }
          });
        }
      }
      return str;
    },
    async getCosos() {
      await axios
        .get("getCosos")
        .then((response) => {
          if (response.data.status) {
            this.cosos = response.data.items;
            this.filter.id_coso = response.data.id_coso
              ? Number(response.data.id_coso)
              : Number(this.cosos[0].id);
            this.id_coso = response.data.id_coso;
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
    this.filter.month = new Date();
    Promise.all([this.getCosos(), this.getAllPhuCap()]).then(async () => {
      await this.getAllPhongBan();
      this.getAllNhanVienDangLamViec();
    });
    this.getCosos();
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
      .el-input__inner {
        width: 155px;
      }
      td {
        &.el-table_1_column_6 {
          &.is-right {
            // display: flex;
            // justify-content: flex-end;
          }
        }
      }
    }
  }
  .el-table td,
  .el-table th.is-leaf {
    border: 0.5px solid #ebeef5;
  }
}

::v-deep {
  .el-input__inner,
  .el-checkbox__inner {
    border: 1px solid #bdc3d4;
  }
  .el-button:focus {
    outline: none;
  }

  // .el-select {
  //   flex-basis: 20%;
  //   margin-right: 5px;
  //   &.chon-phong-ban {
  //     flex-basis: 40%;
  //   }
  //   &.thang {
  //     flex-basis: 13%;
  //   }
  // }
}
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
      display: flex;
      justify-content: flex-start;
      align-items: center;
      padding: 10px 10%;
      // flex-wrap: wrap;
      .label-input {
        flex-basis: 150px;
      }
      .el-checkbox {
        margin-bottom: 14px;
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

    .wrap-filter {
      display: flex;
      justify-content: flex-start;
      align-items: center;
      .form-group-input {
        padding: 0;
        flex: 1;
        .label-input {
          text-align: right;
          padding-right: 15px;
        }
      }
    }
  }
}
.create-phong-ban {
  margin-left: 0;
  margin-right: 0;
}
</style>