<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>5 Second Bookmarklet</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" type="text/css" href="c/styles/core.css" media="all" />
	</head>
	<body>
		<div id="container">
			<div id="header">
				<a id="btn-close" href="close">close</a>
				<h1><a href="http://5secondfilms.com" target="_new">5 Second Films</a></h1>
			</div>
			
			<div id="video-container">
				<h2>&nbsp;</h2>
				<div id="video-wrapper">
					<div id="video">
						<p id="video-loading">Loading Video...</p>
					</div>
				</div>
				<p>
					<a href="" target="_new"></a>
				</p>
			</div>
			
			<div id="controls">
				<ul>
					<li id="btn-prev"><a href="prev">Prev</a></li>
					<li id="btn-random"><a href="random">Random</a></li>
					<li id="btn-next"><a href="next">Next</a></li>
				</ul>
			</div>
			
			<div id="footer">
				<p>Copyright &copy; 2009 5-Second Films LLC</p>
				<ul>
					<li><a target="_new" id="btn-twitter" href="http://twitter.com/5sf">Twitter</a></li>
					<li><a target="_new" id="btn-vimeo" href="http://vimeo.com/fivesecondfilms">Vimeo</a></li>
					<li><a target="_new" id="btn-facebook" href="http://www.facebook.com/pages/5-Second-Films/38010451901">Facebook</a></li>
				</ul>
			</div>
		</div>
		
		<script type="text/javascript" src="c/scripts/lib/jquery/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="c/scripts/lib/swfObject.js"></script>
		<script type="text/javascript" src="c/scripts/lib/jquery/jquery.truncator.js"></script>
		<script type="text/javascript" src="c/scripts/marklet.core.js"></script>
	</body>
</html>