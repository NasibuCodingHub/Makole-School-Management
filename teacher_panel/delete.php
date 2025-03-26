<?php
// Include the database configuration file
include("../assets/config.php");

// Initialize a variable for the response
$response = array('status' => 'error', 'message' => 'An error occurred');

// Check if a delete request has been made via AJAX
if (isset($_POST['subject'])) {
    $subjectToDelete = $_POST['subject'];

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM homework_answers WHERE subject = ?");
    $stmt->bind_param("s", $subjectToDelete);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = "Records deleted successfully!";
    } else {
        $response['message'] = "Error deleting records: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();

// Return the response as JSON
echo json_encode($response);
?>
