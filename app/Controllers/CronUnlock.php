<?php

namespace App\Controllers;

class CronUnlock extends BaseController
{
    public function index()
    {
        $lockFile = WRITEPATH . 'cache/cron_master.lock';

        if (file_exists($lockFile)) {
            $lockTime = filemtime($lockFile);
            $age = time() - $lockTime;

            unlink($lockFile);

            echo "Lock file removed. Age: " . $age . " seconds";
        } else {
            echo "No lock file found";
        }
    }
}
