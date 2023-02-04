<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý thu học phí</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <el-button type="primary" size="small" @click="inThongBao()"
                >In thông báo đóng học phí</el-button
              >
              <el-button type="success" size="small" @click="inPhieuThu()"
                >In phiếu thu học phí</el-button
              >
              <el-button
                type="warning"
                size="small"
                @click="tinhHocPhiHangThang = true"
                >Học phí hàng tháng</el-button
              >
              <el-button
                type="warning"
                size="small"
                @click="tinhHocPhiHangThang = false"
                >Học phí tháng này</el-button
              >
              <el-button type="warning" size="small" @click="tinhHocPhi()"
                >Tính học phí</el-button
              >
            </nav>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="wrap-filter">
              <div class="form-group-input">
                <span class="label-input">Tháng/năm: </span>
                <el-date-picker
                  v-model="filter.month"
                  type="month"
                  placeholder="Chọn tháng/năm"
                  format="MM/yyyy"
                ></el-date-picker>
              </div>
              <div class="form-group-input"  v-if="!id_coso">
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
              <div class="form-group-input">
                <span class="label-input">Khối lớp: </span>
                <el-select
                  v-model="filter.grade"
                  placeholder="Khối lớp"
                  filterable
                >
                  <el-option
                    v-for="grade in grades"
                    :key="grade.id"
                    :label="grade.name"
                    :value="grade.id"
                  >
                  </el-option>
                </el-select>
              </div>
              <div class="form-group-input">
                <span class="label-input">Lớp: </span>
                <el-select v-model="filter.class" placeholder="Lớp" filterable>
                  <el-option
                    v-for="lop in lops"
                    :key="lop.id"
                    :label="lop.name"
                    :value="lop.id"
                  >
                  </el-option>
                </el-select>
              </div>
            </div>
          </div>
        </div>
        <div class="wrap-table">
          <ag-grid-vue
            id="table_student_fee"
            class="ag-theme-alpine"
            :columnDefs="columnDefs"
            :rowData="rowData"
            @grid-ready="onGridReady"
            :defaultColDef="defaultColDef"
            @cell-value-changed="onCellValueChanged"
            :undoRedoCellEditing="undoRedoCellEditing"
            :undoRedoCellEditingLimit="undoRedoCellEditingLimit"
            :suppressRowClickSelection="true"
            :rowSelection="rowSelection"
            @selection-changed="onSelectionChanged"
            v-if="tinhHocPhiHangThang"
          >
          </ag-grid-vue>
          <ag-grid-vue
            id="table_student_fee"
            class="ag-theme-alpine"
            :columnDefs="columnDefsHienTai"
            :rowData="rowData"
            @grid-ready="onGridReady"
            :defaultColDef="defaultColDef"
            @cell-value-changed="onCellValueChanged"
            :undoRedoCellEditing="undoRedoCellEditing"
            :undoRedoCellEditingLimit="undoRedoCellEditingLimit"
            :suppressRowClickSelection="true"
            :rowSelection="rowSelection"
            @selection-changed="onSelectionChanged"
            v-else
          >
          </ag-grid-vue>
        </div>
      </div>
    </div>

    <!-- footer -->
    <!-- ============================================================== -->
    <footer-qlmn></footer-qlmn>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
    <div class="print-template" id="template-phieu-thu">
      <div class="container" v-for="item in listPhieuThu" :key="item.id">
        <div class="row">
          <div class="col-6">
            <div class="col-8" v-if="id_coso">
              <div class="d-flex align-items-center">
                <img
                  :src="info.urlLogo"
                  alt=""
                  width="50"
                  height="50"
                  class="mr-5"
                />
                <div>
                  <p class="mb-0" style="font-size: 10px">
                    {{ info.school_name }}
                  </p>
                  <p class="mb-0" style="font-size: 10px">
                    {{ info.address }}
                  </p>
                </div>
              </div>
            </div>
            <div class="print-title text-center text-uppercase">
              <h3>BIÊN LAI THU TIỀN</h3>
              <span class="title-print-day"
                >Ngày {{ printDay }} tháng {{ printMonth }} năm
                {{ printYear }}</span
              >
            </div>
            <p>Họ, tên học sinh: {{ item.name }}</p>
            <p>Địa chỉ: {{ item.address }}</p>
            <div class="row">
              <div class="col-6">
                <p>
                  Lớp: {{ lops.find((item) => item.id == filter.class).name }}
                </p>
              </div>
              <div class="col-6">
                <p>Tháng: {{returnMoment(filter.month).format("MM/YYYY")}}</p>
              </div>
            </div>
            <p>Nội dung thu, trong đó:</p>
            <p>
              + Học phí:
              {{ item["printHocPhi"] ? item["printHocPhi"] + " đồng" : "" }}
            </p>
            <p>
              + DVCT: {{ item["printDVCT"] ? item["printDVCT"] + " đồng" : "" }}
            </p>
            <p>
              + Miễn giảm:
              {{
                item["family_allowances"]
                  ? item["family_allowances"] + " đồng"
                  : ""
              }}
            </p>
            <p>
              + Khấu trừ tiền ăn:
              {{
                item["khau_tru_tien_an"]
                  ? item["khau_tru_tien_an"] + " đồng"
                  : ""
              }}
            </p>
            <p>
              + Nợ tháng trước :
              {{ item["debt"] ? item["debt"] + " đồng" : "" }}
            </p>
            <p>Tổng số tiền thu: {{ item["tam_tinh"] }} đồng</p>
            <div
              v-if="
                typeof item['hoan_tien'] != 'undefined' &&
                typeof item['thu_them'] != 'undefined'
              "
            >
              <p>Đã thu: {{ item["paid"] ? item["paid"] + " đồng" : "" }}</p>
              <p>
                {{
                  item["hoan_tien"]
                    ? "Hoàn tiền: " + item["hoan_tien"] + " đồng"
                    : "Thu thêm: " + item["thu_them"] + " đồng"
                }}
              </p>
            </div>
            <!-- <p>Viết bằng chữ: {{DocTienBangChu(feesPrint['Tổng cộng'])}}</p> -->
            <div class="row">
              <div class="col-4 text-center">
                <p>Phụ huynh học sinh</p>
              </div>
              <div class="col-4 text-center">
                <p>Kế toán</p>
              </div>
              <div class="col-4 text-center">
                <p>Thủ quỹ</p>
              </div>
              <div class="col-4 text-center">
                <i>(Ký, họ tên)</i>
              </div>
              <div class="col-4 text-center">
                <i>(Ký, họ tên)</i>
              </div>
              <div class="col-4 text-center">
                <i>(Ký, họ tên)</i>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="col-8" v-if="id_coso">
              <div class="d-flex align-items-center">
                <img
                  :src="info.urlLogo"
                  alt=""
                  width="50"
                  height="50"
                  class="mr-5"
                />
                <div>
                  <p class="mb-0" style="font-size: 10px">
                    {{ info.school_name }}
                  </p>
                  <p class="mb-0" style="font-size: 10px">
                    {{ info.address }}
                  </p>
                </div>
              </div>
            </div>
            <div class="print-title text-center text-uppercase">
              <h3>BIÊN LAI THU TIỀN</h3>
              <span class="title-print-day"
                >Ngày {{ printDay }} tháng {{ printMonth }} năm
                {{ printYear }}</span
              >
            </div>
            <p>Họ, tên học sinh: {{ item.name }}</p>
            <p>Địa chỉ: {{ item.address }}</p>
            <div class="row">
              <div class="col-6">
                <p>
                  Lớp: {{ lops.find((item) => item.id == filter.class).name }}
                </p>
              </div>
              <div class="col-6">
                <p>Tháng: {{returnMoment(filter.month).format("MM/YYYY")}}</p>
              </div>
            </div>
            <p>Nội dung thu, trong đó:</p>
            <p>
              + Học phí:
              {{ item["printHocPhi"] ? item["printHocPhi"] + " đồng" : "" }}
            </p>
            <p>
              + DVCT: {{ item["printDVCT"] ? item["printDVCT"] + " đồng" : "" }}
            </p>
            <p>
              + Miễn giảm:
              {{
                item["family_allowances"]
                  ? item["family_allowances"] + " đồng"
                  : ""
              }}
            </p>
            <p>
              + Khấu trừ tiền ăn:
              {{
                item["khau_tru_tien_an"]
                  ? item["khau_tru_tien_an"] + " đồng"
                  : ""
              }}
            </p>
            <p>
              + Nợ tháng trước :
              {{ item["debt"] ? item["debt"] + " đồng" : "" }}
            </p>
            <p>Tổng số tiền thu: {{ item["tam_tinh"] }} đồng</p>
            <div
              v-if="
                typeof item['hoan_tien'] != 'undefined' &&
                typeof item['thu_them'] != 'undefined'
              "
            >
              <p>Đã thu: {{ item["paid"] ? item["paid"] + " đồng" : "" }}</p>
              <p>
                {{
                  item["hoan_tien"]
                    ? "Hoàn tiền: " + item["hoan_tien"] + " đồng"
                    : "Thu thêm: " + item["thu_them"] + " đồng"
                }}
              </p>
            </div>
            <!-- <p>Viết bằng chữ: {{DocTienBangChu(feesPrint['Tổng cộng'])}}</p> -->
            <div class="row">
              <div class="col-4 text-center">
                <p>Phụ huynh học sinh</p>
              </div>
              <div class="col-4 text-center">
                <p>Kế toán</p>
              </div>
              <div class="col-4 text-center">
                <p>Thủ quỹ</p>
              </div>
              <div class="col-4 text-center">
                <i>(Ký, họ tên)</i>
              </div>
              <div class="col-4 text-center">
                <i>(Ký, họ tên)</i>
              </div>
              <div class="col-4 text-center">
                <i>(Ký, họ tên)</i>
              </div>
            </div>
          </div>
        </div>
        <i style="display: inline-block; position: absolute; bottom: 10px"
          >Đề nghị quý phụ huynh đóng hp trước ngày 10 hàng tháng</i
        >
      </div>
    </div>

    <div class="print-template" id="template-thong-bao">
      <div class="container" v-for="item in listThongBao" :key="item.id">
        <div class="row">
          <div class="col-7" v-if="id_coso">
            <div class="d-flex align-items-center">
              <img
                :src="info.urlLogo"
                alt=""
                width="75"
                height="75"
                class="mr-5"
              />
              <div>
                <p class="mb-0" style="font-size: 12px">
                  {{ info.school_name }}
                </p>
                <p class="mb-0" style="font-size: 12px">{{ info.address }}</p>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="print-title text-center text-uppercase">
              <h3>
                GIẤY THÔNG BÁO ĐÓNG TIỀN HỌC PHÍ</br>THÁNG
                {{ returnMoment(filter.month).format('MM/YYYY') }}
              </h3>
              <span class="title-print-day"
                >Ngày {{ printDay }} tháng {{ printMonth }} năm
                {{ printYear }}</span
              >
            </div>
            <br />
            <p>
              K/g phụ huynh học sinh: <b>{{ item.name }}</b>
            </p>
            <p>Địa chỉ: {{ item.address }}</p>
            <div class="row">
              <div class="col-6">
                <p>
                  Lớp: {{ lops.find((item) => item.id == filter.class).name }}
                </p>
              </div>
              <div class="col-6">
                <p>Tháng: {{returnMoment(filter.month).format("MM/YYYY")}}</p>
              </div>
            </div>
            <p>Nội dung thu, trong đó:</p>
            <p>
              + Học phí:
              {{ item["printHocPhi"] ? item["printHocPhi"] + " đồng" : "" }}
            </p>
            <p>
              + DVCT: {{ item["printDVCT"] ? item["printDVCT"] + " đồng" : "" }}
            </p>
            <p>
              + Miễn giảm:
              {{
                item["family_allowances"]
                  ? item["family_allowances"] + " đồng"
                  : ""
              }}
            </p>
            <p>
              + Khấu trừ tiền ăn:
              {{
                item["khau_tru_tien_an"]
                  ? item["khau_tru_tien_an"] + " đồng"
                  : ""
              }}
            </p>
            <p>
              + Nợ tháng trước :
              {{ item["debt"] ? item["debt"] + " đồng" : "" }}
            </p>
            <p>Tổng số tiền thu: {{ item["tam_tinh"] }} đồng</p>
            <div
              v-if="
                typeof item['hoan_tien'] != 'undefined' &&
                typeof item['thu_them'] != 'undefined'
              "
            >
              <p>Đã thu: {{ item["paid"] ? item["paid"] + " đồng" : "" }}</p>
              <p>
                {{
                  item["hoan_tien"]
                    ? "Hoàn tiền: " + item["hoan_tien"] + " đồng"
                    : "Thu thêm: " + item["thu_them"] + " đồng"
                }}
              </p>
            </div>
            <div style="margin-top: 50px">
              <h6>
                Đề nghị quý phụ huynh đóng học phí trước ngày 10 hàng tháng
              </h6>
              <i style="display: block"
                >(Phụ huynh cần mang theo giấy thông báo đi nộp tiền)</i
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import mixin from "./mixin.js";
import "ag-grid-community/dist/styles/ag-grid.css";
import "ag-grid-community/dist/styles/ag-theme-alpine.css";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
import { errorMessage } from "../errors/error-code";
import FooterQlmn from "../layouts/footer-qlmn.vue";
export default {
  name: "student-fee",
  mixins: [mixin],
  components: {
    "ag-grid-vue": AgGridVue,
    FooterQlmn,
  },
  // props: ["id_coso"],
  data: function () {
    return {
      school_years: [],
      grades: [],
      isShowCreateTitle: false,
      isErrorForm: false,
      titleMessage: "",
      gradeUpdate: "",
      yearUpdate: "",
      annualFees: [],
      tableData: [],
      page: 1,
      pageSize: 10,
      dataFilter: [],
      search: "",
      pickerBeginDateBefore: {
        disabledDate(time) {
          return time.getTime() + 86400000 < Date.now();
        },
      },
      filter: {
        school_year: "",
        grade: "",
        class: "",
        month: 1,
        year: "",
        id_coso: null,
      },
      cosos: [],
      id_coso: null,
      resetCheckbox: 1,
      lops: [],
      feesPrint: {},
      printDay: 1,
      printMonth: 1,
      printYear: 2022,
      listPhieuThu: [],
      listThongBao: [],
      arraySelected: [],
      viewPrint: false,
      boxCheckAll: false,
      columnDefs: [
        {
          headerName: "ID",
          field: "ma_hs",
          // minWidth: 200,
          headerCheckboxSelection: true,
          headerCheckboxSelectionFilteredOnly: true,
          checkboxSelection: true,
        },
        {
          headerName: "Tên học sinh",
          field: "name",
          minWidth: 200,
        },
        {
          headerName: "Học phí",
          field: "hoc_phi",
          // valueParser: numberParser,
        },
        {
          headerName: "Tiền ăn",
          field: "tien_an",
          // valueParser: numberParser,
        },
        {
          headerName: "Tổng các loại học phí khác theo tháng",
          field: "monthly_fees",
          width: 300,
          // valueParser: numberParser,
        },
        {
          headerName: "Tổng các loại học phí khác theo ngày",
          field: "daily_fees",
          width: 300,
          // valueParser: numberParser,
        },
        {
          headerName: "Đầu năm",
          field: "yearly_fees",
          // valueParser: numberParser,
        },
        {
          headerName: "Dịch vụ cộng thêm",
          field: "service_fees",
          // valueParser: numberParser,
        },
        {
          headerName: "Tiền giảm trừ",
          field: "family_allowances",
          //   valueParser: numberParser,
        },
        {
          headerName: "Nợ tháng trước",
          field: "debt",
          // valueParser: numberParser,
        },
        {
          headerName: "Khấu trừ tiền ăn",
          field: "khau_tru_tien_an",
          // valueParser: numberParser,
        },
        {
          headerName: "TỔNG (PHẢI THU)",
          field: "tam_tinh",
          // valueParser: numberParser,
        },
        {
          headerName: "ĐÃ THU",
          field: "paid",
          // valueParser: numberParser,
          editable: true,
        },
        {
          headerName: "CÒN NỢ",
          field: "con_no",
          // valueParser: numberParser,
        },
        {
          headerName: "NGÀY THU",
          field: "payment_date",
          type: ["dateColumn"],
          editable: true,
        },
        {
          headerName: "Ghi chú",
          field: "note",
        },
      ],
      rowData: [],
      gridApi: null,
      columnApi: null,
      defaultColDef: {
        minWidth: 110,
        resizable: true,
        suppressSizeToFit: false,
      },
      undoRedoCellEditing: true,
      undoRedoCellEditingLimit: 5,
      rowSelection: "multiple",
      tinhHocPhiHangThang: true,
      columnDefsHienTai: [
        {
          headerName: "ID",
          field: "ma_hs",
          headerCheckboxSelection: true,
          headerCheckboxSelectionFilteredOnly: true,
          checkboxSelection: true,
        },
        {
          headerName: "Tên học sinh",
          field: "name",
          minWidth: 200,
        },
        {
          headerName: "Học phí",
          field: "hoc_phi",
        },
        {
          headerName: "Tiền ăn",
          field: "tien_an",
        },
        {
          headerName: "Tổng các loại học phí khác theo tháng",
          field: "monthly_fees",
          width: 300,
        },
        {
          headerName: "Tổng các loại học phí khác theo ngày",
          field: "daily_fees",
          width: 300,
        },
        {
          headerName: "Đầu năm",
          field: "yearly_fees",
        },
        {
          headerName: "Dịch vụ cộng thêm",
          field: "service_fees",
        },
        {
          headerName: "Tiền giảm trừ",
          field: "family_allowances",
        },
        {
          headerName: "Nợ tháng trước",
          field: "debt",
        },
        {
          headerName: "Khấu trừ tiền ăn",
          field: "khau_tru_tien_an",
        },
        {
          headerName: "TỔNG (PHẢI THU)",
          field: "tam_tinh",
        },
        {
          headerName: "ĐÃ THU",
          field: "paid",
        },
        {
          headerName: "THU THÊM",
          field: "thu_them",
        },
        {
          headerName: "HOÀN TIỀN",
          field: "hoan_tien",
        },
        {
          headerName: "NGÀY THU",
          field: "payment_date",
          type: ["dateColumn"],
          editable: true,
        },
        {
          headerName: "Ghi chú",
          field: "note",
        },
      ],
      info: {
        urlLogo: "",
        school_name: "",
        address: "",
      },
    };
  },
  methods: {
    // GRID'S METHODS
    autoSizeAll(skipHeader) {
      const allColumnIds = [];
      this.gridColumnApi.getAllColumns().forEach((column) => {
        allColumnIds.push(column.getId());
      });
      this.gridColumnApi.autoSizeColumns(allColumnIds, skipHeader);
    },
    onBtStopEditing() {
      this.gridApi.stopEditing();
    },
    onBtStartEditing(key, char, pinned) {
      this.gridApi.setFocusedCell(0, "lastName", pinned);
      this.gridApi.startEditingCell({
        rowIndex: 0,
        colKey: "lastName",
        rowPinned: pinned,
        keyPress: key,
        charPress: char,
      });
    },
    onBtNextCell() {
      this.gridApi.tabToNextCell();
    },
    onBtPreviousCell() {
      this.gridApi.tabToPreviousCell();
    },
    onBtWhich() {
      var cellDefs = this.gridApi.getEditingCells();
      if (cellDefs.length > 0) {
        var cellDef = cellDefs[0];
        console.log(
          "editing cell is: row = " +
            cellDef.rowIndex +
            ", col = " +
            cellDef.column.getId() +
            ", floating = " +
            cellDef.rowPinned
        );
      } else {
        console.log("no cells are editing");
      }
    },
    onGridReady(params) {
      this.gridApi = params.api;
      this.gridColumnApi = params.columnApi;
      autoSizeAll(true);
    },
    async onCellValueChanged(event) {
      await axios
        .post("dongHocPhi", {
          ma_hs: event.data.ma_hs,
          tam_tinh: Number(event.data.tam_tinh.replace(/,/g, "")),
          paid: Number(event.data.paid.replace(/,/g, "")),
          month: this.filter.month,
          id_coso: this.filter.id_coso,
          // year: this.filter.year,
        })
        .then((response) => {
          if (response.data.status) {
            this.$notify({
              title: "Thông báo",
              message: `Học sinh ${event.data.name} đóng học phí tháng ${moment(
                this.filter.month
              ).format("MM/YYYY")} thành công!`,
              type: "success",
            });
            this.fetchData();
          } else {
            this.$notify({
              title: "Thông báo",
              message: response.data.message,
              type: "warning",
            });
            this.fetchData();
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
    onSelectionChanged() {
      this.arraySelected = this.gridApi.getSelectedRows();
      if (this.tinhHocPhiHangThang) {
        this.listPhieuThu = this.arraySelected.filter(
          (row) => row.payment_date != ""
        );
        this.listThongBao = this.arraySelected.filter(
          (row) => row.payment_date == ""
        );
      } else {
        this.listPhieuThu = this.arraySelected;
        this.listThongBao = this.arraySelected;
      }
    },
    // END GRID'S METHODS
    async tinhHocPhi() {
      if (this.validateFilter()) {
        await axios
          .post("tinhHocPhi", this.filter)
          .then((response) => {
            if (response.data.status) {
              this.$notify({
                title: "Thông báo",
                message: response.data.message,
                type: "success",
              });
              this.fetchData();
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
          message: "Vui lòng chọn đầy đủ các trường dữ liệu",
          type: "warning",
        });
      }
    },
    inThongBao() {
      let doc = document.getElementById("template-thong-bao");
      doc.className = "print-template print-enable";
      window.print();
      doc.className = "print-template";
    },
    inPhieuThu() {
      let doc = document.getElementById("template-phieu-thu");
      doc.className = "print-template print-enable";
      window.print();
      doc.className = "print-template";
    },
    setPrintDay() {
      var curDay = new Date();
      this.printDay = curDay.getDate();
      this.printMonth = curDay.getMonth() + 1;
      this.printYear = curDay.getFullYear();
    },
    async getClasses() {
      await axios
        .post("/getClassesDiemDanh", this.filter)
        .then((promise) => {
          if (promise.data.status) {
            this.lops = promise.data.lops;
          }
        })
        .catch();
    },
    setDateOfMonth() {
      if (this.filter.year != "" && this.filter.month != "") {
        return moment(
          this.filter.year + "-" + this.filter.month,
          "YYYY-MM"
        ).daysInMonth();
      }
      return 0;
    },
    setDateOfLastMonth() {
      if (this.filter.year != "" && this.filter.month != "") {
        if (this.filter.month == 1) {
          return 31;
        }
        return moment(
          this.filter.year + "-" + this.filter.month,
          "YYYY-MM"
        ).daysInMonth();
      }
      return 0;
    },
    async fetchData() {
      if (this.validateFilter()) {
        if (this.tinhHocPhiHangThang) {
          await axios
            .post("/getStudentsFees", this.filter)
            .then((response) => {
              if (response.data.status) {
                response.data.student_fees = response.data.student_fees.map(
                  (item) => {
                    let printHocPhi =
                      Number(item.hoc_phi) +
                      Number(item.tien_an) +
                      Number(item.monthly_fees) +
                      Number(item.yearly_fees);
                    item = { ...item, printHocPhi: printHocPhi };

                    let printDVCT =
                      Number(item.daily_fees) + Number(item.service_fees);
                    item = { ...item, printDVCT: printDVCT };
                    for (let key in item) {
                      item[key] =
                        item[key] == 0 || item[key] == null ? "" : item[key];
                      item[key] = isNaN(item[key])
                        ? item[key]
                        : this.formatMoney(item[key]);
                    }

                    return item;
                  }
                );
                this.rowData = response.data.student_fees;
              }
            })
            .catch();
        } else {
          await axios
            .post("hocPhiThangNay", this.filter)
            .then((response) => {
              if (response.data.status) {
                response.data.student_fees = response.data.student_fees.map(
                  (item) => {
                    let printHocPhi =
                      Number(item.hoc_phi) +
                      Number(item.tien_an) +
                      Number(item.monthly_fees) +
                      Number(item.yearly_fees);
                    item = { ...item, printHocPhi: printHocPhi };

                    let printDVCT =
                      Number(item.daily_fees) + Number(item.service_fees);
                    item = { ...item, printDVCT: printDVCT };
                    for (let key in item) {
                      item[key] =
                        item[key] == 0 || item[key] == null ? "" : item[key];
                      item[key] = isNaN(item[key])
                        ? item[key]
                        : this.formatMoney(item[key]);
                    }

                    return item;
                  }
                );
                this.rowData = response.data.student_fees;
              }
            })
            .catch();
        }
      } else {
        this.$notify({
          title: "Thông báo",
          message: "Vui lòng chọn đầy đủ các trường dữ liệu",
          type: "warning",
        });
      }
    },
    formatMoney(num) {
      return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    },
    async getGrades() {
      await axios
        .get("dataGrades")
        .then((promise) => {
          if (promise.data.status) {
            this.grades = promise.data.grades;
            this.filter.grade = this.grades[0].id;
          }
        })
        .catch((rejected) => {});
    },

    validateFilter() {
      if (
        this.filter.month == "" ||
        // this.filter.year == "" ||
        // this.filter.school_year == "" ||
        this.filter.grade == "" ||
        this.filter.class == ""
      ) {
        return false;
      }
      return true;
    },
    async getInfo() {
      if (this.id_coso) {
        await axios
          .get("/dataInfo/" + this.filter.id_coso)
          .then((response) => {
            console.log(response.data.item);
            this.info = response.data.item;
          })
          .catch((e) => {
            this.$notify({
              title: "Thông báo",
              message: errorMessage[e.response.status],
              type: "warning",
            });
          });
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
    returnMoment(date) {
      return moment(date);
    },
  },
  async created() {
    this.filter.month = new Date();
    this.setPrintDay();
    Promise.all([this.getCosos(), this.getGrades(), this.getInfo()]).then(
      async () => {
        await this.getClasses();
        this.filter.class = this.lops.length ? this.lops[0].id : "";
      }
    );
  },
  watch: {
    "filter.month": function () {
      if (this.filter.class) {
        // if (this.filter.class && this.filter.year) {
        this.fetchData();
      }
    },
    "filter.grade": function () {
      if (this.filter.grade !== "" && this.filter.id_coso !== "") {
        this.filter.class = "";
        this.getClasses();
      }
    },
    "filter.id_coso": function () {
      if (this.filter.grade !== "" && this.filter.id_coso !== "") {
        this.filter.class = "";
        this.getClasses();
        this.getInfo();
      }
    },
    "filter.class": function () {
      if (this.filter.class) {
        this.fetchData();
      }
    },
    tinhHocPhiHangThang: function () {
      this.fetchData();
    },
  },
};
</script>

<style lang='scss'>
@media print {
  #main-wrapper > header,
  #main-wrapper > aside,
  #app > .page-wrapper > .page-breadcrumb,
  #app > .page-wrapper > .container-fluid,
  #app > .page-wrapper > footer {
    display: none;
  }

  .page-wrapper {
    margin-left: 0 !important;
  }
}
</style>

<style lang="scss" scoped>
::v-deep {
  .wrap-table {
    padding: 15px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 6px #ccc;
    #table_student_fee {
      width: 100%;
      height: 500px;
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
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 10px 0;
      .label-input {
        flex-basis: 150px;
      }
      .el-select {
        margin-right: 5px;
        &.chon-phong-ban {
          flex-basis: 40%;
        }
        &.thang {
          flex-basis: 35%;
          min-width: 65px;
        }
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

  .print-template {
    font-family: "Roboto Serif", sans-serif;
    display: none;
    .container {
      height: 5.83in;
      width: 8.27in;
      position: relative;
      background: #fff;
      & > .row > .col-6 {
        padding: 30px 15px;
      }
      p {
        font-size: 14px;
        margin-bottom: 6px;
      }
    }

    .title-print-day {
      font-style: italic;

      font-size: 12px;
    }
  }

  #template-thong-bao {
    &.print-template {
      .container {
        height: 8.27in;
        width: 5.83in;
        & > .row > .col-12 {
          padding: 30px 15px;
        }
        p {
          font-size: 18px;
          margin-bottom: 10px;
        }
      }
    }
  }

  @media print {
    .print-enable {
      display: block !important;
    }
  }
}
</style>