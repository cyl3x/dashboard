<script setup>
import MsgBox from '../../components/MsgBox.vue';
</script>

<template>
<div class="flex flex-col items-center flex-auto mx-4 text-neutral-200 max-w-item-4" @drop.prevent @drag.prevent>


    <div class="flex flex-col w-full gap-2 mb-5">
        <transition-group name="fade" :duration="150" mode="out-in">
            <MsgBox v-for="error in errors" :key="error" class="w-full" :msg="error" :isError="true"></MsgBox>
        </transition-group>
        <div class="w-full text-center text-neutral-500">
            Ratio 11/6 | Best: 1650px / 900px
        </div>
    </div>

    <div class="inline-flex flex-wrap justify-center w-full h-auto gap-5">

        <transition-group name="fade" :duration="150" mode="out-in">
            <div :ref="'image-' + idx" v-for="(image, idx) in currentImages" :key="image" class='relative flex-shrink h-48 overflow-hidden rounded-lg shadow-md w-88'> <!-- ratio: 22/12 -> 1650px : 990px -->

                <img :src="'/api/img/' + image" alt='here should be an image' class='object-cover'>

                <!-- unteres element -->
                <div class="absolute bottom-0 left-0 flex items-center justify-between w-full gap-2 px-2 shadow-lg h-7 text-primary-50 bg-neutral-600 bg-opacity-90">
                    <p class="text-lg">{{ image }}</p>
                    <unicon @mouseup="removeImage(image)" name="trash" class="w-4 h-4 transition duration-150 cursor-pointer hover:text-neutral-300" />
                </div>
            </div>
        </transition-group>
    </div>

    <div class='flex flex-wrap h-auto gap-2 p-2 mt-10 border-4 border-dashed rounded-md shadow-lg min-h-36' :class="isDragged ? 'border-primary-300 bg-neutral-900 text-neutral-100' : 'border-neutral-400 bg-neutral-700 text-neutral-400'" @drag.prevent @dragstart.prevent @dragend.prevent @dragover.prevent="setDrag(true)" @dragenter.prevent @dragleave.prevent="setDrag(false)" @drop.prevent="handleImages($event)">
        <img  v-for="(file, idx) in files" :key="file" :src="previewImages[idx]" class="justify-start flex-none overflow-hidden rounded-md h-36"/>

        <label :class="files.length == 0 ? '' : 'border-neutral-400 border-2 '" class="grid items-center gap-3 p-3 text-center rounded-md cursor-pointer">
            <input type="file" accepts="image/*" @change="handleImages($event)" class="hidden" multiple/>

            <p>Click here or<br>Drag and Drop Image to upload</p>

            <button v-if="files.length > 0" @click="uploadImages()" class="px-5 py-1 text-white transition duration-150 rounded-md bg-primary-400 hover:bg-primary-500">Upload</button>
        </label>
    </div>
</div>

</template>

<script>
import path from 'path';
export default {
    name: "UserConfig",
    data(){
        return {
            isDragged: false,
            dragAndDropCapable: false,
            errors: [],
            files: [],
            previewImages: [],
        }
    },
    computed: {
        currentImages(){
            return this.$store.state.user.imageList;
        },
        imageOptions(){
            var images = [];
            this.currentImages.forEach(image => {
                images.push({name: image, value: image})
            });
            return images;
        }
    },
    methods: {
        handleImages(e){
            this.isDragged = false;
            var images = e.target.files || e.dataTransfer.files;
            for( let i = 0; i < images.length; i++ ){
                var image = images[i];

                let reader = new FileReader
                reader.onload = e => this.previewImages.push(e.target.result);
                reader.readAsDataURL(image);

                this.files.push( image );
            }
            if(e.target) e.target.value = "";
        },
        uploadImages(){
            this.isDragged = false;

            for (let file of this.files) {
                if(file.size <= 2048 * 1024){
                    if(this.currentImages.indexOf(file.name) > -1){
                        const newName = this.renameFileAsLongAsItExists(file.name);
                        file = new File([file], newName, {type: file.type});
                    }
                    this.$store.dispatch("user/addImage", file).then(response => {
                        if(response !== true){
                            this.errors.push(file.name + ": " + response);
                            scrollTo({top: 0, left: 0, behavior: 'smooth'});
                        }
                        else {
                            this.files = [];
                            this.previewImages = [];
                        }
                    });
                } else this.errors.push(file.name + " is too big (> 2MB)");
            }
        },
        removeImage(image){
            this.$store.dispatch("user/removeImage", image);
        },
        setDrag(drag){
            this.isDragged = drag;
        },
        renameFileAsLongAsItExists(filename){
            const dotIdx = filename.lastIndexOf('.');
            let name = filename.substring(0, dotIdx);
            let ext = filename.substring(dotIdx, filename.length);
            for(let i = 2; this.currentImages.indexOf(filename) != -1; i++){
                filename = name + '-' + i + ext;
            }
            return filename;
        }
    },
};
</script>