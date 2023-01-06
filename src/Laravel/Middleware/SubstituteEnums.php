<?php

namespace Henzeb\Enumhancer\Laravel\Middleware;

use BackedEnum;
use Henzeb\Enumhancer\Helpers\EnumDefaults;
use Henzeb\Enumhancer\Helpers\EnumGetters;
use Henzeb\Enumhancer\Helpers\EnumImplements;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use ReflectionEnum;
use ReflectionParameter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UnitEnum;

class SubstituteEnums
{
    public function handle(Request $request, \Closure $next): mixed
    {
        foreach ($this->getParameters($request->route()) as $key => $parameter) {
            if ($request->route($key) === null) {
                $this->setDefaultIfAvailable($request, $key, $parameter);
                continue;
            }

            $this->processParameter($parameter, $request, $key);
        }

        return $next($request);
    }

    protected function processParameter(ReflectionEnum $parameter, Request $request, string $key): void
    {
        /**
         * @var string $givenValue
         */
        $givenValue = $request->route($key);

        $value = EnumGetters::tryGet($parameter->getName(), $givenValue, useDefault: false);

        if (!$value) {
            throw new NotFoundHttpException();
        }

        /**
         * Laravel's middleware SubstituteBindings is still being processed. Returning the value allows
         * that middleware to complete the request properly.
         */
        if ($this->isStringBacked($parameter)) {
            /**
             * @var BackedEnum $value
             */
            $value = $value->value;
        }

        $request->route()?->setParameter($key, $value);
    }

    /**
     * @param Route|null $route
     * @return array
     */
    private function getParameters(Route|null $route): array
    {
        return collect(
            $route?->signatureParameters(['subClass' => UnitEnum::class])
        )->mapWithKeys(
            function (ReflectionParameter $parameter) {
                $backedEnumClass = rtrim((string)$parameter->getType(), '?');

                if (enum_exists($backedEnumClass)) {
                    return [$parameter->getName() => new ReflectionEnum($backedEnumClass)];
                }

                return [];
            }
        )->filter()->toArray();
    }

    private function isStringBacked(ReflectionEnum $parameter): bool
    {
        return ((string)$parameter->getBackingType()) === 'string';
    }

    private function hasDefault(ReflectionEnum $parameter): bool
    {
        return EnumImplements::defaults($parameter->getName());
    }

    private function setDefaultIfAvailable(
        Request $request,
        string $key,
        ReflectionEnum $parameter
    ): void {
        if ($this->hasDefault($parameter)) {
            $request->route()?->setParameter(
                $key,
                EnumDefaults::default($parameter->getName())
            );
        }
    }
}
