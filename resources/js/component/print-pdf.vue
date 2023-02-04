<template>
  <div class="container">
    <div class="row">
      <div class="col text-left">Đơn vị: Công ty ABC</div>
    </div>
    <div class="row">
      <div class="col text-left">
        Địa chỉ: 123 Nguyễn Thị Minh Khai, Khóm 4, Phường 5, TP.Trà Vinh
      </div>
    </div>
    <div class="row mt-30">
      <div class="col fs-30 text-center">PHIẾU CHI LƯƠNG</div>
    </div>
    <div class="row">
      <div class="col fs-20 text-center">(tháng {{ thang }} năm {{ nam }})</div>
    </div>
    <div class="row">
      <div class="col text-left">
        <p><strong>Họ và tên:</strong> {{ nhanVien.fullname }}</p>
        <p><strong>Phòng ban:</strong> {{ nhanVien.phong_ban }}</p>
        <p>
          <strong>Số ngày đi làm thực tế:</strong> {{ calculatorSoNgayDiLam(maNv) }} <br />
          <div v-html="renderListChamCong()"></div>
        </p>

        <p>
          <strong>Phụ cấp:</strong> {{ calculatorPhuCap() | formatMoney }}vnd <br />
          <div v-html="renderListPhuCap()"></div>
        </p>

        <p>
          <strong>Tăng/giảm:</strong> {{ calculatorTangGiamLuong() | formatMoney }}vnd <br />
          <div v-html="renderListTangGiam()"></div>
        </p>

        <p>
          <strong>Ứng lương:</strong> {{ calculatorUngLuong() | formatMoney }}vnd <br />
        </p>

        <p><strong>Mức lương ngày:</strong> {{ nhanVien.luong_ngay | formatMoney }}vnd</p>

        <p><strong>Số tiền chi lương:</strong> {{ calculatorTienLuong(maNv) |formatMoney }}vnd</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import mixin from "./mixin.js";
export default {
  name: "print-pdf",
  mixins: [mixin],
  props: {
    maNv: {
      type: String,
    },
    thang: {
      type: String,
    },
    nam: {
      type: String,
    },
  },
  data() {
    return {
      nhanVien: {},
      phuCaps: [],
      test: "",
      hinhThucChamCongs: [],
      chamCong: [],
      phuCapNhanVien: [],
      tangGiamLuongNhanVien: [],
      tangGiamLuong: [],
      ungLuong: [],
      month: this.thang,
      year: this.nam,
    };
  },
  methods: {
    async getNhanVien() {
      await axios.get("/dataNhanVienGetOne/" + this.maNv).then((response) => {
        if (response.data.status == true) {
          this.nhanVien = response.data.nhan_vien;
          this.getAllNhanVienDangLamViecPhuCap();
          this.getAllNhanVienDangLamViecUngLuong();
          this.getAllNhanVienDangLamViecTangGiamLuong();
        }
      });
    },
    setPage(val) {
      this.page = val;
      this.isSetPage = true;
    },
    handleEdit(index, row) {},
    setPhuCap(ma_nv) {
      this.tableData.forEach((item) => {
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
          });
        })
        .catch((e) => {
          this.$notify({
            title: "Thông báo",
            message: errorMessage[e.response.status],
            type: "warning",
          });
        });
    },
    async getAllPhongBan() {
      await axios.get("/dataPhongBan").then((response) => {
        if (response.data.status == true) {
          this.phongBans = response.data.phong_ban;
        }
      });
    },
    async getAllNhanVienDangLamViec() {
      console.log(
        `dataNhanVienDangLamViecPhuCap/${this.month}/${this.year}/${this.phongBan}`
      );
      await axios
        .get(
          `dataNhanVienDangLamViecPhuCap/${this.month}/${this.year}/${this.phongBan}`
        )
        .then((response) => {
          if (response.data.status == true) {
            this.tableData = response.data.nhan_vien;
            for (let i = 0; i < this.tableData.length; i++) {
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
      await axios
        .get("/dataChamCong/" + this.month + "/" + this.year)
        .then((response) => {
          if (response.data.status == true) {
            this.chamCong = response.data.cham_cong;
            this.chamCong.forEach((item, index) => {
              this.chamCong[index] = JSON.parse(`${this.chamCong[index]}`);
              this.$forceUpdate();
            });
          }
        });
    },
    async handleCreate() {
      if (this.validateForm()) {
        await axios
          .post("updatePhuCapNhanVien", {
            thang: this.month,
            nam: this.year,
            phuCap: this.form,
          })
          .then((response) => {
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
          })
          .catch((e) => {
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
      await axios.get("/dataTangGiamLuong").then((response) => {
        if (response.data.status == true) {
          this.tangGiamLuong = response.data.tang_giam_luong;
          for (let i = 0; i < this.tangGiamLuong.length; i++) {
            this.tangGiamLuong[i]["index"] = i + 1;
          }
        }
      });
    },
    async getAllNhanVienDangLamViecPhuCap() {
      console.log(
        `/dataNhanVienDangLamViecPhuCap/${this.month}/${this.year}/${this.nhanVien.id_phong_ban}`
      );
      await axios
        .get(
          `/dataNhanVienDangLamViecPhuCap/${this.month}/${this.year}/${this.nhanVien.id_phong_ban}`
        )
        .then((response) => {
          if (response.data.status == true) {
            this.phuCapNhanVien = response.data.nhan_vien;
            for (let i = 0; i < this.phuCapNhanVien.length; i++) {
              this.phuCapNhanVien[i]["index"] = i + 1;
              if (this.phuCapNhanVien[i].data) {
                this.phuCapNhanVien[i].data = JSON.parse(
                  this.phuCapNhanVien[i].data
                );
              }
            }
          }
        });
    },
    async getAllNhanVienDangLamViecTangGiamLuong() {
      await axios
        .get(
          `/dataNhanVienDangLamViecTangGiamLuong/${this.month}/${this.year}/${this.nhanVien.id_phong_ban}`
        )
        .then((response) => {
          if (response.data.status == true) {
            this.tangGiamLuongNhanVien = response.data.nhan_vien;
            for (let i = 0; i < this.tangGiamLuongNhanVien.length; i++) {
              this.tangGiamLuongNhanVien[i]["index"] = i + 1;
              if (this.tangGiamLuongNhanVien[i].data) {
                this.tangGiamLuongNhanVien[i].data = JSON.parse(
                  this.tangGiamLuongNhanVien[i].data
                );
              }
            }
          }
        });
    },
    async getAllNhanVienDangLamViecUngLuong() {
      console.log(
        `/dataNhanVienDangLamViecUngLuong/${this.month}/${this.year}/${this.nhanVien.id_phong_ban}`
      );
      await axios
        .get(
          `/dataNhanVienDangLamViecUngLuong/${this.month}/${this.year}/${this.nhanVien.id_phong_ban}`
        )
        .then((response) => {
          if (response.data.status == true) {
            this.ungLuong = response.data.nhan_vien;
            for (let i = 0; i < this.ungLuong.length; i++) {
              this.ungLuong[i]["index"] = i + 1;
            }
          }
        });
    },
    async getAllPhuCap() {
      await axios.get("/dataPhuCap").then((response) => {
        if (response.data.status == true) {
          this.phuCaps = response.data.phu_cap;
        }
      });
    },
    async getAllHinhThucChamCong() {
      await axios.get("/dataHinhThucChamCong").then((response) => {
        if (response.data.status == true) {
          this.hinhThucChamCongs = response.data.phu_cap;
        }
      });
    },
    renderListPhuCap() {
      // this.phuCapNhanVien.forEach( (pcnv) => {
      //     if (pcnv.ma_nv == this.maNv) {
      //         let str = "";
      //         if (pcnv.data == null) return str;
      //         str = '( Trong đó: ';

      //         for (const [key, value] of Object.entries(pcnv.data)) {
      //           if (value == true) {
      //             this.phuCaps.forEach((item) => {
      //               if (key == item.id) {
      //                 if (str == "( Trong đó: ") {
      //                   str += item.name + `{ ${item.phu_cap}${item.don_vi_tinh} }`;
      //                 } else {
      //                   str +=
      //                     ", " + item.name + `{ ${item.phu_cap}${item.don_vi_tinh} }`;
      //                 }
      //               }
      //             });
      //           }
      //         }
      //         str += ' )';
      //         return str;
      //     }
      // });
      // return '';

      let stringRender = '<table class="table table-bordered">';
      this.phuCapNhanVien.forEach((item) => {
        if (item.ma_nv == this.maNv) {
          if (item.data) {
            for (const [key, value] of Object.entries(item.data)) {
              if (key != "ma_nv" && key != "name") {
                if (value) {
                  let idPC = key;
                  this.phuCaps.forEach((pc) => {
                    if (key == pc.id) {
                      stringRender += `<tr><td>${
                        pc.name
                      }</td><td>${this.formatMoney(pc.phu_cap)}${
                        pc.don_vi_tinh
                      }</td></tr>`;
                    }
                  });
                }
              }
            }
          }
        }
      });

      stringRender += "</table>";
      return stringRender;
    },
    calculatorSoNgayDiLam(ma_nv) {
      let soNgayDilam = 0;
      this.chamCong.forEach((item) => {
        if (item.ma_nv == ma_nv) {
          for (const [key, value] of Object.entries(item)) {
            if (key.includes("tong_")) {
              let arraySplitFromKey = key.split("tong_");
              let idHTCC = arraySplitFromKey[1];
              this.hinhThucChamCongs.forEach((htcc) => {
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
      let luongNgay = this.nhanVien.luong_ngay;
      let phuCap = 0;
      let tangGiam = 0;
      let ungLuong = 0;

      //phu cap
      this.phuCapNhanVien.forEach((item) => {
        if (item.ma_nv == ma_nv) {
          if (item.data) {
            for (const [key, value] of Object.entries(item.data)) {
              if (key != "ma_nv" && key != "name") {
                if (value) {
                  let idPC = key;
                  this.phuCaps.forEach((pc) => {
                    if (idPC == pc.id) {
                      if (pc.don_vi_tinh === "vnd") {
                        phuCap += Number(pc.phu_cap);
                      } else {
                        phuCap += parseFloat(
                          parseFloat(item.luong_ngay) *
                            (parseFloat(pc.phu_cap) / 100)
                        );
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
      this.tangGiamLuongNhanVien.forEach((item) => {
        if (item.ma_nv == ma_nv) {
          if (item.data) {
            for (const [key, value] of Object.entries(item.data)) {
              if (key != "ma_nv" && key != "name") {
                if (value) {
                  let idPC = key;
                  this.tangGiamLuong.forEach((tg) => {
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
      this.ungLuong.forEach((item) => {
        if (item.ma_nv == ma_nv) {
          if (item.tien_ung != "") {
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
    renderListChamCong() {
      // let stringRender = '( Trong đó: ';

      // this.chamCong.forEach((item) => {
      //     if (item.ma_nv == this.maNv) {
      //     for (const [key, value] of Object.entries(item)) {
      //         if (key.includes("tong_")) {
      //             let arraySplitFromKey = key.split("tong_");
      //             let idHTCC = arraySplitFromKey[1];
      //             this.hinhThucChamCongs.forEach((htcc) => {
      //                 if (idHTCC == htcc.id) {
      //                     if (stringRender == '( Trong đó: ') {
      //                         stringRender += ` ${htcc.name}{ ${value} }`;
      //                     } else {
      //                         stringRender += `, ${htcc.name}{ ${value} }`;
      //                     }
      //                 }
      //             });
      //         }
      //     }
      //     }
      // });

      // stringRender += ' )';
      // return stringRender;

      let stringRender = '<table class="table table-bordered">';
      this.chamCong.forEach((item) => {
        if (item.ma_nv == this.maNv) {
          for (const [key, value] of Object.entries(item)) {
            if (key.includes("tong_")) {
              let arraySplitFromKey = key.split("tong_");
              let idHTCC = arraySplitFromKey[1];
              this.hinhThucChamCongs.forEach((htcc) => {
                if (idHTCC == htcc.id) {
                  stringRender += `<tr><td>${htcc.name}</td><td>${value}</td></tr>`;
                }
              });
            }
          }
        }
      });

      stringRender += "</table>";
      return stringRender;
    },
    renderListTangGiam() {
      let stringRender = '<table class="table table-bordered">';
      this.tangGiamLuongNhanVien.forEach((item) => {
        if (item.ma_nv == this.maNv) {
          if (item.data) {
            for (const [key, value] of Object.entries(item.data)) {
              if (key != "ma_nv" && key != "name") {
                if (value) {
                  let idPC = key;
                  this.tangGiamLuong.forEach((tg) => {
                    if (idPC == tg.id) {
                      stringRender += `<tr><td>${
                        tg.name
                      }</td><td>${this.formatMoney(tg.tang_giam)}</td></tr>`;
                    }
                  });
                }
              }
            }
          }
        }
      });

      stringRender += "</table>";
      return stringRender;
    },
    calculatorPhuCap() {
      let phuCap = 0;

      //phu cap
      this.phuCapNhanVien.forEach((item) => {
        if (item.ma_nv == this.maNv) {
          if (item.data) {
            for (const [key, value] of Object.entries(item.data)) {
              if (key != "ma_nv" && key != "name") {
                if (value) {
                  let idPC = key;
                  this.phuCaps.forEach((pc) => {
                    if (idPC == pc.id) {
                      if (pc.don_vi_tinh === "vnd") {
                        phuCap += Number(pc.phu_cap);
                      } else {
                        phuCap += parseFloat(
                          parseFloat(item.luong_ngay) *
                            (parseFloat(pc.phu_cap) / 100)
                        );
                      }
                    }
                  });
                }
              }
            }
          }
        }
      });

      return phuCap;
    },
    calculatorTangGiamLuong() {
      let tangGiam = 0;
      //tang giam
      this.tangGiamLuongNhanVien.forEach((item) => {
        if (item.ma_nv == this.maNv) {
          if (item.data) {
            for (const [key, value] of Object.entries(item.data)) {
              if (key != "ma_nv" && key != "name") {
                if (value) {
                  let idPC = key;
                  this.tangGiamLuong.forEach((tg) => {
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
      return tangGiam;
    },
    calculatorUngLuong() {
      let ungLuong = 0;
      //ung luong
      this.ungLuong.forEach((item) => {
        if (item.ma_nv == this.maNv) {
          if (item.tien_ung != "") {
            ungLuong += Number(item.tien_ung);
          }
        }
      });

      return ungLuong;
    },
  },
  mounted() {
    this.getNhanVien();
    // this.getAllNhanVienDangLamViec();
    //this.setDefaultFilter();
    this.getAllPhuCap();
    this.getAllHinhThucChamCong();
    this.getAllChamCong();
    this.getAllTangGiamLuong();
    window.onload = function () {
      setTimeout(() => {
        window.print();
      }, 5000);
    };
  },
};
</script>

<style lang="scss" scoped>
.container {
  width: 220mm;
  height: 297mm;
}
</style>
<style>
tr td:nth-child(2) {
  /* text-align: right; */
}
</style>