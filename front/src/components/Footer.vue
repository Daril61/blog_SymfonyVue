<template>
    <nav class="footer">
        <div class="pages">
            <router-link to="/">Home</router-link>
            <router-link to="/no">Lifestyle</router-link>
            <router-link to="/new">Food</router-link>
            <router-link to="/nouveau">Nature</router-link>
            <router-link to="/test">Photography</router-link>
        </div>
        <div class="contacts">
            <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#"><i class="fa-brands fa-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
            <a href="#"><i class="fa-solid fa-envelope"></i></a>
        </div>
    </nav>
</template>

<script>
export default {
    name:'Footer'
}
</script>

<style>
.footer{
    width: 100%;
    height: 130px;

    display: flex;
    justify-content: space-between;
    align-items: center;

    padding: 0 10px;
    background: var(--first);
    color:var(--second);
}

.pages a, .contacts a{
    color:var(--second);
    text-transform: uppercase;

    font-size: 15px;
    font-weight: 600;
}

.pages a{
    padding: 0 25px;
    text-decoration: none;
}

.contacts a{
    padding: 0 20px;
}
</style>