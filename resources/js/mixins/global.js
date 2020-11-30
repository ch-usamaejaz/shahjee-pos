import swal from 'sweetalert';

const global = {
    methods : {
        showSuccessAlert (message) {
            swal({
                title: "Success!",
                text: message,
                icon: "success",
                buttons: false,
                timer: 2000
            });
        },
        showErrorAlert (message) {
            swal({
                title: "Some error occured!",
                text: message,
                icon: "error",
                buttons: false,
                timer: 2000
            });
        },
        showInfoAlert (title, message) {
            swal({
                title: title,
                text: message,
                icon: "info",
                buttons: false,
                timer: 2000
            });
        }
        // checkSessionExpiredError (message) {
        //     if (message === 'Session Expired') {
        //         store.commit('resetStates'); //local storage
        //         this.$router.push('/');
        //     }
        // }
    }
};

export default global;
