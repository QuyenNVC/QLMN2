<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý hình thức chấm công</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <el-button
                type="primary"
                size="small"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                @click="resetForm()"
                >Tạo mới</el-button
              >
              <!-- <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Library</li>
                            </ol> -->
            </nav>
          </div>
        </div>
      </div>
    </div>

    <main-modal widthModal="60%">
      <template>
        <form-wrapper>
          <template v-slot:form-title>{{
            isCreate ? "Tạo mới" : "Cập nhật"
          }}</template>
          <template>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group-input">
                  <span class="label-input form-label required"
                    >Tên hình thức:
                  </span>
                  <el-input
                    placeholder="Tên hình thức"
                    v-model="form.name"
                  ></el-input>
                </div>
                <div class="form-group-input">
                  <span class="label-input">Ngày công: </span>
                  <el-input-number
                    placeholder="Ngày công"
                    :min="0"
                    v-model="form.ngay_cong"
                  ></el-input-number>
                </div>
                <div class="form-group-input">
                  <span class="label-input form-label required">Ghi chú: </span>
                  <el-input
                    placeholder="Ghi chú"
                    v-model="form.ghi_chu"
                  ></el-input>
                </div>
              </div>
            </div>
          </template>
        </form-wrapper>
        <div class="form-group-button custom">
          <el-button
            id="btn_cancel"
            @click="isShowCreate = false"
            data-bs-dismiss="modal"
            ref="btn_cancel"
            v-show="false"
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
      <!-- ============================================================== -->
      <div class="row">
        <div class="col-12">
          <div class="card" v-show="isShowCreate">
            <div class="card-body">
              <h5 class="card-title mb-0" v-show="isCreate">
                Thêm hình thức chấm công
              </h5>
              <h5 class="card-title mb-0" v-show="!isCreate">
                Cập nhật hình thức chấm công
                <span class="strong-name">"{{ nameUpdate }}"</span>
              </h5>
            </div>
            <div class="form-group-input" v-show="isErrorForm">
              <el-alert :title="titleMessage" type="warning" effect="dark">
              </el-alert>
            </div>

            <div class="form-group-input">
              <span class="label-input">Tên hình thức: </span>
              <el-input
                placeholder="Tên hình thức"
                v-model="form.name"
              ></el-input>
            </div>
            <div class="form-group-input">
              <span class="label-input">Ngày công: </span>
              <el-input-number
                v-model="form.ngay_cong"
                controls-position="right"
                @change="handleChange"
                :min="0"
              ></el-input-number>
              <!-- <el-input
                placeholder="Số ngày công"
                v-model=""
                type="number"
                min="0"
              ></el-input> -->
            </div>

            <div class="form-group-input">
              <span class="label-input">Ghi chú: </span>
              <el-input placeholder="Ghi chú" v-model="form.ghi_chu"></el-input>
            </div>
            <div class="form-group-button">
              <el-button @click="handleCancel()">Hủy</el-button>
              <el-button type="primary" @click="handleCreate()">Lưu</el-button>
            </div>
          </div>
          <div class="wrap-table">
            <el-table
              :data="dataFilter"
              empty-text="Không có dữ liệu"
              style="width: 100%"
            >
              <el-table-column label="STT" prop="index"> </el-table-column>
              <el-table-column label="Mã" prop="id"> </el-table-column>
              <el-table-column label="Tên" prop="name"> </el-table-column>
              <el-table-column label="Ngày công" prop="ngay_cong">
              </el-table-column>
              <el-table-column label="Ghi chú" prop="ghi_chu">
              </el-table-column>
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
                    data-bs-toggle="modal"
                    data-bs-target="#exampleModal"
                    @click="handleEdit(scope.$index, scope.row)"
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
import { errorMessage } from "../errors/error-code";
import mainModal from "../layouts/main-modal.vue";
import FormWrapper from "../layouts/form-wrapper.vue";

export default {
  components: { mainModal, FormWrapper },
  name: "phu-cap",
  data() {
    return {
      tableData: [],
      search: "",
      isShowCreate: false,
      isCreate: true,
      form: {
        id: "",
        name: "",
        ngay_cong: "",
        ghi_chu: "",
      },
      titleMessage: "",
      isErrorForm: false,
      nameUpdate: "",
      phongBans: [],
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
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
  },
  methods: {
    setPage(val) {
      this.page = val;
    },
    handleEdit(index, row) {
      // this.isShowCreate = true;
      this.isCreate = false;
      this.isErrorForm = false;
      this.form.name = row.name;
      this.form.ngay_cong = row.ngay_cong;
      this.form.ghi_chu = row.ghi_chu;
      this.form.id = row.id;
      this.nameUpdate = this.form.name;
    },
    handleDelete(index, row) {
      let username = row.name;
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      }).then(async () => {
        await axios
          .post("deleteHinhThucChamCong/" + row.id)
          .then((response) => {
            console.log(response);
            if (response.data.status == true) {
              this.$notify({
                title: "Thông báo",
                message: `Xóa hình thức chấm công "${username}" thành công!`,
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
      await axios.get("dataHinhThucChamCong").then((response) => {
        if (response.data.status == true) {
          this.tableData = response.data.phu_cap;
          for (let i = 0; i < this.tableData.length; i++) {
            this.tableData[i]["index"] = i + 1;
          }
          this.pagedTableData(1);
        }
      });
      this.getAllPhongBan();
    },
    async getAllPhongBan() {
      await axios.get("dataPhongBan").then((response) => {
        if (response.data.status == true) {
          this.phongBans = response.data.phong_ban;
        }
      });
    },
    async handleCreate() {
      if (this.validateForm()) {
        if (this.isCreate) {
          await axios
            .post("createHinhThucChamCong", this.form)
            .then((response) => {
              if (response.data.status == true) {
                this.isErrorForm = false;
                this.titleMessage = "";
                this.fetchData();
                this.$notify({
                  title: "Thông báo",
                  message: `Tạo hình thức chấm công "${this.form.name}" thành công!`,
                  type: "success",
                });
                this.resetForm();
                this.isShowCreate = false;
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
          this.$refs.btn_cancel.$el.click();
        } else {
          await axios
            .post("updateHinhThucChamCong", this.form)
            .then((response) => {
              if (response.data.status == true) {
                this.isErrorForm = false;
                this.titleMessage = "";
                this.fetchData();
                this.$notify({
                  title: "Thông báo",
                  message: `Cập nhật hình thức chấm công "${this.form.name}" thành công!`,
                  type: "success",
                });
                this.resetForm();
                this.isShowCreate = false;
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
          this.$refs.btn_cancel.$el.click();
        }
      }
    },
    validateForm() {
      if (this.form.name == "") {
        this.isErrorForm = true;
        this.titleMessage = "Tên hình thức chấm công không được để trống";
        return false;
      }

      if (this.form.ngay_cong == "") {
        this.isErrorForm = true;
        this.titleMessage = "Số ngày công không được để trống";
        return false;
      }

      // if ( !this.checkNumber(this.form.phu_cap) ) {
      //   this.isErrorForm = true;
      //   this.titleMessage = "Phụ cấp không đúng định dạng";
      //   return false;
      // }

      return true;
    },
    checkNumber(x) {
      // check if the passed value is a number
      if (typeof x == "number" && !isNaN(x)) {
        return true;
      } else {
        if (Number.isInteger(x)) {
          return true;
        } else {
          return false;
        }
      }
    },
    resetForm() {
      this.form.name = "";
      this.form.ngay_cong = "";
      this.form.ghi_chu = "";
      this.isCreate = true;
    },
    handleShowFormCreate() {
      this.isShowCreate = true;
      this.isCreate = true;
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
  mounted() {
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

.form-group-button.custom {
  margin-top: 0 !important;
  margin-right: 0 !important;
}
</style>