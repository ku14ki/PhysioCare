<?php

session_start();

include 'db.php';

$action = $_POST['action'];

// ======================
// LOGIN
// ======================

if($action == "login"){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // PATIENT LOGIN

    if($role == "patient"){

        $sql = "SELECT * FROM patients
                WHERE p_email='$email'";

        $result = $conn->query($sql);

        if($result->num_rows > 0){

            $user = $result->fetch_assoc();

            if(password_verify(
                $password,
                $user['passwd']
            )){

                $_SESSION['patient_id']
                = $user['patient_id'];

                $_SESSION['patient_name']
                = $user['p_name'];

                echo "success";

            } else {

                echo "invalid";
            }

        } else {

            echo "invalid";
        }
    }

    // THERAPIST LOGIN

    else if($role == "therapist"){

        $sql = "SELECT * FROM therapists
                WHERE t_email='$email'";

        $result = $conn->query($sql);

        if($result->num_rows > 0){

            $user = $result->fetch_assoc();

            if(password_verify(
                $password,
                $user['passwd']
            )){

                $_SESSION['therapist_id']
                = $user['therapist_id'];

                $_SESSION['therapist_name']
                = $user['t_name'];

                $_SESSION['therapist_email'] =
                $user['t_email'];

                echo "therapist_success";

            } else {

                echo "invalid";
            }

        } else {

            echo "invalid";
        }
    }
}

// ======================
// REGISTER
// ======================

else if($action == "register"){

    $role = trim($_POST['role']);

    // THERAPIST REGISTER

    if($role == "therapist"){

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $password = trim($_POST['password']);

        $hashedPassword =
        password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $specialization =
        trim($_POST['specialization']);

        $experience =
        trim($_POST['experience']);

        $fee =
        trim($_POST['fee']);

        $about =
        trim($_POST['about']);

        $availability =
        trim($_POST['availability']);

        $imageName = "";

        if(isset($_FILES['image'])
        && $_FILES['image']['error'] == 0){

            $imageName =
            time() . "_" .
            $_FILES['image']['name'];

            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                "uploads/" . $imageName
            );
        }

        $certificateData = null;

        if(isset($_FILES['certificate'])
        && $_FILES['certificate']['error'] == 0){

            $certificateData =
            addslashes(
                file_get_contents(
                    $_FILES['certificate']['tmp_name']
                )
            );
        }

        $sql = "INSERT INTO therapists

        (t_name,
        t_email,
        t_phn,
        specialization,
        experience,
        availability,
        certificate,
        passwd,
        about,
        fee,
        image)

        VALUES

        ('$name',
        '$email',
        '$phone',
        '$specialization',
        '$experience',
        '$availability',
        '$certificateData',
        '$hashedPassword',
        '$about',
        '$fee',
        '$imageName')";

        if($conn->query($sql)){

            echo "registered";

        } else {

            echo "error";
        }
    }
}
?>