<?php

namespace Ardecht\TaskQueue\Storage;

use Ardecht\TaskQueue\Task;

class MemoryStorage implements IStorage
{
    /**
     * @var array
     */
    private $tasks = [];

    /**
     * Add task to storage
     * @param Task $task
     * @return mixed
     */
    public function attach(Task $task)
    {
        $hash = spl_object_hash($task);

        if (!isset($this->tasks[$hash])) {
            $this->tasks[$hash] = $task;
            return true;
        }

        return false;
    }

    /**
     * Remove task from storage
     * @param Task $task
     * @return mixed
     */
    public function detach(Task $task)
    {
        $hash = spl_object_hash($task);

        if (isset($this->tasks[$hash])) {
            unset($this->tasks[$hash]);
            return true;
        }

        return false;
    }

    /**
     * Suspend task in storage
     * @param Task $task
     * @return mixed
     */
    public function suspend(Task $task)
    {
        // Not used

        return true;
    }

    /**
     * Get tasks that can be performed
     * @return mixed
     */
    public function get()
    {
        return $this->tasks;
    }
}
