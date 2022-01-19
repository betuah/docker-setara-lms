// Render script.
// This script renders non editable DOM objects.
var js = document.createElement("script");
js.type = "text/javascript";
js.src = "./assets/tinymce4/js/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image";
document.head.appendChild(js);

/**
 * Creates a TINYMCE instance on "example" div.
 * @param  {string} lang              TINYMCE language. WIRIS plugin read this variable to set the editor lang.
 * @param  {string} wiriseditorparameters JSON containing WIRIS editor parameters.
 */
 function createEditorInstance(lang, wiriseditorparameters) {

 	var dir = 'ltr';
 	if (lang == 'ar' || lang == 'he'){
 		dir = 'rtl';
 	}

 	if (typeof wiriseditorparameters == 'undefined') {
 		wiriseditorparameters = {};
 	}

 	tinymce.init({
        selector: '#editormce',
        height : 200,
        auto_focus:true,
        //language: lang,
        directionality : dir,
        // To avoid TinyMCE path conversion from base64 to blob objects.
        // https://www.tinymce.com/docs/configure/file-image-upload/#images_dataimg_filter
        images_dataimg_filter : function(img) {
            return img.hasAttribute('internal-blob');
        },
        setup : function(ed)
        {
            ed.on('init', function()
            {
                this.getDoc().body.style.fontSize = '16px';
                this.getDoc().body.style.fontFamily = 'Arial, "Helvetica Neue", Helvetica, sans-serif';
            });
        },
         plugins: [
              "advlist autolink link image lists charmap print preview hr anchor pagebreak",
              "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
              //"table contextmenu directionality emoticons paste textcolor responsivefilemanager code tiny_mce_wiris emoticons eqneditor"
	      "table contextmenu directionality emoticons paste textcolor responsivefilemanager code emoticons eqneditor"
         ],
         toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
         //toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor | print preview | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry emoticons eqneditor",
	 toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor | print preview | emoticons eqneditor",
         image_advtab: true,

         external_filemanager_path:"assets/filemanager/",
         filemanager_title:"Tata Kelola Files"
 	});
 }

// Creating TINYMCE demo instance.
createEditorInstance('en', {});
