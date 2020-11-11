<template>
    <div>
      <form  @submit.prevent="signUp()"></form>
        <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v">
          <div class="login-wrapper wd-300 wd-xs-400 pd-25 pd-xs-40 bg-white rounded shadow-base">
            <div class="signin-logo tx-center tx-28 tx-bold tx-inverse">
              <span class="tx-normal">[</span> ShahJee <span class="tx-normal">]</span>
            </div>
          <div class="tx-center mg-b-40">Restuarants</div>

          <div class="form-group">
            <input v-model="email" type="text" class="form-control" placeholder="Enter your Email">
          </div><!-- form-group -->
          <div class="form-group">
            <input v-model="password" type="password" class="form-control" placeholder="Enter your Password">
          </div><!-- form-group -->
          <div class="form-group">
            <input v-model="companyName" type="password" class="form-control" placeholder="Enter your Company Name">
          </div><!-- form-group -->
          
          <div class="form-group tx-12">By clicking the Sign Up button below, you agreed to our privacy policy and terms of use of our website.</div>
          <button type="submit" class="btn btn-info btn-block">Sign Up</button>

          <div class="mg-t-40 tx-center">Already a member? <router-link to="/login" class="tx-info">Log In</router-link></div>
        </div><!-- login-wrapper -->
      </div><!-- d-flex -->
    </div>
</template>

<script>
export default {
  name: 'SignUp',
  data(){
    return{
      email: '',
      password: '',
      companyName: ''
    }
  },
  mounted(){

  },
  methods: {
    signUp(){
      let formData = new FormData();
        formData.append("email" ,this.email);
        formData.append("password",this.password);
        formData.append("companyName", this.companyName)

        let url = '/signup';
        this.axios.post(url, formData).then(function(response){
          console.log(response)
        })
    }
  }  
}
</script>

<style scoped>

</style>