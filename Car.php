<?php

namespace App;

class Car implements Visit
{
	public function go()
	{
		echo 'Drive to Tibet';
	}
}