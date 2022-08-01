<?php

namespace App\Http\Controllers;

use Exception;
use App\Issue;
use App\Campaign;
use App\Parsers\MarkdownParser;
use App\Services\Mailchimp\MailchimpCampaign;
use App\Services\Github\GithubIssues;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use Illuminate\Support\Facades\Session;

class CampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function campaigns()
    {
        $campaigns = Campaign::paginate(20);

        return view('admin.campaigns.dashboard', array('campaigns' => $campaigns));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request, MarkdownParser $markdownParser, GithubIssues $githubIssues)
    {
        $request->validate(array(
            'github_issues' => 'required',
            'title'         => 'required',
            'subject_line'  => 'required',
            'preview_text'  => 'required',
        ));

        $raw_issues       = $request->input('github_issues');
        $raw_title        = $request->input('title');
        $raw_subject_line = $request->input('subject_line');
        $raw_preview_text = $request->input('preview_text');
        $raw_greeting     = $request->input('greeting');

        try
        {
            $issues_data = $this->getIssueParts($raw_issues);
            $issues      = [];

            if (sizeof($issues_data) === 0)
            {
                throw new Exception('You must provide at least 1 valid Github URL');
            }

            foreach($issues_data as $issue_data)
            {
                $github_issue = $githubIssues->getIssue(
                    $issue_data['org'],
                    $issue_data['repo'],
                    $issue_data['issue_number']
                );
    
                $html_description       = $markdownParser->parse($github_issue['body']);
                $html_short_description = $markdownParser->shorten($github_issue['body']);

                $issue = Issue::updateOrCreate(
                    array('type' => 'github', 'url' => $github_issue['url']),
                    array(
                        'html_url'               => $github_issue['html_url'],
                        'repository_url'         => $github_issue['repository_url'],
                        'repository_title'       => $issue_data['repo'],
                        'repository_description' => $github_issue['repo_description'],
                        'title'                  => $github_issue['title'],
                        'description'            => $github_issue['body'],
                        'html_description'       => $html_description,
                        'html_short_description' => $html_short_description,
                    )
                );

                array_push($issues, $issue);
            }

            $campaign = Campaign::create(array(
                'title'        => $raw_title,
                'subject_line' => $raw_subject_line,
                'preview_text' => $raw_preview_text,
                'greeting' => $raw_greeting,
            ));

            $data = array();
            foreach ($issues as $index => $issue) {
                $data[$issue->id] = ['order_by' => $index];
            }

            $campaign->issues()->attach($data);

            return redirect()->route('campaigns.edit', array('id' => $campaign->id));
        }
        catch (Exception $e)
        {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->with('input.title', $raw_title)
                ->with('input.greeting', $raw_greeting)
                ->with('input.github_issues', $raw_issues);
        }
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', array(
            'campaign' => $campaign,
            'issues'   => $campaign->issues()->orderBy('order_by')->get(),
        ));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $request->validate(array(
            'title'         => 'required',
            'subject_line'  => 'required',
            'preview_text'  => 'required',
        ));

        $raw_title        = $request->input('title');
        $raw_subject_line = $request->input('subject_line');
        $raw_preview_text = $request->input('preview_text');
        $raw_greeting     = $request->input('greeting');

        $campaign->title = $raw_title;
        $campaign->subject_line = $raw_subject_line;
        $campaign->preview_text = $raw_preview_text;
        $campaign->greeting = $raw_greeting;
        $campaign->save();

        return redirect()
            ->route('campaigns.edit', array('id' => $campaign->id))
            ->with('status', 'Successfully edited the campaign');
    }

    public function detachFromMailchimp(Campaign $campaign)
    {
        if (!empty($campaign->mailchimp_id))
        {
            try
            {
                $campaign->mailchimp_id = '';
                $campaign->save();

                return redirect()
                    ->route('campaigns.edit', array('id' => $campaign->id))
                    ->with('status', 'Successfully detached the campaign');
            }
            catch (Exception $e)
            {
                return redirect()->back()
                    ->with('action.error', $e->getMessage())
                    ->with('input.emails', $raw_emails);
            }
        }
        else
        {
            return redirect()->back()
                ->with('action.error', 'Can\'t detach from non-existant Mailchimp ID');
        }
    }

    public function schedule(Request $request, Campaign $campaign, MailchimpCampaign $mailchimpCampaign)
    {
        // TODO use env
        $dc = 'us18';
        $raw_emails = $request->input('emails');
        $raw_time = $request->input('time');

        try
        {
            if (empty($raw_emails))
            {
                throw new Exception('You must provide at least 1 email');
            }

            if (empty($raw_time))
            {
                throw new Exception('You must provide a time');
            }

            $time = strtotime($raw_time);
            $emails = explode(',', $raw_emails);

            // only create a new one if the Campaign does not already have
            // one, otherwise update
            if (empty($campaign->mailchimp_id))
            {
                // create mailchimp
                $mailchimpCampaign->useCampaign($campaign);
                $result = $mailchimpCampaign->create();

                $campaign->mailchimp_id  = $result['id'];
                $web_id                  = $result['web_id'];
                $campaign->mailchimp_url = "https://$dc.admin.mailchimp.com/campaigns/show/?id=$web_id";

                $campaign->save();
            }
            else
            {
                $mailchimpCampaign->useCampaign($campaign);
                $mailchimpCampaign->update($campaign->mailchimp_id);
            }

            $mailchimpCampaign->setContent($campaign->mailchimp_id, $campaign, $campaign->issues()->orderBy('order_by')->get());
            $mailchimpCampaign->sendTestEmail($campaign->mailchimp_id, $emails);
            $mailchimpCampaign->schedule($campaign->mailchimp_id, $time);

            return redirect()
                ->route('campaigns.edit', array('id' => $campaign->id))
                ->with('status', 'Successfully scheduled the campaign');
        }
        catch (Exception $e)
        {
            return redirect()->back()
                ->with('action.error', $e->getMessage())
                ->with('input.emails', $raw_emails);
        }
    }

    public function delete(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()
                ->route('campaigns')
                ->with('status', 'Successfully deleted campaign');
    }

    public function deleteIssue(Campaign $campaign, Issue $issue)
    {
        $campaign->issues()->detach($issue->id);

        return redirect()
                ->route('campaigns.edit', $campaign->id)
                ->with('status', 'Successfully removed issue from campaign');
    }

    /**
     * Change a raw string of issues on new lines into
     * an array with github issue parts
     *
     */
    private function getIssueParts(string $raw_issues)
    {
        // Clean the raw issues
        // Turn \r\n into \n
        $raw_issues = preg_replace('~\R~u', "\n", $raw_issues);
        $issues = explode("\n", $raw_issues);
        $issue_parts = array();

        foreach ($issues as $issue_string)
        {
            $path = parse_url($issue_string)['path'];
            
            // Skip empty strings
            if (empty($path))
            {
                continue;
            }

            // Parse path
            preg_match('/\/(.*)\/(.*)\/issues\/(.*)\/?/', $path, $parts);

            if ($parts == FALSE)
            {
                throw new Exception("'$issue_string' could not be parsed");
            }
            
            $org          = $parts[1];
            $repo         = $parts[2];
            $issue_number = $parts[3];

            array_push($issue_parts, array(
                'org' => $org,
                'repo' => $repo,
                'issue_number' => $issue_number,
            ));
        }

        return $issue_parts;
    }
}
