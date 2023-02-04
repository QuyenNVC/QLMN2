<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4>Quản lý thực đơn</h4>
          <div class="ms-auto text-end">
            <nav class="breadcrumb">
              <el-button type="success" size="small" @click="inThucDon"
                >In thực đơn</el-button
              >
              <el-button type="primary" size="small" @click="handleSubmit"
                >Cập nhật</el-button
              >
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="record-wrapper">
        <div class="row">
          <div class="col-12">
            <div class="card filter_form">
              <div class="card-body">
                <h5 class="card-title mb-0">Tìm kiếm</h5>
              </div>
              <div class="container">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group-input d-block d-lg-flex">
                      <span class="label-input" style="margin-right: 52px"
                        >Chọn ngày:
                      </span>
                      <el-date-picker
                        v-model="filter.ngay"
                        type="date"
                        placeholder="Chọn ngày"
                        format="dd/MM/yyyy"
                      ></el-date-picker>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-input d-block d-lg-flex">
                      <span class="label-input">Lứa tuổi: </span>
                      <el-select v-model="filter.age" placeholder="Lứa tuổi">
                        <el-option
                          selected
                          value=""
                          label="Toàn trường"
                        ></el-option>
                        <el-option
                          v-for="item in grades"
                          :key="item.id"
                          :value="item.id"
                          :label="item.name"
                        ></el-option>
                      </el-select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-input d-block d-lg-flex">
                      <span class="label-input">Cấp dưỡng nấu chính: </span>
                      <el-select
                        v-model="filter.cap_duong_nau_chinh"
                        placeholder="Cấp dưỡng nấu chính"
                      >
                        <el-option selected value="" label="Tất cả"></el-option>
                        <el-option
                          v-for="item in nhan_vien_nau_ans"
                          :key="item.id"
                          :value="item.id"
                          :label="item.name"
                        ></el-option>
                      </el-select>
                    </div>
                  </div>
                  <div class="col-md-6" v-if="!id_coso">
                    <div class="form-group-input d-block d-lg-flex">
                      <span class="label-input">Cơ sở: </span>
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
            <div class="wrap-table table_thuc_don">
              <table class="w-100">
                <tr>
                  <th></th>
                  <th>
                    <span class="text-uppercase">ăn sáng</span>
                  </th>
                  <th>
                    <span class="text-uppercase">ăn trưa</span>
                  </th>
                  <th>
                    <span class="text-uppercase">ăn xế</span>
                  </th>
                  <th>
                    <span class="text-uppercase">Chế độ chăm sóc</span>
                  </th>
                  <th>
                    <span class="text-uppercase">số lượng</span>
                  </th>
                  <th>
                    <span class="text-uppercase">t/c chi</span>
                  </th>
                  <th>
                    <span class="text-uppercase">đã chi</span>
                  </th>
                </tr>
                <tr
                  v-for="(item, index) in thuc_dons.slice(0, amount_of_date)"
                  :key="index"
                >
                  <td class="align-middle">
                    Thứ {{ index + 2 }} <br />
                    <b>{{
                      new Date(item.ngay).getDate() +
                      "/" +
                      (new Date(item.ngay).getMonth() + 1)
                    }}</b>
                  </td>
                  <td>
                    <div>
                      <el-select
                        v-model="item.bua_sang"
                        @change="changeSuatAn(item, 'bua_sang', 'an_sang')"
                      >
                        <el-option
                          v-for="(suat_an, index) in suat_ans.filter(
                            (a) => a.loai_suat_an == 1
                          )"
                          :key="index"
                          :value="suat_an.id"
                          :label="suat_an.name"
                        ></el-option>
                      </el-select>
                    </div>
                    <div class="text-start p-2">
                      {{ item.an_sang_suat_an }}
                    </div>
                  </td>
                  <td>
                    <div>
                      <el-select
                        v-model="item.bua_trua"
                        @change="changeSuatAn(item, 'bua_trua', 'an_trua')"
                      >
                        <el-option
                          v-for="(suat_an, index) in suat_ans.filter(
                            (a) => a.loai_suat_an == 2
                          )"
                          :key="index"
                          :value="suat_an.id"
                          :label="suat_an.name"
                        ></el-option>
                      </el-select>
                    </div>
                    <div class="text-start p-2">
                      {{ item.an_trua_suat_an }}
                    </div>
                  </td>
                  <td>
                    <div>
                      <el-select
                        v-model="item.bua_xe"
                        @change="changeSuatAn(item, 'bua_xe', 'an_xe')"
                      >
                        <el-option
                          v-for="(suat_an, index) in suat_ans.filter(
                            (a) => a.loai_suat_an == 3
                          )"
                          :key="index"
                          :value="suat_an.id"
                          :label="suat_an.name"
                        ></el-option>
                      </el-select>
                    </div>
                    <div class="text-start p-2">{{ item.an_xe_suat_an }}</div>
                  </td>
                  <td>
                    <div>
                      <el-select
                        v-model="item.bua_chieu"
                        @change="changeSuatAn(item, 'bua_chieu', 'an_chieu')"
                      >
                        <el-option
                          v-for="(suat_an, index) in suat_ans.filter(
                            (a) => a.loai_suat_an == 4
                          )"
                          :key="index"
                          :value="suat_an.id"
                          :label="suat_an.name"
                        ></el-option>
                      </el-select>
                    </div>
                    <div class="text-start p-2">
                      {{ item.an_chieu_suat_an }}
                    </div>
                  </td>
                  <td class="align-middle">
                    <el-input
                      v-model="item.so_luong"
                      @change="tinhTongTien(item)"
                    ></el-input>
                  </td>
                  <td class="align-middle px-2">
                    <span>{{ formatMoney(item.tc_chi) }}</span>
                  </td>
                  <td class="align-middle px-2">
                    <span>{{ formatMoney(item.da_chi) }}</span>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- footer -->
    <!-- ============================================================== -->
    <footer-qlmn></footer-qlmn>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
    <div
      class="table_thuc_don_print p-5 bg-white position-relative"
      id="table_thuc_don_print"
    >
      <div
        class="thuc_don_thong_tin_truong position-absolute text-wrap"
        style="font-size: 18px; max-width: 200px; top: 15px"
      >
        <p class="mb-0">Trường MN:{{ thong_tin_truong.name }}</p>
        <p class="mb-0">Địa chỉ: {{ thong_tin_truong.dia_chi }}</p>
      </div>
      <div class="thuc_don_title text-center">
        <h2 class="text-uppercase font-weight-bolder">
          thực đơn ăn trong tuần
        </h2>
        <p style="font-size: 24px">
          Tuần lễ từ {{ new Date(thuc_dons[0].ngay).getDate() }} -
          {{ new Date(thuc_dons[0].ngay).getMonth() + 1 }} -
          {{ new Date(thuc_dons[0].ngay).getFullYear() }} đến ngày
          {{ new Date(thuc_dons[amount_of_date - 1].ngay).getDate() }} -
          {{ new Date(thuc_dons[amount_of_date - 1].ngay).getMonth() + 1 }} -
          {{ new Date(thuc_dons[amount_of_date - 1].ngay).getFullYear() }}
        </p>
      </div>

      <table class="align-middle mt-5">
        <tr class="text-center bg-secondary text-dark">
          <th rowspan="2">Thứ</th>
          <th rowspan="2" colspan="2">Món sáng</th>
          <th colspan="4">Món trưa</th>
          <th colspan="2" rowspan="2">Món xế</th>
          <th colspan="2">Chế độ chăm sóc</th>
        </tr>
        <tr class="text-center bg-secondary text-dark">
          <th>Món canh</th>
          <th>Món cơm</th>
          <th>Món mặn</th>
          <th>Tráng miệng</th>
          <th>SDD</th>
          <th>DC-BP</th>
        </tr>
        <tr
          v-for="(item, index) in thuc_dons.slice(0, amount_of_date)"
          :key="index"
        >
          <template
            v-if="
              item.an_sang_suat_an ||
              item.an_trua_suat_an ||
              item.an_xe_suat_an ||
              item.an_chieu_suat_an
            "
          >
            <td>
              Thứ {{ index + 2 }} <br />
              <b>{{
                new Date(item.ngay).getDate() +
                "/" +
                (new Date(item.ngay).getMonth() + 1)
              }}</b>
            </td>
            <td
              v-for="(mon_an, index) in item.an_sang_suat_an.split(',', 2)"
              :key="mon_an"
              class="p-2 text-center"
            >
              {{ mon_an }}
            </td>
            <!-- <td class="px-2 text-break">{{ item.an_trua_suat_an }}</td> -->
            <td
              v-for="(mon_an, index) in item.an_trua_suat_an.split(',', 4)"
              :key="mon_an"
              class="p-2 text-center"
            >
              {{ mon_an }}
            </td>
            <td
              v-for="(mon_an, index) in item.an_xe_suat_an.split(',', 2)"
              :key="mon_an"
              class="p-2 text-center"
            >
              {{ mon_an }}
            </td>
            <td
              v-for="(mon_an, index) in item.an_chieu_suat_an.split(',', 2)"
              :key="mon_an"
              class="p-2 text-center"
            >
              {{ mon_an }}
            </td>
          </template>
        </tr>
      </table>
      <div class="container mt-4" style="font-size: 14px">
        <div class="row text-center">
          <div class="col-6">
            Cấp dưỡng nấu chính:
            {{ findById(nhan_vien_nau_ans, filter.cap_duong_nau_chinh) }}
          </div>
          <div class="col-6">
            Người duyệt thực đơn:
            {{ nguoi_duyet_thuc_don }}
          </div>
        </div>
      </div>
    </div>
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
import { formatString, formatMoney } from "../functions/formatFunctions";

export default {
  name: "thuc-don",
  components: {
    multiselect,
    MainModal,
    FormWrapper,
    FooterQlmn,
  },
  mixins: [mixin],
  data() {
    return {
      filter: {
        ngay: new Date(),
        age: "",
        cap_duong_nau_chinh: "",
        id_coso: null,
      },
      cosos: [],
      id_coso: null,
      grades: [],
      thuc_dons: [
        {
          id: "",
          ngay: "",
          bua_sang: "",
          an_sang_suat_an: "",
          bua_trua: "",
          an_trua_suat_an: "",
          bua_xe: "",
          an_xe_suat_an: "",
          bua_chieu: "",
          an_chieu_suat_an: "",
          so_luong: "",
          tc_chi: "",
          da_chi: "",
        },
        {
          id: "",
          ngay: "",
          bua_sang: "",
          an_sang_suat_an: "",
          bua_trua: "",
          an_trua_suat_an: "",
          bua_xe: "",
          an_xe_suat_an: "",
          bua_chieu: "",
          an_chieu_suat_an: "",
          so_luong: "",
          tc_chi: "",
          da_chi: "",
        },
        {
          id: "",
          ngay: "",
          bua_sang: "",
          an_sang_suat_an: "",
          bua_trua: "",
          an_trua_suat_an: "",
          bua_xe: "",
          an_xe_suat_an: "",
          bua_chieu: "",
          an_chieu_suat_an: "",
          so_luong: "",
          tc_chi: "",
          da_chi: "",
        },
        {
          id: "",
          ngay: "",
          bua_sang: "",
          an_sang_suat_an: "",
          bua_trua: "",
          an_trua_suat_an: "",
          bua_xe: "",
          an_xe_suat_an: "",
          bua_chieu: "",
          an_chieu_suat_an: "",
          so_luong: "",
          tc_chi: "",
          da_chi: "",
        },
        {
          id: "",
          ngay: "",
          bua_sang: "",
          an_sang_suat_an: "",
          bua_trua: "",
          an_trua_suat_an: "",
          bua_xe: "",
          an_xe_suat_an: "",
          bua_chieu: "",
          an_chieu_suat_an: "",
          so_luong: "",
          tc_chi: "",
          da_chi: "",
        },
        {
          id: "",
          ngay: "",
          bua_sang: "",
          an_sang_suat_an: "",
          bua_trua: "",
          an_trua_suat_an: "",
          bua_xe: "",
          an_xe_suat_an: "",
          bua_chieu: "",
          an_chieu_suat_an: "",
          so_luong: "",
          tc_chi: "",
          da_chi: "",
        },
      ],
      suat_ans: [],
      amount_of_date: 6,
      thong_tin_truong: {
        name: "",
        dia_chi: "",
      },
      nguoi_duyet_thuc_don: "",
      nhan_vien_nau_ans: [],
    };
  },
  methods: {
    fetchData() {
      let ngay = this.filter.ngay ? this.formatDate(this.filter.ngay) : "";
      axios
        .post("/dataThucDons", {
          ngay: ngay,
          age: this.filter.age,
          id_coso: this.filter.id_coso,
        })
        .then((response) => {
          if (response.data.status == true) {
            this.amount_of_date = response.data.amount_of_date;
            this.thuc_dons.slice(0, this.amount_of_date);
            for (let i = 0; i < response.data.items.length; i++) {
              let suat_an_sang = this.suat_ans.find(
                (item) => item.id == response.data.items[i].bua_sang
              );
              let suat_an_trua = this.suat_ans.find(
                (item) => item.id == response.data.items[i].bua_trua
              );
              let suat_an_xe = this.suat_ans.find(
                (item) => item.id == response.data.items[i].bua_xe
              );
              let suat_an_chieu = this.suat_ans.find(
                (item) => item.id == response.data.items[i].bua_chieu
              );
              this.thuc_dons[i].id = response.data.items[i].id;
              this.thuc_dons[i].ngay = response.data.items[i].date;
              this.thuc_dons[i].bua_sang = response.data.items[i].bua_sang
                ? Number(response.data.items[i].bua_sang)
                : "";
              this.thuc_dons[i].an_sang_suat_an = suat_an_sang
                ? suat_an_sang.data
                : "";
              this.thuc_dons[i].bua_trua = response.data.items[i].bua_trua
                ? Number(response.data.items[i].bua_trua)
                : "";
              this.thuc_dons[i].an_trua_suat_an = suat_an_trua
                ? suat_an_trua.data
                : "";
              this.thuc_dons[i].bua_xe = response.data.items[i].bua_xe
                ? Number(response.data.items[i].bua_xe)
                : "";
              this.thuc_dons[i].an_xe_suat_an = suat_an_xe
                ? suat_an_xe.data
                : "";
              this.thuc_dons[i].bua_chieu = response.data.items[i].bua_chieu
                ? Number(response.data.items[i].bua_chieu)
                : "";
              this.thuc_dons[i].an_chieu_suat_an = suat_an_chieu
                ? suat_an_chieu.data
                : "";
              this.thuc_dons[i].so_luong = response.data.items[i].so_luong;
              this.tinhTongTien(this.thuc_dons[i]);
              this.thuc_dons[i].da_chi = response.data.items[i].da_chi
                ? response.data.items[i].da_chi
                : 0;
            }
            this.nguoi_duyet_thuc_don = response.data.nguoi_duyet;
            this.filter.cap_duong_nau_chinh = response.data.nguoi_nau;
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
    formatDate(date) {
      var d = new Date(date),
        month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear();

      if (month.length < 2) month = "0" + month;
      if (day.length < 2) day = "0" + day;

      return [year, month, day].join("-");
    },
    findById(arr, id) {
      var result = null;
      if (id) {
        result = arr.find((item) => item.id == id);
      }
      return result ? result.name : "";
    },
    handleSubmit() {
      axios
        .post("/submitThucDon", {
          array: this.thuc_dons,
          grade: this.filter.age,
          nguoi_nau: this.filter.cap_duong_nau_chinh,
          id_coso: this.filter.id_coso,
        })
        .then((response) => {
          if (response.data.status) {
            this.fetchData();
            this.$notify({
              title: "Thông báo",
              message: `Cập nhật thực đơn thành công!`,
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
    // Làm phần thực đơn
    async getGrades() {
      await axios
        .get("/dataGrades")
        .then((response) => {
          if (response.data.status == true) {
            this.grades = response.data.grades;
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
    async getSuatAns() {
      await axios
        .post("/dataSuatAn", this.filter)
        .then((response) => {
          if (response.data.status) {
            this.suat_ans = response.data.items;
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
    changeSuatAn(item, bua_an, name) {
      let suat_an = this.suat_ans.find((a) => a.id == item[bua_an]);
      item[name + "_suat_an"] = suat_an.data;
      this.tinhTongTien(item);
    },
    tinhTongTien(item) {
      let suat_an_sang = this.suat_ans.find((a) => a.id == item.bua_sang);
      let suat_an_trua = this.suat_ans.find((a) => a.id == item.bua_trua);
      let suat_an_xe = this.suat_ans.find((a) => a.id == item.bua_xe);
      let suat_an_chieu = this.suat_ans.find((a) => a.id == item.bua_chieu);
      let tien_an_sang = suat_an_sang ? suat_an_sang.tong_tien : 0;
      let tien_an_trua = suat_an_trua ? suat_an_trua.tong_tien : 0;
      let tien_an_xe = suat_an_xe ? suat_an_xe.tong_tien : 0;
      let tien_an_chieu = suat_an_chieu ? suat_an_chieu.tong_tien : 0;
      item.tc_chi =
        (Number(tien_an_sang) +
          Number(tien_an_trua) +
          Number(tien_an_xe) +
          Number(tien_an_chieu)) *
        item.so_luong;
    },
    inThucDon() {
      let doc = document.getElementById("table_thuc_don_print");
      doc.className = "table_thuc_don_print  p-5 bg-white print-enable";
      window.print();
      doc.className = "table_thuc_don_print  p-5 bg-white";
    },
    async getThongTinTruong() {
      await axios
        .get("/dataInfo/" + this.filter.id_coso)
        .then((response) => {
          if (response.data.status) {
            this.thong_tin_truong.name = response.data.item.school_name;
            this.thong_tin_truong.dia_chi = response.data.item.address;
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
    async getNhanVienNauAn() {
      await axios
        .get("getNhanVienNauAn", {
          params: {
            id_coso: this.filter.id_coso,
          },
        })
        .then((response) => {
          if (response.data.status) {
            this.nhan_vien_nau_ans = response.data.items;
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
    Promise.all([
      this.getGrades(),
      this.getCosos(),
      this.getSuatAns(),
      this.getNhanVienNauAn(),
    ]).then(async () => {
      await this.getThongTinTruong();
      this.fetchData();
    });
  },
  watch: {
    "filter.ngay": function () {
      this.fetchData();
    },
    "filter.age": function () {
      this.fetchData();
    },
    "filter.id_coso": async function () {
      this.getThongTinTruong();
      this.getNhanVienNauAn();
      if (this.filter.id_coso) {
        await this.getSuatAns();
        this.fetchData();
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

.table_thuc_don {
  th,
  td {
    border-collapse: collapse;
    border: 1px solid black;
    text-align: center;
    vertical-align: top;
  }
  td:first-child {
    width: 100px;
  }
  th:nth-child(6),
  td:nth-child(6) {
    width: 60px;
  }
}

.table_thuc_don_print {
  width: 11.69in;
  height: 8in;
  display: none;
  background: #fff;
  table {
    width: 100%;
    height: 5in;
    th,
    td {
      border-collapse: collapse;
      border: 1px solid black;
      width: calc((11.69in - 96px - 100px) / 4);
    }
    th:first-child,
    td:first-child {
      width: 100px;
      text-align: center;
      // width: 100%;
    }
  }
}

@media print {
  .table_thuc_don_print.print-enable {
    display: block !important;
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

.table-record {
  .el-table td,
  .el-table th.is-leaf {
    border: 0.5px solid #ebeef5;
  }
}
</style>