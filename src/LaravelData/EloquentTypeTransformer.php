<?php

namespace LifeSpikes\PhpBeam\LaravelData;

use ReflectionClass;
use ReflectionException;
use Illuminate\Database\Eloquent\Model;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Transformers\Transformer;

/**
 * @phpstan-type ColumnDefinition array{name: string, type: string}
 */
class EloquentTypeTransformer implements Transformer
{
    /**
     * @var ReflectionClass<object>
     */
    private ReflectionClass $reflection;

    /**
     * @throws ReflectionException
     *
     * @phpstan-ignore-next-line
     */
    public function transform(ReflectionClass $class, string $name): ?TransformedType
    {
        $this->reflection = $class;

        return $class->isSubclassOf(Model::class) ? TransformedType::create(
            $class,
            $name,
            $this->generateScript()
        ) : null;
    }

    /**
     * @throws ReflectionException
     */
    private function generateScript(): string
    {
        $types = implode(';'.PHP_EOL, array_map(function (array $column) {
            return '  '.$column['name'].': '.$column['type'];
        }, $this->typeColumns()));

        return '{'.PHP_EOL.$types.PHP_EOL.'}';
    }

    /**
     * @return ColumnDefinition[]
     *
     * @throws ReflectionException
     */
    private function typeColumns(): array
    {
        $builder = ($instance = $this->model())->getConnection()->getSchemaBuilder();
        $columns = $builder->getColumnListing($table = $instance->getTable());

        return array_map(function ($column) use ($builder, $table) {
            return [
                'name' => $column,
                'type' => match ($builder->getColumnType($table, $column)) {
                    'integer', 'float', 'double' => 'number',
                    'boolean' => 'boolean',
                    default => 'string',
                },
            ];
        }, $columns);
    }

    /**
     * @throws ReflectionException
     */
    private function model(): Model
    {
        return assert_instance($this->reflection->newInstance(), Model::class);
    }
}
