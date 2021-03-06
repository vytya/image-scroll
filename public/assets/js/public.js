(function() {
	jQuery(window).ready(function(){
		jQuery('.image-scroll').imagesLoaded( function() {
			var swiper = null;

			var getMaxHeight = function() {
				var maxHeightImage = 0;
				var images = jQuery('.image-scroll img');

				for (var i = 0; i < images.length; i++) {
					if ( maxHeightImage < images[i].height ) {
						maxHeightImage = images[i].height;
					}
				}

				jQuery( '.image-scroll' ).each( function() {
					jQuery(this).height(maxHeightImage);
				});
			}

			getMaxHeight();

			jQuery(window).resize(function() {
				getMaxHeight();
			})

			jQuery( '.image-scroll' ).each( function() {
				var aspectRatio = jQuery(this).data( 'aspect-ratio' );

				var swiper = new Swiper( '.image-scroll', {
					direction: 'vertical',
					scrollbarHide: false,
					scrollbarDraggable: true,
					slidesPerView: 1,
					slidesPerGroup: 1,
					slidesPerColumn: 1,
					spaceBetween: 0,
					speed: 200,
					loop: false,
					freeMode: false,
					grabCursor: false,
					mousewheel: true,
					paginationClickable: true,
					scrollbar: {
						el: '.swiper-scrollbar',
						hide: false,
					},
					slidesPerView: 'auto',
					centeredSlides: true,
					grabCursor: true,
					autoHeight: false,
					effect: 'fade',
				} );

				var loadingImage = (imageScrollData.loading_image) ? imageScrollData.loading_image : 'Loading image #%curr%...',
					notLoadedImage = (imageScrollData.not_loaded_image) ? imageScrollData.not_loaded_image : '<a href="%url%">The image #%curr%</a> could not be loaded.';

				jQuery( this ).magnificPopup({
					delegate: 'a',
					type: 'image',
					tLoading: loadingImage,
					mainClass: 'mfp-img-mobile',
					gallery: {
						enabled: true,
						navigateByImgClick: true,
						preload: [0,1]
					},
					image: {
						tError: notLoadedImage,
						titleSrc: function(item) {
							return item.el.attr('title');
						}
					}
				});
			});
		});
	});
}());
