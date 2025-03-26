<?php
session_start();
include '../assets/config.php';

if (isset($_POST['upload'])) {
    $id = $_SESSION['uid'];
    $subject = $_POST['note_id'];
    $upload_time = date('Y-m-d H:i:s');

    // Retrieve the student's fname from the database
    $query = "SELECT fname FROM students WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $fname = '';
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fname = $row['fname'];
    }
    $stmt->close();

    // Check if a file was uploaded
    if (isset($_FILES['homework_file']) && $_FILES['homework_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['homework_file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = array('pdf', 'doc', 'docx', 'jpg', 'png');

        if (in_array($fileExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 5000000) { // Limit file size to 5MB
                    // Check if the file already exists in the database
                    $checkQuery = "SELECT * FROM homework_answers WHERE student_id=? AND subject=? AND file_name=?";
                    $stmt = $conn->prepare($checkQuery);
                    $stmt->bind_param("sss", $id, $subject, $fileName);
                    $stmt->execute();
                    $checkResult = $stmt->get_result();

                    if ($checkResult->num_rows > 0) {
                        $_SESSION['alert'] = 'File already exists for this subject!';
                        $_SESSION['alert_type'] = 'error';
                        header('Location: workspace.php');
                        exit();
                    } else {
                        $fileDestination = '../homeworkUploads/' . $fileName;

                        if (move_uploaded_file($fileTmpName, $fileDestination)) {

                            // Insert the data into the database
                            $insertQuery = "INSERT INTO homework_answers (student_id, fname, subject, file_name, upload_time) VALUES (?, ?, ?, ?, ?)";
                            $stmt = $conn->prepare($insertQuery);
                            $stmt->bind_param("sssss", $id, $fname, $subject, $fileName, $upload_time);

                            if ($stmt->execute()) {
                                $_SESSION['alert'] = 'File uploaded and data inserted successfully!';
                                $_SESSION['alert_type'] = 'success';
                            } else {
                                $_SESSION['alert'] = 'Error: ' . $stmt->error;
                                $_SESSION['alert_type'] = 'error';
                            }

                            $stmt->close();
                        } else {
                            $_SESSION['alert'] = 'Failed to move uploaded file!';
                            $_SESSION['alert_type'] = 'error';
                        }
                        header('Location: workspace.php');
                        exit();
                    }
                    $stmt->close();
                } else {
                    $_SESSION['alert'] = 'Your file is too large!';
                    $_SESSION['alert_type'] = 'error';
                    header('Location: workspace.php');
                    exit();
                }
            } else {
                $_SESSION['alert'] = 'There was an error uploading your file!';
                $_SESSION['alert_type'] = 'error';
                header('Location: workspace.php');
                exit();
            }
        } else {
            $_SESSION['alert'] = 'You cannot upload files of this type!';
            $_SESSION['alert_type'] = 'error';
            header('Location: workspace.php');
            exit();
        }
    } else {
        $_SESSION['alert'] = 'No file was uploaded or there was an upload error!';
        $_SESSION['alert_type'] = 'error';
        header('Location: workspace.php');
        exit();
    }
}
?>
