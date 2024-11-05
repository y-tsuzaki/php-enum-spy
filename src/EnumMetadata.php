<?php

namespace YTsuzaki\PhpEnumSpy;

class EnumMetadata
{
    public function __construct(
        public readonly string $enumClass,
        public readonly string $filepath,
        public readonly array $keyValues = [],
        public readonly array $convertedValues = [],
    )
    {

    }

}
