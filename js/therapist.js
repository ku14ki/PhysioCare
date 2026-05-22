console.log("THERAPIST PAGE LOADED");

// ========================
// GET THERAPIST ID
// ========================

const params = new URLSearchParams(window.location.search);
const id =
new URLSearchParams(window.location.search)
.get("id");

// ========================
// FETCH THERAPIST
// ========================

fetch(`php/get_therapist.php?id=${id}`)
.then(res => res.json())
.then(data => {

    console.log(data);

    document.getElementById("profileName").innerText =
    data.name;

    document.getElementById("profileRole").innerText =
    data.specialization;

    document.getElementById("profileImg").src =
    "php/uploads/" + data.image;

    document.getElementById("profileTags").innerHTML =
    `<span>${data.specialization}</span>`;

    document.getElementById("profileAbout").innerText =
data.about;

document.getElementById("profileExperience").innerText =
`Experience: ${data.experience} Years`;

    const bookBtn =
document.getElementById("bookBtn");

bookBtn.setAttribute(
"href",
`dashboard.php?id=${id}`
);

console.log(
document.getElementById("bookBtn").href
);

})
.catch(err => console.log(err));

// ========================
// BOOKING MODAL
// ========================

function openBookingModal() {

    document.getElementById("bookingModal")
    .style.display = "flex";
}

function closeBookingModal() {

    document.getElementById("bookingModal")
    .style.display = "none";
}

document.getElementById("experienceList").innerHTML = `

<li>
${data.experience}+ Years Clinical Experience
</li>

<li>
${data.specialization}
</li>

<li>
Personalized Recovery Programs
</li>

<li>
Posture & Mobility Improvement
</li>

`;

document.getElementById("reviewsContainer").innerHTML = `

<div class="review-card">

    <h4>
        ⭐ 4.8
    </h4>

    <p>
        Very professional and helpful therapist.
    </p>

</div>

<div class="review-card">

    <h4>
        ⭐ 5.0
    </h4>

    <p>
        Excellent recovery guidance and friendly behaviour.
    </p>

</div>

`;