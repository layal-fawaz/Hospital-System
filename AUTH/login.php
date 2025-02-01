<?php
//يتصل بالداتا بيز
include('../DATABASE/connection.php');
session_start();
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (isset($_POST['log_in'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $remember_me = (isset($_POST['remember_me'])) ?true:false;

        $connection = mysqli_connect("localhost", "root", "", "hospital");

        // تأكد من الاتصال بقاعدة البيانات
        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // استعلام للتحقق من البريد الإلكتروني
        $checkQuery = "SELECT * FROM aggregate WHERE email='$email'";
        $result = mysqli_query($connection, $checkQuery);

        // تحقق من وجود السجلات
        if ($row = mysqli_fetch_assoc($result)) {
            // تحقق من كلمة المرور
            if (password_verify($password, $row['password'])) {
                // إذا كانت كلمة المرور صحيحة، تخزين البيانات في الجلسة
                $_SESSION['email'] = $row['email'];
                $_SESSION['type'] = $row['type'];
                
                //if user assign remember me ,set cookie for one year to store email
                if ($remember_me) {
                    setcookie('email', $email, time() + (86400 * 365), "/");
                    setcookie('password',   $password , time() + (86400 * 365), "/");

                }
                // حسب النوع بخزن الداتا في التيبل التابع اله و بوجهه للصفحة
                if (strcasecmp($row['type'], "doctor") == 0) {
                    $_SESSION['doctor_id'] = $row['id'];
                    header("Location: ../DOCTOR/index.html");
                    exit(); //م يكمل تنفيذ الكود بالصفحة
                } elseif (strcasecmp($row['type'], "pharmacist") == 0) {
                    $_SESSION['pharmacist_id'] = $row['id'];
                     header("Location: ../PHARMACIST/index.html");
                    exit(); 
                }elseif (strcasecmp($row['type'], "patient") == 0){
                    $_SESSION['patient_id'] = $row['id'];
                    header("Location: ../PATIONTS/patient_home/index.html");
                    exit();
                }
            } else {
                // كلمة المرور غير صحيحة
                echo "<script>
                        alert('Invalid email or password');
                        window.history.back();
                      </script>";
            }
        } else {
            // إذا لم يتم العثور على السجل بالبريد الإلكتروني
            echo "<script>
                    alert('Invalid email or password');
                    window.history.back();
                  </script>";
        }

        // إغلاق الاتصال بقاعدة البيانات
        mysqli_close($connection);
    }
}
?>

