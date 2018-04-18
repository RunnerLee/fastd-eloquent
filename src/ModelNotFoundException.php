<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2018-04
 */

namespace Runner\FastdEloquent;

use FastD\Http\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException as IlluminateModelNotFoundException;

class ModelNotFoundException extends HttpException
{
    public function __construct(IlluminateModelNotFoundException $exception)
    {
        parent::__construct($exception->getMessage(), 404);
    }
}
