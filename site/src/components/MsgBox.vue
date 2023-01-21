<template>
<div v-if="msg" :class="isError ? bgError : bgSuccess" class="p-1 text-center text-white align-middle duration-150 ease-out rounded-md transition-color">
    {{ msg }}
</div>
</template>

<script>
export default {
    props: {
        msg: {
            type: String,
            default: null,
        },
        isError: {
            type: Boolean,
            default: false,
        }
    },
    data(){
        return {
            bgSuccess: "bg-green-200",
            bgError: "bg-red-200",
            bgNormal: "bg-primary-200",
        }
    },
    methods: {
    }
}
</script>