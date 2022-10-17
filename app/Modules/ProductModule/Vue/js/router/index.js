
import productCategory from '../pages/productCategory.vue'
import productSubCategory from '../pages/productSubCategory.vue'
import images from '../pages/images.vue' 
import variation from '../pages/variation.vue' 
import band from '../pages/band.vue'
import cupon from '../pages/cupon.vue'
import requestedProduct from '../pages/requestedProduct.vue'
import discount_announcement from '../pages/discount_announcement.vue'
import menu_categories from '../pages/menu_categories.vue'
import category from '../pages/category.vue'
import product from '../pages/product.vue'
import addProduct from '../pages/addProduct.vue'
import editProduct from '../pages/editProduct.vue'

let onlyAdmin = ['Admin']
let adminAndfranchiseOwner = ['Admin','FranchiseOwner']
let All = ['Admin','FranchiseOwner','ShopOwner']  
const routes = [ 
    {path : '/productmodule/requestedProduct', component: requestedProduct, meta: {  allowed: adminAndfranchiseOwner, title: 'Requested Product - Duare' } },
    {path : '/productmodule/cupon', component: cupon, meta: {  allowed: adminAndfranchiseOwner, title: 'Coupon - Duare' } },
    {path : '/productmodule/discount_announcement', component: discount_announcement, meta: {  allowed: adminAndfranchiseOwner, title: 'Product - Duare' } },
    {path : '/productmodule/category', component: category, meta: {  allowed: adminAndfranchiseOwner, title: 'Category - Duare' } },
    {path : '/productmodule/product', component: product, meta: {  allowed: All, title: 'Product - Duare' } },
    {path : '/productmodule/product-category/:id', component: productCategory, meta: {  allowed: All, title: 'Product Category - Duare' } },
    {path : '/productmodule/product-sub-category/:id', component: productSubCategory, meta: {  allowed: All, title: 'Product SubCategroy- Duare' } },
    {path : '/productmodule/product-brands', component: band, meta: {  allowed: All, title: 'Brands - Duare' } },
    {path : '/productmodule/menu_categories', component: menu_categories, meta: {  allowed: All, title: 'Product - Duare' } },
    {path : '/productmodule/addProduct', component: addProduct, meta: {  allowed: All, title: 'Add Product' } },
    {path : '/productmodule/product-images/:id', component: images, meta: {  allowed: All, title: 'Product Images - Duare' } },
    {path : '/productmodule/product-variation/:id', component: variation, meta: {  allowed: All, title: 'Product Variations - Duare' } },
    {path : '/productmodule/editProduct/:id', component: editProduct, meta: {  allowed: All, title: 'Edit Product - Duare' } },
] 

export default routes
