
function stickyFooter(){

    positionFooter(); 
    function positionFooter(){
		//$("#contentFoot").css({position: "absolute",top:($(window).scrollTop()+$(window).height()-$("#contentFoot").height())+"px"})    
		//$("#contentFoot").css({position: "absolute", bottom: '0px' })    
    }
 
    $(window)
        .scroll(positionFooter)
        .resize(positionFooter)
}


    jQuery(document).ready(function($)
    {
            stickyFooter(); 
            
            var percent = ($("body").height() * 10)/100;
           

        $(window).resize(function() {
            stickyFooter();
            var percent = ($("body").height() * 10)/100;
            $(".tablescroll_wrapper").css("width", ($(window).width())+'px');
            $(".tablescroll_wrapper2").css("width", ($(window).width())+'px');
            $(".tablescroll_head").css("width", ($(window).width())+'px');
            $(".tablescroll_body").css("width", ($(window).width())+'px');
            $(".tablescroll_foot").css("width", ($(window).width())+'px');
            $(".tablescroll_head thead").css("width", ($(window).width())+'px');
            $(".tablescroll_foot tfoot").css("width", ($(window).width())+'px');

            $("#sorter-report1").css("height", percent+'px');
            $("#sorter-report2").css("height", percent+'px');
            $("#sorter-report3").css("height", percent+'px');
			$(".payment_table").css("height", percent+'px');
        });

        $("#sorter-report1").tablesorter({  // Vida report
        // sort on the first column and third column, order asc 
            sortList: [[0,0],[2,0]],
		// use save sort widget
			widgets: ["saveSort"]
        });
	    $("#sorter-report2").tablesorter({ // GMM report
        // sort on the first column and third column, order asc 
            sortList: [[0,0],[2,0]],
		// use save sort widget
			widgets: ["saveSort"]
        }); 
	    $("#sorter-report3").tablesorter({ // Autos report
        // sort on the first column and third column, order asc 
            sortList: [[0,0],[2,0]],
		// use save sort widget
			widgets: ["saveSort"]
        }); 
		
             $('.select1').ddslick({width:'120'});
			 $('.select6').ddslick({width:'120'});
             $('#select2').ddslick({width:'120'});
             $('#select4').ddslick({width:'120'});
             $('#select5').ddslick({width:'140'});
         
       
    });

        /*]]>*/