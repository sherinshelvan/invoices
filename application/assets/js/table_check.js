(function($){
  $(document).on("click", "table input[type='checkbox'].multi-checkbox.all", function(){
    var status = $(this).prop('checked');
    $("table input[type='checkbox'].multi-checkbox").prop('checked', status);    
  });
  $(document).on("click", "table input[type='checkbox'].multi-checkbox.item", function(){
    var total_records = $("table input[type='checkbox'].multi-checkbox.item").length;
    var checked       = $("table input[type='checkbox']:checked.multi-checkbox.item").length;
    if(checked >= total_records){
      $("table input[type='checkbox'].multi-checkbox").prop('checked', true);
    }
    else{
       $("table input[type='checkbox'].multi-checkbox.all").prop('checked', false);
    }
  });
 
})(jQuery);