<template>
<div>
    <VField as="select" v-model="selected" :name="name" :class="[isError == true ? 'focus:ring-red-200 focus:border-red-300' : 'focus:ring-primary-200 focus:border-primary-300']" class="w-full p-2 transition duration-100 ease-in-out border-2 rounded-md focus:ring-opacity-50 text-neutral-200 border-neutral-500 bg-neutral-600 focus:outline-none hover:border-neutral-400 focus:ring ">
        <slot></slot>
        <option v-for="option in options" :key="option" :value="option.value ? option.value : option.name">{{ option.name }}</option>
    </VField>
</div>
</template>

<script>
export default {
    props: {
        name: {
            type: String,
            default: "",
        },
        options: {
            type: Array,
            default: null,
        },
        selected: {
            type: String,
            default: null,
        },
        compareNames: {
            type: Boolean,
            default: false,
        }
    },
    data(){
        return{
            isError: false,
        }
    },
    methods: {
        error(error){
            this.isError = error;
        }
    }
}
</script>