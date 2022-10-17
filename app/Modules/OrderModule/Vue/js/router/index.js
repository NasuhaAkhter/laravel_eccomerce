
import orders from '../pages/orders.vue'
import orderDetails from '../pages/orderDetails.vue'
import orderImages from '../pages/orderImages.vue' 
import limit from '../pages/limit.vue'

let onlyAdmin = ['Admin']
let adminAndfranchiseOwner = ['Admin','FranchiseOwner']
let All = ['Admin','FranchiseOwner','ShopOwner']

const routes = [
    {path : '/orderImages', component: orderImages, meta: {  allowed: All, title: 'Order Images - Duare Admin' } },
    {path : '/ordermodule/limit', component: limit, meta: {  allowed: All, title: 'Orders - Duare Admin' } },
    {path : '/ordermodule/orders', component: orders, meta: {  allowed: All, title: 'Orders - Duare Admin' } },
    {path : '/ordermodule/orderDetails/:id', component: orderDetails, meta: {  allowed: All, title: 'Order Details - Duare Admin' } },
]
export default routes
