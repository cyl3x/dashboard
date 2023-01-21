<template>
<router-view name="navbar">
</router-view>

<router-view class="font-sans" v-slot="{ Component }">
    <transition name="fade" mode="out-in" :duration="150">
        <component :is="Component" />
    </transition>
</router-view>
</template>

<script>
export default {
    data() {
        return {
            visibility: -1,
            lastVisible: -1,
        }
    },
    computed: {
        loggedIn(){
            return this.$store.state.auth.loggedIn;
        },
        exp(){
            return this.$store.state.auth.exp;
        },
    },
    methods:{
        checkRefresh(){
            if(this.loggedIn){
                const exp_left = this.exp - Math.round(Date.now() / 1000);

                if(!this.visibility){
                    this.lastVisible = Date.now();

                    var id = setTimeout(function() {}, 0);
                    while (id--) window.clearTimeout(id);
                } else if (this.exp_left <= 0) this.$store.dispatch('auth/refresh');
                else setTimeout(() => this.$store.dispatch('auth/refresh'), exp_left * 1000);
            }
        }
    },
    beforeMount() {
        const onVisibilityChange = () => {
            if (document.visibilityState === 'hidden') this.visibility = false;
            else this.visibility = true;
        };
        document.addEventListener('visibilitychange', onVisibilityChange, false);
        this.checkRefresh();
    },
    watch: {
        visibility() {
            this.checkRefresh();
        },
        loggedIn(){
            this.checkRefresh();
        }
    }
}
</script>

<style>
@import './assets/css/main.css';
#app {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
</style>
