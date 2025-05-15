<?php

$filename = basename($_GET['file']);
$filepath = 'uploads/' . $filename;


if (file_exists($filepath)) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $contentType = finfo_file($finfo, $filepath);
    finfo_close($finfo);

    header('Content-Description: File Transfer');
    header('Content-Type: ' . $contentType);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($filepath));
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Expires: 0');

    flush();
    readfile($filepath);
    exit;
} else {
    echo "File not found.";
}
?>
