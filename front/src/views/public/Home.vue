<template>
    <main>
        <div class="title">
            <div class="background_title"></div>
            <h1>BLOG DE ROMAIN & COLIN</h1>
            <p>Voici notre blog, nous avons fais tout de A à Z sur ce site.</p>
        </div>
        <div id="articles_container" >
            <div class="article" v-for="(article) in selected_articles" v-bind:key="article.id">
                <img :src="article.image" alt="" class="image_article">
                <h1 class="title_article">{{article.title}}</h1>
                <p class="content_article">{{article.content}}</p>
                <p class="date_article">{{article.date}}</p>
            </div>
            
        </div>
        <div id="pages_choice">
        </div>
    </main>
</template>

<script>
export default {
    name:'Home',
    props: ['id'],
    data() {
        return {
            nbr_article:0,
            all_articles:[],
            selected_articles:[]
        }
    },
    async mounted(){
        await this.init_articles();
        await this.select_articles();

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
    },
    methods: {
        init_articles(){
            let nombre_artc = 15
            this.nbr_article = nombre_artc;
            let list_imgs = ["https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-geler.jpg", "https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac.jpg", "https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-vert.jpg", "https://projetweb-romain-colin.s3.eu-west-3.amazonaws.com/foret-lac-soleil.jpg"]
            for(let i = 0; i < nombre_artc; i++){
                let article = {
                    image:list_imgs[Math.floor(Math.random() * list_imgs.length)],
                    title:"Titre de l'article. " + i,
                    content:"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
                    date:"25 February 2017",
                    id:i
                }
                this.all_articles.push(article);
            }
        },
        select_articles(){
            for(let i = 9 * (parseInt(this.id) - 1); i < 9 * parseInt(this.id); i++){
                if(i >= this.all_articles.length)
                    break;
                this.selected_articles.push(this.all_articles[i]);
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
    background: var(--blue) !important;
    color:var(--second) !important;
}

</style>