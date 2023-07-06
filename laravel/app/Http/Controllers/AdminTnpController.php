<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\TargetVolume;

class AdminTnpController extends Controller
{
    function index() {
        $db_target = DB::connection('db_target');
        $kat_vol = $db_target->select("
            SELECT a.id,a.id_unit,b.name from new_master_category as a join new_master_link as b on a.id_link=b.id where a.id_unit=4
        ");
        // return $kat_vol;
        $target_vol = $db_target->select("
            select t.id,t.id_category,t.tahun,t.bulan,t.thbl,t.target,c.id_unit,c.id_link,l.name from new_master_target_volume as t 
            join new_master_category as c on t.id_category=c.id 
            join new_master_link as l on c.id_link=l.id
            where c.id_unit = 4
        ");
        $data = [
            'kat_vol' => $kat_vol,
            'target_vol'=>$target_vol,
        ];
        return view('admin.target.tnp',$data);
    }
    function new(Request $req) {
        $thbl = str_replace("-","",$req->thbl);
        $db_target = DB::connection('db_target');
        $cek = $db_target->select("
        select * from new_master_target_volume as t 
        join new_master_category as c on t.id_category=c.id 
        join new_master_link as l on c.id_link=l.id
        where c.id_unit = 4 and t.thbl=".$thbl." and l.id='".$req->kategori."'");
        $count = count($cek);
        // return $count;
        if($count == 1){
            return redirect()->back()->with('duplikat','Dobel');
        }
        $tahun = substr($thbl,0,4);
        $bulan = substr($thbl,-2);
        $new_target = $db_target
        ->select("
        insert into new_master_target_volume (id_category,tahun,bulan,thbl,target) values (".$req->kategori.",".$tahun.",".$bulan.",".$thbl.",".$req->target.")");
        return redirect()->back()->with('sukses','Berhasil');
    }
    function edit($id) {
        $db_target = DB::connection('db_target');
        $kat_vol = $db_target->select("
            select a.id,a.id_unit,a.id_link,b.name from new_master_category as a join new_master_link as b on a.id_link=b.id where a.id_unit=4
        ");
        // return $kat_vol;
        $target = TargetVolume::find($id);
        // return $target;
        $target->thbl = substr($target->thbl,0,4)."-".substr($target->thbl,-2);
        $data = [
            'kat_vol'=>$kat_vol,
            'target'=>$target,
        ];
        return view('admin.target.edit_volume.edit_tnp',$data);
    }
    function post_edit(Request $req,$id) {
        $db_target = DB::connection('db_target');
        $thbl = str_replace("-","",$req->thbl);
        $thbl = str_replace("-","",$req->thbl);
        $tahun = substr($thbl,0,4);
        $bulan = substr($thbl,-2);
        $new_target = TargetVolume::find($id);
        if($new_target != null){
            if($new_target->id_category != $req->kategori && $new_target->thbl != $req->thbl){
                return redirect()->back()->with('duplikat','Dobel');
            }
        }
        $new_target->id_category = $req->kategori;
        $new_target->tahun = $tahun;
        $new_target->bulan = $bulan;
        $new_target->thbl = $thbl;
        $new_target->target = $req->target;
        $new_target->save();
        return redirect('/admin/teapen')->with('sukses','Berhasil');
    }
}
