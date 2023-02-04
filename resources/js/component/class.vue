<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý lớp học</h4>
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
                  <span class="label-input form-label required">Tên lớp: </span>
                  <el-input
                    placeholder="Tên lớp"
                    v-model="form.name"
                  ></el-input>
                </div>
                <div class="form-group-input">
                  <span class="label-input form-label required">Năm học: </span>
                  <el-select v-model="form.school_year" placeholder="Năm học">
                    <el-option
                      v-for="school_year in school_years"
                      :key="school_year.id"
                      :label="school_year.period"
                      :value="school_year.id"
                    ></el-option>
                  </el-select>
                </div>
                <div class="form-group-input">
                  <span class="label-input form-label required"
                    >Khối lớp:
                  </span>
                  <el-select v-model="form.grade" placeholder="Chọn khối lớp">
                    <el-option
                      v-for="grade in grades"
                      :key="grade.id"
                      :label="grade.name"
                      :value="grade.id"
                    ></el-option>
                  </el-select>
                </div>
                <div class="form-group-input">
                  <span class="label-input form-label required"
                    >Giáo viên<br />chủ nhiệm:
                  </span>
                  <el-select
                    v-model="form.home_teacher"
                    placeholder="Giáo viên chủ nhiệm"
                    filterable
                  >
                    <el-option
                      v-for="teacher in teachers"
                      :key="teacher.ma_nv"
                      :label="teacher.fullname"
                      :value="teacher.ma_nv"
                    ></el-option>
                  </el-select>
                </div>
                <!-- <div class="form-group-input" v-if="!id_coso">
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
                </div> -->
              </div>
              <div class="col-md-6">
                <div class="form-group-input">
                  <span class="label-input">Địa điểm học: </span>
                  <el-select
                    v-model="form.class_room"
                    placeholder="Địa điểm học"
                  >
                    <el-option
                      v-for="classRoom in classRooms"
                      :key="classRoom.id"
                      :value="classRoom.id"
                      :label="classRoom.name"
                    ></el-option>
                  </el-select>
                </div>
                <div class="form-group-input">
                  <span class="label-input">Học từ: </span>
                  <el-date-picker
                    v-model="form.time_start"
                    type="date"
                    placeholder="Ngày bắt đầu"
                    format="dd/MM/yyyy"
                    :picker-options="pickerBeginDateBefore"
                  ></el-date-picker>
                </div>
                <div class="form-group-input">
                  <span class="label-input">Đến: </span>
                  <el-date-picker
                    v-model="form.time_end"
                    type="date"
                    placeholder="Ngày kết thúc"
                    format="dd/MM/yyyy"
                    :picker-options="pickerBeginDateBefore"
                  ></el-date-picker>
                </div>
                <div class="form-group-input">
                  <span class="label-input">Số học sinh<br />tối đa: </span>
                  <el-input-number
                    placeholder="Số học sinhtối đa"
                    :min="0"
                    v-model="form.max_quantity"
                  ></el-input-number>
                </div>
                <div class="form-group-input">
                  <span class="label-input">Ghi chú: </span>
                  <el-input
                    type="textarea"
                    placeholder="Ghi chú"
                    v-model="form.note"
                  ></el-input>
                </div>
              </div>
              <!-- <div class="col-12">
                <div class="form-group-input">
                  <span class="label-input" style="margin-right: -22px"
                    >Ghi chú:
                  </span>
                  <el-input
                    type="textarea"
                    placeholder="Ghi chú"
                    v-model="form.note"
                  ></el-input>
                </div>
              </div> -->
            </div>
          </template>
        </form-wrapper>
        <div class="form-group-button">
          <el-button
            id="btn_cancel"
            @click="isShowCreate = false"
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
                fixed
              >
              </el-table-column>
              <el-table-column
                label="Lớp học"
                prop="name"
                width="250"
                fixed
                header-align="center"
              >
              </el-table-column>
              <!-- <el-table-column
                label="Cơ sở"
                prop="cosoName"
                width="200"
                v-if="!id_coso"
                header-align="center"
                sortable
              ></el-table-column> -->
              <el-table-column
                label="Giáo viên"
                prop="home_teacher"
                header-align="center"
                width="200"
              >
              </el-table-column>
              <el-table-column
                label="Số lượng"
                prop="max_quantity"
                align="center"
              >
              </el-table-column>
              <el-table-column
                label="Năm học"
                prop="school_year_name"
                width="120"
                align="center"
              >
              </el-table-column>
              <el-table-column
                label="Khối lớp"
                prop="grade_name"
                header-align="center"
                width="150"
              >
              </el-table-column>
              <el-table-column
                label="Địa điểm học"
                prop="class_room_name"
                align="center"
                width="140"
              >
              </el-table-column>
              <el-table-column align="right" fixed="right" width="150">
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
                    @click="handleEdit(scope.$index, scope.row)"
                    v-if="!scope.row.disableBtn"
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
import FormWrapper from "../layouts/form-wrapper.vue";
import FooterQlmn from "../layouts/footer-qlmn.vue";
export default {
  components: { mainModal, FormWrapper, FooterQlmn },
  name: "lop-hoc",
  data() {
    return {
      tableData: [],
      search: "",
      isShowCreateTitle: true,
      school_years: [],
      form: {
        name: "",
        id: "",
        grade: "",
        max_quantity: "",
        school_year: "",
        class_room: "",
        time_start: "",
        time_end: "",
        home_teacher: "",
        note: "",
        giao_vien: "",
        id_coso: "",
      },
      teachers: [],
      nameUpdate: "",
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      grades: [],
      classRooms: [
        {
          id: 1,
          name: "Tầng 1",
        },
        {
          id: 2,
          name: "Tầng 2",
        },
        {
          id: 3,
          name: "Tầng 3",
        },
        {
          id: 4,
          name: "Tầng 4",
        },
      ],
      pickerBeginDateBefore: {
        disabledDate(time) {
          return time.getTime() < Date.now();
        },
      },
      title: "Thêm lớp học",
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
    formatDateInTable(date) {
      if (typeof date != "undefined") {
        var arr = String(date).split("-");
        arr = arr.reverse();
        return arr.join("/");
      }
      return;
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
    formatBeforeSend() {
      this.form.time_start = this.form.time_start
        ? this.formatDate(this.form.time_start)
        : "";
      this.form.time_end = this.form.time_end
        ? this.formatDate(this.form.time_end)
        : "";
    },
    handleEdit(index, row) {
      this.nameUpdate = row.name;
      this.form.name = row.name;
      this.form.id = row.id;
      this.form.school_year = row.school_year ? Number(row.school_year) : "";
      this.form.grade = row.grade ? Number(row.grade) : "";
      this.form.class_room = row.class_room ? Number(row.class_room) : "";
      this.form.time_start = row.time_start;
      this.form.time_end = row.time_end;
      this.form.home_teacher = row.home_teacher_id;
      this.form.max_quantity = row.max_quantity;
      this.form.note = row.note;
      // this.form.id_coso = row.id_coso ? Number(row.id_coso) : null;
      this.isShowCreateTitle = false;
      this.title = `Cập nhật lớp học ${this.nameUpdate}`;
    },
    handleDelete(index, row) {
      let name = row.name;
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      }).then(async () => {
        await axios
          .delete("deleteClass/" + row.id)
          .then((response) => {
            if (response.data.status == true) {
              this.$notify({
                title: "Thông báo",
                message: `Xóa lớp học "${name}" thành công!`,
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
        .get("dataClasses", {
          params: {
            id_coso: this.form.id_coso,
          },
        })
        .then((response) => {
          if (response.data.status == true) {
            this.tableData = response.data.lops;
            for (let i = 0; i < this.tableData.length; i++) {
              this.tableData[i]["index"] = i + 1;
              this.tableData[i]["time_start"] = this.tableData[i]["time_start"]
                ? this.tableData[i]["time_start"]
                : "";
              this.tableData[i]["time_end"] = this.tableData[i]["time_end"]
                ? this.tableData[i]["time_end"]
                : "";
              this.tableData[i]["time_study"] = `${this.formatDateInTable(
                this.tableData[i]["time_start"]
              )} - ${this.formatDateInTable(this.tableData[i]["time_end"])}`;
              this.tableData[i]["school_year_name"] = this.tableData[i][
                "school_year"
              ]
                ? this.school_years.find(
                    (school_year) =>
                      school_year.id == this.tableData[i]["school_year"]
                  ).period
                : "";
              this.tableData[i]["class_room_name"] = this.tableData[i][
                "class_room"
              ]
                ? this.classRooms.find(
                    (item) => item.id == this.tableData[i]["class_room"]
                  ).name
                : "";
              this.tableData[i].disableBtn = this.tableData[i]["school_year"]
                ? this.school_years.find(
                    (school_year) =>
                      school_year.id == this.tableData[i]["school_year"]
                  ).isLocked
                : "";
            }
            this.pagedTableData(1);
          }
          this.loading = false;
        });
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
    handleCreate() {
      if (this.validateForm()) {
        if (this.isShowCreateTitle) {
          if (!this.checkExist()) {
            this.formatBeforeSend();
            return axios
              .post("createClass", this.form)
              .then((response) => {
                if (response.data.status == true) {
                  this.fetchData();
                  this.$notify({
                    title: "Thông báo",
                    message: `Tạo lớp học "${this.form.name}" thành công!`,
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
              message: `Lớp học này đã tồn tại`,
              type: "warning",
            });
          }
        } else {
          this.formatBeforeSend();
          if (!this.checkExist()) {
            return axios
              .post("updateClass", this.form)
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
              message: `Lớp học này đã tồn tại`,
              type: "warning",
            });
          }
        }
      }
      return false;
    },
    validateForm() {
      if (this.form.name == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Tên lớp học không được bỏ trống",
          type: "warning",
        });
        return false;
      }

      if (this.form.grade == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Khối lớp không được bỏ trống",
          type: "warning",
        });
        return false;
      }

      if (this.form.school_year == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Năm học không được bỏ trống",
          type: "warning",
        });
        return false;
      }

      if (this.form.home_teacher == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Giáo viên chủ nhiệm không được bỏ trống",
          type: "warning",
        });
        return false;
      }

      return true;
    },
    checkExist() {
      var checkLop = null;
      checkLop = this.tableData.find(
        (item) =>
          item.name == this.form.name &&
          item.id_coso == this.form.id_coso &&
          item.id !== this.form.id
      );
      return checkLop ? true : false;
    },
    resetForm() {
      this.form.name = "";
      this.form.id = "";
      this.form.grade = "";
      this.form.school_year = "";
      this.form.class_room = "";
      this.form.time_start = "";
      this.form.time_end = "";
      this.form.home_teacher = "";
      this.form.max_quantity = "";
      this.form.note = "";
      this.form.giao_vien = "";
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
    async getAllSchoolYears() {
      await axios
        .get("getSchoolYears")
        .then((promise) => {
          if (promise.data.status) {
            this.school_years = promise.data.school_years;
          }
        })
        .catch((rejected) => {});
    },
    change_time_start(value) {
      this.form.time_start = value;
    },
    change_time_end(value) {},
    async getTeachers() {
      await axios("dataNhanVien")
        .then((promise) => {
          if (promise.data.status) {
            this.teachers = promise.data.nhan_vien;
          }
        })
        .catch();
    },
    async getGrades() {
      await axios("dataGrades")
        .then((promise) => {
          if (promise.data.status) {
            this.grades = promise.data.grades;
          }
        })
        .catch();
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
  created() {
    Promise.all([
      this.getAllSchoolYears(),
      this.getTeachers(),
      this.getGrades(),
    ]).then(() => {
      this.getCosos();
    });
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
        // display: flex;
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
      flex-basis: 150px;
    }
  }
}
</style>