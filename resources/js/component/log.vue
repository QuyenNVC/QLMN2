<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Nhật ký hoạt động</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <el-button
                type="primary"
                size="small"
                @click="handleDeleteAll()"
                >Xóa tất cả</el-button
              >
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
              <el-table-column label="STT" prop="index"> </el-table-column>
              <el-table-column label="Tên người dùng" prop="username">
              </el-table-column>
              <el-table-column label="Thao tác" prop="action">
                <template slot-scope="scope">
                  <el-tag
                    type="success"
                    effect="dark"
                    v-if="scope.row.action == 'create'">
                  {{ scope.row.action }}
                  </el-tag>
                  <el-tag
                    type="warning"
                    effect="dark"
                    v-else-if="scope.row.action == 'update'">
                  {{ scope.row.action }}
                  </el-tag>
                  <el-tag
                    type="danger"
                    effect="dark"
                    v-else>
                  {{ scope.row.action }}
                  </el-tag>
                </template>
              </el-table-column>
              <el-table-column label="Tên bảng" prop="table_name"> </el-table-column>
              <el-table-column label="Tại mục" prop="value_name_table"> </el-table-column>
              <el-table-column label="Thời gian" prop="created_at"> 
                <template slot-scope="scope">
                  {{ formatDate(scope.row.created_at) }}
                </template>
              </el-table-column>
              <el-table-column align="right">
                <template slot="header" slot-scope="scope">
                  <el-input v-model="search" size="mini" placeholder="Tìm kiếm" />
                </template>
                <template slot-scope="scope">
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
import moment from 'moment';

export default {
  name: "log",
  data() {
    return {
      tableData: [],
      search: "",
      isShowCreate: false,
      isShowCreateTitle: true,
      form: {
        id: '',
        name: "",
        phu_cap: "",
        don_vi_tinh: "vnd",
        ghi_chu: "",
      },
      titleMessage: "",
      isErrorForm: false,
      nameUpdate: '',
      phongBans: [],
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
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
      this.page = val
    },
    handleEdit(index, row) {
      console.log(index, row);
      this.isShowCreate = true;
      this.isShowCreateTitle = false;
      this.form.name = row.name;
      this.form.phu_cap = row.phu_cap;
      this.form.don_vi_tinh = row.don_vi_tinh;
      this.form.ghi_chu = row.ghi_chu;
      this.form.id = row.id;
      this.nameUpdate = this.form.name;
    },
    handleDelete(index, row) {
      let username = row.name;
      this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      })
        .then(async () => {
          await axios.post("deleteLog/" + row.id).then((response) => {
            console.log(response);
            if (response.data.status == true) {
              this.$notify({
                title: "Thông báo",
                message: `Xóa thành công!`,
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
        ;
    },
    handleDeleteAll() {
      this.$confirm("Bạn thật sự muốn xóa tất cả dữ liệu này?", "Cảnh báo", {
        confirmButtonText: "Có",
        cancelButtonText: "Không",
        type: "warning",
      })
        .then(async () => {
          await axios.get("deleteLogAll").then((response) => {
            console.log(response);
            if (response.data.status == true) {
              this.$notify({
                title: "Thông báo",
                message: `Xóa thành công!`,
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
        ;
    },
    async fetchData() {
      await axios.get("dataLog").then((response) => {
        if (response.data.status == true) {
          this.tableData = response.data.phu_cap;
          for(let i=0; i<this.tableData.length; i++) {
            this.tableData[i]["index"] = i + 1;
            
          }
          this.pagedTableData(1);
        }
      });
      this.getAllPhongBan();
    },
    async getAllPhongBan() {
      await axios.get("dataPhongBan").then((response) => {
        if (response.data.status == true) {
          this.phongBans = response.data.phong_ban;
        }
      });
    },
    // async handleCreate() {
    //   if (this.validateForm()) {
    //     if ( this.isShowCreateTitle ) {
    //         await axios.post("createPhuCap", this.form).then((response) => {
    //             if (response.data.status == true) {
    //                 this.isErrorForm = false;
    //                 this.titleMessage = "";
    //                 this.fetchData();
    //                 this.$notify({
    //                 title: "Thông báo",
    //                 message: `Tạo phụ cấp "${this.form.name}" thành công!`,
    //                 type: "success",
    //                 });
    //                 this.resetForm();
    //                 this.isShowCreate = false;
    //             } else {
    //                 this.isErrorForm = true;
    //                 this.titleMessage = response.data.message;
    //             }
    //         }).catch(e => {
    //           this.$notify({
    //               title: "Thông báo",
    //               message: errorMessage[e.response.status],
    //               type: "warning",
    //           });
    //         });
    //     } else {
    //         await axios.post("updatePhuCap", this.form).then((response) => {
    //             if (response.data.status == true) {
    //                 this.isErrorForm = false;
    //                 this.titleMessage = "";
    //                 this.fetchData();
    //                 this.$notify({
    //                 title: "Thông báo",
    //                 message: `Cập nhật phụ cấp "${this.form.name}" thành công!`,
    //                 type: "success",
    //                 });
    //                 this.resetForm();
    //                 this.isShowCreate = false;
    //             } else {
    //                 this.isErrorForm = true;
    //                 this.titleMessage = response.data.message;
    //             }
    //         });
    //     }    
    //   }
    // },
    // validateForm() {
    //   if (this.form.name == "") {
    //     this.isErrorForm = true;
    //     this.titleMessage = "Tên phụ cấp không được để trống";
    //     return false;
    //   }

    //   if (this.form.phu_cap == "") {
    //     this.isErrorForm = true;
    //     this.titleMessage = "Phụ cấp không được để trống";
    //     return false;
    //   }

    //   // if ( !this.checkNumber(this.form.phu_cap) ) {
    //   //   this.isErrorForm = true;
    //   //   this.titleMessage = "Phụ cấp không đúng định dạng";
    //   //   return false;
    //   // }

    //   return true;
    // },
    // checkNumber(x) {
    //     // check if the passed value is a number
    //     if(typeof x == 'number' && !isNaN(x)){
        
    //         return true;
        
    //     } else {
    //       if (Number.isInteger(x)) {
    //           return true;
    //       } else {
    //         return false;
    //       }
    //     }
    // },
    resetForm() {
      this.form.name = "";
      this.form.phu_cap = "";
      this.form.don_vi_tinh = "vnd";
      this.form.ghi_chu = "";
    },
    handleShowFormCreate() {
      this.isShowCreate = true;
      this.isShowCreateTitle = true;
      this.resetForm();
    },
    handleCancel() {
      this.isShowCreate = false;
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
    },
    formatDate(date) {
      var d = new Date(date);
      let month = '' + (d.getMonth() + 1);
      let day = '' + d.getDate();
      let year = d.getFullYear();
      let hour = d.getHours();
      let minute = d.getMinutes();


      if (month.length < 2) 
        month = '0' + month;
      if (day.length < 2) 
        day = '0' + day;
      if (hour.length < 2) 
        hours = '0' + hours;
      if (minute.length < 2) 
        minute = '0' + minute;

      return [day, month, year].join('-') + ' ' + [hour, minute].join(':');
    }
  },
  mounted() {
    this.fetchData();
  },
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
      .el-table_1_column_4 {
        .cell {
          width: 100%;
        }
      }
      .el-table_1_column_6 {
        .cell {
          padding-right: 0;
        }
      }
    }
   }

  .el-select {
    width: 100%;
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