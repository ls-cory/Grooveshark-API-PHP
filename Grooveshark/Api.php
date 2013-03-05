<?php
namespace Grooveshark;

include(realpath('Grooveshark/Payload.php'));

class Api extends Payload {

	public function __construct($args = array()){
		parent::__construct($args);
	}

}