<?php
include('../DATABASE/connection.php');

// تحقق من تسجيل الدخول
session_start();
if (!isset($_SESSION['patient_id']) || empty($_SESSION['patient_id'])) {
    echo "Patient ID is missing...";
    exit();
} else {
    $patient_id = $_SESSION['patient_id'];
}

$connection = mysqli_connect("localhost", "root", "", "hospital");

// تأكد من الاتصال
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) { 
        // استلام البيانات من النموذج
        $id = $_POST['id'];  
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $problem = $_POST['problem'];
        $entranceDate = $_POST['entranceDate'];
        $phoneNumber = $_POST['phoneNumber'];

        // تحديث بيانات المريض في قاعدة البيانات
        $query = "UPDATE patients SET 
        name = '$name',
        email = '$email', 
        password = '$password', 
        age = '$age', 
        gender = '$gender', 
        problem = '$problem', 
        entranceDate = '$entranceDate', 
        phoneNumber = '$phoneNumber' 
        WHERE id = '$patient_id'";

        $result = mysqli_query($connection, $query);
        if ($result) {
            header("Location: patient_home/index.html");
        } else {
            echo "Error: " . mysqli_error($connection);
        }
        
    } 
}else {
    // جلب بيانات المريض
    $query2 = "SELECT * FROM patients WHERE id = '$patient_id'";
   
    $result2 = mysqli_query($connection, $query2);
  
  
    if ($result2 && mysqli_num_rows($result2) > 0) {
        $patient = mysqli_fetch_assoc($result2);
    } else {
        echo "No data found for this patient.";
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient</title>
    <link rel="stylesheet" href="../DOCTOR/assets/css/patient_style.css">

</head>
<body>
    <div class="container">
        <h1>Update Patient</h1>
        <form method="post" action="" name="update_patient">
            <div class="form-group">
                <div>
                    
                    <label for="id">Patient ID:</label>
                    <input type="text" id="id" name="id" value="<?php echo isset($patient['id']) ? $patient['id'] : ''; ?>" readonly required>
                </div>

                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo isset($patient['name']) ? $patient['name'] : ''; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($patient['email']) ? $patient['email'] : ''; ?>" required>
                </div>

                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" value="<?php echo isset($patient['password']) ? $patient['password'] : ''; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" value="<?php echo isset($patient['age']) ? $patient['age'] : ''; ?>" required>
                </div>

                <div>
                    <label for="gender">Gender:</label>
                    <input type="text" id="gender" name="gender" value="<?php echo isset($patient['gender']) ? $patient['gender'] : ''; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label for="problem">Problem:</label>
                    <textarea id="problem" name="problem" required><?php echo isset($patient['problem']) ? $patient['problem'] : ''; ?></textarea>
                </div>

                <div>
                    <label for="entranceDate">Entrance Date:</label>
                    <input type="date" id="entranceDate" name="entranceDate" value="<?php echo isset($patient['entranceDate']) ? $patient['entranceDate'] : ''; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label for="phoneNumber">Phone Number:</label>
                    <input type="text" id="phoneNumber" name="phoneNumber" value="<?php echo isset($patient['phoneNumber']) ? $patient['phoneNumber'] : ''; ?>" required>
                <?php // إغلاق الاتصال
                     echo mysqli_close($connection);?>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" name="update">Update </button>
            </div>
        </form>
        <a href="patient_home/index.html">Back to Patient Dashboard</a>
    </div>
</body>
</html>
