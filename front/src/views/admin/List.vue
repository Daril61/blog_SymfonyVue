<template>
    <div class="list_article">
        <table class="table_list">
            <thead>
            <th>Title</th>
            <th>Content</th>
            <th>Date</th>
            <th>Edit</th>
            <th>Delete</th>
            </thead>
            <tbody>
            <tr v-for="(article) in list_article" v-bind:key="article.id"> 
                <td>{{ article.title }}</td>
                <td v-if="article.content.length<51">{{ article.content}}</td>
                <td v-else>{{ article.content.substring(0,50)+".." }}</td>
                <td>{{ article.creation_date }}</td>
                <td class="line_btn"><router-link :to="`/admin/edit/${article.id}`"><i class="fa-solid fa-pen-to-square"></i></router-link></td>
                <td class="line_btn"><button v-on:click="delete_article(article.id)" class="list_btn"><i class="fa-solid fa-trash-can td_function"></i></button></td>
            </tr>
            </tbody>

        </table>
    </div>
</template>

<script>
import Axios from "axios";
import { getCurrentInstance } from 'vue';
export default {
    name:'List',
    data(){
        return{
            list_article:[]
        }
    },
    async mounted() {
        await this.create_articles();
    },
    methods: {
        create_articles(){
            Axios.get("http://localhost:8000/api/articles").then(res => res.data)
            .then(data => {
                console.log(data["hydra:member"])
                //remplace datas par le nom de l'array contenant tout les articles
                this.list_article = data["hydra:member"]
            })
        },
        delete_article(id){
            Axios.delete("http://localhost:8000/api/articles/" + id).then(res => console.log(res)).finally(() => 
            {
                window.location.reload();
            })
        }
    },
}
</script>

<style>
.list_article{
    overflow-y: scroll;
    height: 100%;
}

.list_admin_articles_container{
    min-height: 100vh;
    box-sizing: border-box;

    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;

    gap:65px;
    color: var(--first);
}

tr{
    height:25px;
}

td a, .td_function{
    text-decoration: none;
    color:var(--first);
    cursor:pointer;
}

td a:active, .td_function:active{
    color:var(--second)
}

.list_btn{
    border:none;
    background: none;
}

.table_list {
    table-layout:fixed;
    margin: 15px auto;
}

.table_list {
    color: var(--first);
    background: var(--second);
    font-size: 12pt;
    border-collapse: collapse;
}

.table_list .table_tiltes th, .table_list tfoot th {
    color: var(--first);
    background: var(--second);
}

.table_list caption {
    padding:.6em;
}

.table_list th, .table_list td {
    padding: .6em;
    border: 1px solid var(--first);
}

.line_btn{
    text-align: center;
}

.td_function{
    
    color:rgb(163, 6, 6) !important;
}
</style>