<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CrProgram;
use App\CrCabin;
use App\CrPrice;
use App\Supplier;
class CruiseController extends Controller
{
    public function getCruiseProgram(Request $req, $supplierId){
    	$supplier = Supplier::find($supplierId);
    	return view('admin.cruise.programForm', compact('supplier'));
    }

    public function getProgram(Request $req){
    	$locationid = isset($req->location) ? $req->location: \Auth::user()->country_id;
    	$programs = CrProgram::orderBy('program_name', 'ASC')->get();
    	return view('admin.cruise.program', compact('programs', 'locationid'));
    }

    public function createCruiseProgram(Request $req){
    	$addCruisePro = New CrProgram;
    	$addCruisePro->program_name = $req->program_name;
    	$addCruisePro->supplier_id = $req->cruis_name;
    	$addCruisePro->province_id = $req->program_dest;
    	$addCruisePro->program_intro = $req->program_desc;
    	$addCruisePro->program_remark = $req->program_remark; 
    	$addCruisePro->program_highlight = $req->program_hight;
    	$addCruisePro->status = $req->status;
    	$addCruisePro->save();
    	$addCruisePro->crCabin()->sync($req->crcabin, false);    	
        return back()->with(['message'=> 'Cruise Program created successfully',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
    }

    public function getCabin(){
        $cabin = CrCabin::where('status',1)->get();
        return view('admin.cruise.cabin', compact('cabin'));
    }

    public function getProgramEdit($supId , $crId){
        $supplier = Supplier::find($supId);
        $crpro = CrProgram::find($crId);
        $dataid = '';
        foreach ($crpro->crCabin as $key => $value) {
            $dataid .= $value->pivot->cr_cabin_id.',';
        }
        return view('admin.cruise.programFormEdit', compact('crpro', 'supplier', 'dataid'));
    }

    public function updateCruiseProgram(Request $req){
        $addCruisePro = CrProgram::find($req->eid);
        $addCruisePro->program_name = $req->program_name;
        $addCruisePro->supplier_id = $req->cruis_name;
        $addCruisePro->province_id = $req->program_dest;
        $addCruisePro->program_intro = $req->program_desc;
        $addCruisePro->program_remark = $req->program_remark; 
        $addCruisePro->program_highlight = $req->program_hight;
        $addCruisePro->status = $req->status;
        $addCruisePro->save();
        $addCruisePro->crCabin()->sync($req->crcabin, true);            
        return back()->with(['message'=> 'Cruise Program updated successfully',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
    }

    public function getCrCabin(){
        $getcabin = CrProgram::getCabinProgram();
        return view('admin.cruise.cabinApp', compact('getcabin'));
    }

    public function getApplyCrCabin($proId, $cabinId){
        $crpro = CrProgram::find($proId);
        $crcabin = CrCabin::find($cabinId);
        return view('admin.cruise.cabinApplyPrice', compact('crpro', 'crcabin'));
    }

    public function applyCabinprice(Request $req){
        foreach ($req->ssingle as $key => $psingle) {
            $addRate = New CrPrice;
            $addRate->supplier_id = $req->sup_id;
            $addRate->program_id = $req->pro_id;
            $addRate->cabin_id = $req->cabin_id;
            $addRate->ssingle_price = $req->ssingle[$key];
            $addRate->stwn_price = $req->stwin[$key];
            $addRate->sdbl_price = $req->sdouble[$key];
            $addRate->sextra_price = $req->sextra[$key];
            $addRate->schextra_price = $req->schextra[$key];
            $addRate->nsingle_price = $req->nsingle[$key];
            $addRate->ntwn_price = $req->ntwin[$key];
            $addRate->ndbl_price = $req->ndouble[$key];
            $addRate->nextra_price = $req->nextra[$key];
            $addRate->nchextra_price = $req->nchextra[$key];
            $addRate->start_date = $req->fromdate[$key];
            $addRate->end_date = $req->todate[$key];
            $addRate->save();
        }  
        return back()->with(['message'=> 'Cabin Price Applied successfully',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }

    public function getCrCabinEdit($proId, $cabinId){
        $crpro = CrProgram::find($proId);
        $crcabin = CrCabin::find($cabinId);
        $getCrprice=CrPrice::where(['program_id'=>$proId,'cabin_id'=>$cabinId,'supplier_id'=>$crpro->supplier->id])->get();
        return view('admin.cruise.cabinPriceEdit',  compact('getCrprice', 'crpro', 'crcabin'));
    }

    public function updateCabinprice(Request $req){
        foreach ($req->ssingle as $key => $psingle) {
            $addRate = CrPrice::find($req->eid[$key]);
            $addRate->ssingle_price = $psingle;
            $addRate->stwn_price = $req->stwin[$key];
            $addRate->sdbl_price = $req->sdouble[$key];
            $addRate->sextra_price = $req->sextra[$key];
            $addRate->schextra_price = $req->schextra[$key];
            $addRate->nsingle_price = $req->nsingle[$key];
            $addRate->ntwn_price = $req->ntwin[$key];
            $addRate->ndbl_price = $req->ndouble[$key];
            $addRate->nextra_price = $req->nextra[$key];
            $addRate->nchextra_price = $req->nchextra[$key];
            $addRate->start_date = $req->fromdate[$key];
            $addRate->end_date = $req->todate[$key];
            $addRate->save();
        }
        return back()->with(['message'=> 'Cabin Price update successfully',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 

    }

    public function getCabinprice(Request $req){
        $locationid = isset($req->location) ? $req->location: \Auth::user()->country_id;        
        $cabinPrice = CrPrice::getCabinPrice($locationid);
        return view('admin.cruise.cabinPrice', compact('cabinPrice', 'locationid'));
    }

    // public function bookedCruise(){
    //     return 
    // }

}
