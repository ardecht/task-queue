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
        $id = $task->getId();

        if (!$id) {
            $id = spl_object_hash($task);
            $task->setId($id);
        }

        if (!isset($this->tasks[$id])) {
            $this->tasks[$id] = $task;
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
        $id = $task->getId();

        if ($id && isset($this->tasks[$id])) {
            unset($this->tasks[$id]);
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
     * Unsuspend task in storage
     * @param Task $task
     * @return mixed
     */
    public function unsuspend(Task $task)
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
