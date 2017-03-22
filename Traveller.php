<?php

namespace App;

class Traveller
{
	protected $trafficTool;

	public function __construct(Visit $tool)
	{
		$this->trafficTool = $tool;
	}

	public function visitTibet()
	{
		$this->trafficTool->go();
	}
}