<h1 align="center"> sinmore </h1>

<p align="center"> Company of sinmore quickly starts the project SDK.</p>

## Introduction

1. 引用了无数个overtrue大神的包,感谢大神
2. 代码质量极其低下,还请各位大神多指导,批评

## Installing

```shell
$ composer require mquery/sinmore:dev-master -vvv
```

## Usage

$ php artisan vendor:publish --provider="Mquery\Sinmore\GenerateServiceProvider"

配置好.env文件

$ php artisan migrate

$ composer dump-autoload

$ php artisan db:seed

需要富文本的话

$ php artisan vendor:publish --provider='Overtrue\LaravelUEditor\UEditorServiceProvider'

config\filesystems.php文件添加

```php
'ueditor' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => '/storage',
            'visibility' => 'public',
        ],
```
config\ueditor.php文件修改

```php
'disk' => 'ueditor',
```

## License

MIT
