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

        $stmt =
$conn->prepare(

"SELECT * FROM patients
WHERE p_email=?"

);

$stmt->bind_param(
"s",
$email
);

$stmt->execute();

$result =
$stmt->get_result();

        if($result->num_rows > 0){

            $user = $result->fetch_assoc();

            if(password_verify(
                $password,
                $user['passwd']
            )){

session_regenerate_id(true);

                $_SESSION['patient_id']
                = $user['patient_id'];

                $_SESSION['patient_name']
                = $user['p_name'];

                $_SESSION['role'] = "patient";

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

        $stmt =
$conn->prepare(

"SELECT * FROM therapists
WHERE t_email=?"

);

$stmt->bind_param(
"s",
$email
);

$stmt->execute();

$result =
$stmt->get_result();

        if($result->num_rows > 0){

            $user = $result->fetch_assoc();

            if(password_verify(
                $password,
                $user['passwd']
            )){

            session_regenerate_id(true);

                $_SESSION['therapist_id']
                = $user['therapist_id'];

                $_SESSION['therapist_name']
                = $user['t_name'];

                $_SESSION['therapist_email'] =
                $user['t_email'];

                $_SESSION['role'] = "therapist";

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

    // PATIENT REGISTER

    if($role == "patient"){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = trim($_POST['password']);

    // CHECK EMAIL
    $checkStmt =
    $conn->prepare(

    "SELECT patient_id
    FROM patients
    WHERE p_email=?"

    );

    $checkStmt->bind_param(
    "s",
    $email
    );

    $checkStmt->execute();

    $checkResult =
    $checkStmt->get_result();

    if($checkResult->num_rows > 0){

        echo "email_exists";
        exit();
    }

    // PASSWORD CHECK
    if(strlen($password) < 6){

        echo "weak_password";
        exit();
    }

    // HASH PASSWORD
    $hashedPassword =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    // INSERT
    $stmt =
    $conn->prepare(

    "INSERT INTO patients

    (
    p_name,
    p_email,
    p_phn,
    p_addr,
    passwd
    )

    VALUES
    (?,?,?,?,?)"

    );

    $stmt->bind_param(

    "sssss",

    $name,
    $email,
    $phone,
    $address,
    $hashedPassword

    );

    if($stmt->execute()){

    session_unset();
session_destroy();

session_start();

    $_SESSION['patient_id'] =
    $stmt->insert_id;

    $_SESSION['patient_name'] =
    $name;

    echo "registered";
} else {

        echo "error";
    }
}

    // THERAPIST REGISTER

    if($role == "therapist"){

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);

$checkStmt =
$conn->prepare(

"SELECT therapist_id
FROM therapists
WHERE t_email=?"

);

$checkStmt->bind_param(
"s",
$email
);

$checkStmt->execute();

$checkResult =
$checkStmt->get_result();

if($checkResult->num_rows > 0){

    echo "email_exists";
    exit();
}

        $phone = trim($_POST['phone']);
        $password = trim($_POST['password']);

        if(strlen($password) < 6){

    echo "weak_password";
    exit();
}

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

        $allowedTypes = [

'image/jpeg',
'image/png',
'image/jpg'

];

        if(isset($_FILES['image'])
        && $_FILES['image']['error'] == 0){

        if(

!in_array(
$_FILES['image']['type'],
$allowedTypes

)

){

    echo "invalid_image";
    exit();
}

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

        $stmt =
$conn->prepare(

"INSERT INTO therapists

(
t_name,
t_email,
t_phn,
specialization,
experience,
availability,
certificate,
passwd,
about,
fee,
image
)

VALUES
(?,?,?,?,?,?,?,?,?,?,?)"

);

$stmt->bind_param(

"sssssssssss",

$name,
$email,
$phone,
$specialization,
$experience,
$availability,
$certificateData,
$hashedPassword,
$about,
$fee,
$imageName

);

if($stmt->execute()){

session_unset();
session_destroy();

session_start();

    $_SESSION['therapist_id'] =
    $stmt->insert_id;

    $_SESSION['therapist_name'] =
    $name;

    $_SESSION['therapist_email'] =
    $email;

    echo "registered";
}else {

    echo "error";
}

    }
}
?>