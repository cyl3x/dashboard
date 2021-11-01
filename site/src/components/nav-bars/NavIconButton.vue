<template>
    <router-link :to="{ name: routeName }" v-slot="{ isActive }">
        <div :class="[ isActive && routeName ? focusedClass : notFocusedClass ]" class="w-auto h-full px-4 py-3 transition duration-150 ease-in-out border-b-2 hover:bg-neutral-1000 focus:outline-none hover:border-primary-100">
            <unicon :name="icon" class="h-full text-neutral-50" />
        </div>
    </router-link>
</template>

<script>
export default {
    props: {
        icon: {
            type: String,
            default: "0",
        },
        routeName: {
            type: String,
            default: null,
        }
    },
    data(){
        return {
            focusedClass: 'border-primary-100',
            notFocusedClass: 'border-neutral-900',
        }
    },
}
</script>