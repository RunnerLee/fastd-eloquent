<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2019-11
 */

namespace Runner\FastdEloquent;

use Closure;
use Illuminate\Database\MySqlConnection as Connection;
use Illuminate\Database\QueryException;
use PDOException;

class MysqlConnection extends Connection
{
    protected $lastUseTime = 0;

    /**
     * {@inheritdoc}
     */
    public function __construct($pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);

        $this->lastUseTime = microtime(true);
    }

    /**
     * {@inheritdoc}
     */
    public function logQuery($query, $bindings, $time = null)
    {
        $this->lastUseTime = microtime(true);

        parent::logQuery($query, $bindings, $time);
    }

    /**
     * {@inheritdoc}
     */
    protected function tryAgainIfCausedByLostConnection(QueryException $e, $query, $bindings, Closure $callback)
    {
        try {
            return parent::tryAgainIfCausedByLostConnection(
                $e,
                $query,
                $bindings,
                $callback
            );
        } catch (PDOException $exception) {
            if ($this->causedByBrokenPipe($exception)) {
                $this->reconnect();

                return $this->runQueryCallback($query, $bindings, $callback);
            }

            throw $exception;
        }
    }

    /**
     * @return bool
     */
    protected function causedByBrokenPipe(PDOException $exception)
    {
        if (false === strpos($exception->getMessage(), 'errno=32 Broken pipe')) {
            return false;
        }

        $command = sprintf('lsof -np %s | grep TCP', $pid = posix_getpid());

        exec($command, $output);

        logger()->error('bingo', [
            'socket'        => $output,
            'pid'           => $pid,
            'last_use_time' => $this->lastUseTime,
        ]);

        return true;
    }
}
