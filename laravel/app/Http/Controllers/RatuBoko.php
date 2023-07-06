<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatuBoko extends Controller
{
    public function home()
    {
        // pgsql = db_trx_twc.agent_booking
        // mysql2 = runsystemtwc.tblassetcategory
        // mysql3 = eis_twc_new.ref_target
        // DB::connection("pgsql");
        // DB::connection("mysql2");
        // DB::connection("mysql3");

        $ticketing = DB::connection("pgsql")->select("
        select w.id_link,TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymmdd') as trx_date,w.deskripsi,case when x.total_trx > 0 then x.total_trx else 0 end as actual_trx_date,
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
                    WHERE a.group_id = 5 AND a.trx_date = (CURRENT_DATE - 1) and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                    group by a.trx_date, id_link
                )
                union all
                (
                    select a.trx_date, 2 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    WHERE a.group_id = 5 AND a.trx_date = (CURRENT_DATE - 1) and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
                    group by a.trx_date, id_link
                )
                union all
                (
                       select a.trx_date,3 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    WHERE a.trx_date = (CURRENT_DATE - 1) AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
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
                    where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                    group by thbl,  id_link
                )
                union all
                (
                    select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,2 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
                    group by thbl,  id_link
                )
                union all
                (
                    select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,3 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                    group by thbl,  id_link
                    union all 
                    select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,3 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id in (4,6) and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') AND b.trf_name like '%Boko%'
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
                    where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                    group by tahun,  id_link
                )
                union all
                (
                    select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,2 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                    group by tahun,  id_link
                )
                union all
                (
                    select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,3 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                    group by tahun,  id_link
                    union all 
                    select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,3 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id in (4,6) and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') AND b.trf_name like '%Boko%'
                    group by tahun,  id_link
                )
            )z
            group by tahun,id_link
        )z on w.id_link=z.id_link"
        );
        // return $ticketing;

        $target_pengguna_jasa = DB::connection("mysql3")->select("
            SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
            (
                SELECT y.id_link, x.target 
                FROM new_master_target_volume x
                left OUTER JOIN new_master_category Y
                ON x.id_category = y.id
                left outer join  new_master_unit z
                on y.id_unit = z.id
                WHERE x.bulan = MONTH(current_date) and z.id = 3
            )x
            LEFT OUTER JOIN 
            (
                SELECT y.id_link, SUM(x.target) AS target
                FROM new_master_target_volume x
                left OUTER JOIN new_master_category Y
                ON x.id_category = y.id
                left outer join  new_master_unit z
                on y.id_unit = z.id
                WHERE x.tahun = year(current_date) and z.id = 3
                GROUP BY y.id_link, x.target
            )Y
            ON x.id_link = y.id_link
        ");
        // return $target_pengguna_jasa;

        $count = count($target_pengguna_jasa);

        $income = DB::connection("pgsql")->select("
        select w.id_link,TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymmdd') as trx_date,w.deskripsi,case when x.total_nom > 0 then x.total_nom else 0 end as actual_nom_date,
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
            WHERE a.group_id = 5 AND a.trx_date = (CURRENT_DATE - 1) and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
            group by a.trx_date, id_link
        )
        union all
        (
            select a.trx_date, 2 as id_link, sum(a.tot_nom) as total_nom
            from recap_purchase a
            left outer join
            master_tariff b 
            on a.trf_id = b.trf_id
            WHERE a.group_id = 5 AND a.trx_date = (CURRENT_DATE - 1) and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') and a.source_type not in ('B2B','OTA') 
            group by a.trx_date, id_link
        )
        union all
        (
           	select a.trx_date,3 as id_link, sum(a.tot_nom) as total_nom
			from recap_purchase a
			left outer join
			master_tariff b 
			on a.trf_id = b.trf_id
			WHERE a.trx_date = (CURRENT_DATE - 1) AND ((b.trf_trfftype_id IN (2) and b.trf_name like '%Ratu Boko%' and b.trf_name not like '%Borobudur%') or (a.group_id = 5 and b.trf_name like '%Meals%')) and a.source_type not in ('B2B','OTA')
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
			where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
			group by thbl,  id_link
        )
        union all
        (
            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,2 as id_link, sum(a.tot_nom) as total_nom
			from recap_purchase a
			left outer join
			master_tariff b 
			on a.trf_id = b.trf_id
			where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA')
			group by thbl,  id_link
        )
        union all
        (
            select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,3 as id_link, sum(a.tot_nom) as total_nom
			from recap_purchase a
			left outer join
			master_tariff b 
			on a.trf_id = b.trf_id
			where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
			group by thbl,  id_link
			union all 
			select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,3 as id_link, sum(a.tot_nom) as total_nom
			from recap_purchase a
			left outer join
			master_tariff b 
			on a.trf_id = b.trf_id
			where a.group_id in (4,6) and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyymm') AND b.trf_name like '%Boko%'
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
			where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
			group by tahun,  id_link
        )
        union all
        (
            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,2 as id_link, sum(a.tot_nom) as total_nom
			from recap_purchase a
			left outer join
			master_tariff b 
			on a.trf_id = b.trf_id
			where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
			group by tahun,  id_link
        )
        union all
        (
            select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,3 as id_link, sum(a.tot_nom) as total_nom
			from recap_purchase a
			left outer join
			master_tariff b 
			on a.trf_id = b.trf_id
			where a.group_id = 5 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
			group by tahun,  id_link
			union all 
			select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,3 as id_link, sum(a.tot_nom) as total_nom
			from recap_purchase a
			left outer join
			master_tariff b 
			on a.trf_id = b.trf_id
			where a.group_id in (4,6) and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR((CURRENT_DATE - 1)::date, 'yyyy') AND b.trf_name like '%Boko%'
			group by tahun,  id_link
        )
    )z
    group by tahun,id_link
)z on w.id_link=z.id_link
        ");
        // return $income;
        $target_income = DB::connection("mysql3")->select("
        SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
        (
            SELECT y.id_link, x.target 
            FROM new_master_target_income x
            left OUTER JOIN new_master_category Y
            ON x.id_category = y.id
            left outer join  new_master_unit z
            on y.id_unit = z.id
            WHERE x.bulan = MONTH(current_date) and z.id = 3
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
            WHERE x.tahun = year(current_date) and z.id = 3
            GROUP BY y.id_link,x.target
        )Y
        ON x.id_link = y.id_link
        group by x.id_link,x.target,y.target
        ");
        // return $target_income;

        $target_income_non = DB::connection("mysql3")->select("
        SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
        (
            SELECT y.id_link, x.target 
            FROM new_master_target_income x
            left OUTER JOIN new_master_category Y
            ON x.id_category = y.id
            left outer join  new_master_unit z
            on y.id_unit = z.id
            WHERE x.bulan = MONTH(current_date) and z.id = 3 and y.id_link = 4
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
            WHERE x.tahun = year(current_date) and z.id = 3 and y.id_link = 4
            GROUP BY y.id_link,x.target
        )Y
        ON x.id_link = y.id_link
        group by x.id_link,x.target,y.target
        ");

        $erp = DB::connection("mysql2")->select("
        select x.id_link,date_format((current_date - 1),'%Y%m%d') as trx_date,x.deskripsi,COALESCE(x.actual_nominal_date,0) as actual_nominal_date,COALESCE(y.actual_nominal_month,0) as actual_nominal_month,COALESCE(z.actual_nominal_year,0) as actual_nominal_year
from
(
	select 4 as id_link,DocDt,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_date from 
    (
        select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
        from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
    ) vwtwcjn
	where 
	  left(AcNo,11) in ('4.02.01.01.','4.02.01.02.','4.02.01.03.','4.02.01.04.','4.02.01.05.','4.02.01.06.','4.02.01.07.','4.02.01.08.','4.02.01.09.','4.02.01.10.')
	  and DocDt = '20230227'
)x
left outer join 
(
	select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_month from 
    (
        select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
        from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
    ) vwtwcjn
	where 
	  left(AcNo,11) in ('4.02.01.01.','4.02.01.02.','4.02.01.03.','4.02.01.04.','4.02.01.05.','4.02.01.06.','4.02.01.07.','4.02.01.08.','4.02.01.09.','4.02.01.10.')
	  and left(DocDt,6) = '202302'
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
	  left(AcNo,11) in ('4.02.01.01.','4.02.01.02.','4.02.01.03.','4.02.01.04.','4.02.01.05.','4.02.01.06.','4.02.01.07.','4.02.01.08.','4.02.01.09.','4.02.01.10.')
	  and left(DocDt,4) = '2023'
 )z 
 on x.id_link = z.id_link
        ");

        // return $erp;

        return view("ratuboko",['ticketing'=>$ticketing,'target_pengguna_jasa'=>$target_pengguna_jasa,'income'=>$income,'target_income'=>$target_income,'erp'=>$erp,'target_income_non'=>$target_income_non,'count'=>$count]);
    }




    // ////////////////////////////////////////////////////////
    
    public function filter(Request $req)
    {
        $date = $req->date;
        $ticketing = DB::connection("pgsql")->select("
        select w.id_link,TO_CHAR(('".$date."')::date, 'yyyymmdd') as trx_date,w.deskripsi,case when x.total_trx > 0 then x.total_trx else 0 end as actual_trx_date,
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
                    WHERE a.group_id = 4 AND a.trx_date = ('".$date."') and a.ctg_id in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                    group by a.trx_date, id_link
                )
                union all
                (
                    select a.trx_date, 2 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    WHERE a.group_id = 4 AND a.trx_date = ('".$date."') and a.ctg_id not in (4,5,6) AND (b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%') 
                    group by a.trx_date, id_link
                )
                union all
                (
                    select a.trx_date, 3 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    WHERE a.group_id = 4 AND a.trx_date = ('".$date."') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%') 
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
                    where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$date."')::date, 'yyyymm') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                    group by thbl,  id_link
                )
                union all
                (
                    select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,2 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$date."')::date, 'yyyymm') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                    group by thbl,  id_link
                )
                union all
                (
                    select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl,3 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$date."')::date, 'yyyymm') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
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
                    where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$date."')::date, 'yyyy') and a.ctg_id in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                    group by tahun,  id_link
                )
                union all
                (
                    select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,2 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$date."')::date, 'yyyy') and a.ctg_id not in (4,5,6) and b.trf_trfftype_id NOT IN (2) and trf_name not like '%Meals%'
                    group by tahun,  id_link
                )
                union all
                (
                    select TO_CHAR(a.trx_date::date, 'yyyy') as tahun,3 as id_link, sum(a.tot_trx) as total_trx
                    from recap_purchase a
                    left outer join
                    master_tariff b 
                    on a.trf_id = b.trf_id
                    where a.group_id = 4 and TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$date."')::date, 'yyyy') AND (b.trf_trfftype_id IN (2) or trf_name like '%Meals%')
                    group by tahun,  id_link
                )
            )z
        )z on w.id_link = z.id_link"
    );

    $target_pengguna_jasa = DB::connection("mysql3")->select("
        SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
        (
            SELECT y.id_link, x.target 
            FROM new_master_target_volume x
            left OUTER JOIN new_master_category Y
            ON x.id_category = y.id
            left outer join  new_master_unit z
            on y.id_unit = z.id
            WHERE x.bulan = MONTH(current_date) and z.id = 2
        )x
        LEFT OUTER JOIN 
        (
            SELECT y.id_link, SUM(x.target) AS target
            FROM new_master_target_volume x
            left OUTER JOIN new_master_category Y
            ON x.id_category = y.id
            left outer join  new_master_unit z
            on y.id_unit = z.id
            WHERE x.tahun = year(current_date) and z.id = 2
            GROUP BY y.id_link, x.target
        )Y
        ON x.id_link = y.id_link
    ");
    // return $target_pengguna_jasa;

    $count = count($target_pengguna_jasa);

    $income = DB::connection("pgsql")->select("
    select w.id_link,TO_CHAR(('".$date."')::date, 'yyyymmdd') as trx_date,w.deskripsi,case when x.total_nom > 0 then x.total_nom else 0 end as actual_nom_date,
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
                WHERE a.group_id = 4 AND a.trx_date = ('".$date."') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                group by a.trx_date, id_link
            )
            union all
            (
                select a.trx_date, 3 as id_link, sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND a.trx_date = ('".$date."')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
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
                WHERE a.group_id = 4 AND TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$date."')::date, 'yyyymm') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                group by thbl, id_link
            )
            union all
            (
                select TO_CHAR(a.trx_date::date, 'yyyymm') as thbl, 3 as id_link, sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND TO_CHAR(a.trx_date::date, 'yyyymm') = TO_CHAR(('".$date."')::date, 'yyyymm') AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
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
                WHERE a.group_id = 4 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$date."')::date, 'yyyy') AND b.trf_trfftype_id NOT IN (2) and b.trf_name not like '%Meals%' and a.source_type not in ('B2B','OTA') 
                group by tahun, id_link
            )
            union all
            (
                select TO_CHAR(a.trx_date::date, 'yyyy') as tahun, 3 as id_link, sum(a.tot_nom) as total_nom
                from recap_purchase a
                left outer join
                master_tariff b 
                on a.trf_id = b.trf_id
                WHERE a.group_id = 4 AND TO_CHAR(a.trx_date::date, 'yyyy') = TO_CHAR(('".$date."')::date, 'yyyy')  AND (b.trf_trfftype_id IN (2) or b.trf_name like '%Meals%') and a.source_type not in ('B2B','OTA')
                group by tahun, id_link
            )
        )z
    )z on w.id_link=z.id_link
    ");
    $target_income = DB::connection("mysql3")->select("
    SELECT x.id_link, x.target AS target_mountly, y.target AS target_yearly FROM
    (
        SELECT y.id_link, x.target 
        FROM new_master_target_income x
        left OUTER JOIN new_master_category Y
        ON x.id_category = y.id
        left outer join  new_master_unit z
        on y.id_unit = z.id
        WHERE x.bulan = MONTH(current_date) and z.id = 2
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
        WHERE x.tahun = year(current_date) and z.id = 2
        GROUP BY y.id_link,x.target
    )Y
    ON x.id_link = y.id_link
    group by x.id_link,x.target,y.target
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
        WHERE x.bulan = MONTH(current_date) and z.id = 2 and y.id_link = 4
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
        WHERE x.tahun = year(current_date) and z.id = 2 and y.id_link = 4
        GROUP BY y.id_link,x.target
    )Y
    ON x.id_link = y.id_link
    group by x.id_link,x.target,y.target
    ");

    $erp = DB::connection("mysql2")->select("
    select x.id_link,date_format(('".$date."'),'%Y%m%d') as trx_date,x.deskripsi,COALESCE(x.actual_nominal_date,0) as actual_nominal_date,COALESCE(y.actual_nominal_month,0) as actual_nominal_month,COALESCE(z.actual_nominal_year,0) as actual_nominal_year
from
(
select 4 as id_link,DocDt,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_date from 
(
    select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
) vwtwcjn
where 
  left(AcNo,11) in ('4.02.01.01.','4.02.01.02.','4.02.01.03.','4.02.01.04.','4.02.01.05.','4.02.01.06.','4.02.01.07.','4.02.01.08.','4.02.01.09.','4.02.01.10.')
  and DocDt = '20230227'
)x
left outer join 
(
select 4 as id_link,'Non Paket/Aneka Usaha' as deskripsi, sum(CAmt - DAmt) as actual_nominal_month from 
(
    select a.DocDt AS DocDt,b.AcNo AS AcNo,b.CAmt, b.DAmt 
    from ((tbljournalhdr a join tbljournaldtl b on(a.DocNo = b.DocNo)))
) vwtwcjn
where 
  left(AcNo,11) in ('4.02.01.01.','4.02.01.02.','4.02.01.03.','4.02.01.04.','4.02.01.05.','4.02.01.06.','4.02.01.07.','4.02.01.08.','4.02.01.09.','4.02.01.10.')
  and left(DocDt,6) = '202302'
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
  left(AcNo,11) in ('4.02.01.01.','4.02.01.02.','4.02.01.03.','4.02.01.04.','4.02.01.05.','4.02.01.06.','4.02.01.07.','4.02.01.08.','4.02.01.09.','4.02.01.10.')
  and left(DocDt,4) = '2023'
)z 
on x.id_link = z.id_link
    ");
        return view("view",['ticketing'=>$ticketing,'target_pengguna_jasa'=>$target_pengguna_jasa,'income'=>$income,'target_income'=>$target_income,'erp'=>$erp,'target_income_non'=>$target_income_non,'count'=>$count]);
    }
}
