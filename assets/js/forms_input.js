$(':input').focusin(function() {
	$(this).css('background-color', '#ECEDF2');	
});

$(':input').focusout(function() {
	$(this).css('background-color', 'white');	
});


// Using :input makes it apply formating to all inputs ie, textarea and text etc.
// :text would make formatting specific to text inputs.

//	$(':text').focusin(function() {
//		$(this).css('background-color', 'yellow');	
//	});
//	
//	$(':text').focusout(function() {
//		$(this).css('background-color', 'white');	
//	});
