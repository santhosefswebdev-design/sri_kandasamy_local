#!/bin/bash
/usr/bin/curl -s -L -A "CronJob/1.0" "https://srikandaswamytemple.grasp.com.my/dev/cronMaster/run?key=temple_master_cron_2024_secure" >> /home/graspcommy/srikandaswamytemple_grasp_com_my/dev/writable/logs/cron_Master_output.log 2>&1
echo "Cron executed at: $(date)" >> /home/graspcommy/srikandaswamytemple_grasp_com_my/dev/writable/logs/cron_Execution_times.log