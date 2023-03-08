/* custom admin scripts for site */


jQuery(document).ready(function(){

	// Debug
	//console.log('custom admin js file loaded');

	//Implement datatables
	jQuery('table.data_table').DataTable({
	 	paging: false,
		dom: 'Bfrtip',
		buttons: [
			'copy', 'excel', 'csv', 'print'
		]
	});

});
