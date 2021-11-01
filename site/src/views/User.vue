<template>
    <div class="mx-auto mb-4 mt-28 max-w-400">
        <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in" :duration="150">
                <component :is="Component"/>
            </transition>
        </router-view>
    </div>
</template>

<script>
export default {
    name: 'User',
    beforeMount(){
        if(!this.$store.state.user.user) this.$store.dispatch("user/getUser");
        if(!this.$store.state.user.imageList) this.$store.dispatch("user/getImageList");
        if(!this.$store.state.user.config) this.$store.dispatch("user/getConfig");
    }
};
</script>

<style>
</style>