<template>
    <div class="post">
        <form class="big_view" v-if="big_view" @submit.prevent="edit">
            <div class="big_view_image">
                <img :src="this.article.imageURL" alt="" style="display:block">
                <div class="choice">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-vert.jpg" @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-vert.jpg')">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac.jpg" @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac.jpg')">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-soleil.jpg"  @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-soleil.jpg')">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-geler.jpg" @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-geler.jpg')">
                </div>
                <button class="delete_img" @click="delete_image" v-if="this.image_given()"><i class="fa-solid fa-x"></i></button>
            </div>
            <div class="big_view_title">
                <input type="text" name="title" v-model="article.title" maxlength="19">
            </div>
            <div class="big_view_content">
                <textarea name="content" v-model="article.content"></textarea>
            </div>
            <button type="submit" class="big_btn_submit">EDIT</button>
        </form>
        <form class="little_view" v-else @submit.prevent="edit">
            <div class="little_view_image">
                <img :src="this.article.imageURL" alt="" style="display:block">
                <div class="choice">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-vert.jpg" @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-vert.jpg')">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac.jpg" @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac.jpg')">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-soleil.jpg"  @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-soleil.jpg')">
                    <img src="https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-geler.jpg" @click="select_pic('https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-geler.jpg')">
                </div>
                <button class="delete_img" @click="delete_image" v-if="this.image_given()"><i class="fa-solid fa-x"></i></button>
            </div>
            <div class="little_view_title">
                <input type="text" name="title" v-model="article.title" maxlength="19">
            </div>
            <div class="little_view_content">
                <textarea name="content" v-model="article.content"></textarea>
            </div>
            <button type="submit" class="little_btn_submit">EDIT</button>
        </form>

        <button class="change_view" @click="this.big_view = !this.big_view">Change View</button>
    </div>
</template>

<script>
import Axios from "axios";
export default {
    name:'Edit',
    props:['id'],
    data() {
        return {
            big_view:true,
            article:{}
        }
    },
    async mounted() {
        await this.get_article();
    },
    methods: {
        get_article(){
            Axios.get("http://localhost:8000/api/articles/" + this.id).then(res => res.data)
            .then(data => {
                console.log(data)
                //remplace article par le nom de l'object article
                this.article = data
            })
        },
        image_given(){
            console.log("image_given")
            console.log(this.article)
            console.log(this.article['imageURL'])
            if(this.article.imageURL != ''){
                return true;
            }
            return false;
        },
        select_pic(src_img){
            this.article.imageURL = src_img;
            document.getElementsByClassName("choice")[0].style.display = "none";
        },
        edit(){
            Axios.put("http://localhost:8000/api/articles/" + this.id, this.article).then(res => console.log(res)).finally(
                () => {
                    this.$router.push('/admin/list')
                }
            )
        },
        delete_image(){
            this.article.imageURL = '';
            document.getElementsByClassName("choice")[0].style.display = "grid";
        }
    },
}
</script>