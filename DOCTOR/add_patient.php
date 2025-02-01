<?php
session_start();
// check if doctor has been logged in
if (!isset($_SESSION['doctor_id']) || empty($_SESSION['doctor_id'])) {
    echo "Access Denied:Doctor ID is missing ,Only doctors can add patient";
    exit();
}
// Check if the cookies for email and password are set ,if set store the email and password values in variables
if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    $email = $_COOKIE['email'];
    $password = $_COOKIE['password'];

} else {
//cookie not found
echo "<script>alert('No user found. Try to log in again');</script>";
  header("Location: ../Auth/login.php");
}


//conect to database from database file
include("../DATABASE/connection.php");

$connection = mysqli_connect("localhost", "root", "", "hospital");

// تأكد من الاتصال بقاعدة البيانات
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['add'])) { //if form click add 
    // get data from the form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $problem = $_POST['problem'];
    $entranceDate = $_POST['entranceDate'];
    $phoneNumber = $_POST['phoneNumber'];

$doctor_id = $_SESSION['doctor_id']; //get doc id from session

// check if doctor login is found in data base doctors by select all doctors id ,if dic id match id store in session
//(doctor was logged in) ,log in the doctor to patients table then this doctor can add patient
$checkDoctorQuery = "SELECT id FROM doctors WHERE id = '$doctor_id'";
$doctorResult = mysqli_query($connection, $checkDoctorQuery);

if (mysqli_num_rows($doctorResult) > 0) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    // إدخال البيانات في جدول aggregate
    $insert_aggregate = "INSERT INTO aggregate (id, email, password, type) 
                         VALUES ('$id', '$email', '$hashed_password', 'patient')";
    if (!mysqli_query($connection, $insert_aggregate)) {
        echo "<script> alert('Error in aggregate query: " . mysqli_error($connection) . "');</script>";
        mysqli_close($connection);
        exit();
    }
    //inserting new patient with values from the add form
    $query = "INSERT INTO patients (id, name, email, password, age, gender, problem, entranceDate, phoneNumber) 
              VALUES ('$id', '$name', '$email', '$password', '$age', '$gender', '$problem', '$entranceDate', '$phoneNumber')";
    
    if (mysqli_query($connection, $query)) {
        // add this patientId and docId to patient_Doctor table to make many to many relation
        $query2 = "INSERT INTO patient_Doctor (pat_id, doc_id) VALUES ('$id', '$doctor_id')";

        if (mysqli_query($connection, $query2)) { //display alert if added successfully
            echo "<script>alert('Patient added successfully!');</script>";
            echo "<script>window.location.href = 'patients_management.php';</script>";
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    } else {
        echo "Error: " . mysqli_error($connection);
    }
} else {
    echo "<script>alert('Doctor not found in the database!');</script>";
}
}
?>

<!DOCTYPE html>
 <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients Management</title>
    <link rel="stylesheet" href="assets/css/patient_style.css">
</head>
<body>
<div class="container">
    <h1>Add Patient</h1>
    <form method="post" action="" name="add_patient">
        <div class="form-group">
            <div>
                <label for="id">ID:</label>
                <input type="text" id="id" name="id" required>
            </div>

            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
            </div>

            <div>
                <label for="gender">Gender:</label>
                <input type="text" id="gender" name="gender" required>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="problem">Problem:</label>
                <textarea id="problem" name="problem" required></textarea>
            </div>

            <div>
                <label for="entranceDate">Entrance Date:</label>
                <input type="date" id="entranceDate" name="entranceDate" required>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="phoneNumber">Phone Number:</label>
                <input type="text" id="phoneNumber" name="phoneNumber" required>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" name="add" id="add">Add Patient</button>
        </div>
    </form>
    <a href="patients_management.php">patients management</a>
</div>
</body>
</html> 
