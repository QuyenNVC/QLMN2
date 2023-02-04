<template>
    <div class="formParentsSignUp">
        <div class="card regform wow bounce animated" data-wow-delay="0.05s">
            <div class="card-body regform">
                <div class="myform form ">
                <div class="logo mb-3">
                    <div class="col-md-12 text-center">
                        <div class="logo_wrapper text-center bg-white">
                            <img :src="urlLogo()" alt="">
                        </div>
                    </div>
                </div>
                <form action="" name="registration">
                    <div class="input-group mt-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-address-card"></i></span>
                        <input type="input" class="form-control" id="floatingInput" placeholder="Họ và tên" name="fullname" v-model="form.fullname" @keyup="handleChangeInput" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <p class="text-white" v-if="errors.fullname">{{ errors.fullname }}</p>
                    <div class="input-group mt-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                        <input type="input" class="form-control" id="floatingInput" placeholder="Tài khoản" name="phone_number" v-model="form.username" @keyup="handleChangeInput" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <p class="text-white" v-if="errors.username">{{ errors.phone_number }}</p>
                    <div class="input-group mt-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" class="form-control" placeholder="Email" name="email" v-model="form.email" @keyup="handleChangeInput" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <p class="text-white" v-if="errors.email">{{ errors.email }}</p>
                    <div class="col-md-12 text-center mt-3">
                        <button type="button" class="btn-submit" @click="submit(event)">Lấy mật khẩu</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import {errorMessage} from '../errors/error-code'
    export default {
        name: 'forget-password',
        data() {
            return {
                form: {
                    fullname: '',
                    username: '',
                    email: '',
                },
                errors: {
                    fullname: '',
                    username: '',
                    email: '',
                },
                validForm: true
            }
        },
        mounted() {
            
        },
        methods: {
            urlLogo() {
                return window.location.origin+'/img/logo_smartkids.png'
            },
            handleChangeInput(event) {
                let {name, value, type, placeholder} = event.target;
                this.errors[name] = '';
                let regexLetter = /^[a-z A-Z]+$/;
                let regexEmail = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
                this.validForm = true;
                if(value.trim() === '') {
                    this.errors[name] = `${placeholder} không được để trống!`;
                    this.validForm = false;
                }

                if(name === 'fullname' && !this.removeAscent(value).match(regexLetter)) {
                    this.errors[name] = `${placeholder} không đúng định dạng!`;
                    this.validForm = false;
                }

                if(name === 'email' && !value.match(regexEmail)) {
                    this.errors[name] = `${placeholder} không đúng định dạng!`;
                    this.validForm = false;
                }
            },
            async submit(event) {
                if(this.validForm) {
                    await axios.post('submitForgetPassword', this.form).then((response) => {
                        if(response.data.status) {
                            this.$notify({
                                title: "Thông báo",
                                message: `Vui lòng truy cập vào email để đổi mật khẩu tài khoản!`,
                                type: "success",
                            });
                        } else {
                            this.$notify({
                                title: "Thông báo",
                                message: response.data.message,
                                type: "warning",
                            });
                        }
                    }).catch(e => {
                        this.$notify({
                            title: "Thông báo",
                            message: errorMessage[e.response.status],
                            type: "warning",
                        });
                    });
                }
            },
            removeAscent (str) {
                if (str === null || str === undefined) return str;
                str = str.toLowerCase();
                str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
                str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
                str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
                str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
                str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
                str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
                str = str.replace(/đ/g, "d");
                return str;
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import url(https://fonts.googleapis.com/css2?family=Roboto+Serif&display=swap);
    .formParentsSignUp {
        display: flex;
        justify-content: center;
        align-items: center;
        background: #0437A4;
        height: 100vh;
        .card {
            width: 30%;
            background: transparent;
            border: none;
            color: white;
            .logo_wrapper {
                width: 60%;
                aspect-ratio: 1;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 50%;
                margin: auto;
                margin-bottom: 3rem;
                img {
                    display: block;
                    width: 100%;
                    height: auto;
                    margin: auto;
                }
            }
            .input-group {
                border-radius: 500px;
                .input-group-text {
                    background: #fff;
                    border-radius: 500px;
                    border-top-right-radius: 0;
                    border-bottom-right-radius: 0;
                    border: none;
                    padding: 0.75rem 0.75rem;
                }
                .form-control {
                    background: #fff;
                    border-radius: 500px;
                    border: none;
                }
            }
            button.btn-submit {
                color: #0437A4;
                background: #fff;
                border-radius: 500px;
                display: inline-block;
                width: 100%;
                height: 50px;
                line-height: 50px;
                text-align: center;
                font-weight: bold;
                border: none;
            }
            .form-control {
                &:focus {
                    box-shadow: none;
                }
            }
            @media (max-width: 920px) {
                width: 60%;
            }
            @media (max-width: 540px) {
                width: 90%
            }
        }
        .regform{
            // box-shadow: 0px 8px 9px 0px rgba(69, 69, 69, 0.25);
        }
        .sign{
            color: #000;
        }
    }
</style>