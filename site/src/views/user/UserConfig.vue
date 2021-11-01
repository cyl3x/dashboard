<script setup>
import { ref } from 'vue'
import TextInput from '../../components/TextInput.vue';
import TextSelect from '../../components/TextSelect.vue';
import isURL from '../../plugins/URLValidator';
import MsgBox from '../../components/MsgBox.vue';
import Draggable from 'vuedraggable';
import ColorPicker from '../../components/ColorPicker.vue';
</script>

<template>
<VForm ref="updateConfig" :key="config" :initial-values="config"  @submit="updateConfig" class="w-full px-4 m-auto text-neutral-200 max-w-item-4" v-slot="{ values }">

    <MsgBox class="px-2 mb-2" :msg="msg" :isError="isError"></MsgBox>

    <div v-if="!isError || !msg" class="w-full text-center text-neutral-500">Hold and Drag & Drop Elements</div>

    <VFieldArray name="sections" v-slot="{ fields: sections, push: pushSection, swap: swapSection, remove: removeSection }">
        <div v-for="(section, sectionIdx) in sections" :key="section.key" class="flex flex-row flex-wrap w-full gap-2 py-2"> <!--@change="handleInput(values)"-->

            <h2 :class="sectionIdx > 0 ? 'pt-3' : ''" class="flex justify-between w-full pb-1 text-2xl h-15">
                Section {{ sectionIdx + 1 }}
                <div class="flex flex-row-reverse gap-4 text-neutral-500">
                    <unicon class="w-6 h-6 transition duration-100 cursor-pointer hover:text-neutral-300" name="plus" @mouseup="values.sections[sectionIdx].push(newItem)"/>
                    <div class="flex flex-row-reverse justify-center gap-2 align-middle">
                        <unicon v-if="sectionIdx != sections.length - 1" class="w-6 h-6 duration-100 cursor-pointer hover:transition hover:text-neutral-300" @mouseup="swapSection(sectionIdx, sectionIdx+1)" name="arrow-down" />
                        <unicon v-if="sectionIdx != 0" class="flex w-6 h-6 transition duration-100 cursor-pointer hover:text-neutral-300" @mouseup="swapSection(sectionIdx, sectionIdx-1)" name="arrow-up" />
                    </div>
                </div>
            </h2>

            <VFieldArray :name="`sections[${sectionIdx}]`" v-slot="{ remove: removeItem }">
                <Draggable :key="section.key" :list="values.sections[sectionIdx]" item-key="key" :delay="300" @choose="drag = true" @unchoose="drag = false" filter=".undraggable" ghost-class="opacity-50" chosen-class="border" :animation="200" :group="{ name: 'items' }" class="flex flex-row flex-wrap items-center justify-center w-full gap-2 text-sm sm:text-base">
                    <template #item="{ element, index: itemIdx }">
                        <div :set="item = element" class="relative flex flex-col items-center h-48 overflow-hidden rounded-md shadow-lg cursor-move w-88 border-primary-200">
                            <div class='absolute top-0 left-0 w-full h-full overflow-hidden bg-center bg-cover bg-neutral-700 -z-10 filter blur-xs disable-select' :style="item.image ? {'background-image': 'url(/api/img/' + item.image + ')'} : ''"></div>

                            <div class="flex w-full overflow-hidden shadow-sm text-neutral-50 h-9 bg-neutral-600 bg-opacity-90" >
                                <VField as="select"  :value="item.image" :name="`sections[${sectionIdx}][${itemIdx}].image`" class="w-full h-full px-1 bg-white bg-opacity-0 focus:ring-primary-200 focus:ring">
                                    <option v-for="option in imageOptions" :key="option" :value="option.value ? option.value : option.name">{{ option.name }}</option>
                                </VField>
                                <div style="width: 2px;" class="my-2 ml-1 bg-neutral-50"></div>
                                <unicon class="self-center w-4 h-full mx-2 my-auto transition duration-100 cursor-pointer text-neutral-50 hover:text-red-200" @mouseup="() => { removeItem(itemIdx); if(values.sections[sectionIdx]?.length <= 0) removeSection(sectionIdx); }" name="minus-circle-filled" />
                            </div>

                            <div :class="drag ? 'pointer-events-none' : 'pointer-events-auto'" class="flex flex-col items-center justify-center h-full gap-2 mx-2 w-8/10" :style="'color:' +  item.color + ';'">
                                <VField v-on:keydown.enter.prevent="false" @focusin="resizeInput($event)" @change="resizeInput($event)" @input="resizeInput($event)" spellcheck="false" :value="item.name" placeholder="[Name]" :name="`sections[${sectionIdx}][${itemIdx}].name`" autocomplete="off"  class="overflow-hidden text-3xl font-bold text-center bg-white bg-opacity-0 rounded-md max-w-84 text-shadow max-h-10" />
                                <ColorPicker :value="item.color" @changeColor="values.sections[sectionIdx][itemIdx].color = $event" :name="`sections[${sectionIdx}][${itemIdx}].color`" class="w-10 h-5 rounded-lg shadow-md"></ColorPicker>
                                <VField v-on:keydown.enter.prevent="false" @focusin="resizeInput($event)" @change="resizeInput($event)" @input="resizeInput($event)" spellcheck="false" :value="item.url" placeholder="[URL]" :name="`sections[${sectionIdx}][${itemIdx}].url`" autocomplete="off"  class="mt-2 overflow-hidden text-center bg-white bg-opacity-0 rounded-md max-w-84 text-shadow max-h-10" />
                            </div>
                        </div>
                    </template>
                </Draggable>
            </VFieldArray>
        </div>

    <div class="flex flex-row flex-wrap w-full gap-2 px-2 py-2">
        <h2 class="w-full pt-3 pb-1 text-2xl h-15">
            Section {{ sections.length + 1 }}
        </h2>

        <div @click="pushSection([newItem])" class="flex justify-center w-full h-auto py-5 overflow-hidden transition duration-150 border-4 border-dashed rounded-md shadow-lg cursor-pointer text-neutral-500 border-neutral-500 hover:border-neutral-400 bg-neutral-700 hover:text-neutral-400">
            <unicon name="plus" class="m-auto w-7 h-7"/>
        </div>
    </div>
    </VFieldArray>

    <div class="flex flex-row-reverse w-full gap-4 px-2 py-2 mt-4">
        <button class="px-5 py-2 text-white transition duration-150 ease-in-out bg-green-400 rounded-md cursor-pointer hover:bg-green-500">
            Save
        </button>
        <div @click="discardConfig" class="px-5 py-2 text-white transition duration-150 ease-in-out rounded-md cursor-pointer bg-primary-500 hover:bg-primary-600">
            Discard
        </div>
    </div>

    <span ref="textWidth" class="text-opacity-0 opacity-0 text-neutral-800"></span>
</VForm>
</template>

<script>
export default {
    name: "UserConfig",
    data(){
        return {
            drag: false,
            colorOptions: [
                {
                    name: "White",
                    value: "white"
                },
                {
                    name: "Black",
                    value: "black"
                }
            ],
            newItem: { name: 'New Item', image: '', url: '', color: 'white' },
            isError: false,
            msg: null,
        }
    },
    computed: {
        config(){
            return this.$store.state.user.config;
        },
        imageList(){
            return this.$store.state.user.imageList;
        },
        imageOptions(){
            var images = [];
            this.imageList?.forEach(image => {
                images.push({name: image, value: image})
            });
            return images;
        }
    },
    methods: {
        isValidURL(value, e){
            if (value && !isURL(value)) return 'Please enter a valid URL';
            return true;
        },
        updateConfig(config){
            this.$store.dispatch("user/updateConfig", config).then( (response) => {
                if(response === true){
                    this.isError = false;
                    this.msg = "Success";
                } else {
                    this.isError = true;
                    this.msg = response.error;
                }
                scrollTo({top: 0, left: 0, behavior: 'smooth'});
            });
        },
        discardConfig(){
            this.$refs.updateConfig.setValues(this.config);
            scrollTo({top: 0, left: 0, behavior: 'smooth'});
        },
        resizeInput(e){
            const name = e.target.name.split('.')[1];
            const valueLength = e.target.value.length;
            const placeholderLength = e.target.placeholder.length;
            this.$refs.textWidth.textContent = (valueLength > placeholderLength ? e.target.value : e.target.placeholder);
            this.$refs.textWidth.className = (e.target.className.split(' ').filter(name => { return name.indexOf('text') > -1 || name.indexOf('font') > -1 }).join(' ') + ' text-opacity-0 text-neutral-800 opacity-0');

            e.target.style.width = this.$refs.textWidth.offsetWidth + 20 + "px";
        },
    },
};
</script>

<style>
.flip-list-move {
  transition: transform 0.5s;
}
.no-move {
  transition: transform 0s;
}
</style>
