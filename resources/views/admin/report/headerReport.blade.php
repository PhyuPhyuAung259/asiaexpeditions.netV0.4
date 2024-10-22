<?php 
$comadd = \App\Company::find(1);
$logo =  Storage::url("avata/".$comadd->logo);
?>
<table class="table">
	<tr>
		<td style="padding-bottom: 0px;">{!! $comadd->address !!}</td>
		<td style="padding-bottom: 0px;" class="text-right" width="150px">
			<img src="{{$logo}}" style="width: 100%;">
		</td>
	</tr>
</table>
<div class="pull-right hidden-print" id="preview_layout" style="color: #009688; cursor: pointer;">
	<input type="hidden" name="preview-type" value="standard">
	<p>Wide View <i class="fa fa-reply"></i></p>
</div>
<div class="clearfix"></div>