import { createApp } from 'vue'
import App from './App.vue'
import router from './router/index.js'
import store from './store/index.js'
import { Unicon } from './plugins/unicon'
import Logo from './components/Logo.vue'
import MsgBox from './components/MsgBox.vue'
import InlineSvg from 'vue-inline-svg';
import { Form as VForm, Field as VField, ErrorMessage as VErrorMsg, FieldArray as VFieldArray } from "vee-validate";


createApp(App)
    .use(router)
    .use(store)
    .use(Unicon, { fill: 'currentColor' })
    .component('inline-svg', InlineSvg)
    .component('Logo', Logo)
    .component('MsgBox', MsgBox)
    .component('VErrorMsg', VErrorMsg)
    .component('VField', VField)
    .component('VForm', VForm)
    .component('VFieldArray', VFieldArray)
    .mount('#app')