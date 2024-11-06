<?php

declare(strict_types=1);

namespace examples\dir1;

enum MyEnumA: string
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
    public function toJapanese (): string {
        return match ($this) {
            self::MY_CASE_A => '日本語a',
            self::MY_CASE_B => '日本語b',
            self::MY_CASE_C => '日本語c',
        };
    }


}