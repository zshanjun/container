<?php

namespace App;

class Leg implements Visit
{
    protected $person;

    /**
     * 测试容器能否解决多层依赖问题
     * Leg constructor.
     */
    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    public function go()
	{
		echo $this->person->name . ' Walk to Tibet';
	}
}