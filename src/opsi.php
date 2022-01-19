<?php ?>
	<script type="text/javascript" src = "./assets/tinymce4/js/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image"></script>
	<script type="text/javascript" src = "./assets/tinymce4/js/tinymce/tinymce.min.js"></script>
	<link type="text/css" rel="stylesheet" href="./assets/tinymce4/css/prism.css" />
	<script type="text/javascript">

				tinymce.init({
		    		selector: '.myeditablediv',
		    		height : 100,
		        	menubar: false,
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
		              "table contextmenu directionality emoticons paste textcolor responsivefilemanager code tiny_mce_wiris"
		         ],
		         toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
		         toolbar2: "| link unlink anchor | image media | forecolor backcolor | print preview | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry",
		         image_advtab: true
		  		});

					// $(".opsitambahan").append("<fieldset class='form-group'><label class='form-label' for='exampleInput'>Pilihan 2</label><textarea class='myeditablediv' name='jawaban2'></textarea>Set Jawaban Benar <input type='radio' name='gender' value='1'></fieldset>");

					
				}
	</script>
	<fieldset class="form-group">
						<label class="form-label " for="exampleInput">Pilihan 2</label>
						<textarea class="myeditablediv" name="jawaban2"></textarea> 
						Set Jawaban Benar <input type="radio" name="gender" value="1">
					</fieldset>
	<script type="text/javascript" src =="./assets/tinymce4/js/wirislib.js" ></script>
	<script type="text/javascript" src =="./assets/tinymce4/js/prism.js"></script>