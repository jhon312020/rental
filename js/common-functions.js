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
	}
};