<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-12
 */
if (!function_exists('eloquent_db')) {
    function eloquent_db($name = null)
    {
        return app()->get('eloquent')->getConnection($name);
    }
}
