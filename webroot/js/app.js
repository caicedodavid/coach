$(document).ready(function(){
	var d = new Date();
    d.setDate(d.getDate() - 1);

    var birthdateOptions = {
        format: 'YYYY-MM-DD',
        viewMode: 'years',
        maxDate: d,
        useCurrent : false,
        defaultDate : $('#birthdate').attr("defaultDate")
    };
    var payDateOptions = {
        format: 'YYYY-MM-DD',
        minDate: d,
        disabledDates: [d],
        useCurrent: true
    };
    var sessionDateOptions = {
        format: 'YYYY-MM-DD',
        minDate: d,
        disabledDates: [d],
    };
    var sessionTimeOptions = {
        format: 'HH:mm',
        stepping: 30,
    };
    $('#payment-date').datetimepicker(payDateOptions['defaultDate'] = $('#birthdate').attr("defaultDate"));
    $('#date').datetimepicker(birthdateOptions);
    $('#date1').datetimepicker(sessionDateOptions);
    $('#session-time').datetimepicker(sessionTimeOptions);

	$(document).on('click', '#pagination-button a', function () {
		var thisHref = $(this).attr('href');
		if (!thisHref) {
			return false;
		}
		$.get(thisHref, function(data) {
			var users = $(data).find('#items');
			var paging = $(data).find('div.paging');
			$('#items').append(users);
			$( "div.paging" ).replaceWith(paging);
			$('span.stars').stars();
		});
		return false;
	});

	$('div.pagination').on('click', 'a', function () {
		var thisHref = $(this).attr('href');
		$('$ajaxContainer').load(thisHref, function() {
			$('html, body').animate({
				scrollTop: $('$ajaxContainer').offset().top
			}, 200);
		});
		return false;
	});

	$.fn.stars = function() {
		return $(this).each(function() {
			// Get the value
			var val = parseFloat($(this).data('rating'));
			// Make sure that the value is in 0 - 5 range, multiply to get width
			var size = Math.max(0, (Math.min(5, val))) * 16;
			// Create stars holder
			var $span = $('<span/>').width(size);
			// Replace the numerical value with stars
			$(this).html($span);
		});
	};

	$(function() {
		$('span.stars').stars();
	});

	$(document).on('click', '.paging a', function () {
		var thisHref = $(this).attr('href');
		if (!thisHref) {
			return false;
		}
		$('#pagination-container').fadeTo(300, 0);
		$('#pagination-container').load(thisHref.concat(' #pagination-container'), function() {
			$(this).fadeTo(200, 1);
		});
		return false;
	});

	$('#accept').click(function(){
		var param1 = $(this).attr("name"); 
		$.ajax({
			url: "approveSession",       
			type: "POST",
			dataType : 'json',
			async: true,             
			data: {session:param1},               
			success: function(data) {
				$(this).closest(".row").replaceWith('<div class="alert alert-success"><strong>The session was scheduled.</div>');
			}
		});
	});

	$('#start').click(function(){
		var sessionId = $(this).attr("name"); 
		var url = "/sessions/updateStartTime/"+ sessionId;
		$.ajax({
			url: url,
			type:"GET",
			dataType : 'json',
			async: true,
		});
	});

	$('.rate-input').rating({displayOnly: true, step: 0.5});

	$( "#topic-selector" ).change(function() {
		var coachId = $(this).attr("coach-id");
		var topicId= this.options[this.selectedIndex].value
		var url = "/sessions/add/" + coachId + "/" + topicId;
		window.location.replace(url);
	});

	$('#pay-button').click(function() {      
		var $inputs = $('#payment-form :input:checked');
		var value = 0;
		$inputs.each(function() {
			if((this.getAttribute("price")) && (this.getAttribute("placeholder") !== null)){
				value += Number(this.getAttribute("price"))
			}
		});

		var list = document.getElementById("price-text");
		var newItem = document.createElement("h3");
		newItem.setAttribute('id', 'price-text');
		var textnode = document.createTextNode("You are going to pay to this coach " + value.toString() + "$");
		newItem.appendChild(textnode);
		$("#price-text").replaceWith(newItem);
		$("input[name='total']").val(value);
	});

	$('#payment-form').click(function() {  
		var checkboxes = $("input[type='checkbox']"),
		submitButt = $("#pay-button"); 
		checkboxes.click(function() {
			submitButt.attr("disabled", !checkboxes.is(":checked"));
		});
	});

	$(document).on("click", ".cancel-session", function () {
		var sessionId = this.getAttribute('id');
		$("input[name=id]").val(sessionId);
	});

	$('#categories-select').multipleSelect({
		filter: true
	});

	function checkSelected(select) {
		var ids = select.attr("ids");
		if (ids == null){
			return;
		}
		var selected = JSON.parse(select.attr("ids"));
		select.multipleSelect("setSelects", selected);
	}

	checkSelected($('#categories-select'));

	$("div.educo-theme").attr("style", "width:100%");

	$('a.select-category').click(function() {
		var categoryId = this.getAttribute('category-id'); 
		$("#category-id").children().each(function () {
			if (this.value == categoryId){
				this.setAttribute('selected', true);
			}
		});
		$("#search-form").submit();
	});

	if (document.cookie.indexOf("timezone") < 0) {
		// set a new cookie
		expiry = new Date();
		expiry.setTime(expiry.getTime()+(24*60*60*1000)); // one day

		// Date()'s toGMTSting() method will format the date correctly for a cookie
		document.cookie = "timezone=" + moment.tz.guess();
	}
});