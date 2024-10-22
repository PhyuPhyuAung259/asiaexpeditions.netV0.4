<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\RestaurantMenu;
use App\Supplier;
use App\TransportService;
use App\TransportMenu;
use App\GuideService;
use App\GuideLanguage;
use App\MISCService;
use App\Entrance;
use App\GolfMenu;
use App\Service;
use App\Driver;

class ServiceController extends Controller
{
	public function getDriver(Request $req){
		if (isset($req->eid) && !empty($req->eid)) {
			$edriver = Driver::find($req->eid);
			return response()->json(["data_load"=> $edriver]);
		}else{
			$locat = isset($req->locat) ? $req->locat : \Auth::user()->country_id;
			$drivers = Driver::where(["status"=>1, 'country_id'=>$locat])->orderBy("driver_name", "ASC")->get();
			return view("admin.service.driver", compact('drivers', 'locat'));
		}
	}

	public function addDriver(Request $req){
		if (isset($req->eid) && !empty($req->eid)) {
			$edr = Driver::find($req->eid);
			$edr->driver_name = $req->driver_name;
			$edr->country_id  = $req->country;
			$edr->province_id = $req->city;
			$edr->supplier_id = $req->transport;
			$edr->phone 	  = $req->phone;
			$edr->phone2 	  = $req->phone2;
			$edr->email 	  = $req->email;
			$edr->email2 	  = $req->email2;
			$edr->address     = $req->adress;
			$edr->intro       = $req->intro;
			$edr->save();
			$message = "successfully updated";
		}else{
			$adr = New Driver;
			$adr->driver_name = $req->driver_name;
			$adr->country_id  = $req->country;
			$adr->province_id = $req->city;
			$adr->supplier_id = $req->transport;
			$adr->phone 	  = $req->phone;
			$adr->phone2 	  = $req->phone2;
			$adr->email 	  = $req->email;
			$adr->email2 	  = $req->email2;
			$adr->address     = $req->adress;
			$adr->intro       = $req->intro;
			$adr->save();
			$message = "successfully added";
		}
		return back()->with(['message'=>'Driver '.$req->driver_name." ". $message, 'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
	}
	public function restaurantMenu (Request $req ){
		$locat = isset($req->locat) ? $req->locat: \Auth::user()->country_id;
		if ($req->rest) {
			$restaurant=RestaurantMenu::where(['status'=>1, 'supplier_id'=>$req->rest])->orderBy('title')->get();
		}else{
			$restaurant=RestaurantMenu::where(['status'=>1, 'country_id'=> $locat])->orderBy('title')->get();
		}
		return view('admin.service.restaurant', compact('restaurant', 'locat'));
	}

	public function createrestMenu( ){
		return view('admin.service.restaurantMenu');
	}

	public function AddRestMenu(Request $req){
		$rest = Supplier::where('id',$req->rest_name)->first();
		foreach ($req->menu_name as $key => $menu_name) {
			$addres = NEW RestaurantMenu;
			$addres->title = $menu_name;
			$addres->country_id  = $req->country;
			$addres->province_id = $req->province;
			$addres->supplier_id = $rest->id;
			$addres->price 		 = $req->price[$key];
			$addres->kprice      = $req->kprice[$key];
			$addres->save();
		}
		return back()->with(['message'=> "Menu added successfully", 'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
	}

	public function updateRestMenu(Request $req){
		$rest = Supplier::where('id',$req->rest_name)->first();
		$addres = RestaurantMenu::find($req->eid);
		$addres->title = $req->menu_name;
		$addres->country_id  = $req->country;
		$addres->province_id = $req->province;
		$addres->supplier_id = isset($req->rest_name)? $req->rest_name:'';
		$addres->price 		 = $req->price;
		$addres->kprice      = $req->kprice;
		$addres->save();
		return back()->with(['message'=> "Menu updated successfully",  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
	}

	public function tranService (Request $req){
		$locat = isset($req->locat) ? $req->locat : \Auth::user()->country_id;
		$tranService = TransportService::where(['status'=>1, 'country_id'=>$locat])->orderBy("title")->get();
		return view("admin.service.transport_service", compact("tranService", 'locat'));
	}

	public function getEditTransport(Request $req){
		$tranp = TransportService::find($req->eid);
		$serviceID = "";
		if ($tranp) {
			foreach ($tranp->supplier_transport as $key => $sup) {
				$serviceID .= $sup->pivot->supplier_id.",";
			}
		}
		return response()->json(["data_load"=> $tranp, "serviceID" => $serviceID]);
	}

	public function createtranService(Request $req){
		if (!empty($req->eid) && isset($req->eid) )  {
			$atran =  TransportService::find($req->eid);
			$atran->country_id  = $req->country;
			$atran->province_id  = $req->city;
			$atran->tour_id 	= $req->tour;
			$atran->title = $req->service_name;
			$atran->save();
			$atran->supplier_transport()->sync($req->multi_sup, true);
			$message = " Transport service update successfully";
		}else{
			$atran =  NEW TransportService;
			$atran->country_id  = $req->country;
			$atran->province_id  = $req->city;
			$atran->tour_id 	= $req->tour;
			$atran->title = $req->service_name;
			$atran->save();
			$atran->supplier_transport()->sync($req->multi_sup, false);
			$message = " Transport service added successfully";
		}
		return response()->json(["message"=> $message]);
	}
 
	public function getVehicle(Request $req){  
		$tranName = TransportMenu::where(["supplier_id"=>$req->sup_id, 'transport_id'=> $req->tran_id, 'status'=>1])->orderBy("name", "ASC")->get();
		$data = [];
		if ($tranName->count() > 0) {
			foreach ($tranName as $key=> $tran) {
				$data[] = [
	                'tran_id' => $tran->id ? $tran->id:'',
	                'name'  => $tran->name ? $tran->name:'',
	                'price' => $tran->price ? $tran->price:'',
	                'kprice'=> $tran->kprice ? $tran->kprice:''
	            ];
			}
		}
		return response()->json(['query_data'=> $data]);
		// return view('admin.service.vehicle_type', compact('supplier_transport'));
	}

	public function CreateVehicle(Request $req){
		// return 'hfdasfds';
		if (isset($req->eid) && !empty($req->eid)  )  {
			$vh =  TransportMenu::find($req->eid);
			$vh->name 		= $req->vehicle;
			$vh->supplier_id = $req->supplier;
			$vh->transport_id = $req->transport_id;
			$vh->price 		= $req->price;
			$vh->kprice 	= $req->kprice;
			$vh->save();
			$message = "Vehicle update successfully";
		}else{
			$vh =  New TransportMenu;
			$vh->name 		= $req->vehicle;
			$vh->supplier_id =$req->supplier;
			$vh->transport_id=$req->transport_id;
			$vh->price 		= $req->price;
			$vh->kprice 	= $req->kprice;
			$vh->save();
			$message = "Vehicle added successfully";
		}
		return response()->json(["message"=>$message]);
	}


	public function getGuide(Request $req){
		$locat = isset($req->locat) ? $req->locat : \Auth::user()->country_id;
		$guide = GuideService::where(['status'=>1, 'country_id'=>$locat])->orderBy("title")->get();
		return view('admin.service.guide_service', compact('guide', 'locat'));
	}

	public function addGuideService(Request $req){
		if (!empty($req->eid) && isset($req->eid) )  {
			$atran = GuideService::find($req->eid);
			$atran->country_id  = $req->country;
			$atran->province_id  = $req->city;
			$atran->title = $req->service_name;
			$atran->code = $req->service_code;
			$atran->save();
			$message = "Guide service update successfully";
		}else{
			$atran = New GuideService;
			$atran->country_id  = $req->country;
			$atran->province_id  = $req->city;
			$atran->title = $req->service_name;
			$atran->code = $req->service_code;
			$atran->save();
			$message = "Guide service added successfully";
		}
		return response()->json(["message"=> $message]);
	}

	public function getGuideLanguage($serviceId, Request $req){
		$getLanguage = GuideLanguage::where('guide_service_id', $serviceId)->orderBy('name', 'ASC')->get();
		if ($getLanguage->count() > 0) {
			$message = '';
			foreach ($getLanguage as $key => $lang) {
				$supName = '';
				$supID = "";
				foreach ($lang->supplier as $key => $value) {
					$supName .= $value->supplier_name.", ";
					$supID .= $value->pivot->supplier_id.",";
				}
				$guidename = isset($lang->supplier->supplier_name)? $lang->supplier->supplier_name:'';
				$message .= "<tr class='odd'>
					<td>".$lang->name."</td>
					<td> <span class='badge' data-toggle='tooltip' data-placement='top' title='".rtrim($supName, ",")."'>".$lang->supplier->count()."</span></td>
					<td class='text-right'>".number_format($lang->price,2)."</td>
					<td class='text-right'>".number_format($lang->kprice,2)."</td>
					<td class='text-right'>
						<a href='javascript:void(0)' class='btnEdit' data-name='".($lang->name)."'  data-id=".$lang->id." data-con='' data-sup='".$supID."' data-price='".$lang->price."' data-kprice='".$lang->kprice."' title='Click to update?'>
							<i style='padding:1px 2px;' class='btn btn-info btn-xs fa fa-pencil-square-o'></i>
						</a>
						&nbsp;
						<a href='javascript:void(0)' class='RemoveHotelRate' data-id=".$lang->id." title='Remove this?' data-type='service_language'>
							<i style='padding:1px 2px;' class='btn btn-danger btn-xs fa  fa-minus-circle'></i>
						</a>
					</td>
				</tr>";
			}
		}else{
			$message ="<tr class='odd'><td valign='top' colspan='5' class='text-center'>No matching records found</td></tr>";
		}
		echo $message;
	}

	// public function getEditLang(Request $req){
	// 	$guide = GuideLanguage::find($req->eid);
		
	// }

	public function addLanguage (Request $req){
		if (!empty($req->languid) && isset($req->languid) )  {
			$atran = GuideLanguage::find($req->languid);
			$atran->name  = $req->title;
			$atran->guide_service_id  = $req->service;
			$atran->supplier_id = $req->guide_lang;
			$atran->price = $req->price;
			$atran->kprice = $req->kprice;
			$atran->save();
			$atran->supplier()->sync($req->multi_sup, true);
			$message = "Language update successfully";
		}else{
			$atran = New GuideLanguage;
			$atran->name  = $req->title;
			$atran->guide_service_id  = $req->service;
			$atran->supplier_id = $req->guide_lang;
			$atran->price = $req->price;
			$atran->kprice = $req->kprice;
			$atran->save();
			$atran->supplier()->sync($req->multi_sup, false);
			$message = "Language added successfully";
		}
		return response()->json(["message"=> $message]);
	}

	public function getMiscService(Request $req){
		$locat = isset($req->locat) ? $req->locat : \Auth::user()->country_id;
		$misc_service = MISCService::where(['status'=>1, 'country_id'=>$locat])->orderBy('name')->get();
		return view('admin.service.misc_service', compact('misc_service', 'locat'));
	}

	public function addMisc(Request $req){
		if (!empty($req->eid) && isset($req->eid) )  {
			$misc = MISCService::find($req->eid);
			$misc->name  		= $req->title;
			$misc->country_id  = $req->country;
			$misc->province_id = $req->city;
			$misc->price 		= $req->price;
			$misc->kprice 		= $req->kprice;
			$misc->save();
			$message = "MISC service update successfully";
		}else{
			$misc = NEW MISCService;
			$misc->name  		= $req->title;
			$misc->country_id  = $req->country;
			$misc->province_id = $req->city;
			$misc->price 		= $req->price;
			$misc->kprice 		= $req->kprice;
			$misc->save();
			$message = "MISC service created successfully";
		}
		return response()->json(["message"=> $message]);
	}

	public function getEntrance(Request $req){
		$locat = isset($req->locat) ? $req->locat : \Auth::user()->country_id;
		$entrance = Entrance::where(['status'=>1, 'country_id'=>$locat])->orderBy('name')->get();
		return view("admin.service.entrance", compact('entrance', 'locat'));
	}

	public function addEntrance(Request $req){
		if (!empty($req->eid) && isset($req->eid) )  {
			$misc = Entrance::find($req->eid);
			$misc->name  		= $req->title;
			$misc->country_id  = $req->country;
			$misc->province_id = $req->city;
			$misc->price 		= $req->price;
			$misc->kprice 		= $req->kprice;
			$misc->save();
			$message = "Entrance fees update successfully";
		}else{
			$misc = NEW Entrance;
			$misc->name  		= $req->title;
			$misc->country_id   = $req->country;
			$misc->province_id  = $req->city;
			$misc->price 		= $req->price;
			$misc->kprice 		= $req->kprice;
			$misc->save();
			$message = "Entrance fees created successfully";
		}
		return response()->json(["message"=> $message]);
	}

	public function serviceInclude (){
		$service = Service::where('status',1)->orderBy("service_cat", "DESC")->get();
		return view("admin.service.service_include", compact('service'));
	}

	public function addService (Request $req){
		$eservice = Service::find($req->seviceid);
		if ($eservice) {
			$eservice->service_name = $req->service_name; 
			$eservice->service_cat  = $req->service_cat;
			$eservice->service_status = $req->status;
			$eservice->save();
			$message = "Service successfully updated";
		}else{ 
			$aservice = new Service;
			$aservice->service_name = $req->service_name;
			$aservice->service_cat  = $req->service_cat;
			$aservice->service_status = $req->status;
			$aservice->save();
			$message = "Service successfully created";
		}
		return back()->with(['message'=> $message,  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
	}

	public function golfService(Request $req){
		$gservice = GolfMenu::where(['status'=> 1])->orderBy('name', 'ASC')->get();
		return view('admin.service.golf_service', compact('gservice'));
	}

	public function addGolfService(Request $req){
		$gMenu = GolfMenu::find($req->eid);
		if ($gMenu) {
			$gMenu->name = $req->title;
			$gMenu->supplier_id = $req->golf_name;
			$gMenu->price = $req->price;
			$gMenu->nprice = $req->nprice;
			$gMenu->kprice = $req->kprice;
			$gMenu->nkprice = $req->nkprice;
			$gMenu->save();
			$message = "Golf Service successfully updated";
		}else{
			$aMenu = new GolfMenu;
			$aMenu->name = $req->title;
			$aMenu->supplier_id = $req->golf_name;
			$aMenu->price   = $req->price;
			$aMenu->nprice  = $req->nprice;
			$aMenu->kprice  = $req->kprice;
			$aMenu->nkprice = $req->nkprice;
			$aMenu->save();
			$message = "Golf Service successfully created";
		}
		return back()->with(['message'=> $message,  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
	}

}
