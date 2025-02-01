<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
     .background{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(87, 154, 213, 0.4); 
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999; 
}


        /* تنسيق الفورم */
        form {
            width: 400px; 
            padding: 20px;
            background-color: #f8f9fa; /* لون خلفية فاتح */
            border: 2px solid #6c757d; /* حدود رمادية */
            border-radius: 10px; 
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5); /* ظل */
            z-index: 1000;
        }

        fieldset {
            border: none; 
            padding: 0;
        }

        legend {
            font-size: 1.5em;
            font-weight: bold;
            color: #333; /* لون النص */
            margin-bottom: 15px;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%; 
            padding: 10px;
            margin-bottom: 15px; 
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 38%;
            padding: 10px;
            margin-right: 10px;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }

        .button_submit {
            background-color: #28a745; /* لون أخضر للزر */
        }

        .button_reset {
            background-color: #dc3545; /* لون أحمر للزر */
        }

        button:hover {
            opacity: 0.9; 
        }
    </style>
</head>
<body>
    <div class="background">
        <form method="post" action="#" name="Drug">
            <fieldset class="container">
                <legend>Add Drug</legend>
                <label for="name">Name :</label>
                <input type="text" id="name" name="name" placeholder="Enter Drug Name" required>

                <label for="dosage">Dosage :</label>
                <input type="text" id="dosage" name="dosage" placeholder="Enter Dosage" required>

                <label for="productionDate">Production Date:</label>
                <input type="date" id="productionDate" name="productionDate" required>

                <label for="expiryDate">Expiry Date:</label>
                <input type="date" id="expiryDate" name="expiryDate" required>
    
                <div style="display: flex; justify-content: center;">
                    <button type="submit" name="submit" class="button_submit">Add</button>
                    <button type="reset" name="reset" class="button_reset"  onclick="window.history.back()">Cancel</button>
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>
<?php
//التحقق مين الي سجل دخول 
session_start();
if (!isset($_SESSION['pharmacist_id']) || empty($_SESSION['pharmacist_id'])) {
    echo "Pharmacist ID is missing...";
    exite();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {
        // استخراج البيانات من النموذج
        $name = $_POST['name'];
        $dosage = $_POST['dosage'];
        $productionDate = $_POST['productionDate'];
        $expiryDate = $_POST['expiryDate'];
        $pharmacist_id =$_SESSION['pharmacist_id'];

        // الاتصال بقاعدة البيانات
        $connection = mysqli_connect("localhost", "root", "", "hospital");

        // تأكد من الاتصال بقاعدة البيانات
        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // استعلام الإدخال
        $query = "INSERT INTO drugs (name, dosage, productionDate, expiryDate,pharmacist_id) 
                  VALUES ('$name',$dosage, '$productionDate', '$expiryDate',$pharmacist_id)";

        // تنفيذ الاستعلام
        $result = mysqli_query($connection, $query);
        if ($result) {
            header("Location: ../PHARMACIST/show_drugs.php");
        } else {
            echo  mysqli_error($connection);
        }

        // إغلاق الاتصال
        mysqli_close($connection);
    }
}

?>
