<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý nhóm người dùng</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <el-button
                type="primary"
                size="small"
                @click="handleShowFormCreate()"
                >Tạo mới</el-button
              >
            </nav>
          </div>
        </div>
      </div>
    </div>
    <main-modal widthModal="80%">
      <template>
        <h4 class="text-center">Phân quyền</h4>
        <div class="row">
          <el-input
            v-model="permissionSearch"
            placeholder="Tìm kiếm"
            class="mb-3"
          ></el-input>
          <div
            class="col-lg-4 col-md-6"
            v-for="item in filterPermission"
            :key="item.id"
          >
            <input
              type="checkbox"
              id="item.id"
              :value="item.id"
              v-model="rolePermissions"
            />
            <label :for="item.id">{{ item.name }}</label>
          </div>
        </div>
        <div class="form-group-button">
          <el-button
            id="btn_cancel"
            data-bs-dismiss="modal"
            ref="btn_cancel"
            size="small"
            >Hủy</el-button
          >
          <el-button size="small" type="primary" @click="handleAuthorize()"
            >Lưu</el-button
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
          <div class="card" v-show="isShowCreate">
            <div class="card-body">
              <h5 class="card-title mb-0" v-show="isShowCreateTitle">
                Thêm mới nhóm người dùng
              </h5>
              <h5 class="card-title mb-0" v-show="!isShowCreateTitle">
                Cập nhật nhóm người dùng
                <span class="strong-name">"{{ nameUpdate }}"</span>
              </h5>
            </div>
            <div class="form-group-input" v-show="isErrorForm">
              <el-alert :title="titleMessage" type="warning" effect="dark">
              </el-alert>
            </div>

            <div class="form-group-input">
              <span class="label-input">Tên nhóm người dùng: </span>
              <el-input
                placeholder="Tên nhóm người dùng"
                v-model="form.name"
              ></el-input>
            </div>
            <div class="form-group-button">
              <el-button @click="isShowCreate = false">Hủy</el-button>
              <el-button type="primary" @click="handleCreate()">Lưu</el-button>
            </div>
          </div>
          <div class="wrap-table">
            <el-table
              :data="dataFilter"
              empty-text="Không có dữ liệu"
              style="width: 100%"
            >
              <el-table-column
                label="STT"
                prop="index"
                width="80"
                align="center"
              >
              </el-table-column>
              <el-table-column label="Nhóm người dùng" prop="name" align="left">
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
                    type="success"
                    size="mini"
                    data-bs-toggle="modal"
                    data-bs-target="#exampleModal"
                    @click="getRolePermissions(scope.row.id)"
                    >Phân quyền</el-button
                  >
                  <el-button
                    type="warning"
                    size="mini"
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
import { formatString } from "../functions/formatFunctions";
import { errorMessage } from "../errors/error-code";
import mainModal from "../layouts/main-modal.vue";
export default {
  name: "role",
  components: { mainModal },
  data() {
    return {
      tableData: [],
      search: "",
      isShowCreate: false,
      isShowCreateTitle: true,
      form: {
        name: "",
        id: "",
      },
      titleMessage: "",
      isErrorForm: false,
      nameUpdate: "",
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      permissions: [],
      filterPermission: [],
      dialogVisible: false,
      permissionSearch: "",
      rolePermissions: [],
      role: "",
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
    permissionSearch: function () {
      this.filterPermission = this.permissions.filter((item) =>
        formatString(item.name).includes(formatString(this.permissionSearch))
      );
    },
  },
  methods: {
    formatString: formatString,
    setPage(val) {
      this.page = val;
      this.isSetPage = true;
    },
    handleEdit(index, row) {
      console.log(index, row);
      this.isShowCreate = true;
      this.isShowCreateTitle = false;
      this.isErrorForm = false;
      this.form.name = row.name;
      this.form.id = row.id;
      this.nameUpdate = this.form.name;
    },
    handleDelete(index, row) {
      let name = row.name;
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      }).then(async () => {
        await axios
          .post("deleteRole", { array: [row.id] })
          .then((response) => {
            if (response.data.status == true) {
              this.$notify({
                title: "Thông báo",
                message: `Xóa nhóm người dùng "${name}" thành công!`,
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
      await axios.get("dataRoles").then((response) => {
        if (response.data.status == true) {
          this.tableData = response.data.items;
          for (let i = 0; i < this.tableData.length; i++) {
            this.tableData[i]["index"] = i + 1;
          }
          this.pagedTableData(1);
        }
      });
    },
    async handleCreate() {
      if (this.validateForm()) {
        if (this.isShowCreateTitle) {
          await axios
            .post("createRole", this.form)
            .then((response) => {
              if (response.data.status == true) {
                this.isErrorForm = false;
                this.titleMessage = "";
                this.fetchData();
                this.$notify({
                  title: "Thông báo",
                  message: `Tạo nhóm người dùng "${this.form.name}" thành công!`,
                  type: "success",
                });
                this.resetForm();
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
          await axios
            .post("updateRole", this.form)
            .then((response) => {
              if (response.data.status == true) {
                this.isErrorForm = false;
                this.titleMessage = "";
                this.fetchData();
                this.$notify({
                  title: "Thông báo",
                  message: `Cập nhật nhóm người dùng "${this.nameUpdate}" thành "${this.form.name}" thành công!`,
                  type: "success",
                });
                this.resetForm();
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
        }
        this.isShowCreate = false;
      }
    },
    validateForm() {
      if (this.form.name == "") {
        this.isErrorForm = true;
        this.titleMessage = "Tên nhóm người dùng không được để trống";
        return false;
      }

      return true;
    },
    resetForm() {
      this.form.name = "";
      this.form.id = "";
    },
    handleShowFormCreate() {
      this.isShowCreate = true;
      this.isShowCreateTitle = true;
      this.isErrorForm = false;
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
    async getPermissions() {
      await axios
        .get("dataPermissions")
        .then((response) => {
          if (response.data.status) {
            this.permissions = response.data.items;
            this.filterPermission = this.permissions;
            // console.log(this.permissions);
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
    getRolePermissions(id) {
      axios
        .get("dataRolePermission/" + id)
        .then((response) => {
          if (response.data.status) {
            this.rolePermissions = [];
            this.permissionSearch = "";
            this.rolePermissions = response.data.items.map((item) => {
              return item.id_permission;
            });
            this.dialogVisible = true;
            this.role = id;
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
    handleAuthorize() {
      axios
        .post("authorize", {
          role: this.role,
          rolePermissions: this.rolePermissions,
        })
        .then((response) => {
          if (response.data.status) {
            this.$notify({
              title: "Thông báo",
              message: `Phân quyền thành công!`,
              type: "success",
            });
            this.getRolePermissions(this.role);
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
    this.fetchData();
    this.getPermissions();
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
      .el-table_1_column_3 {
        display: flex;
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
<style>
.dialog-permission .el-dialog {
  width: 80%;
}
</style>