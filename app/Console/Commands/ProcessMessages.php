<?php

namespace App\Console\Commands;

use App\Models\Message;
use App\Notifications\InAppNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newMessages = Message::where('is_read', 0)->get();
        foreach ($newMessages as $message) {
            $recipient = $message->recipient;

            try {
                $recipient->notify(new InAppNotification($message));
                $message->update(['is_read' => true]);
                Log::info('Notification sent.');
            } catch (\Exception $e) {
                Log::error('Notification failure: ' . $e->getMessage());
            }
        }
    }
}
