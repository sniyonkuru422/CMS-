
function validateLogin() {
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    if(email == "" || password == "") {
        alert("Please fill in all fields");
        return false;
    }

    // Here, normally you check credentials in the database
    alert("Login successful (demo)");
    return true;
}

// Function to validate the company registration form

function validateCompanyForm() {
    let companyName = document.getElementById("companyName").value;
    let companyEmail = document.getElementById("companyEmail").value;
    let adminName = document.getElementById("adminName").value;
    let adminEmail = document.getElementById("adminEmail").value;
    let adminPassword = document.getElementById("adminPassword").value;

    if(companyName == "" || companyEmail == "" || adminName == "" || adminEmail == "" || adminPassword == "") {
        alert("Please fill in all fields");
        return false;
    }

    // Here, normally you would submit the form data to the server
    alert("Company registered successfully (demo)");
    return true;
}