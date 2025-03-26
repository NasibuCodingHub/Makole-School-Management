<?php
// Include the database configuration file
include("../assets/config.php");

// Query to fetch homework answers
$sql = "SELECT * FROM homework_answers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Create a temporary ZIP file
    $zip = new ZipArchive();
    $zipFileName = 'homework_answers.zip';
    $zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Add files to the ZIP
    while ($row = $result->fetch_assoc()) {
        $filePath = '../homeworkUploads/' . $row["file_name"];
        if (file_exists($filePath)) {
            $zip->addFile($filePath, $row["file_name"]);
        }
    }

    $zip->close();

    // Serve the ZIP file for download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
    header('Content-Length: ' . filesize($zipFileName));
    readfile($zipFileName);

    // Delete the temporary ZIP file
    unlink($zipFileName);
} else {
    echo "No homework found.";
}

$conn->close();
?>
