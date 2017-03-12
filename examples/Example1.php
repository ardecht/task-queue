<?php

require '../vendor/autoload.php';

$storage = new \Ardecht\TaskQueue\Storage\MemoryStorage();
$queue = new \Ardecht\TaskQueue\Queue($storage);

function test()
{
    return 'from test';
}

$task = new \Ardecht\TaskQueue\Task(function () {
    return 'from anonymous';
});

$task2 = new \Ardecht\TaskQueue\Task('test');

$queue->enqueue($task);
$queue->enqueue($task2);

$queue->newTask(function ($v) {
    return 'from newTask function - $v: '.$v;
}, ['works'], 10);

while ($queue->isNotEmpty()) {
    echo $queue->dequeueAndRun() . "\n";
}
