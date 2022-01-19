
/**
 * Creates a Froala instance instance on "example" div.
 * @param  {string} lang              CKEditor language. WIRIS plugin read this variable to set the editor lang.
 * @param  {string} wiriseditorparameters JSON containing WIRIS editor parameters.
 */
function createEditorInstance(lang, wiriseditorparameters) {
	var toolbar = ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent',
                    'indent', 'quote', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', 'wirisEditor', 'wirisChemistry', '|', 'emoticons', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'print'];
	$('#editor').froalaEditor({
        // Add the custom buttons in the toolbarButtons list, after the separator.
        iframe: true,
		theme: 'gray',
        //       toolbarInline: true,
        charCounterCount: false,
        imageEditButtons: ['imageDisplay', 'imageAlign', 'imageRemove'],
        toolbarButtons: toolbar,
        toolbarButtonsMD: toolbar,
        toolbarButtonsSM: toolbar,
        toolbarButtonsXS: toolbar,
		toolbarSticky: false,
        htmlAllowedTags:   ['.*'],
        htmlAllowedAttrs: ['.*'],
		linkAutoPrefix: 'https://localhost/siajar/',
        language: lang,
        imageResize : false,
        key: 'lrqpD6E-11cyeI-7A11lE-13B-13==',
        imageUploadURL: 'url-API/Editor/upload.php',
        fileUploadURL: 'url-API/Editor/upload.php',
        videoUploadURL: 'url-API/Editor/upload.php'
    }).on('froalaEditor.image.removed', function (e, editor, $img) {

		var imageDeleted = String($img.attr("src")).split("/").pop();

        $.ajax({
            // Request method.
            method: "POST",

            // Request URL.
            url: "url-API/Editor/delete.php",

            // Request params.
            data: {
                file: imageDeleted
            }
        })
        .done (function (data) {
            console.log ('image was deleted '+data);
        })
        .fail (function () {
            console.log ('image delete problem');
        })
    }).on('froalaEditor.file.unlink', function (e, editor, link) {

		var fileDeleted = String(link).split("/").pop();

        $.ajax({
            // Request method.
            method: "POST",

            // Request URL.
            url: "url-API/Editor/delete.php",

            // Request params.
            data: {
                file: fileDeleted
            }
        })
        .done (function (data) {
            console.log ('file was deleted '+data);
        })
    }).on('froalaEditor.video.removed', function (e, editor, $video) {

        var videoDeletedURL = $video.context.lastChild.src;

        $.ajax({
            // Request method.
            method: "POST",

            // Request URL.
            url: "url-API/Editor/delete.php",

            // Request params.
            data: {
                file: videoDeletedURL.split("/").pop()
            }
        })
        .done (function (data) {
            console.log ('video was deleted '+data);
        })
    });
}
