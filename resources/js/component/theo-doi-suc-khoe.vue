<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Theo dõi phát triển thể chất - nhận thức</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <el-button type="primary" size="small" @click="handleUpdate()"
                >Cập nhật</el-button
              >
              <el-button type="" size="small" @click="handleCancel()"
                >Hủy</el-button
              >
            </nav>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card filter_form">
            <div class="wrap-filter">
              <!-- <div class="form-group-input">
                <span class="label-input flex_basis_225">Chọn tháng năm: </span>
                <el-select
                  class="thang"
                  v-model="filter.month"
                  placeholder="Tháng"
                  style="width: 80px"
                >
                  <el-option
                    v-for="item in months"
                    :key="item"
                    :label="item"
                    :value="item"
                  >
                  </el-option>
                </el-select>
                <el-select v-model="filter.year" placeholder="Năm">
                  <el-option
                    v-for="item in years"
                    :key="item"
                    :label="item"
                    :value="item"
                  >
                  </el-option>
                </el-select>
              </div> -->
              <div class="form-group-input">
                <span class="label-input">Tháng/năm: </span>
                <el-date-picker
                  v-model="filter.month"
                  type="month"
                  placeholder="Chọn tháng/năm"
                  format="MM/yyyy"
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
                <el-select v-model="filter.id_class" placeholder="Lớp">
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
            :frameworkComponents="frameworkComponents"
          >
          </ag-grid-vue>
        </div>
      </div>
    </div>

    <footer-qlmn></footer-qlmn>
  </div>
</template>

<script>
import axios from "axios";
import mixin from "./mixin.js";
import "ag-grid-community/dist/styles/ag-grid.css";
import "ag-grid-community/dist/styles/ag-theme-alpine.css";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
import footerQLMN from "../layouts/footer-qlmn.vue";
import { errorMessage } from "../errors/error-code";
import CheckboxCellRenderer from "./checkbox-cell-renderer.js";
export default {
  name: "theo-doi-suc-khoe",
  mixins: [mixin],
  components: {
    "ag-grid-vue": AgGridVue,
    "footer-qlmn": footerQLMN,
  },
  data: function () {
    return {
      frameworkComponents: null,
      school_years: [],
      grades: [],
      filter: {
        school_year: "",
        grade: "",
        id_class: "",
        month: 1,
        year: "",
      },
      lops: [],
      arraySelected: [],
      rowsChanged: [],
      viewPrint: false,
      boxCheckAll: false,
      months: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
      years: [],
      columnDefs: [
        {
          headerName: "ID",
          field: "ma_hs",
          minWidth: 100,
          headerCheckboxSelection: true,
          headerCheckboxSelectionFilteredOnly: true,
          checkboxSelection: true,
        },
        {
          headerName: "Tên học sinh",
          field: "name",
        },
        {
          headerName: "Tháng tuổi",
          field: "months_old",
          valueParser: numberParser,
        },
        {
          headerName: "H-Tiêu chuẩn (cm)",
          field: "height_standard",
          valueParser: numberParser,
        },
        {
          headerName: "Chiều cao (cm)",
          field: "height",
          valueParser: numberParser,
          editable: true,
        },
        {
          headerName: "Đánh giá chiều cao",
          field: "evaluate_height",
          editable: true,
        },
        {
          headerName: "W-Tiêu chuẩn (kg)",
          field: "weigth_standard",
          valueParser: numberParser,
        },
        {
          headerName: "Cân nặng (kg)",
          field: "weigth",
          valueParser: numberParser,
          editable: true,
        },
        {
          headerName: "Đánh giá cân nặng",
          field: "evaluate_weigth",
          editable: true,
        },
        {
          headerName: "Đánh giá sức khỏe/thể chất",
          field: "evaluate_physical",
          editable: true,
          minWidth: 250,
          // valueParser: numberParser,
        },
        {
          headerName: "Phát triển nhận thức",
          field: "cognitive_development",
          editable: true,
          // valueParser: numberParser,
        },
        {
          headerName: "Phát triển năng khiếu",
          field: "talent_development",
          editable: true,
          // valueParser: numberParser,
        },
        {
          headerName: "Phát triển tình cảm và quan hệ xã hội",
          field: "relationship_development",
          // valueParser: numberParser,
          editable: true,
          minWidth: 280,
        },
        {
          headerName: "Phát triển ngôn ngữ",
          field: "language_development",
          editable: true,
          // valueParser: numberParser,
        },
        {
          headerName: "Bé khỏe",
          field: "be_khoe",
          cellRenderer: "checkboxRenderer",
          editable: true,
          minWidth: 100,
        },
        {
          headerName: "Bé ngoan",
          field: "be_ngoan",
          cellRenderer: "checkboxRenderer",
          editable: true,
          minWidth: 100,
        },
      ],
      rowData: [],
      gridApi: null,
      columnApi: null,
      defaultColDef: {
        flex: 1,
        minWidth: 200,
        resizable: true,
      },
      undoRedoCellEditing: true,
      undoRedoCellEditingLimit: 5,
      rowSelection: "multiple",
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
      console.log(event.data);
      this.rowsChanged.push(event.data);
    },
    onSelectionChanged() {
      this.arraySelected = this.gridApi.getSelectedRows();
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
        await axios
          .post("dataTheoDoiSucKhoe", this.filter)
          .then((response) => {
            if (response.data.status) {
              this.rowData = response.data.dataTheoDoiSucKhoes;
            }
          })
          .catch();
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
        this.filter.month == "" ||
        this.filter.school_year == "" ||
        this.filter.grade == "" ||
        this.filter.id_class == ""
      ) {
        return false;
      }
      return true;
    },

    handleCancel() {
      this.rowsChanged = [];
      this.fetchData();
    },

    async handleUpdate() {
      await axios
        .post("updateTheoDoiSucKhoe", {
          rowsChanged: this.rowsChanged,
          filter: this.filter,
        })
        .then((response) => {
          if (response.data.status) {
            this.fetchData();
            this.$notify({
              title: "Thông báo",
              message: `Cập nhật sổ theo dõi sức khỏe thành công!`,
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
  },
  async created() {
    this.filter.month = new Date();
    Promise.all([this.getGrades(), this.getAllSchoolYears()]).then(async () => {
      await this.getClasses();
      this.filter.id_class = this.lops.length ? this.lops[0].id : "";
    });
  },
  beforeMount() {
    this.frameworkComponents = {
      checkboxRenderer: CheckboxCellRenderer,
    };
  },
  watch: {
    "filter.month": function () {
      if (this.filter.id_class) {
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
    "filter.id_class": function () {
      this.fetchData();
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
    // .grid {
    //     padding: 1.25rem;
    //     margin-top: 15px;
    //     box-shadow: 0 0 6px #ccc;
    //     background: #fff;
    #table_student_fee {
      width: 100%;
      height: 500px;
    }
    // }
    //     .el-table {
    //       box-shadow: 0 0 2px #ccc;
    //       border-radius: 5px;
    //       .el-input__inner {
    //         width: 155px;
    //       }
    //       .el-table_1_column_3 {
    //         // display: flex;
    //         .cell {
    //           width: 100%;
    //         }
    //       }
    //       .el-table_1_column_2  {
    //         .cell {
    //           text-align: left;
    //         }
    //       }
    //     }
    //   }
    //   .el-table td, .el-table th.is-leaf {
    //         border: 0.5px solid #EBEEF5;
    //     }
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
