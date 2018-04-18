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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EloquentMiddleware extends Middleware
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $next
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        try {
            return $next->process($request);
        } catch (ModelNotFoundException $exception) {
            $response = call_user_func(config()->get('exception.response'), $exception);

            if (is_object($response) && $response instanceof Response) {
                return $response;
            }

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
