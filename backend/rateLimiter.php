<?php

	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	} 
	if (isset($_SESSION['LAST_CALL'])) {
		$last = $_SESSION['LAST_CALL'];
		$curr = date("Y-m-d h:i:s.u");

		//$sec =  abs(myDateToMs($last) - myDateToMs($curr));
		if (compareMilliseconds($last,$curr,250)) {
			//$data = 'Too many requests.';  // rate limit
			//header("Content-Type: text/html");
			die("Rate Limit Exceeded.");        
		}
	}
	$_SESSION['LAST_CALL'] = date('Y-m-d h:i:s.u');
	
	
function compareMilliseconds($date1,$date2,$compare_amount){

  if(strtotime($date1) == strtotime($date2)){

      list($throw,$milliseond1) = explode('.',$date1);
      list($throw,$milliseond2) = explode('.',$date2);

      return ( ($milliseond2 - $milliseond1) < $compare_amount);
  }

}
?>