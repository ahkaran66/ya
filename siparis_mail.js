function siparis_mail() {
    var email = document.getElementById("email").value;

    if (email.trim() !== "") {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "siparis_mail.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log("Sipariş verildi, mail gönderildi.");
            }
        };
        xhr.send("email=" + email);
    } else {
        console.log("E-posta adresi boş olamaz.");
    }
}
