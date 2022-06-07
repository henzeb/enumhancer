<?php

namespace Henzeb\Enumhancer\Laravel\Concerns;

use UnitEnum;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Henzeb\Enumhancer\Helpers\EnumValue;
use Henzeb\Enumhancer\Helpers\EnumMakers;
use function Henzeb\Enumhancer\Functions\b;

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

        if (!$value instanceof $castType) {
            $value = EnumMakers::make($castType, $value);
        }

        if ($this->shouldUseBasicEnumWorkaround($castType)) {

            $keepEnumCase = property_exists($this, 'keepEnumCase') ? $this->keepEnumCase : true;

            return b($value, $keepEnumCase);
        }

        return $value;
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

    private function shouldUseBasicEnumWorkaround(string $enumClass): bool
    {
        return (!is_subclass_of($enumClass, BackedEnum::class, true))
            && 'toArray' === (debug_backtrace(2)[5]['function'] ?? null);
    }
}
