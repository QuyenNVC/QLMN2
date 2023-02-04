<template>
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4>Quản lý suất ăn dinh dưỡng</h4>
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
              <el-button
                type="default"
                size="small"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                @click="handleCopy"
                >Copy</el-button
              >
              <el-button type="danger" size="mini" @click="handleDeleteAll"
                >Xóa</el-button
              >
            </nav>
          </div>
        </div>
      </div>
    </div>
    <main-modal widthModal="95%">
      <template>
        <div class="row">
          <div class="col-12">
            <form-wrapper>
              <template v-slot:form-title>{{ title }}</template>
              <template>
                <div class="row">
                  <div class="col-md-6 col-lg-3">
                    <div class="form-group-input">
                      <span class="label-input form-label required"
                        >Tên suất ăn:
                      </span>
                      <el-input
                        placeholder="Tên cơ sở vật chất"
                        v-model="form.name"
                      ></el-input>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-3">
                    <div class="form-group-input">
                      <span class="label-input form-label required"
                        >Độ tuổi:
                      </span>
                      <el-select v-model="form.age" placeholder="Độ tuổi">
                        <el-option
                          v-for="item in grades"
                          :key="item.id"
                          :value="item.id"
                          :label="item.name"
                        ></el-option>
                      </el-select>
                    </div>
                  </div>
                  <!-- <div class="col-md-6 col-lg-3">
                    <div class="form-group-input">
                      <span class="label-input">Nội dung: </span>
                      <el-input
                        type="text"
                        placeholder="Nội dung"
                        v-model="form.content"
                      ></el-input>
                    </div>
                  </div> -->
                  <div class="col-md-6 col-lg-3" v-if="!id_coso">
                    <div class="form-group-input">
                      <span class="label-input form-label required"
                        >Cơ sở:
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
                  <div class="col-md-6 col-lg-3">
                    <div class="form-group-input">
                      <span class="label-input form-label required"
                        >Loại suất ăn:
                      </span>
                      <el-select
                        v-model="form.loai_suat_an"
                        placeholder="Loại suất ăn"
                      >
                        <el-option
                          v-for="(item, index) in loai_suat_ans"
                          :key="index"
                          :value="index + 1"
                          :label="item"
                        ></el-option>
                      </el-select>
                    </div>
                  </div>
                </div>
              </template>
            </form-wrapper>
          </div>
          <div class="col-9">
            <form-wrapper>
              <template v-slot:form-title>Danh sách thực phẩm</template>
              <template>
                <ag-grid-vue
                  id="ag_grid_danh_sach_thuc_pham"
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
                  :key="renderTable"
                >
                </ag-grid-vue>
              </template>
            </form-wrapper>
            <form-wrapper>
              <template v-slot:form-title>Thành phần tỉ lệ dinh dưỡng</template>
              <template>
                <el-table :data="dataTableTieuChuan">
                  <el-table-column label="Năng lượng (Kcal)" align="center">
                    <el-table-column
                      label="Suất ăn"
                      prop="nang_luong.suat_an"
                      align="center"
                    ></el-table-column>
                    <el-table-column
                      label="Tiêu chuẩn"
                      prop="nang_luong.tieu_chuan"
                      align="center"
                    ></el-table-column>
                  </el-table-column>
                  <el-table-column label="Đạm (P) - (g)" align="center">
                    <el-table-column
                      label="Suất ăn"
                      prop="protid.suat_an"
                      align="center"
                    ></el-table-column>
                    <el-table-column
                      label="Tiêu chuẩn"
                      prop="protid.tieu_chuan"
                      align="center"
                    ></el-table-column>
                  </el-table-column>
                  <el-table-column label="Chất béo (L) - (g)" align="center">
                    <el-table-column
                      label="Suất ăn"
                      prop="lipid.suat_an"
                      align="center"
                    ></el-table-column>
                    <el-table-column
                      label="Tiêu chuẩn"
                      prop="lipid.tieu_chuan"
                      align="center"
                    ></el-table-column>
                  </el-table-column>
                  <el-table-column label="Bột đường (G) - (g)" align="center">
                    <el-table-column
                      label="Suất ăn"
                      prop="glucid.suat_an"
                      align="center"
                    ></el-table-column>
                    <el-table-column
                      label="Tiêu chuẩn"
                      prop="glucid.tieu_chuan"
                      align="center"
                    ></el-table-column>
                  </el-table-column>
                  <el-table-column label="Tỷ lệ P-L-G" align="center">
                    <el-table-column
                      label="Suất ăn"
                      prop="ty_le_plg.suat_an.name"
                      align="center"
                      width="150"
                    ></el-table-column>
                    <el-table-column
                      label="Tiêu chuẩn"
                      prop="ty_le_plg.tieu_chuan.name"
                      align="center"
                      width="150"
                    ></el-table-column>
                  </el-table-column>
                  <el-table-column label="Tỷ lệ đạt (%)" align="center">
                    <el-table-column
                      label="Calo"
                      prop="ty_le_dat.calo"
                      align="center"
                    ></el-table-column>
                    <el-table-column
                      label="Protid"
                      prop="ty_le_dat.protid"
                      align="center"
                    ></el-table-column>
                    <el-table-column
                      label="Lipid"
                      prop="ty_le_dat.lipid"
                      align="center"
                    ></el-table-column>
                    <el-table-column
                      label="Glucid"
                      prop="ty_le_dat.glucid"
                      align="center"
                    ></el-table-column>
                  </el-table-column>
                </el-table>
              </template>
            </form-wrapper>
          </div>
          <div class="col-3">
            <form-wrapper>
              <template v-slot:form-title>Chọn thực phẩm cho suất ăn</template>
              <template>
                <div class="col-12">
                  <div class="form-group">
                    <span class="label-input form-label">Nhóm thực phẩm</span>
                    <el-select
                      placeholder="Nhóm thực phẩm"
                      v-model="form.nhom_thuc_pham"
                      filterable
                    >
                      <el-option
                        v-for="item in nhom_thuc_phams"
                        :key="item.id"
                        :value="item.id"
                        :label="item.name"
                      ></el-option>
                    </el-select>
                  </div>
                  <div class="form-group">
                    <span class="label-input form-label">Thực phẩm</span>
                    <el-select
                      placeholder="Thực phẩm"
                      v-model="form.thuc_pham"
                      filterable
                    >
                      <el-option
                        v-for="item in thuc_phams"
                        :key="item.id"
                        :value="item.id"
                        :label="item.name"
                      ></el-option>
                    </el-select>
                  </div>
                  <div
                    class="form-group-button pt-3"
                    style="justify-content: center"
                  >
                    <el-button
                      type="primary"
                      size="mini"
                      @click="handleAddThucPham()"
                      >Thêm</el-button
                    >
                    <el-button
                      type="danger"
                      size="mini"
                      @click="handleDeleteThucPham()"
                      >Xóa</el-button
                    >
                  </div>
                </div>
              </template>
            </form-wrapper>
            <h5 class="d-flex justify-content-between">
              <span class="text-uppercase text-danger">tổng tiền</span>
              <span
                >{{ formatMoney(dataTableTieuChuan[0].tong_tien) }} đồng</span
              >
            </h5>
          </div>
        </div>
        <div class="form-group-button">
          <el-button
            id="btn_cancel"
            data-bs-dismiss="modal"
            ref="btn_cancel"
            v-show="false"
            >Hủy</el-button
          >
          <el-button size="small" type="primary" @click="save()">Lưu</el-button>
          <!-- <el-button type="primary" @click="saveAndNew()"
            >Lưu và thêm mới</el-button
          > -->
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
                    <span class="label-input"> Tên suất ăn: </span>
                    <el-input
                      placeholder="Tên suất ăn"
                      v-model="filter.name"
                    ></el-input>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group-input d-block d-lg-flex">
                    <span class="label-input">Lứa tuổi: </span>
                    <el-select v-model="filter.age" placeholder="Lứa tuổi">
                      <el-option selected value="" label="Tất cả"></el-option>
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
                    <span class="label-input">Loại suất ăn: </span>
                    <el-select
                      v-model="filter.loai_suat_an"
                      placeholder="Loại suất ăn"
                    >
                      <el-option selected value="" label="Tất cả"></el-option>
                      <el-option
                        v-for="(item, index) in loai_suat_ans"
                        :key="index"
                        :value="index + 1"
                        :label="item"
                      ></el-option>
                    </el-select>
                  </div>
                </div>
                <div class="col-md-6" v-if="!id_coso">
                  <div class="form-group-input d-block d-lg-flex">
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
                class="form-group-button mt-2"
                style="justify-content: center"
              >
                <el-button type="" size="small" @click="resetFilter()"
                  >Làm mới</el-button
                >
                <el-button type="primary" size="small" @click="handleSearch()"
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
              >
                <el-table-column width="40" align="center" :key="resetCheckbox">
                  <template slot="header" slot-scope="scope">
                    <!-- <el-checkbox @change="checkAll($event)"></el-checkbox> -->
                    <input
                      type="checkbox"
                      :checked="selected.includes(-1)"
                      @change="checkAll()"
                    />
                  </template>
                  <template slot-scope="scope">
                    <!-- <el-checkbox
                      :value="scope.row.checked"
                      @change="changeArraySelectedRecord(scope.row.id)"
                    ></el-checkbox> -->
                    <input
                      type="checkbox"
                      :checked="checkSelected(scope.row.id)"
                      @change="changeSelectedRecord(scope.row.id)"
                    />
                  </template>
                </el-table-column>
                <el-table-column label="Tên suất ăn" prop="name">
                </el-table-column>
                <el-table-column label="Độ tuổi" prop="age_name">
                </el-table-column>
                <el-table-column label="Loại suất ăn" prop="loai_suat_an_name">
                </el-table-column>
                <el-table-column
                  prop="data"
                  label="Khẩu phần ăn"
                  width="300"
                ></el-table-column>
                <el-table-column align="right">
                  <template slot-scope="scope">
                    <el-button
                      size="mini"
                      type="warning"
                      @click="handleEdit(scope.row)"
                      data-bs-toggle="modal"
                      data-bs-target="#exampleModal"
                      ><span>Sửa</span></el-button
                    >
                    <el-button
                      slot="reference"
                      size="mini"
                      type="danger"
                      @click="handleDelete(scope.$index, scope.row)"
                      >Xóa</el-button
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
import { formatString, formatMoney } from "../functions/formatFunctions";
import "ag-grid-community/dist/styles/ag-grid.css";
import "ag-grid-community/dist/styles/ag-theme-alpine.css";
import { AgGridVue } from "ag-grid-vue";
import CheckboxCellRenderer from "./checkbox-cell-renderer.js";

export default {
  name: "suat-an",
  components: {
    MainModal,
    FormWrapper,
    FooterQlmn,
    "ag-grid-vue": AgGridVue,
  },
  mixins: [mixin],
  data() {
    return {
      tableData: [],
      nameUpdate: "",
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      arraySelectedRecord: [],
      resetCheckbox: 0,
      title: "Thêm suất ăn",
      form: {
        id: "",
        name: "",
        age: "",
        content: "",
        loai_suat_an: "",
        nhom_thuc_pham: "",
        thuc_pham: "",
        id_coso: null,
      },
      filter: {
        name: "",
        age: "",
        loai_suat_an: "",
        id_coso: null,
      },
      old_filter: {
        name: "",
        age: "",
        loai_suat_an: "",
        id_coso: null,
      },
      cosos: [],
      id_coso: null,
      loading: false,
      grades: [],
      loai_suat_ans: ["Ăn sáng", "Ăn trưa", "Ăn xế", "Chế độ chăm sóc"],
      nhom_thuc_phams: [],
      thuc_phams: [],
      total: 0,
      // Làm danh sách thực phẩm được chọn
      columnDefs: [
        {
          headerCheckboxSelection: true,
          headerCheckboxSelectionFilteredOnly: true,
          checkboxSelection: true,
          minWidth: 50,
          width: 50,
        },
        {
          headerName: "Tên thực phẩm",
          field: "name",
          valueParser: "",
          minWidth: 300,
        },
        {
          headerName: "Đơn vị tính",
          field: "don_vi_tinh",
          valueParser: "",
        },
        {
          headerName: "Số lượng",
          field: "so_luong",
          editable: true,
        },
        {
          headerName: "Đơn giá",
          field: "gia_thi_truong",
        },
        {
          headerName: "Thành tiền",
          field: "thanh_tien",
        },
        {
          headerName: "Năng lượng (Kcal)",
          field: "nang_luong",
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
        },
        {
          headerName: "Lấy từ kho",
          field: "lay_tu_kho",
          cellRenderer: "checkboxRenderer",
        },
      ],
      rowData: [],
      gridApi: null,
      columnApi: null,
      defaultColDef: {
        flex: 1,
        minWidth: 110,
        resizable: true,
        editable: false,
        valueParser: numberParser,
      },
      undoRedoCellEditing: true,
      undoRedoCellEditingLimit: 5,
      rowSelection: "multiple",
      frameworkComponents: null,
      renderTable: 0,
      arrayThucPhamSelect: [],
      dataTableTieuChuan: [
        {
          nang_luong: {
            suat_an: "",
            tieu_chuan: "",
          },
          protid: {
            suat_an: "",
            tieu_chuan: "",
          },
          lipid: {
            suat_an: "",
            tieu_chuan: "",
          },
          glucid: {
            suat_an: "",
            tieu_chuan: "",
          },
          ty_le_plg: {
            suat_an: {
              protid: "",
              lipid: "",
              glucid: "",
              name: "",
            },
            tieu_chuan: {
              protid: "",
              lipid: "",
              glucid: "",
              name: "",
            },
          },
          ty_le_dat: {
            calo: "",
            protid: "",
            lipid: "",
            glucid: "",
          },
          tong_tien: "",
        },
      ],
      loading: false,
      selected: [],
      exclude: [],
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
      let index = this.rowData.findIndex((item) => item.id === event.data.id);
      this.rowData[index].thanh_tien =
        event.data.gia_thi_truong * event.data.so_luong;
      this.rowData[index].nang_luong =
        event.data.nang_luong * event.data.so_luong;
      this.rowData[index].protid = event.data.protid * event.data.so_luong;
      this.rowData[index].lipid = event.data.lipid * event.data.so_luong;
      this.rowData[index].glucid = event.data.glucid * event.data.so_luong;
      this.calcThanhPhanDinhDuongSuatAn();
      this.renderTable = this.renderTable + 1;
    },
    onSelectionChanged() {
      this.arrayThucPhamSelect = this.gridApi.getSelectedRows();
    },
    // END GRID'S METHODS

    // METHODS LIST TABLE
    checkSelected(id) {
      if (this.selected.includes(-1)) {
        if (this.exclude.includes(id)) {
          return false;
        } else {
          return true;
        }
      } else {
        if (this.selected.includes(id)) {
          return true;
        } else {
          return false;
        }
      }
    },
    changeSelectedRecord(id) {
      if (this.selected.includes(-1)) {
        if (this.exclude.includes(id)) {
          const index = this.exclude.indexOf(id);
          if (index > -1) {
            this.exclude.splice(index, 1); // 2nd parameter means remove one item only
          }
        } else {
          this.exclude.push(id);
        }
      } else {
        if (this.selected.includes(id)) {
          const index = this.selected.indexOf(id);
          if (index > -1) {
            this.selected.splice(index, 1); // 2nd parameter means remove one item only
          }
        } else {
          this.selected.push(id);
        }
      }
    },
    checkAll() {
      if (this.selected.includes(-1)) {
        this.exclude = [];
        this.selected = [];
      } else {
        this.selected = [-1];
        this.exclude = [];
      }
    },
    setPage(val) {
      this.page = val;
      // this.isSetPage = true;
      this.fetchData();
    },
    // END METHODS LIST TABLE
    resetFilter() {
      for (let key in this.filter) {
        this.filter[key] = "";
      }
      this.filter.id_coso = null;
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
            .post("/deleteSuatAn", { selected: [row.id], excludes: [] })
            .then((response) => {
              if (response.data.status == true) {
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa suất ăn "${name}" thành công!`,
                  type: "success",
                });
                this.fetchData();
              } else {
                this.$notify({
                  title: "Thông báo",
                  message: response.data.message,
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
    handleDeleteAll() {
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      })
        .then(async () => {
          await axios
            .post("/deleteSuatAn", {
              ...this.old_filter,
              selected: this.selected,
              excludes: this.exclude,
            })
            .then((response) => {
              if (response.data.status == true) {
                this.fetchData();
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa dữ liệu thành công!`,
                  type: "success",
                });
              } else {
                this.$notify({
                  title: "Thông báo",
                  message: response.data.message,
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
    async getNhomThucPhams() {
      await axios
        .get("dataNhomThucPham")
        .then((response) => {
          if (response.data.status) {
            this.nhom_thuc_phams = response.data.items;
          }
        })
        .catch();
    },
    getThucPhams() {
      axios
        .post("dataThanhPhanDinhDuong", {
          nhom_thuc_pham: this.form.nhom_thuc_pham,
          id_coso: this.form.id_coso,
        })
        .then((response) => {
          if (response.data.status) {
            this.thuc_phams = response.data.items;
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
    handleAddThucPham() {
      let thuc_pham = this.thuc_phams.find(
        (item) => item.id === this.form.thuc_pham
      );
      if (this.form.thuc_pham) {
        thuc_pham = {
          ...thuc_pham,
          so_luong: 1,
          thanh_tien: thuc_pham.gia_thi_truong,
          lay_tu_kho: false,
        };
        this.rowData = [...this.rowData, thuc_pham];
        this.form.thuc_pham = "";
      }
    },
    handleDeleteThucPham() {
      this.arrayThucPhamSelect.map((item) => {
        this.rowData = this.rowData.filter(
          (thuc_pham) => thuc_pham.id !== item.id
        );
      });
    },
    getTieuChuan() {
      if (this.form.age && this.form.loai_suat_an) {
        axios
          .post("/getTieuChuanSuatAn", {
            age: this.form.age,
            loai_suat_an: this.form.loai_suat_an,
          })
          .then((response) => {
            if (response.data.status) {
              this.dataTableTieuChuan[0].nang_luong.tieu_chuan =
                response.data.item.nang_luong_o_truong;
              this.dataTableTieuChuan[0].protid.tieu_chuan =
                response.data.item.protid_o_truong;
              this.dataTableTieuChuan[0].lipid.tieu_chuan =
                response.data.item.lipid_o_truong;
              this.dataTableTieuChuan[0].glucid.tieu_chuan =
                response.data.item.glucid_o_truong;
              let tong =
                Number(response.data.item.protid_o_truong) +
                Number(response.data.item.lipid_o_truong) +
                Number(response.data.item.glucid_o_truong);
              let ty_le_protid = tong
                ? (
                    (Number(response.data.item.protid_o_truong) / tong) *
                    100
                  ).toFixed(1)
                : "";
              let ty_le_lipid = tong
                ? (
                    (Number(response.data.item.lipid_o_truong) / tong) *
                    100
                  ).toFixed(1)
                : "";
              let ty_le_glucid = tong
                ? (
                    (Number(response.data.item.glucid_o_truong) / tong) *
                    100
                  ).toFixed(1)
                : "";
              this.dataTableTieuChuan[0].ty_le_plg.tieu_chuan.protid =
                ty_le_protid;
              this.dataTableTieuChuan[0].ty_le_plg.tieu_chuan.lipid =
                ty_le_lipid;
              this.dataTableTieuChuan[0].ty_le_plg.tieu_chuan.glucid =
                ty_le_glucid;
              this.dataTableTieuChuan[0].ty_le_plg.tieu_chuan.name = tong
                ? `[${ty_le_protid}]:[${ty_le_lipid}]:[${ty_le_glucid}]`
                : "";
              this.calcTyLeDat();
            } else {
              this.dataTableTieuChuan[0].nang_luong.tieu_chuan = "";
              this.dataTableTieuChuan[0].protid.tieu_chuan = "";
              this.dataTableTieuChuan[0].lipid.tieu_chuan = "";
              this.dataTableTieuChuan[0].glucid.tieu_chuan = "";
              this.dataTableTieuChuan[0].ty_le_plg.tieu_chuan = {
                protid: "",
                lipid: "",
                glucid: "",
                name: "",
              };
              this.dataTableTieuChuan[0].ty_le_plg.tieu_chuan = {
                calo: "",
                protid: "",
                lipid: "",
                glucid: "",
              };
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
    },
    calcTyLeDat() {
      this.dataTableTieuChuan[0].ty_le_dat.calo =
        this.dataTableTieuChuan[0].nang_luong.suat_an &&
        this.dataTableTieuChuan[0].nang_luong.tieu_chuan
          ? Number(
              (Number(this.dataTableTieuChuan[0].nang_luong.suat_an) /
                Number(this.dataTableTieuChuan[0].nang_luong.tieu_chuan)) *
                100
            ).toFixed(1)
          : "";
      this.dataTableTieuChuan[0].ty_le_dat.protid =
        this.dataTableTieuChuan[0].protid.suat_an &&
        this.dataTableTieuChuan[0].protid.tieu_chuan
          ? Number(
              (Number(this.dataTableTieuChuan[0].protid.suat_an) /
                Number(this.dataTableTieuChuan[0].protid.tieu_chuan)) *
                100
            ).toFixed(1)
          : "";
      this.dataTableTieuChuan[0].ty_le_dat.lipid =
        this.dataTableTieuChuan[0].lipid.suat_an &&
        this.dataTableTieuChuan[0].lipid.tieu_chuan
          ? Number(
              (Number(this.dataTableTieuChuan[0].lipid.suat_an) /
                Number(this.dataTableTieuChuan[0].lipid.tieu_chuan)) *
                100
            ).toFixed(1)
          : "";
      this.dataTableTieuChuan[0].ty_le_dat.glucid =
        this.dataTableTieuChuan[0].glucid.suat_an &&
        this.dataTableTieuChuan[0].glucid.tieu_chuan
          ? Number(
              (Number(this.dataTableTieuChuan[0].glucid.suat_an) /
                Number(this.dataTableTieuChuan[0].glucid.tieu_chuan)) *
                100
            ).toFixed(1)
          : "";
    },
    calcThanhPhanDinhDuongSuatAn() {
      let arrThanhPhanSuatAn = this.rowData.reduce(
        (arrThanhPhan, item, index) => {
          arrThanhPhan[0] = arrThanhPhan[0] + item.nang_luong;
          arrThanhPhan[1] = arrThanhPhan[1] + item.protid;
          arrThanhPhan[2] = arrThanhPhan[2] + item.lipid;
          arrThanhPhan[3] = arrThanhPhan[3] + item.glucid;
          arrThanhPhan[4] = arrThanhPhan[4] + item.thanh_tien;
          return arrThanhPhan;
        },
        [0, 0, 0, 0, 0]
      );
      this.dataTableTieuChuan[0].nang_luong.suat_an = arrThanhPhanSuatAn[0]
        ? Number(arrThanhPhanSuatAn[0]).toFixed(1)
        : "";
      this.dataTableTieuChuan[0].protid.suat_an = arrThanhPhanSuatAn[1]
        ? Number(arrThanhPhanSuatAn[1]).toFixed(1)
        : "";
      this.dataTableTieuChuan[0].lipid.suat_an = arrThanhPhanSuatAn[2]
        ? Number(arrThanhPhanSuatAn[2]).toFixed(1)
        : "";
      this.dataTableTieuChuan[0].glucid.suat_an = arrThanhPhanSuatAn[3]
        ? Number(arrThanhPhanSuatAn[3]).toFixed(1)
        : "";
      this.dataTableTieuChuan[0].tong_tien = arrThanhPhanSuatAn[4]
        ? Number(arrThanhPhanSuatAn[4])
        : "";
      let tong =
        Number(this.dataTableTieuChuan[0].protid.suat_an) +
        Number(this.dataTableTieuChuan[0].lipid.suat_an) +
        Number(this.dataTableTieuChuan[0].glucid.suat_an);
      let ty_le_protid = tong
        ? (
            (Number(this.dataTableTieuChuan[0].protid.suat_an) / tong) *
            100
          ).toFixed(1)
        : "";
      let ty_le_lipid = tong
        ? (
            (Number(this.dataTableTieuChuan[0].lipid.suat_an) / tong) *
            100
          ).toFixed(1)
        : "";
      let ty_le_glucid = tong
        ? (
            (Number(this.dataTableTieuChuan[0].glucid.suat_an) / tong) *
            100
          ).toFixed(1)
        : "";
      this.dataTableTieuChuan[0].ty_le_plg.suat_an.protid = ty_le_protid;
      this.dataTableTieuChuan[0].ty_le_plg.suat_an.lipid = ty_le_lipid;
      this.dataTableTieuChuan[0].ty_le_plg.suat_an.glucid = ty_le_glucid;
      this.dataTableTieuChuan[0].ty_le_plg.suat_an.name = tong
        ? `[${ty_le_protid}]:[${ty_le_lipid}]:[${ty_le_glucid}]`
        : "";
      this.calcTyLeDat();
    },
    handleSearch() {
      this.page = 1;
      // this.resetCheckbox = this.resetCheckbox + 1;
      this.fetchData();
    },
    async fetchData() {
      // this.arraySelectedRecord = [];
      // this.resetCheckbox = this.resetCheckbox + 1;
      this.loading = true;
      this.old_filter = this.filter;
      await axios
        .post("/dataSuatAn", {
          ...this.filter,
          page: this.page,
          pageSize: this.pageSize,
        })
        .then((response) => {
          if (response.data.status == true) {
            this.dataFilter = response.data.items;
            this.total = response.data.total;
            this.dataFilter = this.dataFilter.map((item) => {
              return {
                ...item,
                checked: false,
                age_name: this.findById(this.grades, item.age),
                loai_suat_an_name: this.loai_suat_ans[item.loai_suat_an - 1],
              };
            });
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
      this.loading = false;
    },
    pagedTableData(feature = 1) {
      this.dataFilter = this.tableData.slice(
        this.pageSize * this.page - this.pageSize,
        this.pageSize * this.page
      );
    },
    findById(arr, id) {
      var result = null;
      if (id) {
        result = arr.find((item) => item.id == id);
      }
      return result ? result.name : "";
    },
    // phần xử lý form
    validateForm() {
      if (this.form.name == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Tên suất ăn không được bỏ trống!",
          type: "warning",
        });
        return false;
      }

      if (this.form.age == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Độ tuổi không được bỏ trống!",
          type: "warning",
        });
        return false;
      }

      if (this.form.id_coso == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Cơ sở của suất ăn không được bỏ trống!",
          type: "warning",
        });
        return false;
      }

      if (this.form.loai_suat_an == "") {
        this.$notify({
          title: "Cảnh báo",
          message: "Loại suất không được bỏ trống!",
          type: "warning",
        });
        return false;
      }

      return true;
    },
    // checkExist() {
    //   var checkName = null;
    //   checkName = this.tableData.find(
    //     (item) =>
    //       formatString(item.name) == formatString(this.form.name) &&
    //       item.id != this.form.id
    //   );
    //   return checkName ? true : false;
    // },
    resetForm() {
      this.title = "Thêm suất ăn vật chất";
      for (let key in this.form) {
        this.form[key] = "";
      }
      this.rowData = [];
      this.dataTableTieuChuan = [
        {
          nang_luong: {
            suat_an: "",
            tieu_chuan: "",
          },
          protid: {
            suat_an: "",
            tieu_chuan: "",
          },
          lipid: {
            suat_an: "",
            tieu_chuan: "",
          },
          glucid: {
            suat_an: "",
            tieu_chuan: "",
          },
          ty_le_plg: {
            suat_an: {
              protid: "",
              lipid: "",
              glucid: "",
              name: "",
            },
            tieu_chuan: {
              protid: "",
              lipid: "",
              glucid: "",
              name: "",
            },
          },
          ty_le_dat: {
            calo: "",
            protid: "",
            lipid: "",
            glucid: "",
          },
          tong_tien: "",
        },
      ];
    },
    handleCopy() {
      if (this.selected.length && !this.selected.includes(-1)) {
        let id = this.selected[this.selected.length - 1];
        let row = this.dataFilter.find((item) => item.id === id);
        this.handleEdit(row);
        this.form.id = "";
      } else {
        this.resetForm();
      }
    },
    async handleEdit(row) {
      this.title = `Cập nhật suất ăn "${row.name}"`;
      for (let key in this.form) {
        this.form[key] = row[key];
      }
      this.form.age = row.age ? Number(row.age) : "";
      this.form.loai_suat_an = row.loai_suat_an ? Number(row.loai_suat_an) : "";
      this.form.id_coso = row.id_coso ? Number(row.id_coso) : "";
      await axios
        .get("/editSuatAn/" + row.id)
        .then((response) => {
          if (response.data.status) {
            this.rowData = response.data.items.map((item) => {
              return {
                ...item.thuc_pham,
                so_luong: item.so_luong,
                lay_tu_kho: item.lay_tu_kho,
                thanh_tien: item.thuc_pham.gia_thi_truong * item.so_luong,
                nang_luong: item.thuc_pham.nang_luong * item.so_luong,
                protid: item.thuc_pham.protid * item.so_luong,
                lipid: item.thuc_pham.lipid * item.so_luong,
                glucid: item.thuc_pham.glucid * item.so_luong,
              };
            });
            this.calcThanhPhanDinhDuongSuatAn();
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
    handleSubmit() {
      if (this.validateForm()) {
        // if (!this.checkExist()) {
        return axios
          .post("submitSuatAn", {
            ...this.form,
            data: this.rowData,
            tong_tien: this.dataTableTieuChuan[0].tong_tien,
          })
          .then((response) => {
            if (response.data.status) {
              this.fetchData();
              this.$notify({
                title: "Thông báo",
                message: !this.form.id
                  ? `Thêm suất ăn "${this.form.name}" thành công!`
                  : `Cập nhật suất ăn "${this.form.name}" thành công!`,
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
        // } else {
        //   this.$notify({
        //     title: "Thông báo",
        //     message: "Danh mục cơ sở vật chất này đã tồn tại",
        //     type: "warning",
        //   });
        // }
      }
      return false;
    },
    async save() {
      let result = await this.handleSubmit();
      if (result) {
        this.$refs.btn_cancel.$el.click();
      }
    },
    // async saveAndNew() {
    //   let result = await this.handleSubmit();
    //   if (result) {
    //     this.resetForm();
    //   }
    // },
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
  created() {
    // this.getGrades();
    // await this.getNhomThucPhams();
    // this.fetchData();
    Promise.all([
      this.getCosos(),
      this.getGrades(),
      this.getNhomThucPhams(),
    ]).then(() => {
      this.fetchData();
    });
  },
  beforeMount() {
    this.frameworkComponents = {
      checkboxRenderer: CheckboxCellRenderer,
    };
  },
  watch: {
    "form.nhom_thuc_pham": function () {
      this.getThucPhams();
    },
    "form.age": function () {
      this.getTieuChuan();
    },
    "form.loai_suat_an": function () {
      this.getTieuChuan();
    },
    "rowData.length": function () {
      this.calcThanhPhanDinhDuongSuatAn();
    },
    "form.id_coso": function () {
      this.rowData = [];
      this.getThucPhams();
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
}

.hideUpload > div {
  display: none;
}

.hidden {
  display: none;
}

#ag_grid_danh_sach_thuc_pham {
  height: 300px;
  padding-bottom: 15px;
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