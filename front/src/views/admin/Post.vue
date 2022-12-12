<template>
    <div class="post">
        <form class="big_view" v-if="big_view" @submit.prevent="post">
            <div class="big_view_image">
                <img :src="this.article.imageURL" alt="" style="display:block">
                <div class="choice">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-vert.jpg" @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-vert.jpg')">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac.jpg" @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac.jpg')">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-soleil.jpg"  @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-soleil.jpg')">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-geler.jpg" @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-geler.jpg')">
                </div>
                <button class="delete_img" @click="delete_image" v-if="this.image_gived()"><i class="fa-solid fa-x"></i></button>
            </div>
            <div class="big_view_title">
                <input type="text" name="title" v-model="article.title" maxlength="19">
            </div>
            <div class="big_view_content">
                <textarea name="content" v-model="article.content"></textarea>
            </div>
            <button type="submit" class="big_btn_submit">POST</button>
        </form>
        <form class="little_view" v-else @submit.prevent="post">
            <div class="little_view_image">
                <img :src="this.article.imageURL" alt="" style="display:block">
                <div class="choice">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-vert.jpg" @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-vert.jpg')">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac.jpg" @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac.jpg')">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-soleil.jpg"  @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-soleil.jpg')">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-geler.jpg" @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-geler.jpg')">
                </div>
                <button class="delete_img" @click="delete_image" v-if="this.image_gived()"><i class="fa-solid fa-x"></i></button>
            </div>
            <div class="little_view_title">
                <input type="text" name="title" v-model="article.title" maxlength="19">
            </div>
            <div class="little_view_content">
                <textarea name="content" v-model="article.content"></textarea>
            </div>
            <button type="submit" class="little_btn_submit">POST</button>
        </form>

        <button class="change_view" @click="this.big_view = !this.big_view">Change View</button>
    </div>
</template>

<script>
import Axios from "axios";
export default {
    name:'Post',
    data() {
        return {
            big_view:true,
            is_connected:false,
            article:{
                user:"/api/users/",
                title:'',
                content:'',
                imageURL:'',
                creationDate:"2022-12-12T08:32:47.949Z"
            }
        }
    },
    async mounted(){ 
        if(!!localStorage.getItem('token'))
            this.is_connected = true;
    },
    methods: {
        image_gived(){
            if(this.article.imageURL !== ''){
                return true;
            }
            return false;
        },
        select_pic(src_img){
            this.article.imageURL = src_img;
            document.getElementsByClassName("choice")[0].style.display = "none";
        },
        async getUserID() {
            if(this.is_connected) 

            return await Axios.post("http://localhost:8000/api/me",
            localStorage.getItem('token'), 
            {
                headers: {
                    'content-type': 'text/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            }).then(res => res.data)
            .then(data => {
                console.log(data.id)
                this.article.user += data.id
            })
        },
        async post(){
            this.getUserID().finally(() => {
                console.log(this.article)

                Axios.post("http://localhost:8000/api/articles", this.article)
                .then(
                    res => console.log(res)
                ).finally(
                    () => {
                        this.$router.push('/admin/list')
                    }
                )
            })
        },
        delete_image(){
            this.article.imageURL = '';
            document.getElementsByClassName("choice")[0].style.display = "grid";
        }
    },
}
</script>

<style>
.post{
    width: 100%;
    height: 100%;

    display: flex;
    justify-content: center;
    align-items: center;

    position: relative;
}

.big_view{
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-template-rows: repeat(5, 1fr);
    grid-column-gap: 25px;
    grid-row-gap: 25px;

    width: 1500px;
    height: 450px;
}

.big_view_image{
    width:100%;
    height:100%;
    grid-area: 1 / 1 / 6 / 3;
    position: relative;
}

.big_view .input_img{
    position: absolute;
    top:0;
    left:0;
    width: 100%;
    height: 100%;

    border:1px solid var(--first);
}

.big_view_image img{
    width:100%;
    height: 100%;
    object-fit: cover;
}

.big_view_title{
    grid-area: 1 / 3 / 2 / 5;
    font-size: 2.7rem;

    width: 100%;
    height: 100%;

    margin-top:25px;
}

.big_view_title input{
    width: calc(100% - 25px);
    height: 50px;

    font-size: 2.7rem;
    font-weight: 600;
}

.big_view_content{
    width: calc(100% - 25px);
    height: 100%;
    position: relative;
    grid-area: 2 / 3 / 5 / 5;
}

.big_view_content textarea{
    resize: none;
    text-align: justify;

    color: var(--littles_text);
    font-size: 15px;
    font-weight: 400;
    line-height: 24px;

    width: 100%;
    height: 100%;
}

.big_btn_submit{
    grid-area: 5 / 3 / 6 / 5;
    margin: auto;

    border: 1px solid var(--first);
    border-radius: 5px;
    background: none;
    font-size:1.6rem;
    
    padding: 20px 40px;
    cursor: pointer;
}

.big_btn_submit:hover{
    opacity: 0.7;
}

.little_view{
    display: flex;
    flex-direction: column;
    align-items: center;
    width:400px;
}

.little_view > *{
    padding: 15px 0;
}

.little_view_image img{
    width:100%;
    height: 100%;
}

.little_view_title{
    width:100%;
}
.little_view_title input{
    width:100%;
    font-size: 2rem;
    font-weight: 600;
}
.little_view_content{
    width: 100%;
    height: 200px;
}
.little_view_content textarea{
    resize: none;
    text-align: justify;

    color: var(--littles_text);
    font-size: 15px;
    font-weight: 400;
    line-height: 24px;

    width: 100%;
    height: 100%;
}

.little_btn_submit{
    border: 1px solid var(--first);
    border-radius: 5px;
    background: none;
    font-size:1rem;
    
    padding: 20px 40px;
    cursor: pointer;
}

.little_btn_submit:hover{
    opacity: 0.7;
}

.change_view{
    position: absolute;
    bottom: 0px;
    right:60px;

    border: 1px solid var(--first);
    border-radius: 5px;
    background: none;

    padding: 5px 15px;
    cursor: pointer;
}

.change_view:hover{
    opacity: 0.8;
}

.little_view_image{
    position: relative;
    height: 300px;
    width: 400px;
}

.choice{
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(2, 1fr);

    position: absolute;
    z-index: 100;

    width: 100%;
    height: 100%;
}

.choice img{
    width: 100%;
    height: 100%;
    object-fit: cover;

    cursor: pointer;
    transition: all 0.2s;
}

.choice img:hover{
    transform: scale(1.1);
}

.delete_img{
    background: none;
    border: none;
    font-size: 2rem;

    position: absolute;
    left:0px;

    color:var(--second);
    cursor: pointer;
}

.little_view .delete_img{
    bottom:15px;
}
</style>