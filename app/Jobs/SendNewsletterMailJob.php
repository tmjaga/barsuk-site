<?php

namespace App\Jobs;

use App\Mail\NewsletterHtmlMail;
use App\Models\Mailing;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewsletterMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Mailing $mailing, protected string $mailBody, protected Subscriber $subscriber) {}

    public function handle()
    {
        try {
            Mail::to($this->subscriber->email)->send(new NewsletterHtmlMail($this->mailing->subject, $this->mailBody));

            $this->mailing->increment('sent');
        } catch (\Exception $e) {
            $this->mailing->increment('failed');
            Log::error($e->getMessage());
        } finally {
            if (($this->mailing->sent + $this->mailing->failed) >= $this->mailing->total) {
                $this->mailing->update([
                    'is_completed' => true,
                    'started_at' => null,
                ]);
            }
        }
    }
}
