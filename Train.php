<?php

namespace App;

class Train implements Visit
{
	public function go()
	{
		echo 'Train to Tibet';
	}
}