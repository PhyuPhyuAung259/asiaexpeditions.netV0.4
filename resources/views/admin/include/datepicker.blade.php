<script type="text/javascript">
$(function($){
	var nowTemp = new Date();
	var formatdate = "yyyy-mm-dd";
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	var checkin = $('#from_date').datepicker({
	  onRender: function(date) {
	    // return date.valueOf() < now.valueOf() ? 'disabled' : '';
	  }
	}).on('changeDate', function(ev) {
	  if (ev.date.valueOf() > checkout.date.valueOf()) {
	    var newDate = new Date(ev.date)
	    newDate.setDate(newDate.getDate() + 1);
	    checkout.setValue(newDate);
	  }
	  checkin.hide();
	  $('#to_date')[0].focus();
	}).data('datepicker');

	var checkout = $('#to_date').datepicker({
	  onRender: function(date) {
	    // return date.valueOf() < checkin.date.valueOf() ? 'disabled' : '';
	  }
	}).on('changeDate', function(ev) {
	  checkout.hide();
	}).data('datepicker');

	// booking date
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	var book_date = $(".book_date").datepicker({
		format: formatdate,
		onRender: function(date) {
			return date.valueOf() <= now.valueOf() ? '' : '';
		}
	}).on('changeDate', function(ev){
	    // $(".datepicker", this).hide();
	     $(this).datepicker('hide');
  	}).data('datepicker');
});

</script>