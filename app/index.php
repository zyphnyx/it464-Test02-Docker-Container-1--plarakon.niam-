<?php
// 1. กำหนดค่าการเชื่อมต่อฐานข้อมูลโดยดึงชื่อจาก .env [5]
$host = 'db-node'; // แก้ไขให้ตรงกับค่าทีอยู่ใน Docker Compose หรือ .env
$user = 'Plarakon-Admin'; // แก้ไขให้ตรงกับค่าที่อยู่ใน Docker Compose หรือ .env
$db   = 'Plarakon-DB'; // แก้ไขให้ตรงกับค่าที่อยู่ใน Docker Compose หรือ .env

// 2. กฎเหล็กด้านความปลอดภัย: อ่านรหัสผ่านจาก Docker Secret [4, 7]
$secret_path = '/run/secrets/db_root_pass';
$pass = trim(file_get_contents($secret_path));

// 3. เริ่มการเชื่อมต่อด้วย mysqli [8]
$conn = new mysqli($host, $user, $pass, $db);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("<h2 style='color:red;'>❌ Connection Failed: " . $conn->connect_error . "</h2>");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Project Tracker</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .status-done { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h1>📋 ระบบติดตามสถานะโครงงานนักศึกษา</h1>
    <p>Connected to <strong>MariaDB</strong> successfully!</p>

    <table>
        <tr>
            <th>ID</th>
            <th>Student ID</th>
            <th>Name</th>
            <th>Project Title</th>
            <th>Status</th>
        </tr>
        <?php
        // 4. ดึงข้อมูลจากตาราง students มาแสดงผล [2]
        $sql = "SELECT * FROM students";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["student_id"] . "</td>
                        <td>" . $row["full_name"] . "</td>
                        <td>" . $row["project_name"] . "</td>
                        <td class='status-done'>" . $row["status"] . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>ไม่พบข้อมูลในระบบ</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>