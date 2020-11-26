<template>
<div>
        <!-- ########## START: LEFT PANEL ########## -->
    <div class="br-logo"><router-link to="/dashboard"><span>[</span>ShahJee<span>]</span></router-link></div>
    <div class="br-sideleft overflow-y-auto">
      <label class="sidebar-label pd-x-15 mg-t-20">Navigation</label>
      <v-divider></v-divider>
      <div class="br-sideleft-menu">
        <router-link to="/dashboard" :class="[$route.name == 'dashboard' ? 'active br-menu-link' : 'br-menu-link']">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-home-outline tx-22"></i>
            <span class="menu-item-label">&nbsp;Dashboard</span>
          </div><!-- menu-item -->
        </router-link><!-- br-menu-link -->
        <v-divider></v-divider>
        <router-link to="/orders" :class="[$route.name == 'orders' ? 'active br-menu-link' : 'br-menu-link']">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-paper-outline tx-22"></i>
            <span class="menu-item-label">&nbsp;Orders</span>
          </div><!-- menu-item -->
        </router-link><!-- br-menu-link -->
        <v-divider></v-divider>
        <router-link to="/inventory" :class="[$route.name == 'inventory' ? 'active br-menu-link' : 'br-menu-link']">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-bookmarks-outline tx-22"></i>
            <span class="menu-item-label">&nbsp;Menu</span>
          </div><!-- menu-item -->
        </router-link><!-- br-menu-link -->
        <v-divider></v-divider>
        <router-link to="/store" :class="[$route.name == 'store' ? 'active br-menu-link' : 'br-menu-link']">
          <div class="br-menu-item">
            <i class="menu-item-icon icon ion-ios-cart-outline tx-22"></i>
            <span class="menu-item-label">&nbsp;Store</span>
          </div><!-- menu-item -->
        </router-link><!-- br-menu-link -->
        <v-divider></v-divider>
      </div><!-- br-sideleft-menu -->
    </div><!-- br-sideleft -->
</div>
</template>

<script>
    export default {
        name: "navBar"
    }
</script>

<style scoped>

</style>
