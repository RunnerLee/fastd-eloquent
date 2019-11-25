<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2018-04
 */

namespace Runner\FastdEloquent;

use Exception;
use FastD\Http\JsonResponse;
use FastD\Http\Response;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException as IlluminateModelNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EloquentMiddleware extends Middleware
{
    /**
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        try {
            return $next->process($request);
        } catch (IlluminateModelNotFoundException $exception) {
            $response = call_user_func(config()->get('exception.response'), new ModelNotFoundException($exception));

            if (is_object($response) && $response instanceof Response) {
                return $response;
            }

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }
    }
}
