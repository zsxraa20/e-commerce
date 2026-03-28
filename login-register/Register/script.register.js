document.getElementById("registerForm").addEventListener("submit", function(e){
    e.preventDefault();

    let username = document.getElementById("username").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value;

    // Basic validation
    if (!username || !email || !password) {
        alert("Semua field wajib diisi!");
        return;
    }

    if (username.length < 3) {
        alert("Username minimal 3 karakter!");
        return;
    }

    if (password.length < 6) {
        alert("Password minimal 6 karakter!");
        return;
    }

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = "Loading...";
    submitBtn.disabled = true;

    fetch("../../api/register.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            username: username,
            email: email,
            password: password
        })
    })
    .then(res => res.json())
    .then(data => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;

        if (data.success) {
            alert("Register berhasil! Selamat datang, " + data.data.username);
            window.location.href = "../../index.html";
        } else {
            alert(data.message || "Register gagal. Silakan coba lagi.");
        }
    })
    .catch(error => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        alert("Terjadi kesalahan. Silakan coba lagi.");
        console.error("Register error:", error);
    });
});
