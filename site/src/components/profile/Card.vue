<script setup>
import { ref } from 'vue'
import TextInput from '../TextInput.vue';
</script>

<template>
<VForm ref="form" class="flex flex-col flex-1 w-auto h-auto" v-slot="{ errors }">
    <h2 class="h-auto p-3 text-2xl text-neutral-200">
        {{ title }}
    </h2>
    <div class="flex flex-col w-full h-auto gap-2 px-4 py-3 overflow-hidden rounded-md shadow-lg bg-neutral-700">
        <div class="flex flex-col w-full h-full gap-1 text-neutral-200">

            <MsgBox :msg="msg" :isError="isError"></MsgBox>

            <template v-for="(item) in config" :key="item">
                <label :for="item.refName">{{ item.label }}</label>
                <TextInput :validateOnInput="true" :rules="item.rule" :name="item.refName" :type="item.type ? item.type : 'text'" :pre="item.preValue" :isError="errors[item.refName] ? true : false"></TextInput>
                <VErrorMsg class="z-10 pl-1 -mb-3 text-xs text-red-200" :ref="item.refName" :name="item.refName"/>

                <div class="h-2"></div>
            </template>

        </div>
        <div class="flex flex-row h-auto bg-neutral-700">
            <button class="px-3 py-1 text-white transition duration-150 ease-in-out rounded-md cursor-pointer bg-primary-500 hover:bg-primary-600">
                {{ buttonText }}
            </button>
        </div>
    </div>
</VForm>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            default: null,
        },
        buttonText: {
            type: String,
            default: null,
        },
        config: {
            type: Array,
            default: [],
        },
        msg: {
            type: String,
            default: null,
        },
        isError: {
            type: Boolean,
            default: false,
        }
    },
    computed: {
        user(){
            return this.$store.state.user.user;
        }
    },
    beforeMount(){
        if(!this.$store.state.user.user) this.$store.dispatch("user/getUser");
    }
};
</script>