<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý cơ sở</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb" class="d-flex">
              <a
                href="/them-co-so"
                class="d-inline-block text-white"
                target="_blank"
                ><el-button type="primary" size="small">Thêm mới</el-button></a
              >

              <!-- <el-button
                type="primary"
                size="small"
                @click="resetForm()"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                >Thêm mới</el-button
              > -->
              &ensp;
              <el-button
                class="ml-2"
                type="danger"
                size="small"
                @click="handleDeleteAll()"
                >Xóa</el-button
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
              <p
                class="mb-0 text-danger text-center position-absolute"
                style="top: 10px"
                v-show="!isShowCreateTitle"
              >
                Lưu ý: không nhập mật khẩu để giữ lại password
              </p>
              <div class="col-md-6">
                <div class="form-group-input">
                  <span class="label-input form-label required"
                    >Họ và tên:
                  </span>
                  <el-input
                    placeholder="Họ và tên"
                    v-model="form.name"
                  ></el-input>
                </div>
                <div class="form-group-input">
                  <span class="label-input form-label required"
                    >Tên tài khoản:
                  </span>
                  <el-input
                    placeholder="Tên tài khoản"
                    v-model="form.username"
                    :disabled="!isShowCreateTitle"
                  ></el-input>
                </div>
                <div class="form-group-input">
                  <span
                    class="label-input form-label required"
                    v-bind:class="{ required: isShowCreateTitle }"
                    >Mật khẩu:
                  </span>
                  <el-input
                    placeholder="Mật khẩu"
                    v-model="form.password"
                    type="password"
                  ></el-input>
                </div>
                <div class="form-group-input" v-if="!id_coso">
                  <span class="label-input form-label">Cơ sở: </span>
                  <el-select
                    v-model="form.id_coso"
                    filterable
                    placeholder="Cơ sở"
                  >
                    <el-option label="Cơ sở chính" :value="null"></el-option>
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
              <div class="col-md-6">
                <div class="form-group-input">
                  <span class="label-input form-label">Số điện thoại: </span>
                  <el-input
                    placeholder="Số điện thoại"
                    v-model="form.phone_number"
                  ></el-input>
                </div>
                <div class="form-group-input">
                  <span class="label-input form-label">Email: </span>
                  <el-input placeholder="Email" v-model="form.email"></el-input>
                </div>
                <div class="form-group-input">
                  <span class="label-input form-label">Vai trò: </span>
                  <el-select
                    v-model="form.roles"
                    multiple
                    filterable
                    placeholder="Vai trò"
                  >
                    <el-option
                      v-for="item in roles"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"
                    >
                    </el-option>
                  </el-select>
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
              <el-table-column width="40" align="center">
                <template slot="header" slot-scope="scope">
                  <el-checkbox
                    :key="resetCheckbox"
                    @change="checkAll($event)"
                  ></el-checkbox>
                </template>
                <template slot-scope="scope">
                  <el-checkbox
                    :key="resetCheckbox"
                    :value="scope.row.checked"
                    @change="changeArraySelectedRecord(scope.row.id)"
                  ></el-checkbox>
                </template>
              </el-table-column>
              <el-table-column label="Logo" width="80" align="center">
                <template slot-scope="scope">
                  <img :src="scope.row.urlLogo" alt="" width="50" height="50" />
                </template>
              </el-table-column>
              <el-table-column label="Tên cơ sở" prop="school_name">
              </el-table-column>
              <el-table-column label="Địa chỉ" prop="address">
              </el-table-column>
              <el-table-column
                label="Người đại diện"
                prop="representatives"
                width="150"
              >
              </el-table-column>
              <el-table-column label="Số điện thoại" prop="phone_number">
              </el-table-column>
              <el-table-column align="right" width="180" fixed="right">
                <template slot="header" slot-scope="scope">
                  <el-input
                    v-model="search"
                    size="mini"
                    placeholder="Tìm kiếm"
                  />
                </template>
                <template slot-scope="scope">
                  <a
                    :href="`/cap-nhat-co-so/${scope.row.id}`"
                    target="_blank"
                    class="text-white"
                    ><el-button size="mini" type="warning">Sửa</el-button></a
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
import { formatString } from "../functions/formatFunctions";
import MainModal from "../layouts/main-modal.vue";
import FormWrapper from "../layouts/form-wrapper.vue";
export default {
  name: "info-list",
  components: {
    MainModal,
    FormWrapper,
  },
  data() {
    return {
      tableData: [],
      search: "",
      // isShowCreate: false,
      isShowCreateTitle: true,
      form: {
        name: "",
        username: "",
        password: "",
        phone_number: "",
        email: "",
        roles: [],
        id_coso: null,
        // room: "",
      },
      // titleMessage: "",
      // isErrorForm: false,
      nameUpdate: "",
      // phongBans: [],
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      roles: [],
      arraySelectedRecord: [],
      resetCheckbox: 0,
      title: "Thêm người dùng mới",
      id_coso: "",
      cosos: [],
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
    changeArraySelectedRecord(id) {
      var item = null;
      item = this.arraySelectedRecord.find((idItem) => idItem == id);
      if (item) {
        this.arraySelectedRecord = this.arraySelectedRecord.filter(
          (idItem) => idItem != id
        );
        this.dataFilter.find((item) => item.id == id).checked = false;
      } else {
        this.arraySelectedRecord.push(id);
        this.dataFilter.find((item) => item.id == id).checked = true;
      }
      console.log(this.arraySelectedRecord);
    },
    setPage(val) {
      this.page = val;
    },
    handleEdit(index, row) {
      this.isShowCreateTitle = false;
      for (let key in this.form) {
        this.form[key] = row[key];
      }
      this.form.password = "";
      this.form.roles = row.role_ids;
      // this.form.roles = row.role_ids.map((item) => {
      //   return {
      //     id: item,
      //     name: this.roles.find((role) => role.id == item).name,
      //   };
      // });
      // this.form.room = row.id_phong_ban;
      this.nameUpdate = this.form.name;
    },
    handleDelete(index, row) {
      let name = row.school_name;
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      })
        .then(async () => {
          await axios
            .post("deleteInfo", { array: [row.id] })
            .then((response) => {
              if (response.data.status == true) {
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa cơ sở "${name}" thành công!`,
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
        .catch(() => {});
    },
    fetchData() {
      this.arraySelectedRecord = [];
      this.resetCheckbox += 1;
      axios
        .get("getCosos")
        .then((response) => {
          if (response.data.status) {
            this.tableData = response.data.items;
            this.pagedTableData(1);
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
    },
    // async getAllPhongBan() {
    //   await axios.get("dataPhongBan").then((response) => {
    //     if (response.data.status == true) {
    //       this.phongBans = response.data.phong_ban;
    //     }
    //   });
    // },
    handleCreate() {
      console.log(this.form);
      if (this.validateForm()) {
        if (this.isShowCreateTitle) {
          return axios
            .post("createUser", this.form)
            .then((response) => {
              if (response.data.status == true) {
                this.fetchData();
                this.$notify({
                  title: "Thông báo",
                  message: `Tạo tài khoản cho người dùng "${this.form.name}" thành công!`,
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
          return axios
            .post("updateUser", this.form)
            .then((response) => {
              if (response.data.status == true) {
                this.fetchData();
                this.$notify({
                  title: "Thông báo",
                  message: `Cập nhật tài khoản cho người dùng "${this.form.name}" thành công!`,
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
          message: "Họ tên không được để trống",
          type: "warning",
        });
        return false;
      }

      if (this.form.username == "") {
        this.$notify({
          title: "Thông báo",
          message: "Tên tài khoản không được để trống",
          type: "warning",
        });
        return false;
      }

      if (this.isShowCreateTitle == true) {
        if (this.form.password == "") {
          this.$notify({
            title: "Thông báo",
            message: "Mật khẩu không được để trống",
            type: "warning",
          });
          return false;
        }
      }
      return true;
    },
    resetForm() {
      for (let key in this.form) {
        this.form[key] = "";
      }
      this.form.roles = [];
      this.form.id_coso = null;
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
    async getRoles() {
      await axios
        .get("dataRoles")
        .then((response) => {
          if (response.data.status) {
            // console.log(response.data.items);
            this.roles = response.data.items;
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
    getCosos() {
      axios
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
    async handleDisable(row) {
      await axios
        .post("disableAccount", { id: row.id })
        .then((response) => {
          if (response.data.status) {
            this.fetchData();
            this.$notify({
              title: "Thông báo",
              message: `Đã tạm ngưng tài khoản!`,
              type: "success",
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
    },
    checkAll(e) {
      if (e) {
        this.dataFilter.map((item, index) => {
          item.checked = true;
        });
      } else {
        this.dataFilter.map((item, index) => {
          item.checked = false;
        });
      }
      this.arraySelectedRecord = !e
        ? []
        : this.dataFilter.map((item, index) => {
            return item.id;
          });
      console.log(this.arraySelectedRecord);
    },
    async handleDeleteAll() {
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      })
        .then(async () => {
          await axios
            .post("deleteInfo", { array: this.arraySelectedRecord })
            .then((response) => {
              if (response.data.status == true) {
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa cơ sở thành công!`,
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
        .catch(() => {});
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
      .el-input__inner {
        width: 155px;
      }
      // .el-table_1_column_4 {
      //   display: flex;
      //   .cell {
      //     width: 100%;
      //   }
      // }
    }
  }

  .el-select {
    width: 100%;
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
    // display: flex;
    // justify-content: center;
    // align-items: center;
    // padding: 10px 20%;
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