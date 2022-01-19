<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <script type="text/javascript" src="tinymce4/js/tinymce/tinymce.min.js"></script>
        <textarea id="editormce" class="form-control wrs_div_box" contenteditable="true" tabindex="0" spellcheck="false" aria-label="Rich Text Editor, example"></textarea>

        <script>
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

                     external_filemanager_path:"filemanager/",
                     filemanager_title:"Tata Kelola Files"
                });
                }

                // Creating TINYMCE demo instance.
                createEditorInstance('en', {});

        </script>
    </body>
</html>
