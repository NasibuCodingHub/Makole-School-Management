<?php
if (isset($_GET['file'])) {
    $fileName = basename($_GET['file']);
    $filePath = '../homeworkUploads/' . $fileName;

    if (file_exists($filePath)) {
        // Determine the MIME type of the file
        $mimeType = mime_content_type($filePath);

        // Set headers
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        // Clear output buffer and read the file
        ob_clean();
        flush();
        readfile($filePath);
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "No file specified.";
}
?>
