<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý năng khiếu</h4>
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
    <main-modal>
      <template>
        <form-wrapper>
          <template v-slot:form-title>{{ title }}</template>
          <template>
            <div class="row">
              <div class="col-12">
                <div class="form-group-input">
                  <span class="label-input form-label required"
                    >Tên năng khiếu:
                  </span>
                  <el-input
                    placeholder="Tên năng khiếu"
                    v-model="form.name"
                  ></el-input>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group-input">
                  <span
                    class="label-input"
                    style="margin-right: 33px; white-space: nowrap"
                    >Ghi chú:
                  </span>
                  <el-input
                    type="textarea"
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
                label="Tên năng khiếu của trẻ"
                prop="name"
                header-align="center"
                width="250"
              >
              </el-table-column>
              <el-table-column
                label="Mô tả/Ghi chú"
                prop="note"
                header-align="center"
              >
              </el-table-column>
              <el-table-column align="right" width="250">
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
import { errorMessage } from "../errors/error-code";
import mainModal from "../layouts/main-modal.vue";
import FooterQlmn from "../layouts/footer-qlmn.vue";
import FormWrapper from "../layouts/form-wrapper.vue";
export default {
  components: { mainModal, FooterQlmn, FormWrapper },
  name: "nang-khieu",

  data() {
    return {
      tableData: [],
      search: "",
      title: "Thêm mới năng khiếu",
      isShowCreateTitle: true,
      form: {
        name: "",
        id: "",
        note: "",
      },
      titleMessage: "",
      isErrorForm: false,
      nameUpdate: "",
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
      this.isSetPage = true;
    },
    handleEdit(index, row) {
      this.nameUpdate = row.name;
      this.form.name = row.name;
      this.form.id = row.id;
      this.form.note = row.note;
      // this.isShowCreate = true;
      this.isShowCreateTitle = false;
      // this.isErrorForm = false;
      this.title = `Cập nhật năng khiếu "${row.name}"`;
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
            .post("deleteNangKhieu/" + row.id)
            .then((response) => {
              if (response.data.status == true) {
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa năng khiếu "${name}" thành công!`,
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
    async fetchData() {
      await axios.get("dataNangKhieus").then((response) => {
        if (response.data.status == true) {
          this.tableData = response.data.nangkhieus;
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
              .post("createNangKhieu", this.form)
              .then((response) => {
                if (response.data.status == true) {
                  this.isErrorForm = false;
                  this.titleMessage = "";
                  this.fetchData();
                  this.$notify({
                    title: "Thông báo",
                    message: `Thêm mới năng khiếu "${this.form.name}" thành công!`,
                    type: "success",
                  });
                  return true;
                } else {
                  this.$notify({
                    title: "Thông báo",
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
              message: `Năng khiếu này đã tồn tại`,
              type: "warning",
            });
          }
        } else {
          return axios
            .post("updateNangKhieu", this.form)
            .then((response) => {
              if (response.data.status == true) {
                this.isErrorForm = false;
                this.titleMessage = "";
                this.fetchData();
                this.$notify({
                  title: "Thông báo",
                  message: `Cập nhật năng khiếu "${this.form.name}" thành công!`,
                  type: "success",
                });
                return true;
              } else {
                this.$notify({
                  title: "Thông báo",
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
          title: "Thông báo",
          message: "Tên năng khiếu không được để trống",
          type: "warning",
        });
        return false;
      }
      return true;
    },
    checkExist() {
      var checNangkhieu = null;
      checNangkhieu = this.tableData.find(
        (nangkhieu) =>
          nangkhieu.name == this.form.name && nangkhieu.id !== this.form.id
      );
      return checNangkhieu ? true : false;
    },
    resetForm() {
      this.form.name = "";
      this.form.id = "";
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
  created() {
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