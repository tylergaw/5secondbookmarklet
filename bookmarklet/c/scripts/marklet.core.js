/**
 * 5 Second Films Bookmarklet - Get awesome-ass quick comedy on demand
 * @author Tyler Gaw - www.tylergaw.com me@tylergaw.com
 */

(function ($) {	
	
	var videoList, 
		getVideoList, 
		closeFrame, 
		curVideoIndex = 0, 
		nextVideo, 
		prevVideo, 
		randomVideo;
	
	/**
	 * Close the frame. Uses the reloadCount hack by Dave Hauenstein.
	 *
	 * @return false always to prevent event propagation
	 */
	closeFrame = function () 
	{
		location = 'close.html';
		return false;
	};
	
	
	/**
	 * To suppress Firefox SWF bug where embed from an iframe causes an unnecessary permission error.
	 * See more at: http://www.experts-exchange.com/Software/Internet_Email/Web_Browsers/Firefox/Q_23830500.html
	**/
	window.onerror = function (e) 
	{
		if (e.toString().substr(0, 21) == 'Permission denied for')
		{
			return true;			
		}
		return false;
	};
	
	
	/**
	 * Load the next video
	 *
	 */
	nextVideo = function ()
	{
		if (curVideoIndex == videoList.length - 1)
		{
			curVideoIndex = 0;
		}
		else
		{
			curVideoIndex += 1;
		}
		
		loadVideo(curVideoIndex);
		return false;
	};
	
	
	/**
	 * Load the previous video
	 *
	 */
	prevVideo = function ()
	{
		if (curVideoIndex === 0)
		{
			curVideoIndex = videoList.length - 1;
		}
		else
		{
			curVideoIndex -= 1;
		}
		
		loadVideo(curVideoIndex);
		return false;
	};
	
	
	/**
	 * Load a random video
	 *
	 */
	randomVideo = function ()
	{
		curVideoIndex = Math.floor((videoList.length - 1) * Math.random());
		loadVideo(curVideoIndex);
		return false;
	};
	
	
	/**
	 * Make request to retrieve full quiz json object set it as QuizData
	 *
	 */
	getVideoList = function ()
	{
		var url = 'filmList.js';
		jQuery.ajax({
			type: 'GET',
			dataType: 'json',
			url: url,
			error: 
				function (xhr, textStatus, errorThrown) 
				{
					alert('Sorry, an error has occurred. Please try again.'); 
				},
			success: 
				function (data)
				{
					videoList = data.fiveSecondVideos.videos;
					loadVideo(curVideoIndex);
				}
		});
	};
	
	
	/**
	 * Load the selected video
	 *
	 * @param index - the index of the video in videoList
	 */
	loadVideo = function (index)
	{
		var params;
		$('#video-container > h2').html(videoList[index].title).truncate({max_length: 130});		
		$('#video-container a').attr('href', videoList[index].url).html(videoList[index].url).truncate({max_length: 85});
		
		params = {
			flashvars: 'file=' + videoList[index].file
		};
	    swfobject.embedSWF("http://s3.amazonaws.com/5sf/files/player.swf", "video", "480", "290", "9.0.0", "", false, params);
	};
	
	
	/**
	 * Initializing function
	 *
	 */
	$(function () {
		videoList = getVideoList();
		$('#btn-close').click(closeFrame);
		$('#btn-next').click(nextVideo);
		$('#btn-prev').click(prevVideo);
		$('#btn-random').click(randomVideo);
	});
})(jQuery);