<?php

declare(strict_types=1);

namespace YTsuzaki\PhpEnumSpy\Metadata;

class CaseMetadata
{


    /**
     * @param  string  $name
     * @param  string|int  $value
     * @param  array<string, string|int>  $convertedValues
     */
    public function __construct(
        readonly string $name,
        readonly string|int $value,
        readonly array $convertedValues = []
    )
    {
    }
}