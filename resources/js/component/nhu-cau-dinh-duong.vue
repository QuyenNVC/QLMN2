<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý nhu cầu dinh dưỡng cho trẻ</h4>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
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
  name: "nhu-cau-dinh-duong",
  mixins: [mixin],
  components: {
    "ag-grid-vue": AgGridVue,
    FooterQlmn,
  },
  data: function () {
    return {
      form: {
        id: "",
        age: "",
        nang_luong: "",
        protein: "",
        can_xi: "",
        sat: "",
        vitammin_a: "",
        vitamin_b1: "",
        vitamin_b2: "",
        vitamin_b3: "",
        vitamin_c: "",
        note: "",
      },
      school_years: [],
      grades: [],
      tableData: [],
      // GRID'S props
      columnDefs: [
        {
          headerName: "Tuổi",
          field: "age_name",
          editable: false,
          cellClass: "text-left",
          minWidth: 200,
        },
        {
          headerName: "Năng lượng (kcal)",
          field: "nang_luong",
          minWidth: 180,
        },
        {
          headerName: "Protein (g)",
          field: "protein",
        },
        {
          headerName: "Canxi (g)",
          field: "can_xi",
        },
        {
          headerName: "Sắt (g)",
          field: "sat",
        },
        {
          headerName: "Vitamin A (mg)",
          field: "vitammin_a",
        },
        {
          headerName: "Vitamin B1 (mg)",
          field: "vitamin_b1",
        },
        {
          headerName: "Vitamin B2 (mg)",
          field: "vitamin_b2",
        },
        {
          headerName: "Vitamin B3 (mg)",
          field: "vitamin_b3",
        },
        {
          headerName: "Vitamin C (mg)",
          field: "vitamin_c",
        },
        {
          headerName: "Ghi chú",
          field: "note",
          valueParser: "",
        },
      ],
      rowData: [],
      gridApi: null,
      columnApi: null,
      defaultColDef: {
        flex: 1,
        minWidth: 10,
        resizable: true,
        editable: true,
        valueParser: numberParser,
        cellClass: "text-right",
        minWidth: 140,
      },
      undoRedoCellEditing: true,
      undoRedoCellEditingLimit: 5,
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
      // console.log(this.form);
      await axios
        .post("updateNhuCauDinhDuong", this.form)
        .then((response) => {
          // console.log(response);
          if (!response.data.status) {
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
    // END GRID'S METHODS

    async fetchData() {
      await axios
        .get("dataNhuCauDinhDuong")
        .then((response) => {
          if (response.data.status) {
            this.rowData = response.data.items;
            this.rowData = this.rowData.map((item) => {
              return {
                ...item,
                age_name: this.findById(this.grades, item.age),
              };
            });
          }
        })
        .catch();
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
    findById(arr, id) {
      var result = null;
      if (id) {
        result = arr.find((item) => item.id == id);
      }
      return result ? result.name : "";
    },
  },
  async created() {
    await this.getGrades();
    this.fetchData();
  },
};

window.numberParser = function numberParser(params) {
  if (params.newValue === "") return "";
  return isNaN(params.newValue) || params.newValue < 0
    ? params.oldValue
    : Number(params.newValue);
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