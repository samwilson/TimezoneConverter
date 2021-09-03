( function () {

	/**
	 * @param {HTMLTimeElement} timeEl
	 */
	function convertTimeElement( timeEl ) {
		var date = new Date( Date.parse( timeEl.dateTime ) ),
			dateOptions = JSON.parse( timeEl.dataset.dateOptions ) || {};
		timeEl.title = timeEl.innerText;
		timeEl.innerText = date.toLocaleString( [], dateOptions );
	}

	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		var page = $content[ 0 ];
		page.querySelectorAll( '.ext-timezoneconverter' ).forEach( function ( element ) {
			convertTimeElement( element );
		} );
	} );

}() );
