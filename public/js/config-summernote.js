function initSummernote() {
    $('textarea').each(function() {
        if ($(this).hasClass('summernote')) {
            $(this).summernote({
                height: 400,
                toolbar: [
                	['font', ['bold', 'italic', 'underline']],
                	['alignment', ['alignLeft', 'alignCenter', 'alignJustify', 'alignRight']],
                	['custom', ['customList']]
                ],
                buttons: {
                	bold: function(context) {
			            var ui = $.summernote.ui;
			            var button = ui.button({
			                contents: '<i data-feather="bold"></i>',
			                tooltip: 'Gras',
			                click: function() {
			                    context.invoke('editor.bold');
			                },
			            });
			            return button.render();
			        },
			        italic: function(context) {
			            var ui = $.summernote.ui;
			            var button = ui.button({
			                contents: '<i data-feather="italic"></i>',
			                tooltip: 'Italique',
			                click: function() {
			                    context.invoke('editor.italic');
			                },
			            });
			            return button.render();
			        },
			        underline: function(context) {
			            var ui = $.summernote.ui;
			            var button = ui.button({
			                contents: '<i data-feather="underline"></i>',
			                tooltip: 'Souligné',
			                click: function() {
			                    context.invoke('editor.underline');
			                },
			            });
			            return button.render();
			        },
			        alignLeft: function(context) {
			            var ui = $.summernote.ui;
			            var button = ui.button({
			                contents: '<i data-feather="align-left"></i>',
			                tooltip: 'Aligner à gauche',
			                click: function() {
			                    context.invoke('editor.justifyLeft');
			                },
			            });
			            return button.render();
			        },
			        alignCenter: function(context) {
			            var ui = $.summernote.ui;
			            var button = ui.button({
			                contents: '<i data-feather="align-center"></i>',
			                tooltip: 'Centrer',
			                click: function() {
			                    context.invoke('editor.justifyCenter');
			                },
			            });
			            return button.render();
			        },
			        alignJustify: function(context) {
			            var ui = $.summernote.ui;
			            var button = ui.button({
			                contents: '<i data-feather="align-justify"></i>',
			                tooltip: 'Justifier',
			                click: function() {
			                    context.invoke('editor.justifyFull');
			                },
			            });
			            return button.render();
			        },
			        alignRight: function(context) {
			            var ui = $.summernote.ui;
			            var button = ui.button({
			                contents: '<i data-feather="align-right"></i>',
			                tooltip: 'Aligner à droite',
			                click: function() {
			                    context.invoke('editor.justifyRight');
			                },
			            });
			            return button.render();
			        },
			        customList: customListButton('list', 'Insérer une liste', function() {
			        	var selectedText = $('#summernote').summernote('getSelectedText');
			        
			        	if (selectedText) {
			            	$('#summernote').summernote('editor.formatBlock', '<ul>');
			          	} 
			          	else {
			            	$('#summernote').summernote('editor.insertUnorderedList');
			          	}
			        })
			    },
            });

            feather.replace();
        }
    });
}

function customListButton(iconName, tooltipText, handler) {
    var button = $('<button type="button" class="btn btn-light" title="' + tooltipText + '">');
    button.html('<i data-feather="' + iconName + '"></i>');
    button.click(handler);
    
    return button;
}

$(document).ready(function() {
	initSummernote();
});