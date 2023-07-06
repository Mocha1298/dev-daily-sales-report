<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use DatePeriod;
use DateInterval;

class ReportController extends Controller
{
    function pelataran() {
        $countrow = DB::connection('db_goers')->table('trx_pelataran')->get();
        if(count($countrow) > 0){
            $last_row = DB::connection("db_goers")->select("
            select MAX(created_at) as max from trx_pelataran
            ");
            $time = $last_row[0]->max;
            $last_date = date('Y-m-d H:i:s', strtotime($time. ' +1 seconds'));
            $now = date('Y-m-d H:i:s', strtotime($time. ' +2 days'));
        }else{
            $time = "2023-04-15 00:00:00";
            $last_date = date('Y-m-d H:i:s', strtotime($time. ' +1 seconds'));
            $now = date('Y-m-d H:i:s', strtotime($time. ' +2 days'));
        }
        // return $now;
        $i = 0;
        $date_temp = $last_date;
        do {
            if ($i > 0) {
                $last_date = date('Y-m-d H:i:s', strtotime($date_temp. ' +2 days'));
                $now = date('Y-m-d H:i:s', strtotime($last_date. ' +2 days'));
                $i = 0;
            }else{
                $i++;
            }
            // api section
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

                $client = new \GuzzleHttp\Client();

                $endpoint = "https://newapi.goersapp.com/v3.1/integration/attendees/content?content_id=493696332&content_type=v&start=".$last_date."&end=".$now."&fields=ticket_price";
                $response = $client->request('GET', $endpoint,array(
                    'headers' => array(
                        'Api-Key' => '013a4392-14c8-438d-bcbc-535be9141d13',
                        'G-Channel' => '4',
                        'Authorization' => $token
                    )
                ));
                $statusCode = $response->getStatusCode();
                $json_raw = $response->getBody();
                $json_raw = json_decode($json_raw,true);
            // api section
            $count = count($json_raw['data']);
            $date_temp = $last_date;
        } while ($count == 0);
        $null = null;
        // return $json_raw;
        // return $count;
        for($i=0;$i<$count;$i++){
            if($i < 500){
                $cek = sizeof($json_raw['data'][$i]);
                if($cek == 12){
                    DB::connection("db_goers")->table('trx_pelataran')->insert([
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
                    DB::connection("db_goers")->table('trx_pelataran')->insert([
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
    public function home()
    {
        $now = Carbon::now();
        $start = $now->format('Y-m-d');
        $end = $now->format('Y-m-d');
        $isdash = 1;
        $data = [
            'start'=>$start,
            'end'=>$end,
            'isdash'=>$isdash
        ];
        return view("dashboard",$data);
    }
}
