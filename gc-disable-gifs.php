<?php
/**
 * Created by IntelliJ IDEA.
 * User: sheershoff
 * Date: 12/3/14
 * Time: 1:06 PM
 */

	gc_disable(); // I just can't stand

	$cached = 'downloaded/source';

	if(!file_exists($cached)||(time()-filemtime($cached)>3600)) { // cache for 1 hour
		@unlink($cached);
		copy('https://github.com/composer/composer/commit/ac676f47f7bbc619678a29deae097b6b0710b799', $cached);
	}

	$dom = new DOMDocument();
	@$dom->loadHTMLFile($cached);
	$x = new DOMXPath($dom);

	foreach($x->query('.//div[@class="comment-content"]//.//img') as $node)
	{
		$src = $node->getAttribute("src");
		$srcinfo = parse_url($src);
		$srcpinfo = pathinfo($srcinfo['path']);
		if(!isset($srcpinfo['extension'])||empty($srcpinfo['extension'])||$srcpinfo['extension']=='0'){ // fix file extension
			$srcpinfo['extension']='img';
			$srcpinfo['basename']+='.'.$srcpinfo['extension'];
		};
		if(strlen($srcpinfo['basename'])>120) {
			$srcpinfo['basename'] = md5($srcpinfo['basename']) . '.' . $srcpinfo['extension']; // fix filename length issue
		}
		if(!file_exists('downloaded/'.$srcpinfo['basename']))
			copy($src,'downloaded/'.$srcpinfo['basename']);
	}