<template>
    <VField :value="pre ? pre : ''" type="text" :class="[isError == true ? 'focus:ring-red-200 focus:border-red-300' : 'focus:ring-primary-200 focus:border-primary-300']" class="w-full p-2 transition duration-100 ease-in-out border-2 rounded-md focus:ring-opacity-50 text-neutral-200 border-neutral-500 bg-neutral-600 focus:outline-none hover:border-neutral-400 focus:ring " />
</template>

<script>
export default {
    props: {
        isError: {
            type: Boolean,
            default: false,
        },
        pre: {
            type: String,
            default: "",
        }
    },
}
</script>