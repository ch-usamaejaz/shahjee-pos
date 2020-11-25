<template>
<div>
        <!-- ########## START: HEAD PANEL ########## -->
    <div class="br-header">
      <div class="br-header-left">
        <div class="navicon-left hidden-md-down">
            <a id="btnLeftMenu" href=""><i class="icon ion-navicon-round"></i></a>
        </div>
        <div class="navicon-left hidden-lg-up">
            <a id="btnLeftMenuMobile" href=""><i class="icon ion-navicon-round"></i></a>
        </div>
      </div><!-- br-header-left -->
      <div class="br-header-right">
        <nav class="nav">
          <div class="dropdown">
            <a href="" class="nav-link nav-link-profile" data-toggle="dropdown">
              <span class="logged-name hidden-md-down">Hi, ShahJee Resturents &nbsp;</span>
              <!-- <img src="../../../images/logo.png" class="wd-32 rounded-circle" alt=""> -->
              <!-- <span class="square-10 bg-success"></span> -->
            </a>
            <div class="dropdown-menu dropdown-menu-header wd-200">
              <ul class="list-unstyled user-profile-nav">
                <router-link to="/profile"><li><i class="icon ion-ios-person"></i> Edit Profile</li></router-link>
                <router-link to="/"><li @click="removeStorage"><i class="icon ion-power"></i> Sign Out</li></router-link>
              </ul>
            </div><!-- dropdown-menu -->
          </div><!-- dropdown -->
        </nav>
      </div><!-- br-header-right -->
    </div><!-- br-header -->
    <!-- ########## END: HEAD PANEL ########## -->
</div>
</template>

<script>
export default {
    name: "topBar",
    methods: {
      removeStorage(){
        localStorage.removeItem(1);
        // router.push({path: '/'})
      }
    }
}
</script>

<style scoped>

</style>
