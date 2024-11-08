<?php
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['title'], $_POST['content'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $date = date("Y-m-d");

        try {
            $stmt = $conn->prepare("INSERT INTO tbl_diary (title, content, date) VALUES (:title, :content, :date)");
            
            $stmt->bindParam("title", $title, PDO::PARAM_STR);
            $stmt->bindParam("content", $content, PDO::PARAM_STR);
            $stmt->bindParam("date", $date, PDO::PARAM_STR);

            $stmt->execute();

            echo "
                <script>
                    alert('Diary Added Successfully!');
                    window.location.href = 'http://localhost/my-e-diary/home.php';
                </script>
            ";
        } catch (PDOException $e) {
            echo "Error:". $e->getMessage();
         }

    } else {
        echo "
            <script>
                alert('Please fill in all fields!');
                window.location.href = 'http://localhost/my-e-diary/write-diary.php';
            </script>
        ";
    }
}

?>

