<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Nhật ký sức khỏe</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb"></nav>
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
                <span class="label-input">Chọn ngày: </span>
                <el-date-picker
                  v-model="filter.date"
                  type="date"
                  placeholder="Ngày đành giá"
                  format="dd/MM/yyyy"
                  :picker-options="pickerBeginDateBefore"
                ></el-date-picker>
              </div>
              <div class="form-group-input">
                <span class="label-input">Năm học: </span>
                <el-select v-model="filter.school_year" placeholder="Năm học">
                  <el-option
                    v-for="school_year in school_years"
                    :key="school_year.id"
                    :label="school_year.period"
                    :value="school_year.id"
                  >
                  </el-option>
                </el-select>
              </div>
              <div class="form-group-input">
                <span class="label-input">Khối lớp: </span>
                <el-select v-model="filter.grade" placeholder="Khối lớp">
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
                <el-select v-model="filter.class" placeholder="Lớp">
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
          >
          </ag-grid-vue>
        </div>
      </div>
    </div>

    <!-- footer -->
    <!-- ============================================================== -->
    <footer class="footer text-center">
      All Rights Reserved by SmartID. Designed and Developed by
      <a href="https://www.smartidads.com/">SmartID</a>.
    </footer>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
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
export default {
  name: "nhat-ky-suc-khoe",
  mixins: [mixin],
  components: {
    "ag-grid-vue": AgGridVue,
  },
  data: function () {
    return {
      school_years: [],
      grades: [],
      filter: {
        school_year: "",
        grade: "",
        class: "",
        date: "",
      },
      lops: [],
      arraySelected: [],
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
          // phải xử lý select box
          headerName: "Tình trạng",
          field: "tinh_trang",
          cellEditor: "agSelectCellEditor",
          cellEditorParams: {
            values: ["Tốt", "Bình thường", "Nguy hại", "Xấu"],
          },
          editable: true,
        },
        {
          headerName: "Ghi chú",
          field: "note",
          editable: true,
        },
      ],
      rowData: [],
      gridApi: null,
      columnApi: null,
      defaultColDef: {
        flex: 1,
        minWidth: 110,
        resizable: true,
      },
      undoRedoCellEditing: true,
      undoRedoCellEditingLimit: 5,
      rowSelection: "multiple",
      pickerBeginDateBefore: {
        disabledDate(time) {
          return time.getTime() >= Date.now();
        },
      },
      tinh_trangs: {
        1: "Tốt",
        2: "Bình thường",
        3: "Xấu",
        4: "Nguy hại",
      },
    };
  },
  methods: {
    // GRID'S METHODS
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
      params.api.sizeColumnsToFit();
    },
    async onCellValueChanged(event) {
      //  console.log('data after changes is: ', event.data);
      //  console.log(event.data.tam_tinh.replace(/,/g, ''));
      //  return false;
      await axios
        .post("updateNhatKySucKhoe", {
          ma_hs: event.data.ma_hs,
          tinh_trang: event.data.tinh_trang,
          note: event.data.note,
          date: this.formatDate(this.filter.date),
        })
        .then((response) => {
          if (response.data.status) {
            console.log("Cập nhật tình trạng sức khỏe thành công");
          }
        })
        .catch((e) => {
          this.$notify({
            title: "Thông báo",
            message: errorMessage[e.response.status],
            type: "warning",
          });
        });

      // await axios
      //     .post("dongHocPhi", {
      //         ma_hs: event.data.ma_hs,
      //         tam_tinh: Number(event.data.tam_tinh.replace(/,/g, "")),
      //         paid: Number(event.data.paid.replace(/,/g, "")),
      //         month: this.filter.month,
      //         year: this.filter.year
      //     })
      //     .then(response => {
      //         if (response.data.status) {
      //             console.log(
      //                 `Học sinh ${event.data.name} đóng học phí thàng công`
      //             );
      //             this.fetchData();
      //         }
      //     });
    },
    onSelectionChanged() {
      // this.arraySelected = this.gridApi.getSelectedRows();
      // if (this.tinhHocPhiHangThang) {
      //     this.listPhieuThu = this.arraySelected.filter(
      //         row => row.payment_date != ""
      //     );
      //     this.listThongBao = this.arraySelected.filter(
      //         row => row.payment_date == ""
      //     );
      // } else {
      //     this.listPhieuThu = this.arraySelected;
      //     this.listThongBao = this.arraySelected;
      // }
    },
    // END GRID'S METHODS

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
    async fetchData() {
      if (this.validateFilter()) {
        // this.filter.date = this.formatDate(this.filter.date);
        await axios
          .post("getDataNhatKySucKhoe", {
            class: this.filter.class,
            date: this.formatDate(this.filter.date),
          })
          .then((response) => {
            if (response.data.status) {
              // console.log(response.data.nhatKySucKhoes);
              this.rowData = [];
              for (let item of response.data.nhatKySucKhoes) {
                let temp = {
                  ma_hs: item.ma_hs,
                  name: item.name,
                };
                if (item.data) {
                  let data = JSON.parse(item.data);
                  let date = this.formatDate(this.filter.date);
                  date = date.split("-")[2];
                  console.log(data);
                  if (date in data) {
                    if (data[date].tinh_trang != "") {
                      temp = {
                        ...temp,
                        note: data[date].note,
                        tinh_trang: data[date].tinh_trang,
                      };
                    } else {
                      temp = {
                        ...temp,
                        note: item.date[date].note,
                        tinh_trang: "",
                      };
                    }
                  } else {
                    temp = {
                      ...temp,
                      note: "",
                      tinh_trang: "",
                    };
                  }
                } else {
                  temp = {
                    ...temp,
                    note: "",
                    tinh_trang: "",
                  };
                }
                this.rowData = [...this.rowData, temp];
              }
            }
          })
          .catch();
        // console.log("Đủ điều kiện");
      } else {
        this.$notify({
          title: "Thông báo",
          message: "Vui lòng chọn đầy đủ các trường dữ liệu",
          type: "warning",
        });
      }
    },
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
        this.filter.date == "" ||
        this.filter.school_year == "" ||
        this.filter.grade == "" ||
        this.filter.class == ""
      ) {
        return false;
      }
      return true;
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
  },
  async created() {
    this.filter.date = new Date();

    Promise.all([this.getGrades(), this.getAllSchoolYears()]).then(async () => {
      await this.getClasses();
      this.filter.class = this.lops.length ? this.lops[0].id : "";
    });
  },
  watch: {
    "filter.date": function () {
      if (this.filter.class) {
        this.fetchData();
      }
    },
    "filter.school_year": function () {
      if (this.filter.school_year !== "" && this.filter.grade !== "") {
        this.getClasses();
      }
    },
    "filter.grade": function () {
      if (this.filter.school_year !== "" && this.filter.grade !== "") {
        this.getClasses();
      }
    },
    "filter.class": function () {
      if (this.filter.date) {
        this.fetchData();
      }
    },
  },
};
</script>

<style lang="scss">
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
}
</style>
