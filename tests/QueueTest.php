<?php

namespace Ardecht\TaskQueue\Test;

use Ardecht\TaskQueue\Queue;
use Ardecht\TaskQueue\Storage\MemoryStorage;
use Ardecht\TaskQueue\Task;

class QueueTest extends \PHPUnit_Framework_TestCase
{
    public function testInitializeEmptyQueue()
    {
        $storage = new MemoryStorage();
        $queue = new Queue($storage);

        $this->assertEquals(0, $queue->count());
    }

    public function testLoadQueueFromStorage()
    {
        $storage = new MemoryStorage();

        $task = new Task(function () {
        });
        $task2 = new Task(function () {
        });
        $task3 = new Task(function () {
        });

        $storage->attach($task);
        $storage->attach($task2);
        $storage->attach($task3);

        $queue = new Queue($storage);
        $queue->load();

        $this->assertEquals(3, $queue->count());
    }

    public function testCanNotAddTaskToQueueWhichIsInQueue()
    {
        $this->expectException('Exception');

        $storage = new MemoryStorage();
        $queue = new Queue($storage);

        $task = new Task(function () {
        });

        $queue->enqueue($task);
        $queue->enqueue($task);
    }

    public function testCanNotInitializeQueueWithoutStorage()
    {
        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
            $this->expectException('TypeError');
            $queue = new Queue(null);
        }
    }
}
