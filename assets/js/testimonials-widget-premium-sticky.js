jQuery( document ).ready( function ( $ ) {
	// Editing an individual custom post
	if ( twps.screen == 'post' ) {

		// Change visibility label if appropriate
		if ( parseInt( twps.is_sticky ) ) {
			$( '#post-visibility-display' ).text( twps.sticky_visibility_text );
		}

		// Add checkbox to visibility form
		$( '#post-visibility-select label[for="visibility-radio-public"]' ).next( 'br' ).after(
			'<span id="sticky-span">' +
			'<input id="sticky" name="sticky" type="checkbox" value="sticky"' + twps.checked_attribute + ' /> ' +
			'<label for="sticky" class="selectit">' + twps.label_text + '</label>' +
			'<br />' +
			'</span>'
		);

		// Browsing custom posts
	} else {

		// Add "Sticky" filter above post table if appropriate
		if ( parseInt( twps.sticky_count ) > 0 ) {
			var publish_li = $( '.subsubsub > .publish' );

			publish_li.after(
				'<li class="sticky">' +
				'<a href="edit.php?post_type=' + twps.post_type + '&show_sticky=1">' +
				twps.sticky_text +
				' <span class="count">(' + twps.sticky_count + ')</span>' +
				'</a> |' +
				'</li>'
			);
		}

		// Add checkbox to quickedit forms
		$( 'span.title:contains("' + twps.status_label_text + '")' ).parent().after(
			'<label class="alignleft">' +
			'<input type="checkbox" name="sticky" value="sticky" /> ' +
			'<span class="checkbox-title">' + twps.label_text + '</span>' +
			'</label>'
		);

	}
} );
