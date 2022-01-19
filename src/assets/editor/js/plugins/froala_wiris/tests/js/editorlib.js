$('head').append('<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />');
$('head').append('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">');
$('head').append('<script src="froala/js/plugins/froala_wiris/wiris.js"></script>');

/**
 * Creates a Froala instance instance on "example" div.
 * @param  {string} lang              CKEditor language. WIRIS plugin read this variable to set the editor lang.
 * @param  {string} wiriseditorparameters JSON containing WIRIS editor parameters.
 */
function createEditorInstance(lang, wiriseditorparameters) {
	var toolbar = ['undo', 'redo' , 'bold', '|', 'wirisEditor', 'wirisChemistry','clear', 'insert'];
	$('#example').froalaEditor({
      // Add the custom buttons in the toolbarButtons list, after the separator.
      iframe: true,
      //       toolbarInline: true,
      charCounterCount: false,
      imageEditButtons: ['wirisEditor', 'wirisChemistry'],
      toolbarButtons: toolbar,
      toolbarButtonsMD: toolbar,
      toolbarButtonsSM: toolbar,
      toolbarButtonsXS: toolbar,
      htmlAllowedTags:   ['.*'],
      htmlAllowedAttrs: ['.*'],
      language: lang,
      imageResize : false,
      key: 'lrqpD6E-11cyeI-7A11lE-13B-13==',
      heightMax: 280,
    });

    // Disaable demo padding.
    $('#example').css({'padding' : '0px'})
}

createEditorInstance('en', {});
updateFunction();


function getEditorData() {
	return $('#example').froalaEditor('html.get');
}

function setParametersSpecificPlugin(wiriseditorparameters) {
	_wrs_int_wirisProperties = wiriseditorparameters;
	$.FroalaEditor.INSTANCES[0].opts.wiriseditorparameters = wiriseditorparameters;
}