<template>
    <svg v-if="loading" :class="colorClass ? colorClass + ' stroke-current' : ''" class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
        <circle cx="50" cy="50" fill="none" :stroke="!colorClass && color ? color : ''" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138"></circle>
    </svg>
</template>

<script>
export default {
    props: {
        loading: {
            type: Boolean,
            default: false,
        },
        colorClass: {
            type: String,
            default: null,
        },
        color: {
            type: String,
            default: null,
        }
    },
    data(){
        return {
        }
    },
    methods: {
    }
}
</script>
