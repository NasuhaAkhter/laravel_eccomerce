require('./bootstrap');
window.Vue = require('vue');
Vue.component('mainapp', require('./mainapp.vue').default);
import router from './router'
import store from './store'
import ViewUI from 'view-design';
import VueQuillEditor from 'vue-quill-editor'
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css' 
 
Vue.use(VueQuillEditor, /* { default global options } */)
// import 'view-design/dist/styles/iview.css';
import locale from 'view-design/dist/locale/en-US'
import commons from './commons'
// <<<<<<< HEAD 
if(window.authUser != false){
    let userType = window.authUser.userType
    router.beforeEach((to, from, next) => {
        document.title = to.meta.title
        if(to.meta){
            let allowed = to.meta.allowed
            for(let a of allowed){
                if(a==userType){
                    next();
                }
            }
        }
        return  
    });
}
// =======

let userType = window.authUser.userType
router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if(to.meta){
        // let allowed = to.meta.allowed
        // for(let a of allowed){
        //     if(a==userType){
        //        
        //     }
        // }
    }
    next();
    return  
  });
// >>>>>>> b90509c32a327668bfda95950d827e83ce3c4505
  
import VueGeolocation from 'vue-browser-geolocation'
// 
Vue.use(VueGeolocation)
import * as VueGoogleMaps from 'vue2-google-maps'
Vue.use(VueGoogleMaps, {
    load: { 
         key: 'AIzaSyBU7GqUxT98kSlVbD0iFMijQOQFZUbgA7Q',
         libraries: 'places',
    },
  //// then disable the     following:
   installComponents: true,
});
Vue.use(ViewUI, { locale });
Vue.mixin(commons);
const app = new Vue({
    el: '#app',
    router,
    store
});


