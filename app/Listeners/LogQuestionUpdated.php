<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\QuestionUpdated;
use Illuminate\Support\Facades\Log;
class LogQuestionUpdated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\QuestionUpdated  $event
     * @return void
     */
    public function handle(QuestionUpdated $event)
    {
        Log::info('Question Updated Event Fired', [
            'totalQuestions' => $event->totalQuestions,
            'questionsLastWeek' => $event->questionsLastWeek,
            'newUsersLastWeek' => $event->newUsersLastWeek,
        ]);
    }
}
