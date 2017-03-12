<?php

namespace Ardecht\TaskQueue\Test;

use Ardecht\TaskQueue\Queue;
use Ardecht\TaskQueue\Storage\MemoryStorage;
use Ardecht\TaskQueue\Task;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    public function testInitializeEmptyTaskClosure()
    {
        $task = new Task(function () {
        });

        $this->assertInstanceOf(Task::class, $task);
    }

    public function testResultFromClosureShouldBeTheSameFromTask()
    {
        $closure = function ($a, $b) {
            return $a + $b;
        };

        $task = new Task($closure, [5, 10]);

        $storage = new MemoryStorage();
        $queue = new Queue($storage);

        $queue->enqueue($task);

        $this->assertEquals($closure(5, 10), $queue->dequeueAndRun());
    }

    public function testGenerateHashForTask()
    {
        $task = new Task(function () {
        });

        $hash = md5(__FILE__.':38-'.serialize([])); // Checkout line number!

        $this->assertEquals($hash, $task->getHash());
    }
}
