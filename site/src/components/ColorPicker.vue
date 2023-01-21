<template>
    <div @click="show = !show" class="cursor-pointer" :style="'background-color: ' + value + ';'">
        <transition name="fade" mode="in-out" :duration="100">
            <div v-if="show" class="absolute top-0 left-0 z-20 flex items-center w-full h-full bg-neutral-600">
                <div class="inline-grid w-full h-auto grid-cols-8 gap-1 px-2 py-2">
                    <div @click="setColor(dColor)" v-for="dColor in defaultColors" :key="dColor" :style="'background-color: ' + dColor + ';'" class="h-0 border-white rounded-md shadow-md pb-full"></div>
                </div>
            </div>
        </transition>
        <VField ref="colorSelect" :value="value" :name="name" class="hidden">
        </VField>
    </div>
</template>

<script>
export default {
    name: "ColorPicker",
    props: {
        value: {
            type: String,
            default: '#ffffff',
        },
        name: {
            type: String,
            default: 'ColorPicker',
        },
        defaultColors: {
            type: Array,
            default: [
                '#1a1c2c',
                '#f4f4f4',
                '#94b0c2',
                '#566c86',
                '#333c57',
                '#5d275d',
                '#b13e53',
                '#ef7d57',
                '#ffcd75',
                '#a7f070',
                '#38b764',
                '#257179',
                '#29366f',
                '#3b5dc9',
                '#41a6f6',
                '#73eff7',
            ]
        },
    },
    data(){
        return {
            show: false,
        }
    },
    methods: {
        setColor(color){
            this.$emit("changeColor", color);
        }
    }
};
</script>