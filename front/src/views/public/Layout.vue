<template>
    <div id="content">
        <header>
            <Navigation/>
        </header>
        <RouterView/>
        <footer>
            <Footer/>
        </footer>
    </div>
</template>

<script>
import Navigation from '@/components/Navigation.vue'
import Footer from '@/components/Footer.vue'

export default{
    name:'PublicLayout',
    components: {
        Navigation
    }
}
</script>

<style>
#content{
    width: 1400px;
    margin:auto;

    min-height: 100vh;
    position: relative;
}
</style>