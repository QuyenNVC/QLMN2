<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Danh mục tiêu chuẩn phát triển</h4>
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
        <div class="col-12 form">
          <div class="form-group-input">
            <span class="label-input">Liệt kê theo độ tuổi: </span>
            <el-select v-model="age">
              <el-option label="Tất cả" value="all"></el-option>
              <el-option label="0" value="0"></el-option>
              <el-option label="1" value="1"></el-option>
              <el-option label="2" value="2"></el-option>
              <el-option label="3" value="3"></el-option>
              <el-option label="4" value="4"></el-option>
              <el-option label="5" value="5"></el-option>
            </el-select>
          </div>
        </div>
        <el-tabs type="border-card" v-model="activeName">
          <el-tab-pane
            id="tab-0"
            name="height"
            label="Chiều cao (cm)"
            class="grid"
          >
            <!-- <div class="grid-wrapper">
                            <ag-grid-vue
                                class="ag-theme-alpine table_standard"
                                :columnDefs="columnDefs"
                                :rowData="rowData"
                                @grid-ready="onGridReady"
                                :defaultColDef="defaultColDef"
                                @cell-value-changed="onCellValueChanged"
                                :undoRedoCellEditing="undoRedoCellEditing"
                                :undoRedoCellEditingLimit="
                                    undoRedoCellEditingLimit
                                "
                            >
                            </ag-grid-vue>
                        </div> -->
          </el-tab-pane>
          <el-tab-pane id="tab-1" name="weigth" label="Cân nặng (kg)">
            <!-- <div class="grid-wrapper">
                            <ag-grid-vue
                                class="ag-theme-alpine table_standard"
                                :columnDefs="columnDefsWeigth"
                                :rowData="rowData"
                                @grid-ready="onGridReady"
                                :defaultColDef="defaultColDef"
                                @cell-value-changed="onCellValueChanged"
                                :undoRedoCellEditing="undoRedoCellEditing"
                                :undoRedoCellEditingLimit="
                                    undoRedoCellEditingLimit
                                "
                            >
                            </ag-grid-vue>
                        </div> -->
          </el-tab-pane>
          <el-tab-pane id="tab-2" name="bmi" label="Chỉ số cơ thể (BMI)">
            <!-- <div class="grid-wrapper">
                            <ag-grid-vue
                                class="ag-theme-alpine table_standard"
                                :columnDefs="columnDefsBMI"
                                :rowData="rowData"
                                @grid-ready="onGridReady"
                                :defaultColDef="defaultColDef"
                                @cell-value-changed="onCellValueChanged"
                                :undoRedoCellEditing="undoRedoCellEditing"
                                :undoRedoCellEditingLimit="
                                    undoRedoCellEditingLimit
                                "
                            >
                            </ag-grid-vue>
                        </div> -->
          </el-tab-pane>
        </el-tabs>

        <div class="col-12 d-flex grid">
          <div class="grid-wrapper">
            <ag-grid-vue
              class="ag-theme-alpine table_standard"
              :columnDefs="columnTable"
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
import { errorMessage } from "../errors/error-code";
export default {
  name: "tieu-chuan-phat-trien",
  mixins: [mixin],
  components: {
    "ag-grid-vue": AgGridVue,
  },
  data: function () {
    return {
      activeName: "height",
      age: "all",
      rowsChanged: [],
      columnTable: [],
      // GRID'S props
      columnDefsHEIGHT: [
        {
          headerName: "Tuổi",
          field: "age",
          editable: false,
        },
        {
          headerName: "Tháng tuổi",
          field: "month",
          editable: false,
          minWidth: 120,
        },
        {
          headerName: "Thấp còi độ 2",
          children: [
            {
              headerName: "Trai",
              children: [
                {
                  headerName: "(1)",
                  field: "male_1",
                },
                {
                  headerName: "(2)",
                  field: "male_2",
                },
              ],
            },
            {
              headerName: "Gái",
              children: [
                {
                  headerName: "(1)",
                  field: "female_1",
                },
                {
                  headerName: "(2)",
                  field: "female_2",
                },
              ],
            },
          ],
        },
        {
          headerName: "Thấp còi độ 1",
          children: [
            {
              headerName: "Trai",
              children: [
                {
                  headerName: "(1)",
                  field: "male_3",
                },
                {
                  headerName: "(2)",
                  field: "male_4",
                },
              ],
            },
            {
              headerName: "Gái",
              children: [
                {
                  headerName: "(1)",
                  field: "female_3",
                },
                {
                  headerName: "(2)",
                  field: "female_4",
                },
              ],
            },
          ],
        },
        {
          headerName: "Phát triển bình thường",
          children: [
            {
              headerName: "Trai",
              children: [
                {
                  headerName: "(1)",
                  field: "male_5",
                },
                {
                  headerName: "(2)",
                  field: "male_6",
                },
              ],
            },
            {
              headerName: "Gái",
              children: [
                {
                  headerName: "(1)",
                  field: "female_5",
                },
                {
                  headerName: "(2)",
                  field: "female_6",
                },
              ],
            },
          ],
        },
        {
          headerName: "Quá giới hạn",
          children: [
            {
              headerName: "Trai",
              children: [
                {
                  headerName: "(1)",
                  field: "male_7",
                },
                {
                  headerName: "(2)",
                  field: "male_8",
                },
              ],
            },
            {
              headerName: "Gái",
              children: [
                {
                  headerName: "(1)",
                  field: "female_7",
                },
                {
                  headerName: "(2)",
                  field: "female_8",
                },
              ],
            },
          ],
        },
      ],
      columnDefsWEIGTH: [
        {
          headerName: "Tuổi",
          field: "age",
          editable: false,
        },
        {
          headerName: "Tháng tuổi",
          field: "month",
          editable: false,
          minWidth: 120,
        },
        {
          headerName: "Suy dinh dưỡng độ 2",
          children: [
            {
              headerName: "Trai",
              children: [
                {
                  headerName: "(1)",
                  field: "male_1",
                },
                {
                  headerName: "(2)",
                  field: "male_2",
                },
              ],
            },
            {
              headerName: "Gái",
              children: [
                {
                  headerName: "(1)",
                  field: "female_1",
                },
                {
                  headerName: "(2)",
                  field: "female_2",
                },
              ],
            },
          ],
        },
        {
          headerName: "Suy dinh dưỡng độ 1",
          children: [
            {
              headerName: "Trai",
              children: [
                {
                  headerName: "(1)",
                  field: "male_3",
                },
                {
                  headerName: "(2)",
                  field: "male_4",
                },
              ],
            },
            {
              headerName: "Gái",
              children: [
                {
                  headerName: "(1)",
                  field: "female_3",
                },
                {
                  headerName: "(2)",
                  field: "female_4",
                },
              ],
            },
          ],
        },
        {
          headerName: "Bình thường",
          children: [
            {
              headerName: "Trai",
              children: [
                {
                  headerName: "(1)",
                  field: "male_5",
                },
                {
                  headerName: "(2)",
                  field: "male_6",
                },
              ],
            },
            {
              headerName: "Gái",
              children: [
                {
                  headerName: "(1)",
                  field: "female_5",
                },
                {
                  headerName: "(2)",
                  field: "female_6",
                },
              ],
            },
          ],
        },
        {
          headerName: "Béo phì độ 1",
          children: [
            {
              headerName: "Trai",
              children: [
                {
                  headerName: "(1)",
                  field: "male_7",
                },
                {
                  headerName: "(2)",
                  field: "male_8",
                },
              ],
            },
            {
              headerName: "Gái",
              children: [
                {
                  headerName: "(1)",
                  field: "female_7",
                },
                {
                  headerName: "(2)",
                  field: "female_8",
                },
              ],
            },
          ],
        },
        {
          headerName: "Béo phì độ 2",
          children: [
            {
              headerName: "Trai",
              children: [
                {
                  headerName: "(1)",
                  field: "male_9",
                },
                {
                  headerName: "(2)",
                  field: "male_10",
                },
              ],
            },
            {
              headerName: "Gái",
              children: [
                {
                  headerName: "(1)",
                  field: "female_9",
                },
                {
                  headerName: "(2)",
                  field: "female_10",
                },
              ],
            },
          ],
        },
      ],
      columnDefsBMI: [
        {
          headerName: "Tuổi",
          field: "age",
          editable: false,
        },
        {
          headerName: "Tháng tuổi",
          field: "month",
          editable: false,
          minWidth: 120,
        },
        {
          headerName: "Thiếu cân (gầy/ốm)",
          children: [
            {
              headerName: "Trai",
              children: [
                {
                  headerName: "(1)",
                  field: "male_1",
                },
                {
                  headerName: "(2)",
                  field: "male_2",
                },
              ],
            },
            {
              headerName: "Gái",
              children: [
                {
                  headerName: "(1)",
                  field: "female_1",
                },
                {
                  headerName: "(2)",
                  field: "female_2",
                },
              ],
            },
          ],
        },
        {
          headerName: "Bình thường",
          children: [
            {
              headerName: "Trai",
              children: [
                {
                  headerName: "(1)",
                  field: "male_3",
                },
                {
                  headerName: "(2)",
                  field: "male_4",
                },
              ],
            },
            {
              headerName: "Gái",
              children: [
                {
                  headerName: "(1)",
                  field: "female_3",
                },
                {
                  headerName: "(2)",
                  field: "female_4",
                },
              ],
            },
          ],
        },
        {
          headerName: "Nguy cơ béo phì",
          children: [
            {
              headerName: "Trai",
              children: [
                {
                  headerName: "(1)",
                  field: "male_5",
                },
                {
                  headerName: "(2)",
                  field: "male_6",
                },
              ],
            },
            {
              headerName: "Gái",
              children: [
                {
                  headerName: "(1)",
                  field: "female_5",
                },
                {
                  headerName: "(2)",
                  field: "female_6",
                },
              ],
            },
          ],
        },
        {
          headerName: "Béo phì",
          children: [
            {
              headerName: "Trai",
              children: [
                {
                  headerName: "(1)",
                  field: "male_7",
                },
                {
                  headerName: "(2)",
                  field: "male_8",
                },
              ],
            },
            {
              headerName: "Gái",
              children: [
                {
                  headerName: "(1)",
                  field: "female_7",
                },
                {
                  headerName: "(2)",
                  field: "female_8",
                },
              ],
            },
          ],
        },
      ],
      rowData: [],
      gridApi: null,
      columnApi: null,
      defaultColDef: {
        flex: 1,
        minWidth: 70,
        resizable: true,
        valueParser: numberParser,
        editable: true,
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
    onCellValueChanged(event) {
      // console.log("data after changes is: ", event.data);
      this.rowsChanged.push(event.data);
    },
    // END GRID'S METHODS

    changeTable(table) {
      this.columnTable = this["columnDefs" + table.toUpperCase()];
      this.rowsChanged = [];
      this.fetchData();
    },

    async handleUpdate() {
      console.log(this.rowsChanged);
      await axios
        .post("updatePhysicalStandard", {
          table: this.activeName,
          rowsChanged: this.rowsChanged,
        })
        .then((response) => {
          if (response.data.status) {
            this.fetchData();
            this.$notify({
              title: "Thông báo",
              message: `Cập nhật định mức tiêu chuẩn thành công!`,
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

    handleCancel() {
      this.rowsChanged = [];
      this.fetchData();
    },

    async fetchData() {
      await axios
        .post("getPhysicalStandard", {
          age: this.age,
          table: this.activeName,
        })
        .then((response) => {
          if (response.data.status) {
            this.rowData = response.data.physicalStandard;
          }
        })
        .catch();
    },
  },
  created() {
    this.columnTable = this.columnDefsHEIGHT;
    this.fetchData();
  },
  watch: {
    activeName: function () {
      // console.log(this.activeName);
      // this.fetchData();
      // this.rowsChanged = [];
      // console.log(this.row);
      this.changeTable(this.activeName);
    },
    age: function () {
      this.fetchData();
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
    box-shadow: 0 0 6px #ccc;
    background: #fff;
    .grid-wrapper {
      flex: 1 1 0px;
      height: 500px;
      .table_standard {
        width: 100%;
        height: 100%;
        .ag-header-group-cell-label,
        .ag-header-cell-label {
          justify-content: center;
        }
      }
    }
  }
  .el-tabs__content {
    display: none;
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
