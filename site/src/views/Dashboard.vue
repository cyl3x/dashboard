<template>
<div>
    <div class="w-full h-auto pt-6 m-auto mt-10 mb-4 max-w-400">
        <div v-for="(section, index) in sections" :key="section">

            <div class="my-8 border-t border-neutral-300" v-if="(sections.length + 1) > index && index != 0"></div>

            <div class="flex flex-wrap justify-center gap-5">
                <div v-for="item in section" :key="item" class='relative h-48 overflow-hidden transition duration-300 ease-in-out transform rounded-lg shadow-lg w-88 bg-neutral-700 hover:scale-105'>
                    <img v-if="item.image" :src="'/api/img/' + item.image" :alt='item.image' class='object-cover object-center w-full h-full text-center filter blur-xs'>

                    <h2 class="absolute top-0 flex items-center justify-center w-full h-full text-3xl cursor-default text-shadow disable-select" :style="'color: ' + item.color">{{ item.name }}</h2>
                    <a v-if="item.url" class="absolute top-0 w-full h-full" :href="item.url"></a>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: 'Dashboard',
    computed: {
        sections(){
            return this.$store.state.user.config?.sections;
        },
    },
    beforeMount() {
        if(!this.$store.state.user.config) this.$store.dispatch("user/getConfig");
    },
};
</script>