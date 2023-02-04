<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4>Quản lý danh mục cơ sở vật chất</h4>
          <div class="ms-auto text-end">
            <nav class="breadcrumb">
              <el-button type="danger" size="mini" @click="handleDeleteAll"
                >Xóa</el-button
              >
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
    <main-modal widthModal="60%">
      <template>
        <form-wrapper>
          <template v-slot:form-title>{{ title }}</template>
          <template>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group-input">
                  <span class="label-input form-label required"
                    >Tên cơ sở vật chất:
                  </span>
                  <el-input
                    placeholder="Tên cơ sở vật chất"
                    v-model="form.name"
                  ></el-input>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group-input">
                  <span class="label-input form-label required"
                    >Nhóm cơ sở vật chất:
                  </span>
                  <el-select
                    v-model="form.type_id"
                    placeholder="Nhóm cơ sở vật chất"
                  >
                    <el-option
                      v-for="item in nhomCoSoVatChat"
                      :key="item.id"
                      :value="item.id"
                      :label="item.name"
                    ></el-option>
                  </el-select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group-input">
                  <span class="label-input" style="margin-right: 60px"
                    >Đơn vị tính:
                  </span>
                  <el-input
                    type="text"
                    placeholder="Đơn vị tính"
                    v-model="form.unit"
                  ></el-input>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group-input">
                  <span class="label-input" style="margin-right: 95px"
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
          <div class="col-12">
            <div class="card filter_form">
              <div class="row">
                <div class="col-lg-3 col-md-6" v-if="!id_coso">
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="">Cơ sở: </span>
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
                <div class="col-lg-4 col-md-6">
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="">Nhóm loại CSVC: </span>
                    <el-select
                      v-model="filter.type_id"
                      placeholder="Nhóm loại CSVC"
                    >
                      <el-option selected value="" label="Tất cả"></el-option>
                      <el-option
                        v-for="item in nhomCoSoVatChat"
                        :key="item.id"
                        :value="item.id"
                        :label="item.name"
                      ></el-option>
                    </el-select>
                  </div>
                </div>
              </div>

              <!-- <div class="form-group-button" style="justify-content: center">
                <el-button type="" @click="resetFilter()">Làm mới</el-button>
                <el-button type="primary" @click="handleSearch()"
                  >Tìm kiếm</el-button
                >
              </div> -->
            </div>
            <div class="wrap-table table-record">
              <el-table
                :data="dataFilter"
                empty-text="Không có dữ liệu"
                style="width: 100%"
                stripe
                v-loading="loading"
              >
                <el-table-column width="40" align="center">
                  <template slot="header" slot-scope="scope">
                    <!-- <el-checkbox
                      :key="resetCheckbox"
                      @change="checkAll($event)"
                    ></el-checkbox> -->
                    <input
                      type="checkbox"
                      :checked="selected.includes(-1)"
                      @change="checkAll()"
                    />
                  </template>
                  <template slot-scope="scope">
                    <!-- <el-checkbox
                      :key="resetCheckbox"
                      :value="scope.row.checked"
                      @change="changeArraySelectedRecord(scope.row.id)"
                    ></el-checkbox> -->
                    <input
                      type="checkbox"
                      :checked="checkSelected(scope.row.id)"
                      @change="changeSelectedRecord(scope.row.id)"
                    />
                  </template>
                </el-table-column>
                <el-table-column
                  label="Tên danh mục cơ sở vật chất"
                  prop="name"
                >
                </el-table-column>
                <el-table-column label="Nhóm cơ sở vật chất" prop="type_name">
                </el-table-column>
                <el-table-column label="Đơn vị tính" prop="unit">
                </el-table-column>
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
        :total="total"
        :page-size="pageSize"
        :current-page.sync="page"
        :page-sizes="[10, 20, 30, 50]"
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
import axios from "axios";
import { errorMessage } from "../errors/error-code";
import mixin from "./mixin.js";
import MainModal from "../layouts/main-modal.vue";
import FooterQlmn from "../layouts/footer-qlmn.vue";
import FormWrapper from "../layouts/form-wrapper.vue";
import multiselect from "vue-multiselect";
import { formatString } from "../functions/formatFunctions";

export default {
  name: "danh-muc-co-so-vat-chat",
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
      // arraySelectedRecord: [],
      resetCheckbox: 0,
      title: "Thêm danh mục cơ sở vật chất",
      form: {
        id: "",
        name: "",
        type_id: "",
        unit: "",
        note: "",
      },
      nhomCoSoVatChat: [],
      filter: {
        type_id: "",
        id_coso: null,
      },
      total: 0,
      id_coso: "",
      cosos: [],
      loading: false,
      selected: [],
      exclude: [],
    };
  },
  methods: {
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
    checkSelected(id) {
      if (this.selected.includes(-1)) {
        if (this.exclude.includes(id)) {
          return false;
        } else {
          return true;
        }
      } else {
        if (this.selected.includes(id)) {
          return true;
        } else {
          return false;
        }
      }
    },
    changeSelectedRecord(id) {
      if (this.selected.includes(-1)) {
        if (this.exclude.includes(id)) {
          const index = this.exclude.indexOf(id);
          if (index > -1) {
            this.exclude.splice(index, 1); // 2nd parameter means remove one item only
          }
        } else {
          this.exclude.push(id);
        }
      } else {
        if (this.selected.includes(id)) {
          const index = this.selected.indexOf(id);
          if (index > -1) {
            this.selected.splice(index, 1); // 2nd parameter means remove one item only
          }
        } else {
          this.selected.push(id);
        }
      }
    },
    checkAll() {
      if (this.selected.includes(-1)) {
        this.exclude = [];
        this.selected = [];
      } else {
        this.selected = [-1];
        this.exclude = [];
      }
    },
    setPage(val) {
      this.page = val;
      this.isSetPage = true;
      this.fetchData();
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
            .post("/deleteDanhMucCoSoVatChat", {
              selected: [row.id],
              excludes: [],
            })
            .then((response) => {
              if (response.data.status == true) {
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa danh mục cơ sở vật chất "${name}" thành công!`,
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
    handleDeleteAll() {
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      })
        .then(async () => {
          await axios
            .post("/deleteDanhMucCoSoVatChat", {
              id_coso: this.filter.id_coso,
              selected: this.selected,
              excludes: this.exclude,
            })
            .then((response) => {
              if (response.data.status == true) {
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa danh mục cơ sở vật chất thành công!`,
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
    async getNhomCoSoVatChat() {
      await axios
        .get("/dataNhomCoSoVatChat")
        .then((response) => {
          if (response.data.status == true) {
            this.nhomCoSoVatChat = response.data.items;
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
    async fetchData() {
      // this.arraySelectedRecord = [];
      // this.resetCheckbox += 1;
      this.loading = true;
      await axios
        .post("/dataDanhMucCoSoVatChat", {
          ...this.filter,
          page: this.page,
          pageSize: this.pageSize,
        })
        .then((response) => {
          if (response.data.status == true) {
            this.dataFilter = response.data.items;
            this.total = response.data.count;
            this.dataFilter = this.dataFilter.map((item) => {
              let type_name = this.nhomCoSoVatChat.find(
                (type) => type.id === item.type_id
              );
              return {
                ...item,
                checked: false,
                type_name: type_name ? type_name.name : "",
              };
            });
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
      this.loading = false;
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
          message: "Tên cơ sơ vật chất không được bỏ trống!",
          type: "warning",
        });
        return false;
      }

      if (this.form.type_id == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Nhóm cơ sơ vật chất không được bỏ trống!",
          type: "warning",
        });
        return false;
      }

      return true;
    },
    checkExist() {
      var checkName = null;
      checkName = this.dataFilter.find(
        (item) =>
          formatString(item.name) == formatString(this.form.name) &&
          item.id !== this.form.id
      );
      console.log(checkName);
      return checkName ? true : false;
    },
    resetForm() {
      this.title = "Thêm danh mục cơ sở vật chất";
      for (let key in this.form) {
        this.form[key] = "";
      }
    },
    handleEdit(row) {
      this.title = `Cập nhật danh mục cơ sở vật chất "${row.name}"`;
      for (let key in this.form) {
        this.form[key] = row[key];
      }
      console.log(this.form);
    },
    handleSubmit() {
      if (this.validateForm()) {
        if (!this.checkExist()) {
          return axios
            .post("submitDanhMucCoSoVatChat", {
              ...this.form,
              id_coso: this.filter.id_coso,
            })
            .then((response) => {
              if (response.data.status) {
                this.fetchData();
                this.$notify({
                  title: "Thông báo",
                  message: !this.form.id
                    ? `Thêm danh mục cơ sở vật chất "${this.form.name}" thành công!`
                    : `Cập nhật danh mục cơ sở vật chất "${this.form.name}" thành công!`,
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
            message: "Danh mục cơ sở vật chất này đã tồn tại",
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
  created() {
    Promise.all([this.getCosos(), this.getNhomCoSoVatChat()]).then(() => {
      this.fetchData();
    });
  },
  watch: {
    "filter.type_id": function () {
      this.selected = [];
      this.exclude = [];
      this.setPage(1);
    },
    "filter.id_coso": function () {
      this.selected = [];
      this.exclude = [];
      this.setPage(1);
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
    padding: 10px 20px;
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