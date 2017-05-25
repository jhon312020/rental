var loadAndSave = {
	/*
	* This function used to save the form data into our database.
	* @params 
			inputs - [Object]
	* @return Data [JSON]
	*/
	save: function(inputs, url) {
		var data = inputs;
		var deferred = $.Deferred();
		$.ajax({
			url:url,
			type:'post',
			data:inputs,
			dataType:'json',
			success:function(data, textStatus, jqXHR) {
				deferred.resolve(data);
				console.log(data)
			},
			error:function(jqXHR, textStatus, errorThrown) {
				//deferred.reject({success:false});
				//console.log(textStatus+'----'+errorThrown);
				deferred.reject(jqXHR.responseJSON);
			}
		});
		return deferred.promise();
  },
	/*
	* This function used to save the form data into our database.
	* @params 
			inputs - [Object]
	* @return Data [JSON]
	*/
	post: function(inputs, url) {
		var data = inputs;
		var deferred = $.Deferred();
		$.ajax({
			url:url,
			type:'post',
			data:inputs,
			dataType:'json',
			success:function(data, textStatus, jqXHR) {
				deferred.resolve(data);
				console.log(data)
			},
			error:function(jqXHR, textStatus, errorThrown) {
				deferred.reject(jqXHR.responseJSON);
				console.log(textStatus+'----'+errorThrown);
			}
		});
		return deferred.promise();
  },
	/*
	* This function used to get the information from database.
	* @params 
			inputs - [Object]
	* @return Data [JSON]
	*/
	get: function(inputs, url) {
		var data = inputs;
		var deferred = $.Deferred();
		$.ajax({
			url:url,
			type:'get',
			data:inputs,
			dataType:'json',
			success:function(data, textStatus, jqXHR) {
				deferred.resolve(data);
				console.log(data)
			},
			error:function(jqXHR, textStatus, errorThrown) {
				deferred.reject({success:false});
				console.log(textStatus+'----'+errorThrown);
			}
		});
		return deferred.promise();
  },
		/*
	* This function used to Delete the record from the database
	* @params 
			inputs - [Object]
	* @return Data [JSON]
	*/
	deleteRecord: function(inputs, url) {
		var data = inputs;
		var deferred = $.Deferred();
		$.ajax({
			url:url,
			type:'post',
			data:inputs,
			dataType:'json',
			success:function(data, textStatus, jqXHR) {
				deferred.resolve(data);
				console.log(data)
			},
			error:function(jqXHR, textStatus, errorThrown) {
				deferred.reject({success:false});
				console.log(textStatus+'----'+errorThrown);
			}
		});
		return deferred.promise();
  },
	/*
	* This function used to upload the files using ajax.
	* @params 
			formData            - The file data
			url                 - Ajax url
	* @return Boolean
	*/
	ajaxFileUpload: function (formData, url) {
		var deferred = $.Deferred();
		$.ajax({
			url:url,
			type:'post',
			data:formData,
			processData: false,
			contentType: false,
			dataType:'json',
			success:function(data, textStatus, jqXHR) {
				deferred.resolve(data);
			},
			error:function(jqXHR, textStatus, errorThrown) {
				deferred.reject({success:false});
				console.log(textStatus+'----'+errorThrown);
			}
		});
		return deferred.promise();
	},
	/*
	* This function used to get the file size using ajax.
	* @params 
			url                 - image source url.
	* @return 
	* 	file_size						- size in bytes.
	*/
	getFileSizeFromUrl: function (url) {
		var deferred = $.Deferred();
		$.ajax({
			url:url,
			type:'get',
			success:function(data, textStatus, jqXHR) {
				deferred.resolve(data);
			},
			error:function(jqXHR, textStatus, errorThrown) {
				deferred.reject({success:false});
			}
		});
		return deferred.promise();
	}
};