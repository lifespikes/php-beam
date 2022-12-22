<?php

namespace LifeSpikes\PhpBeam\Inertia\ViewAttribute;

use Closure;
use ReflectionClass;
use ReflectionMethod;
use ReflectionFunction;
use ReflectionAttribute;
use ReflectionException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Safe\Exceptions\JsonException;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class AttributeMiddleware
{
    /**
     * @throws ReflectionException|JsonException
     */
    public function handle(Request $request, Closure $next): Responsable|Response
    {
        /* @todo: fix spaghetti */

        /** @var Response $response */
        $response = $next($request);

        $originalRes = $this->getOriginalResponse($response);
        $attribute = $this->getInertiaAttribute($request);

        if (
            (!$request->expectsJson() || $this->expectsInertia($request)) &&
            (!is_null($originalRes) && !is_null($attribute))
        ) {
            if (!is_null($attribute->redirect)) {
                return redirect($attribute->getRedirectUri($originalRes));
            }

            if (!is_null($view = $attribute->view)) {
                return \Inertia\Inertia::render($view, $originalRes);
            }
        }

        /* If a view wasn't provided, and the request is an Inertia request, just take the user back */
        if (
            $this->expectsInertia($request) &&
            (false === $response->getContent() || '' === $response->getContent())
        ) {
            return back();
        }

        return $response;
    }

    private function expectsInertia(Request $request): bool
    {
        return (bool) $request->header('X-Inertia');
    }

    /**
     * @throws ReflectionException
     */
    private function getInertiaAttribute(Request $request): Inertia|null
    {
        if (is_null($route = $request->route())) {
            return null;
        }

        /** @var array{uses: Closure|string} $action */
        $action = $route->getAction();
        $uses = $action['uses'];

        return $this->findInertiaAttribute(
            $uses instanceof Closure
            ? (new ReflectionFunction($uses))->getAttributes()
            : $this->getActionReflection($uses)->getAttributes()
        );
    }

    /**
     * @throws ReflectionException
     */
    private function getActionReflection(string $uses): ReflectionMethod
    {
        [$controller, $method] = explode('@', $uses);

        return (new ReflectionClass($controller))->getMethod($method);
    }

    /**
     * @param ReflectionAttribute<object>[] $attributes
     */
    private function findInertiaAttribute(array $attributes): ?Inertia
    {
        $attribute = collect($attributes)
            ->first(fn (ReflectionAttribute $attribute) => Inertia::class === $attribute->getName());

        if (!is_null($attribute)) {
            assert(($instance = $attribute->newInstance()) instanceof Inertia);

            return $instance;
        }

        return null;
    }

    /**
     * @param Response|JsonResponse $response
     *
     * @return array|null
     * @throws JsonException
     * @throws JsonException
     */
    private function getOriginalResponse(mixed $response): ?array
    {
        if (
            $response instanceof JsonResponse &&
            is_array($json = \Safe\json_decode($response->content(), true))
        ) {
            return $json;
        }

        return null;
    }
}
