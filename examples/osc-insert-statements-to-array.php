<?php

/*
 * use to quickly convert osc insert statements into array
 * 	- helpful when refactoring old osc code
 */

namespace PHPSQLParser;
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use 
	ob_start
	, ob_get_clean
	, var_export
;

class OscInsertConverter {
	protected 
		$input
		, $output = array()
	;
	
	public function __construct( $input )
	{
		$this->parser = new PHPSQLParser;
		$this->input = explode("\n", $input);
		
		return $this;
	}
	
	public function convert()
	{
		foreach( $this->input as $input ){
			$this->output[] = $this->parser->parse($input);
		}
		
		return $this;
	}
	
	public function output()
	{
		return $this->output;
	}
}

ob_start();
require( __DIR__ . '/osc-insert-statements-to-array/input.php' );
$output = ob_get_clean();

echo var_export(
	(new OscInsertConverter($output))
		->convert()
		->output()
);


