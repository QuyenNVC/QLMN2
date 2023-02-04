<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4>Danh sách học sinh nhập học</h4>
          <div class="ms-auto text-end">
            <nav class="breadcrumb">
              <el-button type="success" size="small" @click="chiaLopTuDong()"
                >Chia lớp</el-button
              >
              <el-button
                type="primary"
                size="small"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                @click="handleShowFormCreate"
                >Thêm mới</el-button
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
              <template v-slot:form-title>Thêm mới học sinh</template>
              <template>
                <div class="row">
                  <div class="col-lg-4 col-md-6">
                    <div class="form-group-input">
                      <span
                        class="form-label required"
                        style="margin-right: 40px"
                        >Họ tên:
                      </span>
                      <el-input
                        placeholder="Họ và tên"
                        v-model="form.name"
                        :disabled="isDisabled"
                      ></el-input>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                    <div class="form-group-input">
                      <span class="form-label">Biệt danh: </span>
                      <el-input
                        placeholder="Biệt danh"
                        v-model="form.biet_danh"
                        :disabled="isDisabled"
                      ></el-input>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                    <div class="form-group-input">
                      <span
                        class="form-label required"
                        style="margin-right: 12px"
                        >Giới tính:
                      </span>
                      <el-select
                        v-model="form.gender"
                        placeholder="Giới tính"
                        :disabled="isDisabled"
                      >
                        <el-option :value="1" label="Nam"></el-option>
                        <el-option :value="0" label="Nữ"></el-option>
                      </el-select>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <div class="form-group-input">
                      <span
                        class="form-label required"
                        style="margin-right: 18px"
                        >Ngày sinh:
                      </span>
                      <el-date-picker
                        v-model="form.birth_date"
                        type="date"
                        placeholder="Ngày sinh"
                        format="dd/MM/yyyy"
                        :disabled="isDisabled"
                      ></el-date-picker>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <div class="form-group-input">
                      <span class="form-label">Chiều cao: </span>
                      <el-input-number
                        placeholder="Chiều cao"
                        v-model="form.height"
                        :disabled="isDisabled"
                      ></el-input-number>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <div class="form-group-input">
                      <span class="form-label">Cân nặng: </span>
                      <el-input-number
                        placeholder="Cân nặng"
                        v-model="form.weigth"
                        :disabled="isDisabled"
                      ></el-input-number>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group-input">
                      <span
                        class="form-label required"
                        style="margin-right: 40px"
                        >Địa chỉ:
                      </span>
                      <el-input
                        type="input"
                        v-model="form.address"
                        placeholder="Địa chỉ"
                        :disabled="isDisabled"
                      ></el-input>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-input">
                      <span class="form-label" style="margin-right: 42px"
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
                          :disabled="isDisabled"
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
                      <span class="form-label">Tiền sử bệnh</span>
                      <el-input
                        type="input"
                        v-model="form.medical_history"
                        placeholder="Tiền sử bệnh"
                        :disabled="isDisabled"
                      ></el-input>
                    </div>
                  </div>
                </div>
              </template>
            </form-wrapper>
            <form-wrapper>
              <template v-slot:form-title>Thông tin phụ huynh</template>
              <template>
                <div class="row">
                  <div class="col-md-6 col-lg-4">
                    <div class="form-group-input">
                      <span class="form-label" style="margin-right: 18px"
                        >Họ tên cha:
                      </span>
                      <el-input
                        placeholder="Họ và tên cha"
                        v-model="form.parent_name"
                        :disabled="isDisabled"
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
                        :disabled="isDisabled"
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
                          v-model="form.parent_job"
                          :disabled="isDisabled"
                        >
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
                      <span class="form-label" style="margin-right: 18px"
                        >Họ tên mẹ:
                      </span>
                      <el-input
                        placeholder="Họ và tên mẹ"
                        v-model="form.mother_name"
                        :disabled="isDisabled"
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
                        :disabled="isDisabled"
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
                          :disabled="isDisabled"
                        >
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
                  <div class="col-12 col-md-6">
                    <div class="form-group-input">
                      <span class="form-label" style="margin-right: 18px"
                        >Điện thoại:
                      </span>
                      <el-input
                        placeholder="Số điện thoại của phụ huynh học sinh"
                        v-model="form.parent_phone"
                        :disabled="isDisabled"
                      ></el-input>
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="form-group-input">
                      <span class="form-label">Email: </span>
                      <el-input
                        placeholder="Email"
                        v-model="form.parent_email"
                        :disabled="isDisabled"
                      ></el-input>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group-input">
                      <span class="form-label" style="margin-right: 35px"
                        >Ghi chú:
                      </span>
                      <el-input
                        type="input"
                        v-model="form.parents_note"
                        placeholder="Ghi chú của phụ huynh"
                        :disabled="isDisabled"
                      ></el-input>
                    </div>
                  </div>
                </div>
              </template>
            </form-wrapper>
            <form-wrapper>
              <template v-slot:form-title>Thông tin bổ sung</template>
              <template>
                <div class="row additional_information">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group-input">
                        <span class="form-label">Mối quan hệ: </span>
                        <el-select
                          v-model="form.type_relate"
                          placeholder="Loại mối quan hệ"
                          :disabled="isDisabled"
                        >
                          <el-option :value="1" label="Anh/Chị"></el-option>
                          <el-option :value="0" label="Em"></el-option>
                        </el-select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-input">
                        <span class="form-label">Anh/Chị/Em ruột: </span>
                        <!-- <multiselect
                          v-model="form.siblings"
                          placeholder="Anh/Chị/Em ruột"
                          label="name"
                          track-by="id"
                          :options="siblings"
                          :multiple="true"
                          :taggable="true"
                          :disabled="isDisabled"
                        ></multiselect> -->
                        <el-select
                          filterable
                          v-model="form.siblings"
                          placeholder="Anh/Chị/Em ruột"
                          :disabled="isDisabled"
                          multiple=""
                        >
                          <el-option
                            v-for="item in siblings"
                            :key="item.id"
                            :value="item.id"
                            :label="item.name"
                          ></el-option>
                        </el-select>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group-input align-items-start">
                        <span class="form-label" style="margin-right: 35px"
                          >Ghi chú:
                        </span>
                        <el-input
                          type="textarea"
                          v-model="form.note"
                          placeholder="Ghi chú"
                          :disabled="isDisabled"
                        ></el-input>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 col-lg-3">
                      <div class="form-group">
                        <span class="form-label">Phiếu điều tra tâm lý: </span>
                        <el-upload
                          class="upload-demo"
                          :auto-upload="false"
                          action=""
                          :file-list="form.fileLists.fileList1"
                          :on-change="handleChangeFile1"
                          :on-preview="handlePreview"
                          :limit="1"
                          :disabled="isDisabled"
                          list-type="text"
                        >
                          <el-button
                            size="small"
                            type="primary"
                            :disabled="isDisabled"
                            >Click to upload</el-button
                          >
                        </el-upload>
                      </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                      <div class="form-group">
                        <span class="form-label">Phiếu khám sức khỏe: </span>
                        <el-upload
                          class="upload-demo"
                          :auto-upload="false"
                          action=""
                          :file-list="form.fileLists.fileList2"
                          :on-change="handleChangeFile2"
                          :on-preview="handlePreview"
                          :limit="1"
                          :disabled="isDisabled"
                          list-type="text"
                        >
                          <el-button
                            size="small"
                            type="primary"
                            :disabled="isDisabled"
                            >Click to upload</el-button
                          >
                        </el-upload>
                      </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                      <div class="form-group">
                        <span class="form-label">Bản sao hộ khẩu: </span>
                        <el-upload
                          class="upload-demo"
                          :auto-upload="false"
                          action=""
                          :file-list="form.fileLists.fileList3"
                          :on-change="handleChangeFile3"
                          :on-preview="handlePreview"
                          :limit="1"
                          :disabled="isDisabled"
                          list-type="text"
                        >
                          <el-button
                            size="small"
                            type="primary"
                            :disabled="isDisabled"
                            >Click to upload</el-button
                          >
                        </el-upload>
                      </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                      <div class="form-group">
                        <span class="form-label">Bản sao giấy khai sinh: </span>
                        <el-upload
                          class="upload-demo"
                          :auto-upload="false"
                          action=""
                          :file-list="form.fileLists.fileList4"
                          :on-change="handleChangeFile4"
                          :on-preview="handlePreview"
                          :limit="1"
                          list-type="text"
                        >
                          <el-button
                            size="small"
                            type="primary"
                            :disabled="isDisabled"
                            >Click to upload</el-button
                          >
                        </el-upload>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </form-wrapper>
            <form-wrapper>
              <template v-slot:form-title>Dịch vụ cộng thêm</template>
              <template>
                <div class="row additional_information">
                  <div
                    class="col-md-4 mb-2"
                    v-for="item in extra_services"
                    :key="item.id"
                  >
                    <input
                      type="checkbox"
                      name=""
                      id=""
                      v-model="form.extra_services[item.id]"
                      :disabled="isDisabled"
                    />
                    {{ item.name }}
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
                      <!-- <el-button size="small" type="primary"
                          >Click to upload</el-button
                        > -->
                      <!-- <img
                          v-if="form.imageUrl"
                          :src="form.imageUrl"
                          class="avatar"
                        /> -->
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
                      <!-- <el-icon v-else class="avatar-uploader-icon"
                          ><Plus
                        /></el-icon> -->
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
    <div class="container-fluid">
      <div class="record-wrapper">
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
            <div class="wrap-table table-record">
              <el-table
                :data="dataFilter"
                empty-text="Không có dữ liệu"
                style="width: 100%"
                stripe
                v-loading="loading"
              >
                <el-table-column width="40" align="center" fixed>
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
                  label="MHS"
                  prop="id"
                  width="100"
                  align="center"
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
                <!-- <el-table-column
                  label="Cơ sở"
                  prop="cosoName"
                  width="200"
                  v-if="!id_coso"
                  header-align="center"
                  sortable
                ></el-table-column> -->
                <el-table-column
                  label="Năm sinh"
                  prop="birth_year"
                  align="center"
                  width="100"
                >
                </el-table-column>
                <el-table-column
                  label="Giới tính"
                  prop="gender_text"
                  align="center"
                  width="80"
                >
                </el-table-column>
                <el-table-column
                  label="Dân tộc"
                  prop="ethnicity_name"
                  align="center"
                  width="100"
                >
                </el-table-column>
                <el-table-column
                  label="Phụ huynh"
                  prop="parent_name"
                  header-align="center"
                  width="200"
                >
                </el-table-column>
                <el-table-column
                  label="Điện thoại"
                  prop="phone"
                  align="center"
                  width="120"
                >
                </el-table-column>
                <el-table-column
                  label="Địa chỉ"
                  prop="address"
                  header-align="center"
                  width="200"
                >
                </el-table-column>
                <el-table-column
                  width="150"
                  align="right"
                  header-align="center"
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
                    <!-- <el-button
                      size="mini"
                      type="success"
                      @click="arrangeClass(scope.row.id)"
                      v-if="!scope.row.status"
                      >Chia lớp</el-button
                    > -->
                    <el-button
                      size="mini"
                      v-if="!scope.row.status"
                      type="warning"
                      @click="handleEdit(scope.$index, scope.row)"
                      data-bs-toggle="modal"
                      data-bs-target="#exampleModal"
                      ><span>Sửa</span></el-button
                    >
                    <el-button
                      v-else
                      size="mini"
                      @click="handleEdit(scope.$index, scope.row)"
                      data-bs-toggle="modal"
                      data-bs-target="#exampleModal"
                      ><span>Xem</span></el-button
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
import SubModal from "../layouts/sub-modal.vue";
import FooterQlmn from "../layouts/footer-qlmn.vue";
import FormWrapper from "../layouts/form-wrapper.vue";
import multiselect from "vue-multiselect";

export default {
  name: "record",
  components: {
    multiselect,
    MainModal,
    FormWrapper,
    SubModal,
    FooterQlmn,
  },
  props: ["id_enrollment"],
  data() {
    return {
      tableData: [],
      search: "",
      isShowCreate: false,
      isShowCreateTitle: true,
      exist: false,
      titleMessage: "",
      isErrorForm: false,
      nameUpdate: "",
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      showUpload: true,
      ethnicities: [],
      arraySelectedRecord: [],
      resetCheckbox: 0,
      ethnicities: [],
      jobs: [],
      edit: false,
      titleSubModal: "",
      job_add: {
        name: "",
      },
      ethnicity_add: {
        name: "",
      },
      form: {
        id: "",
        image: {
          name: "",
          content: "",
        },
        name: "",
        biet_danh: "",
        gender: "",
        birth_date: "",
        address: "",
        height: "",
        weigth: "",
        ethnicity: 1,
        medical_history: "",
        parent_name: "",
        parent_age: "",
        parent_job: "",
        parent_phone: "",
        parent_email: "",
        mother_name: "",
        mother_age: "",
        mother_job: "",
        parents_note: "",
        parents_img: [],
        type_relate: "",
        note: "",
        files: {
          file1: {
            name: "",
            content: "",
          },
          file2: {
            name: "",
            content: "",
          },
          file3: {
            name: "",
            content: "",
          },
          file4: {
            name: "",
            content: "",
          },
        },
        fileLists: {
          imgList: [],
          fileList1: [],
          fileList2: [],
          fileList3: [],
          fileList4: [],
          parentImgList: [],
        },
        id_coso: "",
        extra_services: {},
      },
      siblings: [],
      siblings_filter: {
        id: "",
        address: "",
        parent_phone: "",
        mother_phone: "",
      },
      siblings: [],
      isDisabled: false,
      extra_services: [],
      id_coso: "",
      cosos: [],
      loading: false,
      selected: [],
      exclude: [],
      total: 0,
    };
  },

  methods: {
    chiaLopTuDong() {
      axios
        .post("/arrangeClassAuto", {
          id_coso: this.form.id_coso,
          selected: this.selected,
          excludes: this.exclude,
        })
        .then((response) => {
          if (response.data.status) {
            this.$notify({
              title: "Thông báo",
              message: `Chia lớp thành công`,
              type: "success",
            });
            this.fetchData();
            this.selected = [];
            this.exclude = [];
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
    async getEthnicities() {
      await axios
        .get("/dataEthnicities")
        .then((response) => {
          if (response.data.status) {
            this.ethnicities = response.data.ethnicities;
            // this.fetchData();
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
    checkExist() {
      this.exist = false;
      for (let i = 0; i < this.tableData.length; i++) {
        var date = new Date(this.tableData[i]["birth_date"]);
        if (
          this.tableData[i]["mhs"] != this.form.mhs &&
          this.tableData[i]["name"] == this.form.name &&
          this.tableData[i]["address"] == this.form.address &&
          this.tableData[i]["id_coso"] == this.form.id_coso &&
          this.formatDate(date) == this.formatDate(this.form.birth_date)
        ) {
          this.exist = true;
          break;
        }
      }
      return this.exist;
    },
    toggleUpload() {
      this.showUpload = !this.showUpload;
    },
    setPage(val) {
      this.page = val;
      this.isSetPage = true;
      this.fetchData();
    },
    async arrangeClass(id) {
      var a = document.createElement("a");
      var event = new MouseEvent("click");
      a.href = `/danh-sach-hoc-sinh/${id}`;
      a.target = "_blank";
      a.dispatchEvent(event);
    },
    async handleEdit(index, row) {
      this.isDisabled = false;
      await this.getRecord(row.id);
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
            .post("/deleteRecord/" + row.id)
            .then((response) => {
              if (response.data.status == true) {
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa hồ sơ của em "${name}" thành công!`,
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
    async fetchData() {
      // this.arraySelectedRecord = [];
      // this.resetCheckbox += 1;
      this.loading = true;
      await axios
        .get("/dataRecords/" + this.id_enrollment, {
          params: {
            page: this.page,
            pageSize: this.pageSize,
            id_coso: this.form.id_coso,
          },
        })
        .then((response) => {
          if (response.data.status == true) {
            this.tableData = response.data.items;
            this.tableData = this.tableData.map((item, index) => {
              item = {
                ...item,
                checked: false,
                index: index + 1,
                gender_text: item.gender ? "Nam" : "Nữ",
                birth_year: new Date(item.birth_date).getFullYear(),
                parent_name: item.parent_name
                  ? item.parent_name
                  : item.mother_name,
                phone: item.parent_phone
                  ? item.parent_phone
                  : item.mother_phone,
                ethnicity_name: this.ethnicities.find(
                  (eth) => eth.id == item.ethnicity
                )
                  ? this.ethnicities.find((eth) => eth.id == item.ethnicity)
                      .name
                  : "",
                // cosoName: item.id_coso
                //   ? this.cosos.find((coso) => coso.id === item.id_coso)
                //       .school_name
                //   : "",
              };
              return item;
            });
            this.dataFilter = this.tableData;
            // this.pagedTableData(1);
            this.total = response.data.total;
            this.loading = false;
          }
        });
    },
    formatBeforeSend() {
      this.form.birth_date = this.formatDate(this.form.birth_date);
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
    validateForm() {
      if (this.form.name == "") {
        this.isErrorForm = true;
        this.titleMessage = "Tên học sinh không được để trống";
        return false;
      }
      if (this.form.birth_date == "") {
        this.isErrorForm = true;
        this.titleMessage = "Ngày sinh của học sinh không được để trống";
        return false;
      }
      if (this.form.address == "") {
        this.isErrorForm = true;
        this.titleMessage = "Địa chỉ của học sinh không được để trống";
        return false;
      }
      if (this.form.parent_name == "" && this.form.mother_name == "") {
        this.isErrorForm = true;
        this.titleMessage = "Tên phụ huynh học sinh không được để trống";
        return false;
      }
      if (this.form.parent_phone == "" && this.form.mother_phone == "") {
        this.isErrorForm = true;
        this.titleMessage =
          "Số điện thoại của phụ huynh học sinh không được để trống";
        return false;
      }
      return true;
    },
    resetForm() {
      this.isDisabled = false;
      let id_coso = this.form.id_coso;
      this.form = {
        id: "",
        image: {
          name: "",
          content: "",
        },
        name: "",
        biet_danh: "",
        gender: 1,
        birth_date: "",
        address: "",
        height: "",
        weigth: "",
        ethnicity: 1,
        medical_history: "",
        parent_name: "",
        parent_age: "",
        parent_job: "",
        parent_phone: "",
        parent_email: "",
        mother_name: "",
        mother_age: "",
        mother_job: "",
        // mother_phone: '',
        // mother_email: '',
        parents_note: "",
        parents_img: [],
        type_relate: "",
        siblings: [],
        note: "",
        files: {
          file1: {
            name: "",
            content: "",
          },
          file2: {
            name: "",
            content: "",
          },
          file3: {
            name: "",
            content: "",
          },
          file4: {
            name: "",
            content: "",
          },
        },
        fileLists: {
          imgList: [],
          fileList1: [],
          fileList2: [],
          fileList3: [],
          fileList4: [],
          parentImgList: [],
        },
        id_coso,
      };
      this.mhsAutocomplete();
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

    // LÀM PHẦN FORM
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
      // this.saved.step1 = false;
    },
    handlePreview(file) {
      var a = document.createElement("a");
      var event = new MouseEvent("click");
      a.download = file.name;
      a.href = file.url;
      a.dispatchEvent(event);
    },
    handleChangeFile1(file) {
      const isCorrectType =
        file.name.endsWith(".pdf") ||
        file.name.endsWith(".doc") ||
        file.name.endsWith(".docx");
      const isCorrectSize = file.size / 1024 / 1024 < 10;
      if (!isCorrectType) {
        alert("File không hợp lệ");
        this.form.fileLists.fileList1 = [];
        return false;
      }

      if (!isCorrectSize) {
        alert("Vui lòng chọn file có kích thước nhỏ hơn 10MB");
        this.form.fileLists.fileList1 = [];
        return false;
      }

      // this.form.files.file1 = file.target.files[0];

      this.form.files.file1.name = file.name;

      let reader = new FileReader();
      reader.readAsDataURL(file.raw);
      reader.onload = (e) => {
        this.form.files.file1.content = e.target.result;
      };
    },
    handleChangeFile2(file) {
      const isCorrectType =
        file.name.endsWith(".pdf") ||
        file.name.endsWith(".doc") ||
        file.name.endsWith(".docx");
      const isCorrectSize = file.size / 1024 / 1024 < 10;
      if (!isCorrectType) {
        alert("File không hợp lệ");
        this.form.fileLists.fileList2 = [];
        return false;
      }

      if (!isCorrectSize) {
        alert("Vui lòng chọn file có kích thước nhỏ hơn 10MB");
        this.form.fileLists.fileList2 = [];
        return false;
      }

      this.form.files.file2.name = file.name;

      let reader = new FileReader();
      reader.readAsDataURL(file.raw);
      reader.onload = (e) => {
        this.form.files.file2.content = e.target.result;
      };
    },
    handleChangeFile3(file) {
      const isCorrectType =
        file.name.endsWith(".pdf") ||
        file.name.endsWith(".doc") ||
        file.name.endsWith(".docx");
      const isCorrectSize = file.size / 1024 / 1024 < 10;
      if (!isCorrectType) {
        alert("File không hợp lệ");
        this.form.fileLists.fileList3 = [];
        return false;
      }

      if (!isCorrectSize) {
        alert("Vui lòng chọn file có kích thước nhỏ hơn 10MB");
        this.form.fileLists.fileList3 = [];
        return false;
      }

      this.form.files.file3.name = file.name;

      let reader = new FileReader();
      reader.readAsDataURL(file.raw);
      reader.onload = (e) => {
        this.form.files.file3.content = e.target.result;
      };
    },
    handleChangeFile4(file) {
      const isCorrectType =
        file.name.endsWith(".pdf") ||
        file.name.endsWith(".doc") ||
        file.name.endsWith(".docx");
      const isCorrectSize = file.size / 1024 / 1024 < 10;
      if (!isCorrectType) {
        alert("File không hợp lệ");
        this.form.fileLists.fileList4 = [];
        return false;
      }

      if (!isCorrectSize) {
        alert("Vui lòng chọn file có kích thước nhỏ hơn 10MB");
        this.form.fileLists.fileList4 = [];
        return false;
      }

      this.form.files.file4.name = file.name;

      let reader = new FileReader();
      reader.readAsDataURL(file.raw);
      reader.onload = (e) => {
        this.form.files.file4.content = e.target.result;
      };
    },
    async checkExistStep1() {
      var exist = false;
      this.formatBeforeSend();
      exist = await axios
        .post("/checkExistRecord", this.form)
        .then((response) => {
          if (response.data.status) {
            if (response.data.exist) {
              // alert('Hồ sơ này đã tồn tại, vui lòng kiểm tra lại thông tin');
              return true;
            }
            return false;
          }
        })
        .catch();
      return exist;
    },
    validateStep1() {
      if (this.form.name === "") {
        // alert("Tên học sinh không được để trống");
        this.$notify({
          title: "Thông báo",
          message: "Tên học sinh không được để trống",
          type: "warning",
        });
        return false;
      }

      if (this.form.birth_date === "") {
        // alert("Ngày sinh của học sinh không được để trống");
        this.$notify({
          title: "Thông báo",
          message: "Ngày sinh của học sinh không được để trống",
          type: "warning",
        });
        return false;
      }

      if (this.form.gender === "") {
        // alert("Giới tính của học sinh không được để trống");
        this.$notify({
          title: "Thông báo",
          message: "Giới tính của học sinh không được để trống",
          type: "warning",
        });
        return false;
      }

      if (this.form.address === "") {
        // alert("Địa chỉ của học sinh không được để trống");
        this.$notify({
          title: "Thông báo",
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
          // alert("Số điện thoại không đúng định dạng");
          this.$notify({
            title: "Thông báo",
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
          // alert("Email không đúng định dạng");
          this.$notify({
            title: "Thông báo",
            message: "Email không đúng định dạng",
            type: "warning",
          });
          return false;
        }
      }
      return true;
    },
    async getSiblings() {
      await axios
        .post("/getSiblings", this.siblings_filter)
        .then((response) => {
          if (response.data.status) {
            this.siblings = response.data.siblings;
          }
        })
        .catch();
    },
    async handleSubmit() {
      if (this.validateStep1()) {
        var checkExistStep1 = await this.checkExistStep1();
        if (!checkExistStep1) {
          this.formatBeforeSend();
          var config = { "content-type": "multipart/form-data" };
          return axios
            .post("/createRecord", this.form, config)
            .then((response) => {
              if (response.data.status) {
                this.form.id = response.data.recordId;
                // this.saved.step1 = true;
                this.fetchData();
                this.$notify({
                  title: "Thông báo",
                  message: `Đã cập nhật hồ sơ học sinh "${this.form.name}" thành công!`,
                  type: "success",
                });
                return true;
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
            title: "Cảnh báo",
            message: "Hồ sơ này đã tồn tại",
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
    async getRecord(id) {
      await axios
        .get("/getRecordById/" + id)
        .then((response) => {
          if (response.data.status) {
            if (response.data.record.status != 0) {
              this.isDisabled = true;
            }
            this.form.id = id;
            this.form.name = response.data.record.name;
            this.form.biet_danh = response.data.record.biet_danh;
            this.form.birth_date = response.data.record.birth_date;
            this.form.gender = Number(response.data.record.gender);

            this.form.address = response.data.record.address;
            this.form.height = response.data.record.height;
            this.form.weigth = response.data.record.weigth;
            // this.form.id_coso = response.data.record.id_coso
            //   ? Number(response.data.record.id_coso)
            //   : null;
            this.form.ethnicity = response.data.record.ethnicity
              ? Number(response.data.record.ethnicity)
              : "";
            this.form.medical_history = response.data.record.medical_history;
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
            // this.form.mother_phone = response.data.record.mother_phone;
            // this.form.mother_email = response.data.record.mother_email;
            this.form.parents_note = response.data.record.parents_note;
            this.form.type_relate = response.data.record.type_relate;
            this.form.siblings = response.data.record.siblings;
            this.form.note = response.data.record.note;
            if (response.data.record.image) {
              this.form.fileLists.imgList = [
                {
                  name: response.data.record.image,
                  url: response.data.record.urlImage,
                },
              ];
            }
            if (response.data.record.psychological_record) {
              this.form.fileLists.fileList1 = [
                {
                  name: response.data.record.psychological_record,
                  url: response.data.record.urlFile1,
                },
              ];
            }
            if (response.data.record.health_record) {
              this.form.fileLists.fileList2 = [
                {
                  name: response.data.record.health_record,
                  url: response.data.record.urlFile2,
                },
              ];
            }
            if (response.data.record.family_register) {
              this.form.fileLists.fileList3 = [
                {
                  name: response.data.record.family_register,
                  url: response.data.record.urlFile3,
                },
              ];
            }
            if (response.data.record.birth_certificate) {
              this.form.fileLists.fileList4 = [
                {
                  name: response.data.record.birth_certificate,
                  url: response.data.record.urlFile4,
                },
              ];
            }
            let extra_services = response.data.record.extra_services
              ? JSON.parse(response.data.record.extra_services)
              : {};
            this.form.extra_services = {};
            for (let item of this.extra_services) {
              if (item.id in extra_services) {
                if (extra_services[item.id]) {
                  this.form.extra_services = {
                    ...this.form.extra_services,
                    [item.id]: true,
                  };
                } else {
                  this.form.extra_services = {
                    ...this.form.extra_services,
                    [item.id]: false,
                  };
                }
              } else {
                this.form.extra_services = {
                  ...this.form.extra_services,
                  [item.id]: false,
                };
              }
            }
          }
        })
        .catch();
    },
    findById(arr, id) {
      var result = null;
      if (id) {
        result = arr.find((item) => item.id == id);
      }
      return result ? result.name : "";
    },
    // End method upload image

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
                // this.dialogVisible = false;
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
          // this.job_add.isError = true;
          // this.job_add.titleMessageError = "Nghề nghiệp đã tồn tại";
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
        // this.job_add.isError = true;
        // this.job_add.titleMessageError = "Tên nghề nghệp không được để trống";
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
      // this.job_add.isError = false;
      // this.job_add.titleMessageError = "";
      this.job_add.name = "";
    },
    async getJobs() {
      await axios.get("/dataJobs").then((response) => {
        if (response.data.status) {
          this.jobs = response.data.jobs;
        }
      });
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
    mhsAutocomplete() {
      axios.get("/getLastMHS").then((response) => {
        if (response.data.status == true) {
          this.form.id = response.data.lastMHS;
        }
      });
    },
    async getCosos() {
      await axios
        .get("/getCosos")
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
    async getExtraServices() {
      await axios
        .get("dataExtraServices", {
          params: {
            id_coso: this.form.id_coso,
          },
        })
        .then((response) => {
          if (response.data.status) {
            this.extra_services = response.data.extraservices;
            this.form.extra_services = this.extra_services.reduce(
              (result, item) => {
                return {
                  ...result,
                  [item.id]: false,
                };
              },
              {}
            );
          }
        });
    },
  },
  created() {
    Promise.all([this.getJobs(), this.getEthnicities()]).then(async () => {
      await this.getCosos();
      this.getExtraServices();
    });
  },
  watch: {
    "form.id_coso": function () {
      // this.fetchData();
      this.getExtraServices();
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
    // page: function () {
    //   this.fetchData();
    //   // this.pagedTableData(1);
    //   // this.resetCheckbox = this.resetCheckbox + 1;
    // },
    "form.address": function () {
      this.siblings_filter.address = this.form.address;
      this.siblings_filter.id = this.form.id;
      this.getSiblings();
      // this.saved.step1 = false;
    },
    "form.parent_phone": function () {
      this.siblings_filter.parent_phone = this.form.parent_phone;
      this.siblings_filter.id = this.form.id;
      this.getSiblings();
      // this.saved.step1 = false;
    },
    selected: function () {
      this.$forceUpdate();
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