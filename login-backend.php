<?php
error_reporting(0);
session_start();
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("assets/config.php");


    if ($conn) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $sql = "SELECT id, role, password_hash FROM users WHERE email=?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                $row = mysqli_fetch_assoc($result);

                if ($row && password_verify($password, $row['password_hash'])) {
                    $_SESSION['uid'] = $row['id'];
                    $_SESSION['role'] = $row['role'];

                    // Record login time in login_history
                    $login_time = date("Y-m-d H:i:s");
                    $insert_sql = "INSERT INTO login_history (user_id, role, login_time) VALUES (?, ?, ?)";
                    $insert_stmt = mysqli_prepare($conn, $insert_sql);
                    mysqli_stmt_bind_param($insert_stmt, "sss", $row['id'], $row['role'], $login_time);
                    mysqli_stmt_execute($insert_stmt);

                    // Store login_history_id in session
                    $_SESSION['login_history_id'] = mysqli_insert_id($conn);

                    $response['status'] = 'success';
                    $response['role'] = $row['role'];
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Invalid email or password!';
                }

                mysqli_stmt_close($stmt);
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error fetching result';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error preparing statement';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Database connection error';
    }

    echo json_encode($response);
}


?>
