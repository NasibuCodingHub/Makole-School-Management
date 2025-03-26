<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("database.php"); // Include your database configuration

if (isset($_SESSION['login_history_id'])) {
    $logout_time = date("Y-m-d H:i:s");

    // Prepare the query to fetch the login time
    $sql = "SELECT login_time FROM login_history WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['login_history_id']);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            $row = mysqli_fetch_assoc($result);

            if ($row) {
                $login_time = strtotime($row['login_time']);
                $logout_time_timestamp = strtotime($logout_time);

                // Calculate the session duration
                $duration = gmdate("H:i:s", $logout_time_timestamp - $login_time);

                // Prepare the update query for logout time and session duration
                $update_sql = "UPDATE login_history 
                               SET logout_time = ?, session_duration = ? 
                               WHERE id = ?";
                $update_stmt = mysqli_prepare($conn, $update_sql);
                if ($update_stmt) {
                    mysqli_stmt_bind_param($update_stmt, "ssi", $logout_time, $duration, $_SESSION['login_history_id']);
                    if (!mysqli_stmt_execute($update_stmt)) {
                        error_log("Failed to update logout time and session duration: " . mysqli_error($conn));
                    }
                    mysqli_stmt_close($update_stmt);
                } else {
                    error_log("Failed to prepare update statement: " . mysqli_error($conn));
                }
            } else {
                error_log("Failed to fetch login time.");
            }
        } else {
            error_log("Failed to execute query: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Failed to prepare statement: " . mysqli_error($conn));
    }
}

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page
header('Location: ../index.php');
exit();
?>
