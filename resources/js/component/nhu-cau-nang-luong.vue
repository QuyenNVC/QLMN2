<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý nhu cầu năng lượng cho trẻ</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <el-button type="primary" size="small" @click="handleUpdate()"
                >Cập nhật</el-button
              >
              <el-button type="" size="small" @click="handleCancel()"
                >Hủy</el-button
              >
              <el-button type="danger" size="small" @click="handleDelete()"
                >Xóa</el-button
              >
            </nav>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
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
          <el-pagination
            layout="prev, pager, next"
            :total="this.rowData.length"
            @current-change="setPage"
          >
          </el-pagination>
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
import { formatString } from "../functions/formatFunctions";
export default {
  name: "nhu-cau-nang-luong",
  mixins: [mixin],
  components: {
    "ag-grid-vue": AgGridVue,
    "footer-qlmn": footerQLMN,
  },
  data: function () {
    return {
      frameworkComponents: null,
      grades: [],
      page: 1,
      pageSize: 10,
      arraySelected: [],
      rowsChanged: [],
      boxCheckAll: false,
      columnDefs: [
        {
          headerCheckboxSelection: true,
          headerCheckboxSelectionFilteredOnly: true,
          checkboxSelection: true,
          editable: false,
        },
        {
          headerName: "Tên",
          field: "name",
          valueParser: "",
          minWidth: 200,
        },
        {
          headerName: "Khối lớp",
          field: "age_name",
          minWidth: 200,
          cellEditor: "agSelectCellEditor",
          cellEditorParams: () => {
            return {
              values: this.grades.map((item) => item.name),
            };
          },
        },
        {
          headerName: "Bữa ăn",
          field: "loai_suat_an_name",
          minWidth: 200,
          cellEditor: "agSelectCellEditor",
          cellEditorParams: () => {
            return {
              values: this.loai_suat_ans,
            };
          },
        },
        {
          headerName: "Năng lượng cả ngày (kcal)",
          field: "nang_luong_ca_ngay",
        },
        {
          headerName: "Năng lượng ở trường (kcal)",
          field: "nang_luong_o_truong",
          minWidth: 220,
        },
        {
          headerName: "Protid cả ngày (g)",
          field: "protid_ca_ngay",
        },
        {
          headerName: "Protid ở trường (g)",
          field: "protid_o_truong",
        },
        {
          headerName: "Lipid cả ngày (g)",
          field: "lipid_ca_ngay",
        },
        {
          headerName: "Lipid ở trường (g)",
          field: "lipid_o_truong",
        },
        {
          headerName: "Glucid cả ngày (g)",
          field: "glucid_ca_ngay",
        },
        {
          headerName: "Glucid ở trường (g)",
          field: "glucid_o_truong",
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
        minWidth: 200,
        resizable: true,
        editable: true,
        valueParser: numberParser,
      },
      undoRedoCellEditing: true,
      undoRedoCellEditingLimit: 5,
      rowSelection: "multiple",
      loai_suat_ans: ["Ăn sáng", "Ăn trưa", "Ăn xế", "Ăn chiều"],
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
      if (event.data.age_name) {
        event.data.age = this.grades.find(
          (a) => a.name == event.data.age_name
        ).id;
      }
      if (event.data.loai_suat_an_name) {
        event.data.loai_suat_an =
          this.loai_suat_ans.findIndex(
            (item) => item == event.data.loai_suat_an_name
          ) + 1;
      }
      if (event.data.age == "" && event.data.name == "") {
        this.$notify({
          title: "Thông báo",
          message: "Tên nhu cầu năng lượng và khối lớp không được để trống",
          type: "warning",
        });
      }
      if (event.data.newRow) {
        event.data.newRow = false;
        this.rowsChanged.push(event.data);
        let newRowData = {
          id: "",
          age: "",
          loai_suat_an: "",
          name: "",
          nang_luong_ca_ngay: "",
          nang_luong_o_truong: "",
          protid_ca_ngay: "",
          protid_o_truong: "",
          lipid_ca_ngay: "",
          lipid_o_truong: "",
          glucid_ca_ngay: "",
          glucid_o_truong: "",
          note: "",
          newRow: true,
        };
        this.rowData.push(newRowData);
      } else {
        let record = this.rowsChanged.includes(event.data);
        if (!record) {
          this.rowsChanged.push(event.data);
        }
      }
      // console.log(this.rowsChanged);
    },
    onSelectionChanged() {
      this.arraySelected = this.gridApi.getSelectedRows();
    },
    // END GRID'S METHODS
    setPage(val) {
      this.page = val;
    },

    fetchData() {
      axios
        .get("dataNhuCauNangLuong")
        .then((response) => {
          if (response.data.status) {
            this.rowData = response.data.items;
            this.rowData = this.rowData.map((item) => {
              return {
                ...item,
                age_name: this.findById(this.grades, item.age),
                loai_suat_an_name: item.loai_suat_an
                  ? this.loai_suat_ans[item.loai_suat_an - 1]
                  : "",
              };
            });
            console.log(this.rowData);
            let newRowData = {
              id: "",
              age: "",
              loai_suat_an: "",
              name: "",
              nang_luong_ca_ngay: "",
              nang_luong_o_truong: "",
              protid_ca_ngay: "",
              protid_o_truong: "",
              lipid_ca_ngay: "",
              lipid_o_truong: "",
              glucid_ca_ngay: "",
              glucid_o_truong: "",
              note: "",
              newRow: true,
            };
            this.rowData.push(newRowData);
          }
        })
        .catch();
    },

    getGrades() {
      axios
        .get("dataGrades")
        .then((response) => {
          if (response.data.status) {
            this.grades = response.data.grades;
          }
        })
        .catch();
    },

    handleCancel() {
      this.rowsChanged = [];
      this.fetchData();
    },

    async handleUpdate() {
      await axios
        .post("updateNhuCauNangLuong", {
          rowsChanged: this.rowsChanged,
        })
        .then((response) => {
          if (response.data.status) {
            this.fetchData();
            this.$notify({
              title: "Thông báo",
              message: `Cập nhật dữ liệu thành công!`,
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

    handleDelete() {
      axios
        .post("deleteNhuCauNangLuong", { arr: this.arraySelected })
        .then((response) => {
          if (response.data.status) {
            this.fetchData();
            this.$notify({
              title: "Thông báo",
              message: "Xóa dữ liệu thành công",
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
  beforeMount() {
    this.frameworkComponents = {
      checkboxRenderer: CheckboxCellRenderer,
    };
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

  @media print {
    .print-enable {
      display: block !important;
    }
  }
}
</style>
