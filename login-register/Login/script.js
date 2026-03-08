document.getElementById("loginForm").addEventListener("submit", function(e){

e.preventDefault();

let email = document.getElementById("email").value;
let password = document.getElementById("password").value;

fetch("../../api/login.php",{

method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:`email=${email}&password=${password}`

})

.then(res => res.text())
.then(data => {

if(data === "admin"){

alert("Login Admin Berhasil");
window.location.href="../../dashboard-admin/admin.html";

}

else if(data === "user"){

alert("Login Berhasil");
window.location.href="../../index.html";

}

else{

alert("Email atau Password salah");

}

});

});