<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use DatePeriod;
use DateInterval;

class ManoharaController extends Controller
{
    public function index($start,$end)
    {
        if(date("Y",strtotime($start)) != date("Y",strtotime($end))){
            return redirect()->back()->with("gagal","tahun");
        }
        $date_start = str_replace("-","",$start);//FORMAT FILTER TANPA STRIP (ERP)
        $date_end = str_replace("-","",$end);//FORMAT FILTER TANPA STRIP (ERP)
        $date2_start = date("Ymd",strtotime($start.' - 1 days')); //FILTER - 1
        $date2_end = date("Ymd",strtotime($end.' - 1 days')); //FILTER - 1
        // Masing - masing value di combine dari pengguna jasa dan income ya

        $tabel_pengguna_jasa = [];
        $total_pengguna_jasa = [];
        // tabel pengguna jasa
            // $ticketing = DB::connection("db_manohara")->select("
            //     select w.id_link,w.deskripsi,
            //     case when x.total_trx > 0 then x.total_trx else 0 end as actual_trx_date,
            //     case when y.total_trx > 0 then y.total_trx else 0 end as actual_trx_month,
            //     case when z.total_trx > 0 then z.total_trx else 0 end as actual_trx_year
            //     from
            //     (
            //         select 1 as id_link, 'Domestik' as deskripsi
            //         union all
            //         select 2 as id_link, 'Asing' as deskripsi
            //     )w
            //     left outer join
            //     (
            //         select id_link ,total_trx
            //         from
            //         (
            //             (
            //                 select 1 as id_link, sum(qty) as total_trx
            //                 from manohara_trans_ticket 
            //                 where (DATE_FORMAT(transaction_time, '%Y%m%d') between '".$date_start."' and '".$date_end."') and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
            //                 group by id_link
            //             )
            //             union all
            //             (
            //                 select 2 as id_link, sum(qty) as total_trx
            //                 from manohara_trans_ticket 
            //                 where (DATE_FORMAT(transaction_time, '%Y%m%d') between '".$date_start."' and '".$date_end."') and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
            //                 group by id_link
            //             )
            //         )x
            //     )x on w.id_link = x.id_link
            //     left outer join
            //     (
            //         select id_link, total_trx
            //         from
            //         (
            //             (
            //                 select 1 as id_link, sum(qty) as total_trx
            //                 from manohara_trans_ticket 
            //                 where (DATE_FORMAT(transaction_time, '%Y%m') between left('".$date_start."',6) and left('".$date_end."',6)) and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
            //                 group by id_link
            //             )
            //             union all
            //             (
            //                 select 2 as id_link, sum(qty) as total_trx
            //                 from manohara_trans_ticket 
            //                 where (DATE_FORMAT(transaction_time, '%Y%m') between left('".$date_start."',6) and left('".$date_end."',6)) and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
            //                 group by id_link
            //             )
            //         )y
            //     )y on w.id_link = y.id_link
            //     left outer join
            //     (
            //         select id_link,total_trx
            //         from
            //         (
            //             (
            //                 select 1 as id_link, sum(qty) as total_trx
            //                 from manohara_trans_ticket 
            //                 where DATE_FORMAT(transaction_time, '%Y') = left('".$date_end."',4) and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
            //                 group by id_link
            //             )
            //             union all
            //             (
            //                 select 2 as id_link, sum(qty) as total_trx
            //                 from manohara_trans_ticket 
            //                 where DATE_FORMAT(transaction_time, '%Y') = left('".$date_end."',4) and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
            //                 group by id_link
            //             )
            //         )z
            //     )z on w.id_link = z.id_link
            // ");
            $ticketing = DB::connection("db_manohara")->select("
                select w.id_link,w.deskripsi,
                case when x.total_trx > 0 then x.total_trx else 0 end as actual_trx_date,
                case when x.total_nom > 0 then x.total_nom else 0 end as actual_nom_date,
                case when y.total_trx > 0 then y.total_trx else 0 end as actual_trx_month,
                case when y.total_nom > 0 then y.total_nom else 0 end as actual_nom_month,
                case when z.total_trx > 0 then z.total_trx else 0 end as actual_trx_year,
                case when z.total_nom > 0 then z.total_nom else 0 end as actual_nom_year
                from
                (
                    select 1 as id_link, 'Domestik' as deskripsi
                    union all
                    select 2 as id_link, 'Asing' as deskripsi
                )w
                left outer join
                (
                    select id_link ,total_trx,total_nom
                    from
                    (
                        (
                            select 1 as id_link, sum(qty) as total_trx, sum(subtotal) as total_nom
                            from manohara_trans_ticket 
                            where (DATE_FORMAT(transaction_time, '%Y%m%d') between '".$date_start."' and '".$date_end."') and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(qty) as total_trx, sum(subtotal) as total_nom
                            from manohara_trans_ticket 
                            where (DATE_FORMAT(transaction_time, '%Y%m%d') between '".$date_start."' and '".$date_end."') and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
                            group by id_link
                        )
                    )x
                )x on w.id_link = x.id_link
                left outer join
                (
                    select id_link, total_trx,total_nom
                    from
                    (
                        (
                            select 1 as id_link, sum(qty) as total_trx, sum(subtotal) as total_nom
                            from manohara_trans_ticket 
                            where (DATE_FORMAT(transaction_time, '%Y%m') between left('".$date_start."',6) and left('".$date_end."',6)) and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(qty) as total_trx, sum(subtotal) as total_nom
                            from manohara_trans_ticket 
                            where (DATE_FORMAT(transaction_time, '%Y%m') between left('".$date_start."',6) and left('".$date_end."',6)) and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
                            group by id_link
                        )
                    )y
                )y on w.id_link = y.id_link
                left outer join
                (
                    select id_link,total_trx,total_nom
                    from
                    (
                        (
                            select 1 as id_link, sum(qty) as total_trx, sum(subtotal) as total_nom
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y') = left('".$date_end."',4) and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(qty) as total_trx, sum(subtotal) as total_nom
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y') = left('".$date_end."',4) and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
                            group by id_link
                        )
                    )z
                )z on w.id_link = z.id_link
            ");
            // return $ticketing;
            $target_pj = DB::connection("db_target")->select("
                SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
                (
                    SELECT y.id_link, sum(x.target) as target 
                    FROM new_master_target_volume x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = 5 
                    GROUP BY y.id_link
                )x
                LEFT OUTER JOIN 
                (
                    SELECT y.id_link, SUM(x.target) AS target
                    FROM new_master_target_volume x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE x.tahun = left('".$date_end."',4) and z.id = 5 
                    GROUP BY y.id_link
                )Y
                ON x.id_link = y.id_link
                group by x.id_link,x.target,y.target
            ");
            for ($i=0; $i <= 1; $i++) {
                $tabel_pengguna_jasa[$i]['id_link'] = $ticketing[$i]->id_link;
                $tabel_pengguna_jasa[$i]['nama'] = $ticketing[$i]->deskripsi;
                $tabel_pengguna_jasa[$i]['aktual_d'] = $ticketing[$i]->actual_trx_date;
                $tabel_pengguna_jasa[$i]['aktual_m'] = $ticketing[$i]->actual_trx_month;
                $tabel_pengguna_jasa[$i]['target_m'] = $target_pj[$i]->target_mountly;
                if($tabel_pengguna_jasa[$i]['target_m'] != 0){
                    $tabel_pengguna_jasa[$i]['persen_m'] = $ticketing[$i]->actual_trx_month/$target_pj[$i]->target_mountly*100;
                }else{
                    $tabel_pengguna_jasa[$i]['persen_m'] = 0;
                }
                $tabel_pengguna_jasa[$i]['aktual_y'] = $ticketing[$i]->actual_trx_year;
                $tabel_pengguna_jasa[$i]['target_y'] = $target_pj[$i]->target_yearly;
                if($tabel_pengguna_jasa[$i]['target_y'] != 0){
                    $tabel_pengguna_jasa[$i]['persen_y'] = $ticketing[$i]->actual_trx_year/$target_pj[$i]->target_yearly*100;
                }else{
                    $tabel_pengguna_jasa[$i]['persen_y'] = 0;
                }
            };
            $total_pengguna_jasa[0]['id_link'] = "kosong";
            $total_pengguna_jasa[0]['nama'] = "kosong";
            $total_pengguna_jasa[0]['aktual_d'] = 0;
            $total_pengguna_jasa[0]['aktual_m'] = 0;
            $total_pengguna_jasa[0]['target_m'] = 0;
            $total_pengguna_jasa[0]['aktual_y'] = 0;
            $total_pengguna_jasa[0]['target_y'] = 0;
            for ($i=0; $i <= 1; $i++) {
                $total_pengguna_jasa[0]['aktual_d'] += $tabel_pengguna_jasa[$i]['aktual_d'];
                $total_pengguna_jasa[0]['aktual_m'] += $tabel_pengguna_jasa[$i]['aktual_m'];
                $total_pengguna_jasa[0]['target_m'] += $tabel_pengguna_jasa[$i]['target_m'];
                $total_pengguna_jasa[0]['aktual_y'] += $tabel_pengguna_jasa[$i]['aktual_y'];
                $total_pengguna_jasa[0]['target_y'] += $tabel_pengguna_jasa[$i]['target_y'];
            };
            if($total_pengguna_jasa[0]['target_m'] != 0){
                $total_pengguna_jasa[0]['persen_m'] = $total_pengguna_jasa[0]['aktual_m']/$total_pengguna_jasa[0]['target_m']*100;
            }else{
                $total_pengguna_jasa[0]['persen_m'] = 0;
            }
            if ($total_pengguna_jasa[0]['target_y'] != 0) {
                $total_pengguna_jasa[0]['persen_y'] = $total_pengguna_jasa[0]['aktual_y']/$total_pengguna_jasa[0]['target_y']*100;
            }else{
                $total_pengguna_jasa[0]['persen_y'] = 0;
            }
            // return $tabel_pengguna_jasa;
        // tabel pengguna jasa
        $tabel_income = [];
        $total_income = [];
        // tabel income
            // $income = DB::connection("db_manohara")->select("
            //     select w.id_link,w.deskripsi,
            //     case when x.total_nom > 0 then x.total_nom else 0 end as actual_nom_date,
            //     case when y.total_nom > 0 then y.total_nom else 0 end as actual_nom_month,
            //     case when z.total_nom > 0 then z.total_nom else 0 end as actual_nom_year
            //     from
            //     (
            //         select 1 as id_link, 'Domestik' as deskripsi
            //         union all
            //         select 2 as id_link, 'Asing' as deskripsi
            //     )w
            //     left outer join
            //     (
            //         select id_link ,total_nom
            //         from
            //         (
            //             (
            //                 select 1 as id_link, sum(subtotal) as total_nom
            //                 from manohara_trans_ticket 
            //                 where (DATE_FORMAT(transaction_time, '%Y%m%d') between '".$date_start."' and '".$date_end."') and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
            //                 group by id_link
            //             )
            //             union all
            //             (
            //                 select 2 as id_link, sum(subtotal) as total_nom
            //                 from manohara_trans_ticket 
            //                 where (DATE_FORMAT(transaction_time, '%Y%m%d') between '".$date_start."' and '".$date_end."') and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
            //                 group by id_link
            //             )
            //         )x
            //     )x on w.id_link = x.id_link
            //     left outer join
            //     (
            //         select id_link, total_nom
            //         from
            //         (
            //             (
            //                 select 1 as id_link, sum(subtotal) as total_nom
            //                 from manohara_trans_ticket 
            //                 where (DATE_FORMAT(transaction_time, '%Y%m') between left('".$date_start."',6) and left('".$date_end."',6)) and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
            //                 group by id_link
            //             )
            //             union all
            //             (
            //                 select 2 as id_link, sum(subtotal) as total_nom
            //                 from manohara_trans_ticket 
            //                 where (DATE_FORMAT(transaction_time, '%Y%m') between left('".$date_start."',6) and left('".$date_end."',6)) and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
            //                 group by id_link
            //             )
            //         )y
            //     )y on w.id_link = y.id_link
            //     left outer join
            //     (
            //         select id_link,total_nom
            //         from
            //         (
            //             (
            //                 select 1 as id_link, sum(subtotal) as total_nom
            //                 from manohara_trans_ticket 
            //                 where DATE_FORMAT(transaction_time, '%Y') = left('".$date_end."',4) and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
            //                 group by id_link
            //             )
            //             union all
            //             (
            //                 select 2 as id_link, sum(subtotal) as total_nom
            //                 from manohara_trans_ticket 
            //                 where DATE_FORMAT(transaction_time, '%Y') = left('".$date_end."',4) and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
            //                 group by id_link
            //             )
            //         )z
            //     )z on w.id_link = z.id_link
            // ");
            // return $income;
            $erp = DB::connection('db_erp')->select("
                select x.id_link, x.deskripsi,COALESCE(x.actual_nominal_date,0) as actual_nominal_date,COALESCE(y.actual_nominal_month,0) as actual_nominal_month,COALESCE(z.actual_nominal_year,0) as actual_nominal_year
                from
                (
                    select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_date from 
                        (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.05.') and AcNo <> '4.02.05.11' and (CAmt - DAmt) > 0
                    and (DocDt between '".$date_start."' and '".$date_end."')
                )x
                left outer join 
                (
                    select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_month from 
                        (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.05.') and AcNo <> '4.02.05.11' and (CAmt - DAmt) > 0
                    and (left(DocDt,6) between LEFT('".$date_start."', 6) and LEFT('".$date_end."', 6))
                )y 
                on x.id_link = y.id_link
                left outer join 
                (
                    select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_year from 
                        (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.05.') and AcNo <> '4.02.05.11' and (CAmt - DAmt) > 0
                    and left(DocDt,4) = LEFT('".$date_end."', 4)
                )z 
                on x.id_link=z.id_link
            ");
            // return $target_pengguna_jasa;
            $target_in = DB::connection("db_target")->select("
                SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
                (
                    SELECT y.id_link, sum(x.target) as target 
                    FROM new_master_target_income x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = 5 
                    GROUP BY y.id_link
                )x
                LEFT OUTER JOIN 
                (
                    SELECT y.id_link, SUM(x.target) AS target
                    FROM new_master_target_income x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE (x.tahun between left('".$date_start."',4) and left('".$date_end."',4)) and z.id = 5 
                    GROUP BY y.id_link
                )Y
                ON x.id_link = y.id_link
                group by x.id_link,x.target,y.target
            ");
            // return $target_in;
            $target_erp = DB::connection("db_target")->select("
                SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
                (
                    SELECT y.id_link, SUM(x.target) AS target
                    FROM new_master_target_income x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = 5 and y.id_link = 4
                    GROUP BY y.id_link
                )x
                LEFT OUTER JOIN 
                (
                    SELECT y.id_link, SUM(x.target) AS target
                    FROM new_master_target_income x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE x.tahun = left('".$date_end."',4) and z.id = 5 and y.id_link = 4
                    GROUP BY y.id_link
                )Y
                ON x.id_link = y.id_link
                group by x.id_link,x.target,y.target
            ");
            // return $target_income_non;
            for ($i=0; $i <= 2; $i++) {
                if($i <= 1){
                    $tabel_income[$i]['id_link'] = $ticketing[$i]->id_link;
                    $tabel_income[$i]['nama'] = $ticketing[$i]->deskripsi;
                    $tabel_income[$i]['aktual_d'] = $ticketing[$i]->actual_nom_date;
                    $tabel_income[$i]['aktual_m'] = $ticketing[$i]->actual_nom_month;
                    if($target_in != null){
                        $tabel_income[$i]['target_m'] = $target_in[$i]->target_mountly;
                        $tabel_income[$i]['target_y'] = $target_in[$i]->target_yearly;
                    }else{
                        $tabel_income[$i]['target_m'] = 0;
                        $tabel_income[$i]['target_y'] = 0;
                    }
                    if ($tabel_income[$i]['target_m'] != 0) {
                        $tabel_income[$i]['persen_m'] = $ticketing[$i]->actual_nom_month/$target_in[$i]->target_mountly*100;
                    }else{
                        $tabel_income[$i]['persen_m'] = 0;
                    }
                    $tabel_income[$i]['aktual_y'] = $ticketing[$i]->actual_nom_year;
                    if ($tabel_income[$i]['target_y'] != 0) {
                        $tabel_income[$i]['persen_y'] = $ticketing[$i]->actual_nom_year/$target_in[$i]->target_yearly*100;
                    }else{
                        $tabel_income[$i]['persen_y'] = 0;
                    }
                }
                else{
                    $tabel_income[$i]['id_link'] = $i+1;
                    $tabel_income[$i]['nama'] = $erp[0]->deskripsi;
                    $tabel_income[$i]['aktual_d'] = $erp[0]->actual_nominal_date;
                    $tabel_income[$i]['aktual_m'] = $erp[0]->actual_nominal_month;
                    if ($target_erp != null) {
                        $tabel_income[$i]['target_m'] = $target_erp[0]->target_mountly;
                    }else{
                        $tabel_income[$i]['target_m'] = 0;
                    }
                    if($tabel_income[$i]['target_m'] != 0){
                        $tabel_income[$i]['persen_m'] = $erp[0]->actual_nominal_month/$target_erp[0]->target_mountly*100;
                    }else{
                        $tabel_income[$i]['persen_m'] = 0;
                    }
                    $tabel_income[$i]['aktual_y'] = $erp[0]->actual_nominal_year;
                    if ($target_erp != null) {
                        $tabel_income[$i]['target_y'] = $target_erp[0]->target_yearly;
                    }else{
                        $tabel_income[$i]['target_y'] = 0;
                    }
                    if ($tabel_income[$i]['target_y'] != 0) {
                        $tabel_income[$i]['persen_y'] = $erp[0]->actual_nominal_year/$target_erp[0]->target_yearly*100;
                    }else{
                        $tabel_income[$i]['persen_y'] = 0;
                    }
                }
            };

            $total_income[0]['id_link'] = "kosong";
            $total_income[0]['nama'] = "kosong";
            $total_income[0]['aktual_d'] = 0;
            $total_income[0]['aktual_m'] = 0;
            $total_income[0]['target_m'] = 0;
            $total_income[0]['aktual_y'] = 0;
            $total_income[0]['target_y'] = 0;
            for ($i=0; $i <= 2; $i++) {
                $total_income[0]['aktual_d'] += $tabel_income[$i]['aktual_d'];
                $total_income[0]['aktual_m'] += $tabel_income[$i]['aktual_m'];
                $total_income[0]['target_m'] += $tabel_income[$i]['target_m'];
                $total_income[0]['aktual_y'] += $tabel_income[$i]['aktual_y'];
                $total_income[0]['target_y'] += $tabel_income[$i]['target_y'];
            };
            if ($total_income[0]['target_m'] != 0) {
                $total_income[0]['persen_m'] = $total_income[0]['aktual_m']/$total_income[0]['target_m']*100;
            }else{
                $total_income[0]['persen_m'] = 0;
            }
            if ($total_income[0]['target_y'] != 0) {
                $total_income[0]['persen_y'] = $total_income[0]['aktual_y']/$total_income[0]['target_y']*100;
            }else{
                $total_income[0]['persen_y'] = 0;
            }
            // return $tabel_income;
        // tabel income
        // DETAIL
            $detail_pj_dom = DB::connection("db_manohara")->select("
                select 1 as id_link, description as trf_name,sum(qty) as total_trx, sum(subtotal) as total_nom
                from manohara_trans_ticket 
                where (DATE_FORMAT(transaction_time, '%Y%m%d') between '".$date_start."' and '".$date_end."') and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
                group by description,id_link
            ");
            $detail_pj_asg = DB::connection("db_manohara")->select("
                select 2 as id_link, description as trf_name,sum(qty) as total_trx, sum(subtotal) as total_nom
                from manohara_trans_ticket 
                where (DATE_FORMAT(transaction_time, '%Y%m%d') between '".$date_start."' and '".$date_end."') and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
                group by description,id_link
            ");
            $detailnonpaket = DB::connection("db_erp")->select("
                select 3 as id_link,DocDt,AcNo,AcDesc as trf_name,sum(CAmt - DAmt) as total_nom  from 
                (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                where 
                left(AcNo,8) in ('4.02.05.') and AcNo <> '4.02.05.11' and (CAmt - DAmt) > 0
                and (DocDt between '".$date_start."' and '".$date_end."') 
                group by id_link, AcDesc
                order by AcNo
            ");
            // return $detailnonpaket;
        // DETAIL

        $isdash = 0;
        $data = [
            'tabel_pengguna_jasa'=>$tabel_pengguna_jasa,
            'total_pengguna_jasa'=>$total_pengguna_jasa,
            'tabel_income'=>$tabel_income,
            'total_income'=>$total_income,
            'detail_pj_dom'=>$detail_pj_dom,
            'detail_pj_asg'=>$detail_pj_asg,
            'detailnonpaket'=>$detailnonpaket,
            'start'=>$start,
            'end'=>$end,
            'isdash'=>$isdash
        ];
        return view("manohara",$data);
    }
}
