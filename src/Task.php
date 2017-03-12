<?php

namespace Ardecht\TaskQueue;

final class Task
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var int
     */
    private $priority;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var mixed
     */
    private $id = null;

    /**
     * @var mixed
     */
    private $result = null;

    /**
     * Task constructor.
     * @param callable $callable
     * @param array $arguments
     * @param int $priority
     */
    public function __construct(callable $callable, array $arguments = [], $priority = 0)
    {
        $this->callable = $callable;
        $this->arguments = $arguments;
        $this->priority = (int)$priority;
        $this->hash = $this->generateHash();
    }

    /**
     * Generate hash for task
     * @return string
     */
    private function generateHash()
    {
        $trace = array_reverse(debug_backtrace());

        foreach ($trace as $item) {
            if ($item['class'] == 'Ardecht\TaskQueue\Queue' || $item['class'] == 'Ardecht\TaskQueue\Task') {
                return md5($item['file'].':'.$item['line'].'-'.serialize($this->arguments));
            }
        }

        return '';
    }

    /**
     * Get callable
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * Get arguments
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Get priority
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Get task hash
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Run callable with arguments
     * @return mixed
     */
    public function run()
    {
        $this->result = call_user_func_array($this->callable, $this->arguments);
        return $this->result;
    }
}
