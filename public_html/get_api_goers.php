<?
$now = Carbon::now()->format("Y-m-d h:i:s");
    $now = "2023-04-24 00:02:00";
    $last_row = DB::connection("mysql4")->select("
    select MAX(created_at) as max from trx where DATE(created_at) = '2023-04-23'
    ");
    $time = $last_row[0]->max;
    $last_date = date('Y-m-d H:i:s', strtotime($time. ' +1 seconds'));
    $endpoint1 = "https://newapi.goersapp.com/v3.1/integration/auth/token";
    $client1 = new \GuzzleHttp\Client();
    $email = "api.borobudur@goersapp.com";
    $password = "72bedc3e081bb7698a0ed47ea06e1f05ce0ca0f9";

    $response1 = $client1->request('POST', $endpoint1,array(
        'form_params' => array(
            'email' => $email,
            'password' => $password
        ),
        'headers' => array(
            'Api-Key' => '013a4392-14c8-438d-bcbc-535be9141d13',
            'G-Channel' => '4'
        )
    ));

    $statusCode = $response1->getStatusCode();
    $content = $response1->getBody();
    $token = json_decode($content)->data->token;
    // versi date
    $client = new \GuzzleHttp\Client();
    $endpoint = "https://newapi.goersapp.com/v3.1/integration/attendees/content?content_id=336606369&content_type=v&start=".$last_date."&end=".$now."&fields=ticket_price";
    $response = $client->request('GET', $endpoint,array(
        'headers' => array(
            'Api-Key' => '013a4392-14c8-438d-bcbc-535be9141d13',
            'G-Channel' => '4',
            'Authorization' => $token
        )
    ));
    $statusCode = $response->getStatusCode();
    $content = $response->getBody();

    $data = json_decode($content)->data;

    $count = count($data);
    if($data != null){
        for($i=0;$i<$count;$i++){
            DB::connection("mysql4")->table('trx_test')->insert([
                'first_name' => $data[$i]->first_name,
                'last_name' => $data[$i]->last_name,
                'email' => $data[$i]->email,
                'phone' => $data[$i]->phone,
                'schedule' => $data[$i]->schedule,
                'id' => $data[$i]->id,
                'ticket_id' => $data[$i]->ticket_id,
                'ticket_name' => $data[$i]->ticket_name,
                'venue_name' => $data[$i]->venue_name,
                'ticket_price' => $data[$i]->ticket_price,

                'created_at' => $data[$i]->created_at,

            ]);
        }
        return "SUKSES";
    }