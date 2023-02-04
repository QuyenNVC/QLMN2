<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý phòng ban</h4>
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
                    >Tên phòng ban:
                  </span>
                  <el-input
                    placeholder="Tên phòng ban"
                    v-model="form.name"
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
              stripe
              v-loading="loading"
            >
              <el-table-column
                label="STT"
                prop="index"
                width="50"
                align="center"
              >
              </el-table-column>
              <el-table-column label="Tên phòng ban" prop="name">
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
                    data-bs-toggle="modal"
                    type="warning"
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
  name: "phong-ban",
  components: { mainModal, FormWrapper, FooterQlmn },
  data() {
    return {
      tableData: [],
      search: "",
      isShowCreateTitle: true,
      form: {
        name: "",
        id: "",
        id_coso: null,
      },
      nameUpdate: "",
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      title: "Thêm phòng ban",
      id_coso: "",
      cosos: [],
      loading: false,
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
    "form.id_coso": function () {
      this.fetchData();
    },
  },
  methods: {
    setPage(val) {
      this.page = val;
      this.isSetPage = true;
    },
    handleEdit(index, row) {
      this.isShowCreateTitle = false;
      this.form.name = row.name;
      this.form.id = row.id;
      this.nameUpdate = this.form.name;
      this.title = "Cập nhật phòng ban " + this.nameUpdate;
    },
    handleDelete(index, row) {
      let name = row.name;
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      }).then(async () => {
        await axios
          .delete("deletePhongBan/" + row.id)
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
      });
    },
    async fetchData() {
      this.loading = true;
      await axios
        .get("dataPhongBan", {
          params: {
            id_coso: this.form.id_coso,
          },
        })
        .then((response) => {
          if (response.data.status == true) {
            this.tableData = response.data.phong_ban;
            for (let i = 0; i < this.tableData.length; i++) {
              this.tableData[i]["index"] = i + 1;
            }
            this.pagedTableData(1);
          }
        });
      this.loading = false;
    },
    async handleCreate() {
      if (this.validateForm()) {
        if (this.isShowCreateTitle) {
          if (!this.checkExist()) {
            return await axios
              .post("createPhongBan", this.form)
              .then((response) => {
                if (response.data.status == true) {
                  this.fetchData();
                  this.$notify({
                    title: "Thông báo",
                    message: `Tạo phòng ban "${this.form.name}" thành công!`,
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
              message: `Phòng ban này đã tồn tại`,
              type: "warning",
            });
          }
        } else {
          if (!this.checkExist()) {
            return await axios
              .put("updatePhongBan", this.form)
              .then((response) => {
                if (response.data.status == true) {
                  this.fetchData();
                  this.$notify({
                    title: "Thông báo",
                    message: `Cập nhật phòng ban "${this.form.name}" thành công!`,
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
              message: `Phòng ban này đã tồn tại`,
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
        this.$notify({
          title: "Cảnh báo",
          message: "Tên phòng ban không được bỏ trống",
          type: "warning",
        });
        return false;
      }

      return true;
    },
    resetForm() {
      this.form.name = "";
      this.form.id = "";
      this.isShowCreateTitle = true;
      this.title = "Thêm mới phòng ban";
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
    checkExist() {
      let exist = null;
      exist = this.tableData.find((item) => {
        return (
          item.name == this.form.name &&
          item.id_coso == this.form.id_coso &&
          item.id !== this.form.id
        );
      });
      return exist ? true : false;
    },
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
  },
  async created() {
    await this.getCosos();
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
    .label-input {
      flex-basis: 160px;
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