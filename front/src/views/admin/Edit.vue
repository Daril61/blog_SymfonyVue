<template>
    <div class="post">
        <form class="big_view" v-if="big_view" @submit.prevent="edit">
            <div class="big_view_image">
                <img :src="this.article.img_url" alt="" style="display:block">
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
            <button type="submit" class="big_btn_submit">EDIT</button>
        </form>
        <form class="little_view" v-else @submit.prevent="edit">
            <div class="little_view_image">
                <img :src="this.article.img_url" alt="" style="display:block">
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
            <button type="submit" class="little_btn_submit">EDIT</button>
        </form>

        <button class="change_view" @click="this.big_view = !this.big_view">Change View</button>
    </div>
</template>

<script>
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
            this.article = {
                image:"https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-geler.jpg",
                title:"Titre de l'article. " + this.id,
                content:"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
                date:"25 February 2017"
            }

            console.log(this.article);
        },
        image_gived(){
            if(this.article.image !== ''){
                return true;
            }
            return false;
        },
        select_pic(src_img){
            this.article.img_url = src_img;
            document.getElementsByClassName("choice")[0].style.display = "none";
        },
        edit(){
            console.log(this.article);
        },
        delete_image(){
            this.article.img_url = '';
            document.getElementsByClassName("choice")[0].style.display = "grid";
        }
    },
}
</script>