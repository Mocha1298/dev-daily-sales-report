<?php
$mysqli = new mysqli('report.twc.id','repo_ipin','vHqvKxiFrtyee-nN');
if($mysqli->connect_errno != 0){
    echo $mysqli->connect_error;
}

$json_data = file_get_contents("apigoers.json");
$data = json_decode($json_data, JSON_OBJECT_AS_ARRAY);

$stmt = mysqli->prepare("
    INSERT INTO trx(
        first_name,
        last_name,
        email,
        phone,
        schedule,
        id,
        ticket_id,
        ticket_name,
        venue_name,
        ticket_price,
        validated_at,
        created_at,
        recap_date
    )
    values(?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param("sssssssssisss","
    $first_name,
    $last_name,
    $email,
    $phone,
    $schedule,
    $id,
    $ticket_id,
    $ticket_name,
    $venue_name,
    $ticket_price,
    $validated_at,
    $created_at,
    $recap_date
");

$inserted_rows = 0;
foreach ($data as $item) {
    $first_name = $item['first_name'];
    $last_name = $item['last_name'];
    $email = $item['email'];
    $phone = $item['phone'];
    $schedule = $item['schedule'];
    $id = $item['id'];
    $ticket_id = $item['ticket_id'];
    $ticket_name = $item['ticket_name'];
    $venue_name = $item['venue_name'];
    $ticket_price = $item['ticket_price'];
    $validated_at = $item['validated_at'];
    $created_at = $item['created_at'];
    $recap_date = $item['recap_date'];
    
    $stmt->execute();
    $inserted_rows ++;
}

if(count($data) == $inserted_rows){
    return "SUKSES";
 }else{
    return "KURANG";
 }