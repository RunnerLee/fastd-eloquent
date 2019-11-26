<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-12
 */

namespace Runner\FastdEloquent;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection;
use Illuminate\Pagination\Paginator;

class EloquentServiceProvider implements ServiceProviderInterface
{
    /**
     * @return mixed
     */
    public function register(Container $container)
    {
        $this->registerEloquent($container);

        $this->registerMiddleware($container);

        Connection::resolverFor('mysql', function (...$parameters) {
            return new MysqlConnection(...$parameters);
        });
    }

    protected function registerEloquent(Container $container)
    {
        $this->registerConnections($manager = new Manager());

        $manager->bootEloquent();

        $this->registerPageAndPathResolver();

        $container->add('eloquent', $manager);
    }

    protected function registerMiddleware(Container $container)
    {
        $container->get('dispatcher')->after(new EloquentMiddleware());
    }

    protected function registerConnections(Manager $manager)
    {
        $manager->getDatabaseManager()->setDefaultConnection('default');

        foreach (config()->get('database', []) as $name => $config) {
            $manager->addConnection(
                [
                    'driver'   => 'mysql',
                    'host'     => $config['host'],
                    'port'     => $config['port'],
                    'database' => $config['name'],
                    'username' => $config['user'],
                    'password' => $config['pass'],
                    'charset'  => $config['charset'],
                    'prefix'   => $config['prefix'] ?? '',
                ],
                $name
            );
        }
    }

    protected function registerPageAndPathResolver()
    {
        Paginator::currentPageResolver(function ($pageName) {
            return request()->getParam($pageName, 1);
        });
        Paginator::currentPathResolver(function () {
            return (string) request()->getUri();
        });
    }
}
