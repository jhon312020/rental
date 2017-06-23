/*! Renta custom.js
 * ================
 * Common functions JS application file for Rent v1. This file
 * should be included in all pages. It controls some layout
 * options and implements exclusive Rent.
 *
 * @Author  Bright saharia
 * @Support <http://www.support.com>
 * @Email   <bright@proisc.com>
 * @version 2.3.2
 * @license MIT <http://megamind.org>
 */
var commonFunctions = {
	showConfirmAlert (title, content) {
		var deferred = $.Deferred();
		$.confirm({
			title: title,
			content: content,
			buttons: {
				cancel: function() {
					deferred.reject({ success: false });
				},
				confirm: {
					text: "Yes I'am", // Some Non-Alphanumeric characters
					action: function() {
						deferred.resolve({ success: true });
					}
				}
			}
		});
		return deferred.promise();
	},
	ajaxDataTable (columns, url, id) {
		$('#' + id).DataTable({
				columnDefs: columns,
        processing: true,
        serverSide: true,
        ajax: url
		});
	},
	resetForm (form_id) {
		var $form = $('#' + form_id);
		$form.find('input:text').val('');
	},
	showOverlay (overlay_id) {
		var element = $('#' + overlay_id);
		var hz_padding = (element.innerWidth() - element.width())/2;
		var vz_padding = (element.innerHeight() - element.height())/2;
		var overlay_content = 
				'<div class="black-overlay jsOverlayLoader" style="width:' + element.innerWidth() + 'px;height:' + element.innerHeight() + 'px;margin-left:-' + hz_padding + 'px;margin-top:-' + vz_padding + 'px;">'+
					'<div class="spinner">'+
						'<div class="showbox">'+
						  '<div class="loader">'+
						    '<svg class="circular" viewBox="25 25 50 50">'+
						      '<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>'+
						    '</svg>'+
						  '</div>'+
						'</div>'+
					'</div>'+
				'</div>';
		element.prepend(overlay_content);
	},
	hideOverlay (overlay_id) {
		$('#' + overlay_id + ' .jsOverlayLoader').remove();
	},
	updateSettlement (current_settle_details, advance_amount, input_change) {
		var total_rent_amount = parseInt(current_settle_details.total_rent_amount);
		var total_income_amount = parseInt(current_settle_details.total_income_amount);
		var last_month_rent_details = current_settle_details.last_month_rent_details;
		var last_month_rent_amount = parseInt(last_month_rent_details.rent_amount);
		var pending_balance = parseInt(current_settle_details.pending_amount) - parseInt(advance_amount);
		var return_balance = 0;
		var pending_amount = current_settle_details.pending_amount;
		var last_month_electric_amount = 0;
		if (last_month_rent_details.electricity_amount > 0) {
			last_month_electric_amount = last_month_rent_details.electricity_amount;
		}

		var amountElement = $('#amount');
		var electricityElement = $('#electricity_amount');
		if (!input_change) {
			amountElement.val(last_month_rent_amount);
			electricityElement.val(last_month_electric_amount);
		}
		if (input_change) {
			var input_rent_amount = amountElement.val();
			var input_electric_amount = electricityElement.val();
			total_rent_amount = total_rent_amount - last_month_rent_amount - last_month_electric_amount + parseInt(input_rent_amount) + parseInt(input_electric_amount);
			pending_amount = total_rent_amount - total_income_amount;
			pending_balance = pending_amount - parseInt(advance_amount);
		}

		$('#jsPendingSpan').text(pending_amount);
		if (pending_balance < 0) {
			return_balance = pending_balance*-1;
			pending_balance = 0;
		}
		$('#jsRemainingBalanceSpan').text(pending_balance);
		$('#jsReturnSpan').text(return_balance);
		$('#rent').text(total_rent_amount);
		$('#balance').text(pending_balance);
	}
};