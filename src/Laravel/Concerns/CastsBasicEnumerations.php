<?php

namespace Henzeb\Enumhancer\Laravel\Concerns;

use UnitEnum;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Henzeb\Enumhancer\Helpers\EnumValue;
use Henzeb\Enumhancer\Helpers\EnumMakers;

/**
 * @mixin Model
 * @property bool $keepEnumCase Change public to private/protected if needed.
 */
trait CastsBasicEnumerations
{
    protected function getEnumCastableAttributeValue($key, $value)
    {
        if (is_null($value)) {
            return null;
        }

        $castType = $this->getCasts()[$key];

        /**
         * @TODO: remove when fixed: https://github.com/laravel/framework/issues/42658
         */
        if ($this->shouldUseWorkaround($castType)) {
            return $this->enumToArrayWorkaround($value);
        }

        if ($value instanceof $castType) {
            return $value;
        }

        return EnumMakers::make($castType, $value);
    }

    protected function setEnumCastableAttribute($key, $value)
    {
        $enumClass = $this->getCasts()[$key];

        $keepEnumCase = property_exists($this, 'keepEnumCase') ? $this->keepEnumCase : true;

        if (!isset($value)) {
            $this->attributes[$key] = null;
        } elseif ($value instanceof $enumClass) {
            $this->attributes[$key] = EnumValue::value($value, $keepEnumCase);
        } else {

            if ($value instanceof UnitEnum) {
                $value = EnumValue::value($value);
            }

            $this->attributes[$key] = EnumValue::value(
                EnumMakers::make($enumClass, $value),
                $keepEnumCase
            );
        }
    }

    /**
     * @TODO: remove when fixed: https://github.com/laravel/framework/issues/42658
     * @param $value
     * @return object
     */
    private function enumToArrayWorkaround(string|int $value): object
    {
        return new class($value) {
            public function __construct(public readonly string|int $value)
            {
            }
        };
    }

    /**
     * @TODO: remove when fixed: https://github.com/laravel/framework/issues/42658
     * @param string $enumClass
     * @return bool
     */
    private function shouldUseWorkaround(string $enumClass): bool
    {
        return (!is_subclass_of($enumClass, BackedEnum::class, true))
            && 'toArray' === (debug_backtrace(2)[5]['function'] ?? null);
    }
}
