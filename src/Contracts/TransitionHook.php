<?php

namespace Henzeb\Enumhancer\Contracts;

use UnitEnum;
use Henzeb\Enumhancer\Exceptions\SyntaxException;

abstract class TransitionHook
{
    final public function execute(UnitEnum $from, UnitEnum $transitionTo): void
    {
        $method = $this->getMethodName($from, $transitionTo);

        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    /**
     * @throws SyntaxException
     */
    final public function isAllowed(UnitEnum $from, UnitEnum $transitionTo): bool
    {
        $method = $this->getMethodName($from, $transitionTo, 'allows');

        if (method_exists($this, $method)) {
            $value = $this->$method();
            if (is_bool($value)) {
                return $value;
            }

            if (!is_null($value)) {
                throw new SyntaxException('true', $value);
            }
        }

        return true;
    }

    private function getMethodName(UnitEnum $from, UnitEnum $transitionTo, string $prefix = ''): string
    {
        return $prefix . $from->name . $transitionTo->name;
    }
}
