<?php


namespace App;


/**
 * Class Container
 * @package App
 */
class Container
{
    /**
     * @var array
     */
    protected $bindings = [];

    /**
     * 绑定接口和生成相应实例的回调函数
     * @param $abstract
     * @param null $concrete
     * @param bool $shared
     */
    public function bind($abstract, $concrete = null)
    {
        if ( ! $concrete instanceof \Closure) {
            $concrete = $this->getClosure($abstract, $concrete);
        }
        $this->bindings[$abstract] = compact('concrete');
    }

    /**
     * 默认生成实例的回调函数
     * @param $abstract
     * @param $concrete
     * @return \Closure
     */
    protected function getClosure($abstract, $concrete)
    {
        return function ($container) use ($abstract, $concrete)
        {
            $method = ($abstract == $concrete) ? 'build' : 'make';

            return $container->$method($concrete);
        };
    }

    /**
     * 生成实例对象，首先解决接口和要实例化类之间的依赖关系
     * @param $abstract
     * @return object
     */
    public function make($abstract)
    {
        $concrete = $this->getConcrete($abstract);
        if ($this->isBuildable($concrete, $abstract)) {
            $object = $this->build($concrete);
        } else {
            $object = $this->make($concrete);
        }

        return $object;
    }

    /**
     * 获取绑定的回调函数
     * @param $abstract
     * @return mixed
     */
    protected function getConcrete($abstract)
    {
        if ( ! isset($this->bindings[$abstract])) {
            return $abstract;
        }

        return $this->bindings[$abstract]['concrete'];
    }

    /**
     * @param $concrete
     * @param $abstract
     * @return bool
     */
    protected function isBuildable($concrete, $abstract)
    {
        return $concrete === $abstract || $concrete instanceof \Closure;
    }

    /**
     * 实例化对象
     * @param $concrete
     * @return object
     */
    public function build($concrete)
    {
        if ($concrete instanceof \Closure) {
            return $concrete($this);
        }
        $reflector = new \ReflectionClass($concrete);
        if ( ! $reflector->isInstantiable()) {
            echo $message = "Target [$concrete] is not instantiable.";
        }
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $concrete;
        }
        $dependencies = $constructor->getParameters();
        $instances = $this->getDependencies($dependencies);

        return $reflector->newInstanceArgs($instances);
    }

    /**
     * 解决通过反射机制实例化对象时的依赖
     * @param $parameters
     * @return array
     */
    protected function getDependencies($parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if (is_null($dependency)) {
                $dependencies[] = null;
            } else {
                $dependencies[] = $this->resolveClass($parameter);
            }
        }

        return (array) $dependencies;
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return object
     */
    protected function resolveClass(\ReflectionParameter $parameter)
    {
        return $this->make($parameter->getClass()->name);
    }

}