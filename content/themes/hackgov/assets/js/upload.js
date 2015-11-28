jQuery( function ( $ ) {

	/*
	 * Enable front-end uploading of images.
	 */
	var Tj_Upload = {
		init : function () {
			$( document ).on( 'dragover', '.tj_drop_image', this.onDragOver );
			$( document ).on( 'dragleave', '.tj_drop_image', this.onDragLeave );
			$( document ).on( 'drop', '.tj_drop_image', this.onDrop );
			$( document ).on( 'change', '.tj_select_file', this.onDrop );


			if ( ! ( 'FileReader' in window && 'File' in window ) ) {
				$( '.tj_drop_image .dragging' ).text( Tj_Upload_Opt.labels.unsupported );
				$( document ).off( 'drop' ).on( 'drop', '.tj_drop_image', this.onDragLeave );
			}
		},

		/**
	 	 * Only upload image files.
		 */
		filterImageFiles : function ( files ) {
			var validFiles = [];

			for ( var i = 0, _len = files.length; i < _len; i++ ) {
				if ( files[i].type.match( /^image\//i ) ) {
					validFiles.push( files[i] );
				}
			}

			return validFiles;
		},

		dragTimeout : null,

		onDragOver: function ( event ) {
			event.preventDefault();

			clearTimeout( Tj_Upload.dragTimeout );
			$( '.tj_drop_image.dragging' ).removeClass( 'dragging' );
			$( this ).addClass( 'dragging' );
		},

		onDragLeave: function ( event ) {
			var elemactive = $(this);
			clearTimeout( Tj_Upload.dragTimeout );

			// In Chrome, the screen flickers because we're moving the drop zone in front of 'body'
			// so the dragover/dragleave events happen frequently.
			Tj_Upload.dragTimeout = setTimeout( function () {
				elemactive.removeClass( 'dragging' );
			}, 100 );
		},

		onDrop: function ( event ) {
			event.preventDefault();
			event.stopPropagation();

			// recent chrome bug requires this, see stackoverflow thread: http://bit.ly/13BU7b5
			event.originalEvent.stopPropagation();
			event.originalEvent.preventDefault();

			var files = (this.files) ? this.files : Tj_Upload.filterImageFiles( event.originalEvent.dataTransfer.files );

			$( this ).removeClass( 'dragging' );

			if ( files.length == 0 ) {
				alert( Tj_Upload_Opt.labels.invalidUpload );
				return;
			}

			$( this ).closest('.tj_drop_image').find('.caption').hide();
			$( this ).closest('.tj_drop_image').css({'background-image': 'url("'+Tj_Upload_Opt.preloader+'")', 'background-size': '50px', 'background-repeat': 'no-repeat', 'background-position': 'center'});
			$( this ).addClass( 'uploading' );
			var elem = $(this);

			var formData = new FormData();

			for ( var i = 0, fl = files.length; i < fl; i++ ) {
				formData.append( 'image_' + i, files[ i ] ); // won't work as image[]
			}

			// alert(Tj_Upload_Opt.preloader);

			$.ajax( {
				url:         Tj_Upload_Opt.ajaxurl + '&nonce=' + Tj_Upload_Opt.nonce + '&user_id=' + Tj_Upload_Opt.user_id,
				data:        formData,
				processData: false,
				contentType: false,
				type:        'POST',
				dataType:    'json',
				xhrFields:   { withCredentials: false }
			} )
			.done( function( data ) {
				console.log(data);
				// elem.find('.status').text('uploading');
				// elem.find('.id').text(data.url);

				if ( 'id_image' in data ) {
					// document.location.href = data.url;
					// elem.find('.id_image').text(data.id_image);
					// alert('ok : '+data.id_image);
					elem.closest('.tj_drop_image').find('.uploaded_img').attr('value', data.id_image);
					elem.closest('.tj_drop_image').css({'background-image': 'url("' + data.url + '")', 'background-size': 'contain', 'background-repeat': 'no-repeat', 'background-position': 'center'});
				} else if ( 'error' in data ) {
					alert( 'Upload failed' );
					console.log(data);
					$( this ).removeClass( 'uploading' );
				}
			} )
			.fail( function ( req ) {
				alert( Tj_Upload_Opt.labels.error );
			} );
		}
	};

	Tj_Upload.init();
} );