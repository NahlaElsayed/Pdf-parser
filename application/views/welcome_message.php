<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$pdfText = $statusMsg ='';
$status='error';

if(isset($_POST['pdf_submit'])){ 
    // If file is selected 
    if(!empty($_FILES["pdf_upload"]["name"])){ 
        // File upload path 
        $fileName = basename($_FILES["pdf_upload"]["name"]); 
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
         
        // Allow certain file formats 
        $allowTypes = array('pdf'); 
        if(in_array($fileType, $allowTypes)){ 
            // Include autoloader file 
            include 'vendor/autoload.php'; 
             
            // Initialize and load PDF Parser library 
            $parser = new \Smalot\PdfParser\Parser(); 
             
            // Source PDF file to extract text 
            $file = $_FILES["pdf_upload"]["tmp_name"]; 
             
            // Parse pdf file using Parser library 
            $pdf = $parser->parseFile($file); 
             
            // Extract text from PDF 
            $text = $pdf->getText(); 
             
            // Add line break 
            $pdfText = nl2br($text); 
        }else{ 
            $statusMsg = '<p>Sorry, only PDF file is allowed to upload.</p>'; 
        } 
    }else{ 
        $statusMsg = '<p>Please select a PDF file to extract text.</p>'; 
    } 


} 
 


// Extract email addresses using regular expressions
$pattern = '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/';
preg_match_all($pattern, $pdfText, $matches);

$emailAddresses = $matches[0];


// Loop through the extracted email addresses and process them as needed
foreach ($emailAddresses as $email) {

	
// Split the email address by the @ symbol
$emailParts = explode('@', $email);

// Split the first part of the email by the . symbol
$nameParts = explode('.', $emailParts[0]);

$firstName = $nameParts[0];

if (count($nameParts) > 1) {
    $lastName = $nameParts[1];
} else {
    $lastName = ''; // Empty last name
}

echo 'First Name: ' . $firstName . '<br>';
echo 'Last Name: ' . $lastName. '<br>';
echo 'Email: ' . $email . '<br>';
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
	
	<title>
Multiple Upload PDF File With CodeIgniter
</title>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div id="container">

<?php

echo form_open_multipart();

echo form_upload(array(
	'multiple'=>'',
	'name'=>'pdf_upload[]'
));

echo form_error('pdf_upload');

echo form_submit(array(
	'name'=>'pdf_submit',
	'value'=>'upload file',

));

echo form_close();
?>

<div class="card" text-center style="color:white;   background-color:gray;">
    <div class="card-body">	<?php
	echo $pdfText ?>
     </div>
  </div>




</div>

</body>
</html>
