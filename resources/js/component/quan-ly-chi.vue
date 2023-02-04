<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4>Quản lý chi</h4>
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
    <main-modal widthModal="80%">
      <template>
        <form-wrapper>
          <template v-slot:form-title>{{ title }}</template>
          <template>
            <div class="row">
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label" style="margin-right: 14px">
                    Chứng từ số:
                  </span>
                  <el-input
                    placeholder="Chứng từ số"
                    v-model="form.so_chung_tu"
                    :disabled="true"
                  ></el-input>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label required"> Ngày chứng từ: </span>
                  <el-date-picker
                    v-model="form.ngay_chung_tu"
                    type="date"
                    placeholder="Ngày chứng từ"
                    format="dd/MM/yyyy"
                    :picker-options="pickerBeginDateBefore"
                  ></el-date-picker>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label required"> Tháng/năm: </span>
                  <el-date-picker
                    v-model="form.month"
                    type="month"
                    placeholder="Tháng/năm nguồn thu"
                    format="MM/yyyy"
                  ></el-date-picker>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label"> Nhà cung cấp: </span>
                  <el-select
                    v-model="form.nha_cung_cap"
                    placeholder="Nhà cung cấp"
                  >
                    <el-option
                      selected
                      :value="null"
                      label="--Chọn nhà cung cấp--"
                    ></el-option>
                    <el-option
                      v-for="item in nha_cung_caps"
                      :key="item.id"
                      :value="item.id"
                      :label="item.name"
                    ></el-option>
                  </el-select>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label" style="margin-right: 55px">
                    Đại diện:
                  </span>
                  <el-input
                    placeholder="Đại diện"
                    v-model="form.dai_dien"
                  ></el-input>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label" style="margin-right: 42px">
                    Địa chỉ:
                  </span>
                  <el-input
                    placeholder="Địa chỉ"
                    v-model="form.dia_chi"
                  ></el-input>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="form-group-input">
                  <span class="form-label" style="margin-right: 42px">
                    Ghi chú:</span
                  >
                  <el-input
                    placeholder="Ghi chú"
                    v-model="form.ghi_chu"
                  ></el-input>
                </div>
              </div>
              <div class="col-md-6 col-lg-4" v-if="!id_coso">
                <div class="form-group-input">
                  <span class="form-label required" style="margin-right: 62px">
                    Cơ sở:
                  </span>
                  <el-select
                    v-model="form.id_coso"
                    placeholder="Cơ sở"
                    @change="
                      () => {
                        this.form.loai_chi_phi = '';
                        this.getLoaiChiPhisForForm();
                      }
                    "
                  >
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
          </template>
        </form-wrapper>
        <form-wrapper>
          <template v-slot:form-title>Chi tiết phiếu chi</template>
          <template>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group-input">
                  <span class="form-label required"> Loại chi phí:</span>
                  <el-select
                    placeholder="Loại chi phí"
                    v-model="form.loai_chi_phi"
                  >
                    <el-option
                      v-for="item in loai_chi_phis_form"
                      :key="item.id"
                      :value="item.id"
                      :label="item.name"
                    ></el-option>
                  </el-select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group-input">
                  <span class="form-label"> Nội dung phiếu chi:</span>
                  <el-input
                    placeholder="Nội dung phiếu chi"
                    v-model="form.noi_dung_phieu_chi"
                  ></el-input>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label required" style="margin-right: 9px">
                    Thành tiền:</span
                  >
                  <el-input
                    placeholder="Thành tiền"
                    v-model="form.thanh_tien"
                  ></el-input>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label required"> Đã chi:</span>
                  <el-input
                    placeholder="Đã chi"
                    v-model="form.da_chi"
                  ></el-input>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label"> Còn nợ:</span>
                  <el-input
                    placeholder="Còn nợ"
                    v-model="form.con_no"
                    :disabled="true"
                  ></el-input>
                </div>
              </div>
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
    <div class="container-fluid">
      <div class="record-wrapper">
        <div class="row">
          <div class="col-12">
            <div class="card filter_form">
              <div class="card-body">
                <h5 class="card-title mb-0">Tìm kiếm</h5>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="label-input" style="margin-right: 26px"
                      >Số chứng từ:
                    </span>
                    <el-input
                      placeholder="Số chứng từ"
                      v-model="filter.soChungTu"
                    ></el-input>
                  </div>
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="label-input" style="margin-right: 26px"
                      >Ngày chứng từ:
                    </span>
                    <el-date-picker
                      v-model="filter.ngayChungTu"
                      type="date"
                      placeholder="Ngày chứng từ"
                      format="dd/MM/yyyy"
                    ></el-date-picker>
                  </div>
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="label-input">Nội dung phiếu chi: </span>
                    <el-input
                      type="textarea"
                      placeholder="Nội dung phiếu chi"
                      v-model="filter.noi_dung_phieu_chi"
                    ></el-input>
                  </div>
                </div>
                <div class="col-md-6">
                  <!-- <div class="row">
                    <div class="form-group-input d-block d-lg-flex">
                      <div class="col-sm-6">
                        <div class="form-group-input d-block d-lg-flex p-2">
                          <span class="">Tháng: </span>
                          <el-select v-model="filter.month" placeholder="Tháng">
                            <el-option
                              v-for="item in months"
                              :key="item"
                              :label="item"
                              :value="item"
                            >
                            </el-option>
                          </el-select>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group-input d-block d-lg-flex p-2">
                          <span class="">Năm: </span>
                          <el-select v-model="filter.year" placeholder="Năm">
                            <el-option
                              v-for="item in years"
                              :key="item"
                              :label="item"
                              :value="item"
                            >
                            </el-option>
                          </el-select>
                        </div>
                      </div>
                    </div>
                  </div> -->
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="label-input">Tháng/năm: </span>
                    <el-date-picker
                      v-model="filter.month"
                      type="month"
                      placeholder="Tháng/năm"
                      format="MM/yyyy"
                    ></el-date-picker>
                  </div>
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="label-input">Loại chi phí: </span>
                    <el-select
                      v-model="filter.loai_chi_phi"
                      placeholder="Loại chi phí"
                    >
                      <el-option label="Tất cả" value=""></el-option>
                      <el-option
                        v-for="item in loai_chi_phis"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id"
                      >
                      </el-option>
                    </el-select>
                  </div>
                  <div
                    class="form-group-input d-block d-lg-flex"
                    v-if="!id_coso"
                  >
                    <span class="label-input">Cơ sở: </span>
                    <el-select v-model="filter.id_coso" placeholder="Cơ sở">
                      <el-option :value="null" label="Tất cả"></el-option>
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

              <div
                class="form-group-button mt-3"
                style="justify-content: center"
              >
                <el-button size="small" type="" @click="resetFilter()"
                  >Làm mới</el-button
                >
                <el-button size="small" type="primary" @click="handleSearch()"
                  >Tìm kiếm</el-button
                >
              </div>
            </div>
            <div class="wrap-table table-record">
              <el-table
                :data="dataFilter"
                empty-text="Không có dữ liệu"
                style="width: 100%"
                stripe
              >
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
                  label="Chứng từ số"
                  prop="so_chung_tu"
                  align="center"
                  width="150"
                  fixed="left"
                >
                </el-table-column>
                <el-table-column
                  label="Ngày chứng từ"
                  prop="ngayChungTuRender"
                  align="center"
                  width="120"
                >
                </el-table-column>
                <el-table-column
                  label="Nhà cung cấp"
                  prop="nha_cung_cap_name"
                  header-align="center"
                  width="180"
                >
                </el-table-column>
                <el-table-column
                  label="Người đại diện"
                  prop="dai_dien"
                  header-align="center"
                  width="180"
                >
                </el-table-column>
                <el-table-column
                  label="Địa chỉ"
                  prop="dia_chi"
                  header-align="center"
                  width="200"
                >
                </el-table-column>
                <el-table-column
                  label="Diễn giải"
                  prop="noi_dung_phieu_chi"
                  header-align="center"
                  width="200"
                >
                </el-table-column>
                <el-table-column
                  label="Loại chi phí"
                  prop="loai_chi_phi_name"
                  header-align="center"
                  width="200"
                >
                </el-table-column>
                <el-table-column
                  label="Thành tiền"
                  align="center"
                  width="100"
                  prop="thanh_tien"
                >
                  <template slot-scope="scope" v-if="scope.row.thanh_tien">
                    {{ scope.row.thanh_tien | formatMoney }}
                  </template>
                </el-table-column>
                <el-table-column
                  label="Đã thu"
                  prop="da_chi"
                  align="center"
                  width="100"
                >
                  <template slot-scope="scope" v-if="scope.row.da_chi">
                    {{ scope.row.da_chi | formatMoney }}
                  </template>
                </el-table-column>
                <el-table-column
                  label="Còn nợ"
                  prop="con_no"
                  align="center"
                  width="100"
                >
                  <template slot-scope="scope" v-if="scope.row.con_no">
                    {{ scope.row.con_no | formatMoney }}
                  </template>
                </el-table-column>
                <el-table-column
                  label="Ghi chú"
                  prop="note"
                  header-align="center"
                  width="200"
                ></el-table-column>
                <el-table-column
                  align="right"
                  header-align="center"
                  fixed="right"
                  width="100"
                  label="Cập nhật"
                >
                  <template slot-scope="scope">
                    <el-button
                      size="mini"
                      type="warning"
                      @click="handleEdit(scope.row)"
                      data-bs-toggle="modal"
                      data-bs-target="#exampleModal"
                      ><span>Sửa</span></el-button
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

export default {
  name: "quan-ly-chi",
  components: {
    MainModal,
    FormWrapper,
    FooterQlmn,
  },
  mixins: [mixin],
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
      total: 0,
      dataFilter: [],
      isSetPage: true,
      showUpload: true,
      ethnicities: [],
      arraySelectedRecord: [],
      resetCheckbox: 0,
      ethnicities: [],
      jobs: [],
      edit: false,
      // làm phần quản lý thu
      filter: {
        soChungTu: "",
        ngayChungTu: "",
        month: "",
        year: "",
        loai_chi_phi: "",
        noi_dung_phieu_chi: "",
        id_coso: null,
      },
      form: {
        id: "",
        so_chung_tu: "",
        ngay_chung_tu: "",
        month: "",
        year: "",
        nha_cung_cap: "",
        dai_dien: "",
        dia_chi: "",
        loai_chi_phi: "",
        noi_dung_phieu_chi: "",
        thanh_tien: "",
        da_chi: "",
        con_no: "",
        ghi_chu: "",
        id_coso: null,
      },
      pickerBeginDateBefore: {
        disabledDate(time) {
          return time.getTime() + 86400000 < Date.now();
        },
      },
      months: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
      years: [],
      loai_chi_phis: [],
      loai_chi_phis_form: [],
      nha_cung_caps: [],
      title: "Thêm phiếu chi",
      cosos: [],
      id_coso: null,
      loading: false,
    };
  },
  methods: {
    // LÀM MULTI SELECT KHÔNG ĐƯỢC XÓA
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
    // LÀM MULTI SELECT KHÔNG ĐƯỢC XÓA
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
    // getYears() {
    //   axios
    //     .get("getYears")
    //     .then((response) => {
    //       if (response.data.status) {
    //         this.years = response.data.years;
    //       }
    //     })
    //     .catch();
    // },
    handleSearch() {
      this.page = 1;
      // this.resetCheckbox = this.resetCheckbox + 1;
      this.fetchData();
    },
    fetchData() {
      // this.arraySelectedRecord = [];
      // this.resetCheckbox += 1;
      this.filter.ngayChungTu = this.filter.ngayChungTu
        ? this.formatDate(this.filter.ngayChungTu)
        : "";
      axios
        .post("/dataPhieuChis", {
          ...this.filter,
          page: this.page,
          pageSize: this.pageSize,
        })
        .then((response) => {
          if (response.data.status == true) {
            this.total = response.data.count;
            this.dataFilter = response.data.items;
            this.dataFilter = this.dataFilter.map((item, index) => {
              return {
                ...item,
                checked: false,
                nha_cung_cap_name: this.findById(
                  this.nha_cung_caps,
                  item.nha_cung_cap
                ),
                loai_chi_phi_name: this.findById(
                  this.loai_chi_phis,
                  item.loai_chi_phi
                ),
                ngayChungTuRender: this.formatDateRender(item.ngay_chung_tu),
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
    },
    resetFilter() {
      for (let key in this.filter) {
        this.filter[key] = "";
      }
      this.filter.id_coso = null;
    },
    formatBeforeSend() {
      this.form.ngay_chung_tu = this.formatDate(this.form.ngay_chung_tu);
      this.form.thanh_tien = this.form.thanh_tien.replace(/,/g, "");
      this.form.da_chi = this.form.da_chi.replace(/,/g, "");
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
    formatDateRender(date) {
      date = date.split("-");
      date = date.reverse();
      return date.join("/");
    },
    // pagedTableData(feature = 1) {
    //   if (feature != 1) {
    //     this.dataFilter = this.tableData.filter(
    //       (data) =>
    //         !this.search ||
    //         data.name.toLowerCase().includes(this.search.toLowerCase())
    //     );
    //   } else {
    //     this.dataFilter = this.tableData.slice(
    //       this.pageSize * this.page - this.pageSize,
    //       this.pageSize * this.page
    //     );
    //   }
    // },
    findById(arr, id) {
      var result = null;
      if (id) {
        result = arr.find((item) => item.id == id);
      }
      return result ? result.name : "";
    },
    getLoaiChiPhis() {
      axios
        .get("/dataDanhMucChiPhi", {
          params: this.filter,
        })
        .then((response) => {
          if (response.data.status == true) {
            this.loai_chi_phis = response.data.items;
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
    getLoaiChiPhisForForm() {
      axios
        .get("/dataDanhMucChiPhi", {
          params: {
            id_coso: this.form.id_coso,
          },
        })
        .then((response) => {
          if (response.data.status == true) {
            this.loai_chi_phis_form = response.data.items;
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
    getNhaCungCaps() {
      axios
        .get("/dataNhaCungCap")
        .then((response) => {
          if (response.data.status == true) {
            this.nha_cung_caps = response.data.items;
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
    // phần xử lý form
    async resetForm() {
      this.title = "Thêm phiếu chi";
      for (let key in this.form) {
        this.form[key] = "";
      }
      this.form.id_coso = null;
      this.form.so_chung_tu = await axios
        .get("lastChungTuSoChi")
        .then((response) => {
          return response.data;
        })
        .catch((e) => {
          this.$notify({
            title: "Thông báo",
            message: errorMessage[e.response.status],
            type: "warning",
          });
        });
    },
    handleEdit(row) {
      this.title = `Cập nhật phiếu chi "${row.so_chung_tu}"`;
      for (let key in this.form) {
        this.form[key] = row[key];
      }
      this.form.id_coso = row.id_coso ? Number(row.id_coso) : null;
      this.form.month = new Date(row.year, row.month - 1, "01");
      this.form.nha_cung_cap = row.nha_cung_cap
        ? Number(row.nha_cung_cap)
        : null;
      this.form.loai_chi_phi = row.loai_chi_phi
        ? Number(row.loai_chi_phi)
        : null;
      this.form.thanh_tien = this.formatMoney(Number(row.thanh_tien));
      this.form.da_chi = this.formatMoney(Number(row.da_chi));
      this.form.con_no = this.formatMoney(Number(row.con_no));

      this.form.loai_chi_phi = row.loai_chi_phi ? Number(row.loai_chi_phi) : "";
    },
    handleSubmit() {
      if (this.validateForm()) {
        this.formatBeforeSend();
        return axios
          .post("submitPhieuChi", this.form)
          .then((response) => {
            if (response.data.status) {
              this.fetchData();

              this.$notify({
                title: "Thông báo",
                message: !this.form.id
                  ? `Thêm phiếu chi ${this.form.so_chung_tu} thành công!`
                  : `Cập nhật phiếu chi ${this.form.so_chung_tu} thành công!`,
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
    validateForm() {
      if (this.form.so_chung_tu == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Số chứng từ của phiếu chi không được để trống",
          type: "warning",
        });
        return false;
      }
      if (this.form.ngay_chung_tu == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Ngày chứng từ của phiếu chi không được để trống",
          type: "warning",
        });
        return false;
      }
      if (this.form.month == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Tháng của phiếu chi không được để trống",
          type: "warning",
        });
        return false;
      }
      if (this.form.id_coso == null) {
        this.$notify({
          title: "Cảnh báo",
          message: "Cơ sở của phiếu chi không được để trống",
          type: "warning",
        });
        return false;
      }
      if (this.form.loai_chi_phi == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Loại chi phí không được để trống",
          type: "warning",
        });
        return false;
      }
      let thanhTien = this.form.thanh_tien.replace(/,/g, "");
      let daChi = this.form.da_chi.replace(/,/g, "");
      if (thanhTien <= 0) {
        this.$notify({
          title: "Cảnh báo",
          message: "Số tiền thanh toán phải lớn hơn 0",
          type: "warning",
        });
        return false;
      }
      if (daChi <= 0) {
        this.$notify({
          title: "Cảnh báo",
          message: "Số tiền đã chi phải lớn hơn 0",
          type: "warning",
        });
        return false;
      }
      if (Number(daChi) > Number(thanhTien)) {
        this.$notify({
          title: "Cảnh báo",
          message: "Số tiền đã chi không được lớn hơn số tiền cần thanh toán",
          type: "warning",
        });
        return false;
      }
      return true;
    },
    async getCosos() {
      await axios
        .get("getCosos")
        .then((response) => {
          if (response.data.status) {
            this.cosos = response.data.items;
            this.filter.id_coso = response.data.id_coso
              ? Number(response.data.id_coso)
              : null;
            this.id_coso = response.data.id_coso;
            this.form.id_coso = this.id_coso;
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
      this.getNhaCungCaps(),
      this.getLoaiChiPhis(),
      this.getCosos(),
    ]).then(() => {
      this.loai_chi_phis_form = this.loai_chi_phis;
      this.fetchData();
    });
  },
  watch: {
    "form.da_chi": function () {
      if (this.form.thanh_tien) {
        this.form.con_no = this.form.thanh_tien - this.form.da_chi;
      }
    },
    "form.thanh_tien": function () {
      if (this.form.da_chi) {
        this.form.con_no = this.form.thanh_tien - this.form.da_chi;
      }
    },
    "filter.id_coso": function () {
      this.filter.loai_chi_phi = "";
      this.getLoaiChiPhis();
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