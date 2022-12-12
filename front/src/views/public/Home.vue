<template>
    <main>
        <div class="title">
            <div class="background_title"></div>
            <h1>BLOG DE ROMAIN & COLIN</h1>
            <p>Voici notre blog, nous avons fais tout de A à Z sur ce site.</p>
        </div>
        <div class="recherche_fav">
        <form class="research_div" @submit.prevent="searching" autocomplete="off">
            <input type="text" name="research" id="research" class="research" placeholder="Search" v-model="search">
        </form>
        <button class="select_fav_v1" @click="select_fav()" id="fav_toggle">Favoris</button>
        </div>
       

        <div id="articles_container" v-if="(!show_fav)">
            <div class="article" v-for="(article) in this.selected_articles" v-bind:key="article.id" @click="this.$router.push('/article/'+article.id)">
                <img :src="article.imageURL" alt="" class="image_article">
                <div class="favori_icon" @click="fav(article.id)">
                    <i class="fa-solid fa-star" v-if="this.fav_articlesId.includes(article.id)"></i>
                    <i class="fa-regular fa-star" v-else></i>
                </div>
                <h1 class="title_article">{{article.title}}</h1>
                <p class="content_article">{{article.content}}</p>
                <p class="date_article">{{article.creation_date}}</p>
            </div>
        </div>
        <div id="articles_container" v-else>
            <div class="article" v-for="(article) in this.list_fav_articles" v-bind:key="article.id" @click="this.$router.push('/article/'+article.id)">
                <img :src="article.imageURL" alt="" class="image_article">
                <div class="favori_icon" @click="fav(article.id)">
                    <i class="fa-solid fa-star"></i>
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
            fav_articlesId:[],
            fav_articlesFavId:[],
            list_fav_articles:[],
            search:'',

            user_id:null,
            is_connected:false,
            show_fav:false
        }
    },
    async mounted(){ 
        this.is_connected = false;
        if(!!localStorage.getItem('token'))
            this.is_connected = true;
        
        console.log("Token : " + (this.is_connected == true))

        return await this.init_articles().finally(
            () => {
                console.log("test : " + this.is_connected == false)
                if(this.is_connected) {
                    console.log("gergerbgaerbgçbzeuiezugbdiycfvqsyuev")
                    this.getUserID().finally(
                    () => {
                        console.log("trttt")
                        this.get_fav_articles().finally( () => {
                            
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
                            
                            this.select_articles()
                            this.sort_article()
                        }) 
                    })
                } else {
                    console.log("else")
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
                
                        this.select_articles()
                        this.sort_article()
                }
        })
    },
    methods: {
        async init_articles(){
            return Axios.get("http://localhost:8000/api/articles").then(res => res.data)
            .then(data => {
                console.log(data)
                //remplace datas par le nom de l'array contenant tout les articles
                this.all_articles = data['hydra:member']
                //console.log(data['hydra:member'])
                //console.log(this.all_articles)

                this.nbr_article = this.all_articles.length
            })
        },
        select_articles(){
            this.selected_articles = []

            for(let i = 9 * (parseInt(this.id) - 1); i < 9 * parseInt(this.id); i++){
                if(i >= this.all_articles.length)
                    break;
                this.selected_articles.push(this.all_articles[i]);
            }
        },
        async searching(){
            if(this.search != "") {
                //Potentiellement changer la route
                console.log(this.search)
                return await Axios.post("http://localhost:8000/api/articles/search", {"searchText" : this.search}).then(res => res.data)
                .then(data => {
                    console.log(data)
                    data.forEach(a => {
                        console.log(a)
                    })
                    //remplace datas par le nom de l'array contenant tout les articles
                    this.all_articles = data.datas
                }).finally(() => {
                    this.nbr_article = this.all_articles.length
                    this.select_articles()
                    this.sort_article()
                })

            } else {
                return await this.init_articles().finally(() => {
                    this.select_articles()
                    this.sort_article()
                })
            }
        },
        async getUserID() {
            if(this.is_connected) {

                return Axios.post("http://localhost:8000/api/me",
                localStorage.getItem('token'), 
                {
                    headers: {
                        'content-type': 'text/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(res => res.data)
                .then(data => {
                    this.user_id = data['id']
                })
            } else {
                throw "Not connected"
            }
            
        },
        async get_fav_articles(){
            if(this.is_connected)

            return Axios.get("http://localhost:8000/api/favorites/?user=" + this.user_id).then(res => res.data)
            .then(data => {                
                let tempFav = data["hydra:member"]
                console.log(tempFav)
                tempFav.forEach(f => {
                    var id = f["article"].split("/")[3]

                    this.fav_articlesId.push(parseInt(id))
                    this.fav_articlesFavId.push(f["id"])
                })

            }).finally(
            () => {
                this.list_fav_articles = []
                this.all_articles.forEach(e => {
                    if(Object.values(this.fav_articlesId).includes(e.id))
                        this.list_fav_articles.push(e)
                })
                console.log("Liste des articles en favoris")
                console.log(this.list_fav_articles)
            })
        },
        fav(id){
            if(this.is_connected)
                // Vérification que l'article n'est pas déjà en favoris
                var test = false
                this.list_fav_articles.forEach(e => {
                    if(e['id'] == id) {
                        test = true    
                    }
                })

                // Si il est déjà en favoris alors on le retire
                if(test) {
                    var index = this.fav_articlesId.indexOf(id)
                    var favId = this.fav_articlesFavId[index]
                    Axios.delete("http://localhost:8000/api/favorites/"+ favId)
                    .then(res => {
                        console.log(res)
                        this.fav_articlesFavId.slice(index, 1)
                        this.fav_articlesId.slice(index, 1)
                    })
                } else {
                    console.log("/api/articles/" + id)
                    console.log("/api/users/" + this.user_id)
                    Axios.post("http://localhost:8000/api/favorites", {"article" : "/api/articles/"+id, "user" : "/api/users/"+this.user_id})
                    .then(res => {
                        console.log(res.data)
                        this.get_fav_articles()
                        this.show_fav = !this.show_fav
                        this.show_fav = !this.show_fav
                    })
                }
        },
        select_fav(){
            let btn = document.getElementById("fav_toggle")
            if(this.show_fav)
                btn.classList = ["select_fav_v1"]
            else
                btn.classList = ["select_fav_v2"]

            this.show_fav = !this.show_fav
            this.sort_article()
            this.select_articles();
        },
        sort_article(){
            //Permet d'avoir une grid adapté au nombre d'article
            let list_articles = document.getElementsByClassName("article");
            console.log(list_articles.item(0))
            if(list_articles.length > 0 && document.getElementsByClassName("first_article").length == 0) {
                list_articles[0].classList = ["first_article"];
                let articles_container = document.getElementById("articles_container");
                if(list_articles.length > 4){
                    articles_container.style.gridTemplateRows = "auto repeat(2, 1fr)";
                }else{
                    articles_container.style.gridTemplateRows = "auto 1fr";
                }
            }
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

.recherche_fav{
    display: flex;
    align-items: center;
}

.select_fav_v1,
.select_fav_v2{
    background: none;
    border: 1px solid var(--first);
    border-radius: 5px;
    padding: 3px 5px;
    height: 30px;
    cursor: pointer;
}

.select_fav_v1{
    opacity: 0.2;
}

.select_fav_v2{
    opacity: 1;
}

.select_fav_v1:hover{
    opacity: 0.3;
}

.select_fav_v2:hover{
    opacity: 0.9;
}
</style>