<?php
	$wceplugins = "";
	$wceexplugins = '';
	$kvobtn = 0;
	$bar1 = '';
	if ($result['btn_undo'] == 1) { $bar1 = $bar1 . '"Undo",'; $kvobtn++; }
	if ($result['btn_redo'] == 1) { $bar1 = $bar1 . '"Redo"'; $kvobtn++; }
	if ($bar1!='') { $bar1 = '{name: "bar1", items: ['.$bar1 .  ' ] }, '; }

	$barfont = '';
	if ($result['box_fontsize'] == 1) { $barfont = $barfont . '"FontSize",'; $kvobtn++; }
	if ($result['box_font'] == 1) { $barfont = $barfont . '"Font"'; $kvobtn++; }
	if ($barfont!='') { $barfont = '{name: "barfont", items: [' . $barfont .  ' ] }, '; }
	$bar2 = '';
	if ($result['btn_bold'] == 1) { $bar2 = $bar2 . '"Bold",'; $kvobtn++; }
	if ($result['btn_italic'] == 1) { $bar2 = $bar2 . '"Italic"'; $kvobtn++; }
	if ($bar2!='') { $bar2 = '{name: "bar2", items: ['.$bar2 .  ' ] } ,'; }
	$bar3 = '';
	if ($result['btn_underline'] == 1) { $bar3 = $bar3 . '"Underline",'; $kvobtn++; }
	if ($result['btn_strikethrough'] == 1) { $bar3 = $bar3 . '"Strike"'; $kvobtn++; }
	if ($bar3!='') { $bar3 = '{name: "bar3", items: ['.$bar3 .  ' ] } ,'; }
	$bar4 = '';
	if ($result['btn_forecolor'] == 1) { $bar4 = $bar4 . '"TextColor",'; $kvobtn++; }
	if ($result['btn_backcolor'] == 1) { $bar4 = $bar4 . '"BGColor"'; $kvobtn++; }
	if ($bar4!='') { $bar4 = '{name: "bar4", items: ['.$bar4 .  ' ] } ,'; }
	$bar5 = '';
	if ($result['btn_link'] == 1) { $bar5 = $bar5 . '"Link","Unlink","Anchor","-",'; $kvobtn++; }
	if ($result['btn_image'] == 1) { $bar5 = $bar5 . '"Image"'; $kvobtn++; }
	if ($bar5!='') { $bar5 = '{name: "bar5", items: ['.$bar5 .  ' ] } ,'; }
	$bar6 = ''; 
	if ($result['btn_blockquote'] == 1) { $bar6 = $bar6 . '"Blockquote","-",'; $kvobtn++; }
	if ($result['btn_code'] == 1) { $bar6 = $bar6 . '"Source"'; $kvobtn++; }
	if ($bar6!='') { $bar6 = '{name: "bar6", items: ['.$bar6 .  ' ] } ,'; }
	// Вторая панель (это чисто эмпирически - всё может быть в одну панель)
	$bar7 = ''; 
	if ($result['btn_bullist'] == 1) { $bar7 = $bar7 . '"BulletedList",'; $kvobtn++; }
	if ($result['btn_numlist'] == 1) { $bar7 = $bar7 . '"NumberedList"'; $kvobtn++; }
	if ($bar7!='') { $bar7 = '{name: "bar7", items: ['.$bar7 .  ' ] } ,'; }
	$bar8 = ''; 
	if ($result['btn_table'] == 1) { $bar8 = $bar8 . '"Table",'; $kvobtn++; }
	if ($bar8!='') { $bar8 = '{name: "bar8", items: ['.$bar8 .  ' ] }, '; }
	$bar9 = '';
	if ($result['btn_emoticons'] == 1) { $bar9 = $bar9 . '"Smiley",'; $kvobtn++; }
	if ($bar9!='') { $bar9 = '{name: "bar9", items: ['.$bar9 .  ' ] }, '; }
	$bar10 = '"HorizontalRule","-","Maximize"';
	if ($bar10!='') { $bar10 = '{name: "bar10", items: ['.$bar10 .  ' ] }, '; }

	// Формируем тулбары
	/*$toolbar = $bar1 . $barfont. $bar2 . $bar3 . $bar4 . $bar5 . $bar6 . $bar7 . $bar8 . $bar9 . $bar10;*/
	$toolbar = $bar1 . $bar2 . $bar3 . $bar4 . $bar5 . $bar6 . $bar7 . $bar8 . $bar9 . $barfont . $bar10 ;
	/*$toolbar = '["' . $toolbar . '"]';*/
	//echo $toolbar;
	// формируем external_plugins
	if ($wceexplugins != '') {
	$wceexplugins = 'external_plugins :  { ' . $wceexplugins . ' }, ';
	}
	// Формируем языковые настройки
	/*if ($result['wce_lang']=="english") { $wcelang = '';} else {$wcelang = 'language_url : "'.plugins_url( '/js/ru.js',__FILE__ ) .'",';}*/
	switch ($result['wce_lang']) {
	case "english":
		$wcelang = '';
		break;
	case "русский":
		$wcelang = 'language_url : "'.plugins_url( '/js/ru.js',__FILE__ ) .'",';
		break;
	case "french":
		$wcelang = 'language_url : "'.plugins_url( '/js/fr_FR.js',__FILE__ ) .'",';
		break;
	}
	// Фиксированная ширина редактора
	$wcewidth = '';
	if ($result['wce_widthfix']=="1") { $wcewidth = 'width: '.$result['wce_width'].',';} 
	$wcecontentcss = '';
	if ($result['wce_edtbquotestyle'] == 1) {
		$wcecontentcss = 'content_css : "'.plugins_url( '/plugins/arkbquote/css/arkbquote.css',__FILE__ ) .'",';
	}
	$myeditor = '
		<script src="//cdn.ckeditor.com/4.4.7/full/ckeditor.js"></script>
        <script>
		   CKEDITOR.replace( "comment" );
		   CKEDITOR.config.disableNativeSpellChecker = false;
		   CKEDITOR.config.removePlugins = "scayt,contextmenu";
		CKEDITOR.config.toolbar = ['.$toolbar.'];

        </script>
	';
	echo $myeditor;
?>
