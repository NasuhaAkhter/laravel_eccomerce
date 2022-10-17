import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
import exampleStore from '../../app/Modules/Example/Vue/js/store'
export default new Vuex.Store({
    modules : {
        exampleStore
    },
    state : {
        name: 'My name is sadek ',
        authUser:false,
        isAdmin:false,
        franchise_id:'',
        franchiseList:[],
        franchiseDetails:null,
        galaryModal:false,
        images:[],
        allImages:[],
        newOrder:0
        
    },
    getters: {

        isLoggedIn(state) {
            return !!state.authUser
        },
        getAuthUser(state) {
            return state.authUser
        },
        getNewOrder(state) {
            return state.newOrder
        },
        getGalaryModal(state, data) {
            return state.galaryModal
        },
        getGalaryImages(state, data) {
            return state.images  
        },
        getAllGalaryImages(state, data) {
            return state.allImages  
        },
        getFranchise_id(state, data) {
            return state.franchise_id  
        },
        getFranchiseList(state, data) {
            return state.franchiseList  
        },
        getFranchiseDetails(state, data) {
            return state.franchiseDetails  
        },
        getIsAdmin(state, data) {
            return state.isAdmin  
        },
    },
    mutations: {
        setAuthuser(state, data) {
            state.authUser = data
        },
        setNewOrder(state, data) {
            state.newOrder = data
        },
        setFranchise_id(state, data) {
            state.franchise_id = data
        },
        setFranchiseList(state, data) {
            state.franchiseList = data
        },
        setFranchiseDetails(state, data) {
            state.franchiseDetails = data
        },
        setIsAdmin(state, data) {
            state.isAdmin = data
        }, 
        setGalaryModal(state, data) {
            state.galaryModal = data
        },
        setGalaryImages(state, data) {
            state.images = data
        },
        setAllGalaryImages(state, data) {
            state.allImages = data
        },
    },
    actions: {
        setAuthuser(state, data) {
            state.commit('setAuthuser', data);
         },
        setNewOrder(state, data) {
            state.commit('setNewOrder', data);
         },
          
        setGalaryModal(state, data) {
            state.commit('setGalaryModal', data);
         },
    }
})
