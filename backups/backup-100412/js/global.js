/*
********************************************************************************
                           Global JavaScript for
                         PeterboroughNaturopath.ca
********************************************************************************
*/

	// jQuery to execute upon DOM ready:
	$(function(){

		// Remove divider on the last member list item 
		$("#Member ul li:last-child a").css({
			border: 0
		});

		$("#HomeButton").click(function(){
			window.location.href = "index.html";
		});

	
	});



/* 
                                      EOF
********************************************************************************
*/
