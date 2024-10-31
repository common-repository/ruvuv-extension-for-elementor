(function() {
	jQuery.fn.bgscroll = jQuery.fn.bgScroll = function( options ) {
		
		if( !this.length ) return this;
		if( !options ) options = {};
		if( !window.scrollElements ) window.scrollElements = {};
		
		for( var i = 0; i < this.length; i++ ) {
			
			var allowedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			var randomId = '';
			for( var l = 0; l < 5; l++ ) randomId += allowedChars.charAt( Math.floor( Math.random() * allowedChars.length ) );
			
			this[ i ].current = 0;
			this[ i ].scrollSpeed = options.scrollSpeed ? options.scrollSpeed : 5;
			this[ i ].direction = options.direction ? options.direction : 'h';
			this[ i ].direction_type = options.direction_type ? options.direction_type : '-';
			this[ i ].direction_diagonal = options.direction_diagonal ? options.direction_diagonal : 'no';
			this[ i ].horizontal_position = options.horizontal_position ? options.horizontal_position : 'middle';
			this[ i ].vertical_position = options.vertical_position ? options.vertical_position : 'middle';
			window.scrollElements[ randomId ] = this[ i ];
			var diagonal = "1";
			if (this[ i ].direction_diagonal == 'yes') {
				diagonal = "-1";
			};

			var horizontal_position = '50%';
			if (this[ i ].horizontal_position == 'top') {
				horizontal_position = '0';
			} else if (this[ i ].horizontal_position == 'bottom') {
				horizontal_position = '100%';
			}

			var vertical_position = '50%';
			if (this[ i ].vertical_position == 'left') {
				vertical_position = '0';
			} else if (this[ i ].vertical_position == 'right') {
				vertical_position = '100%';
			}

			if (this[ i ].direction_type == '-') {
				eval( 'window[randomId]=function(){var axis=0;var e=window.scrollElements.' + randomId + ';e.current -= 1;if (e.direction == "h") axis = e.current + "px horizontal_position";else if (e.direction == "v") axis = vertical_position + " " + e.current + "px";else if (e.direction == "d") axis = e.current + "px " + (diagonal * e.current) + "px";jQuery( e ).css("background-position", axis);}' );
			} else {
				eval( 'window[randomId]=function(){var axis=0;var e=window.scrollElements.' + randomId + ';e.current += 1;if (e.direction == "h") axis = e.current + "px " + horizontal_position;else if (e.direction == "v") axis = vertical_position + " " + e.current + "px";else if (e.direction == "d") axis = e.current + "px " + (diagonal * e.current) + "px";jQuery( e ).css("background-position", axis);}' );
			}

			setInterval( 'window.' + randomId + '()', options.scrollSpeed ? options.scrollSpeed : 5 );
		}
		return this;
	}
})(jQuery);