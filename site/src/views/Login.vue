<script setup>
import LoadingCircle from '../components/LoadingCircle.vue';
</script>

<template>
<div class="flex flex-col items-center justify-center h-screen gap-6 px-4 text-lg">
    <Logo class="w-32 h-32"></Logo>

    <VForm @submit="handleLogin" class="flex flex-col w-full gap-4 px-6 py-4 overflow-hidden text-gray-600 bg-white rounded-lg shadow-md sm:max-w-md">
        <MsgBox class="mb-3" :msg="msg" :isError="error"></MsgBox>

        <div>
            <label class="text-sm font-medium " for="username">Username</label>
            <VField :rules="isRequired" type="text" name="username" autocomplete="off" fetched="true" autofocus="autofocus" class="w-full p-1 mt-1 transition duration-100 ease-in-out border-2 border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:outline-none focus:ring focus:ring-primary-200 focus:ring-opacity-50" />
            <!-- <VErrorMsg class="z-10 pl-1 -mb-10 text-xs text-red-200" name="username"/> -->
        </div>

        <div>
            <label class="text-sm font-medium " for="password">Password</label>
            <VField :rules="isRequired" type="password" name="password" autocomplete="current-password" class="w-full p-1 mt-1 transition duration-100 ease-in-out border-2 border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:outline-none focus:ring focus:ring-primary-200 focus:ring-opacity-50" />
            <!-- <VErrorMsg class="z-10 pl-1 -mb-2 text-xs text-red-200" name="password"/> -->
        </div>

        <label for="remember_me" class="flex items-center checkbox-label path">
            <VField name="remember" type="checkbox" value="true" class="w-4 h-4 text-indigo-600 transition duration-100 ease-in-out border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />

            <label class="ml-2 text-sm " name="remember">Remember me</label>
        </label>

        <div class="flex items-center justify-end">
            <LoadingCircle :loading="loading" colorClass="text-gray-800"></LoadingCircle>
            <router-link v-if="!loading" :to="{ name: 'Register' }" class="text-sm underline">Register</router-link>
            <button class="inline-flex items-center px-4 py-2 ml-4 text-xs font-semibold tracking-widest text-white uppercase transition duration-100 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                Login
            </button>
        </div>
    </VForm>
</div>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            msg: '',
            error: false,
        };
    },
    methods: {
        isRequired(value, e){
            if(!value) return e.field.charAt(0).toUpperCase() + e.field.slice(1) + ' is a required field';
            return true;
        },
        handleLogin(user) {
            this.loading = true;
            this.error = false;

            this.$store.dispatch("auth/login", user).then( (response) => {
                if(this.$store.state.auth.loggedIn){
                    this.$router.push("/");
                } else {
                    this.error = true;
                    this.msg = response.error;
                }
                this.loading = false;
            });
        },
    },
    beforeMount() {
        this.$store.dispatch("user/discardConfig");

        if(localStorage.getItem('vuex.auth.loggedIn') === true){
            this.$store.dispatch("auth/refresh").then( (response) => {
                if(this.$store.state.auth.loggedIn) this.$router.push("/");
                else{
                    this.$store.dispatch("discard").then( (response) => {
                        if(!this.$store.state.auth.loggedIn) this.$router.push("/login");
                        else if(response) console.error(response);
                    },
                    (error) => {
                        console.error(error);
                    });
                }
            }, (error) => { console.log(error)});
        }
    }
};
</script>
