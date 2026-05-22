<?php

session_start();

if(!isset($_SESSION['therapist_name'])){

    header("Location: login.html");

    exit();
}

include "php/db.php";

if(!isset($_GET['id'])){

    echo "Patient not found";

    exit();
}

$patientId =
$_GET['id'];

$query = "

SELECT *

FROM patients

WHERE patient_id = '$patientId'

";

$result =
$conn->query($query);

if($result->num_rows == 0){

    echo "Patient not found";

    exit();
}

$patient =
$result->fetch_assoc();

$appointmentsQuery = "

SELECT *

FROM booking

WHERE patient_id = '$patientId'

ORDER BY date DESC,
time DESC

";

$appointmentsResult =
$conn->query($appointmentsQuery);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Patient Profile
</title>

<link
rel="stylesheet"
href="css/profile.css">

</head>

<body>

<div class="patient-profile">

    <div class="left-column">

        <div class="profile-card">

            <img
            src="images/reviewer3.png"
            width="120">

            <h2>

                <?php
                echo $patient['p_name'];
                ?>

            </h2>

            <p>

                Patient ID:
                <?php
                echo $patient['patient_id'];
                ?>

            </p>

        </div>

        <div class="details-card">

            <h3>
                Patient Details
            </h3>

            <p>

                <b>Email:</b>

                <?php
                echo $patient['p_email'];
                ?>

            </p>

            <p>

                <b>Phone:</b>

                <?php
                echo $patient['p_phn'];
                ?>

            </p>

        </div>

    </div>

    <div class="details-card appointment-history">

        <h3>
            Appointment History
        </h3>

        <table class="appointment-table">

            <thead>

                <tr>

                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Notes</th>
                </tr>

            </thead>

            <tbody>

            <?php

            if(
            $appointmentsResult->num_rows > 0
            ){

            while(
            $appointment =
            $appointmentsResult->fetch_assoc()
            ){

            ?>

                <tr>

                    <td>

                        <?php
                        echo $appointment['date'];
                        ?>

                    </td>

                    <td>

                        <?php
                        echo date(
                        "h:i A",
                        strtotime(
                        $appointment['time']
                        )
                        );
                        ?>

                    </td>

                    <td>

<span

class="status-badge

<?php

echo strtolower(
$appointment['status']
);

?>

"

>

<?php
echo $appointment['status'];
?>

</span>

</td>

<td>

<span

class="payment-badge

<?php

echo strtolower(
$appointment['payment_status']
);

?>

"

>

<?php
echo $appointment['payment_status'];
?>

</span>

</td>

<td>

<?php

if(!empty($appointment['notes'])){

?>

<button

class="view-note-btn"

onclick="toggleNote(this)"

>

View Notes

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="note-box hidden-note">

<?php

echo nl2br(
$appointment['notes']
);

?>

</div>

<?php
}
else{

echo "-";

}

?>

</td>

                </tr>

            <?php
            }
            }
            else{

                echo "

                <tr>

                    <td colspan='4'>

                        No appointments found

                    </td>

                </tr>

                ";

            }
            ?>

            </tbody>

        </table>

    </div>

</div>

<script>

function toggleNote(button){

    const noteBox =
    button.nextElementSibling;

    if(
        noteBox.style.display
        === "block"
    ){

        noteBox.style.display =
        "none";

        button.innerHTML =
        `
        View Notes
        <i class="fa-solid fa-chevron-down"></i>
        `;
    }
    else{

        noteBox.style.display =
        "block";

        button.innerHTML =
        `
        Hide Notes
        <i class="fa-solid fa-chevron-up"></i>
        `;
    }
}

</script>

</body>

</html>