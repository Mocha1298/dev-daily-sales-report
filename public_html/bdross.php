<?php
 
if(isset($_POST['button']) && isset($_FILES['attachment']))
{
    $count = count($_FILES['attachment']['name']);
    // echo "<pre>";
    // print_r($_FILES['attachment']['name']);
    // echo "</pre>";
    $from_email         = 'noreply'; //from mail, sender email address
    $recipient_email = 'moch.n.arifin@gmail.com'; //recipient email address

    $inc_name = $_POST['inc_name'];
    $inc_type = $_POST['inc_type'];
    $pic = $_POST['pic'];
    $no_pic = $_POST['no_pic'];
    $email = $_POST['email'];
    $plan = $_POST['plan'];
    $jam = $_POST['jam'];
    $date = date("d-m-Y");
     
    //Load POST data from HTML form
    $reply_to_email = $email; //sender email, it will be used in "reply-to" header
    $subject     = "Borobudur Online Single Subsmissions"; //subject for the email
    $message     = "Nama Instansi : ".$inc_name."\nTipe Instansi : ".$inc_type."\nNama PIC : ".$pic."\nKontak PIC : ".$no_pic."\nWaktu Kunjungan : ".$jam." ".$plan; //body of the email

    $headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
    $headers .= "From:".$from_email."\r\n"; // Sender Email
    $headers .= "Content-Type: multipart/mixed;"; // Defining Content-Type

    $boundary = md5("random"); // define boundary with a md5 hashed value
    //header
    $headers .= "boundary = $boundary\r\n"; //Defining the Boundary
    //body
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= chunk_split(base64_encode($message));
    
    for ($i=0; $i < $count; $i++) { 
        //Get uploaded file data using $_FILES array
        $tmp_name = $_FILES['attachment']['tmp_name'][$i]; // get the temporary file name of the file on the server
        $name     = $_FILES['attachment']['name'][$i]; // get the name of the file
        $size     = $_FILES['attachment']['size'][$i]; // get size of the file for size validation
        $type     = $_FILES['attachment']['type'][$i]; // get type of the file
        $error     = $_FILES['attachment']['error'][$i]; // get the error (if any)
    
        //validate form field for attaching the file
        if($error > 0)
        {
            die('Upload error or No files uploaded');
        }
    
        //read from the uploaded file & base64_encode content
        $handle = fopen($tmp_name, "r"); // set the file handle only for reading the file
        $content = fread($handle, $size); // reading the file
        fclose($handle);                 // close upon completion
    
        $encoded_content = chunk_split(base64_encode($content));
            
        //attachment
        $body .= "--$boundary\r\n";
        $body .="Content-Type: $type; name=".$name."\r\n";
        $body .="Content-Disposition: attachment; filename=".$name."\r\n";
        $body .="Content-Transfer-Encoding: base64\r\n";
        $body .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";
        $body .= $encoded_content; // Attaching the encoded file with email
    }
     
    $sentMailResult = mail($recipient_email, $subject, $body, $headers);

    $body_rep = "Terimakasih telah mengirim subsmission\nSalam Hangat,\n\nPT Taman Wisata Candi Borobudur, Prambanan dan Ratu Boko";
    $headers_rep = "From:".$from_email; // Sender Email
    $sentMailResult_rep = mail($reply_to_email, $subject, $body_rep, $headers_rep);
 
    if($sentMailResult ){
        session_start();

        $_SESSION['flash_message'] = "Sukses";
    }
    else{
        session_start();

        $_SESSION['flash_message'] = "Gagal";
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/ico.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <title>BDR OSS</title>
    <style>
        .card {
            margin: auto;
            border-radius: 10px;
        }

        input {
            border: 0;
        }
        .responsive {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-dark">
    <div class="container bg-dark">
        <div class="card col-sm-6" style="display:flex; justify-content: center;">
            <img src="bdr.jpg" alt="Nature" class="responsive mt-3">
            <?php
                session_start();

                if(isset($_SESSION['flash_message'])) {
                    $message = $_SESSION['flash_message'];
                    unset($_SESSION['flash_message']);
                    if($_SESSION == "Sukses"){
                        echo "
                        <div class='alert alert-success'>
                            Sukses mengirim
                        </div>
                        ";
                    }elseif($_SESSION == "Gagal"){
                        echo "
                        <div class='alert alert-error'>
                            Gagal mengirim
                        </div>
                        ";
                    }
                }
            ?>
            <h4 class="text-center mt-3" href="#">Borobudur Online Single Subsmission</h4>
            <form enctype="multipart/form-data" method="POST" action="" class="mt-4 mb-4">
                <div class="mb-3">
                    <label for="inc_name" class="form-label">Nama Instansi</label>
                    <input type="text" class="form-control" id="inc_name" required name="inc_name">
                </div>
                <div class="mb-3">
                    <label for="inc_type" class="form-label">Jenis Instansi</label>
                    <input type="text" class="form-control" id="inc_type" required name="inc_type">
                </div>
                <div class="mb-3">
                    <label for="pic" class="form-label">Nama PIC</label>
                    <input type="text" class="form-control" id="pic" required name="pic">
                </div>
                <div class="mb-3">
                    <label for="no_pic" class="form-label">Kontak PIC</label>
                    <input type="text" class="form-control" id="no_pic" required name="no_pic">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email PIC</label>
                    <input type="text" class="form-control" id="email" required name="email">
                </div>
                <div class="mb-3">
                    <label for="qty" class="form-label">Jumlah Orang</label>
                    <input type="number" class="form-control" id="qty" required name="jumlahqty">
                </div>
                <div class="mb-3">
                    <label for="plan" class="form-label">Rencana Kunjungan</label>
                    <input type="date" class="form-control" id="plan" required name="plan">
                </div>
                <div class="mb-3">
                    <label for="jam" class="form-label">Waktu Kunjungan</label>
                    <div>
                        <input type="time" class="form-control" id="jam" required name="jam" min="03:00"
                            max="18:00">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="attachment" class="form-label">Upload Dokumen</label>
                    <input type="file" class="form-control" id="attachment" required name="attachment[]" multiple>
                </div>
                <div class="form-group">
                    <input class="btn btn-primary text-center" type="submit" name="button" value="Submit" />
                </div>           
            </form>
        </div>
    </div>
</body>
</html>