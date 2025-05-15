// JavaScript Document

$('.morefilterclick').click(function(e) {
    $('.filters').removeClass("beforemore");
	$('.resultsidebar').addClass("withmoreoption");
	$('.morefilteraply.btn.btn-success').prop("disabled", true); 
	if($('#bedrooms').val()!="" || $('#bathrooms').val()!="" || $('#beds').val()!="" || $('#atype input[type=checkbox]:checked').length != 0 || $('#ptype input[type=checkbox]:checked').length != 0 || $('#otype input[type=checkbox]:checked').length != 0)
	{
		$('#xtemp').val(1);
		
	}else if($('#bedrooms').val()=="" && $('#bathrooms').val()=="" && $('#beds').val()=="" && $('#atype input[type=checkbox]:checked').length == 0 && $('#ptype input[type=checkbox]:checked').length == 0 && $('#otype input[type=checkbox]:checked').length == 0){
		
		$('#xtemp').val(0);
		
		
	}
	
});
$('.morefiltercancel').click(function(e) {
    $('.filters').addClass("beforemore");
	$('.resultsidebar').removeClass("withmoreoption");
});
$('.morefilteraply').click(function(e) {
    $('.filters').addClass("beforemore");
	$('.resultsidebar').removeClass("withmoreoption");
	$('#xtemp').val(1);
	
});

// More options showing 

$('.moreoptionsubfclick').click(function(e) {
	var $this = $(this);
    $(this).parent('.filterchkwrp').siblings('.moreoptionsubfwrp').slideToggle(200);
	$(this).toggleClass("expanded");
	
	
	if ($this.hasClass("expanded")) {
            $this.html(" Less options <i class='fa fa-caret-up pdgS05'></i>");
        } else {
            $this.html("More options <i class='fa fa-caret-down pdgS05'></i>");
        }
	
});