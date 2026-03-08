document.getElementById("registerForm").addEventListener("submit", function(e){

e.preventDefault();

let username = document.getElementById("username").value;
let email = document.getElementById("email").value;
let password = document.getElementById("password").value;

fetch("../../api/register.php",{

method:"POST",
headers:{
"Content-Type":"application/json"
},

body: JSON.stringify({
username: username,
email: email,
password: password
})

})

.then(res => res.text())
.then(data => {

if(data === "success"){
alert("Register berhasil!");
window.location.href="../Login/index.html";
}
else{
alert("Register gagal");
}

});

});