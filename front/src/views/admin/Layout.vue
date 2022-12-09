<template>
    <div>
        <nav class="admin_navigation">
            <router-link to="/"><i class="fa-solid fa-house"></i></router-link>
            <router-link to="/admin/post"><i class="fa-solid fa-cloud-arrow-up"></i></router-link>
            <router-link to="/admin/list"><i class="fa-solid fa-pen-to-square"></i></router-link>
        </nav>
        <div class="workbench">
            <RouterView/>
        </div>
    </div>
</template>

<script>
export default {
    name:'Layout'
}
</script>

<style>

.admin_navigation{
    height: 60vh;
    width:180px;
    position: absolute;
    top:50%;
    transform: translateY(-50%);

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-evenly;

    font-size: 4rem;
    text-decoration: none;
    
}

.admin_navigation a{
    color:var(--first);
}

.admin_navigation a:hover{
    opacity: 0.8;
}

.workbench{
    width: 80%;
    height: 80vh;

    position: absolute;
    left:50%;
    top:50%;
    transform: translateX(-50%) translateY(-50%);
}

</style>