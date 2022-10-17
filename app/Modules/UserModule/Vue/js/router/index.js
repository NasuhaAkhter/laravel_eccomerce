
import usermoduleModulePage from '../pages/usermodule.vue'
import usermoduleModuleuserList from '../pages/user-list.vue'
import shopOwnerList from '../pages/shop-owner-list.vue'
import usermoduleModule_user_balance from '../pages/user_balance.vue'
import transaction from '../pages/transaction.vue'
import delivery_address from '../pages/delivery_address.vue'
import driverList from '../pages/driverList.vue'
import newMemberRequest from '../pages/newMemberRequest.vue'
import onBoardScreen from '../pages/onBoardScreen.vue'
import promotionScreen from '../pages/promotionScreen.vue'
import privacyPolicy from '../pages/privacyPolicy.vue'
import termsAndCondition from '../pages/termsAndCondition.vue'
import returnRefundPolicy from '../pages/returnRefundPolicy.vue'
import franchiseOwner from '../pages/franchiseOwner.vue'
import editAddress from '../pages/editAddress.vue'
import addAddress from '../pages/addAddress.vue'
import usermoduleModulelogin from '../pages/login.vue'
import banner from '../pages/banner.vue'
import expense_category from '../pages/expense_category.vue'
import expense_sheets from '../pages/expense_sheets.vue'
import statistics from '../pages/statistics.vue'
import packages from '../pages/package.vue'
   
let onlyAdmin = ['Admin']
let adminAndfranchiseOwner = ['Admin','FranchiseOwner']
const routes = [ 
    // {path : '/usermodule/usermoduleModulePage', component: usermoduleModulePage},
    {path : '/login', component: usermoduleModulelogin,  meta: {  title: 'Admin Login' }  },
    {path: '/', name: 'index', component: banner, meta: {  allowed: adminAndfranchiseOwner,title: 'Dashboard - Duare' } },
    {path : '/usermodule/shop-owner-list', component: shopOwnerList, meta: {  allowed: adminAndfranchiseOwner,title: 'Shop Owner - Duare' } },
    {path : '/usermodule/expense_category', component: expense_category, meta: {  allowed: adminAndfranchiseOwner,title: 'Expense Category - Duare' } },
    {path : '/usermodule/expense_sheets', component: expense_sheets, meta: {  allowed: adminAndfranchiseOwner,title: 'Expense Sheet - Duare' } },
    {path : '/usermodule/statistics', component: statistics, meta: {  allowed: adminAndfranchiseOwner,title: 'Statistics - Duare' } },
    {path : '/usermodule/packages', component: packages, meta: {  allowed: adminAndfranchiseOwner,title: 'Packages - Duare' } },
    {path : '/usermodule/user-list', component: usermoduleModuleuserList, meta: {  allowed: adminAndfranchiseOwner,title: 'User List - Duare' } },
    {path : '/usermodule/user_balance', component: usermoduleModule_user_balance, meta: {  allowed: adminAndfranchiseOwner,title: 'User Balance - Duare' } },
    {path : '/usermodule/addAddress', component: addAddress, meta: {  allowed: adminAndfranchiseOwner,title: 'Add Address - Duare' } },
    {path : '/usermodule/editAddress', component: editAddress, meta: {  allowed: adminAndfranchiseOwner,title: 'Edit Address - Duare' } },
    {path : '/usermodule/franchiseOwner', component: franchiseOwner, meta: {  allowed: onlyAdmin,title: 'Franchise Owner - Duare' } },
    {path : '/usermodule/returnRefundPolicy', component: returnRefundPolicy, meta: {  allowed: adminAndfranchiseOwner,title: 'Privacy Policy - Duare' } },
    {path : '/usermodule/termsAndCondition', component: termsAndCondition, meta: {  allowed: adminAndfranchiseOwner,title: 'Privacy Policy - Duare' } },
    {path : '/usermodule/privacyPolicy', component: privacyPolicy, meta: {  allowed: adminAndfranchiseOwner,title: 'Privacy Policy - Duare' } },
    {path : '/usermodule/promotionScreen', component: promotionScreen, meta: {  allowed: adminAndfranchiseOwner,title: 'Promotional Screen - Duare' } },
    {path : '/usermodule/onBoardScreen', component: onBoardScreen, meta: {  allowed: adminAndfranchiseOwner,title: 'On Board Screen - Duare' } },
    {path : '/usermodule/newMemberRequest', component: newMemberRequest, meta: {  allowed: adminAndfranchiseOwner,title: 'New Member Request - Duare' } },
    {path : '/usermodule/driverList', component: driverList, meta: {  allowed: adminAndfranchiseOwner,title: 'Driver List - Duare' } },
    {path : '/usermodule/delivery_address/:id', component: delivery_address, meta: {  allowed: adminAndfranchiseOwner,title: 'Delivery Address - Duare' } },
    {path : '/usermodule/transaction/:id', component: transaction, meta: {  allowed: adminAndfranchiseOwner,title: 'User Transaction- Duare' } },
    {path : '/usermodule/banner', component: banner, meta: {  allowed: adminAndfranchiseOwner,title: 'Banners - Duare' }},
]
  
export default routes 
