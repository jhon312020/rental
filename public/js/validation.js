var $errorDiv = 
	'<div class="postal_codeformError parentFormfirstStepForm formError" style="opacity: 0.87; position: absolute; right: initial; margin-top: 0px;display:none;z-index:9;">'+
		'<div class="formErrorArrow formErrorArrowBottom">'+
			'<div class="line1"></div>'+
			'<div class="line2"></div>'+
			'<div class="line3"></div>'+
			'<div class="line4"></div>'+
			'<div class="line5"></div>'+
			'<div class="line6"></div>'+
			'<div class="line7"></div>'+
			'<div class="line8"></div>'+
			'<div class="line9"></div>'+
			'<div class="line10"></div>'+
		'</div>'+
		'<div class="formErrorContent">This field is required.</div>'+
	'</div>';
var jqueryValidate = {
	/*
	* This function used to show the error message below the corresponding form field.
	* @params 
			$formElement - [html] The form field which is going to be display the error message.
			$errorDiv - [html]
	* @return Boolean
	*/
	insertError: function($formElement, $msg) {
    if (!$formElement.next().hasClass('formError')) {
      $formElement.after($errorDiv);
      $formElement.next().css({
        'top': parseInt($formElement.position().top + $formElement.innerHeight()),
        'left': parseInt($formElement.position().left)
      });
      $formElement.next().fadeIn('slow', 'swing');
    } else {
      $formElement.next().css('opacity', 0);
      setTimeout(function() {
        $formElement.next().css('opacity', 0.87);
      }, 200);
    }
		if ($msg) {
			$formElement.next().find('.formErrorContent').html($msg);
		}
  },
	/*
	* This function used to remove the error message from the corresponding form field.
	* @params 
			$formElement - [html] The form field which is going to display the error message.
	* @return Boolean
	*/
	removeError: function($formElement) {
    if ($formElement.next().hasClass('formError'))
      $formElement.next().fadeOut('slow', 'swing').remove();
  },
	/*
	* This function used to reset the form field value to default value.
	* @params 
			$form - [html] This is the form tag or closest div for the group of form field which is going to be reset.
	* @return Boolean
	*/
	resetForm: function($form) {
    $form.find('input:text').val('');
    $form.find('.dropdown').each(function() {
			var $firstDropDownText = $(this).find('.jsDropDownMain').find('li:first').find('a').text();
			var $firstDropDownVal = $(this).find('.jsDropDownMain').find('li:first').data('value');
      $(this).find('input:hidden').val($firstDropDownVal);
      $(this).find('button').html($firstDropDownText + ' <span class="caret"></span>');
    });
  },
	/*
	* This function used to validate if the entered value is numeric or not.
	* @params 
			$formElement - [html] The form field which is going to be validate.
	* @return Boolean
	*/
	isNumeric: function($formElement, $errorDiv, val) {	
		var $msg = 'This field should be numeric';
		if (isNaN(val)) {
			jqueryValidate.insertError($formElement, $errorDiv, $msg);
			return false;
		} else {
			jqueryValidate.removeError($formElement);
			return true;
		}
	},
	/*
	* This function used to validate if the entered value is numeric or not.
	* @params 
			$formElement - [html] The form field which is going to be validate.
	* @return Boolean
	*/
	inputError:function($inputElement, $errorDiv) {
		$inputElement.after($errorDiv);
		$inputElement.next().css({
			'top': parseInt($inputElement.position().top + $inputElement.innerHeight()),
			'left': parseInt($inputElement.position().left)
		});
		$inputElement.next().fadeIn('slow', 'swing');
	},
	/*
	* This function used to validate particular form or div content.
	* @params 
			$target - [html] Next visible tab element.
			$errorDiv - [html]
	* @return Boolean
	*/
	validateTabForm:function($target, $errorDiv) {
		var bool = true;
		$('.formError').remove();
		var $currentTab = $('.jsTabPane:visible');
		$currentTab.find('.secondtab').find('li').each(function() {
			var id = $(this).find('a').attr('href');
			//if the current tab is equal to Next visible tab element
			if(id == $target.attr('href')) {
				return false;
			}
			//if the current tab is risk factor tab then show the toast error message
			if($(this).find('a').attr('aria-controls') == 'step4') {
				if($currentTab.find('.riskTable').find('tbody').find('tr').length == 0) {
					commonFunction.isVisibleTab(id);
					bool = false;
					commonFunction.renderErrorToast();
					return false;
				}	
			} else {
				$(id).find('input:text, input:password, input:hidden').each(function() {
					var type = $(this).attr('type');
					switch(type) {
						case 'text':
							if(!$(this).val().trim() && !$(this).closest('.jsHiddenDiv').hasClass('hideDiv')) {
								commonFunction.isVisibleTab(id);
								validate.insertError($(this), $errorDiv);
								bool = false;
							}
							break;
					}
				});
				if(!bool) {
					return false;
				}
			}
		})
		if(!bool){ 
			return false;
		}
		return true;
	},
	validateInput (input_type, input_val, input_name, type = null, date_two = null, other_key = null) {
		console.log(input_type, input_val, input_name, type)
		switch (input_type) {
			case 'mobile_no':
				return jqueryValidate.isValidMobileNo(input_val, input_name);
				
				break;
				
			case 'email':
				return jqueryValidate.isValidEmail(input_val, input_name);
					
				break;
				
			case 'integer':
				return jqueryValidate.isValidInteger(input_val, input_name);
					
				break;
				
			case 'required':
				return jqueryValidate.isValidRequired(input_val, input_name);
					
				break;
				
			case 'decimel':
				return jqueryValidate.isValidDecimel(input_val, input_name);
				
				break;
				
			case 'date':
				console.log('date changed')
				return jqueryValidate.isValidateDate(input_val, type, date_two, input_name, other_key);
				break;
				
			default:
				break;
		}
	},
	isValidMobileNo (input, input_name) {
		var reg = /^\d{10}$/;
		var isValid = reg.test(input);
		return { valid : isValid, msg : "Invalid " + input_name };
	},
	isValidInteger (input, input_name) {
		console.log(input, input_name)
		var reg = /^\d*$/;
		var isValid = reg.test(input);
		return { valid : isValid, msg : "Invalid " + input_name };
	},
	isValidRequired (input, input_name) {
		console.log(input, input_name)
		var input_valid = { valid : true, msg : "Invalid " + input_name };
		if(!input.trim().length) {
			input_valid.valid = false;
		}
		
		return input_valid;
	},
	isValidEmail (input, input_name) {
		var reg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		var isValid = reg.test(input);
		return { valid : isValid, msg : "Invalid " + input_name };
	},
	isValidDecimel (input, input_name) {
		var reg = /^\d*$/;
		var isValid = reg.test(input);
		return { valid : isValid, msg : "Invalid " + input_name };
	},
	isValidateDate (input, type, date_two, input_name, other_key) {
		console.log('date check')
		console.log(input, type, date_two, input_name, other_key)
		if (jqueryValidate.isValidDate(input))  {
			
			if(!type) {
				return { valid : true, msg : "Invalid date" };
			} else {
				if(date_two && jqueryValidate.isValidDate(date_two)) {
					var date_1 = input.split('/');
					var date1 = new Date(date_1[2], date_1[0] - 1, date_1[1]);
					
					var date_2 = date_two.split('/');
					var date2 = new Date(date_2[2], date_2[0] - 1, date_2[1]);
					
					if(type == 'min') {
						if(date_1 < date_2) {
							return { valid : true, msg : input_name + " should be less than " + other_key + " date" };
						}
					} else {
						if(date_1 > date_2) {
							return { valid : true, msg : input_name + " should be greater than " + other_key + " date" };
						}
					}
				} else {
						return { valid : true, msg : input_name + " is invalid" };
				}
			}
		} else {
			return { valid : false, msg : input_name + " is invalid" };
		}
	},
	isValidDate : function (date) {
    var bits = date.split('/');
    
    //console.log(bits[0] - 1)
    
    var d = new Date(bits[2], bits[1] - 1, bits[0]);
    
    //console.log(d.getFullYear(), bits[2], d.getMonth(), bits[0])
    
    return d.getFullYear() == bits[2] && d.getMonth() + 1 == bits[1];
  },
	renderErrorToast : function(msg) {
		$.toast().reset('all');
		$.toast({
			heading: 'Error',
			text: msg,
			position: 'top-right',
			icon: 'error',
			stack: false
		})
	},
	renderSuccessToast : function(msg) {
		$.toast().reset('all');
		$.toast({
			heading: 'Success',
			text: msg,
			position: 'top-right',
			icon: 'success',
			stack: false
		})
	},
	filterArray : function (array, guest_ids) {
		var deferred = $.Deferred();
		//console.log(array, guest_ids);
		
		var finalArray = array.filter(function(arr, index) {
			
			if(arr.guest_id && guest_ids.indexOf(arr.guest_id) != -1 && arr.selected) {
				//console.log(guest_ids.indexOf(arr.guest_id), arr.guest_id)
				guest_ids.splice(guest_ids.indexOf(arr.guest_id), 1);

			}
			return !arr.selected;
		});
		
		deferred.resolve({ grid : finalArray, guest_ids : guest_ids });
		
		return deferred.promise();
	}
};