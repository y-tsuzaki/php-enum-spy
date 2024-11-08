<?php

namespace YTsuzaki\PhpEnumSpy\Metadata;

class EnumMetadata
{
    /**
     * @param  string  $className
     * @param  string  $filepath
     * @param  array<CaseMetadata>  $cases
     */
    public function __construct(
        public readonly string $className,
        public readonly string $filepath,
        public readonly array $cases
    )
    {

    }

    public function getCaseNames(): array
    {
        return array_map(fn(CaseMetadata $case) => $case->name, $this->cases);
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getCaseValue(string $caseName): string|int|null
    {
        foreach ($this->cases as $case) {
            if ($case->name === $caseName) {
                return $case->value;
            }
        }
        return null;
    }

    public function getConvertedValue(string $caseName, string $converterName)
    {
        foreach ($this->cases as $case) {
            if ($case->name === $caseName) {
                if (!isset($case->convertedValues[$converterName])) {
                    throw new \Exception("No converted value found for $converterName");
                }

                return $case->convertedValues[$converterName] ?? null;
            }
        }
        return null;
    }

    public function toArrayForJson(): array
    {
        $shortClassName = substr($this->className, strrpos($this->className, '\\') + 1);
        $nameSpace = substr($this->className, 0, strrpos($this->className, '\\'));
        $result = [
            'className' => $shortClassName,
            'namespace' => $nameSpace,
            'fully_qualified_class_name' => $this->className,
            'filePath' => $this->filepath,
            'cases' => []
        ];

        foreach ($this->cases as $case) {
            $result["cases"][$case->name] = [
                "name" => $case->name,
                "value" => $case->value,
                ...$case->convertedValues
            ];
        }
        return $result;
    }
}
