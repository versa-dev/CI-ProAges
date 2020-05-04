var Colors = ["#4d4d4d","#5da5da","#faa43a","#60bd68","#f17cB0","#b2912f","#b276b2","#decf3f","#f15854"];
var ColorsExtended = randomColor({
	count: 40,
	luminosity: 'bright',
	seed: 'gus'
});

$(document).ready(function(){ 
	$(".toggleTable").on("click", function(e){
		e.preventDefault();
		var target = $(this).attr("data-target");
		var resize_target = $(this).attr("data-resize");
		var itag = $(this).find("i");
		itag.toggleClass("icon-signal");
		itag.toggleClass("icon-list-alt");

		var resize_cell = $(this).closest(".row").find(resize_target);
		resize_cell.toggle("fast");
		$(target).toggle("fast");

	});
	$(".imprimir").click(function(e){
		e.preventDefault();
		$(".print").removeClass("print");
		var printable = $(this.closest(".printable"))
		printable.addClass("print");

		window.print();
	});
});

function dynamicSort(property) {
    var sortOrder = 1;
    if(property[0] === "-") {
        sortOrder = -1;
        property = property.substr(1);
    }
    return function (a,b) {
    	a[property] = parseFloat(a[property]);
    	b[property] = parseFloat(b[property]);
        var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
        return result * sortOrder;
    }
}

function number_format(number, decimals){
	number = parseFloat(Math.round(number * 100) / 100).toFixed(decimals);
	number = number.toString();
    x = number.split(".");
	x1 = x[0];
	x2 = x.length > 1 ? "." + x[1] : "";
    x1 = x1.split(/(?=(?:...)*$)/);
    // Convert the array to a string and format the output
    number = x1.join(",");
    number = number+x2;
    return number;
}