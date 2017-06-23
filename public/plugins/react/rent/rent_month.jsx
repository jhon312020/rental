//https://www.youtube.com/watch?v=OKRu7i49X54
//console.log(ajax_url);
//var data = [["test"], ["test1"], ["test2"], ["test3"], ["test4"], ["test5"]];

//console.log($.type(formData));

var data = rentData;

var inactiveRentData = trashRentData;

//console.log(data, dateMonth)

var objDate = new Date(dateMonth),
    locale = "en-us",
    month = objDate.toLocaleString(locale, { month: "long" });

var dateFormat = month + '-' + objDate.getFullYear();

var monthYear = objDate.getMonth() + 1 + '-' + objDate.getFullYear();

var rentInput = { month : objDate.getMonth() + 1, year : objDate.getFullYear() };

var validateInput = { amount : "integer", mobile_no : "integer" };

var dateInput = [];

//console.log(data);

var ignoreHeight = ["top_height", "max_height"];

var empty_data = { room_no : "", name : "", email : "", mobile_no : "", amount : "", styles : { max_height : 35, top_height : 0 } };

var header_data = { checkbox : "", room_no : "Room no", name : "Name", email : "Email", mobile_no : "Mobile no", checkin_date : "Checkin date", no_of_days_stayed : "No of days stayed", amount : "Amount", electricity_amount : "Electricity amount", pending_amount: "Pending amount", total_pending_amount: "Total pending amount" };

var editable_header = [ "room_no", "no_of_days_stayed", "pending_amount", "total_pending_amount" ];

const inputType = { rent_amount_received : "radio" };

const propTypes = 
	{ 
		name : React.PropTypes.string.isRequired,
		mobile_no : React.PropTypes.number.isRequired,
		email : React.PropTypes.string.isRequired,
	} ;

var rent_incomes_form = {amount : '', notes : '', date_of_income : ''};

var containerWidth = 0;

class Grid extends React.Component {
	constructor(props) {
		super(props);
		this.state = props;
		//console.log(props)
	}

	showInput (eventName, key, styles, tabIndex) {

		//console.log(key)
		if(editable_header.indexOf(key) == -1) {
			var trData = this.state.trData;
			var top = styles.top.replace ( /[^\d.]/g, '' );
			var left = styles.left.replace ( /[^\d.]/g, '' );
			var defaultTop = parseInt(top);
			var defaultLeft = parseInt(left);
			var styles = { left : defaultLeft + 'px', top : defaultTop + 'px' };
			//const textValue = trData[key];

			if(eventName == 'keyup') {
				
				var stringvalue = String.fromCharCode(event.keyCode);
				if(!(stringvalue.match(/^[0-9a-zA-Z]+$/))) {
					return false;
				}
				if(stringvalue.trim()) {
					var textValue = stringvalue;
				} else {
					return false;
				}

			} else {
				var textValue = trData[key];	
			}

			//console.log(textValue)

			var node = this.refs['gridCell_' + key ];
			
			this.props.onChangeParentStyle(styles, key, this.state.keyIndex, trData['styles']['max_height'], node, textValue, tabIndex);

		
		}
  		//console.log(this)
  }

  componentWillMount () {

  }

	componentDidMount() {
		// Call select2 on your node
    
		var checkbox = this.refs.checkbox;
		var _this = this;
		$(checkbox).iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_flat-green'
    }).on('ifChanged', function(event) {

    	var val = event.target.checked ? 1 : 0;
    	_this.changeCheckboxVal("rent_amount_received", val);

    });

		var header = this.state.header;

		var _this = this;
		
		var tr = this.state.trData;

		//console.log(tr)

		Object.keys(header).map(function(key, index) {
			if(key != 'checkbox') {
				var node = _this.refs['gridCell_' + key ];
				var height = $(node).height() + 15;
				
				if(height < 35) {
					var height = 35;
				}

				if(!tr['styles']) {
					tr['styles'] = {};					
				}

				tr['styles'][ key + '_height'] = height;
				//console.log(node, $(node).height());
			}
		});

		var arr = [];
   	$.each( tr['styles'], function ( key_name, value ) { 
   		
   		if($.inArray(key_name, ignoreHeight) == -1)  {
   			arr.push(value)
   		}
   			
   	});

		var max = Math.max.apply( null, arr );

		//console.log(max)

		tr['styles']['max_height'] = max;

		this.setState({ trData : tr })
  }

  changeCheckboxVal (key, value) {

  	console.log(key)

  	var rowData = this.state.trData;
  	var inputName = header_data[key];
  	var _this = this;
  	
  	var form_data = { id : rowData.id, guest_id : rowData.guest_id, column_key : key, column_value : value, rent_id : rowData.rent_id };

		loadAndSave.save(form_data, ajax_url.update_rent_income).then(function ( data ) {

			//console.log(index, gridData, this.state.key, event.target.value)
			rowData.rent_income_update = value;

			console.log(rowData)
			//console.log(gridData)
			//this.setState({ grid : { index : trData } })
			_this.setState({ trData : rowData }, () => {
				
			});

			jqueryValidate.renderSuccessToast(inputName + " updated successfully!");

		}).fail(function ( error ) {

			jqueryValidate.renderErrorToast(error.error[0]);

		});


  }

	componentWillUpdate () {
		//console.log(this.state)
	}

  getHeight() {
  	
  	console.log('height inline-block')
  }

  updateFocusState (rowIndex, columnIndex) {
  	
  	const nodes = { element : this.refs['reactGridCell_' + rowIndex +'_'+ columnIndex], index : columnIndex };
  	this.props.onChangeParentFocus(nodes);

  }

  updateFocusStateInput (key, styles, tabIndex, rowIndex, columnIndex) {
  	
  	const nodes = { element : this.refs['reactGridCell_' + rowIndex +'_'+ columnIndex], index : columnIndex };
  	this.props.onChangeParentFocus(nodes);

  	if(editable_header.indexOf(key) == -1) {
	  	var _this = this;
			//console.log(key)
			var trData = this.state.trData;
			var top = styles.top.replace ( /[^\d.]/g, '' );
			var left = styles.left.replace ( /[^\d.]/g, '' );
			var defaultTop = parseInt(top);
			var defaultLeft = parseInt(left);
			var styles = { left : defaultLeft + 'px', top : defaultTop + 'px' };
			
			var node = this.refs['gridCell_' + key ];

			
			var textValue = trData[key];	
			
			_this.props.onChangeParentStyle(styles, key, _this.state.keyIndex, trData['styles']['max_height'], node, textValue, tabIndex);
		}

  }

  changeInput () {
  	
  }
  
  selectRow () {
  	var selected = true;
  	var rowData = this.state.trData;
  	//this.state.trData.slice();
  	if(rowData.selected) {
  		rowData.selected = false;
  	} else {
  		rowData.selected = true;
  	}

  	this.setState({ trData : rowData });

  	//console.log(rowData, this.state)

  }

  getRentDetails (rowIndex) {
  	var rowData = this.state.trData;
  	this.props.showSlide(rowData, this.state.keyIndex);
  }

	render() {
		
	//console.log(this.state)

	var header = this.state.header;
	var left = 30;
	var increment = 150;

	var rows = function (tr, top) {
		//console.log(tr, top)
		
	}

	//console.log(this.propTypes);

	var top = this.state.topHeight;
	var tr = this.state.trData;
	var trIndex = this.state.keyIndex;

	//console.log(tr['styles'])
	//console.log(tr)
	return  (
		<div className={"react-grid-Row react-grid-Row--even " + (tr['selected'] ? 'row-selected' : '') } style={{ "overflow" : "hidden" , "contain" : "layout"}}>
		{Object.keys(header).map(function(key, index) {

				var headerStyle = {"position" : "absolute" , "width" : "30px" , "height" :  tr['styles'] && tr['styles']['max_height'] ?  tr['styles']['max_height'] + "px" : "35px"  , "left" : "0" , "contain" : "layout", "top" : tr['styles'] && tr['styles']['top_height'] ?  tr['styles']['top_height'] + "px" : "0px", paddingRight : '8px', paddingLeft : '8px'};

				//console.log(headerStyle)

				//console.log(key)
				 
				 //console.log(key)

				

				 	var tabIndex = trIndex *10 + index;
				 	//console.log(tabIndex)
				  if (index == 0) {

				  	return (
				  	<div tabIndex={tabIndex} className="react-grid-Cell react-grid-Cell--locked" style={headerStyle} key={index}>
							<div className="react-grid-Cell__value">
								<span>
									<div className="react-grid-checkbox-container">
										<input className="react-grid-checkbox" defaultChecked={tr['selected_' + trIndex]} onChange={this.selectRow.bind(this)} type="checkbox" name={"checkbox" + trIndex + key} />
										<label htmlFor={"checkbox" + trIndex + key} className="react-grid-checkbox-label"></label>
									</div>
								</span>
								<span> </span>
								<span> </span>
							</div>
						</div>
						)

				  }

				  headerStyle.left = left + 'px';
					left += increment;
				 	headerStyle.width = increment + 'px';

				 	if(inputType[key]) {
				 		if(inputType[key] == 'radio') {

				 			headerStyle.textAlign = "center";

				 			return (	
				  		<div tabIndex={tabIndex} className="react-grid-Cell" style={headerStyle} key={index} onFocus={this.updateFocusState.bind(this, trIndex, index)} ref={"reactGridCell_" + trIndex + '_' +  index}>
					      <div className="react-grid-Cell__value">
					      	
					         <span>
					            <label>
			                  <input type="checkbox" ref="checkbox" defaultChecked={ tr[key] == 1 ? true : false } onChange={this.changeCheckboxVal.bind(this, key)} />
			                </label>
					         </span>
					      	
					      </div>
					   </div>
					   )

				 		}
				 	} else {
				  	return (	
				  		<div tabIndex={tabIndex} className="react-grid-Cell" style={headerStyle} key={index} onDoubleClick={this.showInput.bind(this, 'dblclick', key, headerStyle, tabIndex + 1)} onFocus={this.updateFocusStateInput.bind(this, key, headerStyle, tabIndex + 1, trIndex, index)} ref={"reactGridCell_" + trIndex + '_' +  index} onKeyUp={this.showInput.bind(this, 'keyup', key, headerStyle, tabIndex + 1)}>
				  			{ key == 'room_no' || key == 'total_pending_amount' ? <i data-toggle="tooltip" title="View rent details" data-container="body" className="fa fa-eye view-icon" onClick={this.getRentDetails.bind(this, index)}></i> : ''}
				  			{ key == 'room_no' || key == 'total_pending_amount' ? <a className="info-a" href={APP_URL + '/reports/' + tr.guest_id + '/guest-income'} target="_blank" ><i className="fa fa-info-circle"></i></a> : ''}
					      <div className="react-grid-Cell__value">
					      	
					         <span>
					            <div ref={"gridCell_" + key} title={tr[key]}>{tr[key]}</div>
					         </span>
					      	
					      </div>
					   </div>
					   )
				  }

			}, this)}
		</div>
	)
		
	}
}

//Grid.propTypes = propTypes;

class Guest extends React.Component {
	
	constructor(props, context) {
		super(props, context);
		this.state = { grid : data, containerWidth : 0, textAreaStyle : {left : '0px', top : '0px', height : 'auto', minHeight : '0px'}, isShowTextArea : false, key : null, index : null, minHeight : '0px', domNode : null, textInputValue : null, tabIndex : 0, nextId : null, dateFormat : dateFormat, rentInput : rentInput, changeIcon : true, isErrorInput : true, errorSpanStyle : {left : '0px', top : '0px', height : 'auto', position : "absolute", zIndex : 9, width : "150px", background : "red", color : "#fff" }, errorSpanText : null, isActive : "current", trash : inactiveRentData, textVisible : false, slideStyle : { height : "0px" }, rent_incomes: $.extend(true, {}, rent_incomes_form), trData:{}, latestRent: [], incomeIndex : '' };

		this.isTabbed = false;
		this.focusElement = null;
		this.mouse_is_inside = false;
		this.monthYear = monthYear;
		//console.log(this.state.grid)
	}

	addNew() {
		const arr = this.state.grid;

		var new_row = $.extend(true, {}, empty_data);

		if(arr.length) {
			const lastElement = arr[arr.length - 1];

			//console.log(lastElement.styles.top_height + lastElement.styles.max_height)


			var top_height = lastElement.styles.top_height + lastElement.styles.max_height;

			//console.log(top_height)

			new_row.styles.top_height = top_height;
		}

		var id = this.state.nextId;

		console.log(id)

		new_row.id = id;

		//console.log(empty_data)

		//console.log(arr)
		//arr.push(new_row);
		//console.log(arr)

		this.setState({ grid : this.state.grid.concat(new_row), nextId : id + 1 });

	}
	
	updateRowHeight () {

		var grid = this.state.grid;

		//console.log(grid)

		var height = 0;
		$.each(grid, function(index, value) {

			//console.log(height)

			if(grid[index]['styles']) {

				if(index == 0) {
					grid[index]['styles']['top_height'] = 0;
				} else {
					grid[index]['styles']['top_height'] = grid[index - 1]['styles']['top_height'] + grid[index - 1]['styles']['max_height'];
				}

			} else {
				grid[index]['styles'] = {};
				if(index == 0) {
					grid[index]['styles']['top_height'] = 0;
					grid[index]['styles']['max_height'] = 35;
				} else {
					
					grid[index]['styles']['max_height'] = 35;
					grid[index]['styles']['top_height'] = grid[index - 1]['styles']['top_height'] + grid[index - 1]['styles']['max_height'];

				}
			}
			
		});

		var trash = this.state.trash;

		$.each(trash, function(index, value) {

			//console.log(height)

			if(trash[index]['styles']) {

				if(index == 0) {
					trash[index]['styles']['top_height'] = 0;
				} else {
					trash[index]['styles']['top_height'] = trash[index - 1]['styles']['top_height'] + trash[index - 1]['styles']['max_height'];
				}

			} else {
				trash[index]['styles'] = {};
				if(index == 0) {
					trash[index]['styles']['top_height'] = 0;
					trash[index]['styles']['max_height'] = 35;
				} else {
					
					trash[index]['styles']['max_height'] = 35;
					trash[index]['styles']['top_height'] = trash[index - 1]['styles']['top_height'] + trash[index - 1]['styles']['max_height'];

				}
			}
			
		});

		this.setState({ grid : grid, trash : trash });
		//console.log(grid)
	}

	componentWillMount () {
		
		this.updateRowHeight();

		var maxid = 0;

		var myArray = this.state.grid;

		myArray.map(function(obj){     
		    if (obj.id > maxid) maxid = obj.id;    
		});

		this.setState({ nextId : maxid + 1 })

		document.addEventListener("keydown", this._handleKeyDown.bind(this));

		//document.body.addEventListener("click", this._outerClick.bind(this));
		var _this = this;
		$(document).on('click', '.jsSideoverlay', function () {
			_this.setState({ slideStyle : { height : '0%' } });
			$(this).hide();
		});


	}

	_handleKeyDown (event) {
		
		if(this.focusElement && !this.state.textVisible) {
	  	const keyCode = event.keyCode || event.which;
	  	const rowsAndCols = this.focusElement;
	  	
	  	//console.log(rowsAndCols)
	  	switch( keyCode ) {
	    		//Left arrow
	        case 37:
	        	this.setState({ isShowTextArea : false });
	          const prevColumn = $(rowsAndCols.node.element).prev();
	        	if(prevColumn.length) {
	        		prevColumn.focus();
	        	}
	          break;
	        //Up arrow
	        case 38:
						this.setState({ isShowTextArea : false });	        	
	        	const prevRow = $(rowsAndCols.node.element).closest('.react-grid-Row').prev();
	        	
	        	if(prevRow.length) {
	        		prevRow.find('div.react-grid-Cell').eq(rowsAndCols.node.index).focus();
	        	}

	          break;
	         //Right arrow
	        case 39:
	          this.setState({ isShowTextArea : false });
	          const nextColumn = $(rowsAndCols.node.element).next();
	        	if(nextColumn.length) {
	        		nextColumn.focus();
	        	}
	          break;
	         //Down arrow
	        case 40:
	        	this.setState({ isShowTextArea : false });
	          const nextRow = $(rowsAndCols.node.element).closest('.react-grid-Row').next();
	        	
	        	if(nextRow.length) {
	        		nextRow.find('div.react-grid-Cell').eq(rowsAndCols.node.index).focus();
	        	}
	          
	          break;
	        default: 
	        		
	            break;
	    }
  	}
	}

	componentDidMount () {
		//this.updateRowHeight();
		//console.log('hello')
		//console.log(this.refs.svg.offsetWidth)
		//console.log(containerWidth)
		containerWidth += 19;

		const _this = this;
		const rentDatePicker = this.refs.rental_date;
		$(rentDatePicker).datepicker({
			autoclose : true,
			format: "dd/mm/yyyy",
			viewMode: "montKonths",
			endDate : new Date()
		}).on('show', function ( ev ) {

		}).on('hide', function ( ev ) {

		}).on('changeDate', function ( ev ) {
			var rent_incomes = _this.state.rent_incomes;
			rent_incomes['date_of_income'] = ev.target.value;
			_this.setState({ rent_incomes : rent_incomes });
		});

		const datePicker = this.refs.datepicker;
		$(datePicker).datepicker({
			autoclose : true,
			format: "mm/yyyy",
			viewMode: "months", 
    	minViewMode: "months",
    	endDate : new Date(nextMonth)
		}).on('show', function(e) {
			var iconNode = _this.refs.iconDate;

			$('.datepicker-dropdown').css({top:$(iconNode).offset().top + $(iconNode).height(), left:$(iconNode).offset().left})
		}).datepicker("setDate", new Date(dateMonth)).on('changeDate', function ( ev ) {

			
			if(ev.date) {
				const date = parseInt(ev.date.getMonth() + 1) + '/' + ev.date.getFullYear();
				var locale = "en-us";
	    	var month = ev.date.toLocaleString(locale, { month: "long" });
	    	const str = month + '-' + ev.date.getFullYear();
	    	
	    	var rentInput = { month : parseInt(ev.date.getMonth() + 1), year : ev.date.getFullYear() };
	    	_this.monthYear = rentInput.month + '-' + rentInput.year;

	    	const old_month = _this.state.dateFormat;

	    	if(old_month != str) {
	    		_this.setState({ dateFormat : str, rentInput : rentInput }, () => {

	    			_this.createRentMonth();

	    		});
	    	}
    	}

		});

		var searchSelect = this.refs.searchSelect;
		$(searchSelect)
      .select2()
      .on('change', function(e) {
        // this ensures the change via select2 triggers 
        // the state change for your component 
        //self.handleChange();
        //$('.side-nav').css('width', '300px');
        var selected_element = $(e.currentTarget);
    		var select_val = selected_element.val();
        var form_data = { room_id : select_val, month : _this.state.rentInput.month, year : _this.state.rentInput.year };
        loadAndSave.post(form_data, ajax_url.get_rent_by_room_no, 'jsRentPanel').then(function ( data ) {

        	_this.setState({ grid : [], trash : [] }, () => {

						_this.setState({ grid : data.rent_income, trash : data.inactive_rent_income }, () => {

			    		_this.updateRowHeight();

			    	})
		    	})
		    	
        }).fail(function ( error ) {

        })
        
      });

		this.setState({ containerWidth : containerWidth });

		this.updateRowHeight();
		$('#slide-out-top').hover(function(){ 
      _this.mouse_is_inside = true; 
      console.log('hover')
    }, function(){ 
      _this.mouse_is_inside = false;
    });
	}

	showDatepicker () {
		var node = this.refs.datepicker;
		$(node).datepicker('show');
	}

	onChangeFocus (node) {
		this.focusElement = { node : node };
	}

	onChangeStyle (styles, value, index, max_height, domNode, textInputValue, tabIndex) {

		//console.log(textInputValue)
		if(this.state.isActive == "trash") {
			return false;
		}

		this.setState({ isShowTextArea : true, minHeight : max_height, domNode : domNode, textInputValue : textInputValue, tabIndex : tabIndex }, () => {

			//console.log(this.state.textInputValue)

			var node = this.refs.myRef;

			$(node).val('').focus();

		});
		
    	var textStyles = this.state.textAreaStyle;
    	textStyles.height = 'auto';
    	textStyles.left = styles.left;
    	textStyles.top = styles.top;
    	textStyles.minHeight = max_height + 'px';
    	textStyles.height = max_height + 'px';
    	textStyles.background = 'transparent';
    	textStyles.color = 'transparent';
			textStyles.textShadow = '0 0 0 #000';

    var keyIndex = 0;

		this.state.grid.map(function(obj, i){
		    if (obj.id == index) keyIndex = i;    
		});

		//console.log(keyIndex, index)
    
    	//console.log(value)
		this.setState({ textAreaStyle : textStyles, key : value, index : keyIndex });
		
    	/*node.style.cssText = 'left : ' + styles.left;
    	node.style.cssText = 'top : ' + styles.top;*/
    	//console.log(styles, value, index)
    	//console.log(this.state)
   }

   updateHeight (index, key, node) {

   	//console.log(index, key)
   	var gridData = this.state.grid;

   	const domNode = this.state.domNode;

   	const cellHeight = gridData[index]['styles'][key + '_height'];
   	
   	const maxHeight = gridData[index]['styles']['max_height'];

   	gridData[index]['styles'][key + '_height'] = $(domNode).height() + 15;

   	//console.log($(domNode).height())
   	
   	var arr = [];
   	$.each( gridData[index]['styles'], function ( key_name, value ) { 
   		
   		if($.inArray(key_name, ignoreHeight) == -1)  {
   			arr.push(value)
   		}
   			
   	});

   	//console.log(gridData[index]['styles'])

		var max = Math.max.apply( null, arr );
		var keyData = gridData[index]['styles'];

		//console.log(keyData)

		var keyList = [];
		$.each(keyData, function ( key_name, value ) {
			if(value == max && $.inArray(key_name, ignoreHeight) == -1) {
				keyList.push(key_name)
			}
		});

		//console.log(keyList);

		//console.log(gridData[index]['styles'], arr, max, key)

		gridData.map(function(index, data) {
			if(index > 0) {
				return data[index]['styles']
			} else {
				return data;
			}
		})
		
		//console.log(maxHeight, max)

		if( maxHeight < max || maxHeight > max) {
			gridData[index]['styles']['max_height'] = max;

			this.updateRowHeight();
		}
		
		this.setState({ grid : gridData });
   }

   updateRow (event) {
   	var _this = this;
   	//setTimeout( function () {
		//console.log('isTabbed' + _this.isTabbed)
		if(!_this.isTabbed) {

			var node = _this.refs.myRef;

			_this.updateTableCell(node, false, 'blur');

		}
   	//}, 1000)
   	
   }

   checkInputValid (val) {

   	var inputType = validateInput[this.state.key];
   	var is_valid = null;
   	if(inputType) {
	   	const key_name = this.state.key;
	   	const inputName = header_data[key_name];
	   	var type = 'max';
	   	var other_key = 'checkin_date';
	   	if(key_name == 'checkin_date') {
	   		type = 'min';
	   		other_key = 'checkout_date';
	   	}

	   	var gridData = this.state.grid;
	   	var index = this.state.index;

	   	if(dateInput.indexOf(key_name)) {
	   		console.log('other')
	   		is_valid = jqueryValidate.validateInput(inputType, val, inputName);
	   	} else {
	   		console.log('date')
	   		console.log(inputType, val, type, gridData[index][other_key], inputName, other_key)
	   		is_valid = jqueryValidate.validateInput(inputType, val, inputName, type, gridData[index][other_key], other_key);
	   	}
   	} else {
   		is_valid = { valid : true };
   	}
   	return is_valid;
   }

   updateTableCell (node, isNext, eventName) {

   	//console.log(eventName, this.isTabbed, isNext)
   	
   	var isValid = this.checkInputValid(node.value);

   	if(!this.state.textVisible) {
   		if(isNext) {
				var _this = this;
				setTimeout( function () {

					_this.isTabbed = false;
					//_this.setState({ isShowTextArea : false });

				}, 10);

			} else {
				this.setState({ isShowTextArea : false });
			}
   		return false;
   	}

   	if(isValid.valid) {
	   	var index = this.state.index;
			var gridData = this.state.grid;
			var key = this.state.key;

			const rowData = gridData[index];
			const columnValue = gridData[index][this.state.key];
			const val = node.value;
			//console.log(index)
			var _this = this;
			if(val != columnValue) {
				var monthYear = this.monthYear.split('-');
				var form_data = { id : rowData.id, guest_id : rowData.guest_id, column_key : key, column_value : val, rent_id : rowData.rent_id, month : monthYear[0], year : monthYear[1] };

				loadAndSave.save(form_data, ajax_url.update_rent_income).then(function ( data ) {

					//console.log(index, gridData, this.state.key, event.target.value)
					gridData[index][key] = val;
					var pending = data.pending;
					gridData[index]['pending_amount'] = pending.pending_amount;
					gridData[index]['total_pending_amount'] = pending.total_pending_amount;

					console.log(gridData)
					//console.log(gridData)
					//this.setState({ grid : { index : trData } })
					_this.setState({ grid : gridData }, () => {
						_this.updateHeight(index, key, node);
					});

					if(isNext) {
						
						setTimeout( function () {

							_this.isTabbed = false;
							_this.setState({ isShowTextArea : false, textVisible : false });

						}, 10);

					} else {
						_this.setState({ isShowTextArea : false, textVisible : false });
					}

					jqueryValidate.renderSuccessToast(header_data[key] + " updated successfully!");

				}).fail(function ( error ) {

					jqueryValidate.renderErrorToast(error.error[0]);

				});

			} else {
				if(isNext) {
					setTimeout( function () {
						_this.isTabbed = false;
					}, 10);
					this.setState({ textVisible : false });
				} else {
					this.setState({ isShowTextArea : false, textVisible : false });
				}
			}
		} else {
			if(isNext) {
				setTimeout( function () {
					_this.isTabbed = false;
				}, 10);
				this.setState({ textVisible : false });
			} else {
				this.setState({ isShowTextArea : false, textVisible : false });
			}
			jqueryValidate.renderErrorToast(msg);
		}
		
		
   }

  removeItem () {
		var gridData = this.state.grid;

		//console.log(gridData)
		var income_ids = [];
		var finalGrid = 
			gridData.filter(function(arr, index) {
				if(arr.selected) {
					income_ids.push(arr.id);
				}
				return !arr.selected;
			});

		//console.log(income_ids)
		var _this = this;
		if(income_ids.length) {
			var form_data = { ids : income_ids, month : this.state.rentInput.month, year : this.state.rentInput.year };

			loadAndSave.deleteRecord(form_data, ajax_url.remove_rent_income, 'jsRentPanel').then(function ( data ) {

				_this.setState({ grid : [], trash : [] }, () => {

					_this.setState({ grid : data.rent_income, trash : data.inactive_rent_income }, () => {

						_this.updateRowHeight();

					});	

				});

			})
    	
		}

		//console.log(this.state.grid)
	}

	showTextAsInput ( event ) {
  	var val = $(event.target).val();
  	//console.log(val, val.length)
  	if(!this.state.textVisible && val.trim().length) {
  		this.setState({ textVisible : true });
  		var styles = this.state.textAreaStyle;
			
			styles.background = '';
			styles.color = '#000';
			styles.textShadow = '';
			
			this.setState({ textAreaStyle : styles });

  	}

  	if(!this.state.textVisible && !val.trim().length) {
  		$(event.target).val('');
  	}
  }
  
	autoResize (event) {
		var node = this.refs.myRef;
		var _this = this;
		var key = event.which || event.KeyCode;

		var val = $(event.target).val();
		if(!this.state.textVisible && !val.length) {
  		return false;
  	}

		//Enter key presses.
		if(key == 13) {
			
			this.updateTableCell(node, false, 'enter');
			event.preventDefault();
			return false;

		//Tab key presses.
		} else if( key == 9 ) {

			this.isTabbed =  true;

			//console.log('Tabbed key pressed' + this.isTabbed)

			const gridCell = this.state.grid[0];

			const headerArray = Object.keys(gridCell);

			const lastHeaderKey = headerArray[headerArray.length - 2];

			var isNext = true;
			if(this.state.key == lastHeaderKey) {
				isNext = false;
			}

			this.updateTableCell(node, isNext, 'tab');

			return false;

		}
		setTimeout(function(){
			//console.log(this.textInput.scrollHeight)
			//node.style.cssText = 'height:auto;';
			var styles = _this.state.textAreaStyle;
			styles.height = 'auto';
			_this.setState({ textAreaStyle : styles });
			styles.height = node.scrollHeight + 'px';
			styles.background = '';
			styles.color = '#000';
			styles.textShadow = '';
			//console.log(styles)
			_this.setState({ textAreaStyle : styles });
			//node.style.cssText = 'height:' + node.scrollHeight + 'px';
	   },0);
	}

	createRentMonth () {
		var _this = this;
		loadAndSave.save(this.state.rentInput, ajax_url.rent_create, 'jsRentPanel').then(function ( data ) {
			//console.log(data)

			_this.setState({ grid : [], trash : [] }, () => {

				_this.setState({ grid : data.rent_income_result, trash : data.inactive_rent_income }, () => {

					_this.updateRowHeight();

				});	

			});
			
		}).fail(function ( error ) {
			//console.log(error)
		})


	}

	moveItem () {
		var trashData = this.state.trash.slice();
		var gridData = this.state.grid.slice();
		//console.log(gridData)
		var incoms_ids = [];
		var finalTrashGrid = 
			trashData.filter(function(arr, index) {
				if(arr.selected) {
					incoms_ids.push(arr.id);
				}
				return !arr.selected;
			});

		//console.log(finalTrashGrid, gridData)
		//console.log(incoms_ids)
		var _this = this;
		if(incoms_ids.length) {
			var form_data = { ids : incoms_ids, month : this.state.rentInput.month, year : this.state.rentInput.year };

			loadAndSave.post(form_data, ajax_url.move_to_active_rent, 'jsRentPanel').then(function ( data ) {

				_this.setState({ grid : [], trash : [] }, () => {

					_this.setState({ grid : data.rent_income, trash : data.inactive_rent_income }, () => {

		    		_this.updateRowHeight();

		    	})
	    	})

			})

		}
	}

	changeTab (tab) {
		this.setState({ isActive : tab, isShowTextArea : false });
	}

	checkActiveTab (tabName) {
	  return (tabName == this.state.isActive) ? "active" : "";
	}

	showTextarea () {
		if(!this.state.textVisible) {
			const textNode = this.refs.myRef;
			//console.log(this.state.textInputValue)
			$(textNode).val(this.state.textInputValue);
			var styles = this.state.textAreaStyle;
			styles.background = '';
			styles.color = '#000';
			styles.textShadow = '';
			this.setState({ textAreaStyle : styles, textVisible : true });
		}
	}

	textPaste (event) {
		if(!this.state.textVisible) {
			event.preventDefault();
			event.stopPropagation();
			return false;
		}
	}

	onShowSlide (rowData, rowIndex) {
		
		var form_data = { rent_id :  rowData.rent_id};
		var _this = this;

		var keyIndex = 0;

		this.state.grid.map(function(obj, i){
		    if (obj.id == rowIndex) keyIndex = i;    
		});

		loadAndSave.post(form_data, ajax_url.get_last_transactions).then(function ( data ) {
			_this.setState({ slideStyle : { height : "100%" }, trData : rowData, latestRent : data.last_paid_rent, incomeIndex : keyIndex  });
			$('.jsSideoverlay').show();

		}).fail(function ( error ) {

		})
		
	}

	onChangeRentForm (event) {
		var rent_incomes = this.state.rent_incomes;
		rent_incomes[event.target.name] = event.target.value;
		this.setState({ rent_incomes : rent_incomes });
	}

	updateRent () {
		var index = this.state.incomeIndex;
		var gridData = this.state.grid;

		var rowData = this.state.trData;
		var rent_incomes = this.state.rent_incomes;
		if (!rent_incomes.amount.trim().length) {
			jqueryValidate.renderErrorToast('Kindly fill the amount field');
			return true;
		}
		if (!rent_incomes.date_of_income.trim().length) {
			jqueryValidate.renderErrorToast('Kindly fill the date field');
			return true;
		}
		var _this = this;
		rent_incomes['user_id'] = rowData.guest_id;
		rent_incomes['rent_id'] = rowData.rent_id;
		var monthYear = this.monthYear.split('-');
		rent_incomes['month'] = monthYear[0];
		rent_incomes['year'] = monthYear[1];
		//console.log(rent_incomes)
		loadAndSave.save(rent_incomes, ajax_url.create_new_income, 'jsSlideRight').then(function ( data ) {
			//$('#popup_rent_amount,#popup_rent_notes').val('');
			var pending = data.pending;
			gridData[index]['pending_amount'] = pending.pending_amount;
			gridData[index]['total_pending_amount'] = pending.total_pending_amount;
			_this.setState({ rent_incomes : {amount : '', notes : '', date_of_income : ''}, slideStyle : { height : "0%" } });
			jqueryValidate.renderSuccessToast("Income added successfully!");

		}).fail(function ( error ) {
			jqueryValidate.renderErrorToast(error.msg[0]);
		});
	}

	render() {
		//console.log('render');
		var left = 30;
		let textInput = null;
		var increment = 150;
		
		var header = $.extend(true, {}, header_data);
		var _this = this;
		
		var rentForm = (<form>
							
									<div className="form-group">
										<label >Enter amount:</label>
										<input type="text" className="form-control" onChange={this.onChangeRentForm.bind(this)} name="amount" id="amount" value={this.state.rent_incomes.amount} />
									</div>
									<div className="form-group">
										<label >Enter date:</label>
										<input type="text" className="form-control" ref="rental_date" name="date_of_income" id="date_of_income" onChange={this.onChangeRentForm.bind(this)} value={this.state.rent_incomes.date_of_income} />
									</div>
									<div className="form-group">
										<label >Enter notes:</label>
										<textarea className="form-control" name="notes" id="notes" onChange={this.onChangeRentForm.bind(this)} value={this.state.rent_incomes.notes}></textarea>
									</div>
									<button type="button" className="btn btn-primary"  style={{ background : "#337ab7", color : "white", width: "100%" }} onClick={this.updateRent.bind(this)} >Submit</button>
						</form>
						);
		
		return (
		<div>
			<div className="row row-buttons">
				<div className="col-sm-12 text-center" style={{ fontSize : "25px" }}>
					<span>{ this.state.dateFormat }</span>&nbsp;&nbsp;&nbsp;
					<i className="fa fa-calendar" ref="iconDate" onClick={this.showDatepicker.bind(this)} style={{ cursor : "pointer" }}></i>
					<input type="text" className="form-control hide" ref="datepicker" />
				</div>
			</div>
			<div className="row row-buttons" style={{ top : "51px" }}>
				<div className="col-sm-12">
					<button type="button" className="btn btn-primary" onClick={this.createRentMonth.bind(this)}><i className="fa fa-refresh"></i> Refresh rent data</button>

					{ this.state.isActive == "current" && <button type="button" className="btn btn-primary btn-margin" onClick={this.removeItem.bind(this)}><i className="fa fa-remove"></i> Remove</button> }

					{ this.state.isActive == "trash" && <button type="button" className="btn btn-primary btn-margin" onClick={this.moveItem.bind(this)}><i className="fa fa-arrows"></i> Move to active</button> }

					<select className="" ref="searchSelect" name="column_search" style={{ width : "150px" }}>
						<option value="0">Select room no</option>
						{rooms.map(function(room, index) {
           		
           			return (
           				<option key={index} value={room.id}>{room.room_no}</option>
           			)
           	})}
					</select>

					<ul id="slide-out-top" className="side-nav-top" style={this.state.slideStyle} id="jsSlideRight">
				    <li>
				    	<div className="userView">
				      		<div className="background">
					        <img src={ ASSETS_PATH + '/office.jpg' } />
					      </div>
					      <a href="javascript:;"><img className="circle" src={ ASSETS_PATH + '/rupee.png' } /></a>
				    	</div>
				    </li>
				    <li className="add-rent-label" style={{ marginTop: "-8px" }}><span>Add rental amount</span></li>
				    <li style={{ width : "90%" }} >
				    	{rentForm}
				    </li>
				    <li className="add-rent-label label-li"><span>Last 5 rent amount recieved</span></li>
				    <li className="" style={{ width: "100%" }}>
				    	<div className="">
								<div className="col-sm-12">
									<table className="table table-bordered table-hover lastest-rent-table">
										<thead>
											<tr>
												<th>Date</th>
												<th>Amount</th>
											</tr>
										</thead>
										<tbody>
											{this.state.latestRent.map(function(rent_amount, index) {
					           			return (
					           				<tr key={index}>
					           					<td>{rent_amount.date_of_income}</td>
					           					<td>{rent_amount.amount}</td>
					           				</tr>
					           			)
					           	})}
										</tbody>
									</table>
								</div>
							</div>
				    </li>
				  </ul>
				</div>
			</div>
			<div className="row row-buttons tab-new" style={{ top : "96px", padding : "0px" }}>
				<div className="col-sm-12" style={{ padding : "0px" }}>
					<ul className="nav nav-tabs" style={{ borderBottom : "0px" }}>
						<li className={this.checkActiveTab('current')}>
							<a style={{ borderRadius : "inherit", marginRight : "0px", padding : "10px 40px", cursor : "pointer", borderBottom : "0px", borderLeft : "0px", borderTop : "1px solid #dddddd" }} className="green" onClick={this.changeTab.bind(this, 'current')}>
								<span className="glyphicon glyphicon-floppy-disk" style={{ paddingRight : "9px" }}></span>Active
							</a>
						</li>
						<li className={this.checkActiveTab('trash')}>
							<a style={{ borderRadius : "inherit", marginRight : "0px", padding : "10px 40px", color: "#555555", borderRight : "1px solid #dddddd", borderBottom : "0px", borderLeft : "0px", borderTop : "1px solid #dddddd" }} className="blue" onClick={this.changeTab.bind(this, 'trash')}>
								<span className="glyphicon glyphicon-trash" style={{ paddingRight : "9px" }}></span>Trash
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div className="react-grid-Container" ref="reactContainer" style={{"width": this.state.containerWidth + 'px', marginTop : "87px" }}>
				
			   <div className="react-grid-Main">
			      <div style={{"overflow":"hidden", "outline":"0", "position":"relative", "minHeight":"400px"}} className="react-grid-Grid">
			         <div height="35" style={{"position":"relative", "height" : "35px"}} className="react-grid-Header">
			            <div height="35" style={{"position" : "absolute", "top" : "0px", "left" : "0px", "width" : this.state.containerWidth + 'px', "overflowX": "hidden", "minHeight" : "auto"}} className="react-grid-HeaderRow">

			            	
			               <div style={{"width" : this.state.containerWidth + 'px', "height" : "35px" , "whiteSpace" : "nowrap" , "overflowX" : "hidden" , "overflowY" : "hidden"}}>

			               	{Object.keys(header).map(function(key, index) {
			               		var headerStyle = {"width" : "30px" , "left" : "0" , "display" : "inline-block" , "position" : "absolute" , "height" : "35px" , "margin" : "0" , "textOverflow" : "ellipsis" , "whiteSpace" : "nowrap"};

			               		

			               		if(index == 0) {
			               			containerWidth += 30;
			               			return (
			               				<div className="react-grid-HeaderCell" style={headerStyle} key={key}>
						                  <div className="widget-HeaderCell__value"></div>
						               </div>
			               			)
			               		}
			               		headerStyle.left = left + 'px';
		               			left += increment;
		               		 	headerStyle.width = increment + 'px';
		               		 	containerWidth += increment;
			               		return (
			               			<div className="react-grid-HeaderCell" style={headerStyle} key={key}>
					                  <div className="widget-HeaderCell__value">{header[key]}</div>
					               	</div>
					               )

	                        })}

			               </div>
			            </div>
			         </div>
			         <div tabIndex="0">
			            <div className="react-grid-Viewport" style={{"padding" : "0" , "bottom" : "0" , "left" : "0" , "right" : "0" , "overflowX" : "hidden" , "overflowY" : "scroll", "position" : "absolute" , "top" : "35px"}}>
				            { this.state.isShowTextArea && <span>
									<textarea tabIndex={this.state.tabIndex} name="test" className="form-control text-area" defaultValue={this.state.textInputValue} ref="myRef" onKeyDown={this.autoResize.bind(this)} style={this.state.textAreaStyle} onPaste={this.textPaste.bind(this)} onDoubleClick={this.showTextarea.bind(this)} onBlur={this.updateRow.bind(this)} onChange={this.showTextAsInput.bind(this)} />
								</span>
								}
			               <div style={{"position" : "absolute" , "top" : "0px" , "left" : "0px" , "width" : "1094px" , "height" : "263px"}} className="react-grid-Canvas">
			                  <div style={{"overflow" : "hidden"}}>
			                     
			                     	
			                     	{this.state.isActive == "current" && this.state.grid.map(function(tr, index) {
			                     		
			                     		return <Grid onChangeParentStyle={_this.onChangeStyle.bind(_this)} trData={tr} header={header} key={tr.id} keyIndex={tr.id} onChangeParentFocus={_this.onChangeFocus.bind(_this)} showSlide={_this.onShowSlide.bind(_this)} />
			                        })}

			                     	{this.state.isActive == "trash" && this.state.trash.map(function(tr, index) {
			                     		
			                     		return <Grid onChangeParentStyle={_this.onChangeStyle.bind(_this)} trData={tr} header={header} key={tr.id} keyIndex={tr.id} onChangeParentFocus={_this.onChangeFocus.bind(_this)} />
			                        })}

			                     
			                  </div>
			               </div>
			            </div>
			         </div>
			      </div>
			   </div>
			</div>
			
			</div>
		);
	}
}

ReactDOM.render(
	<Guest />,
	document.getElementById('guestTable')
);