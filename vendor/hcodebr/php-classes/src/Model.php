<?php

namespace Hcode;

class Model{

	private $values = [];

	public function __call($name, $args)
	{

		$method = substr($name,0,3);
		$fildName = substr($name,3,strlen($name));

		switch($method)
		{

			case "get":
				return $this->values[$fildName];
			break;
			case "set":
				$this->values[$fildName] = $args[0];
			break;
		
		}

	}

	public function setData($data = array())
	{
		foreach($data as $key => $value){

			$this->{"set".$key}($value);// {} serve para criar o metodo set dinamicamente 
		}
	}

	public function getValues()
	{
		echo "aqui";
		return $this->values;
	}

}
?>