<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4>Quản lý danh mục chi phí</h4>
          <div class="ms-auto text-end">
            <nav class="breadcrumb">
              <el-button
                type="primary"
                size="small"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                @click="resetForm"
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
                    >Tên chi phí:
                  </span>
                  <el-input
                    placeholder="Tên chi phí"
                    v-model="form.name"
                  ></el-input>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group-input">
                  <span class="label-input" style="margin-right: 34px"
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
          <el-button type="primary" @click="save()">Lưu</el-button>
          <el-button type="primary" @click="saveAndNew()"
            >Lưu và thêm mới</el-button
          >
        </div>
      </template>
    </main-modal>
    <div class="container-fluid">
      <div class="record-wrapper">
        <div class="row">
          <div class="col-12" v-if="!id_coso">
            <div class="card filter_form">
              <div class="wrap-filter">
                <div>
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
              >
                <!-- LÀM MULTI SELECT, KHÔNG ĐƯỢC XÓA -->
                <!-- <el-table-column width="40" align="center">
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
                </el-table-column> -->
                <el-table-column
                  label="Tên loại chi phí"
                  prop="name"
                  width="250"
                  header-align="center"
                >
                </el-table-column>
                <el-table-column
                  label="Ghi chú"
                  prop="note"
                  header-align="center"
                >
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
                      @click="handleEdit(scope.row)"
                      data-bs-toggle="modal"
                      data-bs-target="#exampleModal"
                      ><span>Sửa</span></el-button
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
          </div>
        </div>
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

    <!-- footer -->
    <!-- ============================================================== -->
    <footer-qlmn></footer-qlmn>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
  </div>
</template>


<script>
import VTreeview from "v-treeview";
import axios from "axios";
import { errorMessage } from "../errors/error-code";
import { mask } from "vue-the-mask";
import mixin from "./mixin.js";
import MainModal from "../layouts/main-modal.vue";
import FooterQlmn from "../layouts/footer-qlmn.vue";
import FormWrapper from "../layouts/form-wrapper.vue";
import multiselect from "vue-multiselect";
import { formatString } from "../functions/formatFunctions";

export default {
  name: "danh-muc-chi-phi",
  components: {
    multiselect,
    MainModal,
    FormWrapper,
    FooterQlmn,
  },
  mixins: [mixin],
  data() {
    return {
      tableData: [],
      search: "",
      nameUpdate: "",
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      arraySelectedRecord: [],
      resetCheckbox: 0,
      title: "Thêm danh mục chi phí",
      form: {
        id: "",
        name: "",
        note: "",
      },
      filter: {
        id_coso: "",
      },
      cosos: [],
      id_coso: "",
    };
  },
  methods: {
    // LÀM MULTI SELECT, KHÔNG ĐƯỢC XÓA
    // changeArraySelectedRecord(id) {
    //   var item = null;
    //   item = this.arraySelectedRecord.find((idItem) => idItem == id);
    //   if (item) {
    //     this.arraySelectedRecord = this.arraySelectedRecord.filter(
    //       (idItem) => idItem != id
    //     );
    //     this.dataFilter.find((item) => item.id == id).checked = false;
    //   } else {
    //     this.arraySelectedRecord.push(id);
    //     this.dataFilter.find((item) => item.id == id).checked = true;
    //   }
    // },
    // checkAll(e) {
    //   if (e) {
    //     this.dataFilter.map((item, index) => {
    //       item.checked = true;
    //     });
    //   } else {
    //     this.dataFilter.map((item, index) => {
    //       item.checked = false;
    //     });
    //   }
    //   this.arraySelectedRecord = !e
    //     ? []
    //     : this.dataFilter.map((item, index) => {
    //         return item.id;
    //       });
    // },
    // LÀM MULTI SELECT, KHÔNG ĐƯỢC XÓA

    setPage(val) {
      this.page = val;
      this.isSetPage = true;
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
            .post("/deleteDanhMucChiPhi", { array: [row.id] })
            .then((response) => {
              if (response.data.status == true) {
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa loại chi phí "${name}" thành công!`,
                  type: "success",
                });
                this.fetchData();
              } else {
                this.$notify({
                  title: "Thông báo",
                  message: response.data.message,
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
      // this.arraySelectedRecord = [];
      // this.resetCheckbox += 1;
      axios
        .get("/dataDanhMucChiPhi", {
          params: this.filter,
        })
        .then((response) => {
          if (response.data.status == true) {
            this.tableData = response.data.items;
            this.tableData = this.tableData.map((item) => {
              return { ...item, checked: false };
            });
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
    findById(arr, id) {
      var result = null;
      if (id) {
        result = arr.find((item) => item.id == id);
      }
      return result ? result.name : "";
    },
    // phần xử lý form
    validateForm() {
      if (this.form.name == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Tên loại chi phí không được bỏ trống!",
          type: "warning",
        });
        return false;
      }

      return true;
    },
    checkExist() {
      var checkName = null;
      checkName = this.tableData.find(
        (item) =>
          formatString(item.name) == formatString(this.form.name) &&
          item.id != this.form.id
      );
      return checkName ? true : false;
    },
    resetForm() {
      this.title = "Thêm danh mục chi phí";
      this.form.name = "";
      this.form.id = "";
      this.form.note = "";
    },
    handleEdit(row) {
      this.title = `Cập nhật loại chi phí "${row.name}"`;
      for (let key in this.form) {
        this.form[key] = row[key];
      }
    },
    handleSubmit() {
      if (this.validateForm()) {
        if (!this.checkExist()) {
          return axios
            .post("submitDanhMucChiPhi", {
              ...this.form,
              id_coso: this.filter.id_coso,
            })
            .then((response) => {
              if (response.data.status) {
                this.fetchData();
                this.$notify({
                  title: "Thông báo",
                  message: !this.form.id
                    ? `Thêm chi phí "${this.form.name}" thành công!`
                    : `Cập nhật chi phí "${this.form.name}" thành công!`,
                  type: "success",
                });
                this.resetForm();
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
            message: "Loại chi phí này đã tồn tại",
            type: "warning",
          });
        }
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
  async created() {
    await this.getCosos();
    this.fetchData();
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
    "filter.id_coso": function () {
      this.fetchData();
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
    padding: 10px 20%;
    .label-input {
      flex-basis: 150px;
    }
  }
}
.form-group-button {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding: 10px 0;
  margin-top: -20px;
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