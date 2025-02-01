<?php
$connection = mysqli_connect("localhost", "root", "");
if (!$connection) {
    die("Error : " . mysqli_connect_error());
}

// التحقق إذا كانت قاعدة البيانات موجودة أم لا، وإذا لم تكن موجودة، يتم إنشاؤها
$query = "CREATE DATABASE IF NOT EXISTS Hospital;";

// تنفيذ الاستعلام لإنشاء قاعدة البيانات
$result = mysqli_query($connection, $query);
if (!$result) {
    echo "Error : " . mysqli_error($connection);
}

//بربطه بقاعدة البيانات الي شغالين عليها hospital
mysqli_select_db($connection, "Hospital");

// الاستعلامات لإنشاء الجداول
$query = "
    CREATE TABLE IF NOT EXISTS patients(
        id int, 
        name varchar(255), 
        email varchar(255), 
        password varchar(255), 
        age int, 
        gender varchar(10), 
        problem text, 
        entranceDate date, 
        phoneNumber varchar(15), 
        PRIMARY KEY (id,email)
    );
";

$query.= "
    CREATE TABLE IF NOT EXISTS doctors(
        id int, 
        name varchar(255), 
        email varchar(255), 
        password varchar(255), 
        phone_number varchar(15), 
        PRIMARY KEY (id,email)
    );
";

$query.= "
    CREATE TABLE IF NOT EXISTS patient_Doctor(
        pat_id int, 
        doc_id int, 
        PRIMARY KEY (pat_id, doc_id),
        FOREIGN KEY (pat_id) REFERENCES patients(id) ON DELETE CASCADE,
        FOREIGN KEY (doc_id) REFERENCES doctors(id) ON DELETE CASCADE
    );
";

$query.= "
    CREATE TABLE IF NOT EXISTS pharmacists(
        id int, 
        name varchar(255), 
        email varchar(255), 
        password varchar(255), 
        phone_number varchar(15), 
        PRIMARY KEY (id,email)
    );
";

$query.= "
    CREATE TABLE IF NOT EXISTS drugs(
        id int AUTO_INCREMENT PRIMARY KEY, 
        name varchar(255), 
        dosage double, 
        productionDate date, 
        expiryDate date, 
        pharmacist_id INT, 
        FOREIGN KEY (pharmacist_id) REFERENCES pharmacists(id) ON DELETE SET NULL
    );
";

$query .= "CREATE TABLE IF NOT EXISTS patient_Drug(
    pat_id int, 
    drug_id int, 
    PRIMARY KEY (pat_id, drug_id),
    FOREIGN KEY (pat_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (drug_id) REFERENCES drugs(id) ON DELETE CASCADE
);";


$query.= "
    CREATE TABLE IF NOT EXISTS aggregate(
        id int PRIMARY KEY, 
        email varchar(255) UNIQUE, 
        password varchar(255), 
        type varchar(50)
    );
";

//بينفذ اكتر من استعلام مع بعض
$tables = mysqli_multi_query($connection, $query);
//افحص اذا تم الانشاء بدون مشاكل او لاء
if (!$tables) {
    echo "Error : " . mysqli_error($connection);
}

// // إغلاق الاتصال بقاعدة البيانات
// mysqli_close($connection);
?>
