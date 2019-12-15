<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class School extends MX_Controller {
	
	public function __construct() {
        parent::__construct();
    }
	public function index()
	{
		echo "Thsi is message ";
	}
	function helloworld()
	{
		echo "Hello World";
	}
}
