<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campaign;
use App\Services\Mailchimp\NewsletterHtmlFactory;

class EmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function emails()
    {
        return view('admin.emails');
    }

    public function preview(Campaign $campaign, NewsletterHtmlFactory $factory)
    {
        $issues = $campaign->issues()->get();
        $html = $factory->build($campaign, $issues);

        return response($html, 200)
            ->header('Content-Type', 'text/html');
    }
}
