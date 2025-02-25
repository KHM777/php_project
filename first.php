<?php
$host = "localhost"; // السيرفر المحلي
$user = "root"; // اسم المستخدم الافتراضي في XAMPP
$pass = ""; // كلمة المرور الافتراضية فارغة في XAMPP
$dbname = "community_db"; // اسم قاعدة البيانات

// إنشاء اتصال بقاعدة البيانات
$conn = new mysqli($host, $user, $pass, $dbname);

// التحقق من نجاح الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التحقق من إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $age = (int) $_POST['age'];

    // التحقق من صحة البريد الإلكتروني
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red;'>Invalid email format!</p>";
    } else {
        $sql = "INSERT INTO users (name, email, age) VALUES ('$name', '$email', $age)";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Data saved successfully!</p>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// إغلاق الاتصال
$conn->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo "Community"; ?></title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h1>Calender</h1>
        <p>Today's date is: <?php echo date("Y-m-d"); ?></p>

        <!-- نموذج إدخال الاسم والعمر -->
        <form method="POST">
            <label for="email">Email:</label> 
            <input type="email" id="email" name="email" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name">
            
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>
            
            <button type="submit">Submit</button>
        <hr>

        <?php
        // التحقق من أن المستخدم أرسل البيانات
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = htmlspecialchars($_POST['name']); // حماية من XSS
            $age = (int) $_POST['age']; // تحويل العمر إلى رقم صحيح

            echo "<p>Name: $name and age is: $age</p>";
            // إنشاء محتوى البيانات للتخزين
            $data = "Name: $name, Email: $email, Age: $age\n";

            // حفظ البيانات في ملف data.txt
            file_put_contents("data.txt", $data, FILE_APPEND);

            echo "<p>Data has been saved successfully!</p>";
        }
        ?>
        </form>
    </body>
</html>