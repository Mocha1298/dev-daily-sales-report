<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use DatePeriod;
use DateInterval;

class TeapenController extends Controller
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

        $tabel_pengguna_jasa = [];
        $total_pengguna_jasa = [];
        // tabel pengguna jasa
            $ticketing = DB::connection('db_ticketing')->select("
                select w.id_link,w.deskripsi,
                case when x.total_trx > 0 then x.total_trx else 0 end as actual_trx_date,
                case when y.total_trx > 0 then y.total_trx else 0 end as actual_trx_month,
                case when z.total_trx > 0 then z.total_trx else 0 end as actual_trx_year
                from
                (
                    select 6 as id_link, 'Ramayana On Air' as deskripsi
                    union all
                    select 7 as id_link, 'Ramayana Trimurti' as deskripsi
                    union all
                    select 8 as id_link, 'Roro Jonggrang' as deskripsi
                    union all
                    select 9 as id_link, 'Pepino' as deskripsi
                )w
                left outer join
                (
                    select case 
                    when a.trf_id in (933,934,935,936,939,940) then 6
                    when a.trf_id in (965,966,967,968,969,970) then 7
                    when a.trf_id in (971,972,974,975,976) then 8
                    when a.trf_id in (2399,2400,2401,2402,2403) then 9
                    end as id_link
                    ,sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') 
                    group by id_link
                )x on w.id_link = x.id_link
                left outer join
                (
                    select case 
                    when a.trf_id in (933,934,935,936,939,940) then 6
                    when a.trf_id in (965,966,967,968,969,970) then 7
                    when a.trf_id in (971,972,974,975,976) then 8
                    when a.trf_id in (2399,2400,2401,2402,2403) then 9
                    end as id_link
                    ,sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 9 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm'))
                    group by  id_link
                )y on w.id_link = y.id_link
                left outer join
                (
                    select case 
                    when a.trf_id in (933,934,935,936,939,940) then 6
                    when a.trf_id in (965,966,967,968,969,970) then 7
                    when a.trf_id in (971,972,974,975,976) then 8
                    when a.trf_id in (2399,2400,2401,2402,2403) then 9
                    end as id_link
                    ,sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 9 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') 
                    group by id_link
                )z on w.id_link = z.id_link
            ");
            $target_pj = DB::connection('db_target')->select("
                SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
                (
                    select 1 as id_link, 'Ramayana On Air' as deskripsi
                    union all
                    select 2 as id_link, 'Ramayana Trimurti' as deskripsi
                    union all
                    select 3 as id_link, 'Roro Jonggrang' as deskripsi
                    union all
                    select 4 as id_link, 'Pepino' as deskripsi
                )w
                left outer join
                (
                    SELECT y.id_link, x.target 
                    FROM new_master_target_volume x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE x.thbl = left('".$date_end."',6) and z.id = 4
                )x on w.id_link = x.id_link
                LEFT OUTER JOIN 
                (
                    SELECT y.id_link, SUM(x.target) AS target
                    FROM new_master_target_volume x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                        WHERE x.tahun = left('".$date_end."',4) and z.id = 4
                    GROUP BY y.id_link
                )Y
                ON w.id_link = y.id_link
            ");
            for ($i=0; $i <= 3; $i++) {
                $tabel_pengguna_jasa[$i]['id_link'] = $i+1;
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
            for ($i=0; $i <= 3; $i++) {
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
        // tabel income
            $income = DB::connection('db_ticketing')->select("
                select w.id_link,w.deskripsi,
                case when x.total_nom > 0 then x.total_nom else 0 end as actual_nom_date,
                case when y.total_nom > 0 then y.total_nom else 0 end as actual_nom_month,
                case when z.total_nom > 0 then z.total_nom else 0 end as actual_nom_year
                from
                (
                    select 1 as id_link, 'Ramayana On Air' as deskripsi
                    union all
                    select 2 as id_link, 'Ramayana Trimurti' as deskripsi
                    union all
                    select 3 as id_link, 'Roro Jonggrang' as deskripsi
                    union all
                    select 4 as id_link, 'Pepino' as deskripsi
                )w
                left outer join
                (
                    select case 
                    when a.trf_id in (933,934,935,936,939,940) then 1
                    when a.trf_id in (965,966,967,968,969,970) then 2
                    when a.trf_id in (971,972,974,975,976) then 3
                    when a.trf_id in (2399,2400,2401,2402,2403) then 4
                    end as id_link
                    ,sum(a.tot_nom) as total_nom
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') 
                    group by id_link
                )x on w.id_link = x.id_link
                left outer join
                (
                    select case 
                    when a.trf_id in (933,934,935,936,939,940) then 1
                    when a.trf_id in (965,966,967,968,969,970) then 2
                    when a.trf_id in (971,972,974,975,976) then 3
                    when a.trf_id in (2399,2400,2401,2402,2403) then 4
                    end as id_link
                    ,sum(a.tot_nom) as total_nom
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 9 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm'))
                    group by id_link
                )y on w.id_link = y.id_link
                left outer join
                (
                    select case 
                    when a.trf_id in (933,934,935,936,939,940) then 1
                    when a.trf_id in (965,966,967,968,969,970) then 2
                    when a.trf_id in (971,972,974,975,976) then 3
                    when a.trf_id in (2399,2400,2401,2402,2403) then 4
                    end as id_link
                    ,sum(a.tot_nom) as total_nom
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 9 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') 
                    group by id_link
                )z on w.id_link = z.id_link
            ");
            $erp = DB::connection('db_erp')->select("
                select x.id_link, x.deskripsi,COALESCE(x.actual_nominal_date,0) as actual_nominal_date,COALESCE(y.actual_nominal_month,0) as actual_nominal_month,COALESCE(z.actual_nominal_year,0) as actual_nominal_year
                from
                (
                    select 5 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_date from 
                        (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.04.') and left(AcNo,10) not in ('4.02.04.01','4.02.04.02','4.02.04.03') and (CAmt - DAmt) > 0
                    and (DocDt between '".$date_start."' and '".$date_end."')
                )x
                left outer join 
                (
                    select 5 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_month from 
                        (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.04.') and left(AcNo,10) not in ('4.02.04.01','4.02.04.02','4.02.04.03') and (CAmt - DAmt) > 0
                    and (left(DocDt,6) between LEFT('".$date_start."', 6) and LEFT('".$date_end."', 6))
                )y 
                on x.id_link = y.id_link
                left outer join 
                (
                    select 5 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_year from 
                        (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.04.') and left(AcNo,10) not in ('4.02.04.01','4.02.04.02','4.02.04.03') and (CAmt - DAmt) > 0
                    and left(DocDt,4) = LEFT('".$date_end."', 4)
                )z 
                on x.id_link=z.id_link
            ");
            $target_in = DB::connection('db_target')->select("
                SELECT w.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
                (
                    select 6 as id_link, 'Ramayana On Air' as deskripsi
                    union all
                    select 7 as id_link, 'Ramayana Trimurti' as deskripsi
                    union all
                    select 8 as id_link, 'Roro Jonggrang' as deskripsi
                    union all
                    select 9 as id_link, 'Pepino' as deskripsi
                )w
                left outer join
                (
                    SELECT y.id_link, SUM(x.target) AS target 
                    FROM new_master_target_income x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE x.thbl = left('".$date_end."',6) and z.id = 4
                    GROUP BY y.id_link
                )x on w.id_link = x.id_link
                LEFT OUTER JOIN 
                (
                    SELECT y.id_link, SUM(x.target) AS target
                    FROM new_master_target_income x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE x.tahun = left('".$date_end."',4) and z.id = 4
                    GROUP BY y.id_link
                )Y
                ON w.id_link = y.id_link
                group by w.id_link,x.target,y.target
            ");
            $target_erp = DB::connection('db_target')->select("
                SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
                (
                    SELECT y.id_link, x.target 
                    FROM new_master_target_income x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE x.thbl = left('".$date_end."',6) and z.id = 4 and y.id_link = 4
                    GROUP BY y.id_link,x.target
                )x
                LEFT OUTER JOIN 
                (
                    SELECT y.id_link, SUM(x.target) AS target
                    FROM new_master_target_income x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE x.tahun = left('".$date_end."',4) and z.id = 4 and y.id_link = 4
                    GROUP BY y.id_link,x.target
                )Y
                ON x.id_link = y.id_link
                group by x.id_link,x.target,y.target
            ");
            for ($i=0; $i <= 4; $i++) {
                if($i <= 3){
                    $tabel_income[$i]['id_link'] = $i+1;
                    $tabel_income[$i]['nama'] = $income[$i]->deskripsi;
                    $tabel_income[$i]['aktual_d'] = $income[$i]->actual_nom_date;
                    $tabel_income[$i]['aktual_m'] = $income[$i]->actual_nom_month;
                    $tabel_income[$i]['target_m'] = $target_in[$i]->target_mountly;
                    if ($tabel_income[$i]['target_m'] != 0) {
                        $tabel_income[$i]['persen_m'] = $income[$i]->actual_nom_month/$target_in[$i]->target_mountly*100;
                    }else{
                        $tabel_income[$i]['persen_m'] = 0;
                    }
                    $tabel_income[$i]['aktual_y'] = $income[$i]->actual_nom_year;
                    $tabel_income[$i]['target_y'] = $target_in[$i]->target_yearly;
                    if ($tabel_income[$i]['target_y'] != 0) {
                        $tabel_income[$i]['persen_y'] = $income[$i]->actual_nom_year/$target_in[$i]->target_yearly*100;
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
            for ($i=0; $i <=4; $i++) {
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
        // tabel income
        // DETAIL
            $detail_pj_oa = DB::connection('db_ticketing')->select("
                select a.trx_date,b.trf_name,6 as id_link
                ,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (933,934,935,936,939,940)
                group by a.trx_date,id_link,b.trf_name
            ");
            $detail_pj_rt = DB::connection('db_ticketing')->select("
                select a.trx_date,b.trf_name,7 as id_link
                ,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (965,966,967,968,969,970)
                group by a.trx_date,id_link,b.trf_name
            ");
            $detail_pj_rj = DB::connection('db_ticketing')->select("
                select a.trx_date,b.trf_name,8 as id_link
                ,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (971,972,974,975,976)
                group by a.trx_date,id_link,b.trf_name
            ");
            $detail_pj_pp = DB::connection('db_ticketing')->select("
                select a.trx_date,b.trf_name,9 as id_link
                ,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (2399,2400,2401,2402,2403)
                group by a.trx_date,id_link,b.trf_name
            ");

            $detail_in_oa = DB::connection('db_ticketing')->select("
                select a.trx_date,b.trf_name,6 as id_link
                ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (933,934,935,936,939,940)
                group by a.trx_date,id_link,b.trf_name
            ");
            $detail_in_rt = DB::connection('db_ticketing')->select("
                select a.trx_date,b.trf_name,7 as id_link
                ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (965,966,967,968,969,970)
                group by a.trx_date,id_link,b.trf_name
            ");
            $detail_in_rj = DB::connection('db_ticketing')->select("
                select a.trx_date,b.trf_name,8 as id_link
                ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (971,972,974,975,976)
                group by a.trx_date,id_link,b.trf_name
            ");
            $detail_in_pp = DB::connection('db_ticketing')->select("
                select a.trx_date,b.trf_name,9 as id_link
                ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (2399,2400,2401,2402,2403)
                group by a.trx_date,id_link,b.trf_name
            ");
            $detailnonpaket = DB::connection('db_erp')->select("
                select 5 as id_link,DocDt,AcNo,AcDesc as trf_name,sum(CAmt - DAmt) as total_nom  from 
                (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                where 
                left(AcNo,8) in ('4.02.04.') and left(AcNo,10) not in ('4.02.04.01','4.02.04.02','4.02.04.03') and (CAmt - DAmt) > 0
                and (DocDt between '".$date_start."' and '".$date_end."')
                group by id_link,DocDt,AcNo,AcDesc
                order by docdt desc
            ");
        // DETAIL
        $isdash = 0;
        $data = [
            'tabel_pengguna_jasa'=>$tabel_pengguna_jasa,
            'total_pengguna_jasa'=>$total_pengguna_jasa,
            'tabel_income'=>$tabel_income,
            'total_income'=>$total_income,
            'detail_pj_oa'=>$detail_pj_oa,
            'detail_pj_rt'=>$detail_pj_rt,
            'detail_pj_rj'=>$detail_pj_rj,
            'detail_pj_pp'=>$detail_pj_pp,
            'detail_in_oa'=>$detail_in_oa,
            'detail_in_rt'=>$detail_in_rt,
            'detail_in_rj'=>$detail_in_rj,
            'detail_in_pp'=>$detail_in_pp,
            'detailnonpaket'=>$detailnonpaket,
            'start'=>$start,
            'end'=>$end,
            'isdash'=>$isdash
        ];
        return view("teapen",$data);
    }
}
