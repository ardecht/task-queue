<?php

namespace Ardecht\TaskQueue;

use Ardecht\TaskQueue\Storage\IStorage;
use Exception;
use SplPriorityQueue;

final class Queue
{
    /**
     * @var IStorage
     */
    private $storage;

    /**
     * @var SplPriorityQueue
     */
    private $tasks;

    /**
     * Queue constructor.
     * @param IStorage $storage
     */
    public function __construct(IStorage $storage)
    {
        $this->storage = $storage;
        $this->tasks = new SplPriorityQueue();
    }

    /**
     * Load tasks from storage
     * @param bool $suspend
     * @return $this
     * @throws Exception
     */
    public function load($suspend = false)
    {
        $tasks = $this->storage->get();

        foreach ($tasks as $task) {
            if ($suspend) {
                if (!$this->storage->suspend($task)) {
                    throw new Exception('Can not suspend task in storage!');
                }
            }

            $this->tasks->insert($task, $task->getPriority());
        }

        return $this;
    }

    /**
     * Add task to queue
     * @param Task $task
     * @return $this
     * @throws Exception
     */
    public function enqueue(Task $task)
    {
        if (!$this->storage->attach($task)) {
            throw new Exception('Can not attach task to storage!');
        }

        $this->tasks->insert($task, $task->getPriority());

        return $this;
    }

    /**
     * Remove task from queue
     * @return mixed
     * @throws Exception
     */
    public function dequeue()
    {
        $task = $this->tasks->extract();

        if (!$this->storage->detach($task)) {
            throw new Exception('Can not detach task from storage!');
        }

        return $task;
    }

    /**
     * Count the number of tasks to be performed
     * @return int
     */
    public function count()
    {
        return $this->tasks->count();
    }

    /**
     * Check whether there are tasks to be performed
     * @return bool
     */
    public function isNotEmpty()
    {
        return $this->tasks->valid();
    }

    /**
     * Remove task from queue then run it
     * @return mixed
     * @throws Exception
     */
    public function dequeueAndRun()
    {
        $task = $this->tasks->extract();

        if (!$this->storage->suspend($task)) {
            throw new Exception('Can not suspend task in storage!');
        }

        $task->run();

        if (!$this->storage->detach($task)) {
            throw new Exception('Can not detach task from storage!');
        }

        return $task;
    }

    /**
     * Create task and add to queue
     * @param callable $callable
     * @param array $arguments
     * @param int $priority
     * @return $this
     */
    public function newTask(callable $callable, array $arguments = [], $priority = 0)
    {
        $task = new Task($callable, $arguments, $priority);
        $this->enqueue($task);

        return $this;
    }
}
