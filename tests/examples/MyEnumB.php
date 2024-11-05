<?php

declare(strict_types=1);

namespace examples;

enum MyEnumB: string
{
    case MY_CASE_A = 'my_case_a';
    case MY_CASE_B = 'my_case_b';

    case MY_CASE_C = 'my_case_c';

    public function someFunction () {
    }

    public function someConvertFunction (): string {
        return match ($this) {
            self::MY_CASE_A => 'converted_a',
            self::MY_CASE_B => 'converted_b',
            self::MY_CASE_C => 'converted_c',
        };
    }
}