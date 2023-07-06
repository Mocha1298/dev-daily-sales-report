<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use DatePeriod;
use DateInterval;

class BorobudurController extends Controller
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
            $pelataran_pj = DB::connection("db_goers")->select("
                select z.id_link, IFNULL(x.total,0) as actual, IFNULL(y.total,0) as mtd, IFNULL(z.total,0) as ytd
                from
                (
                    select case when ticket_name not like '%Foreigner%' then 2 
                    else 1 end as id_link,count(id) as total from trx_pelataran 
                    where (DATE_FORMAT(created_at , '%Y') between left('".$date_start."',4) and left('".$date_end."',4))
                    group by id_link
                )z 
                left outer join
                (
                    select case when ticket_name not like '%Foreigner%' then 2 
                    else 1 end as id_link,count(id) as total from trx_pelataran
                    where (DATE_FORMAT(created_at , '%Y%m') between left('".$date_start."',6) and left('".$date_end."',6))
                    group by id_link
                )y on z.id_link = y.id_link
                left outer join
                (
                    select case when ticket_name not like '%Foreigner%' then 2 
                    else 1 end as id_link,count(id) as total from trx_pelataran 
                    where (DATE_FORMAT(created_at , '%Y%m%d') between '".$date_start."' and '".$date_end."')
                    group by id_link
                )x on z.id_link = x.id_link
            ");
            $ticketing = DB::connection("db_ticketing")->select("
                select w.id_link,w.deskripsi,case when x.total_trx > 0 then x.total_trx else 0 end as actual_trx_date,
                y.total_trx as actual_trx_month, z.total_trx as actual_trx_year
                from
                (
                    select 1 as id_link, 'Domestik' as deskripsi
                    union all
                    select 2 as id_link, 'Asing' as deskripsi
                    union all
                    select 3 as id_link, 'Paket' as deskripsi
                )w
                left outer join
                (
                    select id_link ,total_trx
                    from
                    (
                        (
                            select 1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id not in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
                            group by id_link
                        )
                    )x
                )x on w.id_link = x.id_link
                left outer join
                (
                    select id_link,total_trx as total_trx
                    from
                    (
                        (
                            select 1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm')) and a.ctg_id in (4,5,6,8) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm')) and a.ctg_id not in (4,5,6,8) and (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm')) AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by id_link
                        )
                    )y
                )y on w.id_link = y.id_link
                left outer join
                (
                    select id_link,total_trx as total_trx
                    from
                    (
                        (
                            select 1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') and a.ctg_id in (4,5,6,8) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') and a.ctg_id not in (4,5,6,8) and (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by id_link
                        )
                    )z
                )z on w.id_link=z.id_link"
            );
            $tgt_naik_candi = DB::connection("db_target")->select("
                SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
                (
                    select 5 as id_link, 'Naik Candi' as deskripsi
                )w
                left outer join
                (
                    SELECT y.id_link, sum(x.target) AS target
                    FROM new_master_target_volume x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE (x.thbl BETWEEN DATE_FORMAT('".$date_start."', '%Y%m') and DATE_FORMAT('".$date_end."', '%Y%m')) and y.id_link = 5 and z.id = 1
                )x on w.id_link = x.id_link
                LEFT OUTER JOIN 
                (
                    SELECT y.id_link, SUM(x.target) AS target
                    FROM new_master_target_volume x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                        WHERE x.tahun = DATE_FORMAT('".$date_end."', '%Y') and y.id_link = 5 and z.id = 1
                    GROUP BY y.id_link
                )Y
                ON w.id_link = y.id_link
            ");
            $mtd = DB::connection("db_goers")->select("
                select z.id_link, x.total as actual, y.total as mtd, z.total as ytd
                from
                (
                select 5 as id_link,count(id) as total from trx
                where DATE_FORMAT(schedule , '%Y') = LEFT(".$date_start.",4)
                )z 
                left outer join
                (
                    select 5 as id_link,count(id) as total from trx
                    where DATE_FORMAT(schedule , '%Y%m')
                    BETWEEN LEFT(".$date_start.",6) and LEFT(".$date_end.",6)
                )y on z.id_link = y.id_link
                left outer join
                (
                    select 5 as id_link,count(id) as total from trx
                    where DATE_FORMAT(schedule,'%Y%m%d') 
                    BETWEEN ".$date_start." and ".$date_end."
                )x on z.id_link = x.id_link
            ");
            $target_pj = DB::connection("db_target")->select("
                SELECT w.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
                (
                    select 1 as id_link, 'Domestik' as deskripsi
                    union all
                    select 2 as id_link, 'Asing' as deskripsi
                    union all
                    select 3 as id_link, 'Paket' as deskripsi
                )w
                left outer join
                (
                    SELECT y.id_link, SUM(x.target) AS target
                    FROM new_master_target_volume x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = 1
                    group by y.id_link
                )x on w.id_link = x.id_link
                LEFT OUTER JOIN 
                (
                    SELECT y.id_link, SUM(x.target) AS target
                    FROM new_master_target_volume x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                        WHERE x.tahun = left('".$date_end."',4) and z.id = 1
                    GROUP BY y.id_link
                )Y
                ON w.id_link = y.id_link"
            );
            for ($i=0; $i <= 3; $i++) {
                if($i <= 1){
                    $tabel_pengguna_jasa[$i]['id_link'] = $ticketing[$i]->id_link;
                    $tabel_pengguna_jasa[$i]['nama'] = $ticketing[$i]->deskripsi;
                    $tabel_pengguna_jasa[$i]['aktual_d'] = $ticketing[$i]->actual_trx_date+$pelataran_pj[$i]->actual;
                    $tabel_pengguna_jasa[$i]['aktual_m'] = $ticketing[$i]->actual_trx_month+$pelataran_pj[$i]->mtd;
                    $tabel_pengguna_jasa[$i]['target_m'] = $target_pj[$i]->target_mountly;
                    if($tabel_pengguna_jasa[$i]['target_m'] != 0){
                        $tabel_pengguna_jasa[$i]['persen_m'] = ($tabel_pengguna_jasa[$i]['aktual_m'])/$target_pj[$i]->target_mountly*100;
                    }else{
                        $tabel_pengguna_jasa[$i]['persen_m'] = 0;
                    }
                    $tabel_pengguna_jasa[$i]['aktual_y'] = $ticketing[$i]->actual_trx_year+$pelataran_pj[$i]->ytd;
                    $tabel_pengguna_jasa[$i]['target_y'] = $target_pj[$i]->target_yearly;
                    if($tabel_pengguna_jasa[$i]['target_y'] != 0){
                        $tabel_pengguna_jasa[$i]['persen_y'] = ($tabel_pengguna_jasa[$i]['aktual_y'])/$target_pj[$i]->target_yearly*100;
                    }else{
                        $tabel_pengguna_jasa[$i]['persen_y'] = 0;
                    }
                }
                elseif($i == 2){
                    $tabel_pengguna_jasa[$i]['id_link'] = $ticketing[$i]->id_link;
                    $tabel_pengguna_jasa[$i]['nama'] = $ticketing[$i]->deskripsi;
                    $tabel_pengguna_jasa[$i]['aktual_d'] = $ticketing[$i]->actual_trx_date;
                    $tabel_pengguna_jasa[$i]['aktual_m'] = $ticketing[$i]->actual_trx_month;
                    $tabel_pengguna_jasa[$i]['target_m'] = $target_pj[$i]->target_mountly;
                    if($tabel_pengguna_jasa[$i]['target_m'] != 0){
                        $tabel_pengguna_jasa[$i]['persen_m'] = ($ticketing[$i]->actual_trx_month)/$target_pj[$i]->target_mountly*100;
                    }else{
                        $tabel_pengguna_jasa[$i]['persen_m'] = 0;
                    }
                    $tabel_pengguna_jasa[$i]['aktual_y'] = $ticketing[$i]->actual_trx_year;
                    $tabel_pengguna_jasa[$i]['target_y'] = $target_pj[$i]->target_yearly;
                    if($tabel_pengguna_jasa[$i]['target_y'] != 0){
                        $tabel_pengguna_jasa[$i]['persen_y'] = ($ticketing[$i]->actual_trx_year)/$target_pj[$i]->target_yearly*100;
                    }else{
                        $tabel_pengguna_jasa[$i]['persen_y'] = 0;
                    }
                }
                else{
                    $tabel_pengguna_jasa[$i]['id_link'] = $i+1;
                    $tabel_pengguna_jasa[$i]['nama'] = "Naik Candi";
                    $tabel_pengguna_jasa[$i]['aktual_d'] = $mtd[0]->actual;
                    $tabel_pengguna_jasa[$i]['aktual_m'] = $mtd[0]->mtd;
                    $tabel_pengguna_jasa[$i]['target_m'] = $tgt_naik_candi[0]->target_mountly;
                    if($tabel_pengguna_jasa[$i]['target_m'] != 0){
                        $tabel_pengguna_jasa[$i]['persen_m'] = $mtd[0]->mtd/$tabel_pengguna_jasa[$i]['target_m']*100;
                    }else{
                        $tabel_pengguna_jasa[$i]['persen_m'] = 0;
                    }
                    $tabel_pengguna_jasa[$i]['aktual_y'] = $mtd[0]->ytd;
                    $tabel_pengguna_jasa[$i]['target_y'] = $tgt_naik_candi[0]->target_yearly;
                    if($tabel_pengguna_jasa[$i]['target_y'] != 0){
                        $tabel_pengguna_jasa[$i]['persen_y'] = $mtd[0]->ytdr/$tgt_naik_candi[0]->target_yearly*100;
                    }else{
                        $tabel_pengguna_jasa[$i]['persen_y'] = 0;
                    }
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
        // tabel pengguna jasa

        $tabel_income = [];
        $total_income = [];

        // tabel income
            $tgt_naik_candi_i = DB::connection("db_target")->select("
                SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
                (
                    select 5 as id_link, 'Naik Candi' as deskripsi
                )w
                left outer join
                (
                    SELECT y.id_link, SUM(x.target) AS target 
                    FROM new_master_target_income x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE (x.thbl BETWEEN DATE_FORMAT('".$date_start."', '%Y%m') and DATE_FORMAT('".$date_end."', '%Y%m')) and y.id_link = 5 and z.id = 1
                )x on w.id_link = x.id_link
                LEFT OUTER JOIN 
                (
                    SELECT y.id_link, SUM(x.target) AS target
                    FROM new_master_target_income x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                        WHERE x.tahun = DATE_FORMAT('".$date_end."', '%Y') and y.id_link = 5 and z.id = 1
                    GROUP BY y.id_link
                )Y
                ON w.id_link = y.id_link
            ");
            $mtd_i = DB::connection("db_goers")->select("
                select z.id_link, x.total as actual, y.total as mtd, z.total as ytd
                from
                (
                select 5 as id_link,sum(ticket_price) as total from trx
                where DATE_FORMAT(schedule , '%Y') = DATE_FORMAT('".$date_start."' , '%Y')
                )z 
                left outer join
                (
                    select 5 as id_link,sum(ticket_price) as total from trx
                    where DATE_FORMAT(schedule , '%Y%m')
                    BETWEEN DATE_FORMAT('".$date_start."' , '%Y%m') and DATE_FORMAT('".$date_end."' , '%Y%m')
                )y on z.id_link = y.id_link
                left outer join
                (
                    select 5 as id_link,sum(ticket_price) as total from trx
                    where DATE_FORMAT(schedule,'%Y%m%d') 
                    BETWEEN '".$date_start."' and '".$date_end."'
                )x on z.id_link = x.id_link
            ");
            $income = DB::connection("db_ticketing")->select("
                        select w.id_link,w.deskripsi,case when x.total_nom > 0 then x.total_nom else 0 end as actual_nom_date,
                y.total_nom as actual_nom_month, z.total_nom as actual_nom_year
                from
                (
                    select 1 as id_link, 'Domestik' as deskripsi
                    union all
                    select 2 as id_link, 'Asing' as deskripsi
                    union all
                    select 3 as id_link, 'Paket' as deskripsi
                )w
                left outer join
                (
                    select id_link,total_nom
                    from
                    (
                        (
                            select
                            case when a.ctg_id in (4,5,6,8) then 1
                            else 2 end as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%'  
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') 
                            group by id_link
                        )
                    )x
                )x 
                on w.id_link = x.id_link
                left outer join
                (
                    select id_link,total_nom
                    from
                    (
                        (
                            select
                            case when a.ctg_id in (4,5,6,8) then 1
                            else 2 end as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$start."')::date, 'yyyymm') and TO_CHAR(('".$end."')::date, 'yyyymm')) AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' 
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$start."')::date, 'yyyymm') and TO_CHAR(('".$end."')::date, 'yyyymm')) AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') 
                            group by id_link
                        )
                    )y
                )y on w.id_link = y.id_link
                left outer join
                (
                    select id_link,total_nom
                    from
                    (
                        (
                            select
                            case when a.ctg_id in (4,5,6,8) then 1
                            else 2 end as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%'  
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') 
                            group by id_link
                        )
                    )z
                )z on w.id_link=z.id_link
            ");
            $erp = DB::connection("db_erp")->select("
                select x.id_link, x.deskripsi,COALESCE(x.actual_nominal_date,0) as actual_nominal_date,COALESCE(y.actual_nominal_month,0) as actual_nominal_month,COALESCE(z.actual_nominal_year,0) as actual_nominal_year
                from
                (
                    select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_date from 
                        (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.02.') and AcNo <> '4.02.01.12' and (CAmt - DAmt) > 0
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
                    left(AcNo,8) in ('4.02.02.') and AcNo <> '4.02.01.12' and (CAmt - DAmt) > 0
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
                    left(AcNo,8) in ('4.02.02.') and AcNo <> '4.02.01.12' and (CAmt - DAmt) > 0
                    and left(DocDt,4) = LEFT('".$date_end."', 4)
                )z 
                on x.id_link=z.id_link
            ");
            $target_income = DB::connection("db_target")->select("
                SELECT w.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
                (
                    select 1 as id_link, 'Domestik' as deskripsi
                    union all
                    select 2 as id_link, 'Asing' as deskripsi
                    union all
                    select 3 as id_link, 'Paket' as deskripsi
                )w
                left outer join
                (
                    SELECT y.id_link, SUM(x.target) AS target 
                    FROM new_master_target_income x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = 1
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
                    WHERE x.tahun = left('".$date_end."',4) and z.id = 1
                    GROUP BY y.id_link
                )Y
                ON w.id_link = y.id_link
                group by w.id_link,x.target,y.target
            ");
            $target_erp = DB::connection("db_target")->select("
                SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
                (
                    SELECT y.id_link, x.target 
                    FROM new_master_target_income x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = 1 and y.id_link = 4
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
                    WHERE x.tahun = left('".$date_end."',4) and z.id = 1 and y.id_link = 4
                    GROUP BY y.id_link,x.target
                )Y
                ON x.id_link = y.id_link
                group by x.id_link,x.target,y.target
            ");
            $pelataran_in = DB::connection("db_goers")->select("
                select z.id_link, IFNULL(x.total,0) as actual, IFNULL(y.total,0) as mtd, IFNULL(z.total,0) as ytd
                from
                (
                    select case when ticket_name not like '%Foreigner%' then 2 
                    else 1 end as id_link,sum(ticket_price) as total from trx_pelataran 
                    where (DATE_FORMAT(created_at , '%Y') between left('".$date_start."',4) and left('".$date_end."',4))
                    group by id_link
                )z 
                left outer join
                (
                    select case when ticket_name not like '%Foreigner%' then 2 
                    else 1 end as id_link,sum(ticket_price) as total from trx_pelataran 
                    where (DATE_FORMAT(created_at , '%Y%m') between left('".$date_start."',6) and left('".$date_end."',6))
                    group by id_link
                )y on z.id_link = y.id_link
                left outer join
                (
                    select case when ticket_name not like '%Foreigner%' then 2 
                    else 1 end as id_link,sum(ticket_price) as total from trx_pelataran 
                    where (DATE_FORMAT(created_at , '%Y%m%d') between '".$date_start."' and '".$date_end."')
                    group by id_link
                )x on z.id_link = x.id_link
            ");
            for ($i=0; $i <= 4; $i++) {
                if($i < 2){
                    $tabel_income[$i]['id_link'] = $income[$i]->id_link;
                    $tabel_income[$i]['nama'] = $income[$i]->deskripsi;
                    $tabel_income[$i]['aktual_d'] = $income[$i]->actual_nom_date+$pelataran_in[$i]->actual;
                    $tabel_income[$i]['aktual_m'] = $income[$i]->actual_nom_month+$pelataran_in[$i]->mtd;
                    $tabel_income[$i]['target_m'] = $target_income[$i]->target_mountly;
                    if($tabel_income[$i]['target_m'] != 0){
                        $tabel_income[$i]['persen_m'] = ($tabel_income[$i]['aktual_m'])/$target_income[$i]->target_mountly*100;
                    }else{
                        $tabel_income[$i]['persen_m'] = 0;
                    }
                    $tabel_income[$i]['aktual_y'] = $income[$i]->actual_nom_year+$pelataran_in[$i]->ytd;
                    $tabel_income[$i]['target_y'] = $target_income[$i]->target_yearly;
                    if($tabel_income[$i]['target_y'] != 0){
                        $tabel_income[$i]['persen_y'] = ($tabel_income[$i]['aktual_y'])/$target_income[$i]->target_yearly*100;
                    }else{
                        $tabel_income[$i]['persen_y'] = 0;
                    }
                }
                elseif($i == 2){
                    $tabel_income[$i]['id_link'] = $income[$i]->id_link;
                    $tabel_income[$i]['nama'] = $income[$i]->deskripsi;
                    $tabel_income[$i]['aktual_d'] = $income[$i]->actual_nom_date;
                    $tabel_income[$i]['aktual_m'] = $income[$i]->actual_nom_month;
                    $tabel_income[$i]['target_m'] = $target_income[$i]->target_mountly;
                    if($tabel_income[$i]['target_m'] != 0){
                        $tabel_income[$i]['persen_m'] = ($income[$i]->actual_nom_month)/$target_income[$i]->target_mountly*100;
                    }else{
                        $tabel_income[$i]['persen_m'] = 0;
                    }
                    $tabel_income[$i]['aktual_y'] = $income[$i]->actual_nom_year;
                    $tabel_income[$i]['target_y'] = $target_income[$i]->target_yearly;
                    if($tabel_income[$i]['target_y'] != 0){
                        $tabel_income[$i]['persen_y'] = ($income[$i]->actual_nom_year)/$target_income[$i]->target_yearly*100;
                    }else{
                        $tabel_income[$i]['persen_y'] = 0;
                    }
                }
                elseif($i == 3){
                    $tabel_income[$i]['id_link'] = $i+1;
                    $tabel_income[$i]['nama'] = "Naik Candi";
                    $tabel_income[$i]['aktual_d'] = $mtd_i[0]->actual;
                    $tabel_income[$i]['aktual_m'] = $mtd_i[0]->mtd;
                    $tabel_income[$i]['target_m'] = $tgt_naik_candi_i[0]->target_mountly;
                    if($tabel_income[$i]['target_m'] != 0){
                        $tabel_income[$i]['persen_m'] = $mtd_i[0]->mtd/$tabel_income[$i]['target_m']*100;
                    }else{
                        $tabel_income[$i]['persen_m'] = 0;
                    }
                    $tabel_income[$i]['aktual_y'] = $mtd_i[0]->ytd;
                    $tabel_income[$i]['target_y'] = $tgt_naik_candi_i[0]->target_yearly;
                    if($tabel_income[$i]['target_y'] != 0){
                        $tabel_income[$i]['persen_y'] = $mtd_i[0]->ytd/$tabel_income[$i]['target_y']*100;
                    }else{
                        $tabel_income[$i]['persen_y'] = 0;
                    }
                }else{
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
            for ($i=0; $i < 4; $i++) {
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
            $detail_pj_dom = DB::connection("db_ticketing")->select("
                select 1 as id_link, b.trf_name, sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                group by b.trf_name
            ");
            $det_pelataran_pj = DB::connection("db_goers")->select("
                select case when ticket_name not like '%Foreigner%' then 2 
                    else 1 end as id_link, CONCAT('Goers ',ticket_name,' ',venue_name) as 'trf_name',count(id) as total_trx from trx_pelataran
                where (DATE_FORMAT(created_at , '%Y%m%d') between '".$date_start."' and '".$date_end."')
                group BY ticket_name,venue_name,id_link
            ");
            // return $det_pelataran_pj;
            $detail_pj_asg = DB::connection("db_ticketing")->select("
                select 2 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id not in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                group by b.trf_name
            ");
            $det_pelataran_in = DB::connection("db_goers")->select("
                select case when ticket_name not like '%Foreigner%' then 2 
                    else 1 end as id_link, CONCAT('Goers ',ticket_name,' ',venue_name) as 'trf_name',sum(ticket_price) as total_nom from trx_pelataran
                where (DATE_FORMAT(created_at , '%Y%m%d') between '".$date_start."' and '".$date_end."')
                group BY ticket_name,venue_name,id_link
            ");
            $detail_pj_pkt = DB::connection("db_ticketing")->select("
                select 3 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
                group by b.trf_name
            ");
            $detail_in_dom = DB::connection("db_ticketing")->select(
                "select 1 as id_link, b.trf_name ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id in (4,5,6,8) and a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' 
                group by b.trf_name"                                                  
            );
            $detail_in_asg = DB::connection("db_ticketing")->select(
                "select 2 as id_link, b.trf_name ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id not in (4,5,6,8) and a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%'
                group by b.trf_name"           
            );
            $detail_in_pkt = DB::connection("db_ticketing")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') 
                group by b.trf_name"          
            );
            $detailnonpaket = DB::connection("db_erp")->select(
                "select 5 as id_link,AcDesc as trf_name, sum(CAmt - DAmt) as total_nom from 
                (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                where 
                left(AcNo,8) in ('4.02.01.') and AcNo <> '4.02.01.11' and (CAmt - DAmt) > 0
                and (DocDt between '".$date_start."' and '".$date_end."') 
                group by AcDesc "										
            );
            $d_naik_candi_pj = DB::connection("db_goers")->select("
                select 4 as id_link,
                case 
                when ticket_name like '%Foreigner%' or ticket_name like '%Adult%' or ticket_name like '%Adult%' then  'Wisman' 
                when ticket_name like '%Tambahan%' or ticket_name like '%Additional%' then 'Tambahan' else 'Wisnus' end as name,count(id) as total 
                from trx
                where DATE_FORMAT(schedule , '%Y%m%d') BETWEEN  '".$date_start."' and '".$date_end."' 
                group by id_link,name
            ");
            $d_naik_candi_in = DB::connection("db_goers")->select("
                select 4 as id_link,
                case 
                when ticket_name like '%Foreigner%' or ticket_name like '%Adult%' or ticket_name like '%Adult%' then  'Wisman' 
                when ticket_name like '%Tambahan%' or ticket_name like '%Additional%' then 'Tambahan' else 'Wisnus' end as name,sum(ticket_price) as total 
                from trx
                where DATE_FORMAT(schedule , '%Y%m%d') BETWEEN  '".$date_start."' and '".$date_end."' 
                group by id_link,name
            ");
            // return $d_naik_candi;
        // DETAIL
        $isdash = 0;
        $data = [
            'tabel_pengguna_jasa'=>$tabel_pengguna_jasa,
            'total_pengguna_jasa'=>$total_pengguna_jasa,
            'tabel_income'=>$tabel_income,
            'total_income'=>$total_income,
            'detail_pj_dom'=>$detail_pj_dom,
            'det_pelataran_pj'=>$det_pelataran_pj,
            'det_pelataran_in'=>$det_pelataran_in,
            'detail_pj_asg'=>$detail_pj_asg,
            'detail_pj_pkt'=>$detail_pj_pkt,
            'detail_in_dom'=>$detail_in_dom,
            'detail_in_asg'=>$detail_in_asg,
            'detail_in_pkt'=>$detail_in_pkt,
            'detailnonpaket'=>$detailnonpaket,
            'd_naik_candi_pj'=>$d_naik_candi_pj,
            'd_naik_candi_in'=>$d_naik_candi_in,
            'start'=>$start,
            'end'=>$end,
            'isdash'=>$isdash
        ];
        return view("borobudur",$data);
    }
}
