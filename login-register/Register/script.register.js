document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const username = document.getElementById("username").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const errorMsg = document.getElementById("errorMsg");

    if (username === "" || email === "" || password === "") {
        errorMsg.style.color = "red";
        errorMsg.textContent = "Semua field harus diisi!";
    } 
    else if (password.length < 6) {
        errorMsg.style.color = "red";
        errorMsg.textContent = "Password minimal 6 karakter!";
    } 
    else {
        errorMsg.style.color = "lightgreen";
        errorMsg.textContent = "Registrasi berhasil (simulasi)";
    }
});