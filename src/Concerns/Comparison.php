<?php

namespace Henzeb\Enumhancer\Concerns;

use BackedEnum;

trait Comparison
{
    /**
     * @mixin BackedEnum
     */
    final public function equals(self|string ...$equals): bool
    {
        foreach ($equals as $equal) {

            if ($this->name === $equal) {
                return true;
            }

            if (property_exists($this, 'value') && $this->value === $equal) {
                return true;
            }

            if (property_exists($equal, 'name') && $this->name === $equal->name) {
                return true;
            }
        }
        return false;
    }
}
