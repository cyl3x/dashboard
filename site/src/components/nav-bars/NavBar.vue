<script setup>
import NavIconButton from './NavIconButton.vue';
import UserNavBar from './UserNavBar.vue';
</script>

<template>
<div class="fixed top-0 z-50 w-full h-auto shadow-md">
    <div class="flex justify-center w-full px-4 overflow-hidden h-11 bg-neutral-900">
        <div class="flex justify-between w-full h-full max-w-400">
            <div class="flex w-auto h-full">
                <a class="flex w-auto h-full mx-2" href="/">
                    <Logo class="flex-shrink-0 h-full py-2" />
                    <h1 class="flex-shrink-0 pt-2 text-2xl text-centerr text-neutral-50">{{ nameSpliced }}</h1>
                </a>
            </div>

            <div class="flex justify-end w-auto h-full">
                <NavIconButton icon="layer-group" routeName="Dashboard" />
                <NavIconButton icon="user" routeName="User" :focused="$route.path.includes('/user/')" />
                <NavIconButton icon="sign-out-alt" @click="handleLogout" :focused="false" />
            </div>
        </div>
    </div>
    <transition name="insta" :duration="0">
        <UserNavBar v-if="$route.path.includes('/user/')" style=""></UserNavBar>
    </transition>
</div>
</template>

<script>
export default {
    name: 'NavBar',
    data() {
        return {
            name: window.location.hostname.split(".")[1] + "." + window.location.hostname.split(".")[2],
            nameSpliced: window.location.hostname.split(".")[1].slice(1) + "." + window.location.hostname.split(".")[2],
        };
    },
    methods: {
        handleLogout() {
            this.$store.dispatch("discard").then( (response) => {
                if(this.$store.state.auth.loggedIn) console.error(response);
            },
            (error) => {
                console.error("error");
            });
        },
    },
};
</script>