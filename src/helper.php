<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-12
 */
use FastD\Http\JsonResponse;
use FastD\Http\Response;
use Illuminate\Contracts\Support\Arrayable;

if (!function_exists('eloquent_db')) {
    function eloquent_db($name = null)
    {
        return app()->get('eloquent')->getConnection($name);
    }
}

if (!function_exists('eloquent')) {
    function eloquent(Arrayable $resource, $statusCode = Response::HTTP_OK)
    {
        return new JsonResponse($resource->toArray(), $statusCode);
    }
}
