<script setup>
import LoadingCircle from '../components/LoadingCircle.vue';
</script>

<template>
<div class="flex flex-col items-center justify-center h-screen gap-6 px-4 text-lg">
    <Logo class="w-32 h-32"></Logo>

    <VForm @submit="handleRegister" class="flex flex-col w-full gap-4 px-6 py-4 overflow-hidden text-gray-600 bg-white rounded-lg shadow-md sm:max-w-md">
        <MsgBox class="mb-3" :msg="msg" :isError="error"></MsgBox>

        <div>
            <label class="text-sm font-medium" for="username">Username</label>
            <VField :rules="isRequired" type="text" name="username" autocomplete="off" fetched="true" autofocus="autofocus" class="w-full p-1 mt-1 transition duration-100 ease-in-out border-2 border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:outline-none focus:ring focus:ring-primary-200 focus:ring-opacity-50" />
        </div>

        <div>
            <label class="text-sm font-medium" for="email">Email</label>
            <VField :rules="validateEmail" type="text" name="email" autocomplete="off" fetched="true" autofocus="autofocus" class="w-full p-1 mt-1 transition duration-100 ease-in-out border-2 border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:outline-none focus:ring focus:ring-primary-200 focus:ring-opacity-50" />
            <VErrorMsg class="z-10 pl-1 -mb-2 text-xs text-red-300" name="email"/>
        </div>

        <div>
            <label class="text-sm font-medium" for="new_password">Password</label>
            <VField :rules="validatePassword" type="password" name="new_password" autocomplete="off" class="w-full p-1 mt-1 transition duration-100 ease-in-out border-2 border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:outline-none focus:ring focus:ring-primary-200 focus:ring-opacity-50" />
            <VErrorMsg class="z-10 pl-1 -mb-2 text-xs text-red-300" name="new_password"/>
        </div>

        <div>
            <label class="text-sm font-medium" for="confirm_password">Confirm Password</label>
            <VField :rules="confirmPassword" type="password" name="confirm_password" autocomplete="off" class="w-full p-1 mt-1 transition duration-100 ease-in-out border-2 border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:outline-none focus:ring focus:ring-primary-200 focus:ring-opacity-50" />
            <VErrorMsg class="z-10 pl-1 -mb-2 text-xs text-red-300" name="confirm_password"/>
        </div>

        <div class="flex items-center justify-end">
            <LoadingCircle :loading="loading" colorClass="text-gray-800"></LoadingCircle>
            <button class="inline-flex items-center px-4 py-2 ml-4 text-xs font-semibold tracking-widest text-white uppercase transition duration-100 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                Register
            </button>
        </div>
    </VForm>
</div>
</template>

<script>
import * as valRules from '../plugins/ValidationRules.js';
export default {
    data() {
        return {
            loading: false,
            msg: '',
            error: false,
            password: '',
        };
    },
    methods: {
        validateEmail(value){
            return valRules.validateEmail(value);
        },
        confirmPassword(value){
            return valRules.confirmPassword(value, this.password);
        },
        validatePassword(value){
            this.password = value;
            return valRules.validatePassword(value);
        },
        isRequired(value, e){
            if(!value) return e.field.charAt(0).toUpperCase() + e.field.slice(1) + ' is a required field';
            return true;
        },
        handleRegister(user) {
            this.loading = true;
            this.error = false;

            this.$store.dispatch("auth/register", user).then( (response) => {
                if(response === true){
                    this.$router.push("/login");
                } else {
                    this.error = true;
                    this.msg = response.error;
                }
                this.loading = false;
            });
        },
    },
};
</script>
