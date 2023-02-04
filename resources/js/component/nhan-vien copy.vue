<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Tạo mới nhân viên</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <el-button
                type="primary"
                size="small"
                @click="isShowPreview = true"
                >Xem hồ sơ</el-button
              >
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
      <div
        class="row wrap-content-right create-phong-ban"
        v-show="isShowCreate"
      >
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-0">Thêm phòng ban mới</h5>
            </div>
            <div class="form-group-input" v-show="isErrorForm">
              <el-alert :title="titleMessage" type="warning" effect="dark">
              </el-alert>
            </div>

            <div class="form-group-input">
              <span class="label-input">Tên phòng ban: </span>
              <el-input
                placeholder="Tên phòng ban"
                v-model="formPB.name"
              ></el-input>
            </div>
            <div class="form-group-button">
              <el-button @click="isShowCreate = false">Hủy</el-button>
              <el-button type="primary" @click="handleCreate()">Lưu</el-button>
            </div>
          </div>
        </div>
      </div>
      <div class="row wrap-content-right ho-so-ung-vien" v-show="isShowPreview">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-0">Hồ sơ nhân viên</h5>
            </div>
            <div class="row">
              <div class="col-3">
                <img
                  class="avatar"
                  src="/img/man-icon.png"
                  alt=""
                  v-if="form.gender == 'Nam'"
                />
                <img class="avatar" src="/img/girl-icon.png" alt="" v-else />
                <p class="name-under-avatar">{{ form.name }}</p>
              </div>
              <div class="col-9 content-right">
                <div class="row">
                  <div class="col-6 label-wrap">
                    <span class="label-name">Mã nhân viên: </span>
                    <span class="content-text">{{ form.id }}</span>
                  </div>
                  <div class="col-6 label-wrap">
                    <span class="label-name">Họ và tên: </span>
                    <span class="content-text">{{ form.name }}</span>
                  </div>
                  <div class="col-6 label-wrap">
                    <span class="label-name">Giới tính: </span>
                    <span class="content-text">{{ form.gender }}</span>
                  </div>
                  <div class="col-6 label-wrap">
                    <span class="label-name">Ngày sinh: </span>
                    <span class="content-text">{{
                      form.birthday | moment("DD/MM/YYYY")
                    }}</span>
                  </div>
                  <div class="col-6 label-wrap">
                    <span class="label-name">CMND: </span>
                    <span class="content-text">{{ form.cmnd }}</span>
                  </div>
                  <div class="col-6 label-wrap">
                    <span class="label-name">Nơi cấp: </span>
                    <span class="content-text">{{ form.noiCap }}</span>
                    <span class="label-name">Ngày cấp: </span>
                    <span class="content-text">{{
                      form.ngayCap | moment("DD/MM/YYYY")
                    }}</span>
                  </div>
                  <div class="col-6 label-wrap">
                    <span class="label-name">Dân tộc</span>
                    <span class="content-text">{{ form.danToc }}</span>
                  </div>
                  <div class="col-6 label-wrap">
                    <span class="label-name">Địa chỉ: </span>
                    <span class="content-text">{{ form.address }}</span>
                  </div>
                  <div class="col-6 label-wrap">
                    <span class="label-name">Điện thoại: </span>
                    <span class="content-text">{{ form.phone }}</span>
                  </div>
                  <div class="col-6 label-wrap">
                    <span class="label-name">Email: </span>
                    <span class="content-text">{{ form.email }}</span>
                  </div>
                  <div class="col-6 label-wrap">
                    <span class="label-name">Lương ngày: </span>
                    <span class="content-text">{{ form.luongNgay }}</span>
                  </div>
                  <div class="col-6 label-wrap">
                    <span class="label-name">Phòng ban: </span>
                    <span class="content-text">{{ phongBanName }}</span>
                  </div>
                  <div class="col-12 label-wrap">
                    <span class="label-name">Ngày vào làm: </span>
                    <span class="content-text">{{
                      form.ngayVaoLam | moment("DD/MM/YYYY")
                    }}</span>
                  </div>
                  <div class="col-6 label-wrap">
                    <span class="label-name">Trạng Thái: </span>
                    <span class="content-text">{{
                      form.status ? "Đã nghỉ làm" : "Đang làm việc"
                    }}</span>
                  </div>
                  <div class="col-6 label-wrap" v-show="form.status">
                    <span class="label-name">Ngày nghỉ làm: </span>
                    <span class="content-text">{{
                      form.ngayNghiLam | moment("DD/MM/YYYY")
                    }}</span>
                  </div>
                  <div class="col-12 label-wrap wrap-button-hide-ho-so">
                    <el-button type="warning" @click="isShowPreview = false"
                      >OK</el-button
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row wrap-content-right">
        <div class="col-12 col-md-3">
          <v-treeview
            v-model="treeData"
            :treeTypes="treeTypes"
            @selected="selected"
            :openAll="openAll"
            :contextItems="contextItems"
            @contextSelected="contextSelected"
          ></v-treeview>
        </div>
        <div class="col-12 col-md-9 content-right">
          <div class="label-thong-tin-nhan-vien">Thông tin nhân viên</div>
          <hr />
          <el-alert
            class="show-error"
            :title="alert.title"
            :type="alert.type"
            v-show="alert.show"
            effect="dark"
          >
          </el-alert>
          <div class="wrap-form">
            <div class="form-group">
              <span class="form-label">Mã: </span>
              <el-input
                placeholder="Mã NV"
                v-model="form.id"
                :disabled="true"
              ></el-input>
            </div>
            <div class="form-group">
              <span class="form-label">Họ tên: </span>
              <el-input placeholder="Họ và tên" v-model="form.name"></el-input>
            </div>
            <div class="form-group">
              <span class="form-label">Giới tính: </span>
              <el-select v-model="form.gender" placeholder="Chọn giới tính">
                <el-option label="Nam" value="Nam"> </el-option>
                <el-option label="Nữ" value="Nữ"> </el-option>
              </el-select>
            </div>
            <div class="form-group">
              <span class="form-label">Ngày sinh: </span>
              <el-date-picker
                v-model="form.birthday"
                type="date"
                placeholder="Chọn ngày sinh"
                format="dd/MM/yyyy"
              >
              </el-date-picker>
            </div>
            <div class="form-group">
              <span class="form-label">Dân tộc: </span>
              <el-input placeholder="Dân tộc" v-model="form.danToc"></el-input>
            </div>
            <div class="form-group">
              <span class="form-label">Điện thoại: </span>
              <el-input
                placeholder="Điện thoại"
                v-model="form.phone"
                v-mask="'###-###-####'"
              ></el-input>
            </div>
            <!-- <div class="form-group">
                        <span class="form-label">Chức vụ: </span>
                        <el-select v-model="form.chuc_vu" placeholder="Chọn chức vụ">
                            <el-option
                            label="Nhân viên"
                            value="Nhân viên">
                            </el-option>
                            <el-option
                            label="Quản lý"
                            value="Quản lý">
                            </el-option>
                        </el-select>
                    </div>
                    <div class="form-group" v-show="form.chuc_vu != 'Nhân viên'">
                        <span class="form-label">Tài khoản: </span>
                        <el-input placeholder="Tài khoản" v-model="form.usename"></el-input>
                    </div>
                    <div class="form-group" v-show="form.chuc_vu != 'Nhân viên'">
                        <span class="form-label">Mật khẩu: </span>
                        <el-input placeholder="Mật khẩu" v-model="form.password"></el-input>
                    </div>
                    <div class="form-group" v-show="form.chuc_vu != 'Nhân viên'" style="opacity: 0">
                        <span class="form-label">Test: </span>
                        <el-input placeholder="CMND"></el-input>
                    </div> -->
            <div class="form-group">
              <span class="form-label">CMND: </span>
              <el-input
                placeholder="CMND"
                v-model="form.cmnd"
                v-mask="'#########'"
              ></el-input>
            </div>
            <div class="form-group">
              <span class="form-label">Nơi cấp: </span>
              <el-input placeholder="Nơi cấp" v-model="form.noiCap"></el-input>
            </div>
            <div class="form-group">
              <span class="form-label">Ngày cấp: </span>
              <el-date-picker
                v-model="form.ngayCap"
                type="date"
                placeholder="Ngày cấp"
                format="dd/MM/yyyy"
              >
              </el-date-picker>
            </div>
            <div class="form-group">
              <span class="form-label">Email: </span>
              <el-input placeholder="Email" v-model="form.email"></el-input>
            </div>
            <div class="form-group">
              <span class="form-label">Lương ngày: </span>
              <el-input
                placeholder="Lương ngày"
                v-model.lazy="form.luongNgay"
                v-money="money"
              ></el-input>
            </div>
            <div class="form-group dia-chi">
              <span class="form-label">Địa chỉ: </span>
              <el-input placeholder="Địa chỉ" v-model="form.address"></el-input>
            </div>
            <div class="form-group checkbox">
              <div>
                <span class="form-label">Trạng thái: </span>
                <el-checkbox v-model="form.status">Đã nghỉ việc</el-checkbox>
              </div>
            </div>
            <div class="form-group">
              <span class="form-label">Ngày vào làm: </span>
              <el-date-picker
                v-model="form.ngayVaoLam"
                type="date"
                placeholder="Ngày vào làm"
                format="dd/MM/yyyy"
              >
              </el-date-picker>
            </div>
            <div class="form-group">
              <span class="form-label">Ngày nghỉ làm: </span>
              <el-date-picker
                v-model="form.ngayNghiLam"
                type="date"
                placeholder="Ngày nghỉ làm"
                format="dd/MM/yyyy"
                :disabled="!form.status"
              >
              </el-date-picker>
            </div>
            <div class="form-group">
              <span class="form-label">Phòng ban: </span>
              <el-select v-model="form.phong_ban" placeholder="Chọn phòng ban">
                <el-option
                  v-for="phong_ban in phongBans"
                  :key="phong_ban.id"
                  :label="phong_ban.name"
                  :value="phong_ban.id"
                >
                </el-option>
              </el-select>
            </div>
            <div class="form-group button-luu">
              <el-button
                type="danger"
                v-if="edit"
                @click="handleDeleteNhanVien()"
                >Xóa Nhân Viên</el-button
              >
              <el-button type="primary" @click="handleSubmit()">Lưu</el-button>
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
import VTreeview from "v-treeview";
import axios from "axios";
import { mask } from "vue-the-mask";
import mixin from "./mixin.js";
import { errorMessage } from "../errors/error-code";

export default {
  name: "nhan-vien",
  mixins: [mixin],
  components: {
    VTreeview,
  },
  directives: { mask },
  data() {
    return {
      openAll: true,
      treeTypes: [],
      treeData: [],
      contextItems: [],
      selectedNode: null,
      form: {
        id: "",
        name: "",
        gender: "Nam",
        birthday: "",
        danToc: "",
        cmnd: "",
        noiCap: "",
        ngayCap: "",
        phone: "",
        email: "",
        luongNgay: "",
        address: "",
        ngayVaoLam: "",
        ngayNghiLam: "",
        status: false,
        phong_ban: "",
      },
      phongBans: [],
      phongBanName: "",
      alert: {
        title: "",
        type: "success",
        show: false,
      },
      numberNhanVien: 0,
      isShowCreate: false,
      formPB: {
        name: "",
      },
      titleMessage: "",
      isErrorForm: false,
      isShowPreview: false,
      edit: false,
    };
  },
  methods: {
    getTypeRule(type) {
      var typeRule = this.treeTypes.filter((t) => t.type == type)[0];
      return typeRule;
    },
    contextSelected(command) {
      switch (command) {
        case "Create Basic":
          var node = {
            text: "New Basic Plan",
            type: "Basic",
            children: [],
          };
          this.selectedNode.addNode(node);
          break;
        case "Create Top-up":
          var node = {
            text: "New Top-up",
            type: "Top-up",
            children: [],
          };
          this.selectedNode.addNode(node);
          break;
        case "Rename":
          this.selectedNode.editName();
          break;
        case "Remove":
          break;
      }
    },
    selected(node) {
      this.alert.show = false;
      this.selectedNode = node;
      this.contextItems = [];
      var typeRule = this.getTypeRule(this.selectedNode.model.type);
      typeRule.valid_children.map(function (type, key) {
        var childType = this.getTypeRule(type);
        var item = {
          title: "Create " + type,
          icon: childType.icon,
          type: childType,
        };
        this.contextItems.push(item);
      }, this);

      this.contextItems.push({ title: "Rename", icon: "far fa-edit" });
      this.contextItems.push({ title: "Remove", icon: "far fa-trash-alt" });

      if (node.model.is_content) {
        this.editNhanVien(node.model.id);
        this.edit = true;
      }
    },
    async getAllPhongBan() {
      await axios.get("dataPhongBan").then((response) => {
        if (response.data.status == true) {
          this.phongBans = response.data.phong_ban;
        }
      });
    },
    async handleSubmit() {
      if (this.validate()) {
        this.formatBeforeSend();
        console.log(this.form);
        await axios
          .post("updateNhanVien", this.form)
          .then((response) => {
            if (response.data.status == true) {
              this.isErrorForm = false;
              this.titleMessage = "";
              //this.fetchData();
              this.$notify({
                title: "Thông báo",
                message: `Đã lưu thông tin nhân viên "${this.form.name}"!`,
                type: "success",
              });
              this.setAlertDefault();
              this.resetForm();
              this.createMaNV();
              this.setTreeView();
            } else {
              this.showAlert(
                "Đã có lỗi trong quá trình xử lý, vui lòng thử lại sau",
                "error"
              );
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
    validate() {
      if (this.form.name == "") {
        this.showAlert("Họ tên không được để trống", "warning");
        return false;
      }

      if (this.form.birthday == "") {
        this.showAlert("Ngày sinh không được để trống", "warning");
        return false;
      }

      if (this.form.danToc == "") {
        this.showAlert("Dân tộc không được để trống", "warning");
        return false;
      }

      if (this.form.cmnd == "") {
        this.showAlert("CMND không được để trống", "warning");
        return false;
      }

      if (this.form.noiCap == "") {
        this.showAlert("Nơi cấp cmnd không được để trống", "warning");
        return false;
      }

      if (this.form.ngayCap == "") {
        this.showAlert("Ngày cấp cmnd không được để trống", "warning");
        return false;
      }

      if (this.form.phone == "") {
        this.showAlert("Số điện thoại không được để trống", "warning");
        return false;
      }

      if (this.form.luongNgay == "") {
        this.showAlert("Lương ngày không được để trống", "warning");
        return false;
      }

      if (this.form.address == "") {
        this.showAlert("Địa chỉ không được để trống", "warning");
        return false;
      }

      if (this.form.ngayVaoLam == "") {
        this.showAlert("Ngày vào làm không được để trống", "warning");
        return false;
      }

      if (this.form.status) {
        if (this.form.ngayNghiLam == "" || this.form.ngayNghiLam == null) {
          this.showAlert(
            "Bạn cần chọn ngày nghỉ làm khi khai báo nhân viên này nghỉ việc",
            "warning"
          );
          return false;
        }
      }

      if (this.form.phong_ban == "") {
        this.showAlert("Bạn chưa chọn phòng ban", "warning");
        return false;
      }

      return true;
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
      this.alert.title = "";
      this.alert.type = "success";
      this.alert.show = false;
    },
    formatBeforeSend() {
      this.form.birthday = this.formatDate(this.form.birthday);
      this.form.ngayCap = this.formatDate(this.form.ngayCap);
      this.form.ngayVaoLam = this.formatDate(this.form.ngayVaoLam);
      if (this.form.ngayNghiLam != "") {
        this.form.ngayNghiLam = null;
      }
      this.form.luongNgay = this.form.luongNgay.replace(".", "");
    },
    formatDate(date) {
      var d = new Date(date),
        month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear();

      if (month.length < 2) month = "0" + month;
      if (day.length < 2) day = "0" + day;

      return [year, month, day].join("-");
    },
    resetForm() {
      this.form = {
        id: "",
        name: "",
        gender: "Nam",
        birthday: "",
        danToc: "",
        cmnd: "",
        noiCap: "",
        ngayCap: "",
        phone: "",
        email: "",
        luongNgay: "",
        address: "",
        ngayVaoLam: "",
        ngayNghiLam: "",
        status: false,
        phong_ban: "",
      };
      setTimeout(() => {
        this.form.luongNgay = "";
      }, 1);
    },
    async getNumberNhanVien() {
      await axios.get("dataNhanVien").then((response) => {
        if (response.data.status == true) {
          this.numberNhanVien = response.data.nhan_vien.length;
          this.form.id = "NV" + (this.numberNhanVien + 1);
        }
      });
    },
    createMaNV() {
      this.getNumberNhanVien();
    },
    handleShowFormCreatePhongBan() {
      this.isShowCreate = true;
      this.isErrorForm = false;
      this.resetFormPhongBan();
    },
    resetFormPhongBan() {
      this.formPB.name = "";
    },
    async handleCreate() {
      if (this.validateForm()) {
        await axios
          .post("createPhongBan", this.formPB)
          .then((response) => {
            if (response.data.status == true) {
              this.isErrorForm = false;
              this.titleMessage = "";
              this.$notify({
                title: "Thông báo",
                message: `Tạo phòng ban "${this.formPB.name}" thành công!`,
                type: "success",
              });
              this.resetFormPhongBan();
              this.getAllPhongBan();
            } else {
              this.isErrorForm = true;
              this.titleMessage = response.data.message;
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
    validateForm() {
      if (this.formPB.name == "") {
        this.isErrorForm = true;
        this.titleMessage = "Tên phòng ban không được để trống";
        return false;
      }

      return true;
    },
    async setTreeView() {
      //this.setTreeTypeAndTreeData();
      await axios.get("dataNhanVien2/").then((response) => {
        this.treeTypes = response.data.treeType;
        this.treeData = response.data.treeData;

        let nghiViec = [];

        for (let i = 0; i < this.treeData.length; i++) {
          this.treeData[i].children.forEach((item2) => {
            if (item2.nghiViec) {
              nghiViec.push(item2.id);
            }
          });
        }

        setTimeout(() => {
          nghiViec.forEach((item) => {
            document.querySelector(`label[for=${item}]`).style.color = "red";
          });
        }, 100);
      });
    },
    async setTreeTypeAndTreeData() {
      let nghiViec = [];

      let dataTreeType = [];
      await axios.get("dataPhongBan").then((response) => {
        if (response.data.status == true) {
          this.treeTypes = [];
          this.treeData = [];

          let dataItem = {
            type: "#",
            valid_children: [],
          };
          let itemValid = [];
          response.data.phong_ban.forEach((item) => {
            itemValid.push("PB" + item.id);
          });
          dataItem.valid_children = itemValid;
          this.treeTypes.push(dataItem);

          response.data.phong_ban.forEach(async (item) => {
            let dataItemChild = {
              type: "PB" + item.id,
              is_content: false,
              icon: "far fa-hospital",
              valid_children: [],
            };
            let itemValidChild = [];

            let dataItemTreeData = {
              id: item.id,
              text: item.name,
              type: "PB" + item.id,
              count: 0,
              is_content: false,
              children: [],
            };
            let itemChild = [];

            await axios.get("dataNhanVien/" + item.id).then((response) => {
              if (response.data.status == true) {
                response.data.nhan_vien.forEach((itemNV) => {
                  let dataItemChildChild = {
                    type: itemNV.ma_nv,
                    icon: "far fa-user",
                    valid_children: [],
                  };
                  this.treeTypes.push(dataItemChildChild);
                  itemValidChild.push(itemNV.ma_nv);

                  let dataItemContent = {
                    id: itemNV.ma_nv,
                    text: itemNV.fullname,
                    type: itemNV.ma_nv,
                    count: 0,
                    is_content: true,
                  };
                  itemChild.push(dataItemContent);

                  dataItemTreeData.children = itemChild;

                  if (itemNV.status_nghi_viec) {
                    nghiViec.push(itemNV.ma_nv);
                  }
                });
                dataItemChild.valid_children = itemValidChild;
                this.treeTypes.push(dataItemChild);
              }
            });
            this.treeData.push(dataItemTreeData);
          });
        }
      });
      setTimeout(() => {
        nghiViec.forEach((item) => {
          document.querySelector(`label[for=${item}]`).style.color = "red";
        });
      }, 2000);
    },
    async editNhanVien(manv) {
      await axios.get("dataNhanVienGetOne/" + manv).then((response) => {
        if (response.data.status == true) {
          let dataNV = response.data.nhan_vien;
          this.form = {
            id: dataNV.ma_nv,
            name: dataNV.fullname,
            gender: dataNV.gender,
            birthday: dataNV.birthday,
            danToc: dataNV.dan_toc,
            cmnd: dataNV.cmnd,
            noiCap: dataNV.noi_cap,
            ngayCap: dataNV.ngay_cap,
            phone: dataNV.phone,
            email: dataNV.email,
            luongNgay: dataNV.luong_ngay,
            address: dataNV.address,
            ngayVaoLam: dataNV.ngay_vao_lam,
            ngayNghiLam: dataNV.ngay_nghi_lam,
            status: dataNV.status_nghi_viec == 1 ? true : false,
            phong_ban: dataNV.id_phong_ban,
          };
          setTimeout(() => {
            this.$forceUpdate();
          }, 1);
        }
      });
    },
    createNewNhanVien() {
      this.edit = false;
      this.resetForm();
      this.createMaNV();
    },
    setNamePhongBan() {
      this.phongBans.forEach((item) => {
        if (item.id == this.form.phong_ban) {
          this.phongBanName = item.name;
        }
      });
      this.$forceUpdate();
    },
    async handleDeleteNhanVien() {
      this.$confirm("Bạn thật sự muốn xóa nhân viên này?", "Cảnh báo", {
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy",
        type: "warning",
      })
        .then(async () => {
          await axios
            .post("deleteNhanVien/" + this.form.id)
            .then((response) => {
              if (response.data.status == true) {
                this.$notify({
                  title: "Thông báo",
                  message: `Xóa nhân viên "${this.form.name}" thành công!`,
                  type: "success",
                });
                this.resetForm();
                this.setTreeView();
              } else {
                this.$notify({
                  title: "Thông báo",
                  message: `Đã xảy ra lỗi trong quá trình thực thi, hãy thử lại sau!`,
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
        .catch();
    },
  },
  mounted() {
    this.getAllPhongBan();
    this.createMaNV();
    this.setTreeView();
    this.setNamePhongBan();
  },
  watch: {
    "form.phong_ban": function () {
      this.setNamePhongBan();
    },
  },
};
</script>

<style lang="scss" scoped>
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