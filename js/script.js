const togglePassword = document.querySelector(".toggle-password");
const password = document.querySelector("#password");

togglePassword.addEventListener("click", function(){

    if(password.type === "password"){

        password.type = "text";
        this.textContent = "visibility_off";

    }else{

        password.type = "password";
        this.textContent = "visibility";

    }

});