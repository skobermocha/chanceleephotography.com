<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/css/reset.css" />
<link rel="stylesheet" type="text/css" href="style/css/layout.css" />
<link rel="stylesheet" type="text/css" href="style/css/typography.css" />
		<!--[if lte IE 7]>
			<link rel="stylesheet" type="text/css" media="all" href="style/css/ie.css" />
		<![endif]-->
		<!--[if lte IE 6]>
			<link rel="stylesheet" type="text/css" media="all" href="style/css/ie6.css" />
		<![endif]-->

<title>Chance Lee Photography - Homepage</title>

</head>
<body>

<!-- Enter Desrciption of Page -->
<div id="hiddentext">
<!--
Enter Text Here
-->
</div>

<div class="wrapper">

	<!-- Main Photo Display for Rotaion -->
	<div class="photo"> 
		<a href="/"><?PHP srand((double)microtime()*1000000); ?>
		<img src="gallery/resources/rotate.php?image=<?PHP echo rand(0,100); ?>" id="randimage" alt="Random Image" title="Click for new image" width="960" />
		<script type="text/javascript">
		document.getElementById('randimage').src = document.getElementById('randimage').src + '?unique=' + new Date().valueOf();
		</script></a>
	</div> <!-- End Photo -->
	
	
	<!-- Logo / Main Page Title -->
	<div class="logo">
		<p><h1 id="logo"><a href="">Chance Lee Photography</a>
	</div><!-- End Logo -->
		
	<!-- Main Nav links -->
	<div class="nav">
		<ul class="nav">
			<li id="homeButton"><a href="followme/about/">About</a></li>
			<li><a href="followme/" alt="followme">Follow Me</a></li>
			<li><a href="http://gallery.chanceleephotography.com" alt="http://gallery.chanceleephotography.com">Gallery</a></li>
			<li><a href="followme/contact/" alt="/followme/contact/">Contact</a></li>			
		</ul>
	</div> <!--  End Nav -->
</div><!-- End Wrapper -->
	
<div class="footer">
			<div class="footertext">
				<div class="feeds">
					<ul>
							<li><h2><a href="followme/feed" title="Subscribe to Feed">Subscribe</a></h2></li>
					</ul>
				</div><!-- .feeds -->
				
				<div class="credits">

					<h2>&copy; 2009 Chance Lee Photography.<br />All rights reserved.</h2>
				<!--
	<div class="disclaimer">
						<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=3&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=26175630%40N07"></script>
					</div>
-->
					
				</div><!-- .credits -->
			</div>
		</div><!-- .footer -->

</body>
</html>