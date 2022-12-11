<template>
    <div class="register">
        <div class="logo_login_register">
            <router-link to="/">LOGO</router-link>
        </div>
        <form class="register_form" @submit.prevent="register" autocomplete="off">
            <div>
                <label for="username">Pseudo :</label>
                <input type="username" name="username" id="username" v-model="user.username" required>
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" v-model="user.password" required>
            </div>
            <button type="submit" class="register_btn">Register</button>
        </form>
        <router-link to="/login" class="register_login_btn">Login</router-link>
    </div>
</template>

<script>
import Axios from "axios";
export default {
    name:'Register',
    data() {
        return {
            user:{
                username:'',
                password:''
            }
        }
    },
    methods: {
        register(){
            Axios.post("http://localhost:8000/api/register", this.user).then(res => console.log(res))

            this.$router.push('/login')
        }
    },
}
</script>

<style>
.register{
    width: 100%;
    height:100vh;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap:50px;
}

.register_link_home{
    position: absolute;
    left: 50%;
    top:15px;
    transform: translateX(-50%);

    width:100px;
}

.register_form{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-content: center;

    gap:35px;
}

.register_form div{
    position: relative;
}

.register_form div label{
    position: absolute;
    left:calc(-100% - 15px);
    text-align: right;
    width: 100%;

    font-size: 20px;
}

.register_form div input{
    border:none;
    background: var(--first);
    color: var(--second);
    font-size: 16px;
    padding:4px 25px;
    text-align: center;
    border-radius:15px;
}

.register_form div input:focus{
    outline: none;
}

.register_btn{
    color:var(--second);
    background: var(--first);
    padding:10px 40px;
    border:none;
    border-radius: 15px;
    font-size:20px;

    cursor: pointer;
}

.register_container .site_icon>svg{
    width: 120px !important;
    height: 160px !important;
    margin-top: -200px;
    margin-bottom: 100px;
}
</style>