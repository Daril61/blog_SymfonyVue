<template>
    <main>
        <div class="title">
            <div class="background_title"></div>
            <h1>BLOG DE ROMAIN & COLIN</h1>
            <p>Voici notre blog, nous avons fais tout de A à Z sur ce site.</p>
        </div>
        <form class="research_div" @submit.prevent="searching" autocomplete="off">
            <input type="text" name="research" id="research" class="research" placeholder="Search" v-model="search">
        </form>
        <div id="articles_container" >
            <div class="article" v-for="(article) in selected_articles" v-bind:key="article.id">
                <img :src="article.imageURL" alt="" class="image_article">
                <div class="favori_icon" @click="like(article.id)">
                    <i class="fa-solid fa-star" v-if="liked_articles.includes(article.id)"></i>
                    <i class="fa-regular fa-star" v-else></i>
                </div>
                <h1 class="title_article">{{article.title}}</h1>
                <p class="content_article">{{article.content}}</p>
                <p class="date_article">{{article.creation_date}}</p>
            </div>
            
        </div>
        <div id="pages_choice">
        </div>
    </main>
</template>

<script>
import Axios from "axios";

export default {
    name:'Home',
    props: ['id'],
    data() {
        return {
            nbr_article:0,
            all_articles:[],
            selected_articles:[],
            liked_articles:[],
            search:'',

            user_id:null,
            is_connected:false,
        }
    },
    async mounted(){
        await this.init_articles().finally(
            () => this.select_articles()
        )
        

        //Permet d'avoir une grid adapté au nombre d'article
        let list_articles = document.getElementsByClassName("article");
        list_articles[0].classList = ["first_article"];
        let articles_container = document.getElementById("articles_container");
        if(list_articles.length > 4){
            articles_container.style.gridTemplateRows = "auto repeat(2, 1fr)";
        }else{
            articles_container.style.gridTemplateRows = "auto 1fr";
        }

        let parent_btn = document.getElementById("pages_choice")
        let nbr_btn_page = Math.ceil(this.nbr_article/9)
        for(let i=0; (this.id < nbr_btn_page)?i<=nbr_btn_page:i<nbr_btn_page; i++){
            let btn = document.createElement("a");
            if(i == nbr_btn_page){
                btn.innerHTML = '<i class="fa fa-angle-right">';
                btn.classList.add("next");
                btn.href="/home/"+ (parseInt(this.id)+1);
            }else{
                btn.innerHTML = i+1;
                btn.href="/home/"+ (i+1);
            }
            btn.classList.add("page");
            parent_btn.appendChild(btn);
        }

        let list_pages_btn = document.getElementsByClassName("page");
        list_pages_btn[this.id - 1].classList.add("selected_page");

        document.getElementById("home_link").className = ["router-link-exact-active"];

        if(!!localStorage.getItem('token'))
            this.is_connected = true;
    },
    methods: {
        async init_articles(){
            return Axios.get("http://localhost:8000/api/articles").then(res => res.data)
            .then(data => {
                //remplace datas par le nom de l'array contenant tout les articles
                this.all_articles = data['hydra:member']
                //console.log(data['hydra:member'])
                console.log(this.all_articles)

                this.nbr_article = this.all_articles.length
            })
        },
        select_articles(){
            for(let i = 9 * (parseInt(this.id) - 1); i < 9 * parseInt(this.id); i++){
                if(i >= this.all_articles.length)
                    break;
                this.selected_articles.push(this.all_articles[i]);
            }
        },
        async searching(){
            //Potentiellement changer la route
            Axios.post("http://localhost:8000/api/articles/search", this.search).then(res => res.data)
            .then(data => {
                //remplace datas par le nom de l'array contenant tout les articles
                this.all_articles = data.datas
            })

            this.nbr_article = this.all_articles.length
            this.select_articles()
        },
        async get_liked_articles(){
            await Axios.post("http://localhost:8000/api/me",
            localStorage.getItem('token'), 
            {
                headers: {
                    'content-type': 'text/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            }).then(res => res.data)
            .then(data => {
                console.log(data.roles)
                if(!data.roles.includes('ROLE_ADMIN')){
                    router.push('/')
                }
            })
            await Axios.post("http://localhost:8000/api/me", localStorage.getItem('token')).then(res => res.data.id).then(id => {
                this.user_id = id
            })
            if(this.is_connected){
                await Axios.get("http://localhost:8000/api/likes/" + user_id).then(res => res.data)
                .then(data => {
                    //remplace datas par le nom de l'array contenant tout les id des articles
                    this.liked_articles = data.datas
                })
            }
        },
        like(id){
            if(this.is_connected)
                Axios.post("http://localhost:8000/api/like", {user_id : this.user_id, article_id : id}).then(res => console.log(res))
        }
    },
}
</script>

<style>
main{
    width: 100%;
    min-height: 74.6vh;
}

.title{
    width: 100%;
    padding:200px 0;
    position: relative;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap:50px;

    color:var(--second);
    overflow: hidden;
}

.background_title{
    position: absolute;
    left: -1%;
    top:0;
    width: 102%;
    height: 100%;

    z-index: -100;

    filter: blur(3px) invert(15%);

    background: url('@/assets/img/plage_fond_baniere.jpg');
    background-position: center;
    background-repeat:no-repeat;
    background-size:cover;
}

.title h1{
    font-size: 3rem;
}

/*ARTICLES*/

#articles_container{
    width: 100%;
    padding:50px 50px;
    
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-row-gap: 75px;
    grid-column-gap: 45px;
}

.first_article{
    grid-area: 1 / 1 / 2 / 5;

    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-template-rows: repeat(5, 1fr);
    grid-column-gap: 25px;
    grid-row-gap: 25px;

    position: relative;
}

.first_article .image_article{
    grid-area: 1 / 1 / 6 / 3;
}

.first_article .title_article{
    grid-area: 1 / 3 / 2 / 5;
    font-size: 2.7rem;
}

.first_article .content_article{
    grid-area: 2 / 3 / 5 / 5;
    text-align: justify;
    
    color: var(--littles_text);
    font-size: 15px;
    font-weight: 400;
    line-height: 24px;

    position: relative;
}

.first_article .content_article::after{
    content: '';
    display: block;
    position: absolute;
    bottom: 0;

    width: 100%;
    height: 1px;
    background: var(--littles_text);
    opacity: 0.4;
}

.first_article .date_article{
    grid-area: 5 / 3 / 6 / 4;
    align-self: flex-end;
    color: var(--littles_text);
}

.article{
    display: flex;
    flex-direction: column;
    position: relative;
}

.article > *{
    padding: 15px 0;
}

.article .image_article{
    height:180px;
    order: 1;
}

.article .title_article{
    order:2;
    position: relative;
}

.article .title_article::after{
    content: '';
    display: block;
    position: absolute;
    bottom: 0;

    width: 100%;
    height: 1px;
    background: var(--littles_text);
    opacity: 0.4;
}


.article .content_article{
    order: 4;
    position: relative;
    text-align: justify;

    max-height: 175px;
    overflow: hidden;
}

.article .content_article::after{
    content: '';
    display: block;
    position: absolute;
    bottom: 0;

    width: 100%;
    height: 1px;
    background: var(--littles_text);
    opacity: 0.4;
}

.article .date_article{
    order: 5;
}


/*Menu to choose page*/

#pages_choice{
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 80px;

    gap:10px;
}

#pages_choice .page{
    aspect-ratio: 1/1;
    border-radius:100%;
    border: 1px solid var(--blue);
    text-align: center;
    background: none;
    width: 45px;
    cursor: pointer;
    color: var(--blue);
    text-decoration: none;

    display: flex;
    justify-content: center;
    align-items: center;
}

.selected_page, #pages_choice .page:hover{
    background: var(--seco) !important;
    color:var(--second) !important;
}

.research_div{
    margin: 10px 20px;
}

.research{
    border:1px solid var(--first);
    background: var(--second);
    height: 30px;
    width: 250px;
    border-radius: 10px;
    padding:3px 10px;
    text-align: left !important;
    color: var(--first);
}

.favori_icon{
    position: absolute;
    z-index: 100;
    color: var(--second);
    top:3px;
    left:3px;

    cursor: pointer;
}

.favori_icon:hover{
    opacity: 0.8;
}
</style>