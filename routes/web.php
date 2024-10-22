<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great! 
|
*/   

// Route::get('/','HomeController@index');
Route::get('/admin', function(){ 
	// return $location = "httwww.geoplugin.net/php.gp?ip=".$_SERVER['REMOTE_ADDR'];
	// $location = [];
	// $location = var_export(unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR'])));
	// foreach ($location as $key => $value) {
		// return $location[1];
	// }
	// return $_SERVER['REMOTE_ADDR'];
// 	echo encrypt('virak@12345'); 
	return redirect()->intended('/'); 
});
 
Route::get('login',  'Auth\LoginController@getLogin')->name('login');
Route::POST('doLogin',  'Auth\LoginController@doLogin')->name('doLogin');
Route::get('logout',  'Auth\LoginController@getLogOut')->name('logOut');

// ================== CHECK IS USER AS Admin 
Route::group(['middleware' => ['IsAdmin']], function(){
	Route::get('user/role/menu', 'Admin\RoleController@roleApply')->name('roleApply');
	Route::post('user/role/menu', 'Admin\RoleController@menuApplied')->name('menuApplied');

	Route::get('users', 'Admin\UserController@userList')->name('userList');
	Route::get('user/role', 'Admin\UserController@rolList')->name('rolList');
	Route::POST("role/create", "Admin\UserController@createRole")->name('createRole');
	Route::get("user/register", "Admin\UserController@userForm")->name('userForm');
	Route::get("user/update/{user}/user", "Admin\UserController@userStore")->name('userStore');
	Route::post("user/update", "Admin\UserController@updateUser")->name('updateUser');
	Route::post("user/regiser/new", "Admin\UserController@registerNew")->name('addUser');
	Route::get("user/update/{user}/permission", "Admin\UserController@editpermission")->name('editpermission');
	Route::post("user/update/permission", "Admin\UserController@changePermission")->name('changePermission');
});


// =============== AMINISTRATION SECTION======================================
Route::group(['middleware' => ['IsLogin']], function(){
		// Route documentation :
		Route::prefix("docs")->group(function () {
			Route::get("/", "DocsController@getDocs")->name("getDocs");
			Route::get("/list", "DocsController@getDocsList")->name("getDocsList");
			Route::get("action", "DocsController@createDocs")->name('createDocs');
			Route::post("create/docs", "DocsController@createNewDocs")->name('createNewDocs');
			Route::get("preview", "DocsController@getDocsDetail")->name("getDetail_doc");
		});

		Route::get('setting', 'Admin\ThemeController@setting')->name('setting');
		Route::get('setting/{id}', 'Admin\ThemeController@settingForm')->name('settingForm');
		Route::POST("setting/update/{id}", 'Admin\ThemeController@updateSetting')->name('updateSetting');
	// Route::prefix('admin')->group(function () {
		Route::get("setting-options", "Admin\ThemeController@getTheme")->name('getTheme'); 
		Route::get("company", "Admin\ThemeController@getCompany")->name('company');
		Route::get("company-form", "Admin\ThemeController@companyForm")->name('companyForm');
		Route::post("company", "Admin\ThemeController@addCompany")->name('addCompany');


		Route::get("slide-show", "Admin\SlideController@index")->name('slides');
		Route::get("slide/add", "Admin\SlideController@createSlide")->name('createSlide');
		Route::get("slide/update/{slideId}", "Admin\SlideController@getSlide")->name('getSlide');
		Route::POST("slide/add", "Admin\SlideController@slideStore")->name('slideStore');
		Route::POST("slide/update", "Admin\SlideController@updateStore")->name('updateStore');
		
		Route::get("user/update/{user}/user", "Admin\UserController@userStore")->name('userStore');
		Route::post("user/update", "Admin\UserController@updateUser")->name('updateUser');	

		Route::get('booking/project', 'Admin\ProjectController@projectForm')->name('proForm');
		Route::get('booking/project/update/{project}', 'Admin\ProjectController@projectFormEdit')->name('proFormEdit');
		Route::post('create/project', 'Admin\ProjectController@createProject')->name('createProject');
		Route::get("/getImageFile", "Admin\AdminController@getImageFile")->name('getImageFile');
		Route::post('booking/update', 'Admin\ProjectController@updateProject')->name('updateProject');
		Route::POST("/project/AddNet", 'Admin\ProjectController@projectAddNetPrice')->name('projectAddNetPrice');

		Route::get('/', 'Admin\AdminController@index')->name('adminhome');
		Route::post('booked/{project}', 'Admin\AdminController@searchProject')->name('searchProject');
		Route::get('booked/{project}', 'Admin\ProjectController@projectList')->name('projectList');
		Route::get('booked/project/{prjectNo}', 'Admin\ProjectController@preProject')->name('preProject');

		Route::POST("bookedTourUpdate", "Admin\ProjectController@UpdateTourDesc")->name("UpdateTourDesc");
		
		Route::POST("addClient/project", 'Admin\ProjectController@addClientForProject')->name('addClientForProject');

		Route::post("addProjectPdF", 'Admin\ProjectController@addProjectPdF')->name('addProjectPdF');
		
		Route::get('booked/{booktype}/update/{bookId}', 'Admin\BookingController@geteditBookedType')->name('bookingEdit');
		Route::post('booked/{booktype}/updated', 'Admin\BookingController@updateBookedType')->name('updateBooked');


		Route::get('booked/hotel/apply/{project}-{hotel}-{bookid}/room', 'Admin\BookingController@bookedApplyroom')->name('bapplyRoom');
		Route::get('edit/booked/hotel/apply/{project}-{hotel}-{bookid}/room', 'Admin\BookingController@editbookedApplyroom')->name('editbapplyRoom');
		Route::POST('booked/hotel/apply/room/remark', 'Admin\BookingController@addHotelRemark')->name('addHotelRemark');
		Route::get('booked/cruise/apply/{project}-{hotel}-{bookid}/room', 'Admin\BookingController@bookedCruise')->name('bookedCruise');
		Route::post('booking/applied/room', 'Admin\BookingController@hotelbookedRoomApplied')->name('bookingAppliedroom');
		Route::post('booking/applied/cruisrate', 'Admin\BookingController@crusebookedRoomApplied')->name('crbgAppliedroom');
		Route::get('country', 'Admin\DestinationController@CountryList')->name('CountryList');	
		Route::get('country/create/new', 'Admin\DestinationController@getCountry')->name('getCountry');	
		Route::post('country/create', 'Admin\DestinationController@createCountry')->name('createCountry');
		Route::get('country/{countryId}/edit', 'Admin\DestinationController@getCountryEdit')->name('getCountryEdit');
		
		Route::post('country/update', 'Admin\DestinationController@updateCountry')->name('updateCountry');
		Route::get('province', 'Admin\DestinationController@provinceList')->name('provinceList');	
		Route::get('province/create/new', 'Admin\DestinationController@getProvince')->name('getProvince');
		Route::post('province/create', 'Admin\DestinationController@createProvince')->name("createProvince");
		Route::get('province/{proId}/edit', 'Admin\DestinationController@getProvinceEdit')->name('getProvinceEdit');
		Route::post('province/update', 'Admin\DestinationController@updateProvince')->name('updateProvince');
		Route::get('suppliers', 'Admin\SupplierController@supplierList')->name('supplierList');	
		Route::get('supplier/{supplier}', 'Admin\SupplierController@supplierBusiness')->name('supplierBusiness');

		Route::get('supplier/transport/{id}/driver', 'Admin\SupplierController@getDriver')->name('getDriver');
		Route::get("transport/edit", "Admin\ServiceController@getEditTransport")->name("getEditTransport");

		Route::get('supplier/add/new', 'Admin\SupplierController@getSupplierForm')->name('getSupplierForm');
		 
		Route::post('supplier/create', 'Admin\SupplierController@createSupplier')->name('createSupplier');
		Route::get('supplier/edit/{supplierId}', 'Admin\SupplierController@getEditSupplier')->name('getEditSupplier');
		Route::get('supplier/hotel/update/info/{supplierId}', 'Admin\HotelController@getEditHotelInfo')->name('getEditHotelInfo');
		Route::post('hotel/update/info', 'Admin\HotelController@updateHotelInfo')->name('updateHotelInfo');
		Route::POST("AddHotelDiscount", 'Admin\HotelController@AddHotelDiscount')->name('AddHotelDiscount');
		Route::post('supplier/udpate', 'Admin\SupplierController@udpateSupplier')->name('udpateSupplier');

		Route::get('flight-schedule', 'Admin\FlightController@getFlightSchedule')->name('getFlightSchedule');
		Route::get('flight-schedule/add/new', 'Admin\FlightController@createFlightSchedule')->name('createFlightSchedule');
		
		Route::POST('flight-schedule/create', 'Admin\FlightController@createSchedule')->name('createSchedule');

		Route::POST('flight-schedule/update', 'Admin\FlightController@updateSchedule')->name('updateSchedule');
		Route::GET('schedule-price/add', 'Admin\FlightController@upscheduleprice')->name('upscheduleprice');
		Route::get('flight/edit/{flightId}/schedule', 'Admin\FlightController@getEditSchedule')->name('getEditSchedule');
		Route::get('flight-schedule/apply/{schedule}', 'Admin\FlightController@getSchedulePrice')->name('getSchedulePrice');
		 
		Route::get('service/include', 'Admin\ServiceController@serviceInclude')->name('serviceInclude');
		Route::get('service/{service}', 'Admin\ServiceController@getService')->name('getService');
		Route::get('restaurant/menu', 'Admin\ServiceController@restaurantMenu')->name('restMenu');
		Route::get('restautant/menu/create', 'Admin\ServiceController@createrestMenu')->name('createrestMenu');
		Route::post('service/added', 'Admin\ServiceController@addService')->name('addService');
		Route::post("restautant/add/menu", "Admin\ServiceController@AddRestMenu")->name('AddRestMenu');
		Route::post("restautant/update/menu", "Admin\ServiceController@updateRestMenu")->name('EditRestMenu');
		Route::get("transport/service", "Admin\ServiceController@tranService")->name('tranService'); 
		Route::get("transport/driver/add", "Admin\ServiceController@getDriver")->name('getDriver');
		Route::get("transport/vihecle/", "Admin\ServiceController@getVehicle")->name('getVehicle');

		//edit operation route 
		Route::get("edit/{type}/{id}","Admin\EditOperationController@editOperation")->name('editoperation');
		Route::get("edit/{type}/{project_no}/{id}","Admin\EditOperationController@editGuideOperation")->name('editguideoperation');
		Route::post("transport/added/vehicle", "Admin\ServiceController@CreateVehicle")->name('addVehicle');
		Route::post("transport/service/added", "Admin\ServiceController@createtranService")->name('addtranService');
		Route::get("golf/service", "Admin\ServiceController@golfService")->name('golfService');
		Route::post("golf/service", "Admin\ServiceController@addGolfService")->name('addGolfService');
		Route::get('guide/service', "Admin\ServiceController@getGuide")->name('getGuide');	

		Route::post('driver/add', "Admin\ServiceController@addDriver")->name('addDriver');
		Route::get('guide/service/{service}/language', "Admin\ServiceController@getGuideLanguage")->name('getLanguage'); 
		Route::post('guide/service/added', "Admin\ServiceController@addGuideService")->name('addGuideService');
		
		Route::get('misc/service/', "Admin\ServiceController@getMiscService")->name('getMiscService');
		Route::post('misc/service/added', "Admin\ServiceController@addMisc")->name('addMisc');
		Route::get('entrance/service/', "Admin\ServiceController@getEntrance")->name('getEntrance'); 
		Route::post('entrance/service/added', "Admin\ServiceController@addEntrance")->name('addEntrance');
		Route::post("guide/language/added", "Admin\ServiceController@addLanguage")->name('addLanguage');
		
		//promotion route

		Route::get('hotels/addpromotion','Admin\PromotionController@addPromotion')->name('addPromotion');
		Route::get('hotels/getpromotion','Admin\PromotionController@getPromotion')->name('getPromotion');
		Route::post('hotels/storepromotion','Admin\PromotionController@storePromotion')->name('storePromotion');
		Route::get('promotion/edit/{promoId}', 'Admin\PromotionController@getEditPromotion')->name('getEditPromotion');

		Route::get('hotel/room', 'Admin\HotelController@getRoom')->name('getRoom');
		Route::get('hotel/info', 'Admin\HotelController@getHotelinfo')->name('getHotelinfo');
		Route::post('hotel/info', 'Admin\HotelController@addHotelinfo')->name('addHotelinfo');
		Route::post('hotel/agentTariff', 'Admin\SupplierController@sortHotelTariff')->name('sortHotelTariff');


		Route::get('hotel/facility', 'Admin\HotelController@getHotelFacility')->name('getHotelFacility');
		Route::post('hotel/facility', 'Admin\HotelController@eddHotelFacility')->name('eddHotelFacility');
		Route::post('hotel/room/update', 'Admin\HotelController@EditRoomType')->name('EditRoomType');
		Route::get('hotel/category', 'Admin\HotelController@getRoomCategory')->name('getRoomCat');
		Route::get('hotel/hotelroom', 'Admin\HotelController@getRoomApplied')->name('getRoomApplied');
		Route::get('hotel/hotelrate', 'Admin\HotelController@getHotelRoomRate')->name('getHotelRoomRate');
		Route::get('hotel/add/hotel-rate/{hotelId}/{roomId}', 'Admin\HotelController@getHotelRate')->name('getHotelRate');
		Route::post('hotel/add/hotel-rate/', 'Admin\HotelController@addRoomRate')->name('addRoomRate');
		Route::GET('hotel/update/hotel-rate/', 'Admin\HotelController@updateRoomRate')->name('updateRoomRate');
		Route::post('hotel/hotelrate', 'Admin\HotelController@serachHotelRate')->name('serachHotelRate');

		Route::get('hotel/row/hotel-rate-price', 'Admin\HotelController@getRatePrice')->name('getRatePrice');		
		Route::get('hotel/edit/hotel-rate/{hotelId}/{roomId}', 'Admin\HotelController@getEdiRoomRate')->name('getEditHotelRate');

		Route::get('blog', 'Admin\BlogController@index')->name('blogindex');
		Route::get('blog/create', 'Admin\BlogController@create')->name('blogcreate');
		Route::POST('blog/store', 'Admin\BlogController@store')->name('blogstore');
		Route::get("blog/update/{id}", 'Admin\BlogController@edit')->name('blogedit');
		Route::POST("blog/update/{id}", 'Admin\BlogController@update')->name('blogupdate');

		Route::get('tours', 'Admin\TourController@tourList')->name('tourList');
		Route::get('cities/{country}', 'Admin\AdminController@getCities');
		Route::get('hotels/{country}', 'Admin\AdminController@getHotels');
		Route::get('golfs/{country}', 'Admin\AdminController@getGolfs');
		Route::get('restaurants/{city}', 'Admin\AdminController@getRestaurants');
		Route::get('get_sup_name/{bus_id}','Admin\ReportController@getSupName');

		Route::get('tour/create/new', 'Admin\TourController@tourForm')->name('tourForm');
		Route::post('tour/create', 'Admin\TourController@tourCreate')->name('tourCreate');
		Route::get('tour/update/{tourid}/tour', 'Admin\TourController@getTourUpdate')->name('getTourUpdate');
		Route::post('tour/updateTour', 'Admin\TourController@updateTour')->name('updateTour');
		Route::get('tourtype', 'Admin\TourController@getTourtype')->name('getTourtype');
		Route::get('tourtype/edit', 'Admin\TourController@getTourTypeedit')->name('getTourTypeedit');
		Route::post("create/tourtype", "Admin\TourController@createTourType")->name('createTourType');
		Route::get('tour/add/price/{tourid}', 'Admin\TourController@getTourPrice')->name('getTourPrice');
		Route::get('tour/update/price/{tourid}', 'Admin\TourController@getTourPriceEdit')->name('getTourPriceEdit');
		Route::post('tour/add/price', 'Admin\TourController@addTourPrice')->name('addTourPrice');
		Route::post('tour/update/price', 'Admin\TourController@updateTourPrice')->name('updateTourPrice');

		Route::get('tour/tour-report/{tourid}/{type}', 'Admin\TourController@getTourReport')->name('getTourReport');
		Route::get('supplier/report/{supplierId}/{supType}', 'Admin\SupplierController@getSupplierReport')->name('supplierReport');
		Route::post('supplier/report/{supplierId}/{supType}', 'Admin\SupplierController@sortHotelRateReport')->name('sortHotelRateReport');
		Route::get('supplier/restautant/info', 'Admin\SupplierController@getRestautantinfo')->name('getRestautantinfo');

		Route::get('report/supplier_booked', 'Admin\ReportController@reportSupplierBooked')->name('supplierBooked');


		Route::get('supplier/download/{supplierId}/pdf', 'Admin\SupplierController@getSupplierDownload')->name('getDownload');
		Route::get('cruise/program/{supplierId}', 'Admin\CruiseController@getCruiseProgram')->name('getCruiseProgram');
		Route::post('cruise/program/create', 'Admin\CruiseController@createCruiseProgram')->name('getCrProgram');
		Route::get('cruise/program', 'Admin\CruiseController@getProgram')->name('getProgram');
		Route::get('cruise/program/{cruiseid}/{programid}', 'Admin\CruiseController@getProgramEdit')->name('getProgramEdit');
		Route::post('cruise/update/program', 'Admin\CruiseController@updateCruiseProgram')->name('updateCrprogram');
		Route::get('cruise/applied/cabin', 'Admin\CruiseController@getCrCabin')->name('getCabin');
		Route::get('cruise/cabin', 'Admin\CruiseController@getCabin')->name('crCabin');
		
		Route::get('cruise/cabin/{proid}/{cabId}/apply', 'Admin\CruiseController@getApplyCrCabin')->name('applyCabin');
		Route::get('cruise/cabin/{proid}/{cabId}/apply/edit', 'Admin\CruiseController@getCrCabinEdit')->name('editCabin');
		Route::post('cruise/apply/cabin', 'Admin\CruiseController@applyCabinprice')->name('applyCabinprice');
		Route::post('cruise/cabin/update', 'Admin\CruiseController@updateCabinprice')->name('updateCabinprice');
		Route::get('cruise/cabin/price', 'Admin\CruiseController@getCabinprice')->name('getCabinprice');

		Route::get('hotel/apply/room/{hotelId}', 'Admin\HotelController@getRoomApply')->name('getRoomApply');
		Route::post('hotel/apply/room/now', 'Admin\HotelController@getRoomApplyNow')->name('getRoomApplyNow');
		Route::get('add/booking/row', 'Admin\AdminController@addBookinOption')->name('add_row');
		Route::get('option/remove', 'Admin\AdminController@getOptionDelete')->name('optionRemove');
		Route::get('option/find', 'Admin\AdminController@getFilter')->name('getFilter');
		Route::get('option/findlocaiton', 'Admin\AdminController@getOptionfind')->name('getOptionfind');
		Route::get('changebooking/status', 'Admin\OperationController@changebookingStatus');	
 
		Route::get("booking/hotelrate/find", "Admin\AdminController@bookingHotelRate")->name('bookingHotelRate');

		Route::get("hotelrate/remve/{hotel}/{booking}/{type}", "Admin\AdminController@delPriceRate")->name('RhPrice');
		// report section
		Route::get('report',"Admin\ReportController@tourReport")->name('report');
		Route::post("report", "Admin\ReportController@searchReport")->name("searchReport");
		Route::get('hotel/booking/{project}/{hotelid}/{bookid}/{action}',"Admin\ReportController@getHotelVoucher")->name('hVoucher');

		Route::get("project/booked/{projectNo}/{type}", "Admin\ReportController@getProjectBooked")->name('getProjectBooked');

		Route::get('project/report/{project}/{type}',"Admin\ReportController@getPreviewProject")->name('previewProject');
		Route::get("project/daily-operation-chart", "Admin\ReportController@getOperationDailyChart")->name('OpsDailyChart');

		Route::post("project/daily-operation-chart", "Admin\ReportController@searchOperationDailyChart")->name('searchPOSDailyChart');
		Route::get('project/invoice/{project}/{type}',"Admin\ReportController@getInvoice")->name('getInvoice');

		Route::get('arrival_report',"Admin\ReportController@getClientarrival")->name('clientArrival');
		Route::get('gross_p&l',"Admin\ReportController@getgrossprofit_loss")->name('gross_p&l');
		Route::get('quotation',"Admin\ReportController@getQuotation")->name('getQuotation');

		Route::post("arrival_report", "Admin\ReportController@searchArrival")->name("searchArrival");
		Route::get("statement", "Admin\ReportController@statement")->name("statement");
		Route::post("statement", "Admin\ReportController@searchStatement")->name("searchStatement");
		Route::get("payment_report", "Admin\ReportController@payment_report")->name("payment_report");
		Route::get("changestatus/{projectNum}","Admin\AdminController@changestatus")->name("changestatus");
		Route::post("gross_p&l", "Admin\ReportController@searchGross")->name("searchGross");

		Route::get('operation/{type}/voucher/{projectNo}/{ospBid}', 'Admin\OperationController@opsVoucher')->name('opsVoucher');
		Route::get('operation/{type}/reservation/{projectNo}/{ospBid}', 'Admin\OperationController@opsReservation')->name('opsReservation');
		Route::get('booking/{operation}/{project}', 'Admin\OperationController@applyOperation')->name('getops');
		Route::get('booking/transport/{project}/{supplier_id}', 'Admin\OperationController@bookingTransport')->name('getBookingVoucher');
		Route::post('booking/applied/transport', 'Admin\OperationController@assignTransport')->name('assignTransport');
		Route::post('booking/applied/restuatant', 'Admin\OperationController@assignResturant')->name('assignRestuarant');
		Route::post('booking/applied/entrance', 'Admin\OperationController@assignEntrance')->name('assignEntrance');
		Route::post("booking/updateTeetime", "Admin\OperationController@updateTeetime")->name('updateTeetime');
		Route::post('booking/applied/guide', 'Admin\OperationController@assignGuide')->name('assignGuide');
		Route::post('booking/applied/misc', 'Admin\OperationController@assignMisc')->name('assignMisc');

		Route::get('restautant/voucher/{project}/{restbooked}', 'Admin\OperationController@restVoucher')->name('restVoucher');
		Route::get('restautant/booking/{project}/{restbooked}', 'Admin\OperationController@restBooking')->name('restBooking');
		Route::get('project/report-request/{projectNo}', 'Admin\OperationController@getReportRequest')->name('requestReport');

		Route::get('window/uploaded', 'Admin\UploadController@fileUploaded')->name('fileUploaded');
		Route::get('window/remove/fileUploaded', 'Admin\UploadController@removeFile')->name('removeFile');
		Route::post("window/uploadfile", 'Admin\UploadController@uploadfile')->name('uploadfile');
		Route::post("window/uploadfile/only", 'Admin\UploadController@uploadOnlyFile')->name('uploadOnlyFile');
		Route::get("window/remove-image/logo", 'Admin\UploadController@RemoveLogo')->name('RemoveLogo');
		
		
		//chartofAccount
		Route::get('chartofaccount', 'Account\AccountController@chartofAccount')->name('chartofaccount');	
		Route::get('account/accForm', 'Account\AccountController@accForm')->name('accForm');
		Route::post("addNewAccount", "Account\AccountController@addNewAccount")->name("addNewAccount");
		Route::get('account/editAccForm/{id}', 'Account\AccountController@editAccForm')->name('editAccForm');
		Route::post("updateAcc/{id}", "Account\AccountController@updateAcc")->name("updateAcc");
		Route::get('removeaccount/{id}',"Account\AccountController@removeAcc")->name("removeAcc");
}); 
 

// ===============ACCOUNT SECTION========================================
Route::group(["middleware" => ["IsAccount"]], function(){
	Route::prefix("finance")->group(function () { 
		Route::get("/", "Account\JournalController@getJournal")->name("finance");
		Route::get("receivable/create", "Account\AccountController@getAccountReceivable")->name("getAccountReceivable");
		Route::get('report/{slug}', 'Account\ReportController@index')->name('index');
		Route::get("account-statement", 'Account\JournalController@getAccountStatement')->name('accountStatement'); 
		Route::get("balance-sheet", 'Account\JournalController@getBalanceSheet')->name('getBalanceSheet');
		// Route::get("Proj", "Account\AccountController@getAccountReceivable")->name("getAccountReceivable");
		Route::get("gross-profit-p&l", "Account\JournalController@getGrossProfitePL")->name("getGrossProfitePL");
		Route::POST("gross-profit-p&l", "Account\JournalController@searchGrossProfitPL")->name("searchGrossProfitPL");
		Route::get("gross-profit-p&l_preview", "Account\JournalController@getGrossProfitePLPreview")->name("getGrossProfitePLPreview");

		Route::get("posting-preview/{pro_no}/{type}", "Account\AccountController@previewPosting")->name("previewPosting");
		Route::get("project-preview", "Account\JournalController@getProjectPreview")->name("getProjectPreview");
		Route::get("posting", "Account\AccountController@getPostingAccount")->name("getPostingAccount");
		Route::post("posting", "Account\AccountController@findPostingAccount")->name("findPostingAccount");

		Route::get("payable/create", "Account\AccountController@getPayable")->name("getPayable");
		Route::get("accountPayable/{view_type}", "Account\AccountController@accountPayable")->name("accountPayable");
		Route::get("preivew_posted", "Account\AccountController@PreviewPosted")->name("PreviewPosted");
		Route::get("opening-balance", "Account\AccountController@openBalance")->name("openBalance");
		// Route::post("accountPayable", "Account\AccountController@searchPosted")->name("searchPosted");
		Route::get("journal", "Account\JournalController@getJournal")->name("journalList");
		Route::get("office-supplier", "Account\JournalController@getOfficeSupplier")->name("getOfficeSupplier");
		Route::get("journal/create", "Account\AccountController@getJournalJson")->name("getJournalJson");

		Route::get("outstanding", "Account\JournalController@getOutstanding")->name("getOutstanding");
		Route::get("trial-balance", "Account\JournalController@getTrialBalance")->name("getTrialBalance");
		
		Route::post("make-to-journal", "Account\AccountController@makeToJournal")->name("makeToJournal");


		Route::post("createReceivable", "Account\AccountController@createReceivable")->name("createReceivable");
		Route::post("editjournal-entry", "Account\AccountController@editJournal")->name("editJournal");
		Route::post("createJournal", "Account\AccountController@createJournal")->name("createJournal");
		Route::post("addAccountName", "Account\AccountController@addNewAccount")->name("addNewAccount");

		Route::post("createPayable", "Account\AccountController@createPayment")->name("createPayment");
		
		Route::post("addBankTransfer", "Account\AccountController@addBankTransfer")->name("addBankTransfer");

		Route::get("findOption",  "Account\OptionController@loadData")->name("loadData");	
		Route::get("filter_data", "Account\OptionController@filterData")->name("filterAccount");
		Route::get("removeOption",  "Account\OptionController@RemoveOption")->name("RemoveOption");	
		Route::get("BankTransfer", "Account\AccountController@getTransferForm")->name('transfer_form');
		Route::get("bank-preview/report", "Account\AccountController@getBankPreview")->name('getBankPreview');
		Route::get("getBankTransferred", "Account\AccountController@getBankTransferred")->name("getBankTransferred");
 
		// json string date_add()
		Route::get("accountjournal", "Account\AccountController@getJournalList")->name("getJournalList");
		Route::get("journal-entry", "Account\AccountController@getJournalEntry")->name("getJournalEntry");
		Route::get("accountjournalsingle", "Account\AccountController@getJournalEdit")->name("getJournalEdit");

		// report  
		Route::get("journal/view", "Account\JournalController@getJournalReport")->name("getJournalReport");
		Route::get("cash-book", "Account\JournalController@getCashbook")->name("getCashBook");
		Route::get("daily-cash-book", "Account\JournalController@getDailytCashbook")->name("getDailytCashbook");

		Route::post("udpateExchangeRate", "Account\AccountController@udpateExchangeRate")->name("udpateExchangeRate");

		Route::get("report", "Account\JournalController@getAccountReport")->name("getAccountReport");

		Route::get("profit-and-loss", "Account\JournalController@getProfitAndLoss")->name("getProfitAndLoss"); 
		Route::get("pnlbysegment", "Account\JournalController@pnlbysegment")->name("pnlbysegment");
		Route::post("pnlbysegment", "Account\JournalController@searchPnlbysegment")->name("searchPnlbysegment");
		
		Route::get("bank", "Admin\ThemeController@getBank")->name('getBank');
		Route::get("bank/add", "Admin\ThemeController@getBankForm")->name('getBankForm');
		Route::post("bank/add", "Admin\ThemeController@addBankInfo")->name('addBankInfo');
		// Route::get("journal/report", "Account\JournalController@getJouralReport")->name("getJouralReport");


		
		Route::get("payment_getway", "Account\PaymentController@getPaymentLink")->name('getPaymentLink');
		Route::get("create/payment_getway", "Account\PaymentController@createPaymentLink")->name('createPaymentLink');
		Route::post("add/payment_getway", "Account\PaymentController@addPaymentLink")->name('addPaymentLink');
		Route::post("add/payment_getways", "Account\PaymentController@editPaymentLink")->name('editPaymentLink');
		

		Route::post("add-new-account", "Account\AccountController@createAccountName")->name('createAccountName');

		Route::post("add-new-supplier", "Account\AccountController@AddNewSupplier")->name('AddNewSupplier');
		Route::get("email_sent", function(){
			return view("emails.payment.paymentlinkShipped");
		});
	});
}); 

Route::get("return_payment", "Account\PaymentController@paymentReturnData")->name('paymentReturnData');
Route::get("payment-view/{id}", "Account\PaymentController@getPaymentView")->name('getPaymentView');
Route::post("payment/payment_submit", "Account\PaymentController@paymentSubmit")->name('paymentSubmit');