<?php

namespace Henzeb\Enumhancer\Laravel\Rules;

use Henzeb\Enumhancer\Contracts\Mapper;
use Henzeb\Enumhancer\Helpers\EnumCheck;
use Henzeb\Enumhancer\Helpers\EnumGetters;
use Henzeb\Enumhancer\Helpers\Mappers\EnumMapper;
use Illuminate\Contracts\Validation\Rule;

class IsEnum implements Rule
{
    private mixed $value = null;


    /**
     * @var array|array<string|int,string|Mapper|int>|Mapper[]|null[]|string[]
     */
    private array $mappers;

    /**
     * @param string $type
     * @param array|array<string|int,string|Mapper|int>|Mapper[]|null[]|string[] $mappers
     */
    public function __construct(private readonly string $type, Mapper|string|array|null ...$mappers)
    {
        EnumCheck::check($type);
        $this->mappers = $mappers;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function passes($attribute, $value): bool
    {
        $this->value = EnumMapper::map($this->type, $value, ...$this->mappers);

        return (bool)EnumGetters::tryGet($this->type, $this->value, useDefault: false);
    }

    /**
     * @return string|string[]
     */
    public function message(): string|array
    {
        $message = trans(
            'validation.enumhancer.enum',
            [
                'enum' => $this->type,
                'value' => $this->value,
            ]
        );

        return $message === 'validation.enumhancer.enum'
            ? ['The selected :attribute is invalid.']
            : $message;
    }
}
