<?php

namespace Henzeb\Enumhancer\Laravel\Concerns;

use Henzeb\Enumhancer\Helpers\EnumImplements;

/**
 * @property array $castsIgnoreEnumState Change public to private/protected if needed.
 */
trait CastsStatefulEnumerations
{
    use CastsBasicEnumerations {
        setEnumCastableAttribute as private setEnumCastableAttributeAnyway;
    }

    protected function setEnumCastableAttribute($key, $value)
    {
        $currentAttribute = $this->getAttribute($key);

        if (!isset($value) || !$currentAttribute || $this->shouldNotCastByTransition($key)) {
            $this->setEnumCastableAttributeAnyway($key, $value);
            return;
        }

        $this->setEnumCastableAttributeAnyway(
            $key,
            $currentAttribute->transitionTo($value)
        );
    }

    private function shouldNotCastByTransition($key): bool
    {
        $cast = $this->getCasts()[$key];

        $ignore = property_exists($this, 'castsIgnoreEnumState') ? $this->castsIgnoreEnumState : [];

        return in_array($key, $ignore) || !EnumImplements::state($cast);
    }
}
