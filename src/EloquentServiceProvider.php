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
use Illuminate\Pagination\Paginator;

class EloquentServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     *
     * @return mixed
     */
    public function register(Container $container)
    {
        $manager = new Manager();

        $manager->getDatabaseManager()->setDefaultConnection('default');

        $this->registerConnections($manager, $container);

        $manager->bootEloquent();

        $this->registerPageAndPathResolver($container);

        $container->add('eloquent', $manager);
    }

    /**
     * @param Manager $manager
     */
    protected function registerConnections(Manager $manager, Container $container)
    {
        foreach ($container->get('config')->get('database', []) as $name => $config) {
            $manager->addConnection(
                [
                    'driver' => 'mysql',
                    'host' => $config['host'],
                    'port' => $config['port'],
                    'database' => $config['name'],
                    'username' => $config['user'],
                    'password' => $config['pass'],
                    'charset' => $config['charset'],
                ],
                $name
            );
        }
    }

    protected function registerPageAndPathResolver(Container $container)
    {
        Paginator::currentPageResolver(function ($pageName) use ($container) {
            return $container->get('request')->getParam($pageName, 1);
        });
        Paginator::currentPathResolver(function () use ($container) {
            return $container->get('request')->getUri();
        });
    }
}
