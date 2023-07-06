<?php
$inc_name = $_POST['inc_name'];
$inc_type = $_POST['inc_type'];
$pic = $_POST['pic'];
$no_pic = $_POST['no_pic'];
$plan = $_POST['plan'];
$qty = $_POST['qty'];
$jam = $_POST['jam'];
$file = $_POST['file'];

// Recipient 
$to = 'moch.n.arifin@gmail.com'; 
 
// Email subject 
$subject = 'Borobudur Online Single Submissions';  
 
// Attachment file 
// $file = "uploads/jos.jpg"; 

// Email body content 
$htmlContent = ' 
    <h3>Detail Data</h3> 
    <p>Nama Instansi: '.$inc_name.'</p> 
    <p>Tipe Instansi: '.$inc_type.'</p> 
    <p>Nama PIC: '.$pic.'</p> 
    <p>Kontak PIC: '.$no_pic.'</p> 
    <p>Waktu Kunjung: '.$jam.' WIB '.$plan.'</p> 
'; 
 
// Header for sender info 
$headers = "From: noreply"; 
 
// Boundary  
$semi_rand = md5(time());  
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
 
// Headers for attachment  
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
 
// Multipart boundary  
$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";  

// Preparing attachment 
if(!empty($file) > 0){
    $targetDir = "/";
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
    // Allow certain file formats
    $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg');
    if(in_array($fileType, $allowTypes)){
            // Upload file to the server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
                $uploadedFile = $targetFilePath;
                $message .= "--{$mime_boundary}\n"; 
                $fp =    @fopen($file,"rb"); 
                $data =  @fread($fp,filesize($file)); 
         
                @fclose($fp); 
                $data = chunk_split(base64_encode($data)); 
                $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
                "Content-Description: ".basename($file)."\n" . 
                "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
                "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
        }else{
                $uploadStatus = 0;
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    }else{
            $uploadStatus = 0;
        $statusMsg = 'Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.';
    }
}else{
    echo "jossss";
}
$message .= "--{$mime_boundary}--"; 
$returnpath = "-f noreply"; 
 
// Send email 
$mail = @mail($to, $subject, $message, $headers, $returnpath);  
 
// Email sending status 
echo $mail?"<h1>Email Sent Successfully!</h1>":"<h1>Email sending failed.</h1>"; 
 
?>