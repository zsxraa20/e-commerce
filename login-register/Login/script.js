document.getElementById("loginForm").addEventListener("submit", function(e){
    e.preventDefault();

    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value;
    let errorMsg = document.getElementById("errorMsg");

    // Basic validation
    if (!email || !password) {
        errorMsg.textContent = "Email dan password wajib diisi!";
        return;
    }

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = "Loading...";
    submitBtn.disabled = true;

    fetch("../../api/login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            email: email,
            password: password
        })
    })
    .then(res => res.json())
    .then(data => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;

        if (data.success) {
            if (data.data.role === "admin") {
                alert("Login Admin Berhasil!");
                window.location.href = "../../dashboard-admin/admin.html";
            } else {
                alert("Login Berhasil! Selamat datang, " + data.data.username);
                window.location.href = "../../index.html";
            }
        } else {
            errorMsg.textContent = data.message || "Email atau Password salah";
        }
    })
    .catch(error => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        errorMsg.textContent = "Terjadi kesalahan. Silakan coba lagi.";
        console.error("Login error:", error);
    });
});

// Google button click handler (placeholder)
document.querySelector('.google-btn').addEventListener('click', function() {
    alert('Fitur login dengan Google akan segera hadir!');
});
