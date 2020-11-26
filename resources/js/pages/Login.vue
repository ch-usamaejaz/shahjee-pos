<template>
    <div>
        <form>
        <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v">
            <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 bg-white rounded shadow-base">
                <div class="signin-logo tx-center">
                     <img src="../../images/logo.png" class="wd-150" alt="">
                </div>
                <v-divider></v-divider> 
                <div class="form-group">
                    <input v-model="email" type="text" class="form-control" placeholder="Enter your Email" required>
                </div><!-- form-group -->

                <div class="form-group">
                    <input v-model="password" type="password" class="form-control" placeholder="Enter your Password" required>
                    <a href="" class="tx-info tx-12 d-block mg-t-10">Forgot password?</a>
                </div><!-- form-group -->
            
                <router-link to="/dashboard"><button type="submit" @click="login" class="btn btn-info btn-block">Log In</button></router-link>
                <!-- <div class="mg-t-60 tx-center">Not yet a member? <router-link to="/signup" class="tx-info">Sign Up</router-link></div> -->
            </div><!-- login-wrapper -->
        </div><!-- d-flex -->
        </form>
    </div>
</template>

<script>
export default {
    name: "Login",
    data(){
        return {
            email: '',
            password: '',
            isAuthenticated: false,
        }
    },
    mounted(){

    },
    methods: {
        login(){
            let data = {"email" : this.email, "password" : this.password, "companyName" : this.companyName};
            let url = '/login';
        //     this.axios.post(url, data).then( response => {
        //         console.log(response)
        //     }).catch(err => {
        //         console.log(err.message);
        //     }
        // )
        this.isAuthenticated = true
        localStorage.setItem(1, this.isAuthenticated)
        // router.push({path: '/dashboard'})
    }
  }
}
</script>

<style scoped>

</style>