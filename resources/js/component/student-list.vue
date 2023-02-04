<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Danh sách học sinh</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb" class="d-flex">
              <el-button
                type="primary"
                size="small"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                @click="handleShowFormCreate"
                >Tạo mới</el-button
              >
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

    <main-modal widthModal="95%">
      <template>
        <div class="row">
          <div class="col-9">
            <form-wrapper>
              <template v-slot:form-title>Thông tin học sinh</template>
              <template>
                <div class="row">
                  <div class="col-lg-4 col-md-6">
                    <div class="form-group-input">
                      <span
                        class="form-label required"
                        style="margin-right: 25px"
                        >Họ tên:
                      </span>
                      <el-input
                        placeholder="Họ và tên"
                        v-model="form.name"
                      ></el-input>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                    <div class="form-group-input">
                      <span class="form-label">Tên thân mật: </span>
                      <el-input
                        placeholder="Tên thân mật"
                        v-model="form.biet_danh"
                      ></el-input>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                    <div class="form-group-input">
                      <span class="form-label required">Giới tính: </span>
                      <el-select v-model="form.gender" placeholder="Giới tính">
                        <el-option :value="1" label="Nam"></el-option>
                        <el-option :value="0" label="Nữ"></el-option>
                      </el-select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-input">
                      <span class="form-label required">Ngày sinh: </span>
                      <el-date-picker
                        v-model="form.birth_date"
                        type="date"
                        placeholder="Ngày sinh"
                        format="dd/MM/yyyy"
                      ></el-date-picker>
                    </div>
                  </div>
                  <div class="col-md-6 col-12">
                    <div class="form-group-input">
                      <span class="form-label">Diện ưu tiên:</span>
                      <el-select
                        filterable
                        v-model="form.doi_tuong_uu_tien"
                        placeholder="Diện ưu tiên"
                      >
                        <el-option
                          v-for="item in doituongs"
                          :key="item.id"
                          :value="item.id"
                          :label="item.name"
                        ></el-option>
                      </el-select>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group-input">
                      <span
                        class="form-label required"
                        style="margin-right: 25px"
                        >Địa chỉ:
                      </span>
                      <el-input
                        type="input"
                        v-model="form.address"
                        placeholder="Địa chỉ"
                      ></el-input>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-input">
                      <span class="form-label" style="margin-right: 29px"
                        >Dân tộc:
                      </span>
                      <div
                        class="
                          d-flex
                          align-items-center
                          justify-content-between
                          w-100
                        "
                      >
                        <el-select
                          filterable
                          v-model="form.ethnicity"
                          placeholder="Dân tộc"
                        >
                          <el-option
                            v-for="item in ethnicities"
                            :key="item.id"
                            :value="item.id"
                            :label="item.name"
                          ></el-option>
                        </el-select>
                        <el-button
                          class="ml-2 d-inline-block"
                          type="success"
                          size="small"
                          @click="openSubForm('Thêm dân tộc')"
                          data-bs-toggle="modal"
                          data-bs-target="#subModal"
                          >+</el-button
                        >
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-12">
                    <div class="form-group-input">
                      <span class="form-label" style="margin-right: 10px"
                        >Lớp hiện tại</span
                      >
                      <el-select
                        filterable
                        v-model="form.id_class"
                        placeholder="Chọn lớp"
                      >
                        <el-option
                          v-for="item in lops_form"
                          :key="item.id"
                          :value="item.id"
                          :label="item.name"
                        ></el-option>
                      </el-select>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group-input">
                      <span class="form-label" style="margin-right: 14px"
                        >Thói quen:
                      </span>
                      <el-input
                        type="input"
                        v-model="form.character"
                        placeholder="Tính cách, thói quen"
                      ></el-input>
                    </div>
                  </div>
                </div>
              </template>
            </form-wrapper>
          </div>
          <div class="col-3">
            <form-wrapper>
              <template v-slot:form-title>Hình ảnh</template>
              <template>
                <div class="col-12">
                  <div
                    class="
                      form-group-input
                      upload-image-student-form
                      d-flex
                      justify-content-center
                    "
                  >
                    <el-upload
                      :auto-upload="false"
                      action=""
                      :on-change="handleChangeImage"
                      :on-remove="deleteAttachment"
                      :limit="1"
                      list-type="picture"
                      style="width: 80%"
                      :file-list="form.fileLists.imgList"
                    >
                      <div
                        class="w-100 avatar-uploader-icon"
                        style="
                          aspect-ratio: 1;
                          align-items: center;
                          display: flex;
                          justify-content: center;
                          border-radius: 10px;
                          color: #ccc;
                          border: 3px dashed #d9d9d9;
                        "
                        v-show="
                          !form.image.name && form.fileLists.imgList.length == 0
                        "
                      >
                        <span>+</span>
                      </div>
                    </el-upload>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group text-center">
                    <span class="form-label"
                      >Mã số (ID): <b>{{ form.id }}</b>
                    </span>
                  </div>
                </div>
              </template>
            </form-wrapper>
          </div>
          <div class="col-9">
            <form-wrapper>
              <template v-slot:form-title>Thông tin phụ huynh</template>
              <template>
                <div class="row">
                  <div class="col-md-6 col-lg-4">
                    <div class="form-group-input">
                      <span class="form-label" style="margin-right: 14px"
                        >Họ tên cha:
                      </span>
                      <el-input
                        placeholder="Họ và tên cha"
                        v-model="form.parent_name"
                      ></el-input>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <div class="form-group-input">
                      <span class="form-label">Năm sinh: </span>
                      <el-input-number
                        placeholder="Năm sinh"
                        v-model="form.parent_age"
                        :min="1970"
                      ></el-input-number>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <div class="form-group-input">
                      <span class="form-label">Nghề nghiệp: </span>
                      <div
                        class="
                          d-flex
                          align-items-center
                          justify-content-between
                          w-100
                        "
                      >
                        <el-select filterable v-model="form.parent_job">
                          <el-option
                            v-for="item in jobs"
                            :key="item.id"
                            :value="item.id"
                            :label="item.name"
                            placeholder="Nghề nghiệp"
                          ></el-option>
                        </el-select>
                        <el-button
                          class="ml-2 d-inline-block"
                          type="success"
                          size="small"
                          @click="openSubForm('Thêm nghề nghiệp')"
                          data-bs-toggle="modal"
                          data-bs-target="#subModal"
                          >+</el-button
                        >
                      </div>
                    </div>
                  </div>
                  <div></div>
                  <div class="col-md-6 col-lg-4">
                    <div class="form-group-input">
                      <span class="form-label" style="margin-right: 14px"
                        >Họ tên mẹ:
                      </span>
                      <el-input
                        placeholder="Họ và tên mẹ"
                        v-model="form.mother_name"
                      ></el-input>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <div class="form-group-input">
                      <span class="form-label">Năm sinh: </span>
                      <el-input-number
                        placeholder="Năm sinh"
                        v-model="form.mother_age"
                        :min="1970"
                      ></el-input-number>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <div class="form-group-input">
                      <span class="form-label">Nghề nghiệp: </span>
                      <div
                        class="
                          d-flex
                          align-items-center
                          justify-content-between
                          w-100
                        "
                      >
                        <el-select
                          filterable
                          v-model="form.mother_job"
                          placeholder="Nghề nghiệp"
                        >
                          <el-option
                            v-for="item in jobs"
                            :key="item.id"
                            :value="item.id"
                            :label="item.name"
                          ></el-option>
                        </el-select>
                        <el-button
                          class="ml-2 d-inline-block"
                          type="success"
                          size="small"
                          @click="openSubForm('Thêm nghề nghiệp')"
                          data-bs-toggle="modal"
                          data-bs-target="#subModal"
                          >+</el-button
                        >
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="form-group-input">
                      <span class="form-label required">Điện thoại: </span>
                      <el-input
                        placeholder="Số điện thoại của cha học sinh"
                        v-model="form.parent_phone"
                      ></el-input>
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="form-group-input">
                      <span class="form-label">Email: </span>
                      <el-input
                        placeholder="Email"
                        v-model="form.parent_email"
                      ></el-input>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group-input">
                      <span class="form-label" style="margin-right: 29px"
                        >Ghi chú:
                      </span>
                      <el-input
                        type="input"
                        v-model="form.parents_note"
                        placeholder="Ghi chú của phụ huynh"
                      ></el-input>
                    </div>
                  </div>
                </div>
              </template>
            </form-wrapper>
          </div>
          <div class="col-3">
            <form-wrapper class="status_student">
              <template v-slot:form-title>Trạng thái học sinh</template>
              <template>
                <div class="col-12">
                  <div class="row">
                    <div class="col-lg-12 d-flex align-items-center">
                      <div class="form-group-input">
                        <!-- <span class="form-label">Ngày vào học: </span> -->
                        <el-date-picker
                          v-model="form.ngay_vao_hoc"
                          type="date"
                          placeholder="Ngày vào học"
                          format="dd/MM/yyyy"
                        >
                        </el-date-picker>
                      </div>
                    </div>

                    <div class="col-lg-12 d-flex align-items-center">
                      <div class="form-group">
                        <el-checkbox v-model="form.isAbsent"
                          >Đã nghỉ học</el-checkbox
                        >
                      </div>
                    </div>
                    <div class="col-lg-12 d-flex align-items-center">
                      <div class="form-group-input">
                        <!-- <span class="form-label">Ngày nghỉ học: </span> -->
                        <el-date-picker
                          v-model="form.ngay_nghi_hoc"
                          type="date"
                          placeholder="Ngày nghỉ học"
                          format="dd/MM/yyyy"
                        >
                        </el-date-picker>
                      </div>
                    </div>
                    <div class="col-lg-12 d-flex align-items-center">
                      <div class="form-group">
                        <el-checkbox v-model="form.baoLuu"
                          >Bảo lưu học tập</el-checkbox
                        >
                      </div>
                    </div>
                    <div class="col-lg-12 d-flex align-items-center">
                      <div class="form-group-input">
                        <!-- <span class="form-label">Ngày bảo lưu: </span> -->
                        <el-date-picker
                          v-model="form.ngay_bao_luu"
                          type="date"
                          placeholder="Ngày bảo lưu"
                          format="dd/MM/yyyy"
                        >
                        </el-date-picker>
                      </div>
                    </div>

                    <div class="col-lg-12 d-flex align-items-center my-2">
                      <el-button
                        size="small"
                        type="primary"
                        @click="handleBaoLuu"
                        >Thực hiện bảo lưu</el-button
                      >
                    </div>
                  </div>
                </div>
              </template>
            </form-wrapper>
          </div>
        </div>
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
    <sub-modal :title="titleSubModal">
      <template>
        <div
          class="form-group-input d-flex align-items-center"
          v-if="titleSubModal == 'Thêm dân tộc'"
        >
          <span
            class="form-label mb-0"
            style="margin-right: 25px; white-space: nowrap"
            >Dân tộc:
          </span>
          <el-input
            placeholder="Dân tộc"
            v-model="ethnicity_add.name"
          ></el-input>
        </div>
        <div
          class="form-group-input d-flex align-items-center"
          v-if="titleSubModal == 'Thêm nghề nghiệp'"
        >
          <span
            class="form-label mb-0"
            style="margin-right: 25px; white-space: nowrap"
            >Nghề nghiệp:
          </span>
          <el-input placeholder="Nghề nghiệp" v-model="job_add.name"></el-input>
        </div>
        <div class="form-group d-flex justify-content-end mt-2">
          <el-button
            data-bs-dismiss="modal"
            ref="btn_cancel_sub_modal"
            v-show="false"
            >Hủy</el-button
          >
          <el-button type="primary" size="small" @click="saveSubModal()"
            >Lưu</el-button
          >
        </div>
      </template>
    </sub-modal>
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
          <div class="card card_search">
            <!-- <div class="card-body">
              <h5 class="card-title mb-0">Tìm kiếm</h5>
            </div> -->
            <div class="row">
              <div class="col-lg-3 col-sm-6 col-12" v-if="!id_coso">
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
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group-input d-block d-lg-flex">
                  <span class="label-input">Năm học: </span>
                  <el-select
                    v-model="filter.school_year"
                    placeholder="Năm học"
                    filterable
                  >
                    <el-option
                      v-for="item in school_years"
                      :key="item.id"
                      :value="item.id"
                      :label="item.period"
                    ></el-option>
                  </el-select>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group-input d-block d-lg-flex">
                  <span class="label-input">Khối lớp: </span>
                  <el-select
                    v-model="filter.grade"
                    placeholder="Khối lớp"
                    filterable
                  >
                    <el-option
                      v-for="item in grades"
                      :key="item.id"
                      :value="item.id"
                      :label="item.name"
                    ></el-option>
                  </el-select>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group-input d-block d-lg-flex">
                  <span class="label-input">Lớp: </span>
                  <el-select
                    v-model="filter.class"
                    placeholder="Lớp"
                    filterable
                  >
                    <el-option
                      v-for="item in classes"
                      :key="item.id"
                      :value="item.id"
                      :label="item.name"
                    ></el-option>
                  </el-select>
                </div>
              </div>
            </div>
          </div>

          <div class="wrap-table">
            <el-table
              :data="dataFilter"
              empty-text="Không có dữ liệu"
              style="width: 100%"
              :key="rerender"
            >
              <el-table-column align="center" width="50" fixed>
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
              <el-table-column
                label="MHS"
                prop="id"
                align="center"
                width="100"
                fixed
                sortable
              >
              </el-table-column>
              <el-table-column
                label="Học sinh"
                prop="name"
                fixed
                width="200"
                header-align="center"
                sortable
              >
              </el-table-column>
              <el-table-column
                label="Năm sinh"
                prop="birt_year"
                width="120"
                align="center"
                sortable
              >
              </el-table-column>
              <el-table-column
                label="Giới tính"
                prop="gender"
                width="80"
                align="center"
              >
              </el-table-column>
              <el-table-column
                label="Phụ huynh"
                prop="parent_name"
                width="200"
                header-align="center"
              >
              </el-table-column>
              <el-table-column
                label="Điện thoại"
                prop="parent_phone"
                width="120"
                align="center"
              >
              </el-table-column>
              <el-table-column
                label="Địa chỉ"
                prop="address"
                width="200"
                header-align="center"
              >
              </el-table-column>

              <el-table-column
                align="right"
                header-align="center"
                width="150"
                fixed="right"
              >
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
                    @click="editStudent(scope.row.id)"
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
                </template></el-table-column
              ></el-table
            >
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
    </div>
    <footerQLMN></footerQLMN>
  </div>
</template>

<script>
import axios from "axios";
import { errorMessage } from "../errors/error-code";
import { formatString } from "../functions/formatFunctions";
import footerQLMN from "../layouts/footer-qlmn.vue";
import MainModal from "../layouts/main-modal.vue";
import SubModal from "../layouts/sub-modal.vue";
import FormWrapper from "../layouts/form-wrapper.vue";
export default {
  name: "student-list",
  components: {
    footerQLMN,
    MainModal,
    FormWrapper,
    SubModal,
  },
  data() {
    return {
      tableData: [],
      search: "",
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,

      arraySelectedRecord: [],
      resetCheckbox: 0,
      filter: {
        school_year: "",
        grade: "",
        class: "",
      },
      school_years: [],
      grades: [],
      classes: [],
      rerender: 1,

      // Làm phần form
      form: {
        id: "",
        image: {
          name: "",
          content: "",
        },
        name: "",
        biet_danh: "",
        gender: 1,
        birth_date: "",
        doi_tuong_uu_tien: "",
        address: "",
        id_class: "",
        ethnicity: "",
        character: "",
        parent_name: "",
        parent_age: "",
        parent_job: "",
        parent_phone: "",
        parent_email: "",
        mother_name: "",
        mother_age: "",
        mother_job: "",
        parents_note: "",
        fileLists: {
          imgList: [],
        },
        imageUrl: "",
        isAbsent: false,
        baoLuu: false,
        ngay_vao_hoc: "",
        ngay_nghi_hoc: "",
        ngay_bao_luu: "",
        id_coso: null,
      },
      doituongs: [],
      ethnicities: [],
      jobs: [],
      lops: [],
      lops_form: [],
      edit: false,
      titleSubModal: "",
      job_add: {
        name: "",
      },
      ethnicity_add: {
        name: "",
      },
      cosos: [],
      id_coso: null,
    };
  },
  methods: {
    // Method upload image
    handleChangeImage(file) {
      const isCorrectType =
        file.name.endsWith(".png") ||
        file.name.endsWith(".jpg") ||
        file.name.endsWith(".jpeg");
      const isCorrectSize = file.size / 1024 / 1024 < 20;
      if (!isCorrectType) {
        alert("Hình ảnh không hợp lệ");
        this.form.fileLists.imgList = [];
        return false;
      }

      if (!isCorrectSize) {
        alert("Vui lòng chọn file có kích thước nhỏ hơn 20MB");
        this.form.fileLists.imgList = [];
        return false;
      }

      this.form.image.name = file.name;

      let reader = new FileReader();
      reader.readAsDataURL(file.raw);
      reader.onload = (e) => {
        this.form.image.content = e.target.result;
      };
    },
    deleteAttachment() {
      this.form.image.name = "";
      this.form.image.content = "";
      this.form.fileLists.imgList = [];
    },
    // End method upload image

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
    },
    setPage(val) {
      this.page = val;
    },
    // handleEdit(id) {
    //   window.location.pathname = `hoc-sinh/${id}`;
    // },
    handleDelete(index, row) {
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      })
        .then(async () => {
          await axios
            .post("deleteStudent", { array: [row.id] })
            .then((response) => {
              if (response.data.status == true) {
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa học sinh "${row.name}" thành công!`,
                  type: "success",
                });
                this.getStudentsByIdClass();
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
    },
    async handleDeleteAll() {
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      })
        .then(async () => {
          await axios
            .post("deleteStudent", { array: this.arraySelectedRecord })
            .then((response) => {
              if (response.data.status == true) {
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa hồ sơ học sinh thành công!`,
                  type: "success",
                });
                this.getStudentsByIdClass();
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

    // LÀM PHẦN DANH SÁCH HỌC SINH
    async getAllSchoolYears() {
      await axios
        .get("/getSchoolYears")
        .then((promise) => {
          if (promise.data.status) {
            this.school_years = promise.data.school_years;
            let d = new Date();
            if (d.getMonth() + 1 < 6) {
              this.filter.school_year = this.school_years.find(
                (item) => item.name == d.getFullYear() - 1
              ).id;
            } else {
              this.filter.school_year = this.school_years.find(
                (item) => item.name == d.getFullYear()
              ).id;
            }
          }
        })
        .catch((rejected) => {});
    },
    async getGrades() {
      await axios("/dataGrades")
        .then((promise) => {
          if (promise.data.status) {
            this.grades = promise.data.grades;
            this.filter.grade = this.grades[0].id;
          }
        })
        .catch((error) => {
          this.$notify({
            title: "Thông báo",
            message: errorMessage[error.response.status],
            type: "warning",
          });
        });
    },
    async getClasses() {
      this.filter.class = "";
      try {
        let result = await axios.get(
          `/dataClasses/${this.filter.school_year}/${this.filter.grade}`,
          {
            params: {
              id_coso: this.form.id_coso,
            },
          }
        );
        if (result.data.status) {
          this.classes = result.data.items;
        }
      } catch (err) {
        console.log(err);
      }
    },
    // lấy các thông tin liên quan
    async getJobs() {
      await axios.get("/dataJobs").then((response) => {
        if (response.data.status) {
          this.jobs = response.data.jobs;
        }
      });
    },
    async getDoituongs() {
      await axios
        .get("/dataDoituongs")
        .then((response) => {
          if (response.data.status) {
            this.doituongs = response.data.doituongs;
          }
        })
        .catch();
    },
    async getEthnicities() {
      await axios
        .get("/dataEthnicities")
        .then((response) => {
          if (response.data.status) {
            this.ethnicities = response.data.ethnicities;
          }
        })
        .catch((rejected) => console.log(rejected));
    },
    async getLopsForForm() {
      let result = await axios.get("/dataClasses", {
        params: {
          id_coso: this.form.id_coso,
        },
      });
      this.lops_form = result.data.lops;
    },
    async getLops() {
      let result = await axios.get("/dataClasses");
      this.lops = result.data.lops;
    },
    // end lấy các thông tin liên quan
    getStudentsByIdClass() {
      axios
        .get("/getStudentsByIdClass", {
          params: {
            id_class: this.filter.class,
          },
        })
        .then((response) => {
          if (response.data.status) {
            this.tableData = response.data.items;
            this.tableData = this.tableData.map((item, index) => {
              item = {
                ...item,
                checked: false,
                index: index + 1,
                gender: item.gender ? "Nam" : "Nữ",
                birt_year: new Date(item.birth_date).getFullYear(),
              };
              return item;
            });
            this.pagedTableData(1);
          }
        })
        .catch((error) => {
          this.$notify({
            title: "Thông báo",
            message: error,
            type: "warning",
          });
        });
    },

    // LÀM PHẦN FORM HỌC SINH
    handleShowFormCreate() {
      this.isShowCreate = true;
      this.isShowCreateTitle = true;
      this.isErrorForm = false;
      this.resetForm();
    },
    handleBaoLuu() {},
    async handleSubmit() {
      if (this.validateForm()) {
        var checkExist = await this.checkExist();
        if (!checkExist) {
          return axios.post("/submitStudent", this.form).then((response) => {
            if (response.data.status) {
              this.getStudentsByIdClass();
              this.$notify({
                title: "Thông báo",
                message: !this.edit
                  ? `Thêm mới học sinh "${this.form.name}" thành công!`
                  : `Cập nhật thông tin học sinh "${this.form.name}" thành công!`,
                type: "success",
              });
              return true;
            }
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
    formatBeforeSend() {
      this.form.birth_date = this.formatDate(this.form.birth_date);
      if (this.form.ngay_vao_hoc) {
        this.form.ngay_vao_hoc = this.formatDate(this.form.ngay_vao_hoc);
      }
      if (this.form.ngay_nghi_hoc) {
        this.form.ngay_nghi_hoc = this.formatDate(this.form.ngay_nghi_hoc);
      }
      if (this.form.ngay_bao_luu) {
        this.form.ngay_bao_luu = this.formatDate(this.form.ngay_bao_luu);
      }
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
    async checkExist() {
      this.exist = false;
      this.formatBeforeSend();
      this.exist = await axios
        .post("/checkExistRecord", this.form)
        .then((response) => {
          if (response.data.status) {
            if (response.data.exist) {
              // alert("Hồ sơ này đã tồn tại, vui lòng kiểm tra lại thông tin");
              this.$notify({
                title: "Thông báo",
                message: "Học sinh này đã tồn tại",
                type: "warning",
              });
              return true;
            }
            return false;
          }
        })
        .catch();
      return this.exist;
    },
    validateForm() {
      var namePattern = /([A-Za-z])/g;
      var stringPhonePattern = /((09|03|07|08|05)+([0-9]{8})\b)/g;
      // console.log(this.form);
      if (this.form.name.length < 3) {
        this.$notify({
          title: "Cảnh báo",
          message: "Tên học sinh phải có ít nhất 3 ký tự trở lên",
          type: "warning",
        });
        return false;
      } else if (!namePattern.test(this.form.name)) {
        this.$notify({
          title: "Cảnh báo",
          message: "Tên học sinh không hợp lệ",
          type: "warning",
        });
        return false;
      }
      if (this.form.gender === "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Giới tính của học sinh không được để trống",
          type: "warning",
        });
        return false;
      }
      if (this.form.birth_date == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Ngày sinh của học sinh không được để trống",
          type: "warning",
        });
        return false;
      }
      if (this.form.address == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Địa chỉ của học sinh không được để trống",
          type: "warning",
        });
        return false;
      }

      if (this.form.parent_phone != "" && this.form.parent_phone != null) {
        if (
          !String(this.form.parent_phone).match(
            /(84|0[3|5|7|8|9])+([0-9]{8})\b/g
          )
        ) {
          this.$notify({
            title: "Cảnh báo",
            message: "Số điện thoại không đúng định dạng",
            type: "warning",
          });
          return false;
        }
      }

      if (this.form.parent_email != "" && this.form.parent_email != null) {
        if (
          !String(this.form.parent_email).match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
          )
        ) {
          this.$notify({
            title: "Cảnh báo",
            message: "Email không đúng định dạng",
            type: "warning",
          });
          return false;
        }
      }
      return true;
    },
    findById(arr, id) {
      var result = null;
      if (id) {
        result = arr.find((item) => item.id == id);
      }
      return result ? result.name : "";
    },
    async mhsAutocomplete() {
      await axios.get("/getLastMHS").then((response) => {
        if (response.data.status == true) {
          this.form.id = response.data.lastMHS;
        }
      });
    },
    resetForm() {
      this.mhsAutocomplete();
      this.edit = false;
      this.image = {
        name: "",
        content: "",
      };
      this.form.name = "";
      this.form.biet_danh = "";
      this.form.gender = 1;
      this.form.birth_date = "";
      this.form.address = "";
      this.form.doi_tuong_uu_tien = "";
      this.form.id_class = "";
      (this.form.ethnicity = 1),
        (this.form.character = ""),
        (this.form.parent_name = "");
      this.form.parent_job = "";
      this.form.parent_phone = "";
      this.form.parent_age = "";
      (this.form.parent_email = ""), (this.form.mother_name = "");
      (this.form.mother_age = ""), (this.form.mother_job = "");
      this.form.parents_note = "";
      this.form.isAbsent = false;
      this.form.baoLuu = false;
      this.form.ngay_vao_hoc = "";
      this.form.ngay_nghi_hoc = "";
      this.form.ngay_bao_luu = "";
      this.titleMessage = "";
      this.isErrorForm = false;
      this.form.fileLists.imgList = [];
      this.form.image = {
        name: "",
        content: "",
      };
    },
    editStudent(id) {
      this.edit = true;
      axios
        .get("/getRecordById/" + id)
        .then(async (response) => {
          if (response.data.status) {
            this.form.id_coso = response.data.record.id_coso
              ? Number(response.data.record.id_coso)
              : null;
            // await this.getLopsForForm();
            this.form.baoLuu = false;
            this.form.isAbsent = false;
            this.form.id = response.data.record.id;
            this.form.name = response.data.record.name;
            this.form.biet_danh = response.data.record.biet_danh;
            this.form.birth_date = response.data.record.birth_date;
            this.form.gender = Number(response.data.record.gender);
            this.form.address = response.data.record.address;

            this.form.doi_tuong_uu_tien = response.data.record.doi_tuong_uu_tien
              ? Number(response.data.record.doi_tuong_uu_tien)
              : "";
            this.form.ethnicity = response.data.record.ethnicity
              ? Number(response.data.record.ethnicity)
              : "";
            this.form.id_class = response.data.record.id_class
              ? Number(response.data.record.id_class)
              : "";
            this.form.character = response.data.record.character;
            this.form.parent_name = response.data.record.parent_name;
            this.form.parent_age = response.data.record.parent_age;
            this.form.parent_job = response.data.record.parent_job
              ? Number(response.data.record.parent_job)
              : "";
            this.form.parent_phone = response.data.record.parent_phone;
            this.form.parent_email = response.data.record.parent_email;
            this.form.mother_name = response.data.record.mother_name;
            this.form.mother_age = response.data.record.mother_age;
            this.form.mother_job = response.data.record.mother_job
              ? Number(response.data.record.mother_job)
              : "";
            this.form.parents_note = response.data.record.parents_note;
            if (response.data.record.image) {
              this.form.fileLists.imgList = [
                {
                  name: response.data.record.image,
                  url: response.data.record.urlImage,
                },
              ];
            }
            this.form.ngay_vao_hoc = response.data.record.ngay_vao_hoc;
            this.form.ngay_nghi_hoc = "";
            this.form.ngay_bao_luu = "";
            if (response.data.record.status == 2) {
              this.form.baoLuu = true;
              this.form.ngay_bao_luu = response.data.record.ngay_bao_luu;
            } else if (response.data.record.status == 3) {
              this.form.isAbsent = true;
              this.form.ngay_nghi_hoc = response.data.record.ngay_nghi_hoc;
              this.form.ngay_bao_luu = response.data.record.ngay_bao_luu;
              if (this.form.ngay_bao_luu != "") {
                this.form.baoLuu = true;
              }
            }
          }
        })
        .catch();
    },
    // LÀM PHẦN FORM PHỤ
    openSubForm(title) {
      this.titleSubModal = title;
    },
    // Job's method
    handleAddJob() {
      if (this.validateFormAddJob()) {
        if (!this.checkJobExist()) {
          return axios
            .post("/addJob", this.job_add)
            .then((response) => {
              if (response.data.status) {
                this.getJobs();
                this.$notify({
                  title: "Thông báo",
                  message: `Thêm nghề nghiệp "${this.job_add.name}" thành công!`,
                  type: "success",
                });
                this.resetFormAddJob();
                return true;
              }
            })
            .catch();
        } else {
          this.$notify({
            title: "Cảnh báo",
            message: "Nghề nghiệp đã tồn tại",
            type: "warning",
          });
        }
      }
      return false;
    },
    validateFormAddJob() {
      if (this.job_add.name == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Tên nghề nghệp không được để trống",
          type: "warning",
        });
        return false;
      }
      return true;
    },
    checkJobExist() {
      var checkName = null;
      checkName = this.jobs.find((job) => job.name == this.job_add.name);
      return checkName ? true : false;
    },
    resetFormAddJob() {
      this.job_add.name = "";
    },
    // end Job's method

    // Ethnicity's method
    handleEthnicityJob() {
      if (this.validateFormEthnicityJob()) {
        if (!this.checkEthnicityExist()) {
          return axios
            .post("/addEthnicity", this.ethnicity_add)
            .then((response) => {
              if (response.data.status) {
                this.getEthnicities();
                this.$notify({
                  title: "Thông báo",
                  message: `Thêm dân tộc "${this.ethnicity_add.name}" thành công!`,
                  type: "success",
                });
                this.resetFormEthnicityJob();
                return true;
              }
            })
            .catch();
        } else {
          this.$notify({
            title: "Cảnh báo",
            message: "Dân tộc đã tồn tại",
            type: "warning",
          });
        }
      }
      return false;
    },
    validateFormEthnicityJob() {
      if (this.ethnicity_add.name == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Tên dân tộc không được để trống",
          type: "warning",
        });
        return false;
      }
      return true;
    },
    checkEthnicityExist() {
      var checkName = null;
      checkName = this.ethnicities.find(
        (ethnicity) => ethnicity.name == this.ethnicity_add.name
      );
      return checkName ? true : false;
    },
    resetFormEthnicityJob() {
      this.ethnicity_add.name = "";
    },
    async saveSubModal() {
      let result = "";
      if (this.titleSubModal == "Thêm dân tộc") {
        result = await this.handleEthnicityJob();
      }
      if (this.titleSubModal == "Thêm nghề nghiệp") {
        result = await this.handleAddJob();
      }
      if (result) {
        this.$refs.btn_cancel_sub_modal.$el.click();
      }
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
      this.getCosos(),
      this.getJobs(),
      this.getEthnicities(),
      this.getGrades(),
      this.getDoituongs(),
      this.getAllSchoolYears(),
    ]).then(async () => {
      this.mhsAutocomplete();
      this.getLopsForForm(), await this.getClasses();
      this.filter.class = this.classes.length ? this.classes[0].id : "";
    });
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
    "filter.school_year": function () {
      this.classes = [];
      if (
        this.filter.school_year !== "" &&
        this.filter.grade !== "" &&
        this.form.id_coso != ""
      ) {
        this.getClasses();
      }
    },
    "filter.grade": function () {
      this.classes = [];
      if (
        this.filter.school_year !== "" &&
        this.filter.grade !== "" &&
        this.form.id_coso != ""
      ) {
        this.getClasses();
      }
    },
    "filter.class": function () {
      if (this.filter.class) {
        this.getStudentsByIdClass();
      } else {
        this.dataFilter = [];
      }
    },
    "form.id_coso": function () {
      this.form.id_class = "";
      this.getLopsForForm();
      if (
        this.filter.school_year !== "" &&
        this.filter.grade !== "" &&
        this.form.id_coso != ""
      ) {
        this.getClasses();
      }
      // this.getLopsForForm();
    },
  },
};
</script>

<style scoped>
.avatar-uploader .avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>

<style>
.avatar-uploader .el-upload {
  /* border: 1px dashed #d9d9d9; */
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: var(--el-transition-duration-fast);
}

.avatar-uploader .el-upload:hover {
  border-color: var(--el-color-primary);
}

.el-icon.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  text-align: center;
}
</style>

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

.status_student {
  .form-group-input {
    padding-bottom: 5px;
  }
  .form-group {
    margin-bottom: -7px;
  }
}

.form-group-button {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding: 10px 0;
  margin-top: -20px;
}

.card_search {
  border-radius: 8px;
  box-shadow: 0 0 6px #ccc;
  .form-group-input {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px 15px;
    .label-input {
      flex-basis: 100px;
    }
  }
}
</style>
<style lang="scss">
.el-upload--picture {
  width: 100% !important;
  font-size: 60px;
}
.upload-image-student-form {
  // display: block !important;
  .el-upload-list--picture {
    // width: auto;
    // // height: auto;
    // aspect-ratio: 3/4;
    // border: 1px solid #c0ccda;
    // border-radius: 6px;
    // margin-top: 15px;
    .el-upload-list__item {
      // height: auto !important;
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
</style>