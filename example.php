<?php
include(realpath('Grooveshark/Api.php'));

use Grooveshark\Api as Grooveshark;

$args = array(
	'key' => '[KEY]',
	'secret' => '[SECRET]',
);
$grooveshark = new Grooveshark($args);

//If we had to set the SESSIONID
//$grooveshark->sessionID = [SESSIONID];

var_dump($grooveshark->request('getPopularSongsToday'));
?>