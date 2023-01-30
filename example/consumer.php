<?php
require_once 'Bean.php';

//$bean = new Bean('192.168.7.206');
$bean = new Bean('127.0.0.1');

$bean->ph->watch('testtube');

while (true) {
    $job = $bean->ph->reserve();
    try {
        $jobPayload = $job->getData();
        var_dump($jobPayload);
        //sleep(1);

        $bean->ph->touch($job);
        $bean->ph->delete($job);
    } catch (Exception $e) {
        $bean->ph->release($job);
    }
}




