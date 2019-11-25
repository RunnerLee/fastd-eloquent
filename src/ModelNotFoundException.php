<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2018-04
 */

namespace Runner\FastdEloquent;

use FastD\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException as IlluminateModelNotFoundException;
use RuntimeException;

class ModelNotFoundException extends RuntimeException
{
    public function __construct(IlluminateModelNotFoundException $exception)
    {
        parent::__construct($exception->getMessage(), Response::HTTP_NOT_FOUND);
    }
}
