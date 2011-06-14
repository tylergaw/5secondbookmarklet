<?php
	include ('../models/FiveSecondVideos.php');
	$videos = new FiveSecondVideos();
	
	/**
	 * Fetch a set of 10 video URLs as an array
	 */
	function fetchVideoPageSetAsArrayTest ($videos)
	{
		$videoSet = $videos->fetchVideoPageSet(20, true);
		prettyOutput($videoSet);
	}
	//fetchVideoPageSetAsArrayTest($videos);
	
	
	/**
	 * Fetch a set of 10 video URLs as a JSON object
	 */
	function fetchVideoPageSetAsJSONTest ($videos)
	{
		$videoSet = $videos->fetchVideoPageSet(20);
		prettyOutput($videoSet);
	}
	//fetchVideoPageSetAsJSONTest($videos);
	
	
	/**
	 * Fetch all video page sets as an Arry
	 */
	function fetchAllVideoPageSetsAsArrayTest ($videos)
	{
		$videoSet = $videos->fetchAllVideoPageSets(true);
		prettyOutput($videoSet);
	}
	//fetchAllVideoPageSetsAsArrayTest($videos);
	
	
	/**
	 * Fetch all video page sets as a JSON object
	 */
	function fetchAllVideoPageSetsAsJSONTest ($videos)
	{
		$videoSet = $videos->fetchAllVideoPageSets();
		prettyOutput($videoSet);
	}
	//fetchAllVideoPageSetsAsJSONTest($videos);
	
	
	/**
	 * Fetch video data for a single video as a JSON object
	 */
	function fetchVideoDataTest ($videos, $videoUrl)
	{
		$videoSet = $videos->fetchVideoData($videoUrl);
		prettyOutput($videoSet);
	}
	//fetchVideoDataTest($videos, "http://5secondfilms.com/watch/choose_your_own_adventure");
	
	
	/**
	 * Fetch the newest video URL
	 */
	function fetchNewestVideoUrlTest ($videos)
	{
		$videoSet = $videos->fetchNewestVideoUrl();
		prettyOutput($videoSet);
	}
	//fetchNewestVideoUrlTest($videos);
	
	/**
	 * Let's see it!
	 */
	function prettyOutput($data)
	{
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
?>