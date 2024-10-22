<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
	      .btn_popup{        
	        margin-top: 0px;
	        margin-right: 0px;
	        margin-bottom: 0px;
	        padding: 0px;
	        border: 0px;
	        background: transparent;
	        overflow: hidden;
	        position: fixed;
	        z-index: 1;
	        width: 180px;
	        height: 30px;
	        right: -50px;
	        bottom: 0px;
	        display: block;
	      }
	      .form_popup{	      	
	        margin-top: 0px;
	        margin-right: 0px;
	        margin-bottom: -2px;
	        padding: 0px;
	        background: transparent;
	        overflow: hidden;
	        position: fixed;
	        z-index: 16000002;
	        height: 0;
	        width: 250px;
	        right: 8px;
	        bottom: 0px;
	        border:1px solid #e0c7c78f;
	        box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 3px 2px;
	        background-color: #ffffff;
	        border-top-left-radius: 5px;
	        border-top-right-radius: 5px;  
	      }
	      .user_active{
	        font-weight: 500;
	        font-size: 15px;   
	        padding: 5px 10px 5px 10px;
	        margin-bottom: 2px;
	        border-radius: 4px;
	      }
	      .user_active:hover{
	        background-color: #f0ecec;
	      }	   
	      .scrolling:hover::-webkit-scrollbar {
			width: 10px;
			}
		  .scrolling::-webkit-scrollbar {
			width: 8px;
			}
		  .scrolling::-webkit-scrollbar-track { // set style to track
			background : #555999;
			border-radius: 10px;
			}
		  .scrolling::-webkit-scrollbar-thumb { // sets style to thumb
			background : rgba(255,255,255,0.5);
			border-radius: 10px;
			box-shadow:  0 0 6px rgba(0, 0, 0, 0.5);
			}		 

    </style>
</head>
<body>
	<?php  
		$datas 		= \App\User::where(['banned'     => 0,
										'company_id' => Auth::User()->company_id,
									   ])
								->orderBy('id', 'DESC')
								->get();
        $dataCount  = 0;

        $today      = new DateTime('now', new DateTimeZone('Asia/bangkok'));
        $ft         = $today->format('y-m-d H:i:s ');

        // add last date to table user
		$add_active = \App\User::find(\Auth::User()->id);        
        $add_active->last_active = $ft;
        $add_active->save();
        
                                     
    ?>
        <div class="form_popup scrolling" style="transition: 1s; max-height: 300px;
	      	overflow-y: scroll;">
          <div class="text-right" style="padding: 0 10px; cursor: pointer;" id="hid">
            <i class="fa fa-times"></i>
          </div>
          <center><h4 style="margin-top: 0;">List User Active</h4> </center>
            @foreach($datas as $data)
            	<?php 
	            	$datetime1  = new DateTime($data->last_active);
			        $datetime2  = new DateTime($ft);
			        $interval   = $datetime1->diff($datetime2);                  
			        $day        = '';
			        $hour       = '';
			        $min        = '';                                            
			        $dateAt		=  date('h:i a', strtotime($data->last_active));
			        $month		=  date("F j", strtotime($data->last_active));                                   
						
			        if ( $interval->h > 0 and $interval->d == 0 and $interval->m == 0){
			            $hour   =  $interval->h.' Hr';
			        }elseif ( $interval->d == 1 and $interval->m == 0) {
			            $day    ='Yesterday at '.$dateAt;
			        }elseif( $interval->d>1 and $interval->m == 0 ){
			            $day    = ($interval->d).' Days at '.$dateAt;
			        }
			        elseif( $interval->m > 0 ){
			            $day    = ($month).' at '.$dateAt;
			        }else{
			            if($interval->i==0){
			                $min    ='Now';
			            }else{          
			                $min    =$interval->i.' mins a go';
			            }
			        } 

            	 ?>

              @if($data->isOnline()) 
                <?php $dataCount++ ?>
                <div class="user_active">                            
                  <img src="{{ $data->picture > 0 ? Storage::url($data->picture) : Storage::url('/avata/user_icon.png') }}" style="border-radius: 50%;width: 36px; height: 36px;">
                  {{ $data->fullname }}             
                  <i class="fa fa-circle text-success" style="font-size:13px;padding-left:5px;"> Online</i>
                </div>
              @else
              	<div class="user_active">                            
                  <img src="{{ $data->picture > 0 ? Storage::url($data->picture) : Storage::url('/avata/user_icon.png') }}" style="border-radius: 50%;width: 36px; height: 36px;">
                  {{ $data->fullname }} |
                  <i onmouseover="this.style.textDecoration='underline';" 
                  	 onmouseout="this.style.textDecoration='none';" 
                  	 style="{font-size: 12px; cursor: pointer;}" 
                  	 title="{{date('Y-F-j', strtotime($data->last_active)).' at '.$dateAt}}">
                  	{{isset($day)? $day: ''}}{{isset($hour)? $hour: ''}} {{isset($min)? $min: ''}}

                  </i>
	            </div>

              @endif          
            @endforeach
        </div>
        <div class="btn_popup" style="transition: 0.1s;">    
          <button id="pop" class="btn btn-success" style="padding:3px 12px;font-weight:600;">{{$dataCount}} User Active
            <i class="fa fa-user-o" style="padding: 5px;"></i></button>
        </div>

    <script type="text/javascript">
        $(document).ready(function(){
          $('#pop').on('click',function(){
            $('.form_popup').css({'height':'auto'});
            $('.btn_popup').css({'height':'0px'});
          });
          $('#hid').on('click',function(){
            $('.form_popup').css({'height':'0'});
            $('.btn_popup').css({'height':'30px'});
          });         
        });
      </script>
</body>
</html>