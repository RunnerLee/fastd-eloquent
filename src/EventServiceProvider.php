<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2018-06
 */

namespace Runner\FastdEloquent;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Eloquent\Model;

class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     *
     * @return mixed
     */
    public function register(Container $container)
    {
        $dispatcher = new Dispatcher();

        $container->add('event', $dispatcher);

        $this->registerListen($dispatcher);

        Model::setEventDispatcher($dispatcher);

        $this->registerObserver();
    }

    protected function registerConfig()
    {
        $file = app()->getPath().'/config/event.php';
        config()->merge([
            'event' => file_exists($file) ? load($file) : [],
        ]);
    }

    protected function registerListen(Dispatcher $dispatcher)
    {
        foreach (config()->get('event.listen') as $event => $listeners) {
            foreach ($listeners as $listener) {
                $dispatcher->listen($event, $listener);
            }
        }
    }

    protected function registerObserver()
    {
        foreach (config()->get('event.observer') as $model => $observer) {
            $model::observe($observer);
        }
    }
}
