<?php
//กาํหนดค่า Access-Control-Allow-Origin ให้เครืÉองอืÉน ๆ สามารถเรียกใชง้านหนา้นÊีได้
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");

header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//ตัÊงค่าการเชืÉอมต่อฐานขอ้ มูล
$link = mysqli_connect('localhost', 'thanawat', 'goal452', 'thanawat');
mysqli_set_charset($link, 'utf8');
$requestMethod = $_SERVER["REQUEST_METHOD"];
//ตรวจสอบหากใช้Method GET
if ($requestMethod == 'GET') {
    if(isset($_GET['course_code'])){
        $course_code=$_GET['course_code'];
    }
    //ตรวจสอบการส่งค่า code
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];
        //คาํสัÉง SQL กรณี มีการส่งค่า id มาให้แสดงเฉพาะขอ้ มูลของ code นัÊน
        $sql = "SELECT * FROM exam_results WHERE id = '$id'";
    } else {
        //คาํสัÉง SQL แสดงขอ้ มูลทÊงหมด ั
        $sql = "SELECT * FROM exam_results WHERE course_code='$course_code'";
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
//แปลงขอ้ มูลทÉอ่านได้ ี เป็ น array แลว้เก็บไวท้ีÉตวัแปร result
$result = json_decode($data, true);
//ตรวจสอบการเรียกใชง้านวา่ เป็น Method POST หรือไม่
if ($requestMethod == 'POST') {
    if (!empty($result)) {
        $course_code = $result['course_code'];
        $course_code = $result['course_code'];
        $point = $result['point'];
        //คาํสัÉง SQL สาํ หรบั เพิÉมขอ้ มูลใน Database
        $sql = "INSERT INTO exam_results (course_code, course_code, point) VALUES
('$course_code', '$course_code','$point')";
        try {
            $result = mysqli_query($link, $sql);
            echo json_encode(['status' => 'ok', 'message' => 'Insert Data Complete']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}