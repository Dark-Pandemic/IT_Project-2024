<?php
include ("../conn/conn.php");

if (isset($_GET['diary'])) {
    $diaryID = $_GET['diary'];

    try {
        $stmt = $conn->prepare("DELETE FROM tbl_diary WHERE tbl_diary_id = :tbl_diary_id");
        $stmt->bindParam('tbl_diary_id', $diaryID, PDO::PARAM_INT);
        $stmt->execute();

        echo"
        <script>
            alert('Diary Deleted Successfully');
            window.location.href = 'http://localhost/E-diary/read-diary.php';
        </script>
        ";
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
