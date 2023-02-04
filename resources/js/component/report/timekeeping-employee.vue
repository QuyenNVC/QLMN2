<template>
    <div class="page-wrapper"  v-loading="loading">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Thống kê chấm công nhân viên</h4>
            <div class="ms-auto text-end">
                <nav class="breadcrumb">
                    <el-button
                        type="primary"
                        size="small"
                        data-bs-toggle="modal"
                        data-bs-target="#exampleModal"
                        @click="exportExcel()"
                        >Xuất excel</el-button
                    >
                </nav>
            </div>
            <!-- <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                <el-button
                    type="primary"
                    size="small"
                    @click="createNewNhanVien()"
                    >Tạo mới nhân viên</el-button
                >
                <el-button
                    type="primary"
                    size="small"
                    @click="handleShowFormCreatePhongBan()"
                    >Tạo mới phòng ban</el-button
                > -->
                <!-- <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Library</li>
                                </ol> -->
                <!-- </nav>
            </div> -->
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
                  <div class="form-group-input">
                    <span class="label-input">Chọn phòng ban: </span>
                    <el-select class="chon-phong-ban" v-model="phongBan" placeholder="Chọn phòng ban">
                       <el-option
                        v-for="phong_ban in phongBans"
                        :key="phong_ban.id"
                        :label="phong_ban.name"
                        :value="phong_ban.id">
                        </el-option>
                    </el-select>
                  </div>
                  <el-button type="primary" @click="handleFilter()">Lọc</el-button>
                </div>
              </div>
            </div>
        </div>
        <div class="row wrap-content-right">
            <div class="col-12 content-right">
                <div class="label-thong-tin-nhan-vien">
                    {{ phongBanName }}
                </div>
                <hr>
                <el-alert
                  class="show-error"
                  :title="alert.title"
                  :type="alert.type"
                  v-show="alert.show"
                  effect="dark">
                </el-alert>
                <div class="wrap-form">
                    <table>
                      <tr>
                        <th>#</th>
                        <th class="ma-nv">Mã NV</th>
                        <th class="ho-ten min-w-200px">Họ Tên</th>
                        <th
                          v-if="hienThiNgayHomNay"
                        >{{ getDateOfWeek(dateOfMonth) }} {{ dateOfMonth }}</th>
                        <th
                          v-for="date in dateOfMonth"
                          :key="'Dateth' + date"
                          v-else
                        >{{ getDateOfWeek(date) }} {{ date }}</th>
                        <th
                          v-for="item in hinhThucChamCongs"
                          :key="item.id"
                        >Tổng {{ item.id }}</th>
                      </tr>
                      <tr 
                        v-for="(item, index) in nhanViens"
                        :key="item.index"
                      >
                        <td>{{ index + 1 }}</td>
                        <td> {{ item.ma_nv }} </td>
                        <td> {{ item.fullname }} </td>
                        <td
                          v-if="hienThiNgayHomNay"
                          @dblclick="showSelectChamCong(item.ma_nv, dateOfMonth)"
                        > 
                          <span :id="'select' + item.ma_nv + dateOfMonth" v-if="getNgayCongOfNhanVienByDate(item.ma_nv).length == 0">  </span>
                          <span 
                            v-for="ngaycong in getNgayCongOfNhanVienByDate(item.ma_nv)"
                            :key="ngaycong.ma_nv" 
                            :id="'select' + item.ma_nv + dateOfMonth"
                            v-else
                          > 
                            {{ (ngaycong[dateOfMonth]) ? ngaycong[dateOfMonth] : '' }} 
                          </span>
                          <div class="closeselect" :class="'closeselect' + item.ma_nv + dateOfMonth"  v-show="false" @click="updateStatusChamCong(item.ma_nv, dateOfMonth)">x</div>
                          <el-select v-model="test" placeholder="" v-show="false" :class="'select' + item.ma_nv + dateOfMonth" @change="updateStatusChamCong(item.ma_nv, dateOfMonth)" style="width: 65px;">
                            <el-option
                              v-for="htcc in hinhThucChamCongs"
                              :key="htcc.id"
                              :label="htcc.id"
                              :value="htcc.id">
                            </el-option>
                          </el-select>
                        </td>
                        <td
                          v-for="date in dateOfMonth"
                          :key="'Datetd' + date"
                          @dblclick="false"
                          v-else
                        > 
                          <span :id="'select' + item.ma_nv + date" v-if="getNgayCongOfNhanVienByDate(item.ma_nv).length == 0">  </span>
                          <span 
                            v-for="ngaycong in getNgayCongOfNhanVienByDate(item.ma_nv)"
                            :key="ngaycong.ma_nv" 
                            :id="'select' + item.ma_nv + date"
                            v-else
                          > 
                            {{ (ngaycong[date]) ? ngaycong[date] : '' }} 
                          </span>
                          <div class="closeselect" :class="'closeselect' + item.ma_nv + date"  v-show="false" @click="updateStatusChamCong(item.ma_nv, date)">x</div>
                          <el-select v-model="test" placeholder="" v-show="false" :class="'select' + item.ma_nv + date" @change="updateStatusChamCong(item.ma_nv, date)" style="width: 65px;">
                            <el-option
                              v-for="htcc in hinhThucChamCongs"
                              :key="htcc.id"
                              :label="htcc.id"
                              :value="htcc.id">
                            </el-option>
                          </el-select>
                        </td>
                        <td
                          class="tong"
                          v-for="htcc in hinhThucChamCongs"
                          :key="htcc.id"
                        >
                          <span v-if="getNgayCongOfNhanVienByDate(item.ma_nv).length == 0"> 0 </span>
                          <span 
                            v-for="ngaycong in getNgayCongOfNhanVienByDate(item.ma_nv)"
                            :key="ngaycong.ma_nv"
                            v-else
                          > 
                            {{ (ngaycong["tong_" + htcc.id]) ? ngaycong["tong_" + htcc.id] : '0' }} 
                          </span>
                        </td>
                      </tr>
                    </table>
                </div>
            </div>
            <div class="col-12">
              <div class="row wrap-footer-content">
                <div class="col-6 hinh-thuc-cham-cong">
                    <div
                      class="item-hinh-thuc-cham-cong"
                      v-for="item in hinhThucChamCongs"
                      :key="item.id"
                    >[{{ item.id }}] = {{ item.name }}</div>
                </div>
                <div class="col-6 btn-process">
                    <!-- <el-button type="primary" @click="setHienThiNgayHomNay()">Hiển thị hôm nay</el-button> -->
                    <!-- <el-button type="warning">Xuất Excel</el-button> -->
                </div>
              </div>
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
import VTreeview from "v-treeview"
import axios from 'axios'
import {mask} from 'vue-the-mask'
import moment from 'moment';
import {errorMessage} from '../../errors/error-code';

export default {
    name: "timekeeping-employee",
    components: {
        VTreeview
    },
    props: {
      phongBanUser: {
        type: String
      }
    },
    directives: {mask},
    data() {
      return {
        phongBans: [],
        alert: {
          title: '',
          type: 'success',
          show: false
        },
        titleMessage: "",
        isErrorForm: false,
        months: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
        years: [2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030],
        hinhThucChamCongs: [],
        month: 1,
        year: 2020,
        dateOfMonth: 0,
        phongBan: (this.phongBanUser != -1) ? parseInt( this.phongBanUser ) : '',
        nhanViens: [],
        test: '',
        chamCong: [],
        phongBanName: '',
        hienThiNgayHomNay: false,
        loading: false,
      };
    },
    methods: {
      async getAllPhongBan() {
        await axios.get("dataPhongBan").then((response) => {
          if (response.data.status == true) {
            this.phongBans = response.data.phong_ban;
            if (this.phongBans.length > 0) {
              this.phongBan = this.phongBans[0].id;
            }
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
      async getAllHinhThucChamCong() {
        await axios.get("dataHinhThucChamCong").then((response) => {
          if (response.data.status == true) {
            this.hinhThucChamCongs = response.data.phu_cap;
          }
        });
      },
      showAlert(title, type) {
        this.alert.title = title;
        this.alert.type = type;
        this.alert.show = true;
        //setTimeout(() => {
          //this.setAlertDefault();
        //}, 3000);
      },
      setAlertDefault() {
        this.alert.title = '';
        this.alert.type = 'success';
        this.alert.show = false;
      },
      formatDate(date) {
        var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

        if (month.length < 2) 
          month = '0' + month;
        if (day.length < 2) 
          day = '0' + day;

        return [year, month, day].join('-');
      },
      setDefaultFilter() {
        let date = new Date();
        this.month = date.getMonth() + 1;
        this.year = date.getFullYear();
      },
      async getAllNhanVienPhongBan() {
        await axios.get("dataNhanVienDangLamViec/" + this.phongBan).then((response) => {
              
              if (response.data.status == true) {
                console.log(response.data.nhan_vien);
                this.nhanViens = response.data.nhan_vien;
              }
        });
      },
      setDateOfMonth() {
        this.dateOfMonth= moment(this.year + '-' + this.month, "YYYY-MM").daysInMonth();
      },
      getDateOfWeek(date) {
        let dt = moment(this.year + '-' + this.month + '-' + date, "YYYY-MM-DD HH:mm:ss");
        
        return this.convertDayNameEnToVi(dt.format('dddd'));
      },
      convertDayNameEnToVi(text) {
        switch(text) {
          case 'Monday': return 'T2';
          case 'Tuesday': return 'T3';
          case 'Wednesday': return 'T4';
          case 'Thursday': return 'T5';
          case 'Friday': return 'T6';
          case 'Saturday': return 'T7';
          case 'Sunday': return 'CN';
        }
      },
      showSelectChamCong( ma_nv, date) {
        if (this.nhoHonNgayHienTai(date)) {
          if ( this.chamCong.length > 0 ) {
            let flag = 0;
            this.chamCong.forEach((item)=> {
              if (item.ma_nv) {
                if (item.ma_nv == ma_nv) {
                  if ( item[date] ) {
                    
                    this.test = item[date];
                  } else {
                    this.test = '';
                  }
                  flag = 1;
                }
              }
            });
            if (flag == 0) {
              this.test = '';
            }
          } else {
            this.test = '';
          }


          let ref = ('select' + ma_nv + date);
          let refclose = ('closeselect' + ma_nv + date);
          let select = document.getElementsByClassName(ref);
          select[0].style.display = 'block';
          let closeselect = document.getElementsByClassName(refclose);
          closeselect[0].style.display = 'block';
          document.getElementById(ref).style.display = 'none';
        }
      },
      updateStatusChamCong( ma_nv, date) {
        if ( this.chamCong.length > 0 ) {
          let flag = 0;
          this.chamCong.forEach((item, index)=> {
            if (item.ma_nv) {
              if (item.ma_nv == ma_nv) {
                if ( item[date] ) {
                  this.chamCong[index][date] = this.test;
                } else {
                  this.chamCong[index][date] = this.test;
                }


                this.hinhThucChamCongs.forEach( (htcc) => {
                  let number = 0;

                  for (const property in item) {
                    if (item[property] == htcc.id) {
                      if (!property.includes('tong_')) {
                        number++;
                      }
                    }
                  }
                  this.chamCong[index]["tong_" + htcc.id] = '' + number;
                });
                flag = 1;
              }
            }
          });
          if (flag == 0) {
            let ngayCong = {
              ma_nv: ma_nv
            }

            ngayCong[date] = this.test;
            this.hinhThucChamCongs.forEach( (item) => {
              if (item.id == this.test) {
                ngayCong["tong_" + item.id] = '1';
              } else {
                ngayCong["tong_" + item.id] = '0';
              }
            });
            this.chamCong.push(ngayCong);
          }
        } else {
          let ngayCong = {
            ma_nv: ma_nv
          }

          ngayCong[date] = this.test;
          this.hinhThucChamCongs.forEach( (item) => {
            if (item.id == this.test) {
              ngayCong["tong_" + item.id] = '1';
            } else {
              ngayCong["tong_" + item.id] = '0';
            }
          });
          this.chamCong.push(ngayCong);
        }

        axios.post('updateChamCong', {
          chamCong: this.chamCong,
          thang: this.month,
          nam: this.year
        }).then( response => {
          console.log(response.data);
        }).catch(e => {
          this.$notify({
                title: "Thông báo",
                message: errorMessage[e.response.status],
                type: "warning",
            });
        }); 

        let ref = ('select' + ma_nv + date);
        let refclose = ('closeselect' + ma_nv + date);
        let select = document.getElementsByClassName(ref);
        select[0].style.display = 'none';
        let closeselect = document.getElementsByClassName(refclose);
        closeselect[0].style.display = 'none';
        document.getElementById(ref).style.display = 'block';
        //document.getElementById(ref).innerHTML = this.test;
        this.$forceUpdate();
      },
      nhoHonNgayHienTai(date) {
        let dateNow = new Date();
        var a = moment([dateNow.getFullYear(), dateNow.getMonth() + 1, dateNow.getDate()]);
        var b = moment([this.year, this.month, date]);
        if (a.diff(b, 'days') >= 0) {
          return true;
        } else {
          return false;
        }
      },
      setNamePhongBan() {
        this.phongBans.forEach( (item) => {
          if (item.id == this.phongBan) {
            this.phongBanName = item.name;
          }
        });
      },
      getNgayCongOfNhanVienByDate(ma_nv) {
        let dataMoi = this.chamCong.filter( (item) => {
            return item.ma_nv == ma_nv;
        });
        return dataMoi;
      },
      setHienThiNgayHomNay() {
        setTimeout(() => {
          this.hienThiNgayHomNay = true;
          let dateNow = new Date;
          this.dateOfMonth = dateNow.getDate();
        }, 1);
        let dateNow = new Date;
        this.month = dateNow.getMonth() + 1;
        this.year = dateNow.getFullYear();
        this.getAllChamCong();
      },
      setNotHienThiNgayHomNay() {
        this.hienThiNgayHomNay = false;
        //this.getAllPhongBan();
        this.setDateOfMonth();
        this.getAllChamCong();
      },
      async handleFilter() {
        this.loading = true;
        await this.getAllNhanVienPhongBan();
        this.setNamePhongBan();
        this.setDateOfMonth();
        await this.getAllChamCong();
        this.loading = false;
      },
      async exportExcel() {
        this.loading= true;
        await axios.post('timekeeping-employee/export-excel', {
            department: this.phongBan,
            month: this.month,
            year: this.year,
        }).then((response) => {
            if (response.data.status == true) {
                this.loading = false;
                let result = response.data.result;
                this.downloadURI(result.url, result.filename);
            }
        });
      },
      downloadURI(uri, name) 
      {
          var link = document.createElement("a");
          // If you don't know the name or want to use
          // the webserver default set name = ''
          link.setAttribute('download', name);
          link.href = uri;
          document.body.appendChild(link);
          link.click();
          link.remove();
      },
    },
    async mounted() {
      this.loading = true;
      await this.getAllPhongBan();
      await this.getAllHinhThucChamCong();
      this.setDefaultFilter();
      await this.handleFilter();
      this.loading = false;
    },
    watch: {
    }
}
</script>

<style lang="scss" scoped>
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
</style>
<style lang="scss">
    div[data-v-12b157c7] {
        display: none;
    }
    ul {
        padding: 0;
    }
    .wrap-content-right {
        padding: 15px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 6px #ccc;
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
                overflow: auto;
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
                
                table {
                  width: 100%;
                  td, th {
                    border: 1px solid #929191;
                    padding: 5px 10px;
                    .ma-nv {
                      width: 30px;
                    }
                    .ho-ten {
                      width: 50px;
                    }
                  }

                  th {
                        background-color: #f6f6f6;
                  }

                  td {
                    .closeselect {
                      text-align: right;
                      cursor: pointer;
                      padding-right: 5px;
                    }
                    &.tong {
                      text-align: center;
                      font-weight: bold;
                      color: blue;
                    }
                  }
                }
            }
        }
        .wrap-footer-content {
           margin-top: 10px;
          .hinh-thuc-cham-cong {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            .item-hinh-thuc-cham-cong {
              flex-basis: 30%;
              margin: 5px 0;
            }
          }
          .btn-process {
            display: flex;
            justify-content: flex-end;
            align-items: center;
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

.min-w-200px {
  min-width: 200px;
}
</style>