<?php

declare(strict_types=1);

namespace examples\dir1;

enum MyIntegerEnum: int
{
    case MY_CASE_A = 1;
    case MY_CASE_B = 2;
    case MY_CASE_C = 1234567890;

}