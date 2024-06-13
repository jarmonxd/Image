<?php
$targetDir = "/"; 
$allowTypes = array('jpg', 'jpeg', 'png', 'gif','webp'); 

if(isset($_FILES['image']['name'])){
    $imageName = $_FILES['image']['name'];
    $imageTempName = $_FILES['image']['tmp_name'];
    $imageSize = $_FILES['image']['size'];  // รับขนาดไฟล์

    // ตรวจสอบขนาดไฟล์ (5 MB = 5 * 1024 * 1024 bytes)
    $maxFileSize = 5 * 1024 * 1024; 
    if ($imageSize > $maxFileSize) {
        echo json_encode(array('success' => 0, 'error' => 'ขนาดไฟล์เกิน 5 MB อัพโหลดไม่ได้'));
        exit; // หยุดการทำงานถ้าไฟล์ใหญ่เกิน
    }

    // ตรวจสอบประเภทไฟล์
    $imageType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    if(in_array($imageType, $allowTypes)){
        // สร้างชื่อไฟล์ใหม่เพื่อป้องกันการทับซ้อน
        $newImageName = uniqid() . '.' . $imageType; 
        $targetFilePath = $targetDir . $newImageName;

        if(move_uploaded_file($imageTempName, $targetFilePath)){
            $imageURL = "/" . $newImageName; 
            echo json_encode(array('success' => 1, 'url' => $imageURL));
        } else {
            echo json_encode(array('success' => 0, 'error' => 'อัปโหลดไม่สำเร็จ'));
        }
    } else {
        echo json_encode(array('success' => 0, 'error' => 'อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น'));
    }
}

try {
    if(isset($_FILES['image']['name'])){
        // ... (ส่วนการอัปโหลดเหมือนเดิม)
    } else {
        throw new Exception("No image uploaded");
    }
} catch (Exception $e) {
    echo json_encode(array('success' => 0, 'error' => $e->getMessage()));
}
?>
