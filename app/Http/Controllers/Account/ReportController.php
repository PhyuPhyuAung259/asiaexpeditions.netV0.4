<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\component\Content;
use App\Http\Controllers\Controller;
use App\Http\Resources\Supplier as SupplierResource;
use App\Http\Resources\SupplierCollection;

use App\Supplier;

class ReportController extends Controller
{
    public function index(Request $req, $reporType){
        $Enddate = date("Y-m-d", strtotime("+1 month"));
        $d =  Content::diffDate(date('Y-m-d'), $Enddate); 
        $fromDate = isset($req->fromDate) ? $req->fromDate : date('Y-m-d');
        $endDate = isset($req->endDate) ? $req->endDate : date('Y-m-').$d;
    	$supplier = Supplier::where(['supplier_status'=>1, 'business_id'=>9])->whereHas("project",
    		function($query) use ($fromDate, $endDate){
    			$query->where(['project_status'=>1]);
    			$query->whereNotIn('project_fileno', ['', 'null', 0]);
    			$query->whereBetween('project_start', [$fromDate, $endDate]);
    		})->orderBy('supplier_name')->get();
    	if ($reporType == 'agent') {
    		// , 
    		// function($query) use ($fromDate, $endDate) {
    		// 	$query->where(['project_status'=>1]);
    		// 	$query->whereNotIn('project_fileno', ["", "null", 0]);
    		// 	$query->whereBetween('project_start', [$fromDate, $endDate]);
    		// }
    		return view('admin.report.supplier_report', compact('supplier', 'fromDate', 'endDate'));
    	}
    }
}
