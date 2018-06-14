# FastD Eloquent

[illuminate/database](https://github.com/illuminate/database) In FastD.

## Features

- 支持 Eloquent ORM
- 支持 Pagination
- 中间件捕获 NotFoundException

## Installation

```
composer require runner/fastd-eloquent
```

## Usage

配置服务提供者

```
\Runner\FastdEloquent\EloquentServiceProvider::class
```

## Event and Observer

如果需要用到模型事件或 Observer, 需要注册服务提供者

```
\Runner\FastdEloquent\EventServiceProvider::class
```

并增加配置 `event.php`

```php
<?php
return [
    'listen' => [
        CustomEvent::class => [
            CustomListener::class,    
        ],
    ],
    'observer' => [
        CustomModel::class => CustomObserver::class,    
    ],
];
```

## Document
- [Database](https://laravel.com/docs/5.6/database)
- [Eloquent](https://laravel.com/docs/5.6/eloquent)
