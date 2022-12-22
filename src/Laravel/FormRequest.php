<?php

namespace LifeSpikes\PhpBeam\Laravel;

use Closure;
use Webmozart\Assert\Assert;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Validation\Rule;

class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /**
     * @return array<string, (string|Closure|Rule)[]>
     */
    public function rules(): array
    {
        return [];
    }

    public function uploadedFile(string $key): UploadedFile
    {
        Assert::isInstanceOf($file = $this->file($key), UploadedFile::class);

        return $file;
    }

    /**
     * @return string[]
     */
    public function strings(string ...$fields): array
    {
        $buffer = [];

        foreach ($fields as $field) {
            $buffer[$field] = $this->string($field);
        }

        return $buffer;
    }
}
