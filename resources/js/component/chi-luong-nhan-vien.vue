<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">{{ title }}</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <!-- <el-button
                type="primary"
                size="small"
                @click="handleShowFormCreate()"
                >Tạo mới</el-button
              > -->
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
      <div class="row wrap-content-right create-phong-ban">
          <div class="col-12">
              <div class="card" >
                <div class="form-group-input" v-show="isErrorForm">
                  <el-alert :title="titleMessage" type="warning" effect="dark">
                  </el-alert>
                </div>
                <div class="wrap-filter">
                  <div class="form-group-input">
                    <span class="label-input">Chọn tháng năm: </span>
                    <el-select class="thang" v-model="month" placeholder="Tháng">
                        <el-option
                        v-for="item in months"
                        :key="item"
                        :label="item"
                        :value="item">
                        </el-option>
                        
                    </el-select>
                    <el-select v-model="year" placeholder="Năm">
                        <el-option
                        v-for="item in years"
                        :key="item"
                        :label="item"
                        :value="item">
                        </el-option>
                    </el-select>
                  </div>
                  <div class="form-group-input"  v-if="phongBanUser == -1">
                    <span class="label-input">Chọn phòng ban: </span>
                    <el-select class="chon-phong-ban" v-model="phongBan" placeholder="Chọn phòng ban">
                       <el-option
                        label="Tất cả phòng ban"
                        value="">
                        </el-option>
                       <el-option
                        v-for="phong_ban in phongBans"
                        :key="phong_ban.id"
                        :label="phong_ban.name"
                        :value="phong_ban.id">
                        </el-option>
                    </el-select>
                  </div>
                </div>
              </div>
            </div>
        </div>
      <!-- ============================================================== -->
      <div class="row">
       
        <div class="col-12">
          <div class="card" v-show="isShowCreate">
            <div class="card-body">

              <h5 class="card-title mb-0">
                Phụ cấp nhân viên <span class="strong-name">"{{ nameUpdate }}"</span>
              </h5>
            </div>
            <div class="form-group-input" v-show="isErrorForm">
              <el-alert :title="titleMessage" type="warning" effect="dark">
              </el-alert>
            </div>

            <div class="form-group-input">
              <el-checkbox 
              v-for="item in phuCaps"
              :key="item.id"
              v-model="form[item.id]"
              @change="changeCheck()"
              >
                {{ item.name }} ( {{ item.phu_cap }}{{ item.don_vi_tinh }} )
              </el-checkbox>
            </div>
            <div class="form-group-button">
              <el-button @click="handleCancelEdit()">Hủy</el-button>
              <el-button type="primary" @click="handleCreate()">Lưu</el-button>
            </div>
          </div>
          <div class="wrap-table">
            <el-table
              :data="
                dataFilter
              "
              empty-text="Không có dữ liệu"
              style="width: 100%"
            >
              <el-table-column label="#" prop="index"> </el-table-column>
              <el-table-column label="Mã NV" prop="ma_nv"> </el-table-column>
              <el-table-column label="Họ Tên" prop="fullname"> </el-table-column>
              <el-table-column label="Phòng Ban" prop="phong_ban"> </el-table-column>
              <el-table-column label="Số ngày đi làm">
                <template slot-scope="scope">
                  {{ calculatorSoNgayDiLam(scope.row.ma_nv) }}
                </template>  
              </el-table-column>
              <el-table-column label="Tiền lương">
                <template slot-scope="scope">
                  {{ calculatorTienLuong(scope.row.ma_nv) | formatMoney }}
                </template>  
              </el-table-column>
              <el-table-column align="right">
                <template slot="header" slot-scope="scope">
                  <el-input v-model="search" size="mini" placeholder="Tìm kiếm" />
                </template>
                <template slot-scope="scope">
                  <i class="fas fa-print printer" @click='handlePrint(scope.row.ma_nv)'></i>
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
import mixin from './mixin.js';
import {errorMessage} from '../errors/error-code';
export default {
  name: "chi-luong-nhan-vien",
  mixins: [mixin],
  props: {
    title: {
      type: String
    },
    phongBanUser: {
      type: String
    }
  },
  data() {
    return {
      tableData: [],
      search: "",
      isShowCreate: false,
      isShowCreateTitle: true,
      form: {
        ma_nv: "",
      },
      titleMessage: "",
      isErrorForm: false,
      nameUpdate: '',
      page: 1,
      pageSize: 10,
      dataFilter: [],
      isSetPage: true,
      months: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
      years: [2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030],
      month: 1,
      year: 2020,
      phongBan: (this.phongBanUser != -1) ? parseInt( this.phongBanUser ) : '',
      phongBanName: '',
      phongBans: [],
      phuCaps: [],
      test: '',
      hinhThucChamCongs: [],
      chamCong: [],
      phuCapNhanVien: [],
      tangGiamLuongNhanVien: [],
      tangGiamLuong: [],
      ungLuong: []
    };
  },
  watch: {
    phongBan : function() {
      this.setNamePhongBan();
      this.getAllNhanVienDangLamViec();
      this.getAllNhanVienDangLamViecPhuCap();
      this.getAllNhanVienDangLamViecUngLuong();
      this.getAllNhanVienDangLamViecTangGiamLuong();
      this.getAllChamCong();
    },
    month: function() {
      this.getAllNhanVienDangLamViec();
      this.getAllNhanVienDangLamViecPhuCap();
      this.getAllNhanVienDangLamViecUngLuong();
      this.getAllNhanVienDangLamViecTangGiamLuong();
      this.getAllChamCong();
        //this.setDateOfMonth();
        //this.getAllChamCong();
    },
    year: function() {
      this.getAllNhanVienDangLamViec();
      this.getAllNhanVienDangLamViecPhuCap();
      this.getAllNhanVienDangLamViecUngLuong();
      this.getAllNhanVienDangLamViecTangGiamLuong();
      this.getAllChamCong();
        //this.setDateOfMonth();
        //this.getAllChamCong();
    },
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
      
    },
    setPhuCap(ma_nv) {
      this.tableData.forEach( (item) => {
        if (item.ma_nv == ma_nv) {
          if (item.data) {
            for (const [key, value] of Object.entries(item.data)) {
              if (value == true || value == false) {
                this.form[key] = value;
              }
            }
          } else {
            this.resetFormForUpDate();
          }
        }
      });
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
        ;
    },
    async getAllPhongBan() {
      await axios.get("dataPhongBan").then((response) => {
        if (response.data.status == true) {
          this.phongBans = response.data.phong_ban;
        }
      });
    },
    async getAllNhanVienDangLamViec() {
      console.log(`dataNhanVienDangLamViecPhuCap/${this.month}/${this.year}/${this.phongBan}`);
      await axios.get(`dataNhanVienDangLamViecPhuCap/${this.month}/${this.year}/${this.phongBan}`).then((response) => {
        if (response.data.status == true) {
          this.tableData = response.data.nhan_vien;
          for(let i=0; i<this.tableData.length; i++) {
            this.tableData[i]["index"] = i + 1;
            if (this.tableData[i].data) {
              this.tableData[i].data = JSON.parse(this.tableData[i].data);
            }
          }
          this.pagedTableData(1);
        }
      });
    },
    async getAllChamCong() {
        await axios.get("dataChamCong/" + this.month + "/" + this.year).then((response) => {
          if (response.data.status == true) {
            this.chamCong = response.data.cham_cong;
            this.chamCong.forEach( (item, index) => {
              this.chamCong[index] = JSON.parse(`${this.chamCong[index]}`);
              this.$forceUpdate();
            });
          }
        });
      },
    async handleCreate() {
      if (this.validateForm()) {
            await axios.post("updatePhuCapNhanVien", {
              thang: this.month,
              nam: this.year,
              phuCap: this.form
              }).then((response) => {
                if (response.data.status == true) {
                    this.isErrorForm = false;
                    this.titleMessage = "";
                    this.getAllNhanVienDangLamViec();
                    this.$notify({
                    title: "Thông báo",
                    message: `Cập nhật phụ cấp cho nhân viên "${this.form.name}" thành công!`,
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
        this.isShowCreate = false;
      }
    },
    async getAllTangGiamLuong() {
      await axios.get("dataTangGiamLuong").then((response) => {
        if (response.data.status == true) {
          this.tangGiamLuong = response.data.tang_giam_luong;
          for(let i=0; i<this.tangGiamLuong.length; i++) {
            this.tangGiamLuong[i]["index"] = i + 1;
            
          }
        }
      });
    },
    async getAllNhanVienDangLamViecPhuCap() {
      console.log(`dataNhanVienDangLamViecPhuCap/${this.month}/${this.year}/${this.phongBan}`);
      await axios.get(`dataNhanVienDangLamViecPhuCap/${this.month}/${this.year}/${this.phongBan}`).then((response) => {
        if (response.data.status == true) {
          this.phuCapNhanVien = response.data.nhan_vien;
          for(let i=0; i<this.phuCapNhanVien.length; i++) {
            this.phuCapNhanVien[i]["index"] = i + 1;
            if (this.phuCapNhanVien[i].data) {
              this.phuCapNhanVien[i].data = JSON.parse(this.phuCapNhanVien[i].data);
            }
          }
        }
      });
    },
    async getAllNhanVienDangLamViecTangGiamLuong() {
      await axios.get(`dataNhanVienDangLamViecTangGiamLuong/${this.month}/${this.year}/${this.phongBan}`).then((response) => {
        if (response.data.status == true) {
          this.tangGiamLuongNhanVien = response.data.nhan_vien;
          for(let i=0; i<this.tangGiamLuongNhanVien.length; i++) {
            this.tangGiamLuongNhanVien[i]["index"] = i + 1;
            if (this.tangGiamLuongNhanVien[i].data) {
              this.tangGiamLuongNhanVien[i].data = JSON.parse(this.tangGiamLuongNhanVien[i].data);
            }
          }
        }
      });
    },
    async getAllNhanVienDangLamViecUngLuong() {
      console.log(`dataNhanVienDangLamViecUngLuong/${this.month}/${this.year}/${this.phongBan}`);
      await axios.get(`dataNhanVienDangLamViecUngLuong/${this.month}/${this.year}/${this.phongBan}`).then((response) => {
        if (response.data.status == true) {
          this.ungLuong = response.data.nhan_vien;
          for(let i=0; i<this.ungLuong.length; i++) {
            this.ungLuong[i]["index"] = i + 1;
          }
        }
      });
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
      this.form.ma_nv = '';
      this.getAllPhuCap();
    },
    resetFormForUpDate() {
      this.getAllPhuCap();
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
                  data.fullname.toLowerCase().includes(this.search.toLowerCase())
                  || data.ma_nv.toLowerCase().includes(this.search.toLowerCase())
              )
        
      } else {
      this.dataFilter = this.tableData.slice(this.pageSize * this.page - this.pageSize, this.pageSize * this.page);
      }
    },
    setDefaultFilter() {
      let date = new Date();
      this.month = date.getMonth() + 1;
      this.year = date.getFullYear();
    },
    setNamePhongBan() {
      this.phongBans.forEach( (item) => {
        if (item.id == this.phongBan) {
          this.phongBanName = item.name;
        }
      });
    },
    async getAllPhuCap() {
      await axios.get("dataPhuCap").then((response) => {
        if (response.data.status == true) {
          this.phuCaps = response.data.phu_cap;
          this.phuCaps.forEach( (item, index) => {
              this.form[item.id] = false;
          });
        }
      });
    },
    async getAllHinhThucChamCong() {
      await axios.get("dataHinhThucChamCong").then((response) => {
        if (response.data.status == true) {
          this.hinhThucChamCongs = response.data.phu_cap;
        }
      });
    },
    changeCheck() {
      this.$forceUpdate();
    },
    handleCancelEdit() {
      this.isShowCreate = false;
      this.getAllPhuCap();
    },
    renderListPhuCap(data) {
      console.log(data);
      let str = '';
      if (data == null) return str;

      for (const [key, value] of Object.entries(data)) {
        if (value == true) {
          this.phuCaps.forEach( (item) => {
            if (key == item.id) {
              if (str == '') {
                str += item.name + `(${item.phu_cap}${item.don_vi_tinh})`;
              } else {
                str += ', ' + item.name + `(${item.phu_cap}${item.don_vi_tinh})`;
              }
            }
          });
        }
      }
      return str;
    },
    calculatorSoNgayDiLam(ma_nv) {
      let soNgayDilam = 0;
      this.chamCong.forEach( (item) => {
        if (item.ma_nv == ma_nv) {
          for (const [key, value] of Object.entries(item)) {
            if (key.includes('tong_')) {
                let arraySplitFromKey = key.split('tong_');
                let idHTCC = arraySplitFromKey[1];
                this.hinhThucChamCongs.forEach( (htcc) => {
                  if (idHTCC == htcc.id) {
                    soNgayDilam += parseInt(value) * parseFloat(htcc.ngay_cong);
                  }
                });
            }
          }
        }
      });

      return soNgayDilam;
    },
    calculatorTienLuong(ma_nv) {
      let soNgayDilam = this.calculatorSoNgayDiLam(ma_nv);
      let luongNgay = 0;
      let phuCap = 0;
      let tangGiam = 0;
      let ungLuong = 0;

    
      this.dataFilter.forEach( (item) => {
          if (item.ma_nv == ma_nv) {
            luongNgay = item.luong_ngay;
            
          }
      });

      //phu cap
      this.phuCapNhanVien.forEach( (item) => {
          if (item.ma_nv == ma_nv) {
            if (item.data) {
              for (const [key, value] of Object.entries(item.data)) {
                if (key != 'ma_nv' && key != 'name') {
                    if ( value ) {
                      let idPC = key;
                      this.phuCaps.forEach( (pc) => {
                        if (idPC == pc.id) {
                            if (pc.don_vi_tinh === 'vnd') {
                              phuCap += Number(pc.phu_cap);
                            } else {
                              phuCap += parseFloat( parseFloat(item.luong_ngay) * ( parseFloat(pc.phu_cap) / 100) );
                            }
                        }
                      });
                    }
                }
              }
           }
            
          }
      });

      //tang giam
      this.tangGiamLuongNhanVien.forEach( (item) => {
          if (item.ma_nv == ma_nv) {
            if (item.data) {
              for (const [key, value] of Object.entries(item.data)) {
                if (key != 'ma_nv' && key != 'name') {
                    if ( value ) {
                      let idPC = key;
                      this.tangGiamLuong.forEach( (tg) => {
                        if (idPC == tg.id) {
                            tangGiam += Number(tg.tang_giam);
                        }
                      });
                    }
                }
              }
           }
            
          }
      });

      //ung luong
      this.ungLuong.forEach( (item) => {
          if (item.ma_nv == ma_nv) {
            if (item.tien_ung != '') {
              ungLuong += Number(item.tien_ung);
           }
            
          }
      });


      let luongThucLanh = parseFloat(soNgayDilam) * Number(luongNgay);
      luongThucLanh += parseFloat(phuCap);
      luongThucLanh += parseFloat(tangGiam);
      luongThucLanh -= parseFloat(ungLuong);

      return luongThucLanh;
    },
    handlePrint(ma_nv) {
      window.open(location.origin + `/print-pdf/${ma_nv}/${this.month}/${this.year}` , '_blank');
    }
  },
  mounted() {
    this.getAllPhongBan();
    this.getAllNhanVienDangLamViec();
    this.setDefaultFilter();
    
    this.getAllPhuCap();
    this.getAllHinhThucChamCong();
    this.getAllChamCong();
    this.getAllTangGiamLuong();
    this.getAllNhanVienDangLamViecPhuCap();
    this.getAllNhanVienDangLamViecUngLuong();
    this.getAllNhanVienDangLamViecTangGiamLuong();
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
      td { 
        &.el-table_1_column_6{
          &.is-right {
            // display: flex;
            // justify-content: flex-end;
          } 
        }
        .printer {
          color: #0000FF;
          cursor: pointer;
        }
      }
      
    }
  }
  .el-table td, .el-table th.is-leaf {
        border: 0.5px solid #EBEEF5;
    }
}

::v-deep{ 
        .el-input__inner,
        .el-checkbox__inner {
            border: 1px solid #bdc3d4;
        }
        .el-button:focus {
          outline: none;
        }

        .el-select {
          flex-basis: 20%;
          margin-right: 5px;
          &.chon-phong-ban {
            flex-basis: 40%;
          }
          &.thang {
            flex-basis: 13%;
          }
        }
    }
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
      display: flex;
      justify-content: flex-start;
      align-items: center;
      padding: 10px 10%;
      flex-wrap: wrap;
      .label-input {
        flex-basis: 150px;
      }
      .el-checkbox {
        margin-bottom: 14px;
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

    .wrap-filter {
      display: flex;
      justify-content: flex-start;
      align-items: center;
      .form-group-input {
        padding: 0;
        flex: 1;
        .label-input {
          text-align: right;
          padding-right: 15px;
        }
      }
    }
  }
}
.create-phong-ban {
  margin-left: 0;
  margin-right: 0;
}
</style>