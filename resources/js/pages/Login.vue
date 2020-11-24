<template>
    <div>
        <form @submit.prevent="login()">
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
            
                <button type="submit" class="btn btn-info btn-block">Sign In</button>
                <div class="mg-t-60 tx-center">Not yet a member? <router-link to="/signup" class="tx-info">Sign Up</router-link></div>
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
            password: ''
        }
    },
    mounted(){

    },
    methods: {
        login(){
            let formData = new FormData();
            formData.append("email" ,this.email);
            formData.append("password",this.password);

            let url = '/login';
            this.axios.post(url, formData).then(function(response){
                console.log(response)
            })
        }
    }
}
</script>

<style scoped>

</style>