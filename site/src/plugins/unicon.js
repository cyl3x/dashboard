import Unicon from 'vue-unicons'
import {
    uniSignOutAlt,
    uniUser,
    uniLayerGroup,
    uniMinus,
    uniPlus,
    uniArrowDown,
    uniArrowUp,
    uniTrash,
} from "vue-unicons/dist/icons";

const minusCirleFilled = {
    name: 'minus-circle-filled',
    style: 'line',
    path: '<path d="M12,2A10,10,0,1,0,22,12,10.01146,10.01146,0,0,0,12,2Zm4,11H8a1,1,0,0,1,0-2h8a1,1,0,0,1,0,2Z"></path>'
}


Unicon.add([
    uniSignOutAlt,
    uniUser,
    uniLayerGroup,
    uniMinus,
    uniPlus,
    uniArrowDown,
    uniArrowUp,
    uniTrash,
    minusCirleFilled,
]);

export { Unicon };