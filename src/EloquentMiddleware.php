<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2018-01
 */

namespace Runner\FastdEloquent;

use FastD\Http\Response;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Exception;

class EloquentMiddleware extends Middleware
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $next
     *
     * @return Response|\Psr\Http\Message\ResponseInterface
     *
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        try {
            return $next->process($request);
        } catch (ModelNotFoundException $exception) {
            return json(
                [
                    'code' => Response::HTTP_NOT_FOUND,
                    'msg' => $exception->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            throw $e;
        }
    }
}
