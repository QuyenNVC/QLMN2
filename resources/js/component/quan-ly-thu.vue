<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4>Quản lý thu</h4>
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
                  <span class="form-label" style="margin-right: 24px">
                    Chứng từ số:
                  </span>
                  <el-input
                    placeholder="Chứng từ số"
                    v-model="form.chungTuSo"
                    :disabled="true"
                  ></el-input>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label required" style="margin-right: 12px">
                    Ngày chứng từ:
                  </span>
                  <el-date-picker
                    v-model="form.ngayChungTu"
                    type="date"
                    placeholder="Ngày chứng từ"
                    format="dd/MM/yyyy"
                    :disabled="!form.isEditable"
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
                    :disabled="!form.isEditable"
                  ></el-date-picker>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label"> Mã khách hàng: </span>
                  <el-input
                    placeholder="Mã khách hàng"
                    v-model="form.maKhachHang"
                    :disabled="!form.isEditable"
                  ></el-input>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label required"> Tên khách hàng: </span>
                  <el-input
                    placeholder="Tên khách hàng"
                    v-model="form.tenKhachHang"
                    :disabled="!form.isEditable"
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
                    v-model="form.diaChi"
                    :disabled="!form.isEditable"
                  ></el-input>
                </div>
              </div>
              <div class="col-lg-4 col-md-6" v-if="!id_coso">
                <div class="form-group-input">
                  <span class="form-label required" style="margin-right: 54px">
                    Cơ sở:
                  </span>
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
              <div class="col-lg-4 col-md-6">
                <el-checkbox
                  :value="form.tuHocSinh"
                  @change="form.tuHocSinh = !form.tuHocSinh"
                  >Từ học sinh</el-checkbox
                >
              </div>
            </div>
          </template>
        </form-wrapper>
        <form-wrapper>
          <template v-slot:form-title>Chi tiết phiếu thu</template>
          <template>
            <div class="row">
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label required">
                    Thành tiền (sau thuế):</span
                  >
                  <el-input
                    placeholder="Thành tiền"
                    v-model="form.thanhTien"
                    :disabled="!form.isEditable"
                  ></el-input>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label required"> Đã thu:</span>
                  <el-input
                    placeholder="Đã thu"
                    v-model="form.daThu"
                    :disabled="!form.isEditable"
                  ></el-input>
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group-input">
                  <span class="form-label"> Còn nợ:</span>
                  <el-input
                    placeholder="Còn nợ"
                    v-model="form.conNo"
                    :disabled="true"
                  ></el-input>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group-input">
                  <span class="form-label" style="margin-right: 24px">
                    Nội dung phiếu thu:</span
                  >
                  <el-input
                    placeholder="Nội dung phiếu thu"
                    v-model="form.noiDungPhieuThu"
                    :disabled="!form.isEditable"
                  ></el-input>
                </div>
              </div>
              <div class="col-12">
                <el-checkbox
                  :value="form.daThuTien"
                  @change="form.daThuTien = !form.daThuTien"
                  :disabled="!form.isEditable"
                  >Đã thu tiền</el-checkbox
                >
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
                    <span class="label-input">Số chứng từ: </span>
                    <el-input
                      placeholder="Số chứng từ"
                      v-model="filter.soChungTu"
                    ></el-input>
                  </div>
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="label-input">Ngày chứng từ: </span>
                    <el-date-picker
                      v-model="filter.ngayChungTu"
                      type="date"
                      placeholder="Ngày chứng từ"
                      format="dd/MM/yyyy"
                    ></el-date-picker>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="label-input">Tên khách hàng: </span>
                    <el-input
                      placeholder="Tên khách hàng"
                      v-model="filter.tenKhachHang"
                    ></el-input>
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
                v-loading="loading"
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
                  label="Tên người nộp/công ty"
                  prop="ten_khach_hang"
                  header-align="center"
                  width="180"
                >
                </el-table-column>
                <el-table-column
                  label="Địa chỉ người nộp/công ty"
                  prop="dia_chi"
                  header-align="center"
                  width="200"
                >
                </el-table-column>
                <el-table-column
                  label="Diễn giải"
                  prop="noi_dung_phieu_thu"
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
                  prop="da_thu"
                  align="center"
                  width="100"
                >
                  <template slot-scope="scope" v-if="scope.row.da_thu">
                    {{ scope.row.da_thu | formatMoney }}
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
                  label="Đã thu tiền"
                  prop="isPaid"
                  align="center"
                >
                  <template slot-scope="scope">
                    <el-checkbox
                      :checked="scope.row.isPaid ? true : false"
                      :readonly="true"
                    ></el-checkbox>
                  </template>
                </el-table-column>
                <el-table-column
                  label="Ghi chú"
                  prop="note"
                  header-align="center"
                  width="200"
                ></el-table-column>
                <el-table-column
                  align="center"
                  fixed="right"
                  label="Cập nhật"
                  width="100"
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
  name: "quan-ly-thu",
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
        tenKhachHang: "",
        id_coso: null,
      },
      title: "Thêm phiếu thu",
      form: {
        id: "",
        chungTuSo: "",
        ngayChungTu: "",
        tuHocSinh: false,
        month: "",
        year: "",
        maKhachHang: "",
        tenKhachHang: "",
        diaChi: "",
        noiDungPhieuThu: "",
        thanhTien: "",
        daThu: "",
        conNo: "",
        ghiChu: "",
        daThuTien: false,
        isEditable: true,
        id_coso: null,
      },
      pickerBeginDateBefore: {
        disabledDate(time) {
          return time.getTime() + 86400000 < Date.now();
        },
      },
      cosos: [],
      id_coso: null,
      loading: false,
    };
  },
  methods: {
    // GIỮ LẠI. KHÔNG XÓA ĐỂ LÀM CHỨC NĂNG MULTISELECT
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
    //   console.log(this.arraySelectedRecord);
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
    // CHỨC NĂNG MULTISELECT KHÔNG ĐƯỢC XÓA

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
    handleSearch() {
      this.page = 1;
      this.resetCheckbox = this.resetCheckbox + 1;
      this.fetchData();
    },
    fetchData() {
      // PHẦN LÀM MULTI CHECKBOX, KHÔNG ĐƯỢC XÓA
      // this.arraySelectedRecord = [];
      // this.resetCheckbox += 1;

      this.filter.ngayChungTu = this.filter.ngayChungTu
        ? this.formatDate(this.filter.ngayChungTu)
        : "";
      this.loading = true;
      axios
        .post("/dataPhieuThus", {
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
                ngayChungTuRender: this.formatDateRender(item.ngay_chung_tu),
              };
            });
            this.loading = false;
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
      this.filter = {
        soChungTu: "",
        ngayChungTu: "",
        tenKhachHang: "",
        id_coso: null,
      };
    },
    formatBeforeSend() {
      this.form.ngayChungTu = this.formatDate(this.form.ngayChungTu);
      this.form.thanhTien = this.form.thanhTien.replace(/,/g, "");
      this.form.daThu = this.form.daThu.replace(/,/g, "");
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
    async resetForm() {
      this.title = "Thêm phiếu thu";
      this.form = {
        id: "",
        chungTuSo: "",
        ngayChungTu: "",
        tuHocSinh: false,
        month: "",
        year: "",
        maKhachHang: "",
        tenKhachHang: "",
        diaChi: "",
        noiDungPhieuThu: "",
        thanhTien: "",
        daThu: "",
        conNo: "",
        ghiChu: "",
        id_coso: null,
        daThuTien: false,
        isEditable: true,
      };
      this.form.chungTuSo = await axios
        .get("lastChungTuSoThu")
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
      this.title = `Cập nhật phiều thu ${row.so_chung_tu}`;
      this.form.id = row.id;
      this.form.chungTuSo = row.so_chung_tu;
      this.form.ngayChungTu = row.ngay_chung_tu;
      this.form.tuHocSinh = row.isFromStudent;
      // this.form.month = row.month;
      // this.form.year = row.year;
      this.form.month = new Date(row.year, row.month - 1, "01");
      this.form.maKhachHang = row.ma_khach_hang;
      this.form.tenKhachHang = row.ten_khach_hang;
      this.form.diaChi = row.dia_chi;
      this.form.noiDungPhieuThu = row.noi_dung_phieu_thu;
      this.form.thanhTien = this.formatMoney(Number(row.thanh_tien));
      this.form.daThu = this.formatMoney(Number(row.da_thu));
      this.form.conNo = this.formatMoney(Number(row.con_no));
      this.form.ghiChu = row.ghi_chu;
      this.form.daThuTien = row.isPaid ? true : false;
      this.form.isEditable = row.isEditable ? true : false;
      this.form.id_coso = row.id_coso ? Number(row.id_coso) : null;
      // console.log(this.form);
    },
    handleSubmit() {
      if (this.validateForm()) {
        this.formatBeforeSend();
        console.log(this.form);
        return axios
          .post("submitPhieuThu", this.form)
          .then((response) => {
            if (response.data.status) {
              this.fetchData();

              this.$notify({
                title: "Thông báo",
                message: !this.form.id
                  ? `Thêm phiếu thu ${this.form.chungTuSo} thành công!`
                  : `Cập nhật phiếu thu ${this.form.chungTuSo} thành công!`,
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
      let namePattern = /([A-Za-z])/g;
      if (this.form.tenKhachHang.length < 3) {
        this.$notify({
          title: "Cảnh báo",
          message: "Tên khách hàng phải có ít nhất 3 ký tự trở lên",
          type: "warning",
        });
        return false;
      } else if (!namePattern.test(this.form.tenKhachHang)) {
        this.$notify({
          title: "Cảnh báo",
          message: "Tên khách hàng không hợp lệ",
          type: "warning",
        });
        return false;
      }
      if (this.form.chungTuSo == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Số chứng từ của phiếu thu không được để trống",
          type: "warning",
        });
        return false;
      }
      if (this.form.ngayChungTu == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Ngày chứng từ của phiếu thu không được để trống",
          type: "warning",
        });
        return false;
      }
      if (this.form.month == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Tháng của phiếu thu không được để trống",
          type: "warning",
        });
        return false;
      }
      if (this.form.id_coso == null) {
        this.$notify({
          title: "Cảnh báo",
          message: "Cơ sở của phiếu thu không được để trống",
          type: "warning",
        });
        return false;
      }
      let thanhTien = this.form.thanhTien.replace(/,/g, "");
      let daThu = this.form.daThu.replace(/,/g, "");
      // console.log(thanhTien, daThu);
      if (thanhTien <= 0) {
        this.$notify({
          title: "Cảnh báo",
          message: "Số tiền thanh toán phải lớn hơn 0",
          type: "warning",
        });
        return false;
      }
      if (daThu <= 0) {
        this.$notify({
          title: "Cảnh báo",
          message: "Số tiền đã thu phải lớn hơn 0",
          type: "warning",
        });
        return false;
      }
      if (Number(daThu) > Number(thanhTien)) {
        this.$notify({
          title: "Cảnh báo",
          message: "Số tiền đã thu không được lớn hơn số tiền cần thanh toán",
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
  async created() {
    await this.getCosos();
    this.fetchData();
  },
  watch: {
    "form.daThu": function () {
      if (this.form.thanhTien) {
        this.form.conNo = this.form.thanhTien - this.form.daThu;
      }
    },
    "form.thanhTien": function () {
      if (this.form.daThu) {
        this.form.conNo = this.form.thanhTien - this.form.daThu;
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