import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import exampleMainPage from './exampleMainPage';
import index from './pages/index';
import profile from './pages/profile';
// import dashboard from './pages/dashboard';
import ExampleRoutes from '../../app/Modules/Example/Vue/js/router'
import UserRoutes from '../../app/Modules/UserModule/Vue/js/router'
import franchisesRoute from '../../app/Modules/FranchiseModule/Vue/js/router'
import productRoute from '../../app/Modules/ProductModule/Vue/js/router'
import orderModule from '../../app/Modules/OrderModule/Vue/js/router'

let onlyAdmin = ['Admin']
let adminAndfranchiseOwner = ['Admin','FranchiseOwner']

const initialRoutes = [ 
    {  
        path: '/example-main-page',
        name: 'test',
        component: exampleMainPage,
    },
    { 
        path: '/',  
        name: 'index',
        component: index,
        meta: {  allowed: adminAndfranchiseOwner,title: 'Admin Site' }
    },
    {
        path: '/profile',
        name: 'profile',
        component: profile,
        meta: {
            allowed: adminAndfranchiseOwner,
            title: 'Admin Site - Dashboard'
        }
          
    }  
]
// bring in all the modules routes
// console.log(AccountsRoutes)
var routes = [
]

routes = routes.concat(
    initialRoutes,
    ExampleRoutes,
    franchisesRoute,
    productRoute,
    orderModule,
    UserRoutes
)

export default new Router({
    mode: 'history',
    routes
})
