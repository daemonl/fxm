<?php

namespace Rebase\BigvBundle\Common;

class BasicFunctions 
{
	public static function GetSafeString($string)
	{
		return 	preg_replace('/[^a-zA-Z0-9\s]/',"", $string);
	}
}

?>