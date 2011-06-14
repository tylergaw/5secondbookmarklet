<?php

include ('../lib/Scrape.php');

class FiveSecondVideos
{
	private $_scrape;
	private $_baseUrl  = "http://5secondfilms.com/films/";
	private $_listFile = "../../bookmarklet/filmList.js";
	
	public function __construct() 
	{
		$this->_scrape = new Scrape();
		return true;
	}
	
	
	/**
	 * Build a full index of each video on each page
	 * @param $asArray BOOL=false - default to a JSON string, if true will return an array with each page url instead
	 * @return $pageSets JSON || ARRAY
	 */
	public function fetchAllVideoPageSets ($asArray = false)
	{
		$data = $this->_scrapeDataRequest($this->_baseUrl);
		
		/**
		 * First determine the total number of video pages
		 *
		 * They do paging by the number of the first video to show on the page, serving up 10 at a time
		 * urls are like /films/P10, /films/P20,...
		 */
		$pageData = $this->_scrape->fetchBetween('<div class="pagination">', '</div>', $data, true);
		$pagination = $this->_scrape->fetchAllBetween('<a href="http://5secondfilms.com/films/P', '/">', $pageData, false);
		
		$lastPageNum = $pagination[count($pagination) -1];
		// Overiding so I can test without a huge request
		$lastPageNum = 10;

		/**
		 * 
		 */
		if ($asArray)
		{			
			$pageSets = array();
			$tmpArray = array();
			for ($i = 0; $i <= $lastPageNum; $i+=10)
			{					
				foreach($this->fetchVideoPageSet($i, true) as $item)
				{
					$pageSets[] = $item;
				}
			}
		}
		else
		{
			$pageSets = '{' ."\n". ' "fiveSecondVideos" : { ' ."\n". '"pages": [' . "\n";
			for ($i = 0; $i <= $lastPageNum; $i+=10)
			{	
				$pageSets .= $this->fetchVideoPageSet($i);

				if ($i == $lastPageNum)
				{
					$pageSets .= "\n";
				}
				else
				{
					$pageSets .= ",\n";
				}
			}
			$pageSets .= ']}}';
		}
		
		return $pageSets;
	}
	
	
	/**
	 * Fetch an array of 10 video urls beginning with $startNum
	 *
	 * @param $startNum INT
	 * @return $pageSet JSON || ARRAY
	 */
	public function fetchVideoPageSet ($startNum, $asArray = false)
	{
		$pageUrl = $this->_baseUrl . "P" . $startNum;
		$data = $this->_scrapeDataRequest($pageUrl);
		$data = $this->_scrape->fetchAllBetween('<div class="result">', '<img', $data, true);
		
		if ($asArray)
		{
			$pageSet = array();
			foreach ($data as $item)
			{
				$pageUrl = $this->_scrape->fetchBetween('<a href="', '">', $item, false);
				$pageSet[] = $pageUrl;
			}
		}
		else
		{
			$pageSet = "{\n"; 
			for ($i = 0; $i < count($data); $i++)
			{
				$pageUrl = $this->_scrape->fetchBetween('<a href="', '">', $data[$i], false);
				$pageSet .= "\t" . '"url" : "' . $pageUrl . '"';
				
				if ($i == (count($data) - 1))
				{
					$pageSet .= "\n";
				}
				else
				{
					$pageSet .= ",\n";
				}
			}
			
			$pageSet .= "}";
		}
		return $pageSet;
	}
	
	
	/**
	 * Fetch the title, url, and file url for a single video
	 *
	 * @param $videoUrl STRING - e.g., http://5secondfilms.com/watch/family_arcade
	 * @return $videoData JSON
	 *
	 */
	public function fetchVideoData ($videoUrl)
	{
		$video = "{\n";
		$video .= "\t" . '"url" : "' . $videoUrl . '",' . "\n";
		$data = $this->_scrapeDataRequest($videoUrl);
		
		/**
		 * Video Title
		 */
		$videoTitle = $this->_scrape->fetchBetween('<div id="content"><h1>', '</h1>', $data, false);
		$video .= "\t" . '"title" : "' . $videoTitle . '",' . "\n";

		/**
		 * Video File (url of file stored at http://s3.amazonaws.com/5sf/films/)
		 */
		$videoFile = $this->_scrape->fetchBetween('flashvars: "file=', '"};', $data, false);
		$video .= "\t" . '"file" : "' . $videoFile . '"' . "\n";
		
		$video .= "}";
		
		return $video;
	}
	
	
	/**
	 * Fetch all videos and their data - title, url, file url
	 *
	 * @return $videos JSON
	 */
	public function fetchAllVideos ()
	{
		$videoUrlArray = $this->fetchAllVideoPageSets(true);
		
		$videos = '{' ."\n". ' "fiveSecondVideos" : { ' ."\n". '"videos": [' . "\n";
		
		for ($i = 0; $i < count($videoUrlArray); $i++)
		{
			$videos .= $this->fetchVideoData($videoUrlArray[$i]);
			
			if ($i == (count($videoUrlArray) - 1))
			{
				$videos .= "\n";
			}
			else
			{
				$videos .= ",\n";
			}
		}
		
		$videos .= "]}}";
		
		return $videos;
	}
	
	
	/**
	 * Fetch the URL of the newest video
	 *
	 * @return $url STRING - The URL of the newest video
	 */
	public function fetchNewestVideoUrl ()
	{
		$data = $this->_scrapeDataRequest($this->_baseUrl);
		$data = $this->_scrape->fetchBetween('<div class="result">', '<img', $data, true);
		$url = $this->_scrape->fetchBetween('<a href="', '">', $data, false);
		
		return $url;
	}
	
	
	/**
	 * Update the filmlist file with the newest video
	 *
	 */
	public function updateVideoList ()
	{
		$newestUrl = $this->fetchNewestVideoUrl();
		$filmListContents = file_get_contents($this->_listFile);
		$pos = strpos($filmListContents, $newestUrl);
		
		/**
		 * If the newest video URL is not on the list, scrape the data and then insert it.
		 *
		 */
		if ($pos === false)
		{
			$newestVideoData = "\n" . $this->fetchVideoData($newestUrl) . ", ";
			
			if (is_writable($this->_listFile))
			{
				
				/**
				 * Open the file in write mode
				 */
				if (!$handle = fopen($this->_listFile, 'w'))
				{
					echo "Cannot open file";
					exit;
				}
				
				/**
				 * Build a new string with the newest video
				 */
				$firstPos   = strpos($filmListContents, "[");
				//$preamble   = strstr($filmListContents, "[", true) . "["; //will only work with PHP 5.3 - prod host has 5.2.11
				$preamble = substr($filmListContents, 0, $firstPos) . "[";
				$afterFirst = substr($filmListContents, $firstPos + 1, strlen($filmListContents));
				$updatedList = $preamble . $newestVideoData . $afterFirst;
				
				/**
				 * Write the updated string to the file
				 */
				if (fwrite($handle, $updatedList) === FALSE)
				{
					echo "Cannot write to file";
					exit;
				}
				
				
				echo "List updated with newest video " . $newestUrl;
				fclose($handle);
			}
			else
			{
				echo "The file " . $this->_listFile . " is not writable";
			}
		}
		else
		{
			echo "The List is Up to date, no new videos to add";
		}
	}
	
	
	/**
	 * Scrape a URL and return the data with a newlines stripped
	 * @param $url STRING
	 * @return $data STRING
	 * 
	 */
	private function _scrapeDataRequest ($url)
	{
		$this->_scrape->fetch($url);
		$data = $this->_scrape->removeNewLines($this->_scrape->result);
		return $data;
	}
}

?>