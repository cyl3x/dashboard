<script setup>
import Card from '../../components/profile/Card.vue';
</script>

<template>
<div class="w-full h-auto px-4 ml-auto mr-auto max-w-300">
    <div class="flex flex-row flex-wrap w-full h-auto gap-7">
        <Card @submit="updateProfile" class="flex-none flex-grow w-20" title="Update Profile" ref="updateProfile" buttonText="Update Profile" :config="profileConf" :msg="msgProfile" :isError="errorProfile">
        </Card>

        <Card @submit="updatePassword" class="flex-none flex-grow w-20" title="Update Password" ref="updatePassword" buttonText="Update Password" :config="passConf" :msg="msgPassword" :isError="errorPassword">
        </Card>

        <Card title="Two Factor Auth" class="flex-none flex-grow w-20" buttonText="Enable 2FA" :config="twoFAConf"></Card>
    </div>
</div>
</template>

<script>
import * as valRules from '../../plugins/ValidationRules.js';
export default {
    name: "UserProfile",
    data(){
        return {
            loading: false,
            msgProfile: null,
            msgPassword: null,
            errorProfile: false,
            errorPassword: false,
            password: '',
            passConf: [
                {
                    refName: "current_password",
                    label: "Current Password",
                    type: "password",
                    rule: (value) => {
                        if(!value) return 'You must provide your current account password';
                        else return true;
                    },
                },
                {
                    refName: "new_password",
                    label: "New Password",
                    type: "password",
                    rule: (value) => { this.password = value; return valRules.validatePassword(value)},
                },
                {
                    refName: "confirm_password",
                    label: "Confirm New Password",
                    type: "password",
                    rule: (value) => { return valRules.confirmPassword(value, this.password)},
                }
            ],
            twoFAConf: [
                {
                    refName: "2fa",
                    label: 'Cooming Soon',
                    type: 'hidden',
                    rule: (value) => { return true; },
                }
            ]
        }
    },
    computed: {
        user(){
            return this.$store.state.user.user;
        },
        profileConf() {
            return [
                {
                    refName: "username",
                    label: "Username",
                    preValue: this.user?.username,
                    rule: valRules.validateUsername,
                },
                {
                    refName: "email",
                    label: "Email",
                    preValue: this.user?.email,
                    rule: valRules.validateEmail,
                },
                {
                    refName: "password",
                    label: "Password",
                    type: "password",
                    rule: (value) => {
                        if(!value) return 'You must provide your current account password';
                        else return true;
                    },
                }
            ];
        }
    },
    methods: {
        updateProfile(profile){
            this.$store.dispatch("auth/updateProfile", profile).then((response) => {
                if(response === true){
                    this.errorProfile = false;
                    this.msgProfile = "Success";
                } else {
                    this.errorProfile = true;
                    this.msgProfile = response.error;
                }
            });
        },
        updatePassword(passwords){
            this.$store.dispatch("auth/updatePassword", passwords).then((response) => {
                if(response === true){
                    this.errorPassword = false;
                    this.msgPassword = "Success";
                    this.$router.push('/login');
                } else {
                    this.errorPassword = true;
                    this.msgPassword = response.error;
                }
            });
        }
    },
};
</script>