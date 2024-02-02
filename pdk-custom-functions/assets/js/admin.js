jQuery(window).on('load', function(){
    // Code here will be executed on document ready. Use $ as normal.
    jQuery(function ($) {
	
		$('.mailtag').click(function(e){
			copyToClipboard(this);
			if( $(this).find('.tooltip').length===0){
				navigator.clipboard.readText().then((clipText) => ($(this).append('<span class="tooltip">Copied: '+clipText+'</span>')));
				setTimeout(function(){
					$('.tooltip').animate({
						opacity:'0'
					}, 500, 'linear', function(){
						$(this).remove();
					});
				},500)
			}
			
		})

		function copyToClipboard(element){
			var copyText = $("<input>");
			$("body").append(copyText);
			copyText.val($(element).text()).select();

			// Copy the text inside the text field
			navigator.clipboard.writeText(copyText.val());
			copyText.remove();
		}
		
	});
});