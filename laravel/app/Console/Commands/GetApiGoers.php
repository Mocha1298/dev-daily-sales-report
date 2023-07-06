<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;

class GetApiGoers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getapigoers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get API Naik Candi GOERS';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now()->format("Y-m-d H:i:s");
        // return $now;
        // $last_row = DB::connection("mysql4")->select("
        // select MAX(created_at) as max from trx
        // ");
        $last_row = DB::connection("mysql4")->select("
        select MAX(created_at) as max from trx
        ");
        $time = $last_row[0]->max;
        // return $time;
        $last_date = date('Y-m-d H:i:s', strtotime($time. ' +1 seconds'));
       
        // $last_date = $last_row->created_at;
        // return $last_date;

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
        $json_raw = $response->getBody();
        // return $json_raw;
        $json_raw = json_decode($json_raw,true);
        // return $json_raw['data'][0]['first_name'];
        $count = count($json_raw['data']);
        // return $count;
        $null = null;
        if($json_raw != null){
            for($i=0;$i<$count;$i++){
                if($i < 500){
                    $cek = sizeof($json_raw['data'][$i]);
                    if($cek == 12){
                        DB::connection("mysql4")->table('trx')->insert([
                            'first_name' => $json_raw['data'][$i]['first_name'],
                            'last_name' => $json_raw['data'][$i]['last_name'],
                            'email' => $json_raw['data'][$i]['email'],
                            'phone' => $json_raw['data'][$i]['phone'],
                            'schedule' => $json_raw['data'][$i]['schedule'],
                            'id' => $json_raw['data'][$i]['id'],
                            'ticket_id' => $json_raw['data'][$i]['ticket_id'],
                            'ticket_name' => $json_raw['data'][$i]['ticket_name'],
                            'venue_name' => $json_raw['data'][$i]['venue_name'],
                            'ticket_price' => $json_raw['data'][$i]['ticket_price'],
                            'validated_at' => $json_raw['data'][$i]['validated_at'],
                            'created_at' => $json_raw['data'][$i]['created_at'],
                            'recap_date' => $now,
                        ]);
                    }if($cek == 11){
                        DB::connection("mysql4")->table('trx')->insert([
                            'first_name' => $json_raw['data'][$i]['first_name'],
                            'last_name' => $json_raw['data'][$i]['last_name'],
                            'email' => $json_raw['data'][$i]['email'],
                            'phone' => $json_raw['data'][$i]['phone'],
                            'schedule' => $json_raw['data'][$i]['schedule'],
                            'id' => $json_raw['data'][$i]['id'],
                            'ticket_id' => $json_raw['data'][$i]['ticket_id'],
                            'ticket_name' => $json_raw['data'][$i]['ticket_name'],
                            'venue_name' => $json_raw['data'][$i]['venue_name'],
                            'ticket_price' => $json_raw['data'][$i]['ticket_price'],
                            'validated_at' => $null,
                            'created_at' => $json_raw['data'][$i]['created_at'],
                            'recap_date' => $now,
                        ]);
                    }
                }
            }
        }
        return Command::SUCCESS;
    }
}
