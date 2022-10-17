
import shopList from '../pages/shopList.vue'
import franchise from '../pages/franchise.vue'
import gallery from '../pages/gallery.vue'
import adminFranchise from '../pages/adminFranchise.vue'
import addFranchise from '../pages/addFranchise.vue'
import addFranchisePolygon from '../pages/addFranchisePolygon.vue'
import addshippingPrice from '../pages/addshippingPrice.vue'
import editFranchise from '../pages/editFranchise.vue'
import editShop from '../pages/editShop.vue'
import franchise_daily_schedule from '../pages/franchise_daily_schedule.vue'
import shipping_price from '../pages/shipping_price.vue'  
import editShippingPrice from '../pages/editShippingPrice.vue'  
import addShop from '../pages/addShop.vue'  
import myFranchise from '../pages/myFranchise.vue'  
import test from '../pages/test.vue'  
let onlyAdmin = ['Admin']  
let adminAndfranchiseOwner = ['Admin','FranchiseOwner']
let All = ['Admin','FranchiseOwner','ShopOwner']
const routes = [
    {path : '/franchisemodule/franchise-list', component: franchise, meta: {  allowed: onlyAdmin, title: 'Franchise List' } },
    {path : '/franchisemodule/admin-franchise-list', component: adminFranchise, meta: {  allowed: onlyAdmin, title: 'Admin Franchise List' } },
    {path : '/franchisemodule/addFranchisePolygon', component: addFranchisePolygon, meta: {  allowed: onlyAdmin, title: 'Add New Franchise' }},
    {path : '/franchisemodule/addFranchise', component: addFranchise, meta: {  allowed: onlyAdmin, title: 'Add New Franchise' }},
    {path : '/franchisemodule/editFranchise', component: editFranchise, meta: {  allowed: onlyAdmin, title: 'Edit Franchise' }},
    {path : '/franchisemodule/myFranchise', component: myFranchise, meta: {  allowed: adminAndfranchiseOwner, title: ' My Franchise' } },
    {path : '/franchisemodule/addshippingPrice', component: addshippingPrice, meta: {  allowed: adminAndfranchiseOwner, title: 'Add Shipping  Price' }},
    {path : '/franchisemodule/editShippingPrice/:id', component: editShippingPrice, meta: {  allowed: adminAndfranchiseOwner, title: 'Edit Shipping Price' } },
    {path : '/franchisemodule/shipping_price', component: shipping_price, meta: {  allowed: adminAndfranchiseOwner, title: 'Shipping Price' } },
    {path : '/franchisemodule/gallery', component: gallery, meta: {  allowed: All, title: 'Gallery' } },
    {path : '/franchisemodule/shop-list', component: shopList, meta: {  allowed: adminAndfranchiseOwner, title: 'Shop List' } },
    {path : '/franchisemodule/addShop', component: addShop, meta: {  allowed: adminAndfranchiseOwner, title: 'Add New Shop' } },
    {path : '/franchisemodule/editShop', component: editShop, meta: {  allowed: adminAndfranchiseOwner, title: 'Edit Shop' } },
    {path : '/franchisemodule/franchise_daily_schedule', component: franchise_daily_schedule, meta: {  allowed: adminAndfranchiseOwner, title: '' } },
    {path : '/franchisemodule/test', component: test}
]
export default routes 
  