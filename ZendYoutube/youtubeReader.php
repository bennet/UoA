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

//$videoFeed = $yt->getVideoFeed(Zend_Gdata_YouTube::VIDEO_URI);
$videoFeed = $yt->getuserUploads("bennetmundackattu");
printVideo($videoFeed);
function printVideo($videoFeed)
{
	$count = 1;
	foreach ($videoFeed as $videoEntry)
	{
	    echo "Entry # " . $count . "<br />";
	    printVideoEntry($videoEntry);
	    echo "<br /><br /><br />";
	    $count++;
	}
}

function printVideoEntry($videoEntry) 
{
  // the videoEntry object contains many helper functions
  // that access the underlying mediaGroup object
  echo 'Video: ' . $videoEntry->getVideoTitle() . "<br />";
  //echo 'Video ID: ' . $videoEntry->getVideoId() . "<br />";
  //echo 'Updated: ' . $videoEntry->getUpdated() . "<br />";
  echo 'Description: ' . $videoEntry->getVideoDescription() . "<br />";
  //echo 'Category: ' . $videoEntry->getVideoCategory() . "<br />";
  //echo 'Tags: ' . implode(", ", $videoEntry->getVideoTags()) . "<br />";
  echo 'Watch page: ' . $videoEntry->getVideoWatchPageUrl() . "<br />";
  echo 'Flash Player Url: ' . $videoEntry->getFlashPlayerUrl() . "<br />";
  echo 'Duration: ' . $videoEntry->getVideoDuration() . "<br />";
  //echo 'View count: ' . $videoEntry->getVideoViewCount() . "<br />";
  //echo 'Rating: ' . $videoEntry->getVideoRatingInfo() . "<br />";
  //echo 'Geo Location: ' . $videoEntry->getVideoGeoLocation() . "<br />";
  //echo 'Recorded on: ' . $videoEntry->getVideoRecorded() . "<br />";
  
  // see the paragraph above this function for more information on the 
  // 'mediaGroup' object. in the following code, we use the mediaGroup
  // object directly to retrieve its 'Mobile RSTP link' child
  /*foreach ($videoEntry->mediaGroup->content as $content) {
    if ($content->type === "video/3gpp") {
      echo 'Mobile RTSP link: ' . $content->url . "<br />";
    }
  }*/
  
  echo "Thumbnails:<br />";
  $videoThumbnails = $videoEntry->getVideoThumbnails();

  foreach($videoThumbnails as $videoThumbnail) {
    echo $videoThumbnail['time'] . ' - ' . "<img src='". $videoThumbnail['url'] . "' />";
    echo ' height=' . $videoThumbnail['height'];
    echo ' width=' . $videoThumbnail['width'] . "<br />";
  }
}
