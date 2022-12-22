<?php

namespace LifeSpikes\PhpBeam\Inertia\ViewAttribute;

use Attribute;
use ArrayAccess;

#[Attribute]
class Inertia
{
    /**
     * @param string|null $view
     * @param array|null $redirect Redirect to route
     */
    public function __construct(
        public ?string $view = null,
        public ?array $redirect = null
    ) {
    }

    public function getRedirectUri(mixed $originalResponse): string
    {
        $route = $this->redirect;
        assert(2 === count($route ?? []));

        [$uri, $params] = $route;

        return route($uri, $this->processPlaceholders($originalResponse, $params));
    }

    /**
     * @param mixed $response
     * @param array $params
     *
     * @return array
     */
    private function processPlaceholders(mixed $response, array $params): array
    {
        return collect($params)->map(function (mixed $value) use ($response) {
            assert(is_string($value));
            assert(is_array($response) || $response instanceof ArrayAccess);

            \Safe\preg_match_all('/{(.*)}/Um', $value, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                [$placeholder, $key] = $match;
                $replaceValue = $response[$key];

                assert(!is_object($replaceValue));

                $value = str_replace($placeholder, (string) $replaceValue, $value);
            }

            return $value;
        })->toArray();
    }
}
