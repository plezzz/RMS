/*
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Version: 4.3.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v4.3/admin/
*/

var handleDataTableCombinationSetting = function() {
	"use strict";
    
	if ($('#data-table-combine').length !== 0) {
		var options = {
			dom: 'lBfrtip',
			buttons: [
				{ extend: 'copy', className: 'btn-sm' },
				{ extend: 'csv', className: 'btn-sm' },
				{ extend: 'excel', className: 'btn-sm' },
				{ extend: 'pdf', className: 'btn-sm' },
				{ extend: 'print', className: 'btn-sm' }
			],
			responsive: true,
			autoFill: true,
			colReorder: true,
			keys: true,
			rowReorder: true,
			select: true
		};

		if ($(window).width() <= 767) {
			options.rowReorder = false;
			options.colReorder = false;
			options.autoFill = false;
		}
		$('#data-table-combine').DataTable(options);
	}
};

var TableManageCombine = function () {
	"use strict";
	return {
		//main function
		init: function () {
			handleDataTableCombinationSetting();
		}
	};
}();