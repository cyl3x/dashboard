<script setup>
import NavTextButton from './NavTextButton.vue';
</script>

<template>
<div class="w-full h-10 px-4 bg-neutral-700">
    <div class="w-auto h-full m-auto max-w-400">
        <div class="flex items-center w-full h-full gap-2 text-lg text-neutral-400">
            <NavTextButton text="Profile" routeName="Profile" class="w-auto h-full px-2" />
            <NavTextButton text="Config" routeName="Config" class="w-auto h-full px-2" />
            <NavTextButton text="Images" routeName="Images" class="w-auto h-full px-2" />
        </div>
    </div>
    <div class="w-1/4"></div>
</div>
</template>

<script>
export default {
    name: 'UserNavBar',
};
</script>