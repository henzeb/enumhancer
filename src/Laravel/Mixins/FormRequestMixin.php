<?php

namespace Henzeb\Enumhancer\Laravel\Mixins;

use Closure;
use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Helpers\EnumGetters;
use Henzeb\Enumhancer\Helpers\Mappers\EnumMapper;
use Illuminate\Foundation\Http\FormRequest;
use UnitEnum;

class FormRequestMixin
{
    public function asEnum(): Closure
    {
        return function (
            string $key,
            string $class,
            Mapper|string|array|null ...$mappers
        ): ?UnitEnum {

            /**
             * @var FormRequest $this
             */
            return EnumGetters::tryGet(
                $class,
                EnumMapper::map($class, $this->get($key), ...$mappers)
            );
        };
    }
}
