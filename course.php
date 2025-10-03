<?php
//กาํหนดค่า Access-Control-Allow-Origin ให้เครืÉองอืÉน ๆ สามารถเรียกใชง้านหนา้นÊีได้
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");

header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//ตัÊงค่าการเชืÉอมต่อฐานขอ้ มูล
$link = mysqli_connect('localhost', 'root', '', 'phatcharaporn');
mysqli_set_charset($link, 'utf8');
$requestMethod = $_SERVER["REQUEST_METHOD"];
//ตรวจสอบหากใช้Method GET
if($requestMethod == 'GET'){
//ตรวจสอบการส่งค่า code
if(isset($_GET['course_code']) && !empty($_GET['course_code'])){
$course_code = $_GET['course_code'];
//คาํสัÉง SQL กรณี มีการส่งค่า id มาให้แสดงเฉพาะขอ้ มูลของ code นัÊน
$sql = "SELECT * FROM course WHERE course_code = '$course_code'";

}else{
//คาํสัÉง SQL แสดงขอ้ มูลทÊงหมด ั
$sql = "SELECT * FROM course";
}
$result = mysqli_query($link, $sql);
//สร้างตวัแปร array สาํ หรบั เก็บขอ้ มูลทีÉได้
$arr = array();
while ($row = mysqli_fetch_assoc($result)) {
$arr[] = $row;
}
echo json_encode($arr);
}

$data = file_get_contents('php://input');
// POST course
if ($requestMethod == 'POST') {
    $result = json_decode($data, true);
    if (!empty($result)) {
        $course_code = $result['course_code'];
        $course_name = $result['course_name'];
        $credit = $result['credit'];
        //คาํสัÉง SQL สาํ หรบั เพิÉมขอ้ มูลใน Database
        $sql = "INSERT INTO course (course_code, course_name, credit) VALUES ('$course_code', '$course_name','$credit')";
        try {
            $result = mysqli_query($link, $sql);
            echo json_encode(['status' => 'ok', 'message' => 'Insert Data Complete']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}

//PUT course
if ($requestMethod == 'PUT') {
    $result = json_decode($data, true);
    if (!empty($result)) {
        $edit_course_code = $result['edit_course_code'];
        $course_code = $result['course_code'];
        $course_name = $result['course_name'];
        $credit = $result['credit'];

        $sql = "UPDATE course SET course_code='$course_code', course_name='$course_name',credit='$credit' WHERE course_code = '$edit_course_code'";
        try {
            $result = mysqli_query($link, $sql);
            echo json_encode(['status' => 'ok', 'message' => 'Update Data Complete']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}

//ตรวจสอบการเรียกใชง้านวา่ เป็น Method DELETE หรือไม่
if ($requestMethod == 'DELETE') {
    //ตรวจสอบวา่ มีการส่งค่า course_code มาหรือไม่
    if (isset($_GET['course_code']) && !empty($_GET['course_code'])) {
        $course_code = $_GET['course_code'];
        //คาํสัÉง SQL สาํ หรบัลบขอ้ มูลใน Database ตามค่า id ทีÉส่งมา
        $sql = "DELETE FROM course WHERE course_code = '$course_code'";
        try {
            $result = mysqli_query($link, $sql);
            echo json_encode(['status' => 'ok', 'message' => 'Delete Data Complete']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}