<template>
    <div class="container">
        <div class="form">
            <header>Login Form</header>
            <form @submit.prevent="handleLogin">
                <div class="input-field">
                    <input type="text" required v-model="dataLogin.email">
                    <label>Email or Username</label>
                </div>
                <div class="input-field">
                    <input class="pswrd" type="password" required v-model="dataLogin.password">
                    <span class="show">SHOW</span>
                    <label>Password</label>
                </div>
                <div class="button">
                    <div class="inner"></div>
                    <button>LOGIN</button>
                </div>
            </form>
            <div class="auth">Or login with</div>
            <div class="links">
                <div class="facebook">
                    <i class="fab fa-facebook-square"><span>Facebook</span></i>
                </div>
                <div class="google">
                    <i class="fab fa-google-plus-square"><span>Google</span></i>
                </div>
            </div>
            <div class="signup">
                Not a member? <a href="#">Signup now</a>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, inject } from 'vue'
import router from "@/router";
import { isEmpty } from "lodash";
import { login } from "@/api";
import { useRouter, useRoute } from "vue-router";
import { useStore } from "vuex";
import {ROUTER_PATH, MODULE_STORE, PAGE_DEFAULT, TYPE_USER} from "@/const";
import {getToken, removeToken, setToken} from "@/utils/authToken";


export default {
    name: "Login",
    setup() {
        const submitted = ref(false)
        const dataLogin = ref({})
        const store = useStore();
        const router = useRouter();
        const toast = inject("$toast");

        const setLoadingPage = (isLoading) => {
            store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = isLoading
        }

        const handleLogin = async (event) => {
            event.preventDefault();
            if (isEmpty(dataLogin)) {
                return;
            }
            try {
                store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = true;
                const form = new FormData();
                form.append("email", dataLogin.value.email);
                form.append("password", dataLogin.value.password);
                const response = await login(form);
                const { access_token, expires_in } = response;
                if (getToken(TYPE_USER.USER)) removeToken(TYPE_USER.USER);
                setToken(access_token, expires_in, TYPE_USER.ADMIN);
                store.state[MODULE_STORE.AUTH.NAME].isAuthenticated = true;
                await router.push(ROUTER_PATH.ADMIN);
                toast.success("Login successful!");
                store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
            } catch (errors) {
                const error = errors.message || errors.error || this.$t("common.has_error");
                toast.error(error);
                store.state[MODULE_STORE.COMMON.NAME].isLoadingPage = false;
            }
        }

        return {dataLogin, handleLogin, setLoadingPage}
    }
}

</script>

<style scoped>
*{
    margin: 0;
    padding: 0;
    border-radius: 5px;
    box-sizing: border-box;
}
.container {
    height: 100vh;
    display: flex;
    align-items: center;
    text-align: center;
    font-family: sans-serif;
    justify-content: center;
    background: url("../../assets/images/background/login-bg.jpg");
    background-size: cover;
    background-position: center;
}
.form{
    position: relative;
    width: 400px;
    background: white;
    padding: 60px 40px;
}
header{
    font-size: 40px;
    margin-bottom: 60px;
    font-family: 'Montserrat', sans-serif;
}
.input-field, form .button{
    margin: 25px 0;
    position: relative;
    height: 50px;
    width: 100%;
}
.input-field input{
    height: 100%;
    width: 100%;
    border: 1px solid silver;
    padding-left: 15px;
    outline: none;
    font-size: 19px;
    transition: .4s;
}
input:focus{
    border: 1px solid #1DA1F2;
}
.input-field label, span.show{
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
}
.input-field label{
    left: 15px;
    pointer-events: none;
    color: grey;
    font-size: 18px;
    transition: .4s;
}
span.show{
    right: 20px;
    color: #111;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    user-select: none;
    visibility: hidden;
    font-family: 'Open Sans', sans-serif;
}
input:valid ~ span.show{
    visibility: visible;
}
input:focus ~ label,
input:valid ~ label{
    transform: translateY(-33px);
    background: white;
    font-size: 16px;
    color: #1DA1F2;
}
form .button{
    margin-top: 30px;
    overflow: hidden;
    z-index: 111;
}
.button .inner{
    position: absolute;
    height: 100%;
    width: 300%;
    left: -100%;
    z-index: -1;
    transition: all .4s;
    background: -webkit-linear-gradient(right,#00dbde,#fc00ff,#00dbde,#fc00ff);
}
.button:hover .inner{
    left: 0;
}
.button button{
    width: 100%;
    height: 100%;
    border: none;
    background: none;
    outline: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    font-family: 'Montserrat', sans-serif;
}
.container .auth{
    margin: 35px 0 20px 0;
    font-size: 19px;
    color: grey;
}
.links{
    display: flex;
    cursor: pointer;
}
.facebook, .google{
    height: 40px;
    width: 100%;
    border: 1px solid silver;
    border-radius: 3px;
    margin: 0 10px;
    transition: .4s;
}
.facebook:hover{
    border: 1px solid #4267B2;
}
.google:hover{
    border: 1px solid #dd4b39;
}
.facebook i, .facebook span{
    color: #4267B2;
}
.google i, .google span{
    color: #dd4b39;
}
.links i{
    font-size: 23px;
    line-height: 40px;
    margin-left: -90px;
}
.links span{
    position: absolute;
    font-size: 17px;
    font-weight: bold;
    padding-left: 8px;
    font-family: 'Open Sans', sans-serif;
}
.signup{
    margin-top: 50px;
    font-family: 'Noto Sans', sans-serif;
}
.signup a{
    color: #3498db;
    text-decoration: none;
}
.signup a:hover{
    text-decoration: underline;
}

</style>
