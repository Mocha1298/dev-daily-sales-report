<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTIme;

class ReportControllerDev extends Controller
{
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
    public function dashboard($unit)
    {
        if($unit == "borobudur"){
            $unit = 1;
        }elseif($unit == "prambanan"){
            $unit = 2;
        }elseif($unit == "ratuboko"){
            $unit = 3;
        }
        elseif($unit == "tmii"){
            $unit = 4;
        }elseif($unit == "manohara"){
            $unit = 5;
        }elseif($unit == "teapen"){
            $unit = 6;
        }
        else{
            return redirect('/dev');
        }

        $tanggal = Carbon::now();//TANGGAL SEKARANG (sak jam jam e mung radinggo)

        $date = $tanggal->format('Ymd');//FORMAT TANGGAL TANPA STRIP (ERP)
        $tanggal = $tanggal->format('Y-m-d');//FORMAT TANGGAL STRIP

        $date2 = Carbon::now()->subDays(2)->format('Ymd');//TANGGAL H-2 FORMAT TANPA STRIP
        $tgl_start = $tanggal;
        $tgl_end = $tanggal;
        
        // variable spesial
            $naik_candi_act = 0;
            $naik_candi_mtd = 0;
            $naik_candi_ytd = 0;
            $naik_candi_act_i = 0;
            $naik_candi_mtd_i = 0;
            $naik_candi_ytd_i = 0;
            $tgt_naik_candi_m = 0;
            $tgt_naik_candi_y = 0;
            $tgt_naik_candi_m_i = 0;
            $tgt_naik_candi_y_i = 0;
            $acv_mtd = 0;
            $d_naik_candi = 0;
        // variable spesial


        // BOROBUDUR
        if($unit == 1){
            // naik candi section
                $tgt_naik_candi = DB::connection("mysql3")->select("
                    SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
                    (
                        select 5 as id_link, 'Naik Candi' as deskripsi
                    )w
                    left outer join
                    (
                        SELECT y.id_link, SUM(x.target) AS target
                        FROM new_master_target_volume x
                        left OUTER JOIN new_master_category Y
                        ON x.id_category = y.id
                        left outer join  new_master_unit z
                        on y.id_unit = z.id
                        WHERE (x.thbl BETWEEN DATE_FORMAT('".$date."', '%Y%m') and DATE_FORMAT('".$date."', '%Y%m')) and y.id_link = 5 and z.id = 1
                    )x on w.id_link = x.id_link
                    LEFT OUTER JOIN 
                    (
                        SELECT y.id_link, SUM(x.target) AS target
                        FROM new_master_target_volume x
                        left OUTER JOIN new_master_category Y
                        ON x.id_category = y.id
                        left outer join  new_master_unit z
                        on y.id_unit = z.id
                            WHERE x.tahun = DATE_FORMAT('".$date."', '%Y') and y.id_link = 5 and z.id = 1
                        GROUP BY y.id_link
                    )Y
                    ON w.id_link = y.id_link
                ");
                $tgt_naik_candi_m = $tgt_naik_candi[0]->target_mountly;
                $tgt_naik_candi_y = $tgt_naik_candi[0]->target_yearly;
                $tgt_naik_candi_i = DB::connection("mysql3")->select("
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
                        WHERE (x.thbl BETWEEN DATE_FORMAT('".$date."', '%Y%m') and DATE_FORMAT('".$date."', '%Y%m')) and y.id_link = 5 and z.id = 1
                    )x on w.id_link = x.id_link
                    LEFT OUTER JOIN 
                    (
                        SELECT y.id_link, SUM(x.target) AS target
                        FROM new_master_target_income x
                        left OUTER JOIN new_master_category Y
                        ON x.id_category = y.id
                        left outer join  new_master_unit z
                        on y.id_unit = z.id
                            WHERE x.tahun = DATE_FORMAT('".$date."', '%Y') and y.id_link = 5 and z.id = 1
                        GROUP BY y.id_link
                    )Y
                    ON w.id_link = y.id_link
                ");
                $tgt_naik_candi_m_i = $tgt_naik_candi_i[0]->target_mountly;
                $tgt_naik_candi_y_i = $tgt_naik_candi_i[0]->target_yearly;
                $mtd = DB::connection("mysql4")->select("
                    select z.id_link, x.total as actual, y.total as mtd, z.total as ytd
                    from
                    (
                    select 5 as id_link,count(id) as total from trx
                    where DATE_FORMAT(schedule , '%Y') = DATE_FORMAT('".$date."' , '%Y')
                    )z 
                    left outer join
                    (
                        select 5 as id_link,count(id) as total from trx
                        where DATE_FORMAT(schedule , '%Y%m')
                        BETWEEN DATE_FORMAT('".$date."' , '%Y%m') and DATE_FORMAT('".$date."' , '%Y%m')
                    )y on z.id_link = y.id_link
                    left outer join
                    (
                        select 5 as id_link,count(id) as total from trx
                        where DATE_FORMAT(schedule,'%Y%m%d') 
                        BETWEEN '".$date."' and '".$date."'
                    )x on z.id_link = x.id_link
                ");
                // return $mtd;
                // if($mtd[0]->actual > 1250){
                //     $naik_candi_act = 1250;
                // }else{
                    $naik_candi_act = $mtd[0]->actual;
                // }
                $naik_candi_mtd = $mtd[0]->mtd;
                $naik_candi_ytd = $mtd[0]->ytd;
                $mtd_i = DB::connection("mysql4")->select("
                    select z.id_link, x.total as actual, y.total as mtd, z.total as ytd
                    from
                    (
                    select 5 as id_link,sum(ticket_price) as total from trx
                    where DATE_FORMAT(schedule , '%Y') = DATE_FORMAT('".$date."' , '%Y')
                    )z 
                    left outer join
                    (
                        select 5 as id_link,sum(ticket_price) as total from trx
                        where DATE_FORMAT(schedule , '%Y') = DATE_FORMAT('".$date."' , '%Y')
                    )y on z.id_link = y.id_link
                    left outer join
                    (
                        select 5 as id_link,sum(ticket_price) as total from trx
                        where DATE_FORMAT(schedule,'%Y%m%d') 
                        BETWEEN '".$date."' and '".$date."'
                    )x on z.id_link = x.id_link
                ");
                // return $mtd_i;
                $naik_candi_act_i = $mtd_i[0]->actual;
                $naik_candi_mtd_i = $mtd_i[0]->mtd;
                $naik_candi_ytd_i = $mtd_i[0]->ytd;
                $d_naik_candi = DB::connection("mysql4")->select("
                    select 5 as id_link,DATE_FORMAT(schedule , '%Y%m%d') as date_use,
                    case 
                    when ticket_name like '%Foreigner%' or ticket_name like '%Adult%' or ticket_name like '%Adult%' then  'Wisman' 
                    when ticket_name like '%Tambahan%' or ticket_name like '%Additional%' then 'Tambahan' else 'Wisnus' end as name,count(id) as total 
                    from trx
                    where DATE_FORMAT(schedule , '%Y%m%d') BETWEEN  '".$date."' and '".$date."' 
                    group by id_link,name,date_use
                    order by date_use,name
                ");
                // return $d_naik_candi;
            // naik candi
            $ticketing = DB::connection("pgsql")->select("
                        select w.id_link,TO_CHAR(('".$tanggal."')::date, 'yyyymmdd') as trx_date,w.deskripsi,case when x.total_trx > 0 then x.total_trx else 0 end as actual_trx_date,
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
                    select trx_date,id_link ,total_trx
                    from
                    (
                        (
                            select a.trx_date, 1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND a.trx_date = ('".$tanggal."') and a.ctg_id in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date, 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND a.trx_date = ('".$tanggal."') and a.ctg_id not in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date, 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND a.trx_date = ('".$tanggal."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
                            group by a.trx_date, id_link
                        )
                    )x
                )x on w.id_link = x.id_link
                left outer join
                (
                    select thbl,id_link,total_trx as total_trx
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') and a.ctg_id in (4,5,6,8) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by thbl,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') and a.ctg_id not in (4,5,6,8) and (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                            group by thbl,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by thbl,  id_link
                        )
                    )y
                )y on w.id_link = y.id_link
                left outer join
                (
                    select tahun,id_link,total_trx as total_trx
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') and a.ctg_id in (4,5,6,8) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by tahun,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') and a.ctg_id not in (4,5,6,8) and (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                            group by tahun,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by tahun,  id_link
                        )
                    )z
                )z on w.id_link=z.id_link"
            );
            $income = DB::connection("pgsql")->select("
                        select w.id_link,TO_CHAR(('".$tanggal."')::date, 'yyyymmdd') as trx_date,w.deskripsi,case when x.total_nom > 0 then x.total_nom else 0 end as actual_nom_date,
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
                    select trx_date,id_link,total_nom
                    from
                    (
                        (
                            select a.trx_date,
                            case when a.ctg_id in (4,5,6,8) then 1
                            else 2 end as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND a.trx_date = ('".$tanggal."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%'  
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date, 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND a.trx_date = ('".$tanggal."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%')
                            group by a.trx_date, id_link
                        )
                    )x
                )x 
                on w.id_link = x.id_link
                left outer join
                (
                    select thbl,id_link,total_nom
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,
                            case when a.ctg_id in (4,5,6,8) then 1
                            else 2 end as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%'
                            group by thbl, id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl, 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%')
                            group by thbl, id_link
                        )
                    )y
                )y on w.id_link = y.id_link
                left outer join
                (
                    select tahun,id_link,total_nom
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,
                            case when a.ctg_id in (4,5,6,8) then 1
                            else 2 end as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%'
                            group by tahun, id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun, 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') 
                            group by tahun, id_link
                        )
                    )z
                )z on w.id_link=z.id_link
            ");
            $erp = DB::connection("mysql2")->select("
                            select x.id_link,'".$date."' as trx_date,x.deskripsi,COALESCE(x.actual_nominal_date,0) as actual_nominal_date,COALESCE(y.actual_nominal_month,0) as actual_nominal_month,COALESCE(z.actual_nominal_year,0) as actual_nominal_year
                from
                (
                    select 4 as id_link,DocDt,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_date from 
                        (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.02.') and AcNo <> '4.02.01.12' and (CAmt - DAmt) > 0
                    and DocDt = '".$date."'
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
                    and left(DocDt,6) = LEFT('".$date."', 6)
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
                    and left(DocDt,4) = LEFT('".$date."', 4)
                )z 
                on x.id_link=z.id_link
            ");
            // DETAIL
            $detail_pj_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name, sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND a.trx_date = ('".$tanggal."') and a.ctg_id in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                group by b.trf_name"                                             
            );
            $detail_pj_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND a.trx_date = ('".$tanggal."') and a.ctg_id not in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                group by b.trf_name"
            );
            $detail_pj_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND a.trx_date = ('".$tanggal."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
                group by b.trf_name "
            );
            $detail_in_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id in (4,5,6,8) and a.group_id = 6 AND a.trx_date = ('".$tanggal."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%'
                group by b.trf_name"                                                  
            );
            $detail_in_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id not in (4,5,6,8) and a.group_id = 6 AND a.trx_date = ('".$tanggal."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' 
                group by b.trf_name"           
            );
            $detail_in_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND a.trx_date = ('".$tanggal."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%')
                group by b.trf_name"          
            );
            $detail_in_not = DB::connection("mysql2")->select(
                "select 4 as id_link,AcDesc, sum(CAmt - DAmt) as actual_nominal_date from 
                (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                where 
                left(AcNo,8) in ('4.02.01.') and AcNo <> '4.02.01.11' and (CAmt - DAmt) > 0
                and DocDt = '".$date."' 
                group by AcDesc "										
            );
        }
        // PRAMBANAN
        if($unit == 2){
            $ticketing = DB::connection("pgsql")->select("
                select w.id_link,TO_CHAR(('".$tanggal."')::date, 'yyyymmdd') as trx_date,w.deskripsi,case when x.total_trx > 0 then x.total_trx else 0 end as actual_trx_date,
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
                    select trx_date,id_link ,total_trx
                    from
                    (
                        (
                            select a.trx_date, 1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND a.trx_date = ('".$tanggal."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date, 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND a.trx_date = ('".$tanggal."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date, 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND a.trx_date = ('".$tanggal."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
                            group by a.trx_date, id_link
                        )
                    )x
                )x on w.id_link = x.id_link
                left outer join
                (
                    select thbl,id_link,total_trx as total_trx
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by thbl,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by thbl,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by thbl,  id_link
                        )
                    )y
                )y on w.id_link = y.id_link
                left outer join
                (
                    select tahun,id_link,total_trx as total_trx
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by tahun,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by tahun,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by tahun,  id_link
                        )
                    )z
                )z on w.id_link = z.id_link"
            );
            $income = DB::connection("pgsql")->select("
                select w.id_link,TO_CHAR(('".$tanggal."')::date, 'yyyymmdd') as trx_date,w.deskripsi,case when x.total_nom > 0 then x.total_nom else 0 end as actual_nom_date,
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
                    select trx_date,id_link,total_nom
                    from
                    (
                        (
                            select a.trx_date,
                            case when a.ctg_id in (4,5,6) then 1
                            else 2 end as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND a.trx_date = ('".$tanggal."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date, 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND a.trx_date = ('".$tanggal."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
                            group by a.trx_date, id_link
                        )
                    )x
                )x 
                on w.id_link = x.id_link
                left outer join
                (
                    select thbl,id_link,total_nom
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,
                            case when a.ctg_id in (4,5,6) then 1
                            else 2 end as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                            group by thbl, id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl, 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
                            group by thbl, id_link
                        )
                    )y
                )y on w.id_link = y.id_link
                left outer join
                (
                    select tahun,id_link,total_nom
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,
                            case when a.ctg_id in (4,5,6) then 1
                            else 2 end as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                            group by tahun, id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun, 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
                            group by tahun, id_link
                        )
                    )z
                )z on w.id_link=z.id_link
            ");
            $erp = DB::connection("mysql2")->select("
                            select x.id_link,'".$date."' as trx_date,x.deskripsi,COALESCE(x.actual_nominal_date,0) as actual_nominal_date,COALESCE(y.actual_nominal_month,0) as actual_nominal_month,COALESCE(z.actual_nominal_year,0) as actual_nominal_year
                from
                (
                    select 4 as id_link,DocDt,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_date from 
                        (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.02.') and AcNo <> '4.02.01.12' and (CAmt - DAmt) > 0
                    and DocDt = '".$date."'
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
                    and left(DocDt,6) = LEFT('".$date."', 6)
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
                    and left(DocDt,4) = LEFT('".$date."', 4)
                )z 
                on x.id_link=z.id_link
            ");

            // DETAIL
            $detail_pj_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND a.trx_date = ('".$tanggal."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                group by b.trf_name "										
            );
            $detail_pj_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND a.trx_date = ('".$tanggal."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                group by b.trf_name "										
            );
            $detail_pj_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND a.trx_date = ('".$tanggal."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
                group by b.trf_name "										
            );
            $detail_in_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id in (4,5,6) and a.group_id = 4 AND a.trx_date = ('".$tanggal."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                group by b.trf_name"										
            );
            $detail_in_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id not in (4,5,6) and a.group_id = 4 AND a.trx_date = ('".$tanggal."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "										
            );
            $detail_in_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND a.trx_date = ('".$tanggal."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
                group by b.trf_name "										
            );
            $detail_in_not = DB::connection("mysql2")->select(
                "select 4 as id_link,AcDesc, sum(CAmt - DAmt) as actual_nominal_date from 
                (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                where 
                left(AcNo,8) in ('4.02.02.') and AcNo <> '4.02.01.12' and (CAmt - DAmt) > 0
                and DocDt = '".$date."'
                group by AcDesc"										
            );
        }
        // RATUBOKO
        if($unit == 3){
            $ticketing = DB::connection("pgsql")->select("
                select w.id_link,TO_CHAR(('".$tanggal."')::date, 'yyyymmdd') as trx_date,w.deskripsi,case when x.total_trx > 0 then x.total_trx else 0 end as actual_trx_date,
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
                    select trx_date,id_link ,total_trx
                    from
                    (
                        (
                            select a.trx_date, 1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 5 AND a.trx_date = ('".$tanggal."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date, 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 5 AND a.trx_date = ('".$tanggal."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date,3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.trx_date = ('".$tanggal."') AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
                            group by a.trx_date, id_link
                        )
                    )x
                )x on w.id_link = x.id_link
                left outer join
                (
                    select thbl,id_link,sum(total_trx) as total_trx 
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                            group by thbl,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                            group by thbl,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by thbl,  id_link
                            union all 
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id in (4,6) and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') AND b.trf_name like '%Boko%'
                            group by thbl,  id_link
                        )
                    )y
                    group by thbl,id_link
                )y on w.id_link = y.id_link
                left outer join
                (
                    select tahun,id_link,sum(total_trx) as total_trx
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by tahun,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by tahun,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by tahun,  id_link
                            union all 
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id in (4,6) and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') AND b.trf_name like '%Boko%'
                            group by tahun,  id_link
                        )
                    )z
                    group by tahun,id_link
                )z on w.id_link=z.id_link"
            );
            $income = DB::connection("pgsql")->select("
                        select w.id_link,TO_CHAR(('".$tanggal."')::date, 'yyyymmdd') as trx_date,w.deskripsi,case when x.total_nom > 0 then x.total_nom else 0 end as actual_nom_date,
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
                    select trx_date,id_link ,total_nom
                    from
                    (
                        (
                            select a.trx_date, 1 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 5 AND a.trx_date = ('".$tanggal."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date, 2 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 5 AND a.trx_date = ('".$tanggal."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date,3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.trx_date = ('".$tanggal."') AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
                            group by a.trx_date, id_link
                        )
                    )x
                )x on w.id_link = x.id_link
                left outer join
                (
                    select thbl,id_link,sum(total_nom) as total_nom 
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,1 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                            group by thbl,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,2 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                            group by thbl,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by thbl,  id_link
                            union all 
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id in (4,6) and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$tanggal."')::date, 'yyyymm') AND b.trf_name like '%Boko%'
                            group by thbl,  id_link
                        )
                    )y
                    group by thbl,id_link
                )y on w.id_link = y.id_link
                left outer join
                (
                    select tahun,id_link,sum(total_nom) as total_nom
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,1 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by tahun,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,2 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by tahun,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by tahun,  id_link
                            union all 
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id in (4,6) and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tanggal."')::date, 'yyyy') AND b.trf_name like '%Boko%'
                            group by tahun,  id_link
                        )
                    )z
                    group by tahun,id_link
                )z on w.id_link=z.id_link
            ");
            $erp = DB::connection("mysql2")->select("
                            select x.id_link,'".$date."' as trx_date,x.deskripsi,COALESCE(x.actual_nominal_date,0) as actual_nominal_date,COALESCE(y.actual_nominal_month,0) as actual_nominal_month,COALESCE(z.actual_nominal_year,0) as actual_nominal_year
                from
                (
                    select 4 as id_link,'".$date."' as DocDt,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_date from 
                    (
                        select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                    (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                        where 
                        left(AcNo,8) in ('4.02.03.') and AcNo not in ('4.02.01.07','4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                        and DocDt =  '".$date."'
                        union all
                        select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                    (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                        where 
                        AcNo in ('4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                        and DocDt = '".$date2."' 
                    )x
                )x
                left outer join 
                (
                    select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_month from 
                    (
                        select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                    (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                        where 
                        left(AcNo,8) in ('4.02.03.') and AcNo not in ('4.02.01.07','4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                        and left(DocDt,6) = left('".$date."',6) 
                        union all
                        select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                    (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                        where 
                        AcNo in ('4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                        and left(DocDt,6) = left('".$date2."',6) 
                    )x
                )y 
                on x.id_link = y.id_link
                left outer join 
                (
                    select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_year from 
                    (
                        select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                    (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                        where 
                        left(AcNo,8) in ('4.02.03.') and AcNo not in ('4.02.01.07','4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                        and left(DocDt,4) = left('".$date."',4) 
                        union all
                        select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                    (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                        where 
                        AcNo in ('4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                        and left(DocDt,4) = left('".$date2."',4)
                    )x
                )z 
                on x.id_link=z.id_link
            ");
            // DETAIL
            $detail_pj_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name ,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 5 AND a.trx_date = ('".$tanggal."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "                                               
            );
            $detail_pj_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 5 AND a.trx_date = ('".$tanggal."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "
            );
            $detail_pj_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.trx_date = ('".$tanggal."') AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
                group by b.trf_name "
            );
            $detail_in_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 5 AND a.trx_date = ('".$tanggal."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "
            );
            $detail_in_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 5 AND a.trx_date = ('".$tanggal."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "
            );
            $detail_in_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.trx_date = ('".$tanggal."') AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
                group by b.trf_name "
            );
            $detail_in_not = DB::connection("mysql2")->select(
                "select 4 as id_link,AcDesc, sum(CAmt - DAmt) as actual_nominal_date from 
                (
                    select 4 as id_link,AcNo,AcDesc,CAmt,DAmt from 
                                    (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.03.') and AcNo not in ('4.02.01.07','4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                    and DocDt = '".$date."' 
                    union all
                    select 4 as id_link,AcNo,AcDesc,CAmt,DAmt from 
                                    (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                    where 
                    AcNo in ('4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                    and DocDt = '".$date2."' 
                )x
                group by AcDesc"										
            );
        }
        // TMII
        if($unit == 4){
            $ticketing = DB::connection('pgsql')->select("
                select w.id_link,TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymmdd') as trx_date,w.deskripsi,
                case when x.total_trx > 0 then x.total_trx else 0 end as actual_trx_date,
                case when y.total_trx > 0 then y.total_trx else 0 end as actual_trx_month,
                case when z.total_trx > 0 then z.total_trx else 0 end as actual_trx_year
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
                    /versi date/
                    select trx_date,id_link ,total_trx
                    from
                    (
                        (
                            select a.trx_date, 1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 26 AND a.trx_date = (CURRENT_DATE - 1) and a.ctg_id in (4,5,6) AND b.trf_trfftype_id NOT IN (2) 
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date, 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 26 AND a.trx_date = (CURRENT_DATE - 1) and a.ctg_id not in (4,5,6) AND b.trf_trfftype_id NOT IN (2) 
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date, 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 26 AND a.trx_date = (CURRENT_DATE - 1) AND b.trf_trfftype_id IN (2)
                            group by a.trx_date, id_link
                        )
                    )x
                )x on w.id_link = x.id_link
                left outer join
                (
                    /versi month/
                    select thbl,id_link,total_trx as total_trx
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2)
                            group by thbl,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2)
                            group by thbl,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') AND b.trf_trfftype_id IN (2)
                            group by thbl,  id_link
                        )
                    )y
                )y on w.id_link = y.id_link
                left outer join
                (
                    /versi year/
                    select tahun,id_link,total_trx as total_trx
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) 
                            group by tahun,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2)
                            group by tahun,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') AND b.trf_trfftype_id IN (2) 
                            group by tahun,  id_link
                        )
                    )z
                )z on w.id_link = z.id_link
            ");
            $income = DB::connection('pgsql')->select("
                select w.id_link,TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymmdd') as trx_date,w.deskripsi,
                case when x.total_nom > 0 then x.total_nom else 0 end as actual_nom_date,
                case when y.total_nom > 0 then y.total_nom else 0 end as actual_nom_month,
                case when z.total_nom > 0 then z.total_nom else 0 end as actual_nom_year
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
                    /versi date/
                    select trx_date,id_link ,total_nom
                    from
                    (
                        (
                            select a.trx_date, 1 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 26 AND a.trx_date = (CURRENT_DATE - 1) and a.ctg_id in (4,5,6) AND b.trf_trfftype_id NOT IN (2) 
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date, 2 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 26 AND a.trx_date = (CURRENT_DATE - 1) and a.ctg_id not in (4,5,6) AND b.trf_trfftype_id NOT IN (2) 
                            group by a.trx_date, id_link
                        )
                        union all
                        (
                            select a.trx_date, 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 26 AND a.trx_date = (CURRENT_DATE - 1) AND b.trf_trfftype_id IN (2)
                            group by a.trx_date, id_link
                        )
                    )x
                )x on w.id_link = x.id_link
                left outer join
                (
                    /versi month/
                    select thbl,id_link,total_nom  as total_nom
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,1 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2)
                            group by thbl,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,2 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2)
                            group by thbl,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') AND b.trf_trfftype_id IN (2)
                            group by thbl,  id_link
                        )
                    )y
                )y on w.id_link = y.id_link
                left outer join
                (
                    /versi year/
                    select tahun,id_link,total_nom as total_nom
                    from
                    (
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,1 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) 
                            group by tahun,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,2 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2)
                            group by tahun,  id_link
                        )
                        union all
                        (
                            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') AND b.trf_trfftype_id IN (2) 
                            group by tahun,  id_link
                        )
                    )z
                )z on w.id_link = z.id_link
            ");
        }
        // MANOHARA
        if($unit == 5){
            $ticketing = DB::connection("pgsql")->select("
                select w.id_link,w.deskripsi,
                case when x.total_trx > 0 then x.total_trx else 0 end as actual_trx_date,
                case when y.total_trx > 0 then y.total_trx else 0 end as actual_trx_month,
                case when z.total_trx > 0 then z.total_trx else 0 end as actual_trx_year
                from
                (
                    select 1 as id_link, 'Domestik' as deskripsi
                    union all
                    select 2 as id_link, 'Asing' as deskripsi
                )w
                left outer join
                (
                    /versi date/
                    select id_link ,total_trx
                    from
                    (
                        (
                            select 1 as id_link, sum(qty) as total_trx
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y%m%d') =  '20230410' and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(qty) as total_trx
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y%m%d') =  '20230410' and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
                            group by id_link
                        )
                    )x
                )x on w.id_link = x.id_link
                left outer join
                (
                    /versi month/
                    select id_link, total_trx
                    from
                    (
                        (
                            select 1 as id_link, sum(qty) as total_trx
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y%m') =  '202304' and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(qty) as total_trx
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y%m') =  '202304' and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
                            group by id_link
                        )
                    )y
                )y on w.id_link = y.id_link
                left outer join
                (
                    /versi year/
                    select id_link,total_trx
                    from
                    (
                        (
                            select 1 as id_link, sum(qty) as total_trx
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y') =  '2023' and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(qty) as total_trx
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y') =  '2023' and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
                            group by id_link
                        )
                    )z
                )z on w.id_link = z.id_link
            ");
            $income = DB::connection("pgsql")->select("
                select w.id_link,w.deskripsi,
                case when x.total_nom > 0 then x.total_nom else 0 end as actual_nom_date,
                case when y.total_nom > 0 then y.total_nom else 0 end as actual_nom_month,
                case when z.total_nom > 0 then z.total_nom else 0 end as actual_nom_year
                from
                (
                    select 1 as id_link, 'Domestik' as deskripsi
                    union all
                    select 2 as id_link, 'Asing' as deskripsi
                )w
                left outer join
                (
                    /versi date/
                    select id_link ,total_nom
                    from
                    (
                        (
                            select 1 as id_link, sum(subtotal) as total_nom
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y%m%d') =  '20230410' and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(subtotal) as total_nom
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y%m%d') =  '20230410' and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
                            group by id_link
                        )
                    )x
                )x on w.id_link = x.id_link
                left outer join
                (
                    /versi month/
                    select id_link, total_nom
                    from
                    (
                        (
                            select 1 as id_link, sum(subtotal) as total_nom
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y%m') =  '202304' and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(subtotal) as total_nom
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y%m') =  '202304' and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
                            group by id_link
                        )
                    )y
                )y on w.id_link = y.id_link
                left outer join
                (
                    /versi year/
                    select id_link,total_nom
                    from
                    (
                        (
                            select 1 as id_link, sum(subtotal) as total_nom
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y') =  '2023' and (category = 'WISNUS' or (category = 'INHOUSE' and country = 'Indonesia'))
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(subtotal) as total_nom
                            from manohara_trans_ticket 
                            where DATE_FORMAT(transaction_time, '%Y') =  '2023' and (category = 'WISMAN' or (category = 'INHOUSE' and country <> 'Indonesia'))
                            group by id_link
                        )
                    )z
                )z on w.id_link = z.id_link
            ");
        }
        // TEAPEN
        if($unit == 6){
            $ticketing = DB::connection('pgsql')->select("
                select w.id_link,TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymmdd') as trx_date,w.deskripsi,
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
                    select a.trx_date,case 
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
                    WHERE a.group_id = 9 AND a.trx_date = (CURRENT_DATE - 1) 
                    group by a.trx_date,id_link
                )x on w.id_link = x.id_link
                left outer join
                (
                    select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,case 
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
                    where a.group_id = 9 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm')
                    group by thbl,  id_link
                )y on w.id_link = y.id_link
                left outer join
                (
                    select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,case 
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
                    where a.group_id = 9 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') 
                    group by tahun,  id_link
                )z on w.id_link = z.id_link
            ");
            $income = DB::connection('pgsql')->select("
                select w.id_link,TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymmdd') as trx_date,w.deskripsi,
                case when x.total_nom > 0 then x.total_nom else 0 end as actual_trx_date,
                case when y.total_nom > 0 then y.total_nom else 0 end as actual_trx_month,
                case when z.total_nom > 0 then z.total_nom else 0 end as actual_trx_year
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
                    select a.trx_date,case 
                    when a.trf_id in (933,934,935,936,939,940) then 6
                    when a.trf_id in (965,966,967,968,969,970) then 7
                    when a.trf_id in (971,972,974,975,976) then 8
                    when a.trf_id in (2399,2400,2401,2402,2403) then 9
                    end as id_link
                    ,sum(a.tot_nom) as total_nom
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    WHERE a.group_id = 9 AND a.trx_date = (CURRENT_DATE - 1) 
                    group by a.trx_date,id_link
                )x on w.id_link = x.id_link
                left outer join
                (
                    select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,case 
                    when a.trf_id in (933,934,935,936,939,940) then 6
                    when a.trf_id in (965,966,967,968,969,970) then 7
                    when a.trf_id in (971,972,974,975,976) then 8
                    when a.trf_id in (2399,2400,2401,2402,2403) then 9
                    end as id_link
                    ,sum(a.tot_nom) as total_nom
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 9 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm')
                    group by thbl,  id_link
                )y on w.id_link = y.id_link
                left outer join
                (
                    select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,case 
                    when a.trf_id in (933,934,935,936,939,940) then 6
                    when a.trf_id in (965,966,967,968,969,970) then 7
                    when a.trf_id in (971,972,974,975,976) then 8
                    when a.trf_id in (2399,2400,2401,2402,2403) then 9
                    end as id_link
                    ,sum(a.tot_nom) as total_nom
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 9 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') 
                    group by tahun,  id_link
                )z on w.id_link = z.id_link
            ");
            $erp = DB::connection('mysql2')->select("
                select 4 as id_link,DocDt,sum(CAmt - DAmt) as actual_nominal_date  from 
                (
                    select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                ) vwtwcjn
                where 
                left(AcNo,8) in ('4.02.04.') and left(AcNo,10) not in ('4.02.04.01','4.02.04.02','4.02.04.03') and (CAmt - DAmt) > 0
                and DocDt = '20230507'
                group by id_link,DocDt
                order by docdt desc
            ");
            $detail_pj_oa = DB::connection('pgsql')->select("
                select a.trx_date,b.trf_name,6 as id_link
                ,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND a.trx_date = (CURRENT_DATE - 2) and  a.trf_id in (933,934,935,936,939,940)
                group by a.trx_date,id_link,b.trf_name
            ");
            $detail_pj_rt = DB::connection('pgsql')->select("
                select a.trx_date,b.trf_name,7 as id_link
                ,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND a.trx_date = (CURRENT_DATE - 2) and  a.trf_id in (965,966,967,968,969,970)
                group by a.trx_date,id_link,b.trf_name
            ");
            $detail_pj_rj = DB::connection('pgsql')->select("
                select a.trx_date,b.trf_name,8 as id_link
                ,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND a.trx_date = (CURRENT_DATE - 2) and  a.trf_id in (971,972,974,975,976)
                group by a.trx_date,id_link,b.trf_name
            ");
            $detail_pj_pp = DB::connection('pgsql')->select("
                select a.trx_date,b.trf_name,9 as id_link
                ,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND a.trx_date = (CURRENT_DATE - 2) and  a.trf_id in (2399,2400,2401,2402,2403)
                group by a.trx_date,id_link,b.trf_name
            ");

            $detail_in_oa = DB::connection('pgsql')->select("
                select a.trx_date,b.trf_name,6 as id_link
                ,sum(a.tot_nom) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND a.trx_date = (CURRENT_DATE - 2) and  a.trf_id in (933,934,935,936,939,940)
                group by a.trx_date,id_link,b.trf_name
            ");
            $detail_in_rt = DB::connection('pgsql')->select("
                select a.trx_date,b.trf_name,7 as id_link
                ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND a.trx_date = (CURRENT_DATE - 2) and  a.trf_id in (965,966,967,968,969,970)
                group by a.trx_date,id_link,b.trf_name
            ");
            $detail_in_rj = DB::connection('pgsql')->select("
                select a.trx_date,b.trf_name,8 as id_link
                ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND a.trx_date = (CURRENT_DATE - 2) and  a.trf_id in (971,972,974,975,976)
                group by a.trx_date,id_link,b.trf_name
            ");
            $detail_in_pp = DB::connection('pgsql')->select("
                select a.trx_date,b.trf_name,9 as id_link
                ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND a.trx_date = (CURRENT_DATE - 2) and  a.trf_id in (2399,2400,2401,2402,2403)
                group by a.trx_date,id_link,b.trf_name
            ");
            
            $detail_in_non = DB::connection('mysql2')->select("
                select 4 as id_link,DocDt,AcNo,AcDesc,sum(CAmt - DAmt) as actual_nominal_date  from 
                (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                where 
                where 
                left(AcNo,8) in ('4.02.04.') and left(AcNo,10) not in ('4.02.04.01','4.02.04.02','4.02.04.03') and (CAmt - DAmt) > 0
                and DocDt = '20230507'
                group by id_link,DocDt,AcNo,AcDesc
                order by docdt desc
            ");

            $target_pengguna_jasa = DB::connection('mysql3')->select("
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
                    WHERE x.thbl = left('20230520',6) and z.id = 4
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
                    WHERE x.tahun = left('20230520',4) and z.id = 4
                    GROUP BY y.id_link
                )Y
                ON w.id_link = y.id_link
                group by w.id_link,x.target,y.target
            ");
            $target_income = DB::connection('mysql3')->select("
                SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
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
                    SELECT y.id_link, x.target 
                    FROM new_master_target_volume x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE x.thbl = left('20230520',6) and z.id = 4
                )x on w.id_link = x.id_link
                LEFT OUTER JOIN 
                (
                    SELECT y.id_link, SUM(x.target) AS target
                    FROM new_master_target_volume x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                        WHERE x.tahun = left('20230520',4) and z.id = 4
                    GROUP BY y.id_link
                )Y
                ON w.id_link = y.id_link
            ");
            $target_income_non = DB::connection('mysql3')->select("
                SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
                (
                    SELECT y.id_link, x.target 
                    FROM new_master_target_income x
                    left OUTER JOIN new_master_category Y
                    ON x.id_category = y.id
                    left outer join  new_master_unit z
                    on y.id_unit = z.id
                    WHERE x.thbl = left('20230520',6) and z.id = 4 and y.id_link = 4
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
                    WHERE x.tahun = left('20230520',4) and z.id = 4 and y.id_link = 4
                    GROUP BY y.id_link,x.target
                )Y
                ON x.id_link = y.id_link
                group by x.id_link,x.target,y.target
            ");
        }
        $target_pengguna_jasa = DB::connection("mysql3")->select("
            SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
            (
                select 1 as id_link, 'Domestik' as deskripsi
                union all
                select 2 as id_link, 'Asing' as deskripsi
                union all
                select 3 as id_link, 'Paket' as deskripsi
            )w
            left outer join
            (
                SELECT y.id_link, x.target 
                FROM new_master_target_volume x
                left OUTER JOIN new_master_category Y
                ON x.id_category = y.id
                left outer join  new_master_unit z
                on y.id_unit = z.id
                WHERE x.thbl = left('".$date."',6) and z.id = ".$unit."
            )x on w.id_link = x.id_link
            LEFT OUTER JOIN 
            (
                SELECT y.id_link, SUM(x.target) AS target
                FROM new_master_target_volume x
                left OUTER JOIN new_master_category Y
                ON x.id_category = y.id
                left outer join  new_master_unit z
                on y.id_unit = z.id
                    WHERE x.tahun = left('".$date."',4) and z.id = ".$unit."
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link"
        );
        // return $target_pengguna_jasa;
        $target_income = DB::connection("mysql3")->select("
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
                WHERE x.thbl = left('".$date."',6) and z.id = ".$unit."
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
                WHERE x.tahun = left('".$date."',4) and z.id = ".$unit."
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link
            group by w.id_link,x.target,y.target
        ");
        $target_income_non = DB::connection("mysql3")->select("
            SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
            (
                SELECT y.id_link, x.target 
                FROM new_master_target_income x
                left OUTER JOIN new_master_category Y
                ON x.id_category = y.id
                left outer join  new_master_unit z
                on y.id_unit = z.id
                WHERE x.thbl = left('".$date."',6) and z.id = ".$unit." and y.id_link = 4
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
                WHERE x.tahun = left('".$date."',4) and z.id = ".$unit." and y.id_link = 4
                GROUP BY y.id_link,x.target
            )Y
            ON x.id_link = y.id_link
            group by x.id_link,x.target,y.target
        ");

        $count = count($target_pengguna_jasa);
        $data = [
            'ticketing'=>$ticketing,
            'target_pengguna_jasa'=>$target_pengguna_jasa,
            'income'=>$income,
            'target_income'=>$target_income,
            'erp'=>$erp,
            'naik_candi_act'=>$naik_candi_act,
            'naik_candi_mtd'=>$naik_candi_mtd,
            'naik_candi_ytd'=>$naik_candi_ytd,
            'naik_candi_act_i'=>$naik_candi_act_i,
            'naik_candi_mtd_i'=>$naik_candi_mtd_i,
            'naik_candi_ytd_i'=>$naik_candi_ytd_i,
            'd_naik_candi'=>$d_naik_candi,

            'tgt_naik_candi_m'=>$tgt_naik_candi_m,
            'tgt_naik_candi_y'=>$tgt_naik_candi_y,
            'tgt_naik_candi_m_i'=>$tgt_naik_candi_m_i,
            'tgt_naik_candi_y_i'=>$tgt_naik_candi_y_i,
            'target_income_non'=>$target_income_non,
            'count'=>$count,
            'unit'=>$unit,
            'tanggal'=>$tanggal,
            'detail_pj_dom'=>$detail_pj_dom,
            'detail_pj_asg'=>$detail_pj_asg,
            'detail_pj_pkt'=>$detail_pj_pkt,
            'detail_in_dom'=>$detail_in_dom,
            'detail_in_asg'=>$detail_in_asg,
            'detail_in_pkt'=>$detail_in_pkt,
            'detail_in_not'=>$detail_in_not,
            'tgl_start'=>$tgl_start,
            'tgl_end'=>$tgl_end
        ];
        return view("report_page_dev",$data);
    }
    public function filter($unit,$tgl_start,$tgl_end)
    {
        if(date("Y",strtotime($tgl_start)) != date("Y",strtotime($tgl_end))){
            return redirect()->back()->with("gagal","tahun");
        }

        if($unit == "borobudur"){
            $unit = 1;
        }elseif($unit == "prambanan"){
            $unit = 2;
        }elseif($unit == "ratuboko"){
            $unit = 3;
        }
        else{
            return view("home");
        }

        $date_start = str_replace("-","",$tgl_start);//FORMAT FILTER TANPA STRIP (ERP)

        $date_end = str_replace("-","",$tgl_end);//FORMAT FILTER TANPA STRIP (ERP)

        $date2_start = date("Ymd",strtotime($tgl_start.' - 1 days')); //FILTER - 1
        $date2_end = date("Ymd",strtotime($tgl_end.' - 1 days')); //FILTER - 1


        // variable spesial
            $naik_candi_act = 0;
            $naik_candi_mtd = 0;
            $naik_candi_ytd = 0;
            $naik_candi_act_i = 0;
            $naik_candi_mtd_i = 0;
            $naik_candi_ytd_i = 0;
            $tgt_naik_candi_m = 0;
            $tgt_naik_candi_y = 0;
            $tgt_naik_candi_m_i = 0;
            $tgt_naik_candi_y_i = 0;
            $acv_mtd = 0;
            $d_naik_candi = 0;
        // variable spesial


        // BOROBUDUR
        if($unit == 1){
            // naik candi section
                $tgt_naik_candi = DB::connection("mysql3")->select("
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
                $tgt_naik_candi_m = $tgt_naik_candi[0]->target_mountly;
                $tgt_naik_candi_y = $tgt_naik_candi[0]->target_yearly;
                $tgt_naik_candi_i = DB::connection("mysql3")->select("
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
                $tgt_naik_candi_m_i = $tgt_naik_candi_i[0]->target_mountly;
                $tgt_naik_candi_y_i = $tgt_naik_candi_i[0]->target_yearly;
                $mtd = DB::connection("mysql4")->select("
                    select z.id_link, x.total as actual, y.total as mtd, z.total as ytd
                    from
                    (
                    select 5 as id_link,count(id) as total from trx
                    where DATE_FORMAT(schedule , '%Y') = DATE_FORMAT('".$date_start."' , '%Y')
                    )z 
                    left outer join
                    (
                        select 5 as id_link,count(id) as total from trx
                        where DATE_FORMAT(schedule , '%Y%m')
                        BETWEEN DATE_FORMAT('".$date_start."' , '%Y%m') and DATE_FORMAT('".$date_end."' , '%Y%m')
                    )y on z.id_link = y.id_link
                    left outer join
                    (
                        select 5 as id_link,count(id) as total from trx
                        where DATE_FORMAT(schedule,'%Y%m%d') 
                        BETWEEN '".$date_start."' and '".$date_end."'
                    )x on z.id_link = x.id_link
                ");
                // return $mtd;
                // if($mtd[0]->actual > 1250){
                //     $naik_candi_act = 1250;
                // }else{
                    $naik_candi_act = $mtd[0]->actual;
                // }
                $naik_candi_mtd = $mtd[0]->mtd;
                $naik_candi_ytd = $mtd[0]->ytd;
                $mtd_i = DB::connection("mysql4")->select("
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
                $naik_candi_act_i = $mtd_i[0]->actual;
                $naik_candi_mtd_i = $mtd_i[0]->mtd;
                $naik_candi_ytd_i = $mtd_i[0]->ytd;
                $d_naik_candi = DB::connection("mysql4")->select("
                    select 5 as id_link,DATE_FORMAT(schedule , '%Y%m%d') as date_use,
                    case 
                    when ticket_name like '%Foreigner%' or ticket_name like '%Adult%' or ticket_name like '%Adult%' then  'Wisman' 
                    when ticket_name like '%Tambahan%' or ticket_name like '%Additional%' then 'Tambahan' else 'Wisnus' end as name,count(id) as total 
                    from trx
                    where DATE_FORMAT(schedule , '%Y%m%d') BETWEEN  '".$date_start."' and '".$date_end."' 
                    group by id_link,name,date_use
                    order by date_use,name
                ");
            // naik candi
            $ticketing = DB::connection("pgsql")->select("
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
                            select 1 as id_link, sum(a.tot_nom) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id not in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
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
                            where a.group_id = 6 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$tgl_start."'::date, 'yyyymm')  and TO_CHAR('".$tgl_end."'::date, 'yyyymm')) and a.ctg_id in (4,5,6,8) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$tgl_start."'::date, 'yyyymm')  and TO_CHAR('".$tgl_end."'::date, 'yyyymm')) and a.ctg_id not in (4,5,6,8) and (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$tgl_start."'::date, 'yyyymm')  and TO_CHAR('".$tgl_end."'::date, 'yyyymm')) AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
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
                            where a.group_id = 6 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') and a.ctg_id in (4,5,6,8) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') and a.ctg_id not in (4,5,6,8) and (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 6 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by id_link
                        )
                    )z
                )z on w.id_link=z.id_link"
            );
            $income = DB::connection("pgsql")->select("
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
                            WHERE a.group_id = 6 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%'  
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') 
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
                            WHERE a.group_id = 6 AND (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$tgl_start."')::date, 'yyyymm') and TO_CHAR(('".$tgl_end."')::date, 'yyyymm')) AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' 
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$tgl_start."')::date, 'yyyymm') and TO_CHAR(('".$tgl_end."')::date, 'yyyymm')) AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') 
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
                            WHERE a.group_id = 6 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%'  
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 6 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') 
                            group by id_link
                        )
                    )z
                )z on w.id_link=z.id_link
            ");
            $erp = DB::connection("mysql2")->select("
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
            // DETAIL
            $detail_pj_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name, sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                group by b.trf_name"                                             
            );
            $detail_pj_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id not in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                group by b.trf_name"
            );
            $detail_pj_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
                group by b.trf_name "
            );
            $detail_in_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id in (4,5,6,8) and a.group_id = 6 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' 
                group by b.trf_name"                                                  
            );
            $detail_in_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id not in (4,5,6,8) and a.group_id = 6 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%'
                group by b.trf_name"           
            );
            $detail_in_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') 
                group by b.trf_name"          
            );
            $detail_in_not = DB::connection("mysql2")->select(
                "select 4 as id_link,AcDesc, sum(CAmt - DAmt) as actual_nominal_date from 
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
        }
        // PRAMBANAN
        if($unit == 2){
            $ticketing = DB::connection("pgsql")->select("
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
                            WHERE a.group_id = 4 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
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
                            where a.group_id = 4 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$tgl_start."'::date, 'yyyymm')  and TO_CHAR('".$tgl_end."'::date, 'yyyymm')) and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by  id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 4 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$tgl_start."'::date, 'yyyymm')  and TO_CHAR('".$tgl_end."'::date, 'yyyymm')) and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by  id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 4 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$tgl_start."'::date, 'yyyymm')  and TO_CHAR('".$tgl_end."'::date, 'yyyymm')) AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by  id_link
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
                            where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by id_link
                        )
                    )z
                )z on w.id_link = z.id_link"
            );
            $income = DB::connection("pgsql")->select("
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
                            case when a.ctg_id in (4,5,6) then 1
                            else 2 end as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
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
                            case when a.ctg_id in (4,5,6) then 1
                            else 2 end as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$tgl_start."')::date, 'yyyymm') and TO_CHAR(('".$tgl_end."')::date, 'yyyymm')) AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$tgl_start."')::date, 'yyyymm') and TO_CHAR(('".$tgl_end."')::date, 'yyyymm')) AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
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
                            case when a.ctg_id in (4,5,6) then 1
                            else 2 end as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 4 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
                            group by id_link
                        )
                    )z
                )z on w.id_link=z.id_link
            ");
            $erp = DB::connection("mysql2")->select("
                            select x.id_link,x.deskripsi,COALESCE(x.actual_nominal_date,0) as actual_nominal_date,COALESCE(y.actual_nominal_month,0) as actual_nominal_month,COALESCE(z.actual_nominal_year,0) as actual_nominal_year
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

            // DETAIL
            $detail_pj_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                group by b.trf_name "										
            );
            $detail_pj_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                group by b.trf_name "										
            );
            $detail_pj_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
                group by b.trf_name "										
            );
            $detail_in_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id in (4,5,6) and a.group_id = 4 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                group by b.trf_name"										
            );
            $detail_in_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id not in (4,5,6) and a.group_id = 4 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "										
            );
            $detail_in_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
                group by b.trf_name "										
            );
            $detail_in_not = DB::connection("mysql2")->select(
                "select 4 as id_link,AcDesc, sum(CAmt - DAmt) as actual_nominal_date from 
                (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                where 
                left(AcNo,8) in ('4.02.02.') and AcNo <> '4.02.01.12' and (CAmt - DAmt) > 0
                and (DocDt between '".$date_start."' and '".$date_end."')
                group by AcDesc"										
            );
        }
        // RATUBOKO
        if($unit == 3){
            $ticketing = DB::connection("pgsql")->select("
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
                            WHERE a.group_id = 5 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                            group by  id_link
                        )
                        union all
                        (
                            select  2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 5 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                            group by  id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
                            group by  id_link
                        )
                    )x
                )x on w.id_link = x.id_link
                left outer join
                (
                    select id_link,sum(total_trx) as total_trx 
                    from
                    (
                        (
                            select 1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$tgl_start."'::date, 'yyyymm')  and TO_CHAR('".$tgl_end."'::date, 'yyyymm')) and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                            group by  id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$tgl_start."'::date, 'yyyymm')  and TO_CHAR('".$tgl_end."'::date, 'yyyymm')) and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                            group by  id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$tgl_start."'::date, 'yyyymm')  and TO_CHAR('".$tgl_end."'::date, 'yyyymm')) AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by  id_link
                            union all 
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id in (4,6) and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$tgl_start."'::date, 'yyyymm')  and TO_CHAR('".$tgl_end."'::date, 'yyyymm')) AND b.trf_name like '%Boko%'
                            group by  id_link
                        )
                    )y
                    group by id_link
                )y on w.id_link = y.id_link
                left outer join
                (
                    select id_link,sum(total_trx) as total_trx
                    from
                    (
                        (
                            select 1 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by id_link
                            union all 
                            select 3 as id_link, sum(a.tot_trx) as total_trx
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id in (4,6) and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') AND b.trf_name like '%Boko%'
                            group by id_link
                        )
                    )z
                    group by id_link
                )z on w.id_link=z.id_link"
            );
            $income = DB::connection("pgsql")->select("
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
                    select id_link ,total_nom
                    from
                    (
                        (
                            select 1 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 5 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                            group by id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE a.group_id = 5 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                            group by id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            WHERE (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
                            group by id_link
                        )
                    )x
                )x on w.id_link = x.id_link
                left outer join
                (
                    select id_link,sum(total_nom) as total_nom 
                    from
                    (
                        (
                            select 1 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$tgl_start."')::date, 'yyyymm') and TO_CHAR(('".$tgl_end."')::date, 'yyyymm')) and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                            group by   id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$tgl_start."')::date, 'yyyymm') and TO_CHAR(('".$tgl_end."')::date, 'yyyymm')) and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                            group by   id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$tgl_start."')::date, 'yyyymm') and TO_CHAR(('".$tgl_end."')::date, 'yyyymm')) AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by   id_link
                            union all 
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id in (4,6) and (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$tgl_start."')::date, 'yyyymm') and TO_CHAR(('".$tgl_end."')::date, 'yyyymm')) AND b.trf_name like '%Boko%'
                            group by   id_link
                        )
                    )y
                    group by id_link
                )y on w.id_link = y.id_link
                left outer join
                (
                    select id_link,sum(total_nom) as total_nom
                    from
                    (
                        (
                            select 1 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by   id_link
                        )
                        union all
                        (
                            select 2 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                            group by   id_link
                        )
                        union all
                        (
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                            group by   id_link
                            union all 
                            select 3 as id_link, sum(a.tot_nom) as total_nom
                            from recap_purchase a
                            left outer join
                            master_tariff b 
                            on a.trf_id = b.trf_id
                            where a.group_id in (4,6) and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$tgl_end."')::date, 'yyyy') AND b.trf_name like '%Boko%'
                            group by   id_link
                        )
                    )z
                    group by id_link
                )z on w.id_link=z.id_link
            ");
            $erp = DB::connection("mysql2")->select("
                            select x.id_link,x.deskripsi,COALESCE(x.actual_nominal_date,0) as actual_nominal_date,COALESCE(y.actual_nominal_month,0) as actual_nominal_month,COALESCE(z.actual_nominal_year,0) as actual_nominal_year
                from
                (
                    select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_date from 
                    (
                        select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                    (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                        where 
                        left(AcNo,8) in ('4.02.03.') and AcNo not in ('4.02.01.07','4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                        and (DocDt between '".$date_start."' and '".$date_end."')
                        union all
                        select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                    (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                        where 
                        AcNo in ('4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                        and (DocDt between '".$date2_start."' and '".$date2_end."') 
                    )x
                )x
                left outer join 
                (
                    select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_month from 
                    (
                        select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                    (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                        where 
                        left(AcNo,8) in ('4.02.03.') and AcNo not in ('4.02.01.07','4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                        and (left(DocDt,6) between LEFT('".$date_start."', 6) and LEFT('".$date_end."', 6)) 
                        union all
                        select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                    (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                        where 
                        AcNo in ('4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                        and (left(DocDt,6) between LEFT('".$date2_start."', 6) and LEFT('".$date2_end."', 6))
                    )x
                )y 
                on x.id_link = y.id_link
                left outer join 
                (
                    select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_year from 
                    (
                        select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                    (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                        where 
                        left(AcNo,8) in ('4.02.03.') and AcNo not in ('4.02.01.07','4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                        and left(DocDt,4) = left('".$date_end."',4) 
                        union all
                        select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                    (
                            select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt
                            from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                        ) vwtwcjn
                        where 
                        AcNo in ('4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                        and left(DocDt,4) = left('".$date2_end."',4)
                    )x
                )z 
                on x.id_link=z.id_link
            ");
            // DETAIL
            $detail_pj_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name ,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 5 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "                                               
            );
            $detail_pj_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 5 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "
            );
            $detail_pj_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
                group by b.trf_name "
            );
            $detail_in_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 5 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "
            );
            $detail_in_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 5 AND (a.trx_date between '".$tgl_start."' and '".$tgl_end."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "
            );
            $detail_in_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE (a.trx_date between '".$tgl_start."' and '".$tgl_end."') AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
                group by b.trf_name "
            );
            $detail_in_not = DB::connection("mysql2")->select(
                "select 4 as id_link,AcDesc, sum(CAmt - DAmt) as actual_nominal_date from 
                (
                    select 4 as id_link,AcNo,AcDesc,CAmt,DAmt from 
                                    (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.03.') and AcNo not in ('4.02.01.07','4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                    and (DocDt between '".$date_start."' and '".$date_end."') 
                    union all
                    select 4 as id_link,AcNo,AcDesc,CAmt,DAmt from 
                                    (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                    where 
                    AcNo in ('4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                    and (DocDt between '".$date2_start."' and '".$date2_end."') 
                )x
                group by AcDesc"										
            );
        }
        $target_pengguna_jasa = DB::connection("mysql3")->select("
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
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit."
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
                    WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit."
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link"
        );
        $target_income = DB::connection("mysql3")->select("
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
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit."
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
                WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit."
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link
            group by w.id_link,x.target,y.target
        ");
        $target_income_non = DB::connection("mysql3")->select("
            SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
            (
                SELECT y.id_link, x.target 
                FROM new_master_target_income x
                left OUTER JOIN new_master_category Y
                ON x.id_category = y.id
                left outer join  new_master_unit z
                on y.id_unit = z.id
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit." and y.id_link = 4
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
                WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit." and y.id_link = 4
                GROUP BY y.id_link,x.target
            )Y
            ON x.id_link = y.id_link
            group by x.id_link,x.target,y.target
        ");
        $count = count($target_pengguna_jasa);
        $data = [
            'ticketing'=>$ticketing,
            'target_pengguna_jasa'=>$target_pengguna_jasa,
            'income'=>$income,
            'target_income'=>$target_income,
            'erp'=>$erp,
            'naik_candi_act'=>$naik_candi_act,
            'naik_candi_mtd'=>$naik_candi_mtd,
            'naik_candi_ytd'=>$naik_candi_ytd,
            'naik_candi_act_i'=>$naik_candi_act_i,
            'naik_candi_mtd_i'=>$naik_candi_mtd_i,
            'naik_candi_ytd_i'=>$naik_candi_ytd_i,
            'd_naik_candi'=>$d_naik_candi,

            'tgt_naik_candi_m'=>$tgt_naik_candi_m,
            'tgt_naik_candi_y'=>$tgt_naik_candi_y,
            'tgt_naik_candi_m_i'=>$tgt_naik_candi_m_i,
            'tgt_naik_candi_y_i'=>$tgt_naik_candi_y_i,
            'target_income_non'=>$target_income_non,
            'count'=>$count,
            'unit'=>$unit,
            'detail_pj_dom'=>$detail_pj_dom,
            'detail_pj_asg'=>$detail_pj_asg,
            'detail_pj_pkt'=>$detail_pj_pkt,
            'detail_in_dom'=>$detail_in_dom,
            'detail_in_asg'=>$detail_in_asg,
            'detail_in_pkt'=>$detail_in_pkt,
            'detail_in_not'=>$detail_in_not,
            'tgl_start'=>$tgl_start,
            'tgl_end'=>$tgl_end
        ];
        return view("report_page_dev",$data);
    }
    public function borobudur($start,$end)
    {
        if(date("Y",strtotime($start)) != date("Y",strtotime($end))){
            return redirect()->back()->with("gagal","tahun");
        }
        $unit = 1;
        $date_start = str_replace("-","",$start);//FORMAT FILTER TANPA STRIP (ERP)
        $date_end = str_replace("-","",$end);//FORMAT FILTER TANPA STRIP (ERP)
        $date2_start = date("Ymd",strtotime($start.' - 1 days')); //FILTER - 1
        $date2_end = date("Ymd",strtotime($end.' - 1 days')); //FILTER - 1

        // naik candi section
            $tgt_naik_candi = DB::connection("mysql3")->select("
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
            $tgt_naik_candi_m = $tgt_naik_candi[0]->target_mountly;
            $tgt_naik_candi_y = $tgt_naik_candi[0]->target_yearly;
            $tgt_naik_candi_i = DB::connection("mysql3")->select("
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
            $tgt_naik_candi_m_i = $tgt_naik_candi_i[0]->target_mountly;
            $tgt_naik_candi_y_i = $tgt_naik_candi_i[0]->target_yearly;
            $mtd = DB::connection("mysql4")->select("
                select z.id_link, x.total as actual, y.total as mtd, z.total as ytd
                from
                (
                select 5 as id_link,count(id) as total from trx
                where DATE_FORMAT(schedule , '%Y') = DATE_FORMAT('".$date_start."' , '%Y')
                )z 
                left outer join
                (
                    select 5 as id_link,count(id) as total from trx
                    where DATE_FORMAT(schedule , '%Y%m')
                    BETWEEN DATE_FORMAT('".$date_start."' , '%Y%m') and DATE_FORMAT('".$date_end."' , '%Y%m')
                )y on z.id_link = y.id_link
                left outer join
                (
                    select 5 as id_link,count(id) as total from trx
                    where DATE_FORMAT(schedule,'%Y%m%d') 
                    BETWEEN '".$date_start."' and '".$date_end."'
                )x on z.id_link = x.id_link
            ");
            $naik_candi_act = $mtd[0]->actual;
            $naik_candi_mtd = $mtd[0]->mtd;
            $naik_candi_ytd = $mtd[0]->ytd;
            $mtd_i = DB::connection("mysql4")->select("
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
            $naik_candi_act_i = $mtd_i[0]->actual;
            $naik_candi_mtd_i = $mtd_i[0]->mtd;
            $naik_candi_ytd_i = $mtd_i[0]->ytd;
            $d_naik_candi = DB::connection("mysql4")->select("
                select 5 as id_link,DATE_FORMAT(schedule , '%Y%m%d') as date_use,
                case 
                when ticket_name like '%Foreigner%' or ticket_name like '%Adult%' or ticket_name like '%Adult%' then  'Wisman' 
                when ticket_name like '%Tambahan%' or ticket_name like '%Additional%' then 'Tambahan' else 'Wisnus' end as name,count(id) as total 
                from trx
                where DATE_FORMAT(schedule , '%Y%m%d') BETWEEN  '".$date_start."' and '".$date_end."' 
                group by id_link,name,date_use
                order by date_use,name
            ");
        // naik candi
        $ticketing = DB::connection("pgsql")->select("
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
                        select 1 as id_link, sum(a.tot_nom) as total_trx
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
        $income = DB::connection("pgsql")->select("
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
        $erp = DB::connection("mysql2")->select("
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
        // DETAIL
            $detail_pj_dom = DB::connection("pgsql")->select("
                select 1 as id_link, b.trf_name, sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                group by b.trf_name
            ");
            $detail_pj_asg = DB::connection("pgsql")->select("
                select 2 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id not in (4,5,6,8) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%')
                group by b.trf_name
            ");
            $detail_pj_pkt = DB::connection("pgsql")->select("
                select 3 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
                group by b.trf_name
            ");
            $detail_in_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id in (4,5,6,8) and a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' 
                group by b.trf_name"                                                  
            );
            $detail_in_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id not in (4,5,6,8) and a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%'
                group by b.trf_name"           
            );
            $detail_in_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 6 AND (a.trx_date between '".$start."' and '".$end."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') 
                group by b.trf_name"          
            );
            $detail_in_not = DB::connection("mysql2")->select(
                "select 4 as id_link,AcDesc, sum(CAmt - DAmt) as actual_nominal_date from 
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
        // DETAIL
        $target_pengguna_jasa = DB::connection("mysql3")->select("
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
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit."
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
                    WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit."
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link"
        );
        $target_income = DB::connection("mysql3")->select("
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
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit."
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
                WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit."
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link
            group by w.id_link,x.target,y.target
        ");
        $target_income_non = DB::connection("mysql3")->select("
            SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
            (
                SELECT y.id_link, x.target 
                FROM new_master_target_income x
                left OUTER JOIN new_master_category Y
                ON x.id_category = y.id
                left outer join  new_master_unit z
                on y.id_unit = z.id
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit." and y.id_link = 4
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
                WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit." and y.id_link = 4
                GROUP BY y.id_link,x.target
            )Y
            ON x.id_link = y.id_link
            group by x.id_link,x.target,y.target
        ");
        $count = count($target_pengguna_jasa);
        $isdash = 0;
        $data = [
            'ticketing'=>$ticketing,
            'target_pengguna_jasa'=>$target_pengguna_jasa,
            'income'=>$income,
            'target_income'=>$target_income,
            'erp'=>$erp,
            'naik_candi_act'=>$naik_candi_act,
            'naik_candi_mtd'=>$naik_candi_mtd,
            'naik_candi_ytd'=>$naik_candi_ytd,
            'naik_candi_act_i'=>$naik_candi_act_i,
            'naik_candi_mtd_i'=>$naik_candi_mtd_i,
            'naik_candi_ytd_i'=>$naik_candi_ytd_i,
            'd_naik_candi'=>$d_naik_candi,

            'tgt_naik_candi_m'=>$tgt_naik_candi_m,
            'tgt_naik_candi_y'=>$tgt_naik_candi_y,
            'tgt_naik_candi_m_i'=>$tgt_naik_candi_m_i,
            'tgt_naik_candi_y_i'=>$tgt_naik_candi_y_i,
            'target_income_non'=>$target_income_non,
            'count'=>$count,
            'unit'=>$unit,
            'detail_pj_dom'=>$detail_pj_dom,
            'detail_pj_asg'=>$detail_pj_asg,
            'detail_pj_pkt'=>$detail_pj_pkt,
            'detail_in_dom'=>$detail_in_dom,
            'detail_in_asg'=>$detail_in_asg,
            'detail_in_pkt'=>$detail_in_pkt,
            'detail_in_not'=>$detail_in_not,
            'start'=>$start,
            'end'=>$end,
            'isdash'=>$isdash
        ];
        return view("borobudur",$data);
    }
    public function prambanan($start,$end)
    {
        if(date("Y",strtotime($start)) != date("Y",strtotime($end))){
            return redirect()->back()->with("gagal","tahun");
        }
        $unit = 2;
        $date_start = str_replace("-","",$start);//FORMAT FILTER TANPA STRIP (ERP)
        $date_end = str_replace("-","",$end);//FORMAT FILTER TANPA STRIP (ERP)
        $date2_start = date("Ymd",strtotime($start.' - 1 days')); //FILTER - 1
        $date2_end = date("Ymd",strtotime($end.' - 1 days')); //FILTER - 1
        $ticketing = DB::connection("pgsql")->select("
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
                        WHERE a.group_id = 4 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                        group by id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 4 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                        group by id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 4 AND (a.trx_date between '".$start."' and '".$end."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
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
                        where a.group_id = 4 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm')) and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                        group by  id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 4 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm')) and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                        group by  id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 4 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm')) AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                        group by  id_link
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
                        where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                        group by id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                        group by id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                        group by id_link
                    )
                )z
            )z on w.id_link = z.id_link"
        );
        $income = DB::connection("pgsql")->select("
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
                        case when a.ctg_id in (4,5,6) then 1
                        else 2 end as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 4 AND (a.trx_date between '".$start."' and '".$end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                        group by id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 4 AND (a.trx_date between '".$start."' and '".$end."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
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
                        case when a.ctg_id in (4,5,6) then 1
                        else 2 end as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 4 AND (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$start."')::date, 'yyyymm') and TO_CHAR(('".$end."')::date, 'yyyymm')) AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                        group by id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 4 AND (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$start."')::date, 'yyyymm') and TO_CHAR(('".$end."')::date, 'yyyymm')) AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
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
                        case when a.ctg_id in (4,5,6) then 1
                        else 2 end as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 4 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                        group by id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 4 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
                        group by id_link
                    )
                )z
            )z on w.id_link=z.id_link
        ");
        $erp = DB::connection("mysql2")->select("
            select x.id_link,x.deskripsi,COALESCE(x.actual_nominal_date,0) as actual_nominal_date,COALESCE(y.actual_nominal_month,0) as actual_nominal_month,COALESCE(z.actual_nominal_year,0) as actual_nominal_year
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
        // DETAIL
            $detail_pj_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                group by b.trf_name "										
            );
            $detail_pj_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                group by b.trf_name "										
            );
            $detail_pj_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND (a.trx_date between '".$start."' and '".$end."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
                group by b.trf_name "										
            );
            $detail_in_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id in (4,5,6) and a.group_id = 4 AND (a.trx_date between '".$start."' and '".$end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                group by b.trf_name"										
            );
            $detail_in_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.ctg_id not in (4,5,6) and a.group_id = 4 AND (a.trx_date between '".$start."' and '".$end."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "										
            );
            $detail_in_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND (a.trx_date between '".$start."' and '".$end."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
                group by b.trf_name "										
            );
            $detail_in_not = DB::connection("mysql2")->select(
                "select 4 as id_link,AcDesc, sum(CAmt - DAmt) as actual_nominal_date from 
                (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                where 
                left(AcNo,8) in ('4.02.02.') and AcNo <> '4.02.01.12' and (CAmt - DAmt) > 0
                and (DocDt between '".$date_start."' and '".$date_end."')
                group by AcDesc"										
            );
        // DETAIL
        $target_pengguna_jasa = DB::connection("mysql3")->select("
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
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit."
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
                    WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit."
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link"
        );
        $target_income = DB::connection("mysql3")->select("
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
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit."
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
                WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit."
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link
            group by w.id_link,x.target,y.target
        ");
        $target_income_non = DB::connection("mysql3")->select("
            SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
            (
                SELECT y.id_link, x.target 
                FROM new_master_target_income x
                left OUTER JOIN new_master_category Y
                ON x.id_category = y.id
                left outer join  new_master_unit z
                on y.id_unit = z.id
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit." and y.id_link = 4
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
                WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit." and y.id_link = 4
                GROUP BY y.id_link,x.target
            )Y
            ON x.id_link = y.id_link
            group by x.id_link,x.target,y.target
        ");
        $count = count($target_pengguna_jasa);
        $isdash = 0;
        $data = [
            'ticketing'=>$ticketing,
            'target_pengguna_jasa'=>$target_pengguna_jasa,
            'income'=>$income,
            'target_income'=>$target_income,
            'erp'=>$erp,
            'target_income_non'=>$target_income_non,
            'count'=>$count,
            'unit'=>$unit,
            'detail_pj_dom'=>$detail_pj_dom,
            'detail_pj_asg'=>$detail_pj_asg,
            'detail_pj_pkt'=>$detail_pj_pkt,
            'detail_in_dom'=>$detail_in_dom,
            'detail_in_asg'=>$detail_in_asg,
            'detail_in_pkt'=>$detail_in_pkt,
            'detail_in_not'=>$detail_in_not,
            'start'=>$start,
            'end'=>$end,
            'isdash'=>$isdash
        ];
        return view("prambanan",$data);
    }
    public function ratuboko($start,$end)
    {
        if(date("Y",strtotime($start)) != date("Y",strtotime($end))){
            return redirect()->back()->with("gagal","tahun");
        }
        $unit = 2;
        $date_start = str_replace("-","",$start);//FORMAT FILTER TANPA STRIP (ERP)
        $date_end = str_replace("-","",$end);//FORMAT FILTER TANPA STRIP (ERP)
        $date2_start = date("Ymd",strtotime($start.' - 1 days')); //FILTER - 1
        $date2_end = date("Ymd",strtotime($end.' - 1 days')); //FILTER - 1
        $ticketing = DB::connection("pgsql")->select("
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
                        WHERE a.group_id = 5 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                        group by  id_link
                    )
                    union all
                    (
                        select  2 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 5 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                        group by  id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE (a.trx_date between '".$start."' and '".$end."') AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
                        group by  id_link
                    )
                )x
            )x on w.id_link = x.id_link
            left outer join
            (
                select id_link,sum(total_trx) as total_trx 
                from
                (
                    (
                        select 1 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 5 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm')) and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                        group by  id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 5 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm')) and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                        group by  id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 5 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm')) AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                        group by  id_link
                        union all 
                        select 3 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id in (4,6) and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm')) AND b.trf_name like '%Boko%'
                        group by  id_link
                    )
                )y
                group by id_link
            )y on w.id_link = y.id_link
            left outer join
            (
                select id_link,sum(total_trx) as total_trx
                from
                (
                    (
                        select 1 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                        group by id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                        group by id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                        group by id_link
                        union all 
                        select 3 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id in (4,6) and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') AND b.trf_name like '%Boko%'
                        group by id_link
                    )
                )z
                group by id_link
            )z on w.id_link=z.id_link"
        );
        $income = DB::connection("pgsql")->select("
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
                select id_link ,total_nom
                from
                (
                    (
                        select 1 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 5 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                        group by id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 5 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                        group by id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE (a.trx_date between '".$start."' and '".$end."') AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
                        group by id_link
                    )
                )x
            )x on w.id_link = x.id_link
            left outer join
            (
                select id_link,sum(total_nom) as total_nom 
                from
                (
                    (
                        select 1 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 5 and (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$start."')::date, 'yyyymm') and TO_CHAR(('".$end."')::date, 'yyyymm')) and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                        group by   id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 5 and (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$start."')::date, 'yyyymm') and TO_CHAR(('".$end."')::date, 'yyyymm')) and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                        group by   id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 5 and (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$start."')::date, 'yyyymm') and TO_CHAR(('".$end."')::date, 'yyyymm')) AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                        group by   id_link
                        union all 
                        select 3 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id in (4,6) and (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$start."')::date, 'yyyymm') and TO_CHAR(('".$end."')::date, 'yyyymm')) AND b.trf_name like '%Boko%'
                        group by   id_link
                    )
                )y
                group by id_link
            )y on w.id_link = y.id_link
            left outer join
            (
                select id_link,sum(total_nom) as total_nom
                from
                (
                    (
                        select 1 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                        group by   id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                        group by   id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                        group by   id_link
                        union all 
                        select 3 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id in (4,6) and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') AND b.trf_name like '%Boko%'
                        group by   id_link
                    )
                )z
                group by id_link
            )z on w.id_link=z.id_link
        ");
        $erp = DB::connection("mysql2")->select("
                        select x.id_link,x.deskripsi,COALESCE(x.actual_nominal_date,0) as actual_nominal_date,COALESCE(y.actual_nominal_month,0) as actual_nominal_month,COALESCE(z.actual_nominal_year,0) as actual_nominal_year
            from
            (
                select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_date from 
                (
                    select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                (
                        select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                        from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                    ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.03.') and AcNo not in ('4.02.01.07','4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                    and (DocDt between '".$date_start."' and '".$date_end."')
                    union all
                    select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                (
                        select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                        from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                    ) vwtwcjn
                    where 
                    AcNo in ('4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                    and (DocDt between '".$date2_start."' and '".$date2_end."') 
                )x
            )x
            left outer join 
            (
                select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_month from 
                (
                    select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                (
                        select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                        from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                    ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.03.') and AcNo not in ('4.02.01.07','4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                    and (left(DocDt,6) between LEFT('".$date_start."', 6) and LEFT('".$date_end."', 6)) 
                    union all
                    select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                (
                        select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                        from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                    ) vwtwcjn
                    where 
                    AcNo in ('4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                    and (left(DocDt,6) between LEFT('".$date2_start."', 6) and LEFT('".$date2_end."', 6))
                )x
            )y 
            on x.id_link = y.id_link
            left outer join 
            (
                select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_year from 
                (
                    select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                (
                        select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                        from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                    ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.03.') and AcNo not in ('4.02.01.07','4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                    and left(DocDt,4) = left('".$date_end."',4) 
                    union all
                    select 4 as id_link,AcNo,CAmt,DAmt from 
                                                                (
                        select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt
                        from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
                    ) vwtwcjn
                    where 
                    AcNo in ('4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                    and left(DocDt,4) = left('".$date2_end."',4)
                )x
            )z 
            on x.id_link=z.id_link
        ");
        // DETAIL
            $detail_pj_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name ,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 5 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "                                               
            );
            $detail_pj_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 5 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "
            );
            $detail_pj_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_trx) as total_trx
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE (a.trx_date between '".$start."' and '".$end."') AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
                group by b.trf_name "
            );
            $detail_in_dom = DB::connection("pgsql")->select(
                "select 1 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 5 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "
            );
            $detail_in_asg = DB::connection("pgsql")->select(
                "select 2 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 5 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                group by b.trf_name "
            );
            $detail_in_pkt = DB::connection("pgsql")->select(
                "select 3 as id_link, b.trf_name,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE (a.trx_date between '".$start."' and '".$end."') AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
                group by b.trf_name "
            );
            $detail_in_not = DB::connection("mysql2")->select(
                "select 4 as id_link,AcDesc, sum(CAmt - DAmt) as actual_nominal_date from 
                (
                    select 4 as id_link,AcNo,AcDesc,CAmt,DAmt from 
                                    (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                    where 
                    left(AcNo,8) in ('4.02.03.') and AcNo not in ('4.02.01.07','4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                    and (DocDt between '".$date_start."' and '".$date_end."') 
                    union all
                    select 4 as id_link,AcNo,AcDesc,CAmt,DAmt from 
                                    (
                    select a.DocDt AS DocDt,c.AcDesc,b.AcNo AS AcNo,b.CAmt, b.DAmt 
                    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo))
                    join tblcoa c on (b.AcNo = c.AcNo))
                ) vwtwcjn
                    where 
                    AcNo in ('4.02.03.05.01','4.02.03.05.02','4.02.03.05.03') and (CAmt - DAmt) > 0
                    and (DocDt between '".$date2_start."' and '".$date2_end."') 
                )x
                group by AcDesc"										
            );
        // DETAIL
        $target_pengguna_jasa = DB::connection("mysql3")->select("
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
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit."
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
                    WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit."
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link"
        );
        $target_income = DB::connection("mysql3")->select("
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
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit."
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
                WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit."
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link
            group by w.id_link,x.target,y.target
        ");
        $target_income_non = DB::connection("mysql3")->select("
            SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
            (
                SELECT y.id_link, x.target 
                FROM new_master_target_income x
                left OUTER JOIN new_master_category Y
                ON x.id_category = y.id
                left outer join  new_master_unit z
                on y.id_unit = z.id
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit." and y.id_link = 4
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
                WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit." and y.id_link = 4
                GROUP BY y.id_link,x.target
            )Y
            ON x.id_link = y.id_link
            group by x.id_link,x.target,y.target
        ");
        $count = count($target_pengguna_jasa);
        $isdash = 0;
        $data = [
            'ticketing'=>$ticketing,
            'target_pengguna_jasa'=>$target_pengguna_jasa,
            'income'=>$income,
            'target_income'=>$target_income,
            'erp'=>$erp,
            'target_income_non'=>$target_income_non,
            'count'=>$count,
            'unit'=>$unit,
            'detail_pj_dom'=>$detail_pj_dom,
            'detail_pj_asg'=>$detail_pj_asg,
            'detail_pj_pkt'=>$detail_pj_pkt,
            'detail_in_dom'=>$detail_in_dom,
            'detail_in_asg'=>$detail_in_asg,
            'detail_in_pkt'=>$detail_in_pkt,
            'detail_in_not'=>$detail_in_not,
            'start'=>$start,
            'end'=>$end,
            'isdash'=>$isdash
        ];
        return view("ratuboko",$data);
    }
    public function tamanmini($start,$end)
    {
        if(date("Y",strtotime($start)) != date("Y",strtotime($end))){
            return redirect()->back()->with("gagal","tahun");
        }
        $unit = 2;
        $date_start = str_replace("-","",$start);//FORMAT FILTER TANPA STRIP (ERP)
        $date_end = str_replace("-","",$end);//FORMAT FILTER TANPA STRIP (ERP)
        $date2_start = date("Ymd",strtotime($start.' - 1 days')); //FILTER - 1
        $date2_end = date("Ymd",strtotime($end.' - 1 days')); //FILTER - 1

        $ticketing = DB::connection('pgsql')->select("
            select w.id_link,w.deskripsi,
            case when x.total_trx > 0 then x.total_trx else 0 end as actual_trx_date,
            case when y.total_trx > 0 then y.total_trx else 0 end as actual_trx_month,
            case when z.total_trx > 0 then z.total_trx else 0 end as actual_trx_year
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
                        WHERE a.group_id = 26 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id in (4,5,6) AND b.trf_trfftype_id NOT IN (2) 
                        group by  id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 26 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id not in (4,5,6) AND b.trf_trfftype_id NOT IN (2) 
                        group by  id_link
                    )
                    union all
                    (
                        select  3 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 26 AND (a.trx_date between '".$start."' and '".$end."') AND b.trf_trfftype_id IN (2)
                        group by  id_link
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
                        where a.group_id = 26 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm')) and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2)
                        group by  id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 26 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm')) and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2)
                        group by id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 26 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('".$start."'::date, 'yyyymm')  and TO_CHAR('".$end."'::date, 'yyyymm')) AND b.trf_trfftype_id IN (2)
                        group by  id_link
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
                        where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) 
                        group by  id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2)
                        group by id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_trx) as total_trx
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') and b.trf_trfftype_id IN (2) 
                        group by  id_link
                    )
                )z
            )z on w.id_link = z.id_link
        ");
        $income = DB::connection('pgsql')->select("
            select w.deskripsi,
            case when x.total_nom > 0 then x.total_nom else 0 end as actual_nom_date,
            case when y.total_nom > 0 then y.total_nom else 0 end as actual_nom_month,
            case when z.total_nom > 0 then z.total_nom else 0 end as actual_nom_year
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
                select id_link ,total_nom
                from
                (
                    (
                        select 1 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 26 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id in (4,5,6) AND b.trf_trfftype_id NOT IN (2) 
                        group by id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 26 AND (a.trx_date between '".$start."' and '".$end."') and a.ctg_id not in (4,5,6) AND b.trf_trfftype_id NOT IN (2) 
                        group by id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        WHERE a.group_id = 26 AND (a.trx_date between '".$start."' and '".$end."') AND b.trf_trfftype_id IN (2)
                        group by id_link
                    )
                )x
            )x on w.id_link = x.id_link
            left outer join
            (
                select id_link,total_nom  as total_nom
                from
                (
                    (
                        select 1 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 26 and (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$start."')::date, 'yyyymm') and TO_CHAR(('".$end."')::date, 'yyyymm')) and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2)
                        group by id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 26 and (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$start."')::date, 'yyyymm') and TO_CHAR(('".$end."')::date, 'yyyymm')) and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2)
                        group by id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 26 and (TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR(('".$start."')::date, 'yyyymm') and TO_CHAR(('".$end."')::date, 'yyyymm')) AND b.trf_trfftype_id IN (2)
                        group by id_link
                    )
                )y
            )y on w.id_link = y.id_link
            left outer join
            (
                select id_link,total_nom as total_nom
                from
                (
                    (
                        select 1 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) 
                        group by id_link
                    )
                    union all
                    (
                        select 2 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2)
                        group by id_link
                    )
                    union all
                    (
                        select 3 as id_link, sum(a.tot_nom) as total_nom
                        from recap_purchase a
                        left outer join
                        master_tariff b 
                        on a.trf_id = b.trf_id
                        where a.group_id = 26 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$end."')::date, 'yyyy') AND b.trf_trfftype_id IN (2) 
                        group by id_link
                    )
                )z
            )z on w.id_link = z.id_link
        ");
        $target_pengguna_jasa = DB::connection("mysql3")->select("
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
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit."
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
                    WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit."
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link"
        );
        $target_income = DB::connection("mysql3")->select("
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
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit."
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
                WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit."
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link
            group by w.id_link,x.target,y.target
        ");
        $target_income_non = DB::connection("mysql3")->select("
            SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
            (
                SELECT y.id_link, x.target 
                FROM new_master_target_income x
                left OUTER JOIN new_master_category Y
                ON x.id_category = y.id
                left outer join  new_master_unit z
                on y.id_unit = z.id
                WHERE (x.thbl between left('".$date_start."',6) and left('".$date_end."',6)) and z.id = ".$unit." and y.id_link = 4
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
                WHERE x.tahun = left('".$date_end."',4) and z.id = ".$unit." and y.id_link = 4
                GROUP BY y.id_link,x.target
            )Y
            ON x.id_link = y.id_link
            group by x.id_link,x.target,y.target
        ");
        $count = count($target_pengguna_jasa);
        $isdash = 0;
        $data = [
            'ticketing'=>$ticketing,
            'target_pengguna_jasa'=>$target_pengguna_jasa,
            'income'=>$income,
            'target_income'=>$target_income,
            'erp'=>$erp,
            'target_income_non'=>$target_income_non,
            'count'=>$count,
            'unit'=>$unit,
            'detail_pj_dom'=>$detail_pj_dom,
            'detail_pj_asg'=>$detail_pj_asg,
            'detail_pj_pkt'=>$detail_pj_pkt,
            'detail_in_dom'=>$detail_in_dom,
            'detail_in_asg'=>$detail_in_asg,
            'detail_in_pkt'=>$detail_in_pkt,
            'detail_in_not'=>$detail_in_not,
            'start'=>$start,
            'end'=>$end,
            'isdash'=>$isdash
        ];
        return view("tamanmini",$data);
    }
    public function manohara($start,$end)
    {
        
    }
    public function teapen($start,$end)
    {
        if(date("Y",strtotime($start)) != date("Y",strtotime($end))){
            return redirect()->back()->with("gagal","tahun");
        }
        $unit = 2;
        $date_start = str_replace("-","",$start);//FORMAT FILTER TANPA STRIP (ERP)
        $date_end = str_replace("-","",$end);//FORMAT FILTER TANPA STRIP (ERP)
        $date2_start = date("Ymd",strtotime($start.' - 1 days')); //FILTER - 1
        $date2_end = date("Ymd",strtotime($end.' - 1 days')); //FILTER - 1
        $ticketing = DB::connection('pgsql')->select("
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
        // return $ticketing;
        $income = DB::connection('pgsql')->select("
            select w.id_link,w.deskripsi,
            case when x.total_nom > 0 then x.total_nom else 0 end as actual_trx_date,
            case when y.total_nom > 0 then y.total_nom else 0 end as actual_trx_month,
            case when z.total_nom > 0 then z.total_nom else 0 end as actual_trx_year
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
                ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 9 AND (a.trx_date between '2023-04-01' and '2023-04-30') 
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
                ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                where a.group_id = 9 and ( TO_CHAR(a.trx_date::date, 'yyyymm') between TO_CHAR('2023-04-01'::date, 'yyyymm')  and TO_CHAR('2023-04-30'::date, 'yyyymm'))
                group by id_link
            )y on w.id_link = y.id_link
            left outer join
            (
                select case 
                when a.trf_id in (933,934,935,936,939,940) then 6
                when a.trf_id in (965,966,967,968,969,970) then 7
                when a.trf_id in (971,972,974,975,976) then 8
                when a.trf_id in (2399,2400,2401,2402,2403) then 9
                end as id_link
                ,sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                where a.group_id = 9 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('2023-04-30')::date, 'yyyy') 
                group by id_link
            )z on w.id_link = z.id_link
        ");
        // return $income;
        $erp = DB::connection('mysql2')->select("
        select x.id_link, x.deskripsi,COALESCE(x.actual_nominal_date,0) as actual_nominal_date,COALESCE(y.actual_nominal_month,0) as actual_nominal_month,COALESCE(z.actual_nominal_year,0) as actual_nominal_year
        from
        (
            select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_date from 
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
            select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_month from 
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
            select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_year from 
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
        // return $erp;
        $detail_pj_oa = DB::connection('pgsql')->select("
            select a.trx_date,b.trf_name,6 as id_link
            ,sum(a.tot_trx) as total_trx
            from recap_purchase a
            left outer join
            master_tariff b 
            on a.trf_id = b.trf_id
            WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (933,934,935,936,939,940)
            group by a.trx_date,id_link,b.trf_name
        ");
        // return $detail_pj_oa;
        $detail_pj_rt = DB::connection('pgsql')->select("
            select a.trx_date,b.trf_name,7 as id_link
            ,sum(a.tot_trx) as total_trx
            from recap_purchase a
            left outer join
            master_tariff b 
            on a.trf_id = b.trf_id
            WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (965,966,967,968,969,970)
            group by a.trx_date,id_link,b.trf_name
        ");
        $detail_pj_rj = DB::connection('pgsql')->select("
            select a.trx_date,b.trf_name,8 as id_link
            ,sum(a.tot_trx) as total_trx
            from recap_purchase a
            left outer join
            master_tariff b 
            on a.trf_id = b.trf_id
            WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (971,972,974,975,976)
            group by a.trx_date,id_link,b.trf_name
        ");
        $detail_pj_pp = DB::connection('pgsql')->select("
            select a.trx_date,b.trf_name,9 as id_link
            ,sum(a.tot_trx) as total_trx
            from recap_purchase a
            left outer join
            master_tariff b 
            on a.trf_id = b.trf_id
            WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (2399,2400,2401,2402,2403)
            group by a.trx_date,id_link,b.trf_name
        ");

        $detail_in_oa = DB::connection('pgsql')->select("
            select a.trx_date,b.trf_name,6 as id_link
            ,sum(a.tot_nom) as total_trx
            from recap_purchase a
            left outer join
            master_tariff b 
            on a.trf_id = b.trf_id
            WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (933,934,935,936,939,940)
            group by a.trx_date,id_link,b.trf_name
        ");
        $detail_in_rt = DB::connection('pgsql')->select("
            select a.trx_date,b.trf_name,7 as id_link
            ,sum(a.tot_nom) as total_nom
            from recap_purchase a
            left outer join
            master_tariff b 
            on a.trf_id = b.trf_id
            WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (965,966,967,968,969,970)
            group by a.trx_date,id_link,b.trf_name
        ");
        // return $detail_in_rt;
        $detail_in_rj = DB::connection('pgsql')->select("
            select a.trx_date,b.trf_name,8 as id_link
            ,sum(a.tot_nom) as total_nom
            from recap_purchase a
            left outer join
            master_tariff b 
            on a.trf_id = b.trf_id
            WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (971,972,974,975,976)
            group by a.trx_date,id_link,b.trf_name
        ");
        // return $detail_in_rj;
        $detail_in_pp = DB::connection('pgsql')->select("
            select a.trx_date,b.trf_name,9 as id_link
            ,sum(a.tot_nom) as total_nom
            from recap_purchase a
            left outer join
            master_tariff b 
            on a.trf_id = b.trf_id
            WHERE a.group_id = 9 AND (a.trx_date between '".$start."' and '".$end."') and  a.trf_id in (2399,2400,2401,2402,2403)
            group by a.trx_date,id_link,b.trf_name
        ");
        $detail_in_not = DB::connection('mysql2')->select("
            select 4 as id_link,DocDt,AcNo,AcDesc,sum(CAmt - DAmt) as actual_nominal_date  from 
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
        $target_pengguna_jasa = DB::connection('mysql3')->select("
            SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
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
                SELECT y.id_link, x.target 
                FROM new_master_target_volume x
                left OUTER JOIN new_master_category Y
                ON x.id_category = y.id
                left outer join  new_master_unit z
                on y.id_unit = z.id
                WHERE x.thbl = left('20230520',6) and z.id = 4
            )x on w.id_link = x.id_link
            LEFT OUTER JOIN 
            (
                SELECT y.id_link, SUM(x.target) AS target
                FROM new_master_target_volume x
                left OUTER JOIN new_master_category Y
                ON x.id_category = y.id
                left outer join  new_master_unit z
                on y.id_unit = z.id
                    WHERE x.tahun = left('20230520',4) and z.id = 4
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link
        ");
        // return $target_pengguna_jasa;
        $target_income = DB::connection('mysql3')->select("
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
                WHERE x.thbl = left('20230520',6) and z.id = 4
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
                WHERE x.tahun = left('20230520',4) and z.id = 4
                GROUP BY y.id_link
            )Y
            ON w.id_link = y.id_link
            group by w.id_link,x.target,y.target
        ");
        // return $target_income;
        $target_income_non = DB::connection('mysql3')->select("
            SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
            (
                SELECT y.id_link, x.target 
                FROM new_master_target_income x
                left OUTER JOIN new_master_category Y
                ON x.id_category = y.id
                left outer join  new_master_unit z
                on y.id_unit = z.id
                WHERE x.thbl = left('20230520',6) and z.id = 4 and y.id_link = 4
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
                WHERE x.tahun = left('20230520',4) and z.id = 4 and y.id_link = 4
                GROUP BY y.id_link,x.target
            )Y
            ON x.id_link = y.id_link
            group by x.id_link,x.target,y.target
        ");
        $count = count($target_pengguna_jasa);
        $isdash = 0;
        $data = [
            'ticketing'=>$ticketing,
            'target_pengguna_jasa'=>$target_pengguna_jasa,
            'income'=>$income,
            'target_income'=>$target_income,
            'erp'=>$erp,
            'target_income_non'=>$target_income_non,
            'count'=>$count,
            'unit'=>$unit,
            'detail_pj_oa'=>$detail_pj_oa,
            'detail_pj_rt'=>$detail_pj_rt,
            'detail_pj_rj'=>$detail_pj_rj,
            'detail_pj_pp'=>$detail_pj_pp,
            'detail_in_oa'=>$detail_in_oa,
            'detail_in_rt'=>$detail_in_rt,
            'detail_in_rj'=>$detail_in_rj,
            'detail_in_rj'=>$detail_in_rj,
            'detail_in_pp'=>$detail_in_pp,
            'detail_in_not'=>$detail_in_not,
            'start'=>$start,
            'end'=>$end,
            'isdash'=>$isdash
        ];
        return view("teapen",$data);
    }
}
