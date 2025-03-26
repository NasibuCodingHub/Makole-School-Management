<?php
session_start();
include("config.php");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $comment = mysqli_real_escape_string($conn, $_POST["comment"]);
    $class = mysqli_real_escape_string($conn, $_POST["class"]);
    $subject = mysqli_real_escape_string($conn, $_POST["subject"]);
    $senderId = $_SESSION['uid'];

    if (
        isset($_FILES["file"]) &&
        $_FILES["file"]["error"] == 0 &&
        isset($_POST["title"]) &&
        isset($_POST["class"]) &&
        isset($_POST["subject"]) &&
        isset($_POST["comment"])
    ) {
        $filename = $_FILES["file"]["name"];
        $tempname = $_FILES["file"]["tmp_name"];

        $fileInfo = pathinfo($filename);
        $fileExtension = strtolower($fileInfo['extension']);

        $newName = $senderId . time() . "." . $fileExtension;

        $folder = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "notesUploads" . DIRECTORY_SEPARATOR . $newName;

        if (move_uploaded_file($tempname, $folder)) {
            $query = "INSERT INTO `notes` (`s_no`, `sender_id`, `editor_id`, `class`, `subject`, `title`, `comment`, `file`, `timestamp`) VALUES (NULL,?,?,?,?,?,?,?, current_timestamp());";
            
            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                $response = "Preparation failed: " . mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param($stmt, "sssssss", $senderId, $senderId, $class, $subject, $title, $comment, $newName);
                
                if (mysqli_stmt_execute($stmt)) {
                    $response = "success";
                } else {
                    $response = "Execution failed: " . mysqli_stmt_error($stmt);
                }

                mysqli_stmt_close($stmt);
            }
        } else {
            $response = "File upload failed!";
        }
    } else {
        $response = "Invalid input or file error!";
    }
} else {
    $response = "Invalid request method!";
}

echo $response;
mysqli_close($conn);
?>
