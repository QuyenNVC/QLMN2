<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý hình thức điểm danh</h4>
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
    <main-modal widthModal="40%">
      <template>
        <form-wrapper>
          <template v-slot:form-title>{{ title }}</template>
          <template>
            <div class="row">
              <div class="col-12">
                <div class="form-group-input">
                  <span class="label-input form-label required"
                    >Tên hình thức:
                  </span>
                  <el-input
                    placeholder="Tên hình thức"
                    v-model="form.name"
                  ></el-input>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group-input">
                  <span class="label-input">Học phí theo ngày áp dụng: </span>
                  <multiselect
                    v-model="form.daily"
                    tag-placeholder="Add this as new tag"
                    placeholder="Học phí ngày"
                    label="name"
                    track-by="id"
                    :options="daily_fees"
                    :multiple="true"
                    :taggable="true"
                    @tag="addTag"
                  ></multiselect>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group-input check-box">
                  <span class="label-input form-label"
                    >Khấu trừ học phí tháng:
                  </span>
                  <el-checkbox
                    v-model="form.khau_tru_hoc_phi_thang"
                  ></el-checkbox>
                </div>
                <div class="col-12">
                  <div class="form-group-input">
                    <span class="label-input">Ghi chú: </span>
                    <el-input
                      placeholder="Ghi chú"
                      v-model="form.note"
                    ></el-input>
                  </div>
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
        <div class="col-12" v-if="!id_coso">
          <div class="card filter_form">
            <div class="wrap-filter">
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group-input">
                  <span class="text-nowrap">Cơ sở: </span
                  >&nbsp;&nbsp;&nbsp;&nbsp;
                  <el-select v-model="form.id_coso" placeholder="Cơ sở">
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
        <div class="col-12">
          <div class="wrap-table">
            <el-table
              :data="dataFilter"
              empty-text="Không có dữ liệu"
              style="width: 100%"
            >
              <el-table-column label="Mã" prop="id"> </el-table-column>
              <el-table-column label="Tên" prop="name"> </el-table-column>
              <el-table-column label="Học phí ngày áp dụng" prop="daily_name">
              </el-table-column>
              <!-- <el-table-column label="Dịch vụ cộng thêm áp dụng" prop="service_name"> </el-table-column> -->
              <el-table-column label="Ghi chú" prop="note"> </el-table-column>
              <el-table-column align="right">
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
import axios from "axios";
import Multiselect from "vue-multiselect";
import { errorMessage } from "../errors/error-code";
import MainModal from "../layouts/main-modal.vue";
import FooterQlmn from "../layouts/footer-qlmn.vue";
import FormWrapper from "../layouts/form-wrapper.vue";
export default {
  name: "hinh-thuc-diem-danh",
  components: {
    multiselect: Multiselect,
    MainModal,
    FormWrapper,
    FooterQlmn,
  },
  data() {
    return {
      tableData: [],
      title: "Thêm mới hình thức điểm danh",
      search: "",
      isShowCreate: false,
      isShowCreateTitle: true,
      form: {
        id: "",
        name: "",
        // diem_chuyen_can: "",
        // monthly: "",
        daily: "",
        khau_tru_hoc_phi_thang: "",
        // service: "",
        note: "",
        id_coso: "",
      },
      titleMessage: "",
      isErrorForm: false,
      nameUpdate: "",
      phongBans: [],
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      monthly_fees: [],
      daily_fees: [],
      services: [],
      cosos: [],
      id_coso: "",
    };
  },
  watch: {
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
    "form.id_coso": async function () {
      await this.getDailyFees();
      this.fetchData();
    },
  },
  methods: {
    async getCosos() {
      await axios
        .get("getCosos")
        .then((response) => {
          if (response.data.status) {
            this.cosos = response.data.items;
            this.form.id_coso = response.data.id_coso
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
    async getDailyFees() {
      await axios
        .get("dataDailyFees", {
          params: this.form,
        })
        .then((response) => {
          if (response.data.status == true) {
            this.daily_fees = response.data.dailyfees;
          }
        });
    },

    setPage(val) {
      this.page = val;
    },
    handleEdit(index, row) {
      this.isShowCreateTitle = false;
      this.form.name = row.name;
      this.nameUpdate = row.name;
      this.form.daily = [];
      this.form.khau_tru_hoc_phi_thang = row.khau_tru_hoc_phi_thang
        ? true
        : false;
      this.form.note = row.note;
      this.form.id = row.id;
      console.log(row);
      this.nameUpdate = this.form.name;
      this.title = `Cập nhật hình thức điểm danh "${row.name}"`;
      this.form.daily = row.daily.map((id) => {
        return {
          id: id,
          name: this.daily_fees.find((item) => item.id == id).name,
        };
      });
    },
    handleDelete(index, row) {
      let username = row.name;
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      }).then(async () => {
        await axios
          .post("/deleteHinhThucDiemDanh/" + row.id)
          .then((response) => {
            if (response.data.status == true) {
              this.$notify({
                title: "Thông báo",
                message: `Xóa hình thức điểm danh "${username}" thành công!`,
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
    async fetchData() {
      await axios
        .get("dataHinhThucDiemDanh", {
          params: {
            id_coso: this.form.id_coso,
          },
        })
        .then((response) => {
          if (response.data.status == true) {
            this.tableData = response.data.hinhThucs;
            for (let i = 0; i < this.tableData.length; i++) {
              this.tableData[i]["daily"] = this.tableData[i]["daily"]
                ? this.tableData[i]["daily"].split(",")
                : [];
              this.tableData[i]["daily_name"] = this.tableData[i]["daily"].map(
                (item) => {
                  let fee_name = null;
                  fee_name = this.daily_fees.find((fee) => fee.id == item);
                  return fee_name ? fee_name.name : "";
                }
              );
              this.tableData[i]["daily_name"] =
                this.tableData[i]["daily_name"].join(", ");
            }
            // console.log(this.tableData);
            this.pagedTableData(1);
          }
        });
    },
    handleCreate() {
      if (this.validateForm()) {
        if (this.isShowCreateTitle) {
          if (!this.checkExist()) {
            return axios
              .post("createHinhThucDiemDanh", this.form)
              .then((response) => {
                if (response.data.status == true) {
                  this.isErrorForm = false;
                  this.titleMessage = "";
                  this.fetchData();
                  this.$notify({
                    title: "Thông báo",
                    message: `Tạo hình thức điểm danh "${this.form.name}" thành công!`,
                    type: "success",
                  });
                  this.resetForm();
                  this.isShowCreate = false;
                  return true;
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
          } else {
            this.$notify({
              title: "Thông báo",
              message: `Hình thức này đã tồn tại`,
              type: "warning",
            });
          }
        } else {
          if (!this.checkExist()) {
            return axios
              .post("updateHinhThucDiemDanh", this.form)
              .then((response) => {
                if (response.data.status == true) {
                  this.isErrorForm = false;
                  this.titleMessage = "";
                  this.fetchData();
                  this.$notify({
                    title: "Thông báo",
                    message: `Cập nhật hình thức điểm danh "${this.form.name}" thành công!`,
                    type: "success",
                  });
                  this.resetForm();
                  this.isShowCreate = false;
                  return true;
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
          } else {
            this.$notify({
              title: "Thông báo",
              message: `Hình thức này đã tồn tại`,
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
    validateForm() {
      if (this.form.name == "") {
        this.isErrorForm = true;
        this.titleMessage = "Tên hình thức điểm danh không được để trống";
        return false;
      }
      return true;
    },
    checkExist() {
      var checkName = null;
      checkName = this.tableData.find(
        (item) =>
          item.name == this.form.name &&
          item.id != this.form.id &&
          item.id_coso == this.form.id_coso
      );
      return checkName ? true : false;
    },
    resetForm() {
      this.form.name = "";
      this.form.note = "";
      this.form.id = "";
      this.form.khau_tru_hoc_phi_thang = "";
      this.form.daily = "";
      this.isShowCreateTitle = true;
      this.title = "Thêm mới hình thức điểm danh";
    },
    handleShowFormCreate() {
      this.isShowCreate = true;
      this.isShowCreateTitle = true;
      this.isErrorForm = false;
      this.resetForm();
    },
    handleCancel() {
      this.isShowCreate = false;
      this.resetForm();
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
  },
  async created() {
    await this.getCosos();
    await this.getDailyFees();
    this.fetchData();
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
      .el-table_1_column_4 {
        .cell {
          width: 100%;
        }
      }
      .el-table_1_column_2 {
        text-align: left;
      }
    }
  }

  .el-select {
    width: 100%;
  }

  .el-table td,
  .el-table th.is-leaf {
    border: 0.5px solid #ebeef5;
  }
  .el-input-number {
    width: 100%;
  }
  .el-input-number.is-controls-right .el-input__inner {
    padding-left: 15px;
    padding-right: 50px;
    text-align: left;
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
    padding: 10px 0;
    .label-input {
      flex-basis: 200px;
    }
    &.check-box {
      justify-content: flex-start;
    }
  }

  .form-group-button {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 10px 25%;
  }
}
</style>