export const sweetAlert = {
    methods: {
        showToast(icon, title) {
            this.$swal
                .mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    onOpen: toast => {
                        toast.addEventListener(
                            "mouseenter",
                            this.$swal.stopTimer
                        );
                        toast.addEventListener(
                            "mouseleave",
                            this.$swal.resumeTimer
                        );
                    }
                })
                .fire({ icon, title });
        }
    }
};
