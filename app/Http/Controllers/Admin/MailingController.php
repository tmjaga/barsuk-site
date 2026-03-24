<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MailingStatus;
use App\Http\Controllers\Controller;
use App\Jobs\SendNewsletterMailJob;
use App\Models\Mailing;
use App\Models\Subscriber;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Pelago\Emogrifier\CssInliner;
use Throwable;

class MailingController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $search = $request->query('search');

        $mailings = Mailing::query()
            ->when($search, fn ($q) => $q->where('subject', 'like', "%{$search}%"))
            ->latest()
            ->paginate(config('app.items_per_page'))
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json($mailings);
        }

        return view('admin.mailings.mailings-index', compact('mailings'));
    }

    public function create(): View
    {
        return view('admin.mailings.mailings-addedit');
    }

    public function edit(Mailing $mailing): View|RedirectResponse
    {
        // check mailing status
        if ($mailing->status == MailingStatus::IN_PROGRESS->value) {
            return to_route('admin.mailings.index')->with([
                'status' => __('Mailing In Process and can not be edited'),
                'variant' => 'error',
            ]);
        }

        return view('admin.mailings.mailings-addedit', compact('mailing'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        Mailing::create($validated);

        return to_route('admin.mailings.index')->with([
            'status' => __('Mailing has been Created'),
            'variant' => 'success',
        ]);
    }

    public function destroy(Mailing $mailing): JsonResponse
    {
        try {
            // check mailing status
            if ($mailing->status == MailingStatus::IN_PROGRESS->value) {
                throw new Exception('Mailing In Process and can not be edited');
            }

            $mailing->delete();

            return response()->json([
                'message' => __('Album deleted successfully'),
            ]);
        } catch (Throwable $e) {
            Log::error('Error deleting album', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error while deleting album'),
            ], 500);
        }
    }

    public function update(Request $request, Mailing $mailing): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        $mailing->update($validated);

        return to_route('admin.mailings.index')->with([
            'status' => __('Mailing has been Updated'),
            'variant' => 'success',
        ]);
    }

    public function startMailing(Mailing $mailing): JsonResponse
    {
        try {
            $subscribers = Subscriber::verified()->get();

            $mailing->update([
                'started_at' => now(),
                'total' => $subscribers->count(),
                'sent' => 0,
                'failed' => 0,
                'is_completed' => false,
            ]);

            // generate email message
            $mailBody = formatEmailContent($mailing->body);

            $htmlMail = view('emails.newsletter', [
                'mailSubject' => $mailing->subject,
                'mailBody' => $mailBody,
            ])->render();

            $cssFile = getManifestCssFile();
            $css = file_get_contents($cssFile);
            $inlinedHtml = CssInliner::fromHtml($htmlMail)->inlineCss($css)->render();

            foreach ($subscribers as $subscriber) {
                $withTokenHtml = str_replace('__TOKEN__', $subscriber->token, $inlinedHtml);
                SendNewsletterMailJob::dispatch($mailing, $withTokenHtml, $subscriber);
            }

            return response()->json([
                'message' => __('Mailing started successfully'),
            ]);
        } catch (Throwable $e) {
            Log::error('Error starting mailing', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error while starting mailing'),
            ], 500);
        }
    }

    // TODO: Delete this method
    public function mail()
    {

        // generate email message
        // $mailBody = formatEmailContent('test test');

        $data['name'] = 'Test name';
        $data['email'] = 'est@mail.com';
        $data['subject'] = 'Test subject';
        $data['message'] = 'Test name message';

        /*
        $htmlMail = view('emails.new-contact', [
            'mailSubject' => 'test',
            'mailBody' => $mailBody,
        ])->render();

        $cssFile = getManifestCssFile();
        $css = file_get_contents($cssFile);
        $inlinedHtml = CssInliner::fromHtml($htmlMail)->inlineCss($css)->render();

        return $inlinedHtml;
        */
        return view('emails.new-contact', compact('data'));

    }
}
