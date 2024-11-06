
# PHP-Enum-Spy
A PHP CLI tool for extracting Enum definitions.
Outputs Enum definitions to CSV and JSON files for easy access and analysis.

## Example

This tool can output JSON files like the following.

```json:ExampleEnum.json
{
    "enums": {
        "YTsuzaki\\PhpEnumSpyExample\\ExampleEnum": {
            "className": "ExampleEnum",
            "namespace": "YTsuzaki\\PhpEnumSpyExample",
            "fully_qualified_class_name": "YTsuzaki\\PhpEnumSpyExample\\ExampleEnum",
            "filePath": "/Users/y-tsuzaki/work/php-enum-spy-example/src/ExampleEnum.php",
            "cases": {
                "IN_PROGRESS": {
                    "name": "IN_PROGRESS",
                    "value": "in_progress",
                    "displayName": "進行中"
                },
                "PUNIRU": {
                    "name": "PUNIRU",
                    "value": "puniru_is_cute_slime",
                    "displayName": "ぷにるはかわいいスライム"
                }
            }
        }
    }
}
```

CSV files are output in the following format.

```csv:ExampleEnum.csv
class_name,file_path,case,value,displayName
"YTsuzaki\PhpEnumSpyExample\ExampleEnum",/Users/y-tsuzaki/work/php-enum-spy-example/src/ExampleEnum.php,IN_PROGRESS,in_progress,進行中
"YTsuzaki\PhpEnumSpyExample\ExampleEnum",/Users/y-tsuzaki/work/php-enum-spy-example/src/ExampleEnum.php,PUNIRU,puniru_is_cute_slime,ぷにるはかわいいスライム
```

The Enum class used in the sample is as follows.

```php:ExampleEnum.php
<?php

declare(strict_types=1);

namespace YTsuzaki\PhpEnumSpyExample;

enum ExampleEnum: string
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    case PUNIRU = "puniru_is_cute_slime";

    public function toDisplay(): string
    {
        return match ($this) {
            self::TODO => '未完了',
            self::IN_PROGRESS => '進行中',
            self::DONE => '完了',
            self::PUNIRU => 'ぷにるはかわいいスライム',
        };
    }
}
```

Please refer to the following Example repository for details.
https://github.com/y-tsuzaki/php-enum-spy-example

## Installation

```bash
composer require --dev y-tsuzaki/php-enum-spy
```

## Configuration

Create a configuration file `php-enum-spy.config.php` in the root directory of your project.


```php:php-enum-spy.config.php
<?php

$config = [
  "dirs" => [
    // Add the directory where the Enum class file is located
    "src",
  ],
  "convert_functions" => [],
];

return $config;
```

### Custom conversion function

If you want to read the method in Enum and convert the value, add a custom conversion function as follows.

```php:php-enum-spy.config.php
<?php

$config = [
  "dirs" => [
    // Add the directory where the Enum class file is located
    "src",
  ],
  "convert_functions" => [
    　// Add your custom convert function if needed
      "your_custom_function" => function (UnitEnum $enum) {
          if (method_exists($enum, 'toJapanese')) {
              return $enum->toJapanese();
          }
          return null;
      },
  ],
];

return $config;
```

## Usage

Create a configuration file (php-enum-spy.config.php) based on the above description.
```bash
vi php-enum-spy.config.php
```

Run the following command.
```bash
vendor/bin/php-enum-spy
```

CSV files and JSON files are output to ./output.
```
output  
├── enum_metadata.csv
└── enum_metadata.json
```
