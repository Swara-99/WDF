// Welcome button

let button = document.getElementById("welcomeButton");

if (button) {

    button.onclick = function() {

        alert("Welcome to Student Hub!");

    };

}


// Register button

let registerButton = document.getElementById("registerButton");

if (registerButton) {

    registerButton.onclick = function() {

        alert("Registration successful!");

    };

}


// Edit button

let editButton = document.getElementById("editButton");

if (editButton) {

    editButton.onclick = function() {

        alert("You can edit your profile here.");

    };

}


// Event button

let eventButton = document.getElementById("eventButton");

if (eventButton) {

    eventButton.onclick = function() {

        alert("You have registered for the event!");

    };

}


// Registration Form

let form = document.getElementById("registrationForm");

if (form) {

    form.onsubmit = function(event) {

        event.preventDefault();


        let name = document.getElementById("name").value;
        let email = document.getElementById("email").value;
        let mobile = document.getElementById("mobile").value;
        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("confirmPassword").value;
        let course = document.getElementById("course").value;
        let year = document.getElementById("year").value;
        let terms = document.getElementById("terms").checked;

        let valid = true;


        // Name

        let namePattern = /^[A-Za-z ]+$/;

        if (name == "" || !namePattern.test(name)) {

            document.getElementById("nameError").textContent =
                "Enter a valid name";

            valid = false;

        } else {

            document.getElementById("nameError").textContent = "";

        }


        // Email

       let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email)) {

            document.getElementById("emailError").textContent =
                "Enter a valid email";

            valid = false;

        } else {

            document.getElementById("emailError").textContent = "";

        }


        // Mobile

        let mobilePattern = /^[0-9]{10}$/;

        if (!mobilePattern.test(mobile)) {

            document.getElementById("mobileError").textContent =
                "Enter 10 digit mobile number";

            valid = false;

        } else {

            document.getElementById("mobileError").textContent = "";

        }


        // Password

        if (password.length < 6) {

            document.getElementById("passwordError").textContent =
                "Password must have at least 6 characters";

            valid = false;

        } else {

            document.getElementById("passwordError").textContent = "";

        }


        // Confirm Password

        if (password != confirmPassword) {

            document.getElementById("confirmError").textContent =
                "Passwords do not match";

            valid = false;

        } else {

            document.getElementById("confirmError").textContent = "";

        }


        // Course

        if (course == "") {

            document.getElementById("courseError").textContent =
                "Select a course";

            valid = false;

        } else {

            document.getElementById("courseError").textContent = "";

        }


        // Year

        if (year == "") {

            document.getElementById("yearError").textContent =
                "Select a year";

            valid = false;

        } else {

            document.getElementById("yearError").textContent = "";

        }


        // Terms

        if (!terms) {

            document.getElementById("termsError").textContent =
                "Accept the terms";

            valid = false;

        } else {

            document.getElementById("termsError").textContent = "";

        }


        // Success

        if (valid) {

            alert("Registration successful!");

        }

    };

}


// Login Form

let loginForm = document.getElementById("loginForm");

if (loginForm) {

    loginForm.onsubmit = function(event) {

        let username = document.getElementById("username").value;
        let password = document.getElementById("password").value;

        if (username == "" || password == "") {

            event.preventDefault();

            alert("Please enter email and password.");

        }

    };



}
