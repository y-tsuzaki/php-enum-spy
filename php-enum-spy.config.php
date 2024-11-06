<?php

$config = [
  "dirs" => [
      "tests/examples/dir1",
      "tests/examples/dir2",
  ],
  "convert_functions" => [
      "custom_convert_1" => function (UnitEnum $enum) {
          if (method_exists($enum, 'someConvertFunction')) {
              return $enum->someConvertFunction();
          }
          return null;
      },
      "custom_convert_2" => function (UnitEnum $enum) {
          if (method_exists($enum, 'toJapanese')) {
              return $enum->toJapanese();
          }
          return null;
      }
  ],
];


return $config;