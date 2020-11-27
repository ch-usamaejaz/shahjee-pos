<template>
    <div>
        <div class="br-mainpanel">
            <div class="pd-30">
                <h4 class="tx-gray-800 mg-b-5">Profile</h4>
                <v-form
                    ref="form"
                    v-model="valid"
                    lazy-validation
                    >
                    <v-text-field
                        v-model="companyName"
                        :counter="5"
                        :rules="companyNameRules"
                        label="Company Name"
                        required
                    ></v-text-field>

                    <v-text-field
                        v-model="email"
                        :rules="emailRules"
                        label="E-mail"
                        required
                    ></v-text-field>

                    <v-text-field
                        v-model="currentPassword"
                        label="Current Password"
                        :rules="newPasswordRules"
                        required
                        type="password"
                    ></v-text-field>

                    <v-text-field
                        v-model="newPassword"
                        label="New Password"
                        :counter="8"
                        
                        type="password"
                    ></v-text-field>

                    <v-btn
                        color="success"
                        class="mr-4"
                        @click="saveNew"
                    >
                        Save
                    </v-btn>

                    <v-btn
                        color="error"
                        class="mr-4"
                        @click="reset"
                    >
                        Reset Form
                    </v-btn>
                </v-form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'profile',
    data: () => ({
      valid: true,
      name: '',
      currentPassword: '',
      newPassword: '',
      companyName: '',
      companyNameRules: [
        v => !!v || 'Company Name is required',
        v => (v && v.length >= 5) || 'Company Name must be greater than 5 characters',
      ],
      nameRules: [
        v => !!v || 'Name is required',
        v => (v && v.length >= 5) || 'Name must be greater than 5 characters',
      ],
      email: '',
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
      newPasswordRules: [
        v => !!v || 'Password is required',
        // v => (v && v.length >= 8) || 'Password must be greater than 8 characters',
      ]
    }),
    methods: {
      saveNew () {
        let formData = new FormData();
        let newData = {
            "name": this.name,
            "e-mail": this.email,
            "companyName": this.companyName,
            "newPassword": this.newPassword
        }
        let url = '/profile';
        this.axios.post(url,newData).then(response =>{
        if(!response.data.error){
            this.showSuccessAlert('Profile Updated')
            return
        }
        this.showErrorAlert(response.data.message)
        }, (error) =>{
            this.showErrorAlert(error.message);
        })
      },
      reset () {
        this.$refs.form.reset()
      },
    },

}
</script>

<style scoped>

</style>