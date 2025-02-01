<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    echo "Access Denied: Only patient can update personal information";
    exit();
}

include("../DATABASE/connection.php");
$connection = mysqli_connect("localhost", "root", "", "hospital");

// تأكد من الاتصال
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$patient_id = $_SESSION['patient_id'];

$patient_query = "SELECT d.*
                  FROM drugs d
                  JOIN patient_drug pd ON d.id = pd.drug_id
                  JOIN patients p ON pd.pat_id = p.id
                  WHERE p.id = '$patient_id'";

$result = mysqli_query($connection, $patient_query);

if (!$result) {
    echo "Error in drug query: " . mysqli_error($connection);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../PHARMACIST/pharmacist-dashboard/css/style.css">
    <link rel="stylesheet" href="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
      <div class="d-flex">
        <!-- تضمين Sidebar -->
        <?php include('sideBar.html'); ?>
    <!-- جدول عرض الأدوية -->
     <div class="container">
    <div class="card border-0">
        <div class="card-header">
            <h3 class="card-title">
                your Drugs
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Dosage</th>
                        <th scope="col">Production Date</th>
                        <th scope="col">Expiry Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  

                    // عرض الأدوية في الجدول
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>
                            <th scope="row">' . $row['id'] . '</th>
                            <td>' . $row['name'] . '</td>
                            <td>' . $row['dosage'] . '</td>
                            <td>' . $row['productionDate'] . '</td>
                            <td>' . $row['expiryDate'] . '</td>
                          </tr>';
                    
                        }
                    } else {
                        echo "<tr>
                                <td colspan='5' class='text-center'>No drugs.</td>
                              </tr>";
                    }

                    // إغلاق الاتصال
                    mysqli_close($connection);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../PHARMACIST/pharmacist-dashboard/js/script.js"></script>
</body>
</html>