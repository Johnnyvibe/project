(function() {
	tinymce.PluginManager.add('true_mce_button', function( editor, url ) { 
		editor.addButton('true_mce_button', { 
			text: '[custom-meta-user]', 
			title: 'Вставить шорткод [custom-meta-user]', 
			icon: false, 
			onclick: function() {
				editor.insertContent('[custom-meta-user]'); 
			}
		});
	});
})();