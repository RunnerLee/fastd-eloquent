<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2018-01
 */

namespace Runner\FastdEloquent;

use Exception;
use FastD\Http\Response;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class EloquentMiddleware extends Middleware
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $next
     *
     * @throws Exception
     *
     * @return Response|\Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        try {
            return $next->process($request);
        } catch (ModelNotFoundException $exception) {
            return json(
                [
                    'code' => Response::HTTP_NOT_FOUND,
                    'msg'  => $exception->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            throw $e;
        }
    }
}
