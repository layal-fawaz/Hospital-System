<?php
include('../DATABASE/connection.php');

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    extract($_POST);
    // تحقق من وجود الحقل sign_up
    if (isset($sign_up)) {
        // الاتصال بقاعدة البيانات
        $connection = mysqli_connect("localhost", "root", "", "hospital");

        if (!$connection) {
            echo "<script>alert('Connection failed: " . mysqli_connect_error() . "');</script>";
            exit();
        }
        mysqli_select_db($connection, "Hospital");

        
        // تشفير كلمة المرور
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // إدخال البيانات في جدول aggregate
        $insert_aggregate = "INSERT INTO aggregate (id, email, password, type) 
                             VALUES ('$id', '$email', '$hashed_password', '$type')";
        if (!mysqli_query($connection, $insert_aggregate)) {
            echo "<script> alert('Error in aggregate query: " . mysqli_error($connection) . "');</script>";
            mysqli_close($connection);
            exit();
        }

        // التحقق من النوع وإدخال البيانات في الجدول المناسب
        if (strcmp($type, "doctor") == 0) {
            $insert_doctor = "INSERT INTO doctors (id, name, email, password, phone_number) 
                              VALUES ('$id', '$name', '$email', '$hashed_password', '$phone_number')";
            if (!mysqli_query($connection, $insert_doctor)) {
                echo "<script>alert('Error in doctors query: " . mysqli_error($connection) . "');</script>";
                mysqli_close($connection);
                exit();
            }
        } elseif (strcmp($type, "pharmacist") == 0) {
            $insert_pharmacist = "INSERT INTO pharmacists (id, name, email, password, phone_number) 
                                  VALUES ('$id', '$name', '$email', '$hashed_password', '$phone_number')";
            if (!mysqli_query($connection, $insert_pharmacist)) {
                echo "<script>alert('Error in pharmacists query: " . mysqli_error($connection) . "');</script>";
                mysqli_close($connection);
                exit();
            }
        } else {
            echo "<script>alert('Error: Invalid type.');</script>";
            mysqli_close($connection);
            exit();
        }

        // إغلاق الاتصال بقاعدة البيانات
        mysqli_close($connection);

        // رسالة النجاح
        echo "<script>alert('You Register successfully!');</script>";
        header("Location:home/index.html");

    } else {
        echo "<script>
        alert('Error: Invalid request.');
        window.history.back();
        </script>";
    }
}



?>

