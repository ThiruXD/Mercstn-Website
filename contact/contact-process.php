<?php
$address = "thiruselvan868@gmail.com"; // Your email address
if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");

$error = false;
$fields = array('name', 'email', 'phone', 'subject', 'message');

foreach ($fields as $field) {
    if (empty($_POST[$field]) || trim($_POST[$field]) == '') {
        $error = true;
    }
}

if (!$error) {
    $name = stripslashes($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = stripslashes($_POST['subject']);
    $message = stripslashes($_POST['message']);

    $e_subject = 'You\'ve been contacted by ' . $name . '. Subject: ' . $subject;

    // Prepare email content
    $e_body = "You have been contacted by: $name" . PHP_EOL . PHP_EOL;
    $e_reply = "E-mail: $email" . PHP_EOL;
    $e_phone = "Phone: $phone" . PHP_EOL;
    $e_content = "Subject: $subject" . PHP_EOL . "Message:\r\n$message" . PHP_EOL;

    $msg = wordwrap($e_body . $e_reply . $e_phone . $e_content, 70);

    $headers = "From: $email" . PHP_EOL;
    $headers .= "Reply-To: $email" . PHP_EOL;
    $headers .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Allowed file extensions
        $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'pdf');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Set the destination path for the uploaded file
            $uploadFileDir = './uploaded_files/';
            $dest_path = $uploadFileDir . $fileName;

            // Move the file to the destination
            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                // File is successfully uploaded
                $msg .= PHP_EOL . "File uploaded: " . $fileName;
            } else {
                echo 'Error moving the uploaded file.';
            }
        } else {
            echo 'Upload failed. Allowed file types: ' . implode(', ', $allowedfileExtensions);
        }
    }

    if (mail($address, $e_subject, $msg, $headers)) {
        // Email has sent successfully, echo a success message.
        echo 'Success';
    } else {
        echo 'ERROR!';
    }
} else {
    echo 'Please fill in all fields.';
}
?>
