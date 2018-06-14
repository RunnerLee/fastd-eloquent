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
        $this->registerEloquent($container);

        $this->registerMiddleware($container);
    }

    /**
     * @param Container $container
     */
    protected function registerEloquent(Container $container)
    {
        $manager = new Manager();

        $this->registerConnections($manager, $container);

        $manager->bootEloquent();

        $this->registerPageAndPathResolver($container);

        $container->add('eloquent', $manager);
    }

    /**
     * @param Container $container
     */
    protected function registerMiddleware(Container $container)
    {
        $container->get('dispatcher')->after(new EloquentMiddleware());
    }

    /**
     * @param Manager   $manager
     * @param Container $container
     */
    protected function registerConnections(Manager $manager, Container $container)
    {
        $manager->getDatabaseManager()->setDefaultConnection('default');

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

    /**
     * @param Container $container
     */
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
