console.log("JS LOADED");

// ROLE

let registerRole = "patient";

document

.querySelectorAll(".register-role-btn")

.forEach(btn => {

    btn.addEventListener("click", () => {

        document

        .querySelectorAll(
            ".register-role-btn"
        )

        .forEach(b =>
            b.classList.remove("active")
        );

        btn.classList.add("active");

        registerRole =
        btn.dataset.role;

        const therapistFields =
        document.getElementById(
            "therapistFields"
        );

        if (
            registerRole ===
            "therapist"
        ) {

            therapistFields
            .style.display = "block";

        }

        else {

            therapistFields
            .style.display = "none";
        }
    });

});

let selectedRole = "patient";

document.querySelectorAll(".role-btn")

.forEach(btn => {

    btn.addEventListener("click", () => {

        document
        .querySelectorAll(".role-btn")
        .forEach(b => b.classList.remove("active"));

        btn.classList.add("active");

        selectedRole =
        btn.dataset.role;

        const therapistFields =
        document.getElementById(
            "therapistFields"
        );

        if (
            selectedRole ===
            "therapist"
        ) {

            therapistFields
            .style.display = "block";

        }

        else {

            therapistFields
            .style.display = "none";
        }
    });

});

let otpVerified = false;

// =========================
// NAVBAR
// =========================

// CONTACT BUTTON (scroll)
function scrollToContact() {
    document.getElementById("contact").scrollIntoView({
        behavior: "smooth"
    });
}



// GET STARTED BUTTON
function handleGetStarted() {
    const role = localStorage.getItem("role");

    if (role === "admin") {
        window.location.href = "admin.html";
    } else if (role === "user") {
        window.location.href = "dashboard.php";
    } else {
        window.location.href = "login.html";
    }
}

function goToLogin() {
    window.location.href = "login.html";
}

function goToRegister() {
    window.location.href = "login.html";
}

function scrollToProviders() {
    document.getElementById("providers").scrollIntoView({
        behavior: "smooth"
    });
}

function bookAppointment() {
    const isLoggedIn = localStorage.getItem("user");

    if (isLoggedIn) {
        // go to dashboard
        window.location.href = "dashboard.php";
    } else {
        // go to login first
        window.location.href = "login.html";
    }
}

function goToAllTherapists() {
    window.location.href = "therapists.html";
}

// HERO

function getTherapy() {
    // For now → send to login page
    const isLoggedIn = localStorage.getItem("user");

if (isLoggedIn) {
    window.location.href = "dashboard.php";
} else {
    window.location.href = "login.html";
}
}

function scrollToAbout() {
    document.getElementById("about").scrollIntoView({
        behavior: "smooth"
    });
}

let therapists = [];

let selectedDate = null;
let selectedTime = null;
let selectedTherapist = null;

if (document.getElementById("searchInput")) {

document.addEventListener("DOMContentLoaded", function () {

    

const paymentBtn =
document.querySelector(".payment-btn");
const confirmation = document.getElementById("confirmation");

// ===== ELEMENTS =====
const searchInput = document.getElementById("searchInput");
const dashboardCards = document.getElementById("dashboardCards");
const resultsBox = document.getElementById("results");
const searchPanel = document.getElementById("searchPanel");

// =========================
// 🔍 SEARCH DROPDOWN
// =========================

let therapists = [];

// FETCH THERAPISTS
fetch("php/get_therapists.php")

.then(res => res.text())
.then(text => {

    if(text.trim() === ""){
        return [];
    }

    return JSON.parse(text);
})

.then(data => {

    therapists = data.map(t => ({

    id: t.therapist_id,

    name: t.t_name,

    type: t.specialization,

    image: t.image,

    availability: t.availability

}));
    console.log(therapists);

    const therapistContainer =
document.getElementById("therapistContainer");

if (therapistContainer) {

    therapistContainer.innerHTML = "";

    therapists.forEach(t => {

        const card = document.createElement("div");

        card.classList.add("therapist-card");

        card.innerHTML = `

            <img
            src="php/uploads/${t.image}"
            alt="${t.name}"
            class="therapist-img">

            <h3>Dr. ${t.name}</h3>

            <p>${t.type}</p>

            <button onclick="window.location.href='therapist.html?id=${t.id}'">
                View Profile
            </button>
        `;

        therapistContainer.appendChild(card);

    });
}

})

.catch(err => {

    console.log(err);
});

if (searchInput) {

searchInput.addEventListener("input", () => {

    const query = searchInput.value.toLowerCase().trim();
if(resultsBox){
    resultsBox.innerHTML = "";
}

if (query === "") {
    if(resultsBox){
    resultsBox.style.display = "none";
}

    if(dashboardCards){
    dashboardCards.style.display = "grid";
}

if(searchPanel){
    searchPanel.classList.add("hidden");
}

    return;
}

if(dashboardCards){
    dashboardCards.classList.add("hidden");
    dashboardCards.classList.remove("hidden");
}
if(resultsBox){
    resultsBox.style.display = "flex";
}

const filtered = therapists.filter(t =>
    t.name.toLowerCase().includes(query) ||
    t.type.toLowerCase().includes(query)
);

if(resultsBox){

    if (filtered.length === 0) {

        resultsBox.innerHTML =
        "<div class='result-card'>No results found</div>";

    } else {

        filtered.forEach(t => {

            const card =
            document.createElement("div");

            card.classList.add("result-card");

            card.innerHTML =
            `<b>${t.name}</b><br><small>${t.type}</small>`;

            card.addEventListener("click", () => {

    selectedTherapist = t;

    loadBookedSlots();

    document.getElementById(
        "selectedTherapist"
    ).innerHTML = `

        <img
        src="php/uploads/${t.image}"
        style="
            width:70px;
            height:70px;
            border-radius:50%;
            object-fit:cover;
            margin-bottom:10px;
        ">

        <h3>Dr. ${t.name}</h3>

        <p>${t.type}</p>
    `;

    resultsBox.style.display = "none";

});

            resultsBox.appendChild(card);

        });
    }
}


});
}


// =========================
// ⏎ ENTER → SHOW FULL LIST
// =========================
searchInput.addEventListener("keydown", (e) => {
if (e.key === "Enter") {
showSearchPanel(searchInput.value);
if(resultsBox){
    resultsBox.style.display = "none";
}
}
});

function viewTherapistProfile(id){

    window.location.href =
    `therapist-profile.html?id=${id}`;
}

function showSearchPanel(query) {

query = query.toLowerCase().trim();
if (query === "") return;

dashboardCards.style.display = "none";
searchPanel.classList.remove("hidden");
searchPanel.innerHTML = "";

const filtered = therapists.filter(t =>
    t.name.toLowerCase().includes(query) ||
    t.type.toLowerCase().includes(query)
);

if (filtered.length === 0) {
    searchPanel.innerHTML = "<p>No therapist found</p>";
    return;
}

filtered.forEach(t => {

    const item = document.createElement("div");
    item.classList.add("search-item");

    item.innerHTML = `
        <div class="search-item-info">
            <h4>${t.name}</h4>
            <p>${t.type}</p>
        </div>

        <div class="search-actions">
            <button
class="btn-outline"
onclick="viewTherapistProfile(${t.id})">

    View Profile

</button>
            <button class="btn-fill select-btn">Select</button>
        </div>
    `;

    // 👉 select button
    item.querySelector(".select-btn").addEventListener("click", () => {
        selectTherapist(t);
    });

    searchPanel.appendChild(item);
});


}

// =========================
// 🎯 SELECT THERAPIST (REUSABLE)
// =========================
function selectTherapist(t) {
selectedTherapist = t;

window.currentAvailability =
t.availability || "";

loadBookedSlots();

searchInput.value = t.name;


document.getElementById("selectedTherapist").innerHTML = `

    <img
    src="php/uploads/${t.image}"
    style="
        width:70px;
        height:70px;
        border-radius:50%;
        object-fit:cover;
        margin-bottom:10px;
    ">

    <h4>
        Dr. ${t.name}
    </h4>

    <p>
        ${t.type}
    </p>

`;

searchPanel.classList.add("hidden");


}

// =========================
// 🧹 CLICK OUTSIDE
// =========================
document.addEventListener("click", (e) => {
if (!e.target.closest(".search-box")) {
if(resultsBox){
    resultsBox.style.display = "none";
}
}
});

// =========================
// 📅 CALENDAR
// =========================
let currentDate = new Date();
const calendar = document.getElementById("calendar");
const monthYear = document.getElementById("monthYear");

function renderCalendar() {

const calendar =
document.getElementById("calendar");

if(!calendar) return;

calendar.innerHTML = "";

const year = currentDate.getFullYear();
const month = currentDate.getMonth();

const firstDay = new Date(year, month, 1).getDay();
const lastDate = new Date(year, month + 1, 0).getDate();

monthYear.innerText = currentDate.toLocaleString("default", {
    month: "long",
    year: "numeric"
});

for (let i = 0; i < firstDay; i++) {
    calendar.innerHTML += `<div></div>`;
}

for (let i = 1; i <= lastDate; i++) {
    const dateDiv = document.createElement("div");
    dateDiv.innerText = i;

    const today = new Date();

    const currentDay =
new Date(year, month, i).getDay();

let unavailable = false;

const availability =
window.currentAvailability || "";

if(
    availability.includes("Monday-Friday")
){
    unavailable =
    currentDay === 0 ||
    currentDay === 6;
}

else if(
    availability.includes("Monday-Saturday")
){
    unavailable =
    currentDay === 0;
}

    if (

    unavailable ||

    (
        year === today.getFullYear() &&
        month === today.getMonth() &&
        i < today.getDate()
    )

) {
        dateDiv.classList.add("disabled");
    } else {
        dateDiv.addEventListener("click", () => {
            document.querySelectorAll(".calendar div").forEach(d => d.classList.remove("active"));
            dateDiv.classList.add("active");

            selectedDate = `${i}/${month+1}/${year}`;

            loadBookedSlots();

            checkReady();
        });
    }

    calendar.appendChild(dateDiv);
}


}

const prevBtn =
document.getElementById("prevMonth");

if(prevBtn){

    prevBtn.onclick = () => {

        currentDate.setMonth(
            currentDate.getMonth() - 1
        );

        renderCalendar();
    };
}

const nextBtn =
document.getElementById("nextMonth");

if(nextBtn){

    nextBtn.onclick = () => {

        currentDate.setMonth(
            currentDate.getMonth() + 1
        );

        renderCalendar();
    };
}

renderCalendar();

// =========================
// ⏰ TIME PICKER
// =========================
const timePicker =
document.getElementById("timePicker");

if(timePicker){

    timePicker.addEventListener("change", () => {

        if(!selectedDate){

            alert(
                "Please select a date first"
            );

            timePicker.value = "";

            return;
        }

        const now = new Date();

const today =
`${now.getDate()}/${now.getMonth()+1}/${now.getFullYear()}`;

if(selectedDate === today){

    const currentMinutes =
    now.getHours() * 60 +
    now.getMinutes();

    const [hours, minutes] =
    timePicker.value.split(":");

    const selectedMinutes =
    parseInt(hours) * 60 +
    parseInt(minutes);

    if(selectedMinutes <= currentMinutes){

        alert(
            "Past time slots cannot be selected"
        );

        timePicker.value = "";

        return;
    }
}

        selectedTime = timePicker.value;

        console.log(selectedTime);

        checkReady();

    });

}

// =========================
// ✅ ENABLE BUTTON
// =========================
function checkReady() {
if (selectedDate && selectedTime && selectedTherapist) {
paymentBtn.classList.add("active");
}
}

// =========================
// 📌 BOOK BUTTON
// =========================

if(paymentBtn){

    paymentBtn.addEventListener("click", () => {

        if (
            !selectedTherapist ||
            !selectedDate ||
            !selectedTime
        ) {

            alert(
                "Please select therapist, date and time"
            );

            return;
        }

        fetch(
            "php/booking-appointment.php",
            {

                method: "POST",

                headers: {
                    "Content-Type":
                    "application/json"
                },

                body: JSON.stringify({

                    therapist_id:
                    selectedTherapist.id,

                    booking_date:
                    selectedDate,

                    booking_time:
                    selectedTime
                })
            }
        )

        .then(res => res.json())

        .then(data => {

            if(data == "slot_taken"){

                alert(
                    "This time slot is already booked"
                );

                return;
            }

            alert(data.message);

            if(data.success){

                confirmation.style.display = "block";

                confirmation.innerHTML = `

✅ Booking Confirmed

<br>

${selectedDate} • ${selectedTime}

`;
            }

        })

        .catch(err => {

            console.log(err);

            alert("Booking failed");
        });

    });

}

});

}

function showRegister() {

    document.getElementById("loginForm")
    .style.display = "none";

    document.getElementById("registerForm")
    .style.display = "block";
}

function showLogin() {

    document.getElementById("registerForm")
    .style.display = "none";

    document.getElementById("loginForm")
    .style.display = "block";
}

// LOGIN & REGISTER

// =======================
// REGISTER
// =======================

function registerUser(){

    let formData = new FormData();
    

    formData.append("action", "register");

    formData.append(
        "role",
        registerRole
    );

    formData.append(
        "name",
        document.getElementById("regName").value
    );

    formData.append(
        "email",
        document.getElementById("regEmail").value
    );

    formData.append(
        "phone",
        document.getElementById("regPhone").value
    );

    formData.append(
        "address",
        document.getElementById("regAddress").value
    );

    formData.append(
        "password",
        document.getElementById("regPassword").value
    );

    // THERAPIST DATA

    if(registerRole === "therapist"){

        formData.append(
            "specialization",
            document.getElementById("specialization").value
        );

        formData.append(
            "experience",
            document.getElementById("experience").value
        );

        formData.append(
            "fee",
            document.getElementById("fee").value
        );

        formData.append(
            "about",
            document.getElementById("about").value
        );

        formData.append(
            "availability",
            document.getElementById("availability").value
        );

        formData.append(
            "certificate",
            document.getElementById("certificate").files[0]
        );

        formData.append(
            "image",
            document.getElementById("image").files[0]
        );
    }

    fetch("php/login.php", {

        method:"POST",

        body:formData
    })

    .then(response => response.text())

    .then(data => {

    data = data.trim();

    console.log(data);

    if(data === "registered"){

    alert("Registration successful");

    if(registerRole === "patient"){

        window.location.href =
        "dashboard.php";

    } else {

        window.location.href =
        "profile.php";
    }

}

else if(data === "email_exists"){

    alert(
    "This email is already registered"
    );
}

else if(data === "weak_password"){

    alert(
    "Password must contain at least 6 characters"
    );
}

else if(data === "invalid_image"){

    alert(
    "Only JPG, JPEG and PNG images are allowed"
    );
}

else{

    alert(
    "Registration failed. Please try again."
    );
}
});
}

// =======================
// LOGIN
// =======================

function loginUser() {

    const email =
    document.getElementById("loginEmail")
    .value.trim();

    const password =
    document.getElementById("loginPassword")
    .value.trim();

    if (email === "" || password === "") {

        alert("Please fill all fields");
        return;
    }

    fetch("php/login.php", {

        method: "POST",

        headers: {
            "Content-Type":
            "application/x-www-form-urlencoded"
        },

        body:
`action=login&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&role=${encodeURIComponent(selectedRole)}`
    })

    .then(res => res.text())

    .then(data => {

        data = data.trim();

        console.log("SERVER RESPONSE:", data);

        // PATIENT LOGIN
        if (data === "success") {

            alert("Patient login successful");

            window.location.href =
            "dashboard.php";
        }

        // THERAPIST LOGIN
        else if (
            data === "therapist_success"
        ) {

            alert("Therapist login successful");

            window.location.href =
            "profile.php";
        }

        else {

            alert("Invalid email or password");
        }
    })

    .catch(err => {

        console.log(err);

        alert("Something went wrong");
    });
}


// PIE CHART
const ctx2 = document.getElementById('patientChart');

if (ctx2 && typeof Chart !== "undefined") {
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Orthopedic', 'Sports', 'Rehab'],
            datasets: [{
                data: [40, 30, 30],
                backgroundColor: ['#4a6cf7', '#7fbe4a', '#ffb547']
            }]
        }
    });
}

// fade-in animation
const cards = document.querySelectorAll(
    '.stat-card, .chart-card, .appointments, .patients'
);

cards.forEach((card, i) => {
    card.style.opacity = 0;
    card.style.transform = "translateY(20px)";

    setTimeout(() => {
        card.style.opacity = 1;
        card.style.transform = "translateY(0)";
    }, i * 120);
});

// sidebar collapse
function toggleSidebar(){

    const sidebar =
    document.getElementById(
    "sidebar"
    );

    const main =
    document.getElementById(
    "mainContent"
    );

    sidebar.classList.toggle(
    "collapsed"
    );

    if(main){

        main.classList.toggle(
        "expanded"
        );
    }
}

let currentEmail = "";

// ======================
// CHECK USER
// ======================

function checkUser() {

    const email =
    document.getElementById("regEmail")
    .value.trim();

    // EMAIL VALIDATION
    const emailPattern =
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(email)) {

        alert("Enter a valid email");
        return;
    }

    currentEmail = email;

    fetch("php/check_user.php", {

        method: "POST",

        headers: {
            "Content-Type":
            "application/x-www-form-urlencoded"
        },

        body: `email=${email}`
    })

    .then(res => res.text())

    .then(data => {

    data = data.trim();

    console.log(data);

    if(data === "exists"){

        alert(
        "This email is already registered"
        );

        return;
    }

    // SEND OTP
    sendOTP(email);

    // GO TO OTP SCREEN
    document.getElementById(
        "registerStep1"
    ).style.display = "none";

    document.getElementById(
        "registerStep2"
    ).style.display = "block";

});
}

function sendOTP(email) {

    fetch("php/send_otp.php", {

        method: "POST",

        headers: {
            "Content-Type":
            "application/x-www-form-urlencoded"
        },

        body: `email=${email}`
    })

    .then(res => res.text())

    .then(data => {

        data = data.trim();

        if (data === "success") {

            alert("OTP sent successfully");

        } else {

            alert("Failed to send OTP");
        }
    });
}

function verifyOTP() {

    const otp =
    document.getElementById("otpInput")
    .value.trim();

    fetch("php/verify_otp.php", {

        method: "POST",

        headers: {
            "Content-Type":
            "application/x-www-form-urlencoded"
        },

        body:
        `email=${currentEmail}&otp=${otp}`
    })

    .then(res => res.text())

    .then(data => {

        data = data.trim();

        if (data === "success") {

            // HIDE OTP STEP
            document.getElementById(
                "registerStep2"
            ).style.display = "none";

            // SHOW PROFILE STEP
            document.getElementById(
                "registerStep3"
            ).style.display = "block";

        } else {

            alert("Invalid OTP");
        }
    });
}


// SLOT LOADER

function loadBookedSlots(){

    if(
        !selectedDate ||
        !selectedTherapist
    ){
        return;
    }

    const [day, month, year] =
    selectedDate.split("/");

    const formattedDate =
    `${year}-${month}-${day}`;

    fetch(

        `php/get-booked-slots.php?date=${formattedDate}&therapist_id=${selectedTherapist.id}`

    )

    .then(res => res.json())

    .then(bookedSlots => {

        const options =
        timePicker.querySelectorAll("option");

        options.forEach(option => {

    option.disabled = false;
    option.style.color = "";

    if(option.value === ""){
        return;
    }

    const now = new Date();

const today =
`${now.getDate()}/${now.getMonth()+1}/${now.getFullYear()}`;

if(selectedDate === today){

    const currentTime =
    now.getHours() * 60 +
    now.getMinutes();

    const [hours, minutes] =
    option.value.split(":");

    const slotTime =
    parseInt(hours) * 60 +
    parseInt(minutes);

    if(slotTime <= currentTime){

        option.disabled = true;
        option.style.color = "gray";
    }
}

            option.disabled = false;

            if(
                bookedSlots.includes(
                    option.value
                )
            ){

                option.disabled = true;
option.style.color = "gray";
            }
        });

    });

}

let sortDirections = {};

function sortTable(columnIndex, type){

    const table =
    document.querySelector(".appointment-table tbody");

    const rows =
    Array.from(table.querySelectorAll("tr"));

    const currentDirection =
    sortDirections[columnIndex] === "asc"
    ? "desc"
    : "asc";

    sortDirections[columnIndex] =
    currentDirection;

    rows.sort((a, b) => {

        let aText =
        a.children[columnIndex]
        .innerText
        .trim();

        let bText =
        b.children[columnIndex]
        .innerText
        .trim();

        if(type === "date"){

            aText = new Date(aText);
            bText = new Date(bText);
        }

        else if(type === "time"){

            aText =
            new Date(
                "1970/01/01 " + aText
            );

            bText =
            new Date(
                "1970/01/01 " + bText
            );
        }

        else{

            aText =
            aText.toLowerCase();

            bText =
            bText.toLowerCase();
        }

        if(aText < bText){

            return currentDirection === "asc"
            ? -1
            : 1;
        }

        if(aText > bText){

            return currentDirection === "asc"
            ? 1
            : -1;
        }

        return 0;
    });

    rows.forEach(row => {
        table.appendChild(row);
    });
}