<?php
session_start();
// التحقق إذا كان المستخدم مسجل الدخول
if (isset($_SESSION['pharmacist_id']) || isset($_SESSION['doctor_id']) || isset($_SESSION['patient_id']) ) {
// إزالة جميع متغيرات الجلسة
session_unset();
// تدمير الجلسة
session_destroy();
//لانو انا مش عاملة اسم للكوكي هذا 
//PHPSESSID فحستخدم هذا الاسم 
// حذف الكوكي PHPSESSID
if (isset($_COOKIE['PHPSESSID'])) {
    setcookie('PHPSESSID', '', time() - 3600, '/'); //احدد وقت قبل الحالي و بنفس المسار الي انا فيه عشان يحذف 
}
// يوجهني إلى الصفحة الرئيسية
header("Location: ../index.html");

}
?>