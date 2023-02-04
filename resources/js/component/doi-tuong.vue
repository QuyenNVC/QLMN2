<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý nhóm đối tượng học sinh</h4>
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
                  <span class="label-input form-label required"
                    >Tên nhóm đối tượng:
                  </span>
                  <el-input
                    placeholder="Tên nhóm đối tượng"
                    v-model="form.name"
                  ></el-input>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group-input">
                  <span
                    class="label-input form-label required"
                    style="margin-right: 25px"
                    >Định mức giảm (%):
                  </span>
                  <el-input-number
                    placeholder="Định mức giảm (%)"
                    :min="0"
                    v-model="form.dinh_muc_giam"
                  ></el-input-number>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group-input">
                  <span class="label-input" style="margin-right: 10px"
                    >Định mức giảm tiền:
                  </span>
                  <el-input
                    placeholder="Định mức giảm tiền"
                    v-model="form.dinh_muc_giam_tien"
                  ></el-input>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group-input">
                  <span
                    class="label-input"
                    style="margin-right: 65px; white-space: nowrap"
                    >Ghi chú:
                  </span>
                  <el-input
                    type="text"
                    placeholder="Ghi chú"
                    v-model="form.note"
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
          <div class="wrap-table">
            <el-table
              :data="dataFilter"
              empty-text="Không có dữ liệu"
              style="width: 100%"
            >
              <el-table-column
                label="STT"
                prop="index"
                width="50"
                align="center"
              >
              </el-table-column>
              <el-table-column
                label="Tên loại đối tượng ưu tiên"
                prop="name"
                header-align="center"
              >
              </el-table-column>
              <el-table-column
                label="Định mức giảm (%)"
                prop="dinh_muc_giam"
                align="center"
                width="150"
              >
              </el-table-column>
              <el-table-column
                label="Định mức giảm tiền"
                prop="dinh_muc_giam_tien"
                header-align="center"
              >
              </el-table-column>
              <el-table-column
                label="Mô tả/Ghi chú"
                prop="note"
                header-align="center"
              >
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
                    @click="handleEdit(scope.$index, scope.row)"
                    data-bs-toggle="modal"
                    data-bs-target="#exampleModal"
                    type="warning"
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
import { errorMessage } from "../errors/error-code";
import mainModal from "../layouts/main-modal.vue";
import FormWrapper from "../layouts/form-wrapper.vue";
import FooterQlmn from "../layouts/footer-qlmn.vue";
export default {
  components: { mainModal, FormWrapper, FooterQlmn },
  name: "doi-tuong",
  data() {
    return {
      tableData: [],
      search: "",
      // isShowCreate: false,
      isShowCreateTitle: true,
      form: {
        name: "",
        id: "",
        dinh_muc_giam: "",
        dinh_muc_giam_tien: "",
        note: "",
      },
      titleMessage: "",
      isErrorForm: false,
      nameUpdate: "",
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      title: "Thêm đối tượng ưu tiên",
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
      this.isSetPage = true;
    },
    handleEdit(index, row) {
      this.nameUpdate = row.name;
      this.title = "Cập nhật nhóm đối tượng " + row.name;
      this.form.name = row.name;
      this.form.id = row.id;
      this.form.dinh_muc_giam = row.dinh_muc_giam;
      this.form.dinh_muc_giam_tien = row.dinh_muc_giam_tien;
      this.form.note = row.note;
      this.isShowCreateTitle = false;
    },
    handleDelete(index, row) {
      let name = row.name;
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      }).then(async () => {
        await axios
          .post("deleteDoituong/" + row.id)
          .then((response) => {
            if (response.data.status == true) {
              this.$notify({
                title: "Thông báo",
                message: `Xóa đối tượng "${name}" thành công!`,
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
      await axios.get("dataDoituongs").then((response) => {
        if (response.data.status == true) {
          this.tableData = response.data.doituongs;
          for (let i = 0; i < this.tableData.length; i++) {
            this.tableData[i]["index"] = i + 1;
          }
          this.pagedTableData(1);
        }
      });
    },
    handleCreate() {
      if (this.validateForm()) {
        if (this.isShowCreateTitle) {
          if (!this.checkExist()) {
            return axios
              .post("createDoituong", this.form)
              .then((response) => {
                if (response.data.status == true) {
                  this.isErrorForm = false;
                  this.titleMessage = "";
                  this.fetchData();
                  this.$notify({
                    title: "Thông báo",
                    message: `Tạo đối tượng "${this.form.name}" thành công!`,
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
              message: `Nhóm đối tượng này đã tồn tại`,
              type: "warning",
            });
          }
        } else {
          return axios
            .post("updateDoituong", this.form)
            .then((response) => {
              if (response.data.status == true) {
                this.fetchData();
                this.$notify({
                  title: "Thông báo",
                  message: `Cập nhật lớp học "${this.form.name}" thành công!`,
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
        this.$notify({
          title: "Cảnh báo",
          message: "Tên nhóm đối tượng không được để trống",
          type: "warning",
        });
        return false;
      }
      if (this.form.dinh_muc_giam == "" && this.form.dinh_muc_giam_tien == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Định mức giảm hoặc định mức giảm tiền không được để trống",
          type: "warning",
        });
        return false;
      }

      return true;
    },
    checkExist() {
      var checkDoituong = null;
      checkDoituong = this.tableData.find(
        (doituong) =>
          doituong.name == this.form.name && doituong.id !== this.form.id
      );
      return checkDoituong ? true : false;
    },
    resetForm() {
      this.form.name = "";
      this.form.id = "";
      this.form.dinh_muc_giam = "";
      this.form.dinh_muc_giam_tien = "";
      this.form.note = "";
      this.isShowCreateTitle = true;
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
      .el-table_1_column_3 {
        .cell {
          width: 100%;
        }
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

  .luu-y {
    color: #7d42dc;
    font-style: italic;
    text-align: center;
  }
}
</style>