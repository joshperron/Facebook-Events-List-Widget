<?php
// make sure this api file is in your directory, if not get it here https://github.com/facebook/php-sdk/tree/master/src
require './facebook.php';

// Authenticate
$facebook = new Facebook(array(
	'appId' => 'put_your_app_id_here',
	'secret' => 'put_your_app_secret_here',
	'cookie' => true, // enable optional cookie support
));

// Attempt to fetch SpiritGallery's events page
// place access token for your fanpage after events?access_token=
// also place your fanpage that you would like to pull events from in $events=$facebook->api('/fanpage_here/
try{
	$events=$facebook->api('/fanpage_here/events?access_token=access_token_here');
}catch (FacebookApiException $e){
	error_log($e);
}

// Iterate through each event
foreach ($events["data"] as $event){
	// Get the start time of the event and convert it to a UNIX timestamp (hopefully)
	$startTime=strtotime($event["start_time"]);
 
		// If the time falls within a day, show the event details. If the event is after today, show it as well.
		// 60 (seconds/minute) * (60 minutes/hour) * 24 (hours/day) * 1 (day)
		if ((time()-$startTime)<=60*60*24*1 || $startTime>time()){
			try{
				// Fetch more details about the event
				$ev=$facebook->api('/'.$event["id"]);
				}catch (FacebookApiException $e){
					// We errored :(
					error_log($e);
					}
  
					// Show some HTMLsauce
					?>
					<div>
						<img src="https://graph.facebook.com/<?php echo $event["id"]; ?>/picture?type=small" align="left" />
						<b>Name:</b> <a href="http://www.facebook.com/event.php?eid=<?php echo $event['id']; ?>"><?php echo $event['name'];    ?></a><br>
						<b>Time:</b> <?php echo date("l jS \of F Y h:i:s A",strtotime($event["start_time"])); ?>&nbsp;-&nbsp;<?php echo date("l jS 
						\of F Y h:i:s A",strtotime($event["end_time"])); ?><br>
						<b>Location:</b> <?php echo $ev["location"]; ?><br>
						<b>Description:</b> <?php echo substr( $ev["description"] ,0,250);?><?php print("...");?><br>
						<br>
					</div>
				<?php
			}
		}
?>

