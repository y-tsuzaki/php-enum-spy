<?php

$config = [
  "dirs" => [
    "tests/examples",
  ],
  "convert_functions" => [
      "myConvertFunction" => function (UnitEnum $enum) {
          if (method_exists($enum, 'someConvertFunction')) {
              return $enum->someConvertFunction();
          }
      },

      "myConvertFunction2" => function (UnitEnum $enum) {
          if (method_exists($enum, 'toJapanese')) {
              return $enum->toJapanese();
          }
      }
  ],
];


return $config;