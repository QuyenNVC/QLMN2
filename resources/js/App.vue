<template>
    <div>
        <router-link :to="'/login'">
            <button>abc</button>
        </router-link>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: 'App'
    }
</script>
