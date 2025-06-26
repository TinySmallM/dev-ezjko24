CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];

	config.removeButtons = 'Save,NewPage,ExportPdf,Preview,Print,Templates,Cut,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,About,ShowBlocks,Font,Smiley,PageBreak,Iframe,SpecialChar,Anchor,Outdent,Indent,CreateDiv,Blockquote,Language,BGColor,HorizontalRule,Styles';
	config.allowedContent = '*';
	config.extraAllowedContent = '*';
	config.extended_valid_elements = "span,style,link[href|rel]";
    config.custom_elements = "span,style,link,~link";
};