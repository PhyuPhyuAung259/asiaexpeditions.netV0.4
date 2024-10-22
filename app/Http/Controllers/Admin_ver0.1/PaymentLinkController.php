<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentLinkController extends Controller
{
    //
    public function Index(){
    	return view("admin.payment.index");
    }
}
