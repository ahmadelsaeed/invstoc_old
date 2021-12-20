<?php

namespace App\Jobs;

use App\Events\notifications;
use App\Jobs\Job;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendGeneralNotification extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $users;
    protected $not_title;
    protected $not_type;
    protected $not_link;
    protected $from_user_id;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($users,$not_title,$not_type,$not_link,$from_user_id)
    {
        $this->users = $users;
        $this->not_title = $not_title;
        $this->not_type = $not_type;
        $this->not_link = $not_link;
        $this->from_user_id = $from_user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->attempts() == 1)
        {
            $users = $this->users;
            $from_user_id = $this->from_user_id;
            $not_title = $this->not_title;
            $not_type = $this->not_type;
            $not_link = $this->not_link;

            $get_users_data = User::whereIn("user_id",$users)->get()->groupBy("user_id")->all();

            foreach($users as $key => $user_id)
            {

                notifications::add_notification([
                    'not_title'=>$not_title,
                    'not_type'=>"$not_type",
                    'not_link'=>$not_link,
                    'not_from_user_id' => $from_user_id,
                    'not_to_user_id' => $user_id,
                    'get_users_data' => $get_users_data,
                ]);

            }

        }

    }
}
