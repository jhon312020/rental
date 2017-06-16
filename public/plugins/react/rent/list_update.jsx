//https://www.youtube.com/watch?v=OKRu7i49X54
//console.log(ajax_url);
//var data = [["test"], ["test1"], ["test2"], ["test3"], ["test4"], ["test5"]];

//console.log($.type(formData));

var data = formData;

console.log(data)

var validateInput = { advance : "integer", mobile_no : "mobile_no", email : "email", checkin_date : "date", checkout_date : "date", name : "required" };

var dateInput = [ "checkin_date", "checkout_date" ];

//console.log(data);

//var data = [{ id : 1, name : "Bright", city : "City", state : "State State State State State State State ", country : "Country", zip : "Zip", email : "Email", address : "Address", mobile_no : "Mobile no"}];

var ignoreHeight = ["top_height", "max_height"];

var empty_data = { room_no : "", name : "", city : "", state : "", country : "", zip : "", email : "", address : "", mobile_no : "", advance : "", is_incharge : true, styles : { max_height : 35, top_height : 0 } };

var header_data = {checkbox : "", room_no : "Room no", name : "Name", city : "City", state : "State", country : "Country", zip : "Zip", email : "Email", address : "Address", mobile_no : "Mobile no", checkin_date : "Checkin date", checkout_date : "Checkout date", advance : "Advance", is_incharge : "Is incharge" };

const inputType = { is_incharge : "radio" };

var editable_header = [  ];
var keyInputType = { room_no : "select" };

const propTypes = 
	{ 
		name : React.PropTypes.string.isRequired,
		mobile_no : React.PropTypes.number.isRequired,
		email : React.PropTypes.string.isRequired,
	} ;

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

		var checkbox = this.refs.checkbox;
		var _this = this;
		$(checkbox).iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_flat-green'
    }).on('ifChanged', function(event) {

    	var val = event.target.checked ? 1 : 0;
    	_this.changeCheckboxVal("is_incharge", val);

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

  updateIncharge (event) {
  	console.log(event.target.checked)

  }

  changeCheckboxVal (key, value) {

  	console.log(key)

  	var rowData = this.state.trData;
  	var inputName = header_data[key];
  	var _this = this;
  	
  	var form_data = { id : rowData.id, guest_id : rowData.guest_id, income_id : rowData.income_id, column_key : key, column_value : value };

		loadAndSave.save(form_data, ajax_url.rent_update).then(function ( data ) {

			//console.log(index, gridData, this.state.key, event.target.value)
			rowData.is_incharge = value;

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
		<div className={"react-grid-Row react-grid-Row--even" + (tr['selected'] ? ' row-selected' : '') } style={{ "overflow" : "hidden" , "contain" : "layout"}}>
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

										{tr['rent_id'] && <input type="hidden" name={"guest[" + trIndex + "][rent_id]"} value={tr['rent_id']} /> }

										{tr['rent_id'] && <input type="hidden" name={"guest[" + trIndex + "][guest_id]"} value={tr['guest_id']} /> }
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
			                  <input type="checkbox" ref="checkbox" name={"guest[" + trIndex + "][" +  key + "]"}   defaultChecked={ tr[key] == 1 ? true : false } onChange={this.changeCheckboxVal.bind(this, key)} />
			                </label>
					         </span>
					      	
					      </div>
					   </div>
					   )

				 		}
				 	} else {
				 		return (	
				  		<div tabIndex={tabIndex} className="react-grid-Cell" style={headerStyle} key={index} onDoubleClick={this.showInput.bind(this, 'dblclick', key, headerStyle, tabIndex + 1)} onFocus={this.updateFocusStateInput.bind(this, key, headerStyle, tabIndex + 1, trIndex, index)} ref={"reactGridCell_" + trIndex + '_' +  index} onKeyUp={this.showInput.bind(this, 'keyup', key, headerStyle, tabIndex + 1)}>
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
		this.state = { grid : data, containerWidth : 0, textAreaStyle : {left : '0px', top : '0px', height : 'auto', minHeight : '0px'}, isShowTextArea : false, key : null, index : null, minHeight : '0px', domNode : null, textInputValue : null, tabIndex : 0, nextId : null, newRow : [], oldGuest : {}, selectStyle : {left : '0px', top : '0px' }, textVisible : false };

		this.isTabbed = false;
		this.focusElement = null;
		//console.log(this.state.grid)
	}

	addNew() {
		/*const arr = this.state.grid;
		var _this = this;
		var form_data = { room_id : room.id };
		loadAndSave.save(form_data, ajax_url.new_rent_by_room_id).then(function ( data ) {

			_this.setState({ grid : [] }, () => {

				_this.setState({ grid : data.rents }, () => {

						_this.updateRowHeight();

				});

				jqueryValidate.renderSuccessToast("New rent created successfully for room no " + room.room_no);

			});

		}).fail(function ( error ) {

		})*/

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

		this.setState({ grid : grid });
		//console.log(grid)
	}

	componentWillMount () {
		
		this.updateRowHeight();

		this.setNextId();

		document.addEventListener("keydown", this._handleKeyDown.bind(this));

		this._onChangeSelect2();

	}


	setNextId () {

		var maxid = 0;

		var myArray = this.state.grid;

		myArray.map(function(obj){     
		    if (obj.id > maxid) maxid = obj.id;    
		});

		this.setState({ nextId : maxid + 1 })
	}

	_onChangeSelect2 () {
		
		var _this = this;
		$(document).on('change', '.select2', function () {

			var form_data = { room_id : $(this).val() ? $(this).val() : 0 };
			loadAndSave.post(form_data, ajax_url.get_guest_rent).then(function ( data ) {
				
				var max_rent_id = 0;
				if(data.guests.length) {
					max_rent_id = data.guests[0].id;
				}
				
				var guests = data.guests;
				$.each(_this.state.newRow, function ( i, v) {

					var row = v;
					row.id = parseInt(max_rent_id + i + 1);
					guests.unshift(row);

				})

				//console.log(guests);

				_this.setState({ grid : [] }, () => {

					_this.setState({ grid : guests }, () => {

					_this.updateRowHeight();

					_this.setNextId();

					});
						
				})
				
			})
		})
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
		this.setState({ containerWidth : containerWidth });
		//https://github.com/kriasoft/react-starter-kit/issues/909

		var searchSelect = this.refs.searchSelect;
		$(searchSelect)
      .select2()
      .on('change', function() {
        // this ensures the change via select2 triggers 
        // the state change for your component 
        //self.handleChange();
        //$('.side-nav').css('width', '300px');
        
      });

    var gridSelect = this.refs.roomSelect;
		$(gridSelect)
      .select2()
      .on('change', function() {
        // this ensures the change via select2 triggers 
        // the state change for your component 
        //self.handleChange();
        //$('.side-nav').css('width', '300px');
        
      });

     this.outerClick();
     var _this = this;

     var searchInput = this.refs.searchInput;
     $(searchInput).autocomplete({
			serviceUrl: ajax_url.get_guest_details_by_type,
			minChars: 0,
			showNoSuggestionNotice: true,
			noSuggestionNotice: 'Sorry, no matching results',
			onSelect: function (suggestion) {
				
				if(suggestion && suggestion.id) {
					var form_data = { guest_id : suggestion.id };
					loadAndSave.post(form_data, ajax_url.get_guest_by_id).then(function ( data ) {
						_this.setState({ oldGuest : data.guest });
						$('.side-nav').css('width', '300px');
					}).fail(function ( error ) {

					})
				}
				
			},
			onHint: function (hint) {
				//ele.closest('.form-element').find('.autoajax').val(hint);
			},
			onInvalidateSelection: function() {
				//$('#selction-ajax').html('You selected: none');
				
			}
		});


	}

	onChangeFocus (node) {
		this.focusElement = { node : node };
	}

	onChangeStyle (styles, value, index, max_height, domNode, textInputValue, tabIndex) {

		//console.log(textInputValue)

		var defaultInputType = "text";

		console.log(value, keyInputType)

		if(keyInputType[value]) {
			defaultInputType = keyInputType[value];
		}

		switch (defaultInputType) {
			case "text":

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
				break;
			case "select":

				this.setState({ isShowSelect : true, domNode : domNode, textInputValue : textInputValue, tabIndex : tabIndex }, () => {

					//console.log(this.state.textInputValue)

					var node = this.refs.roomSelect;

					$(node).val(textInputValue).focus();

				});
	
	    	var selectStyle = this.state.selectStyle;
	    	//textStyles.height = 'auto';
	    	selectStyle.left = styles.left;
	    	selectStyle.top = styles.top;
	    	/*textStyles.minHeight = max_height + 'px';
	    	textStyles.height = max_height + 'px';*/

		    var keyIndex = 0;

				this.state.grid.map(function(obj, i){
				    if (obj.id == index) keyIndex = i;    
				});

				//console.log(keyIndex, index)
		    
		    	//console.log(value)
				this.setState({ selectStyle : selectStyle, key : value, index : keyIndex });
				break;
		}
		
		
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

   	console.log(isValid)
   	const inputName = header_data[this.state.key];

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


			var _this = this;
			if(val != columnValue) {

				var form_data = { id : rowData.id, guest_id : rowData.guest_id, income_id : rowData.income_id, column_key : key, column_value : val };

				loadAndSave.save(form_data, ajax_url.rent_update).then(function ( data ) {

					//console.log(index, gridData, this.state.key, event.target.value)
					gridData[index][key] = val;

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

					jqueryValidate.renderSuccessToast(inputName + " updated successfully!");

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
			jqueryValidate.renderErrorToast(isValid.msg);
		}
		
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

	removeItem () {
		var gridData = this.state.grid;

		//console.log(gridData)

		var finalGrid = 
			gridData.filter(function(arr, index) {
				return !arr.selected;
			});

		if(gridData.length != finalGrid.length) {

			this.setState({ grid : this.state.grid.filter(function (e, i) {
      	return !e.selected;
      	})

    	}, () => {
    		this.updateRowHeight();
    	})
    	
		}

		//console.log(this.state.grid)
	}

	addGuest () {
		var old_guest = this.state.oldGuest;
		var form_data = { room_id : room.id, guest_id : old_guest.guest_id };
		var _this = this;
		loadAndSave.post(form_data, ajax_url.add_new_rent_by_room_guest).then(function ( data ) {
			_this.setState({ grid : [] }, () => {

				_this.setState({ grid : data.rents }, () => {

						_this.updateRowHeight();

				});
			});

				jqueryValidate.renderSuccessToast("New rent created successfully for room no " + room.room_no);

		}).fail(function ( error ) {
			if(error.msg) {
				jqueryValidate.renderErrorToast(error.msg);
			}
		})
		
	}

	outerClick () {

		$(document).on('click', 'body', function ( event ) {
			$('.side-nav').css('width', '0px');
		})
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

	render() {
		//console.log('render');
		var left = 30;
		let textInput = null;
		var increment = 150;
		
		var header = $.extend(true, {}, header_data);
		var _this = this;
		var old_guest = this.state.oldGuest;
		//console.log(rooms)
		return (
		<div>
			<div className="row row-buttons" style={{ top : "51px" }}>
				<div className="col-sm-12">
					<button type="button" className="btn btn-primary" onClick={this.addNew.bind(this)}><i className="fa fa-plus"></i> Add</button>
					<button type="button" className="btn btn-primary btn-margin" onClick={this.removeItem.bind(this)}><i className="fa fa-remove"></i> Remove</button>
					<form style={{ display : "inline" }}>
					<input type="hidden" name="guest_ids" />
					<select className="" ref="searchSelect" name="column_search" style={{ width : "150px" }}>
						<option value="">Select type</option>
						<option value="name">Name</option>
						<option value="email">Email</option>
						<option value="mobile_no">Mobile no</option>
					</select>
					<input type="text" className="form-control" ref="searchInput" name="column_value" style={{ width : "200px", position : "absolute", display : "inline", left : "375px" }} />
					</form>
					<ul id="slide-out" className="side-nav" style={{ width : "0px" }}>
				    <li><div className="userView">
				      <div className="background">
				        <img src={ ASSETS_PATH + '/office.jpg' } />
				      </div>
				      <a href="javascript:;"><img className="circle" src={ ASSETS_PATH + '/yuna.jpg' } /></a>
				      <a href="javascript:;"><span className="white-text name">{ old_guest.name }</span></a>
				      <a href="javascript:;"><span className="white-text email">{ old_guest.email }</span></a>
				    	</div>
				    </li>
				    <li style={{ width : "100%", textAlign : "center" }}>
				    	<a href="javascript:;" data-original-title="Add guest" data-toggle="tooltip" type="button" className="btn btn-sm btn-success" onClick={this.addGuest.bind(this)}><i className="glyphicon glyphicon-plus"></i></a></li>
				    <li>
				    	<span>Name&nbsp;:&nbsp;</span><span>{ old_guest.name }</span>
				    </li>
				    <li>
				    	<span>Address&nbsp;:&nbsp;</span><span>{ old_guest.address }</span>
				    </li>
				    <li>
				    	<span>City&nbsp;:&nbsp;</span><span>{ old_guest.city }</span>
				    </li>
				    <li>
				    	<span>State&nbsp;:&nbsp;</span><span>{ old_guest.state }</span>
				    </li>
				    <li>
				    	<span>Country&nbsp;:&nbsp;</span><span>{ old_guest.country }</span>
				    </li>
				    <li>
				    	<span>Email&nbsp;:&nbsp;</span><span>{ old_guest.email }</span>
				    </li>
				    <li>
				    	<span>Mobile no&nbsp;:&nbsp;</span><span>{ old_guest.mobile_no }</span>
				    </li>
				    <li style={{ width : "95%", marginTop : "20px" }}>
				    	<a href={ APP_URL + '/guests/' + old_guest.id + '/edit' } target="_blank" data-original-title="Edit this user" data-toggle="tooltip" type="button" className="btn btn-sm btn-warning"><i className="glyphicon glyphicon-edit"></i></a>
	            <span className="pull-right">
			          
		          </span>
				    </li>
				  </ul>
				</div>
			</div>
			<div className="react-grid-Container" ref="reactContainer" style={{"width": this.state.containerWidth + 'px', marginTop : "46px" }}>
				
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
								{ this.state.isShowSelect && <span>
									<select ref="roomSelect" className="grid-select" style={this.state.selectStyle}>
										{rooms.map(function(room, roomIndex) {
											return ( <option key={room.id} value={room.id}>{room.room_no}</option> )
										})}
									</select>
									</span>
								}
			               <div style={{"position" : "absolute" , "top" : "0px" , "left" : "0px" , "width" : "1094px" , "height" : "263px"}} className="react-grid-Canvas">
			                  <div style={{"overflow" : "hidden"}}>
			                     
			                     	
			                     	{this.state.grid.map(function(tr, index) {
			                     		
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