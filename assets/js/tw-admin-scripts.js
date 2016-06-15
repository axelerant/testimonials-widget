(function ($) {
	jQuery( document ).on('click', '.twp-schema-notice .notice-dismiss', function (e) {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'tw_schema_notice',
				nonce: tw_schema_notice.nonce
			}
		});
		e.stopPropagation();
		return false;
	});
})(jQuery);
