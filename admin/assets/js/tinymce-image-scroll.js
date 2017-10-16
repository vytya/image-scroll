(function() {
	var getImageSizes = function() {
		var imageSizes = [];

		if (imageScrollData.image_sizes) {
			imageScrollData.image_sizes.forEach(function(it){
				var d = {};

				d = {
					'text': it,
					'value': it
				};

				imageSizes.push(d);
			});
		}

		return imageSizes;
	}

	tinymce.PluginManager.add( 'image_scroll', function( editor, url ) {
		editor.addButton( 'image_scroll', {
			title: "Image Scroll shortcode",
			image: 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDIxNCAyMTQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDIxNCAyMTQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIiB3aWR0aD0iNTEycHgiIGhlaWdodD0iNTEycHgiPjxnPjxwYXRoIGQ9Ik0wLDIxNGgxNzRWNDBIMFYyMTR6IE00NC42NjIsNzIuNjY2YzcuNzAzLDAsMTMuOTQ4LDYuMjQ1LDEzLjk0OCwxMy45NDhzLTYuMjQ1LDEzLjk0OC0xMy45NDgsMTMuOTQ4ICAgcy0xMy45NDgtNi4yNDUtMTMuOTQ4LTEzLjk0OFMzNi45NTksNzIuNjY2LDQ0LjY2Miw3Mi42NjZ6IE01Ny4zOTgsMTI1LjQybDE5Ljk0NCwyNS44MjdsMzAuOTk3LTQ0LjAyTDE1OS41ODMsMTgwSDE1LjI1ICAgTDU3LjM5OCwxMjUuNDJ6IiBmaWxsPSIjNTU1ZDY2Ii8+PHBvbHlnb24gcG9pbnRzPSI0NCwwIDQ0LDI2IDE4OCwyNiAxODgsMTcwIDIxNCwxNzAgMjE0LDAgICIgZmlsbD0iIzU1NWQ2NiIvPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48L3N2Zz4=',
			onclick: function() {
				editor.windowManager.open({
					title: "Insert Image Scroll",
					body: [{
						type: 'textbox',
						subtype: 'hidden',
						name: 'id',
						id: 'hiddenID'
					},
					{
						type: 'button',
						text: 'Choose images',
						onclick: function(e){
							e.preventDefault();
							var hidden = jQuery('#hiddenID');
							var imageView = jQuery('#imageView');
							var custom_uploader = wp.media.frames.file_frame = wp.media({
								title: 'Choose image',
								button: {text: 'Add image'},
								multiple: true
							});

							custom_uploader.on('select', function() {
								var attachments = custom_uploader.state().get('selection').toJSON(),
									ids = new Array,
									thumbs = new Array;

								attachments.forEach(function(it, i){
									ids.push(it.id);

									if (i === 0) {
										thumbs.push('<div style="display: flex; flex-wrap: wrap; align-items: center; overflow-y: scroll; max-height: 100%; max-width: 100%;">');
									}

									if (it.sizes) {
										if (it.sizes.thumbnail) {
											thumbs.push('<img src="' + it.sizes.thumbnail.url + '" style="border: 1px solid #ccc; width: 75px; height: 75px; object-fit: cover; margin-right: 5px; margin-bottom: 5px;"> ');
										} else {
											thumbs.push('<img src="' + it.sizes.full.url + '" style="border: 1px solid #ccc; width: 75px; height: 75px; object-fit: cover; margin-right: 5px; margin-bottom: 5px;"> ');
										}
									}

									if (i === (attachments.length - 1)) {
										thumbs.push('</div>');
									}
								})

								hidden.val(ids.join());
								imageView.html(thumbs.join(''));
							});

							custom_uploader.open();
						}
					},
					{
						type: 'container',
						name: 'Image',
						label: 'Images',
						html: '<div style="width: 200px; height: 200px; display: flex; align-items: center;">Choose images first</div>',
						id: 'imageView'
					},
					{
						type: 'listbox',
						name: 'size',
						label: 'Size',
						values: getImageSizes(),
						minWidth: 350
					},
					],
					onsubmit: function(e) {
						editor.insertContent(
							'[imagescroll imageids="' + e.data.id + '" size="' + e.data.size + '"][/imagescroll]'
						);
					}
				})
			}
		});
	});
})();
