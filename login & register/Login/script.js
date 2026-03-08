document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const errorMsg = document.getElementById("errorMsg");

    // Kredensial Admin (Contoh)
    const adminEmail = "admin@ephone.com";
    const adminPassword = "admin123";

    if (email === "" || password === "") {
        errorMsg.style.color = "red";
        errorMsg.textContent = "Email dan Password harus diisi!";
    } 
    else if (email === adminEmail && password === adminPassword) {
        // Jika login sebagai ADMIN
        alert("Login Berhasil sebagai Admin!");
        window.location.href = "../Admin/dashboard.html"; // Arahkan ke folder dashboard admin
    }
    else if (password.length >= 6) {
        // Jika login sebagai USER BIASA
        alert("Login Berhasil!");
        window.location.href = "../index.html"; // Arahkan balik ke halaman utama
    } 
    else {
        errorMsg.style.color = "red";
        errorMsg.textContent = "Password minimal 6 karakter!";
    }
});

// Di dalam Login/script.js bagian admin login
if (email === "admin@gmail.com" && password === "admin123") {
    alert("Selamat datang Admin!");
    window.location.href = "../admin.html"; // Naik satu level ke root folder
}

