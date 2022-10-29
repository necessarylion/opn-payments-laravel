<?php
namespace OpnPayments\Utils;

class StaticClassConverter {
    private $className;

    public function __construct($class) {
        $this->className = $class;
    }

    public function __call($method, $arguments) {
        $result = call_user_func([$this->className, $method], ...$arguments);
        return json_decode(json_encode($result->toArray()));
    }
}
