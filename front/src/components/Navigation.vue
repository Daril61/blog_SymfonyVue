<template>
    <nav class="navigation">
        <div class="logo">
            <h1>LOGO</h1>
        </div>
        <div class="links">
            <router-link to="/" id="home_link">Home</router-link>
            <router-link to="/admin/post" v-if="is_admin">Poster</router-link>
            <router-link to="/admin/list" v-if="is_admin">List</router-link>
            <button class="logout" @click="logout" v-if="is_connected">Log out</button>
            <router-link to="/login" v-else>Login</router-link>
        </div>
    </nav>
</template>

<script>
export default {
    name:'Home',
    data() {
        return {
            is_connected:true,
            is_admin:true,
        }
    },
    methods: {
        logout(){
            this.is_connected = false;
        }
    },
}
</script>

<style>
.navigation{
    width: 100%;
    height: 70px;

    display: flex;
    justify-content: space-between;
    align-items: center;
}
 
.logo{
    height: 100%;
    width: 150px;

    display: flex;
    justify-content: center;
    align-items: center;
}

.links{
    height:100%;
    display: flex;
    align-items: center;
}

.links a, .logout{
    text-decoration: none;
    text-transform: uppercase;
    color: var(--first);
    
    display: flex;
    align-items: center;

    height:100%;
    padding:0 50px;
    
    font-size: 15px;
    font-weight: 600;
}

.logout{
    background: none;
    border: none;
    cursor: pointer;
}

.navigation .router-link-exact-active, .links a:hover,
.logout:hover{
    color: var(--second) !important;
    background: var(--blue) !important;
}
</style>