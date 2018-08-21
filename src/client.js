jQuery( function( $ ) {
	function onTriggerClick( el ) {
		const src = $( el.currentTarget ).attr( 'data-src' );

		if ( ! src ) {
			return false;
		}
		const container = $( '.heather-result__img' );

		container
			.fadeOut( 400, function() {
				container.attr( 'src', src );
			} )
			.fadeIn( 400 );
	}

	const trigger = $( '.heather-filter' );

	trigger.mouseenter( el => {
		const $this = $( el.currentTarget );

		if ( ! $this.hasClass( 'active' ) ) {
			$( '.heather-filter' )
				.not( $this )
				.removeClass( 'active' );

			el.preventDefault();
			onTriggerClick( el );
			$this.addClass( 'active' );
		}
	} );
} );
