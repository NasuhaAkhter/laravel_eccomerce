<template>
    <div>
    <div :class="darkMode? 'darkMode' : ''" id="main-wrapper">
      <!-- Menu --> 
      <nav v-if="$route.path != '/login' && $route.path != '/register' && $route.path != '/resetPassword' && $route.path != '/forgetPassword'" :class="sidebar? '_navbar _fixed_top _navbar_light _navbar_wap _mini_navbar' : '_navbar _fixed_top _navbar_light _navbar_wap'">
        <div class="_navbar_left">
          <div class="_navbar_logo">
            <a href="" class="_navbar_logo_link">
              DUARE ADMIN PANEL
              <!-- <img class="_navbar_logo_img" src="/img/logo-icon.png" alt="" title="">
              <img class="_navbar_logo_img_text" src="/img/logo-text.png" alt="" title=""> -->
            </a>
          </div>
          <div class="_navbar_left_button"> 
            <div @click="sidebar = !sidebar" class="_navbar_left_icon">
              <Icon type="md-list" />
            </div>
            <div @click="mobSidebar = !mobSidebar" class="_navbar_left_icon _mob_icon">
              <Icon type="md-list" />
            </div>
          </div>
          <!-- <div class="_navbar_search">
            <div class="_navbar_search_main_all">
              <div class="_navbar_search_main">
                <Icon type="ios-search-outline" />
                <input type="text"  placeholder="Search..">
                <div class="outline"></div>
              </div>
            </div>
          </div> -->
        </div>
        <div class="_navbar_right">
          <ul class="_navbar_right_list">
            <li @click="openFranchiseModal" style="cursor: pointer;" ><Icon type="md-locate"  /> <span>{{showFranchiseName}}</span>  </li>
            <li class="_mosearch" @click="mobSearchOpen = true"><Icon type="ios-search-outline" /></li>
            <!-- <li class="_1noti">
              <Dropdown trigger="click" placement="bottom-end">
                <a href="javascript:void(1)">
                  <Icon type="ios-notifications-outline" />
                  <p class="_noti_num">3</p>
                </a>
                <DropdownMenu slot="list">
                    <p class="_1noti_title">Notifications</p>
                    <template v-if="spinLoading">
                      <div class="_1noti_list _1scrollbar">
                         <div  class="_1noti_list_items">
                          <div class="_1noti_list_pic">
                            <img class="_1noti_list_img" src="/img/photo.jpg" alt="" title="">
                          </div>
                          <div class="_1noti_list_details">
                            <p class="_1noti_list_title _1text_overflow">has comment on your board</p>
                            <p class="_1noti_list_time">10:12:2020</p>
                          </div>
                        </div>
                        <div class="_1noti_list_items _active">
                          <div class="_1noti_list_pic">
                            <img class="_1noti_list_img" src="/img/photo.jpg" alt="" title="">
                          </div>
                          <div class="_1noti_list_details"> 
                            <p class="_1noti_list_name _1text_overflow">Arnold Schwarzenegger</p>
                            <p class="_1noti_list_title _1text_overflow">has comment on your board</p>
                            <p class="_1noti_list_time">10:12:2020</p>
                          </div>
                        </div>
                        <div   class="_1noti_list_items">
                          <div class="_1noti_list_pic">
                            <img class="_1noti_list_img" src="/img/photo.jpg" alt="" title="">
                          </div>
                          <div class="_1noti_list_details">
                            <p class="_1noti_list_name _1text_overflow">Arnold Schwarzenegger</p>
                            <p class="_1noti_list_title _1text_overflow">has comment on your board</p>
                            <p class="_1noti_list_time">10:12:2020</p>
                          </div>
                        </div>
                       </div>
                      <div class="_1noti_see">
                        <a class="_1noti_see_text _2link" href="">See all notifications</a>
                      </div>
                    </template>
                    <template v-else>
                      <div class="_noti_load demo-spin-col">
                        <Spin fix>
                          <Icon type="ios-loading" size=18 class="demo-spin-icon-load"></Icon>
                          <div>Loading</div>
                        </Spin>
                      </div>
                    </template>
                </DropdownMenu>
              </Dropdown>
            </li>
            <li class="_1noti">
              <Dropdown trigger="click" placement="bottom-end">
                <a href="javascript:void(0)">
                  <Icon type="ios-mail-outline" /> 
                  <p class="_noti_num">5</p>
                </a>
                <DropdownMenu slot="list">
                    <p class="_1noti_title">Messages</p>

                    <template v-if="!spinLoading">
                      <div class="_1noti_list _1scrollbar">
                         <div class="_1noti_list_items">
                          <div class="_1noti_list_pic">
                            <img class="_1noti_list_img" src="/img/photo.jpg" alt="" title="">
                          </div>

                          <div class="_1noti_list_details">
                            <p class="_1noti_list_name _1text_overflow">Arnold Schwarzenegger</p>
                            <p class="_1noti_list_title _1text_overflow">has comment on your board</p>

                            <p class="_1noti_list_time">10:12:2020</p>
                          </div>
                        </div>
                        <div class="_1noti_list_items _active">
                          <div class="_1noti_list_pic">
                            <img class="_1noti_list_img" src="/img/photo.jpg" alt="" title="">
                          </div>

                          <div class="_1noti_list_details">
                            <p class="_1noti_list_name _1text_overflow">Arnold Schwarzenegger</p>
                            <p class="_1noti_list_title _1text_overflow">has comment on your board</p>

                            <p class="_1noti_list_time">10:12:2020</p>
                          </div>
                        </div>
                        <div class="_1noti_list_items">
                          <div class="_1noti_list_pic">
                            <img class="_1noti_list_img" src="/img/photo.jpg" alt="" title="">
                          </div>

                          <div class="_1noti_list_details">
                            <p class="_1noti_list_name _1text_overflow">Arnold Schwarzenegger</p>
                            <p class="_1noti_list_title _1text_overflow">has comment on your board</p>

                            <p class="_1noti_list_time">10:12:2020</p>
                          </div>
                        </div>
                       </div>

                      <div class="_1noti_see">
                        <a class="_1noti_see_text _2link" href="">See all notifications</a>
                      </div>
                    </template>

                    <template v-else>
                      <div class="_noti_load demo-spin-col">
                        <Spin fix>
                          <Icon type="ios-loading" size=18 class="demo-spin-icon-load"></Icon>
                          <div>Loading</div>
                        </Spin>
                      </div>
                    </template>
                </DropdownMenu>
              </Dropdown>
            </li> -->
            <li class="_nav_pro">
              <Dropdown trigger="click" placement="bottom-end">
                <a href="javascript:void(2)">
                  <div class="_nav_pro_pic">
                    <!-- <img class="_nav_pro_img" src="/img/photo.jpg" alt="" title=""> -->
                    <img v-if="authInfo.image" class="_nav_pro_img" :src="authInfo.image" title="" alt="">
                    <img v-else class="_nav_pro_img" src="/img/profile.png" title="" alt="">
                  </div>
                </a>
                <DropdownMenu slot="list">
                  <div class="_nav_pro_main">
                    <div class="_nav_pro_top">
                      <div class="_nav_pro_top_pic">
                        <img v-if="authInfo.image" class="_nav_pro_top_img" :src="authInfo.image" title="" alt="">
                        <img v-else class="_nav_pro_top_img" src="/img/profile.png" title="" alt="">
                      </div>
                      <div class="_nav_pro_top_details">
                        <p class="_nav_pro_top_name">{{authInfo.name}}</p>
                        <p class="_nav_pro_top_email">{{authInfo.countryCode}}{{authInfo.phone}}</p>
                      </div>
                    </div>
                    <div class="_nav_pro_list_main _1border_color">
                      <ul class="_nav_pro_list">
                        <li>
                          <router-link to="/profile">
                            <Icon type="md-person" />
                            <p class="_nav_pro_list_text">My Profile</p>
                          </router-link>
                        </li> 
                        <li> 
                          <a>
                             <Icon @click="lightSidebarClick" type="ios-sunny" />
                             <p @click="lightSidebarClick" class="_nav_pro_list_text">Light</p>
                          </a> 
                        </li>
                        <li>  
                          <a >
                            <Icon @click="darkModeClick" type="ios-moon" />
                            <p @click="darkModeClick" class="_nav_pro_list_text">Dark</p>
                          </a>
                        </li>
                      </ul>
                    </div>
                    <div class="_nav_pro_list_main _1border_color">
                      <ul class="_nav_pro_list">
                        <li>
                          <a href="/usermodule/logout">
                            <Icon type="ios-exit" />
                            <p class="_nav_pro_list_text">Log Out</p>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </DropdownMenu>
              </Dropdown>
            </li>
          </ul>
        </div>
        <!-- Mobile Search -->
        <div class="_mob_search"  :class="mobSearchOpen? '_mob_search_open' : ''">
          <div class="_mob_search_main">
            <div class="_navbar_search_main_all">
              <div class="_navbar_search_main">
                <Icon type="ios-search-outline" />
                <input type="text"  placeholder="Search..">
                <div class="outline"></div>
              </div>
            </div>
          </div>
          <div class="_mob_search_close"><Icon @click="mobSearchOpen = false" type="md-close" /></div>
        </div>
        <!-- Mobile Search -->
      </nav>
      <!-- Menu end -->

      <!-- Sidebar  -->
      <aside v-if="$route.path != '/login' && $route.path != '/register' && $route.path != '/resetPassword' && $route.path != '/forgetPassword'" :class="[(sidebar? '_left_sidebar _hide_sidebar':'_left_sidebar') , (lightSidebar? '_light_sidebar' : ''), (darkSidebar? '_dark_sidebar' : ''), (mobSidebar? '_mobSidebarOpen':'')]">
        <div class="_left_sidebar_main">
          <div class="_left_sidebar_top">
            <div class="_mobile_logo">
              <a href="" class="_navbar_logo_link">
                  DUARE DASHBOARD
                <!-- <img src="/img/main-logo.png" alt="" title="" class="_navbar_logo_img"> 
                <img src="/img/header.png" alt="" title="" class="_navbar_logo_img_text"> -->
              </a>
            </div>

            <div class="_left_sidebar_top_main">
              
              <div class="_left_sidebar_pic">
                  <img v-if="authInfo.image" class="_left_sidebar_img" :src="authInfo.image" title="" alt="">
                  <img v-else class="_left_sidebar_img" src="/img/profile.png" title="" alt="">
                <!-- <img class="_left_sidebar_img" src="/img/photo.jpg" alt="" title=""> -->
              </div>
            </div>
            <p class="_left_sidebar_pro">{{authInfo.userType | adminTitle}}</p>
            <p class="_left_sidebar_name">{{authInfo.name}}</p>
          </div>
          
          <div class="_left_sidebar_menu _1scrollbar" style="margin-bottom: 58px;">
            <Menu :theme="theme3" :active-name="activeRoute">
              <MenuGroup title="Dashboard">
                <p class="_group_name"><Icon type="ios-more" /></p>
                <MenuItem to="/"  v-if="authInfo.userType != 'ShopOwner'"  name="index"><Icon type="md-home" /><span class="submenu_text">Home</span></MenuItem>
                <MenuItem to="/franchisemodule/gallery" name="franchisemodule">
                    <Icon type="ios-color-palette" />
                    <span class="submenu_text">Gallery</span>
                </MenuItem>
                <Submenu name="1" v-if="authInfo.userType != 'ShopOwner'" >
                  <template slot="title"><Icon type="md-stats" /><span class="submenu_text">Statistics</span></template>
                  <MenuItem to="/usermodule/statistics" name="statistics"><Icon type="ios-analytics" /><span class="submenu_text">Statistics</span></MenuItem>
                  <MenuItem to="/usermodule/user_balance" name="usermoduleModule_user_balance"><Icon type="ios-analytics" /><span class="submenu_text">User Balance</span></MenuItem>
                  <MenuItem  to="/usermodule/expense_category" name="expense_category">  <Icon type="ios-analytics" /><span class="submenu_text">Expense Category</span></MenuItem>
                  <MenuItem  to="/usermodule/expense_sheets" name="expense_sheets">  <Icon type="ios-analytics" /><span class="submenu_text">Expense Sheet</span></MenuItem>
                </Submenu>
                <Submenu name="2" v-if="authInfo.userType != 'ShopOwner'">
                  <template slot="title"><Icon type="ios-people" /><span class="submenu_text">Users</span></template>
                    <MenuItem to="/usermodule/user-list" name="usermoduleModuleuserList"><Icon type="ios-analytics" /><span class="submenu_text">Customer lists</span></MenuItem>
                    <MenuItem to="/usermodule/franchiseOwner" name="franchiseOwner" v-if="userType == 'Admin'"><Icon type="ios-analytics" /><span class="submenu_text">Franchises Owner</span></MenuItem>
                    <MenuItem to="/usermodule/shop-owner-list" name="shopOwnerList"><Icon type="ios-analytics" /><span class="submenu_text">Shop Owner lists</span></MenuItem>
                    <MenuItem to="/usermodule/driverList" name="driverList"><Icon type="ios-analytics" /><span class="submenu_text">Driver lists</span></MenuItem>
                    <MenuItem to="/usermodule/newMemberRequest" name="newMemberRequest"><Icon type="ios-analytics" /><span class="submenu_text">New Member Request</span></MenuItem>
                </Submenu>
                <Submenu name="3" v-if="authInfo.userType != 'ShopOwner'">
                  <template slot="title"><Icon type="md-map" /><span class="submenu_text">Franchise</span></template>
                    <MenuItem to="/franchisemodule/franchise-list" name="franchise" v-if="userType == 'Admin'"><Icon type="ios-analytics" /><span class="submenu_text">Franchises</span></MenuItem>
                    <MenuItem to="/franchisemodule/addFranchisePolygon" name="addFranchisePolygon" v-if="userType == 'Admin'"><Icon type="ios-analytics" /><span class="submenu_text">Add Franchises</span></MenuItem>
                    <MenuItem to="/franchisemodule/myFranchise" name="myFranchise" v-if="userType == 'FranchiseOwner'"><Icon type="ios-analytics" /><span class="submenu_text">My Franchises</span></MenuItem>
                    <MenuItem to="/franchisemodule/shop-list" name="shopList"><Icon type="ios-analytics" /><span class="submenu_text">Shops</span></MenuItem>
                    <MenuItem to="/franchisemodule/shipping_price" name="shipping_price"><Icon type="ios-analytics" /><span class="submenu_text">Shipping Price</span></MenuItem>
                    <MenuItem to="/franchisemodule/franchise_daily_schedule" name="franchise_daily_schedule"><Icon type="ios-analytics" /><span class="submenu_text">Franchise Daily Schedule</span></MenuItem>
                </Submenu>
                <Submenu name="4" >
                  <template slot="title"><Icon type="md-cube" /><span class="submenu_text">Products</span></template>
                  <MenuItem v-if="authInfo.userType != 'ShopOwner'" to="/productmodule/category" name="category"><Icon type="ios-analytics" /><span class="submenu_text">Category list</span></MenuItem>
                  <MenuItem v-if="authInfo.userType != 'ShopOwner'" to="/productmodule/product-brands" name="band"><Icon type="ios-analytics" /><span class="submenu_text">Product brands</span> </MenuItem>
                  <MenuItem to="/productmodule/product" name="product"><Icon type="ios-analytics" /><span class="submenu_text">Products</span></MenuItem>
                  <MenuItem to="/productmodule/addProduct" name="addProduct"><Icon type="ios-analytics" /><span class="submenu_text">Add Products</span></MenuItem>
                  <MenuItem  v-if="authInfo.userType != 'ShopOwner'"  to="/productmodule/requestedProduct" name="requestedProduct"><Icon type="ios-analytics" /><span class="submenu_text">  Requested Products</span></MenuItem>
                </Submenu> 
                <Submenu name="5" v-if="authInfo.userType != 'ShopOwner'"> 
                  <template slot="title"><Icon type="ios-bug" /><span class="submenu_text">App</span></template>
                  <MenuItem v-if="authInfo.userType != 'ShopOwner'" to="/usermodule/onBoardScreen" name="onBoardScreen"><Icon type="ios-analytics" /><span class="submenu_text">On Board Screens</span></MenuItem>
                  <MenuItem v-if="authInfo.userType != 'ShopOwner'" to="/usermodule/promotionScreen" name="promotionScreen"><Icon type="ios-analytics" /><span class="submenu_text">Promotion Screens</span></MenuItem>
                  <MenuItem v-if="authInfo.userType != 'ShopOwner'" to="/usermodule/privacyPolicy" name="privacyPolicy"><Icon type="ios-analytics" /><span class="submenu_text">Privacy Policy</span></MenuItem>
                  <MenuItem v-if="authInfo.userType != 'ShopOwner'" to="/usermodule/termsAndCondition" name="termsAndCondition"><Icon type="ios-analytics" /><span class="submenu_text">Terms And Conditions</span></MenuItem>
                  <MenuItem v-if="authInfo.userType != 'ShopOwner'" to="/usermodule/returnRefundPolicy" name="returnRefundPolicy"><Icon type="ios-analytics" /><span class="submenu_text">Return Refund Policy</span></MenuItem>
                  <MenuItem v-if="authInfo.userType != 'ShopOwner'" to="/usermodule/banner" name="banner"><Icon type="md-albums" /><span class="submenu_text">Banners</span></MenuItem>
                </Submenu>
                <MenuItem to="/ordermodule/orders" name="oreder"><Icon type="ios-analytics" /> <span v-if=" authInfo && authInfo.userType  != 'ShopOwner'">({{totalOrders}}) </span><span class="submenu_text">Orders</span></MenuItem>
                <MenuItem v-if="authInfo.userType != 'ShopOwner'" to="/productmodule/cupon" name="cupon"><Icon type="ios-analytics" /><span class="submenu_text">Coupon list</span></MenuItem>
                <MenuItem v-if="authInfo.userType != 'ShopOwner'" to="/usermodule/packages" name="package"><Icon type="ios-analytics" /><span class="submenu_text">Package list</span></MenuItem>
               
                <!-- <MenuItem to="/productmodule/menu_categories" name="menu_categories">  
                    <Icon type="ios-analytics" />
                    <span class="submenu_text">Restaurant Menu Category</span>
                </MenuItem> -->
              
             
              </MenuGroup>
            </Menu>
          </div>
          <!-- <div class="_left_sidebar_bottom _1border_color">
            <Menu :theme="theme3" active-name="11">
                <MenuItem name="1">
                    <Icon @click="lightSidebarClick" type="ios-sunny" />
                    <span @click="lightSidebarClick" class="submenu_text">Light</span>
                </MenuItem>
                <MenuItem name="2">
                    <Icon @click="darkModeClick" type="ios-moon" />
                    <span @click="darkModeClick" class="submenu_text">Dark</span>
                </MenuItem>
                <MenuItem name="3">
                  <a href="/usermodule/logout">
                    <Icon type="md-log-out" />
                    <span class="submenu_text">Logout</span>
                    </a>
                </MenuItem>
            </Menu> -->
            <!-- <p>Change theme</p>
            <RadioGroup v-model="theme3">
                <Radio label="light"></Radio>
                <Radio label="dark"></Radio>
            </RadioGroup> -->
          <!-- </div> -->
        </div>
      </aside>
      <!-- Sidebar --> 
      <div :class="[(sidebar? '_main_layout _mini_main_layout':'_main_layout') , ($route.path  != '/login' && $route.path  != '/register' && $route.path  != '/resetPassword' && $route.path  != '/forgetPassword'? '':'_login_layout')]"> 
        <router-view></router-view>
        <Footer v-if="$route.path != '/login' && $route.path != '/register' && $route.path != '/resetPassword' && $route.path != '/forgetPassword' && $route.path  != '/messenger'"/>
      </div>
    </div>
    <Modal title="Select Franchise"  v-model="selectFranchiseModal" :mask-closable="false" :closable="false" >
      <Form   label-position="top" >
          <FormItem    label="Select Franchise" >
            <Select v-model="mainFranchise">
              <Option v-for="(item, index) in franchiseList" :value="item.id" :key="index">{{item.name}}</Option>
            </Select>
          </FormItem> 
      </Form>
      <div slot="footer">
          <Button type="primary"  @click="selectFranchise" >Select</Button>
      </div>
    </Modal>
    <Gallery />
   </div>
</template>
<script>
import { mapGetters } from 'vuex'
import Footer from "./components/footer.vue";
import Gallery from "./components/upload_from_gallery.vue"; 
export default {
  components: { 
    Footer, Gallery
  }, 
  data(){
    return{
      totalOrders:0,
      userType:'',
      activeRoute:'index',
      sidebar:false,
      selectFranchiseModal:false,
      isHovering: false,
      theme3: 'light',
      lightSidebar: true,
      darkSidebar: false,
      darkMode: false,
      mobSearchOpen: false,
      mobSidebar: false,
      spinLoading: false,
      mainFranchise:'',
    }
  },
  methods:{
    async openFranchiseModal(){
      if(!this.isAdmin) return
      this.selectFranchiseModal = true;
    },
      async lightSidebarClick(){ 
        const res = await this.callApi('post', `usermodule/lightModeSave`)
        if(res.status == 200 ){
            this.lightSidebar = true
            this.darkSidebar = false
            this.darkMode = false
        }
      },
      async darkModeClick(){
        const res = await this.callApi('post', `usermodule/darkModeSave`)
        if(res.status == 200 ){
            this.darkSidebar = true
            this.lightSidebar = false
            this.darkMode = true
        }
      },
      async get_total_pending_order(){
            const res = await this.callApi('get', `/usermodule/get_total_pending_order`)
            if(res.status==200){
                this.totalOrders = res.data
                this.$store.commit('setNewOrder', this.totalOrders);
            } 
        }, 
    	async getAllFranchise(){
        const res = await this.callApi('get', `/franchisemodule/allfranchises`)
        if(res.status==200){
          this.$store.commit('setFranchiseList',res.data.data)
          this.$store.commit('setFranchise_id',res.data.data[0].id);
        this.$store.commit('setFranchiseDetails',res.data.data[0]);
        this.mainFranchise = res.data.data[0].id
				  
        } 
      }, 
      selectFranchise(){
        if(this.mainFranchise == '') return this.i("Please select a franchaise!");
        this.$store.commit('setFranchise_id',this.mainFranchise);
        let index = this.franchiseList.findIndex(d=> d.id == this.mainFranchise);
        this.$store.commit('setFranchiseDetails',this.franchiseList[index]);
        this.selectFranchiseModal = false;

      },
  },
  watch: { 
    "$route.name": function (newVal, oldVal) {
       this.activeRoute = newVal; 
       console.log(newVal) 
      },
  },
  computed:{...mapGetters([
         'getNewOrder', 
        ]),
    showFranchiseName(){
        if(this.franchiseDetails && this.franchiseDetails.name) return this.franchiseDetails.name
        return "Select a Franchise";
        
      }
  },
   
  filters:{
    adminTitle(value){
        if(value == 'Admin') return "Super-Admin"
        else if(value == 'FranchiseOwner') return "Franchise-Owner"
        else if(value == 'ShopOwner') return "Shop-Owner"
        
      }
  },
  async created(){
     this.activeRoute = this.$route.path;
     this.$store.commit('setAuthuser', window.authUser);
     this.userType = this.authInfo.userType
     if(!this.authInfo) { 
      try {
        if(this.$route.path != '/login') this.$router.push('/login');
      } 
      catch(error) {
          console.log("0014 route.path::")
      }
    }else{ 
      if(authUser.userType  == 'ShopOwner'){
        // window.location ='/franchisemodule/gallery'
  			this.$router.push('/franchisemodule/gallery')
	   	}
      if(authUser.dark_mode == 1){
        this.lightSidebar= false
        this.darkSidebar= true  
        this.darkMode= true
      }
    }

    this.get_total_pending_order();
    await this.getAllFranchise();
    if(this.authInfo.userType == 'Admin'){
      // this.selectFranchiseModal = true;
      this.$store.commit('setIsAdmin',true)
    }
    else {
       this.$store.commit('setFranchise_id',this.authInfo.franchise_id);
        this.$store.commit('setIsAdmin',false)
        let index = this.franchiseList.findIndex(d=> d.id == this.authInfo.franchise_id);
        this.$store.commit('setFranchiseDetails',this.franchiseList[index]);
    }
    setInterval(this.get_total_pending_order, 10000);

  }
}
</script>

<style scoped>
 .demo-spin-icon-load{
    animation: ani-demo-spin 1s linear infinite;
  }
  @keyframes ani-demo-spin {
    from { transform: rotate(0deg);}
    50%  { transform: rotate(180deg);}
    to   { transform: rotate(360deg);}
  }
 .demo-spin-col{
    height: 200px;
    position: relative;
 }
 </style>
