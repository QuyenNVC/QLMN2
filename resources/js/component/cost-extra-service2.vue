<template>
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Quản lý định mức dịch vụ cộng thêm</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                    <el-button
                        type="primary"
                        size="small"
                        @click="handleShowFormCreate()"
                        >Thêm mới</el-button
                    >
                    </nav>
                </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card" v-show="isShowCreate">
                        <div class="card-body">
                        <h5 class="card-title mb-0" v-show="isShowCreateTitle">
                            Thêm định mức dịch vụ cộng thêm
                        </h5>
                        <h5 class="card-title mb-0" v-show="!isShowCreateTitle">
                            Cập nhật định mức dịch vụ cộng thêm của khối lớp <span class="strong-name">"{{ gradeUpdate }}"</span>, năm học <span class="strong-name">"{{ yearUpdate }}"</span>
                        </h5>
                        </div>
                        <div class="form-group-input" v-show="isErrorForm">
                        <el-alert :title="titleMessage" type="warning" effect="dark">
                        </el-alert>
                        </div>

                        <div class="container">
                        <div class="row">
                            <el-input placeholder="Mã định mức dịch vụ cộng thêm" v-model="form.id" type="hidden"></el-input>
                            <div class="col-sm-6">
                                <div class="form-group-input">
                                    <span class="label-input">Năm học: </span>
                                    <el-select v-model="form.school_year">
                                        <el-option v-for="school_year in school_years" :key="school_year.id" :label="school_year.period" :value="school_year.id"></el-option>
                                    </el-select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group-input">
                                    <span class="label-input">Khối lớp: </span>
                                    <el-select v-model="form.grade_id">
                                        <el-option v-for="grade in grades" :key="grade.id" :label="grade.name" :value="grade.id"></el-option>
                                    </el-select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group-input">
                                    <span class="label-input">Ngày áp dụng: </span>
                                    <el-date-picker v-model="form.effective_date"
                                    type="date"
                                    placeholder="Ngày áp dụng"
                                    format="dd/MM/yyyy"
                                    :picker-options="pickerBeginDateBefore"></el-date-picker>
                                </div>
                            </div>
                            <div class="col-sm-6" v-for="extraService in extraServices" :key="extraService.id">
                                <div class="form-group-input">
                                    <span class="label-input">{{ extraService.name }}: </span>
                                    <input v-model="form[extraService.name]" v-money='money' class="el-input__inner" :placeholder="extraService.name">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group-input">
                                    <span class="label-input">Ghi chú: </span>
                                    <el-input type="textarea" placeholder="Ghi chú" v-model="form.note"></el-input>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="form-group-button">
                        <el-button @click="isShowCreate = false">Hủy</el-button>
                        <el-button type="primary" @click="handleCreate()">Lưu</el-button>
                        </div>
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
                    <el-table-column label="STT" prop="index"> </el-table-column>
                    <el-table-column label="Năm học" prop="school_year_name"> </el-table-column>
                    <el-table-column label="Khối lớp" prop="grade_name"> </el-table-column>
                    <el-table-column label="Ngày áp dụng" prop="effective_date"> </el-table-column>
                    <el-table-column v-for="extraService in extraServices" :key="extraService.id" :label="extraService.name" :prop="extraService.name"> </el-table-column>
                    <el-table-column label="Tổng cộng" prop="sumFee"> </el-table-column>
                    <el-table-column label="Mô tả/Ghi chú" prop="note"> </el-table-column>
                    <el-table-column align="right">
                        <template slot="header" slot-scope="scope">
                        <el-input v-model="search" size="mini" placeholder="Tìm kiếm" />
                        </template>
                        <template slot-scope="scope">
                        <el-button
                            size="mini"
                            @click="handleEdit(scope.$index, scope.row)"
                            v-if="scope.row.disableBtn"
                            >Sửa</el-button
                        >

                        <el-button
                            slot="reference"
                            size="mini"
                            type="danger"
                            @click="handleDelete(scope.$index, scope.row)"
                            v-if="scope.row.disableBtn"
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
    name: 'cost-extra-service',
    mixins: [mixin],
    data: function() {
        return {
                form: {
                    id: "",
                    school_year: "",
                    effective_date: "",
                    grade_id: "",
                    note: "",
                    fees: []
                },
            school_years: [],
            grades: [],
            isShowCreate: false,
            isShowCreateTitle: false,
            isErrorForm: false,
            titleMessage: "",
            gradeUpdate: "",
            yearUpdate: "",
            extraServices: [],
            tableData: [],
            page: 1,
            pageSize: 10,
            dataFilter: [],
            isSetPage: true,
            search: "",
            pickerBeginDateBefore:{
                disabledDate(time) {
                    return time.getTime() < Date.now();
                }
            },
        };
    },
    methods: {
        handleEdit(index, row) {
            this.gradeUpdate = row.grade_name;
            this.yearUpdate = row.school_year_name;
            this.form.id = row.id;
            this.form.school_year = row.school_year;
            this.form.effective_date = row.effective_date;
            this.form.grade_id = row.grade;
            this.form.note = row.note;
            for(let j=0; j<this.extraServices.length;j++) {
                this.form[this.extraServices[j].name] = row[this.extraServices[j].name] ? row[this.extraServices[j].name].replace(/,/g , '') : '';
            }
            this.isShowCreate = true;
            this.isShowCreateTitle = false;
            this.isErrorForm = false;
        },
        async fetchData() {
            await axios.get('getCostExtraService', this.form).then((response) => {
                this.tableData = response.data.costExtraServices;
                for(let i=0; i<this.tableData.length; i++) {
                    this.tableData[i]["index"] = i + 1;
                    this.tableData[i].sumFee = 0;
                    if(this.tableData[i].fees) {
                        for(let j=0; j<this.extraServices.length;j++) {
                            if(this.tableData[i].fees[j]) {
                                this.tableData[i].sumFee = this.tableData[i].sumFee + Number(this.tableData[i].fees[j].cost);
                            }
                            this.tableData[i][this.extraServices[j].name] = this.tableData[i].fees[j] ? this.formatMoney(this.tableData[i].fees[j].cost) : ""; 
                        }
                    }
                    this.tableData[i].sumFee = this.formatMoney(this.tableData[i].sumFee);
                    var curDate = new Date();
                    var effDate = new Date(this.tableData[i].effective_date);
                    this.tableData[i].disableBtn = curDate < effDate;
                }
                this.pagedTableData(1);
            }).catch();
        },
        formatMoney(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        },
        async getSchoolYears() {
            await axios.get('getSchoolYears').then((promise) => {
                if(promise.data.status) {
                    var currentDate = new Date();
                    var month = currentDate.getMonth()+1;
                    var year = month > 6 ? currentDate.getFullYear() : currentDate.getFullYear() - 1;
                    this.school_years = promise.data.school_years.filter(function(e) {
                        return e.name >= year;
                    });
                }
            }).catch((rejected) => {});
        },
        async getGrades() {
            await axios.get('dataGrades').then((promise) => {
                if(promise.data.status) {
                    this.grades = promise.data.grades;
                }
            }).catch((rejected) => {});
        },
        async getExtraServices() {
            await axios.get("dataExtraServices").then((response) => {
                if (response.data.status == true) {
                    this.extraServices = response.data.extraservices;
                    for(let i=0; i<this.extraServices.length; i++) {
                        var extra_service = this.extraServices[i].name;
                        this.form[extra_service] = "";
                    }
                }
            });
        },
        handleShowFormCreate() {
            this.isShowCreate = true;
            this.isShowCreateTitle = true;
            this.isErrorForm = false;
            this.resetForm();
        },
        resetForm() {
            this.form.id = "";
            this.form.school_year = "";
            this.form.grade_id = "";
            this.form.note = "";
            this.form.effective_date = "";
            for(let i = 0; i<this.extraServices.length; i++) {
                this.form[this.extraServices[i].name] = "";
            }
            this.form.fees = [];
        },
        formatBeforeSend() {
            for(var i = 0; i < this.extraServices.length; i++) {
                var extraService = this.extraServices[i];
                this.form.effective_date = this.form.effective_date ? this.formatDate(this.form.effective_date) : '';
                this.form.fees.push({
                    "id": extraService.id,
                    "value": this.form[extraService.name].replace(/,/g, '')
                });
            }
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
        async handleCreate() {
            if(this.validateForm()) {
                if(this.isShowCreateTitle) {
                    if(!this.checkExist()) {
                        this.formatBeforeSend();
                        await axios.post('createCostExtraService', this.form).then((response) => {
                            if(response.data.status) {
                                this.fetchData();
                                var grade = this.grades.find(grade => grade.id == this.form.grade_id).name;
                                var schoolYear = this.school_years.find(school_year => school_year.id == this.form.school_year).period;
                                this.$notify({
                                    title: "Thông báo",
                                    message: `Tạo thành công định mức dịch vụ cộng thêm cho khối lớp ${ grade } năm học ${ schoolYear }!`,
                                    type: "success",
                                });
                                this.isErrorForm = false;
                                this.titleMessage = "";
                                this.isShowCreate = false;
                                this.resetForm();
                            }
                        }).catch((e) => {
                            this.$notify({
                                title: "Thông báo",
                                message: errorMessage[e.response.status],
                                type: "warning",
                            });
                        })
                    } else {
                        this.isErrorForm = true;
                        this.titleMessage = "Định mức dịch vụ cộng thêm này đã tồn tại";
                    }
                } else {
                    if(!this.checkExist()) {
                        this.formatBeforeSend();
                        console.log(this.form);
                        await axios.post('updateCostExtraService', this.form).then((response) => {
                            if(response.data.status) {
                                this.fetchData();
                                this.$notify({
                                    title: "Thông báo",
                                    message: `Cập nhật thành công định mức dịch vụ cộng thêm cho khối lớp ${ this.gradeUpdate }, năm học ${ this.yearUpdate }!`,
                                    type: "success",
                                });
                                this.isErrorForm = false;
                                this.titleMessage = "";
                                this.isShowCreate = false;
                                this.resetForm();
                            }
                        }).catch((e) => {
                            this.$notify({
                                title: "Thông báo",
                                message: errorMessage[e.response.status],
                                type: "warning",
                            });
                        });
                    } else {
                        this.isErrorForm = true;
                        this.titleMessage = "Định mức dịch vụ cộng thêm này đã tồn tại";
                    }
                }
            }
        },
        handleDelete(index, row) {
            this.$confirm("Bạn thật sự muốn xóa dữ liệu này?", "Cảnh báo", {
                confirmButtonText: "Có",
                cancelButtonText: "Không",
                type: "warning",
            }).then(async () => {
                await axios.post("deleteCostExtraService/" + row.id).then((response) => {
                    if (response.data.status == true) {
                    this.$notify({
                        title: "Thông báo",
                        message: `Xóa định mức dịch vụ cộng thêm thành công!`,
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
                }).catch(e => {
                    this.$notify({
                        title: "Thông báo",
                        message: errorMessage[e.response.status],
                        type: "warning",
                    });
                });
            })
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
        setPage (val) {
            this.page = val;
            this.isSetPage = true;
        },
        checkExist() {
            var checkName  = null;
            console.log(this.tableData);
            console.log(this.form);
            checkName = this.tableData.find(costextraservice => costextraservice.school_year == this.form.school_year && costextraservice.grade == this.form.grade_id && costextraservice.id != this.form.id);
            return checkName ? true : false;
        },
        validateForm() {
            if (this.form.school_year == "") {
                this.isErrorForm = true;
                this.titleMessage = "Năm học không được để trống";
                return false;
            }

            if (this.form.grade_id == "") {
                this.isErrorForm = true;
                this.titleMessage = "Khối lớp không được để trống";
                return false;
            }

            if (this.form.effective_date === null || this.form.effective_date == '') {
                this.isErrorForm = true;
                this.titleMessage = "Ngày có hiệu lực không được để trống";
                return false;
            }

            return true;
        }
    },
    created() {
        this.getExtraServices();
        this.getSchoolYears();
        this.getGrades();
    },
    beforeMount() {
        this.fetchData();
    },
    mounted() {
    }
}
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
        // display: flex;
        .cell {
          width: 100%;
        }
      }
      .el-table_1_column_2  {
        .cell {
          text-align: left;
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