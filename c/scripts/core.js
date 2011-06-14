/**
 * Behaviour for 5secondbookmarklet.tylergaw.com
 * @author Tyler Gaw <me@tylergaw.com>
 *
 */
jQuery(document).ready(function ()
{
	var alertTip,
	isIE = $.browser.msie;
	
	$('#link-bookmarklet').click(function ()
	{
		var data = {}, x, y;
		
		x = $(this).offset().left - 45;
		y = $(this).offset().top - 130;
		
		if (isIE)
		{
			data = {
				'msg': "Right-click and select 'Add To Favorites...' to save this link to your browser's 'Links' toolbar.",
				'coords': {
					'x': x,
					'y': y
				},
				'timeout': 7000
			};
		}
		else 
		{
			data = {
				'msg': "Drag this link to your browser's bookmarks toolbar.",
				'coords': {
					'x': x,
					'y': y
				},
				'timeout': 4000
			};
		}
		alertTip(data);
		return false;
	});
	
	
	/**
	 * Installation hiding/showing
	 */
	$('#installation ul').hide();
	$('#installation-trigger').click(function ()
	{
		$('#installation ul').slideToggle(200);
		return false;
	});

	
	/**
	 * External links open in new window
	 */
	$("a[rel='external']").click(function () 
	{ 
		window.open($(this).attr('href')); 
		return false; 
	});
	
	
	/**
	 * Display our custom alert balloon
	 *
	 * @param data OBJ - {msg, coords:{x, y}}
	 *
	 */
	alertTip = function (data)
	{
		var ieClass, timeout;
		clearTimeout(timeout);

		$('div.tooltip').remove();

		ieClass = (data.isIE) ? ' exploder' : '';
		$('body').append('<div class="tooltip' + ieClass + '" style="opacity:0;"><p>' + data.msg + '</p><span></span></div>');

		$('div.tooltip').css({'left': data.coords.x + 'px', 'top': (data.coords.y + 10) + 'px'})
			.animate({'opacity': 0.9, 'top': data.coords.y + 'px'}, 300);

		timeout = setTimeout(
						function () 
						{	
							$('div.tooltip').remove();
						}, 
						data.timeout);
	};
});