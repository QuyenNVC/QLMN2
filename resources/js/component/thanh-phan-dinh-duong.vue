<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Quản lý thành phần dinh dưỡng</h4>
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
        <div class="col-12">
          <div class="card">
            <div class="wrap-filter">
              <div class="col row">
                <div class="col-lg-3 col-sm-6" v-if="!id_coso">
                  <div class="form-group-input justify-content-start">
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
                <div class="col-lg-4 col-sm-6">
                  <div class="form-group-input justify-content-start">
                    <span class="label-input">Nhóm thực phẩm: </span>
                    <el-select
                      v-model="filter.nhom_thuc_pham"
                      placeholder="Nhóm thực phẩm"
                    >
                      <el-option value="" label="Tất cả"></el-option>
                      <el-option
                        v-for="item in nhom_thuc_phams"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id"
                      >
                      </el-option>
                    </el-select>
                  </div>
                </div>
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
import footerQLMN from "../layouts/footer-qlmn.vue";
import { errorMessage } from "../errors/error-code";
import CheckboxCellRenderer from "./checkbox-cell-renderer.js";

export default {
  name: "thanh-phan-dinh-duong",
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
        nhom_thuc_pham: "",
        id_coso: null,
      },
      id_coso: "",
      cosos: [],
      loading: false,
      nhom_thuc_phams: [],
      page: 1,
      pageSize: 10,
      total: 0,
      arraySelected: [],
      rowsChanged: [],
      viewPrint: false,
      boxCheckAll: false,
      columnDefs: [
        {
          headerCheckboxSelection: true,
          headerCheckboxSelectionFilteredOnly: true,
          checkboxSelection: true,
          editable: false,
        },
        {
          headerName: "Mã nhóm thực phẩm",
          field: "nhom_thuc_pham_name",
          width: 300,
          cellEditor: "agSelectCellEditor",
          cellEditorParams: () => {
            return {
              values: this.nhom_thuc_phams.map((item) => item.name),
            };
          },
        },
        {
          headerName: "Tên thực phẩm",
          field: "name",
          valueParser: "",
          width: 200,
        },
        {
          headerName: "Nguồn từ động vật",
          field: "nguon_dong_vat",
          cellRenderer: "checkboxRenderer",
          editable: false,
        },
        {
          headerName: "Quy đổi về kg",
          field: "quy_doi_ve_kg",
        },
        {
          headerName: "Đơn vị tính",
          field: "don_vi_tinh",
          valueParser: "",
        },
        {
          headerName: "Giá thị trường",
          field: "gia_thi_truong",
        },
        {
          headerName: "Tỷ lệ hấp thu",
          field: "ty_le_hap_thu",
        },
        {
          headerName: "Tỷ lệ thải",
          field: "ty_le_thai",
        },
        {
          headerName: "Năng lượng",
          field: "nang_luong",
        },
        {
          headerName: "Nước",
          field: "nuoc",
        },
        {
          headerName: "Protid (g)",
          field: "protid",
        },
        {
          headerName: "Lipid (g)",
          field: "lipid",
        },
        {
          headerName: "Glucid (g)",
          field: "glucid",
          editable: true,
        },
        {
          headerName: "Cellulose (g)",
          field: "cellulose",
        },
        {
          headerName: "Cholesterol (g)",
          field: "cholesterol",
        },
        {
          headerName: "Canxi (mg)",
          field: "can_xi",
        },
        {
          headerName: "Phốt pho (mg)",
          field: "phot_pho",
        },
        {
          headerName: "Sắt (mg)",
          field: "sat",
        },
        {
          headerName: "Caroten (mg)",
          field: "caroten",
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
        minWidth: 110,
        resizable: true,
        editable: true,
        valueParser: numberParser,
        suppressSizeToFit: false,
      },
      undoRedoCellEditing: true,
      undoRedoCellEditingLimit: 5,
      rowSelection: "multiple",
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
      if (event.data.nhom_thuc_pham_name) {
        event.data.nhom_thuc_pham = this.nhom_thuc_phams.find(
          (a) => a.name == event.data.nhom_thuc_pham_name
        ).id;
      }
      if (event.data.nhom_thuc_pham == "" && event.data.name == "") {
        this.$notify({
          title: "Thông báo",
          message: "Nhóm thức phẩm và tên thực phẩm không được bỏ trống",
          type: "warning",
        });
      }
      if (event.data.newRow) {
        event.data.newRow = false;
        this.rowsChanged.push(event.data);
        let newRowData = {
          id: "",
          nhom_thuc_pham: "",
          name: "",
          nguon_dong_vat: "",
          quy_doi_ve_kg: "",
          don_vi_tinh: "",
          gia_thi_truong: "",
          ty_le_hap_thu: "",
          ty_le_thai: "",
          nang_luong: "",
          nuoc: "",
          protid: "",
          lipid: "",
          glucid: "",
          cellulose: "",
          cholesterol: "",
          can_xi: "",
          phot_pho: "",
          sat: "",
          caroten: "",
          cholesterol: "",
          vitammin_a: "",
          vitamin_b1: "",
          vitamin_b2: "",
          vitamin_b3: "",
          vitamin_c: "",
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
    },
    onSelectionChanged() {
      this.arraySelected = this.gridApi.getSelectedRows();
    },
    // END GRID'S METHODS
    formatBeforeSend(item) {
      if (item.nhom_thuc_pham_name) {
        item = {
          ...item,
          nhom_thuc_pham: this.nhom_thuc_phams.find(
            (a) => a.name == item.nhom_thuc_pham_name
          ).id,
        };
        return item;
      }
    },
    setPage(val) {
      this.page = val;
      this.fetchData();
    },

    fetchData() {
      this.rowsChanged = [];
      axios
        .post("dataThanhPhanDinhDuong", {
          ...this.filter,
          pageSize: this.pageSize,
          page: this.page,
        })
        .then((response) => {
          if (response.data.status) {
            this.rowData = response.data.items;
            this.rowData = this.rowData.map((item) => {
              return {
                ...item,
                nhom_thuc_pham_name: this.findById(
                  this.nhom_thuc_phams,
                  item.nhom_thuc_pham
                ),
              };
            });
            let newRowData = {
              id: "",
              nhom_thuc_pham: "",
              name: "",
              nguon_dong_vat: "",
              quy_doi_ve_kg: "",
              don_vi_tinh: "",
              gia_thi_truong: "",
              ty_le_hap_thu: "",
              ty_le_thai: "",
              nang_luong: "",
              nuoc: "",
              protid: "",
              lipid: "",
              glucid: "",
              cellulose: "",
              cholesterol: "",
              can_xi: "",
              phot_pho: "",
              sat: "",
              caroten: "",
              cholesterol: "",
              vitammin_a: "",
              vitamin_b1: "",
              vitamin_b2: "",
              vitamin_b3: "",
              vitamin_c: "",
              note: "",
              newRow: true,
            };
            this.rowData.push(newRowData);
            this.total = response.data.total;
          }
        })
        .catch();
    },

    getNhomThucPhams() {
      axios
        .get("dataNhomThucPham")
        .then((response) => {
          if (response.data.status) {
            this.nhom_thuc_phams = response.data.items;
          }
        })
        .catch();
    },

    handleCancel() {
      this.rowsChanged = [];
      this.setPage(1);
    },

    async handleUpdate() {
      await axios
        .post("updateThanhPhanDinhDuong", {
          rowsChanged: this.rowsChanged,
          id_coso: this.filter.id_coso,
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
        .post("deleteThanhPhanDinhDuong", { arr: this.arraySelected })
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
    Promise.all([this.getCosos(), this.getNhomThucPhams()]).then(() => {
      this.fetchData();
    });
    // this.getCosos();
    // this.getNhomThucPhams();
    // this.fetchData();
  },
  beforeMount() {
    this.frameworkComponents = {
      checkboxRenderer: CheckboxCellRenderer,
    };
  },
  watch: {
    "filter.nhom_thuc_pham": function () {
      this.setPage(1);
    },
    "filter.id_coso": function () {
      this.setPage(1);
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

  @media print {
    .print-enable {
      display: block !important;
    }
  }
}
</style>
