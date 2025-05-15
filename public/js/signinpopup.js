
$(document).ready(function (e) {

	function closeAllModels(){
		$('.loginwrperoverlay, .modalBox').hide();
		$('body').removeClass("overhidenbody");
	}

	function openAuthModels(modelSelector) {
		closeAllModels();
		$(modelSelector).show();
		$('.loginwrperoverlay').show();
		$('body').addClass("overhidenbody");
	}

	function openShareBox(){
		closeAllModels();
		$(".shareBoxModal").show();
		$('.loginwrperoverlay').show();
		$('body').addClass("overhidenbody");
	}

	$(document).on("click", '.signupclick', function (e) {
		openAuthModels('.registerbox');
		e.preventDefault();
	});
    
	$(document).on("click", '.signupclickmain', function (e) {
		openAuthModels('.registerbox');
		e.preventDefault();
	});

	$(document).on("click", '.signuphostclickmain', function (e) {
		openAuthModels('.registervendorbox');
		e.preventDefault();
	});

	$(document).on("click", '.signinclick', function (e) {
		openAuthModels('.loginbox');
		e.preventDefault();
	});

	$(document).on("click", '.signinclickmain', function (e) {
		openAuthModels('.loginbox');
		e.preventDefault();
	});

	$(document).on("click", ".homePage .sharespacething .yellowbtn", function (e) {
		openAuthModels('.registervendorbox');
		e.preventDefault();
	});

	$(document).on("click", '.registerclose', function (e) {
		closeAllModels();
		e.preventDefault();
	});

	$(document).on("click", ".registervendorclose", function (e) {
		closeAllModels();
		e.preventDefault();
	});


	$(document).on("click", ".shareBoxModalOpen", function (e) {
		closeAllModels();
		openShareBox();
		e.preventDefault();
	});

	$(document).on("click", ".shareBoxModalClose", function (e) {
		closeAllModels();
		e.preventDefault();
	});

});
