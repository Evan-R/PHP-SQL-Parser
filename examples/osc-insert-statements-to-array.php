<?php

/*
 * use to quickly convert osc insert statements into array
 * 	- helpful when refactoring old osc code
 */

namespace PHPSQLParser;
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use 
	Exception
	, ob_start
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
			// skip empty rows
			if( ! $input ){
				continue;
			}
			
			$this->output[] = $this->parser->parse($input);
		}
		
		return $this;
	}
	
	public function output()
	{
		return $this->output;
	}
	
	public function outputCols()
	{
		$a = [];
		foreach( $this->output as $output ){
			$fv = [];
			$fields = $this->parseFields($output['INSERT']);
			$values = $this->parseValues($output['VALUES']);
			
			foreach( $fields as $k => $field ){
				$fv[$field] = $values[$k];
			}
			
			$a[] = $fv;
		}
		
		return $a;
	}
	
	protected function parseFields( Array $a )
	{
		foreach( $a as $k ){
			if( $k['expr_type'] !== 'column-list' ){
				continue;
			}
			
			$r = [];
			foreach( $k['sub_tree'] as $branch ){
				$r[] = $branch['base_expr'];
			}
			
			return $r;
		}
		
		throw new Exception("could not parse fields");
	}
	
	protected function parseValues( Array $a )
	{
		foreach( $a as $k ){
			if( $k['expr_type'] !== 'record' ){
				continue;
			}
			
			$r = [];
			foreach( $k['data'] as $val ){
				$r[] = $val['base_expr'];
			}
			
			return $r;
		}
		
		throw new Exception("could not parse values");
	}
}

ob_start();
require( __DIR__ . '/osc-insert-statements-to-array/input.php' );
$output = ob_get_clean();

var_export(
	(new OscInsertConverter($output))
		->convert()
		->outputCols()
);


