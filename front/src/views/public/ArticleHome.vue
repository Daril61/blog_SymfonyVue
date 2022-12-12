<template>
    <div class="big_view">
        <div class="big_view_image">
            <img :src="this.article.imageURL" alt="" style="display:block">
        </div>
        <div class="big_view_title">
            <h1>
                {{article.title}}
            </h1>
        </div>
        <div class="big_view_content">
            <p>{{article.content}}</p>
        </div>
    </div>
</template>

<script>
import Axios from "axios";
export default {
    name:'ArticleHome',
    props:['id'],
    data() {
        return {
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
        }
    }
}
</script>

