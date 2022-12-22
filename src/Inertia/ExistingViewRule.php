<?php

namespace LifeSpikes\PhpBeam\Inertia;

use PhpParser\Node;
use PHPStan\Rules\Rule;
use PhpParser\Node\Expr;
use PHPStan\Analyser\Scope;
use PhpParser\Node\Attribute;
use LifeSpikes\PhpBeam\Inertia\ViewAttribute\Inertia;

/**
 * @implements Rule<Attribute>
 */
class ExistingViewRule implements Rule
{
    public const PAGES_DIR_ENV = 'INERTIA_PAGES_DIRECTORY';

    public function getNodeType(): string
    {
        return Attribute::class;
    }

    /**
     * @param Attribute $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        /**
         * @var Expr[] $args
         */
        $args = $node->args;

        if (Inertia::class !== implode('\\', $node->name->parts)) {
            return [];
        }

        if (!is_string($path = getenv(self::PAGES_DIR_ENV)) || !file_exists($fullPath = base_path($path))) {
            return ['The '.self::PAGES_DIR_ENV.' environment variable is not an existing directory'];
        }

        if (Node\Scalar\String_::class === get_class($args[0])) {
            return ['Invalid arguments for Inertia attribute'];
        }

        if (($arg = $node->args[0])->name?->name === 'view') {
            /** @var Node\Scalar\String_ $value */
            $value = $arg->value;

            /** @var string|null $rawValue */
            $rawValue = $value->value;

            if (!is_null($rawValue) && ($target = $this->viewExists($rawValue, $fullPath)) !== true) {
                return ["No such Inertia page view $target"];
            }
        }

        return [];
    }

    private function viewExists(string $view, string $pagesDirectory): bool|string
    {
        return file_exists($str = "$pagesDirectory/$view.tsx") ? true : $str;
    }
}
