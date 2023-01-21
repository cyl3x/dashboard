<template>
    <router-link :to="{ name: routeName }" v-slot="{ isActive }">
        <div :class="[ isActive ? focusedClass : notFocusedClass ]" class="flex items-center w-auto h-full px-3 transition duration-150 ease-in-out border-b-2 hover:text-primary-50 focus:outline-none ">
            {{ text }}
        </div>
    </router-link>
</template>

<script>
export default {
    props: {
        text: {
            type: String,
            default: "",
        },
        routeName: {
            type: String,
            default: null
        }
    },
    data(){
        return {
            focusedClass: 'border-primary-100 text-neutral-100',
            notFocusedClass: 'border-neutral-700 text-neutral-300',
        }
    },
}
</script>