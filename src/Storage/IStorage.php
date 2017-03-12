<?php

namespace Ardecht\TaskQueue\Storage;

use Ardecht\TaskQueue\Task;

interface IStorage
{
    /**
     * Add task to storage
     * @param Task $task
     * @return mixed
     */
    public function attach(Task $task);

    /**
     * Remove task from storage
     * @param Task $task
     * @return mixed
     */
    public function detach(Task $task);

    /**
     * Suspend task in storage
     * @param Task $task
     * @return mixed
     */
    public function suspend(Task $task);

    /**
     * Get tasks that can be performed
     * @return mixed
     */
    public function get();
}
