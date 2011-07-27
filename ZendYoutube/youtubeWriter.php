<?php
/**
* Please change the values in credentials.php
*/
ini_set("display_errors",0);
include 'credentials.php';
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata_YouTube');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');


$httpClient 	= Zend_Gdata_ClientLogin::getHttpClient(EMAIL_ID,EMAIL_PASS,'youtube',null,'UoAyoutube',null,null,'https://www.google.com/accounts/ClientLogin');
$applicationId 	= 'Video uploader v1';
$clientId 		= 'My video upload client - v1';

$yt = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId,YOUTUBE_DEVELOPER_KEY);
$yt->setMajorProtocolVersion(2);


$params = array(
				'fileName'         => 'sample.wmv',
				'contentType'      => 'video/quicktime',
				'videoTitle'       => 'Again Test',
				'videoDescription' => 'My Test Movie',
				'videoCategory'    => 'Autos',
				'videoTags'        => 'cars, funny',
				'videoCategory'    => 'Autos',
				'devTag'           => array('mydevtag', 'anotherdevtag'),
			);

saveVideo($yt,$params);

function saveVideo($yt,$params)
{
	$myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
	$filesource = $yt->newMediaFileSource($params['fileName']);
	$filesource->setContentType($params['contentType']);
	$filesource->setSlug($params['fileName']);
	$myVideoEntry->setMediaSource($filesource);
	$myVideoEntry->setVideoTitle($params['videoTitle']);
	$myVideoEntry->setVideoDescription($params['videoDescription']);
	$myVideoEntry->setVideoCategory($params['videoCategory']);
	$myVideoEntry->SetVideoTags($params['videoTags']);
	$myVideoEntry->setVideoDeveloperTags($params['devTag']);

	$yt->registerPackage('Zend_Gdata_Geo');
	$yt->registerPackage('Zend_Gdata_Geo_Extension');
	$where = $yt->newGeoRssWhere();
	$position = $yt->newGmlPos('37.0 -122.0');
	$where->point = $yt->newGmlPoint($position);
	$myVideoEntry->setWhere($where);
	$uploadUrl = 'http://uploads.gdata.youtube.com/feeds/api/users/bennetmundackattu/uploads';
	try
	{
	  $newEntry = $yt->insertEntry($myVideoEntry, $uploadUrl, 'Zend_Gdata_YouTube_VideoEntry');
	}
	catch (Zend_Gdata_App_HttpException $httpException)
	{
	  echo $httpException->getRawResponseBody();
	}
	catch (Zend_Gdata_App_Exception $e)
	{
	  echo $e->getMessage();
	}
}
