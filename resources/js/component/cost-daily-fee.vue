<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý định mức theo ngày</h4>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-12 form">
          <div class="row">
            <div
              class="form-group-input col-lg-3 col-sm-6 col-12"
              v-if="!id_coso"
            >
              <span class="text-nowrap">Cơ sở: </span>&nbsp;&nbsp;&nbsp;&nbsp;
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
            <div class="form-group-input col-lg-3 col-sm-6 col-12">
              <span class="label-input">Năm học: </span>
              <el-select v-model="filter.school_year">
                <el-option
                  v-for="school_year in school_years"
                  :key="school_year.id"
                  :label="school_year.period"
                  :value="school_year.id"
                ></el-option>
              </el-select>
            </div>
          </div>
        </div>
        <div class="col-12 d-flex grid">
          <div class="grid-wrapper">
            <ag-grid-vue
              id="table_daily_fee"
              class="ag-theme-alpine"
              :columnDefs="columnDefs"
              :rowData="rowData"
              @grid-ready="onGridReady"
              :defaultColDef="defaultColDef"
              @cell-value-changed="onCellValueChanged"
              :undoRedoCellEditing="undoRedoCellEditing"
              :undoRedoCellEditingLimit="undoRedoCellEditingLimit"
            >
            </ag-grid-vue>
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
  </div>
</template>

<script>
import axios from "axios";
import mixin from "./mixin.js";
import "ag-grid-community/dist/styles/ag-grid.css";
import "ag-grid-community/dist/styles/ag-theme-alpine.css";
import { AgGridVue } from "ag-grid-vue";
import { errorMessage } from "../errors/error-code";
import FooterQlmn from "../layouts/footer-qlmn.vue";
export default {
  name: "cost-daily-fee",
  mixins: [mixin],
  components: {
    "ag-grid-vue": AgGridVue,
    FooterQlmn,
  },
  // props: ["id_coso"],
  data: function () {
    return {
      form: {
        id_class: "",
      },
      filter: {
        school_year: "",
        id_coso: "",
      },
      school_years: [],
      grades: [],
      tableData: [],
      // GRID'S props
      columnDefs: [{ field: "Lớp", minWidth: 150 }],
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
      cosos: [],
      id_coso: "",
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
      // console.log('data after changes is: ', event.data);
      var datas = event.data;
      for (var item in datas) {
        this.form[item] = datas[item];
      }
      console.log(this.form);
      await axios
        .post("updateCostDailyFee", this.form)
        .then((response) => {
          console.log(response);
          if (response.data.status) {
            this.$notify({
              title: "Thông báo",
              message: `Tạo thành công định mức hàng ngày cho lớp ${this.form["Lớp"]}!`,
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
    // END GRID'S METHODS

    async fetchData() {
      await axios
        .get("getCostDailyFee", {
          params: this.filter,
        })
        .then((response) => {
          if (response.data.status) {
            this.tableData = response.data.classes;
            this.rowData = [];
            for (var i = 0; i < this.tableData.length; i++) {
              var oneRowData = {
                id_class: this.tableData[i].id,
                Lớp: this.tableData[i].name,
              };
              for (var j = 0; j < this.dailyFees.length; j++) {
                var costDailyFeesItem = "";
                costDailyFeesItem = this.tableData[i].costDailyFees.find(
                  (costDailyFee) => costDailyFee.id_type == this.dailyFees[j].id
                );
                // oneRowData[this.dailyFees[j].name] = costDailyFeesItem ? costDailyFeesItem.cost : '';
                oneRowData[this.dailyFees[j].id] = costDailyFeesItem
                  ? costDailyFeesItem.cost
                  : "";
              }
              this.rowData.push(oneRowData);
            }
          }
        })
        .catch();
    },
    // formatMoney(num) {
    //     return String(num).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    // },
    // async getSchoolYears() {
    //   await axios
    //     .get("getSchoolYears")
    //     .then((promise) => {
    //       if (promise.data.status) {
    //         var currentDate = new Date();
    //         var month = currentDate.getMonth() + 1;
    //         var year =
    //           month > 6
    //             ? currentDate.getFullYear()
    //             : currentDate.getFullYear() - 1;
    //         this.school_years = promise.data.school_years.filter(function (e) {
    //           return e.name >= year;
    //         });
    //         console.log(this.school_years);
    //       }
    //     })
    //     .catch((rejected) => {});
    // },
    async getAllSchoolYears() {
      await axios
        .get("getSchoolYears")
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
          }
        })
        .catch((rejected) => {});
    },
    async getDailyFees() {
      await axios
        .get("dataDailyFees", {
          params: this.filter,
        })
        .then((response) => {
          if (response.data.status == true) {
            this.columnDefs = [{ field: "Lớp", minWidth: 150 }];
            this.dailyFees = response.data.dailyfees;
            for (let i = 0; i < this.dailyFees.length; i++) {
              this.columnDefs.push({
                headerName: this.dailyFees[i].name,
                field: "" + this.dailyFees[i].id + "",
                valueParser: numberParser,
                editable: true,
              });
              this.form[this.dailyFees[i].id] = "";
            }
          }
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
    Promise.all([this.getAllSchoolYears(), this.getCosos()]).then((result) => {
      this.getDailyFees();
    });
  },
  watch: {
    "filter.school_year": function () {
      if (this.filter.id_coso != "" && this.filter.school_year != "") {
        this.fetchData();
      }
    },
    "filter.id_coso": async function () {
      await this.getDailyFees();
      if (this.filter.id_coso != "" && this.filter.school_year != "") {
        this.fetchData();
      }
    },
  },
};
</script>

<style lang="scss" scoped>
::v-deep {
  .form {
    padding: 1.25rem;
    background: #fff;
  }

  .grid {
    padding: 1.25rem;
    margin-top: 15px;
    box-shadow: 0 0 6px #ccc;
    background: #fff;
    .grid-wrapper {
      flex: 1 1 0px;
      height: 500px;
      #table_daily_fee {
        width: 100%;
        height: 100%;
      }
    }
  }
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
      .el-table_1_column_2 {
        .cell {
          text-align: left;
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
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px 20%;
    .label-input {
      flex-basis: 150px;
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
</style>