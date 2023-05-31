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

	//Custom code for website analytics implementation workflow

	// auto select all
	jQuery("input.ga4wf, textarea.ga4wf").focus(function() { jQuery(this).select(); } );

	// generate property name
	jQuery("#ga4wf-domain-name").on("change", function(){
		jQuery("#ga4wf-property-name").val( jQuery("#ga4wf-domain-name").val() + " - GA4" );
	} );
	// generate data stream name
	jQuery("#ga4wf-domain-name").on("change", function(){
		jQuery("#ga4wf-data-stream-name").val( jQuery("#ga4wf-domain-name").val() );
	} );
	// generate container name
	jQuery("#ga4wf-domain-name").on("change", function(){
		jQuery("#ga4wf-gtm-container-name").val( jQuery("#ga4wf-domain-name").val() );
	} );
	// generate real time link
	jQuery("#ga4wf-ga-property-id").on("change", function(){
		jQuery("#ga4wf-analytics-real-time").attr("href", "https://analytics.google.com/analytics/web/#/p"+jQuery("#ga4wf-ga-property-id").val()+"/realtime/overview");
	} );

	// begin
	jQuery("#ga4wf-property-name").val( jQuery("#ga4wf-domain-name").val() + " - GA4" );
	jQuery("#ga4wf-data-stream-name").val( jQuery("#ga4wf-domain-name").val() );
	jQuery("#ga4wf-gtm-container-name").val( jQuery("#ga4wf-domain-name").val() );

	function saveTextAsFile() {
	  var textToWrite = document.getElementById('ga4wf-gtm-container-import').innerHTML.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&');
	  var textFileAsBlob = new Blob([ textToWrite ], { type: 'text/html' });
	  var fileNameToSaveAs = "gtm-import.json"; //filename.extension

	  var downloadLink = document.createElement("a");
	  downloadLink.download = fileNameToSaveAs;
	  downloadLink.innerHTML = "Download File";
	  if (window.webkitURL != null) {
	    // Chrome allows the link to be clicked without actually adding it to the DOM.
	    downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
	  } else {
	    // Firefox requires the link to be added to the DOM before it can be clicked.
	    downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
	    downloadLink.onclick = destroyClickedElement;
	    downloadLink.style.display = "none";
	    document.body.appendChild(downloadLink);
	  }

	  downloadLink.click();
	}

	var button = document.getElementById('ga4wf-gtm-import-save');
	button.addEventListener('click', saveTextAsFile);

	function destroyClickedElement(event) {
	  // remove the link from the DOM
	  document.body.removeChild(event.target);
	}

});
