<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý nhân viên</h4>
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
              <!-- <el-button type="danger" size="small" @click="handleDelete()"
                >Xóa</el-button
              > -->
            </nav>
          </div>
        </div>
      </div>
    </div>

    <main-modal widthModal="95%">
      <template>
        <div class="row">
          <div class="col-12">
            <form-wrapper>
              <template v-slot:form-title>{{ title }}</template>
              <template>
                <div class="col-12 wrap-form">
                  <div class="form-group">
                    <span class="form-label required">Mã: </span>
                    <el-input
                      placeholder="Mã NV"
                      v-model="form.ma_nv"
                      :disabled="true"
                    ></el-input>
                  </div>
                  <div class="form-group">
                    <span class="form-label required">Họ tên: </span>
                    <el-input
                      placeholder="Họ và tên"
                      v-model="form.fullname"
                    ></el-input>
                  </div>
                  <div class="form-group">
                    <span class="form-label required">Giới tính: </span>
                    <el-select
                      v-model="form.gender"
                      placeholder="Chọn giới tính"
                    >
                      <el-option label="Nam" value="Nam"> </el-option>
                      <el-option label="Nữ" value="Nữ"> </el-option>
                    </el-select>
                  </div>
                  <div class="form-group">
                    <span class="form-label required">Ngày sinh: </span>
                    <el-date-picker
                      v-model="form.birthday"
                      type="date"
                      placeholder="Chọn ngày sinh"
                      format="dd/MM/yyyy"
                    >
                    </el-date-picker>
                  </div>
                  <div class="form-group">
                    <span class="form-label required">Dân tộc: </span>
                    <el-input
                      placeholder="Dân tộc"
                      v-model="form.dan_toc"
                    ></el-input>
                  </div>
                  <div class="form-group">
                    <span class="form-label required">Điện thoại: </span>
                    <el-input
                      placeholder="Điện thoại"
                      v-model="form.phone"
                      v-mask="'###-###-####'"
                    ></el-input>
                  </div>
                  <div class="form-group">
                    <span class="form-label required">CMND/CCCD: </span>
                    <el-input
                      placeholder="CMND/CCCD"
                      v-model="form.cmnd"
                      v-mask="'#########'"
                    ></el-input>
                  </div>
                  <div class="form-group">
                    <span class="form-label required">Nơi cấp: </span>
                    <el-input
                      placeholder="Nơi cấp"
                      v-model="form.noi_cap"
                    ></el-input>
                  </div>
                  <div class="form-group">
                    <span class="form-label required">Ngày cấp: </span>
                    <el-date-picker
                      v-model="form.ngay_cap"
                      type="date"
                      placeholder="Ngày cấp"
                      format="dd/MM/yyyy"
                    >
                    </el-date-picker>
                  </div>
                  <div class="form-group">
                    <span class="form-label">Email: </span>
                    <el-input
                      placeholder="Email"
                      v-model="form.email"
                    ></el-input>
                  </div>
                  <div class="form-group">
                    <span class="form-label required">Lương ngày: </span>
                    <el-input
                      placeholder="Lương ngày"
                      v-model.lazy="form.luong_ngay"
                      v-money="money"
                    ></el-input>
                  </div>
                  <div class="form-group dia-chi">
                    <span class="form-label required">Địa chỉ: </span>
                    <el-input
                      placeholder="Địa chỉ"
                      v-model="form.address"
                    ></el-input>
                  </div>
                  <div class="form-group">
                    <span class="form-label required">Phòng ban: </span>
                    <el-select
                      v-model="form.id_phong_ban"
                      placeholder="Chọn phòng ban"
                    >
                      <el-option
                        v-for="phong_ban in phongBans"
                        :key="phong_ban.id"
                        :label="phong_ban.name"
                        :value="phong_ban.id"
                      >
                      </el-option>
                    </el-select>
                  </div>
                  <div class="form-group">
                    <span class="form-label required">Ngày vào làm: </span>
                    <el-date-picker
                      v-model="form.ngay_vao_lam"
                      type="date"
                      placeholder="Ngày vào làm"
                      format="dd/MM/yyyy"
                    >
                    </el-date-picker>
                  </div>
                  <div class="form-group">
                    <span class="form-label">Ngày nghỉ làm: </span>
                    <el-date-picker
                      v-model="form.ngay_nghi_lam"
                      type="date"
                      placeholder="Ngày nghỉ làm"
                      format="dd/MM/yyyy"
                      :disabled="!form.status_nghi_viec"
                    >
                    </el-date-picker>
                  </div>
                  <div class="form-group checkbox">
                    <div>
                      <span class="form-label">Trạng thái: </span>
                      <el-checkbox v-model="form.status_nghi_viec"
                        >Đã nghỉ việc</el-checkbox
                      >
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
      <div class="container-fluid">
        <div class="record-wrapper">
          <div class="row">
            <div class="col-12">
              <div class="card filter_form">
                <div class="wrap-filter">
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
                    <div class="form-group-input d-block d-lg-flex">
                      <span class="label-input">Phòng ban: </span>
                      <el-select
                        v-model="filter.phong_ban"
                        placeholder="Phòng ban"
                        filterable
                      >
                        <el-option :value="null" label="Tất cả"></el-option>
                        <el-option
                          v-for="item in phongBans"
                          :key="item.id"
                          :value="item.id"
                          :label="item.name"
                        ></el-option>
                      </el-select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="wrap-table table-record">
                <el-table
                  :data="dataFilter"
                  empty-text="Không có dữ liệu"
                  style="width: 100%"
                  stripe
                  v-loading="loading"
                >
                  <!-- <el-table-column width="40" align="center" fixed>
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
                        :checked="checkSelected(scope.row.id)"
                        @change="changeSelectedRecord(scope.row.id)"
                      />
                    </template>
                  </el-table-column> -->
                  <el-table-column
                    label="MNV"
                    prop="ma_nv"
                    width="100"
                    align="center"
                    fixed
                    sortable
                  >
                  </el-table-column>
                  <el-table-column
                    label="Họ tên"
                    prop="fullname"
                    fixed
                    header-align="center"
                    sortable
                  >
                  </el-table-column>
                  <el-table-column
                    label="Giới tính"
                    prop="gender"
                    align="center"
                    width="80"
                  >
                  </el-table-column>
                  <el-table-column
                    label="Điện thoại"
                    prop="phone"
                    align="center"
                    width="120"
                  >
                  </el-table-column>
                  <el-table-column
                    label="Phòng ban"
                    prop="phongBanName"
                    header-align="center"
                    width="200"
                  >
                  </el-table-column>
                  <el-table-column
                    label="Tình trạng"
                    prop="status"
                    align="center"
                    width="150"
                    sortable=""
                  >
                  </el-table-column>
                  <el-table-column
                    width="150"
                    align="right"
                    header-align="center"
                    fixed="right"
                  >
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
                        @click="editNhanVien(scope.row.ma_nv)"
                        data-bs-toggle="modal"
                        data-bs-target="#exampleModal"
                        ><span>Sửa</span></el-button
                      >
                      <el-button
                        slot="reference"
                        size="mini"
                        type="danger"
                        @click="handleDelete(scope.row.ma_nv)"
                        >Xóa</el-button
                      >
                    </template>
                  </el-table-column>
                </el-table>
              </div>
            </div>
          </div>
        </div>
        <el-pagination
          layout="prev, pager, next"
          :total="total"
          @current-change="setPage"
        >
        </el-pagination>
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
// import VTreeview from "v-treeview";
import axios from "axios";
import { mask } from "vue-the-mask";
import mixin from "./mixin.js";
import { errorMessage } from "../errors/error-code";
import footerQLMN from "../layouts/footer-qlmn.vue";
import MainModal from "../layouts/main-modal.vue";
import SubModal from "../layouts/sub-modal.vue";
import FormWrapper from "../layouts/form-wrapper.vue";

export default {
  name: "nhan-vien",
  mixins: [mixin],
  components: {
    footerQLMN,
    MainModal,
    FormWrapper,
    SubModal,
  },
  directives: { mask },
  data() {
    return {
      tableData: [],
      search: "",
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      form: {
        ma_nv: "",
        fullname: "",
        gender: "Nam",
        birthday: "",
        dan_toc: "",
        cmnd: "",
        noi_cap: "",
        ngay_cap: "",
        phone: "",
        email: "",
        luong_ngay: "",
        address: "",
        ngay_vao_lam: "",
        ngay_nghi_lam: "",
        status: false,
        id_phong_ban: "",
      },
      phongBans: [],
      numberNhanVien: 0,
      filter: {
        phong_ban: null,
        id_coso: null,
      },
      id_coso: "",
      cosos: [],
      loading: false,
      // selected: [],
      // exclude: [],
      total: 0,
      phongBansObject: {},
      title: "Thêm mới nhân viên",
      isEdit: false,
    };
  },
  methods: {
    // METHODS FOR MULTICHECK
    // checkSelected(id) {
    //   if (this.selected.includes(-1)) {
    //     if (this.exclude.includes(id)) {
    //       return false;
    //     } else {
    //       return true;
    //     }
    //   } else {
    //     if (this.selected.includes(id)) {
    //       return true;
    //     } else {
    //       return false;
    //     }
    //   }
    // },
    // changeSelectedRecord(id) {
    //   if (this.selected.includes(-1)) {
    //     if (this.exclude.includes(id)) {
    //       const index = this.exclude.indexOf(id);
    //       if (index > -1) {
    //         this.exclude.splice(index, 1); // 2nd parameter means remove one item only
    //       }
    //     } else {
    //       this.exclude.push(id);
    //     }
    //   } else {
    //     if (this.selected.includes(id)) {
    //       const index = this.selected.indexOf(id);
    //       if (index > -1) {
    //         this.selected.splice(index, 1); // 2nd parameter means remove one item only
    //       }
    //     } else {
    //       this.selected.push(id);
    //     }
    //   }
    // },
    // checkAll() {
    //   if (this.selected.includes(-1)) {
    //     this.exclude = [];
    //     this.selected = [];
    //   } else {
    //     this.selected = [-1];
    //     this.exclude = [];
    //   }
    // },
    // END METHODS FOR MULTICHECK
    setPage(val) {
      this.page = val;
      this.isSetPage = true;
      this.fetchData();
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
    async fetchData() {
      this.loading = true;
      await axios
        .get("dataNhanVien", {
          params: {
            id_phong_ban: this.filter.phong_ban,
            page: this.page,
            pageSize: this.pageSize,
            id_coso: this.filter.id_coso,
          },
        })
        .then((response) => {
          if (response.data.status) {
            this.total = response.data.total;
            this.dataFilter = response.data.nhan_vien.map((item, index) => {
              return {
                ...item,
                phongBanName: this.phongBansObject[item.id_phong_ban],
                status: item.status_nghi_viec == 0 ? "Đang làm việc" : "Đã nghỉ việc",
              };
            });
          }
        })
        .catch();
      this.loading = false;
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
            this.phongBansObject = this.phongBans.reduce(
              (object, item, index) => {
                return {
                  ...object,
                  [item.id]: item.name,
                };
              },
              {}
            );
            this.filter.phong_ban = null;
          }
        });
    },
    async handleSubmit() {
      if (this.validate()) {
        this.formatBeforeSend();
        return await axios
          .post("updateNhanVien", this.form)
          .then((response) => {
            if (response.data.status == true) {
              this.$notify({
                title: "Thông báo",
                message: `Đã lưu thông tin nhân viên "${this.form.fullname}"!`,
                type: "success",
              });
              this.page = 1;
              this.fetchData();
              return true;
            } else {
              this.showAlert(
                "Đã có lỗi trong quá trình xử lý, vui lòng thử lại sau",
                "error"
              );
            }
          })
          .catch((e) => {
            this.$notify({
              title: "Thông báo",
              message: errorMessage[e.response.status],
              type: "warning",
            });
          });
      }
      return false;
    },
    async save() {
      let result = await this.handleSubmit();
      if (result) {
        this.$refs.btn_cancel.$el.click();
      }
    },
    async saveAndNew() {
      let result = await this.handleSubmit();
      if (result) {
        this.resetForm();
      }
    },
    validate() {
      if (this.form.fullname == "") {
        this.showAlert("Họ tên không được để trống", "warning");
        return false;
      }

      if (this.form.birthday == "") {
        this.showAlert("Ngày sinh không được để trống", "warning");
        return false;
      }

      if (this.form.dan_toc == "") {
        this.showAlert("Dân tộc không được để trống", "warning");
        return false;
      }

      if (this.form.cmnd == "") {
        this.showAlert("CMND/CCCD không được để trống", "warning");
        return false;
      }

      if (this.form.noi_cap == "") {
        this.showAlert("Nơi cấp cmnd/cccd không được để trống", "warning");
        return false;
      }

      if (this.form.ngay_cap == "") {
        this.showAlert("Ngày cấp cmnd/cccd không được để trống", "warning");
        return false;
      }

      if (this.form.phone == "") {
        this.showAlert("Số điện thoại không được để trống", "warning");
        return false;
      }

      if (this.form.luong_ngay == "") {
        this.showAlert("Lương ngày không được để trống", "warning");
        return false;
      }

      if (this.form.address == "") {
        this.showAlert("Địa chỉ không được để trống", "warning");
        return false;
      }

      if (this.form.ngay_vao_lam == "") {
        this.showAlert("Ngày vào làm không được để trống", "warning");
        return false;
      }

      if (this.form.status_nghi_viec) {
        if (this.form.ngay_nghi_lam == "" || this.form.ngay_nghi_lam == null) {
          this.showAlert(
            "Bạn cần chọn ngày nghỉ làm khi khai báo nhân viên này nghỉ việc",
            "warning"
          );
          return false;
        }
      }

      if (this.form.id_phong_ban == "") {
        this.showAlert("Bạn chưa chọn phòng ban", "warning");
        return false;
      }

      return true;
    },
    showAlert(message, type) {
      this.$notify({
        title: "Cảnh báo",
        message,
        type,
      });
    },
    formatBeforeSend() {
      this.form.birthday = this.formatDate(this.form.birthday);
      this.form.ngay_cap = this.formatDate(this.form.ngay_cap);
      this.form.ngay_vao_lam = this.formatDate(this.form.ngay_vao_lam);
      if (this.form.ngay_nghi_lam != "") {
        this.form.ngay_nghi_lam = null;
      }
      this.form.luong_ngay = this.form.luong_ngay.replace(".", "");
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
    resetForm() {
      this.isEdit = false;
      this.title = "Thêm mới nhân viên";
      this.form = {
        ma_nv: "",
        fullname: "",
        gender: "Nam",
        birthday: "",
        dan_toc: "",
        cmnd: "",
        noi_cap: "",
        ngay_cap: "",
        phone: "",
        email: "",
        luong_ngay: "",
        address: "",
        ngay_vao_lam: "",
        ngay_nghi_lam: "",
        status_nghi_viec: false,
        id_phong_ban: "",
      };
      setTimeout(() => {
        this.form.luong_ngay = "";
      }, 1);
      this.createMaNV();
    },
    async createMaNV() {
      await axios.get("createMaNV").then((response) => {
        if (response.data.status) {
          this.form.ma_nv = response.data.item;
        }
      });
    },
    async editNhanVien(manv) {
      await axios.get("dataNhanVienGetOne/" + manv).then((response) => {
        if (response.data.status == true) {
          let dataNV = response.data.nhan_vien;
          this.title = `Cập nhật nhân viên "${dataNV.fullname}"`;
          this.form = {
            ma_nv: dataNV.ma_nv,

            fullname: dataNV.fullname,
            gender: dataNV.gender,
            birthday: dataNV.birthday,
            dan_toc: dataNV.dan_toc,
            cmnd: dataNV.cmnd,
            noi_cap: dataNV.noi_cap,
            ngay_cap: dataNV.ngay_cap,
            phone: dataNV.phone,
            email: dataNV.email,
            luong_ngay: dataNV.luong_ngay,
            address: dataNV.address,
            ngay_vao_lam: dataNV.ngay_vao_lam,
            ngay_nghi_lam: dataNV.ngay_nghi_lam,
            status_nghi_viec: dataNV.status_nghi_viec == 1 ? true : false,
            id_phong_ban: dataNV.id_phong_ban,
          };
          setTimeout(() => {
            this.$forceUpdate();
          }, 1);
        }
      });
    },
    async handleDelete(id) {
      this.$confirm("Bạn thật sự muốn xóa nhân viên này?", "Cảnh báo", {
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy",
        type: "warning",
      })
        .then(async () => {
          await axios
            .post("deleteNhanVien/" + id)
            .then((response) => {
              if (response.data.status == true) {
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa nhân viên "${this.form.fullname}" thành công!`,
                  type: "success",
                });
                this.resetForm();
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
    Promise.all([this.getCosos()]).then(async () => {
      await this.getAllPhongBan();
      this.fetchData();
    });
  },
  watch: {
    "filter.phong_ban": function () {
      this.fetchData();
    },
    "filter.id_coso": async function () {
      await this.getAllPhongBan();
      this.fetchData();
    },
  },
};
</script>

<style lang="scss" scoped>
::v-deep {
  .wrap-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    .form-group {
      flex-basis: 30%;
    }
  }
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
    }
  }

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
}
</style>
<style lang="scss">
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
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px 20%;
    .label-input {
      flex-basis: 150px;
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