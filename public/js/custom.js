/*! Renta custom.js
 * ================
 * Custom JS application file for Rent v1. This file
 * should be included in all pages. It controls some layout
 * options and implements exclusive Rent.
 *
 * @Author  Bright saharia
 * @Support <http://www.support.com>
 * @Email   <bright@proisc.com>
 * @version 2.3.2
 * @license MIT <http://megamind.org>
 */
$(document).ready(function() {
	var advance_amount = 0;
	var current_settle_details = null;
	var buttonCommon = 
		{
      exportOptions: {

          format: {
              body: function ( data, row, column, node ) {
                  // Strip $ from salary column to make it numeric
                  /*return column === 5 ?
                      data.replace( /[$,]/g, '' ) :
                      data;*/
                return data;
              }
          }
      }
    };

    if (typeof column_array != 'undefined') {
    	buttonCommon.exportOptions['columns'] = column_array;
  	}

	var export_options = 
			{ 
				dom: 'Bfrtip',
				buttons: 
				[
					'pageLength',
          $.extend( true, {}, buttonCommon, {
              extend: 'copyHtml5'
          } ),
          $.extend( true, {}, buttonCommon, {
              extend: 'excelHtml5'
          } ),
          $.extend( true, {}, buttonCommon, {
              extend: 'pdfHtml5'
          } ),
          $.extend( true, {}, buttonCommon, {
              extend: 'csvHtml5'
          } ),
          $.extend( true, {}, buttonCommon, {
              extend: 'print'
          } ),
        ]
      };
		var $table = $('.datatable').DataTable(export_options);

	if(typeof columns_defs != "undefined") {
		$.each(export_options, function (key, value) {
			columns_defs[key] = value;	
		});
		console.log(columns_defs);
		reportTable = $('.reportTable').DataTable(columns_defs);
	}
	
	$('.datepicker').datepicker({
		autoclose : true,
		format : 'dd/mm/yyyy'
	});

	var old_year = null;

	if($('.chartMonthPicker').length) {

		$('.chartMonthPicker').datepicker({
			autoclose : true,
			format: "mm/yyyy",
			viewMode: "months", 
	  	minViewMode: "months",
	  	endDate : new Date(end_date)
		}).on('show', function(e) {
			var iconNode = $('.monthCalendar');

			$('.datepicker-dropdown').css({top:$(iconNode).offset().top + $(iconNode).height() + 17, left:$(iconNode).offset().left})
		}).datepicker("setDate", new Date(start_date)).on('changeDate', function ( ev ) {

			//console.log(ev)
			if(ev.date) {
					const date = parseInt(ev.date.getMonth() + 1) + '/' + ev.date.getFullYear();
					var locale = "en-us";
		    	var month = ev.date.toLocaleString(locale, { month: "long" });
		    	const str = month + '-' + ev.date.getFullYear();
		    	
		    	var rentInput = { month : parseInt(ev.date.getMonth() + 1), year : ev.date.getFullYear() };

		    	if(old_month != str) {
		    		end_date = ev.date.getFullYear() + '-' + parseInt(ev.date.getMonth() + 1) + '-' + ev.date.getDate();
		    		start_date = ev.date.getFullYear() + '-' + parseInt(ev.date.getMonth() + 1) + '-01';
		    		$('#monthText').text(str);

		    		$('#jsMonthReportSpan').text(str);
		    		//console.log(str)

		    		old_month = str;
		    		if (typeof month_report_ajax_url != 'undefined') {
			    		var form_data = { start_date : start_date };
			    			//console.log(form_data)
			    		loadAndSave.post(form_data, month_report_ajax_url, 'jsReportChartMonthPanel').then(function ( data ) {
			    			//console.log($.parseJSON(data.y_axis))

			    			monthChart.setTitle({text : "Income report for the month of " + str });
			    			monthChart.xAxis[0].setCategories(data.x_axis);
			    			monthChart.series[0].update({ data : $.parseJSON(data.y_axis) });

			    			$('#month_total').text(numeral(data.total_amount).format('0,0'));

					    }).fail(function ( error ) {

					    })
				  	}
				  	if ($('.jsDateForm').length) {
				  		$('#start_date').val(start_date);
				  		$('.jsDateForm').submit();
				  	}
		    	}
	    	}
		});
	}

	if($('.chartYearPicker').length) {

		$('.chartYearPicker').datepicker({
			autoclose : true,
			format: "yyyy",
			viewMode: "years", 
	  	minViewMode: "years",
	  	endDate : new Date(end_date)
		}).on('show', function(e) {
			var iconNode = $('.yearCalendar');

			$('.datepicker-dropdown').css({top:$(iconNode).offset().top + $(iconNode).height() + 17, left:$(iconNode).offset().left})
		}).datepicker("setDate", new Date(start_date)).on('changeDate', function ( ev ) {
			if(ev.date) {
				const year_str = ev.date.getFullYear();
				if(old_year != year_str) {
					$('#yearText').text(year_str);
					$('#jsYearReportSpan').text(year_str);
					var form_data = { year : year_str };
	    			//console.log(form_data)
	    		loadAndSave.post(form_data, year_report_ajax_url, 'jsReportChartYearPanel').then(function ( data ) {
	    			//console.log($.parseJSON(data.y_axis))

	    			yearChart.setTitle({text : "Income report for the year of " + year_str });
	    			yearChart.xAxis[0].setCategories(data.yearly_x_axis);
	    			yearChart.series[0].update({ data : $.parseJSON(data.yearly_y_axis) });

	    			$('#year_total').text(numeral(data.total_amount).format('0,0'));

			    }).fail(function ( error ) {

			    })
				}
			}
		});
	}

		$(document).on('click', '.monthCalendar', function(e) {
			$('.chartMonthPicker').datepicker('show');
		})

		$(document).on('click', '.yearCalendar', function(e) {
			$('.chartYearPicker').datepicker('show');
		})
	
	if($('.monthPicker').length) {
		var old_month = new Date(), locale = "en-us";
		const date_month = old_month.toLocaleString(locale, { month: "long" });
		old_month = date_month + '-' + old_month.getFullYear();

		

		$('.monthPicker').datepicker({
			autoclose : true,
			format: "mm/yyyy",
			viewMode: "months", 
	  	minViewMode: "months",
	  	endDate : new Date(end_date)
		}).on('show', function(e) {
			var iconNode = $('.monthCalendar');

			$('.datepicker-dropdown').css({top:$(iconNode).offset().top + $(iconNode).height() + 17, left:$(iconNode).offset().left})
		}).datepicker("setDate", new Date(start_date)).on('changeDate', function ( ev ) {
			//console.log(ev)
			if(ev.date) {
					const date = parseInt(ev.date.getMonth() + 1) + '/' + ev.date.getFullYear();
					var locale = "en-us";
		    	var month = ev.date.toLocaleString(locale, { month: "long" });
		    	const str = month + '-' + ev.date.getFullYear();
		    	
		    	var rentInput = { month : parseInt(ev.date.getMonth() + 1), year : ev.date.getFullYear() };

		    	if(old_month != str) {
		    		end_date = ev.date.getFullYear() + '-' + parseInt(ev.date.getMonth() + 1) + '-' + ev.date.getDate();
		    		start_date = ev.date.getFullYear() + '-' + parseInt(ev.date.getMonth() + 1) + '-01';
		    		$('#monthText').text(str);
		    		$('#jsReportMonthSpan').text(str);
		    		
		    		old_month = str;

		    		var form_data = { type : $('[name="report_type"]:checked').val(), start_date : start_date, end_date : end_date };
		    		loadAndSave.post(form_data, month_change_ajax_url, 'jsReportPanel').then(function ( data ) {
		    			reportTable.clear().draw();
		    			if(data.report_result.length) {
      					reportTable.rows.add(data.report_result).draw();
      				}
				    }).fail(function ( error ) {

				    })
		    	}
	    	}
		});
		

		$(document).on('click', '.monthCalendar', function(e) {
			$('.monthPicker').datepicker('show');
		})
	}
	var range_object = 
		{
			locale : {
				format : "DD/MM/YYYY",
			},
		};
	if (!is_admin && typeof max_min_range == 'undefined') {
		range_object['minDate'] = report_start_date;
		range_object['maxDate'] = report_end_date;
	}
	$('.dateRange').daterangepicker(range_object);

	$(document).on('click', '.searchReport', function( event ) {
		var date = $('.dateRange').val().split(' - ');
		var form_data = { start_date : date[0], end_date : date[1] };
		if (typeof custom_form_data != 'undefined') {
			Object.assign(form_data, custom_form_data);
		}
		loadAndSave.post(form_data, repot_between_date, 'jsReportPanel').then(function ( data ) {
			reportTable.clear().draw();
      if(data.monthly_report.length) {
        reportTable.rows.add(data.monthly_report).draw();
      }
      if (typeof data.start_date == 'string') {
      	$('#jsReportDateSpan').text(data.start_date + ' to ' + data.end_date);
      }
      if (typeof data.total_amount == 'string') {
      	$('#total_amount_date').text(numeral(data.total_amount).format('0,0'));
    	}
    	if (typeof data.total_amount == 'object') {
      	$.each(data.total_amount, function (key, value) {
      		$('#' + key).text(value);
      	})
    	}
		}).fail(function( error ) {

		})
	})

	$(document).on('change', '[name="report_type"]', function(e) {
    var form_data = { type : $(this).val(), start_date : start_date, end_date : end_date };
    $('#jsRoomReportSpan').text($(this).data('text'));
    loadAndSave.post(form_data, month_change_ajax_url, 'jsReportPanel').then(function ( data ) {
      reportTable.clear().draw();
      if(data.report_result.length) {
        reportTable.rows.add(data.report_result).draw();
      }
    }).fail(function ( error ) {

    })

  })

	var $select = 
		$('.select2').select2({
			width: '100%'
		});
	//console.log(file_path)
	var initial_preview = [];
	var caption = [];
	var file_size = 0;

	if(typeof file_path != 'undefined') {
		initial_preview = [file_path];
	}
	function previewConfig() {
		//console.log(file_path)
		var deferred = $.Deferred();
		if(typeof file_path != 'undefined') {
			var obj = new XMLHttpRequest();
			obj.open('HEAD', file_path, true);
			obj.onreadystatechange = function(){
			  if ( obj.readyState == 4 ) {
			    if ( obj.status == 200 ) {
			      //alert('Size in bytes: ' + obj.getResponseHeader('Content-Length'));
			      file_size = obj.getResponseHeader('Content-Length');
			      deferred.resolve(file_size);

			    } else {
			      //alert('ERROR');
			      deferred.resolve(0);
			   	}
			 	}
			};
			obj.send(null);
		} else {
			deferred.resolve(null);
		}
		return deferred.promise();
	}

	previewConfig().then(function(data) {

		if(typeof file_path == 'undefined') {
			return false;
		}
		if(data) {
			caption = [{caption: avatar, size: data, url: file_path}];
		}
		console.log(caption)
		
		var btnCust = '';
		$("#avatar").fileinput({
	    overwriteInitial: true,
	    maxFileSize: 1500,
	    showClose: false,
	    showCaption: false,
	    showBrowse: false,
	    browseOnZoneClick: false,
	    elErrorContainer: '#kv-avatar-errors-2',
	    msgErrorClass: 'alert alert-block alert-danger',
	    defaultPreviewContent: '<img src="' + ASSETS_PATH + '/default_avatar_male.jpg" alt="Your Avatar" style="width:160px">',
	    layoutTemplates: {main2: '{preview} ' +  btnCust + ' {remove} {browse}'},
	    allowedFileExtensions: ["jpg", "png", "gif"],
	    initialPreviewAsData: true,
	    initialPreview: initial_preview,
	    initialPreviewConfig: caption
		});
	})

	$(document).on('mouseenter', '.file-input img, .file-preview-other-frame', function(e) {
		var html = 
			'<div class="overlay">'+
				'<div class="outer-btn">'+
		    	'<a class="btn btn-primary browse-btn jsFileBrowse">Browse</a>'+
		    '</div>'+
		  '</div>';
		$(this).after(html);
		$(this).next().css({width : $(this).width(), height : $(this).height(), left : $(this).position().left, top : $(this).position().top});
	})

	$(document).on('mouseleave', '.file-input .overlay', function(e) {
		$(this).remove()
	})
	$(document).on('click', '.file-input .overlay', function(e) {
		$(this).closest('.form-group').find('#avatar').trigger('click');
	})
	
	$(document).on('click', 'a.jsDelete', function(e) {
		var $href = $(this).data('href');
		commonFunctions.showConfirmAlert('Confirm!', 'Are you sure to delete?').then(function (data) {
			location.replace($href);	
		})
	});

	$(document).on('click', '.jsModal', function(e) {
		var id = $(this).data('modal-id');
		$('#' + id).modal('show');
	})

	$('.selectAutoAjax').each(function() {
		var ele = $(this);
		var url = ajax_url[$(this).data('url')];
		$(this).autocomplete({
			serviceUrl: url,
			minChars: 0,
			showNoSuggestionNotice: true,
			noSuggestionNotice: 'Sorry, no matching results',
			onSelect: function (suggestion) {
				
				
			},
			onHint: function (hint) {
				ele.closest('.form-element').find('.autoajax').val(hint);
			},
			onInvalidateSelection: function() {
				//$('#selction-ajax').html('You selected: none');
				
			}
		});
	})
	
	$(document).on('click', '.jsSettlement', function () {
		var ele = $(this);

		rent_id = ele.data('id');
		advance = ele.closest('tr').find('td:nth-child(3)').text();
		var url = ajax_url.get_guest_details.replace("guest_id", rent_id);
		loadAndSave.get([], url, 'jsSettlmentPanel').then(function (data) {
			$.each(data.guest_income, function (key, value) {
				$('#' + key).text(value);
			});
			table_row = $table.row( $(this).parents('tr') );
			jqueryValidate.resetForm('settle_form');
			$('.jsIncharge').html('');
			if (data.check_incharge.is_incharge) {
				
				$.each(data.check_incharge.list_incharge, function (key, value) {
					$('.jsIncharge').append(
						'<div class="checkbox">'+
							  '<label><input type="checkbox" name="incharge_list[]" value="' + value.id + '">' + value.name + '</label>'+
						'</div>');
				});
			}
			var rent_details = data.rent_details;
			$('#advance').text(advance);
			advance_amount = advance;
			$('#checkin_date').text(rent_details.checkin_date);
			$('#advance_amount').val(advance);
			$('#jsPendingSpan').text(data.guest_income.balance);
			$('#jsRemainingBalanceSpan, #jsReturnSpan').text(0);
			$('#jsAdvanceSpan').text(advance);
			$('#settlementModal').modal('show');
		}).fail(function ( error ) {
			if(error && error.msg && error.msg[0]) {
				jqueryValidate.renderErrorToast(error.msg[0]);
			}
		})
	});

	$(document).on('click', '.jsSettlementSubmit', function () {
		var form_data = $('#settle_form').serializeArray();
		form_data.push({name: "id", value: rent_id });
		loadAndSave.post(form_data, ajax_url.update_settlement).then(function (data) {
			table_row.remove().draw();
			if (data.msg) {
				$('#settlementModal').modal('hide');
				commonFunctions.resetForm('settlementModal');
				jqueryValidate.renderSuccessToast(data.msg);
			}
		}).fail(function ( error ) {
			if(error) {
				jqueryValidate.resetError('settle_form');
				$.each(error.error, function (key, err) {
					console.log(key, err)
					jqueryValidate.insertError(key, err[0]);
				})
			}
		});
	});

	$(document).on('click', '.rent_table tbody tr.details-row', function () {
		var formData = { room_id : $(this).data('room-id') }
		var ele = $(this);
		$('.duplicateDiv').slideUp('slow', function(){
			$('.duplicateRow').remove();
		});
		var bgColor = "#fff";
		
		loadAndSave.post(formData, ajax_url.get_guest_rent).then(function (data) {
			
			var html = 
				'<tr class="duplicateRow" style="display:none;background:' + bgColor + '">'+
					'<td colspan=6>'+
						'<div class="col-sm-12 duplicateDiv" style="display:none;">'+
							'<table class="table table-striped table-bordered">'+
								'<thead>'+
									'<tr>'+
										'<th>Name</th>'+
										'<th>Email</th>'+
										'<th>Mobile no</th>'+
										'<th>Advance</th>'+
										'<th>Checkin date</th>'+
										'<th>Action</th>'+
									'</tr>'+
								'</thead>'+
								'<tbody>';
									$.each(data.guests, function( index, value ) {
										html += 
										'<tr>'+
											'<td>' + value.name + '</td>'+
											'<td>' + value.email + '</td>'+
											'<td>' + value.mobile_no + '</td>'+
											'<td>' + value.advance + '</td>'+
											'<td>' + value.checkin_date + '</td>'+
											'<td>'+
											'<a target="_blank" href="' + APP_URL + '/reports/' + value.guest_id + '/guest-income" class="btn btn-info btn-sm" style="margin-right:3px;" data-toggle="tooltip" title="View payment details">'+
												'<span class="glyphicon glyphicon-info-sign"></span>'+
											'</a>'+
											'<a href="' + APP_URL + '/rents/' + value.id + '/edit" class="btn btn-info btn-sm" style="margin-right:3px;">'+
												'<span class="glyphicon glyphicon-edit"></span>'+
											'</a>'+
											(value.is_remove ? '<a href="javascript:;" data-href="' + APP_URL + '/rents/' + value.id + '/destroy" class="btn btn-danger btn-sm jsDelete">'+
												'<span class="glyphicon glyphicon-trash"></span>'+
											'</a>' : '')+
										'</tr>';
									});
									
								html += 
								'</tbody>'+
							'</table>'+
						'</div>'+
					'</td>'+
				'</tr>';
			
			ele.after(html);
			$('.duplicateRow').slideDown('slow');
      $('.duplicateDiv').slideDown('slow');
		})
	})

	$(document).on('click', '.settle_table tbody tr.details-row', function () {
		var formData = { room_id : $(this).data('room-id') }
		var ele = $(this);
		$('.duplicateDiv').slideUp('slow', function(){
			$('.duplicateRow').remove();
		});
		var bgColor = "#fff";
		
		loadAndSave.post(formData, ajax_url.get_settle_rent).then(function (data) {
			
			var html = 
				'<tr class="duplicateRow" style="display:none;background:' + bgColor + '">'+
					'<td colspan=6>'+
						'<div class="col-sm-12 duplicateDiv" style="display:none;">'+
							'<table class="table table-striped table-bordered">'+
								'<thead>'+
									'<tr>'+
										'<th>Name</th>'+
										'<th>Email</th>'+
										'<th>Mobile no</th>'+
										'<th>Advance</th>'+
										'<th>Checkin date</th>'+
										'<th>Checkout date</th>'+
										'<th>Action</th>'+
									'</tr>'+
								'</thead>'+
								'<tbody>';
									$.each(data.guests, function( index, value ) {
										html += 
										'<tr>'+
											'<td>' + value.name + '</td>'+
											'<td>' + value.email + '</td>'+
											'<td>' + value.mobile_no + '</td>'+
											'<td>' + value.advance + '</td>'+
											'<td>' + value.checkin_date + '</td>'+
											'<td>' + value.checkout_date + '</td>'+
											'<td>'+
												'<a target="_blank" href="' + APP_URL + '/reports/' + value.guest_id + '/guest-income" class="btn btn-info btn-sm" style="margin-right:3px;" data-toggle="tooltip" title="View payment details">'+
													'<span class="glyphicon glyphicon-info-sign"></span>'+
												'</a>'+
											'</td>'+
										'</tr>';
									});
									
								html += 
								'</tbody>'+
							'</table>'+
						'</div>'+
					'</td>'+
				'</tr>';
			
			ele.after(html);
			$('.duplicateRow').slideDown('slow');
      $('.duplicateDiv').slideDown('slow');
		})
	})

	//Active menu link.
	var $activeElement = $('.sidebar-menu a[data-menu-id="' + active_menu.active_menu_id +  '"]');
	if ($activeElement.length) {
		$activeElement.closest('li').addClass('active');
		$activeElement.closest('ul.treeview-menu').addClass('menu-open');
		$activeElement.closest('li.treeview').addClass('active');
	}

	$(document).on('click', '.jaAddTypes', function () {
		if($('.side-nav').width() == 0) {
			$('.side-nav').css('width', '300px');
		} else {
			$('.side-nav').css('width', '0px');
		}
	});

	$(document).on('click', 'body', function ( event ) {
		if($('.side-nav').width() > 0 && !$(event.target).closest('.side-nav').length && event.target.id != 'slide-out') {
			$('.side-nav').css('width', '0px');
		}
	});

	$(document).on('click', '.jsCreateType', function ( event ) {
		var $input = $('input[name=category]');
		$input.closest('.form-group').removeClass('has-error');
		$('.side-nav .help-block').remove();
		if(!$input.val().trim().length) {
			$input.closest('.form-group').addClass('has-error').focus();
			$input.after('<small class="help-block">This field is required.</small>');
			return false;
		}
		var form_data = { category : $input.val() };
		loadAndSave.save(form_data, type_url).then(function ( data ) {
			$('.side-nav').css('width', '0px');
			$input.val('');
			// Create the DOM option that is pre-selected by default
	    var option = new Option(data.value, data.id, true, true);
	     // Append it to the select
    	$select.append(option);
    	// Update the selected options that are displayed
  		$select.trigger('change');

  		jqueryValidate.renderSuccessToast(data.msg);

		}).fail(function ( error ) {
			if(error && error.msg && error.msg[0]) {
				$input.closest('.form-group').addClass('has-error').focus();
				$input.after('<small class="help-block">' + error.msg[0] + '</small>');	
			}
		})
	})
	
	$(document).on('click', "#jsSendMessage", function () {
		$('[name="send_message"]').val(1);
		$('.jsDateForm').submit();
	})

	$(document).on('click', "#jsSettlementCheck", function () {
		current_settle_details = null;
		var form_data = { rent_id : rent_id, checkout_date : $('#checkout_date').val() };
		var url = ajax_url.get_settlement;
		loadAndSave.post(form_data, url, 'jsSettlmentPanel').then(function (data) {
			current_settle_details = data.amount;
			commonFunctions.updateSettlement(current_settle_details, advance_amount, false);
		}).fail(function ( error ) {
			if(error.error) {
				var msg = error.error[0];
				jqueryValidate.renderErrorToast(msg);
			}
		})

	})
	
	$(document).on('shown.bs.modal', '#settlementModal', function (event) {
		current_settle_details = null;
	})
	$(document).on('change', ".jsCurrentAmount", function () {
		if (current_settle_details) {
			commonFunctions.updateSettlement(current_settle_details, advance_amount, true)
		}
	});
	
	$('[data-toggle=confirmation]').confirmation({
    rootSelector: '[data-toggle=confirmation]',
    container: 'body'
  });


	$('#settlementModal').on('show.bs.modal', function (e) {
	  var anim = 'zoomInUp';
	  commonFunctions.animateModal(anim, 'settlementModal');
	})
	$('#settlementModal').on('hide.bs.modal', function (e) {
	  var anim = 'zoomOutUp';
	  commonFunctions.animateModal(anim, 'settlementModal');
	})
  
});