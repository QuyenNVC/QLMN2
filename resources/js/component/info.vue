<template>
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Thông tin trường mầm non</h4>
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
          <div class="card">
            <div class="form-group-input" style="justify-content: flex-start">
              <span class="label-input">Logo: </span>
              <el-upload
                class="d-flex flex-row-reverse"
                :auto-upload="false"
                action=""
                :file-list="fileLists.imgList"
                :on-change="handleChangeImage"
                :on-remove="deleteAttachment"
                :limit="1"
                list-type="picture"
              >
                <el-button size="mini" class="mx-3" type="primary"
                  >Upload</el-button
                >
              </el-upload>
            </div>
            <div>
              <div class="form-group-input">
                <span class="label-input form-label required"
                  >Tên trường:
                </span>
                <el-input
                  placeholder="Tên trường"
                  v-model="form.school_name"
                  @input.native="handleChangeInput"
                  name="school_name"
                ></el-input>
              </div>
              <p class="text-danger px-20 text-error" v-if="errors.school_name">
                {{ errors.school_name }}
              </p>
            </div>
            <div>
              <div class="form-group-input">
                <span class="label-input form-label required">Địa chỉ: </span>
                <el-input
                  placeholder="Địa chỉ"
                  v-model="form.address"
                  @input.native="handleChangeInput"
                  name="address"
                ></el-input>
              </div>
              <p class="text-danger px-20 text-error" v-if="errors.address">
                {{ errors.address }}
              </p>
            </div>
            <div class="form-group-input">
              <span class="label-input">Website: </span>
              <el-input
                placeholder="Website"
                v-model="form.website"
                @input.native="handleChangeInput"
                name="website"
              ></el-input>
            </div>
            <div>
              <div class="form-group-input">
                <span class="label-input form-label required"
                  >Điện thoại:
                </span>
                <el-input
                  placeholder="Điện thoại"
                  v-model="form.phone_number"
                  @input.native="handleChangeInput"
                  name="phone_number"
                ></el-input>
              </div>
              <p
                class="text-danger px-20 text-error"
                v-if="errors.phone_number"
              >
                {{ errors.phone_number }}
              </p>
            </div>
            <div class="form-group-input">
              <span class="label-input">Fax: </span>
              <el-input
                placeholder="Fax"
                v-model="form.fax"
                @input.native="handleChangeInput"
                name="fax"
              ></el-input>
            </div>
            <div class="form-group-input">
              <span class="label-input">Người đại diện: </span>
              <el-input
                placeholder="Người đại diện"
                v-model="form.representatives"
                @input.native="handleChangeInput"
                name="representatives"
              ></el-input>
            </div>
            <div class="form-group-input">
              <span class="label-input">Chức danh: </span>
              <el-input
                placeholder="Chức danh"
                v-model="form.title"
                @input.native="handleChangeInput"
                name="title"
              ></el-input>
            </div>
            <div class="form-group-button">
              <!-- <el-button @click="isShowCreate = false">Hủy</el-button> -->
              <el-button
                :disabled="!isChanged"
                type="primary"
                size="small"
                @click="handleSubmit()"
                class="mt-3"
                >Lưu</el-button
              >
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
    <footer-qlmn></footer-qlmn>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
  </div>
</template>

<script>
import axios from "axios";
import { errorMessage } from "../errors/error-code";
import footerQLMN from "../layouts/footer-qlmn.vue";
export default {
  name: "info",
  props: ["id_coso"],
  components: {
    "footer-qlmn": footerQLMN,
  },
  data() {
    return {
      form: {
        id: "",
        school_name: "",
        address: "",
        website: "",
        phone_number: "",
        fax: "",
        representatives: "",
        title: "",
      },
      image: {
        name: "",
        content: "",
      },
      fileLists: {
        imgList: [],
      },
      isChanged: false,
      errors: {
        school_name: "",
        address: "",
        phone_number: "",
      },
    };
  },
  watch: {
    search: function () {
      if (this.search != "") {
        this.pagedTableData(5);
        this.isSetPage = false;
      } else {
        this.pagedTableData(1);
        this.isSetPage = true;
      }
    },
    page: function () {
      this.pagedTableData(1);
    },
  },
  methods: {
    handleChangeInput(e) {
      this.isChanged = true;
      this.errors[e.target.name] = "";
      this.validateForm(e.target.name);
    },
    // Method upload image
    handleChangeImage(file) {
      const isCorrectType =
        file.name.endsWith(".png") ||
        file.name.endsWith(".jpg") ||
        file.name.endsWith(".jpeg");
      const isCorrectSize = file.size / 1024 / 1024 < 20;
      if (!isCorrectType) {
        alert("Hình ảnh không hợp lệ");
        this.fileLists.imgList = [];
        return false;
      }

      if (!isCorrectSize) {
        alert("Vui lòng chọn file có kích thước nhỏ hơn 20MB");
        this.fileLists.imgList = [];
        return false;
      }

      this.image.name = file.name;

      let reader = new FileReader();
      reader.readAsDataURL(file.raw);
      reader.onload = (e) => {
        this.image.content = e.target.result;
        this.isChanged = true;
      };
    },
    deleteAttachment() {
      this.image.name = "";
      this.image.content = "";
    },
    // End method upload image
    fetchData() {
      axios
        .get("/dataInfo/" + this.form.id)
        .then((response) => {
          if (response.data.status == true) {
            for (let key in this.form) {
              this.form[key] = response.data.item[key];
            }
            if (response.data.item.logo) {
              this.fileLists.imgList = [
                {
                  name: response.data.item.logo,
                  url: response.data.item.urlLogo,
                },
              ];
            }
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
      if (this.isChanged) {
        axios
          .post("/submitInfo", {
            info: this.form,
            image: this.image,
          })
          .then((response) => {
            if (response.data.status) {
              this.$notify({
                title: "Thông báo",
                message: this.form.id
                  ? "Cập nhật thông tin cơ sở thành công"
                  : "Thêm cơ sở mới thành công",
                type: "success",
              });
              this.form.id = response.data.id_coso;
              this.fetchData();
              this.isChanged = false;
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
    validateForm(name) {
      let regexPhoneNumber = /(84|0[3|5|7|8|9])+([0-9]{8})\b/g;
      // let regexLetter = /^[a-z A-Z]+$/;
      let flag = true;
      if (this.form.school_name == "" && name == "school_name") {
        // this.isErrorForm = true;
        // this.titleMessage = "Tên trường không được để trống";
        this.errors.school_name = "Tên trường không được để trống";
        this.isChanged = false;
        flag = flag & false;
      }
      if (this.form.address == "" && name == "address") {
        // this.isErrorForm = true;
        // this.titleMessage = "Địa chỉ không được để trống";
        this.errors.address = "Địa chỉ không được để trống";
        this.isChanged = false;
        flag = flag & false;
      }
      if (this.form.phone_number == "" && name == "phone_number") {
        // this.isErrorForm = true;
        // this.titleMessage = "Số điện thoại không được để trống";
        this.errors.phone_number = "Số điện thoại không được để trống";
        this.isChanged = false;
        flag = flag & false;
      }
      if (
        !this.form.phone_number.match(regexPhoneNumber) &&
        name == "phone_number"
      ) {
        // this.isErrorForm = true;
        // this.titleMessage = "Số điện thoại không đúng định dạng";
        this.errors.phone_number = "Số điện thoại không đúng định dạng";
        this.isChanged = false;
        flag = flag & false;
      }

      return flag;
    },
    handleShowFormCreate() {
      this.isShowCreate = true;
      this.isShowCreateTitle = true;
      this.isErrorForm = false;
      this.resetForm();
    },
  },
  created() {
    if (this.id_coso) {
      this.form.id = this.id_coso;
      this.fetchData();
    }
  },
};
</script>
<style lang="scss" scoped>
.text-error {
  padding-left: calc(20% + 125px);
  margin: 0;
}
</style>