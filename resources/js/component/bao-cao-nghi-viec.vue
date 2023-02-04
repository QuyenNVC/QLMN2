<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Danh sách nhân viên nghỉ việc</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <el-select v-model="phongBan" placeholder="Chọn phòng ban">
                <el-option
                  label="Tất cả phòng ban"
                  value="">
                </el-option>
                <el-option
                  v-for="item in phongBans"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
              <!-- <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Library</li>
                            </ol> -->
            </nav>
          </div>
        </div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
      <!-- ============================================================== -->
      <!-- Start Page Content -->
      <!-- ============================================================== -->
      <div class="row">
        <div class="col-12">
          <div class="wrap-table">
            <el-table
              :data="
                dataFilter
              "
              empty-text="Không có dữ liệu"
              style="width: 100%"
            >
              <el-table-column label="#" prop="index"> </el-table-column>
              <el-table-column label="Mã Nhân Viên" prop="ma_nv"> </el-table-column>
              <el-table-column label="Tên Nhân Viên" prop="fullname"> </el-table-column>
              <el-table-column label="Tên Nhân Viên" prop="phong_ban"> </el-table-column>
              <!-- <el-table-column align="right">
                <template slot="header" slot-scope="scope">
                  <el-input v-model="search" size="mini" placeholder="Tìm kiếm" />
                </template>
                <template slot-scope="scope">
                  <el-button
                    size="mini"
                    @click="handleEdit(scope.$index, scope.row)"
                    >Sửa</el-button
                  >

                  <el-button
                    slot="reference"
                    size="mini"
                    type="danger"
                    @click="handleDelete(scope.$index, scope.row)"
                    >Xóa</el-button
                  >
                </template>
              </el-table-column> -->
            </el-table>
          </div>
          
          <el-pagination layout="prev, pager, next" :total="(isSetPage && search == '') ? this.tableData.length : this.dataFilter.length" @current-change="setPage">
          </el-pagination>
        </div>
      </div>
      <!-- ============================================================== -->
      <!-- End PAge Content -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Right sidebar -->
      <!-- ============================================================== -->
      <!-- .right-sidebar -->
      <!-- ============================================================== -->
      <!-- End Right sidebar -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
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
import {errorMessage} from '../errors/error-code';
export default {
  name: "bao-cao-nghi-viec",
  data() {
    return {
      tableData: [],
      search: "",
      isShowCreate: false,
      isShowCreateTitle: true,
      form: {
        name: "",
        id: ""
      },
      titleMessage: "",
      isErrorForm: false,
      nameUpdate: '',
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      phongBans: [],
      phongBan: ''
    };
  },
  watch: {
    search: function() {
      if(this.search != '') {
        this.pagedTableData(5);
        this.isSetPage = false;
      } else {
       this.pagedTableData(1);
        this.isSetPage = true;
      }
    },
    page: function() {
      this.pagedTableData(1);
    }
  },
  methods: {
    setPage (val) {
      this.page = val;
      this.isSetPage = true;
    },
    handleEdit(index, row) {
      console.log(index, row);
      this.isShowCreate = true;
      this.isShowCreateTitle = false;
      this.form.name = row.name;
      this.form.id = row.id;
      this.nameUpdate = this.form.name;
    },
    handleDelete(index, row) {
      let name = row.name;
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      })
        .then(async () => {
          await axios.post("deletePhongBan/" + row.id).then((response) => {
            if (response.data.status == true) {
              this.$notify({
                title: "Thông báo",
                message: `Xóa phòng ban "${name}" thành công!`,
                type: "success",
              });
              this.fetchData();
            } else {
              this.$notify({
                title: "Thông báo",
                message: `Đã xảy ra lỗi trong quá trình thực thi, hãy thử lại sau!`,
                type: "danger",
              });
            }
          }).catch((e) => {
            this.$notify({
                title: "Thông báo",
                message: errorMessage[e.response.status],
                type: "warning",
            });
          });
        })
        .catch(() => {});
    },
    async fetchData() {
      await axios.get("dataNhanVienNghiViec/" + this.phongBan).then((response) => {
        if (response.data.status == true) {
          this.tableData = response.data.nhan_vien;
          for(let i=0; i<this.tableData.length; i++) {
            this.tableData[i]["index"] = i + 1;
            
          }
          this.pagedTableData(1);
        }
      });
    },
    async getDataPhongBan() {
      await axios.get("dataPhongBan").then((response) => {
        if (response.data.status == true) {
          this.phongBans = response.data.phong_ban;
        }
      });
    },
    async handleCreate() {
      if (this.validateForm()) {
          if ( this.isShowCreateTitle ) {
            await axios.post("createPhongBan", this.form).then((response) => {
                if (response.data.status == true) {
                    this.isErrorForm = false;
                    this.titleMessage = "";
                    this.fetchData();
                    this.$notify({
                    title: "Thông báo",
                    message: `Tạo phòng ban "${this.form.name}" thành công!`,
                    type: "success",
                    });
                    this.resetForm();
                } else {
                    this.isErrorForm = true;
                    this.titleMessage = response.data.message;
                }
            }).catch(e => {
              this.$notify({
                title: "Thông báo",
                message: errorMessage[e.response.status],
                type: "warning",
            });
            });
        } else {
            await axios.post("updatePhongBan", this.form).then((response) => {
                if (response.data.status == true) {
                    this.isErrorForm = false;
                    this.titleMessage = "";
                    this.fetchData();
                    this.$notify({
                    title: "Thông báo",
                    message: `Cập nhật phòng ban "${this.nameUpdate}" thành "${this.form.name}" thành công!`,
                    type: "success",
                    });
                    this.resetForm();
                } else {
                    this.isErrorForm = true;
                    this.titleMessage = response.data.message;
                }
            }).catch(e => {
              this.$notify({
                title: "Thông báo",
                message: errorMessage[e.response.status],
                type: "warning",
            });
            });
        }    
        this.isShowCreate = false;
      }
    },
    validateForm() {
      if (this.form.name == "") {
        this.isErrorForm = true;
        this.titleMessage = "Tên phòng ban không được để trống";
        return false;
      }

      return true;
    },
    resetForm() {
      this.form.name = "";
      this.form.id = "";
    },
    handleShowFormCreate() {
      this.isShowCreate = true;
      this.isShowCreateTitle = true;
      this.resetForm();
    },
    pagedTableData(feature = 1) {
      if (feature != 1) {

          this.dataFilter = this.tableData.filter(
                (data) =>
                  !this.search ||
                  data.name.toLowerCase().includes(this.search.toLowerCase())
              )
        
      } else {
      this.dataFilter = this.tableData.slice(this.pageSize * this.page - this.pageSize, this.pageSize * this.page);
      }
    }
  },
  mounted() {
    this.getDataPhongBan();
    this.fetchData();
  },
  watch: {
    phongBan: function() {
      this.fetchData();
    }
  }
};
</script>

<style lang="scss" scoped>
::v-deep {
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
        display: flex;
        .cell {
          width: 100%;
        }
      }
      
    }
  }
  .el-table td, .el-table th.is-leaf {
        border: 0.5px solid #EBEEF5;
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