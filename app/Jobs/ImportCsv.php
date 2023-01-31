<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Failures;
use App\Models\FailuresDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCsv implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $header;
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $header)
    {
        $this->data = $data;
        $this->header = $header;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $list = User::all()->toArray();
        $i = 1;
        $logs = [];
        $users = [];
        $total = count($this->data);
        
        foreach ($this->data as $user) {

            $userData = array_combine($this->header, $user);
            $check = $this->checkData($userData, $list, $i);

            if (count($check['log']) < 1) {
                array_push($users, $userData);
            } else {
                array_push($logs,$check);
            }
            $i++;
        }

        if (count($logs) > 0) {
            $failures = new Failures();
            $failures->total = $total;
            $failures->failed = count($logs);
            $failures->detail_failures = 'view detail';
            $failures->save();
            
            foreach( $logs as $log) {
                $value = [];
                foreach(array_keys($log['log']) as $key) {
                    array_push($value,$log['data'][$key]);
                }                
                $failures_detail = new FailuresDetail();
                $failures_detail->line = $log['row'];
                $failures_detail->attribute = implode(" ", array_keys($log['log']));
                $failures_detail->erorr = implode(" --- ", $log['log']); 
                $failures_detail->failures_id = $failures->id;
                $failures_detail->value = implode(" --- ", $value);
                $failures_detail->save();

                Log::channel('daily')->info($failures_detail);
            }
            return back();
        }else{
            $break_data = array_chunk($users, config('global.chunk'), true);

            foreach ($break_data as $data) {
                User::insert($data);
            }
            
            $log = new Failures();
            $log->total = $total;
            $log->failed = count($logs);
            $log->detail_failures = '';
            $log->save();
            return back()->withTotal(count($users));  
        }
    }

    function checkData($data, $list, $row)
    {
        $logs = [];
        $result = [];

        if (strlen($data['name']) < config('global.name_length')) {
            $logs['name'] = 'name too short';
        }if (strlen($data['password']) < config('global.password_length')) {
            $logs['password'] = 'password too short';
        }

        foreach($list as $l){
            if($l['name'] == $data['name']){
                $logs['name'] = 'name already taken';
            }
            if($l['email'] == $data['email']) {
                $logs['email'] = 'email already taken';
            }
        }

        $result['row'] = $row;
        $result['log'] = $logs;
        $result['data'] = $data;

        return $result;
       
    }
}
