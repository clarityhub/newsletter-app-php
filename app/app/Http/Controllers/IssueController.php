<?php

namespace App\Http\Controllers;

use App\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function issues()
    {
        $current_user_id = Auth::id();
        $paged_issues = Issue::with('campaigns')->with('votes')->paginate(20);

        $issues = $paged_issues->map(function ($issue) use ($current_user_id) {
            $issue->current_user_has_voted = $issue->votes->first(function ($vote) use ($current_user_id) {
                return $vote->user_id === $current_user_id;
            });
            $issue->vote_count = sizeof($issue->votes);

            return $issue;
        });

        return view('admin.issues.dashboard', array(
            'issues' => $issues,
            'paged_issues' => $paged_issues,
        ));
    }

    public function edit(Request $request, Issue $issue)
    {
        $returnTo = $request->query('returnTo');

        return view('admin.issues.edit', array(
            'issue' => $issue,
            'returnTo' => $returnTo,
        ));
    }

    public function update(Request $request, Issue $issue)
    {
        $returnTo = $request->input('return_to');

        $raw_title                  = $request->input('title');
        $raw_html_description       = $request->input('html_description');
        $raw_html_short_description = $request->input('html_short_description');

        $issue->title                  = $raw_title;
        $issue->html_description       = $raw_html_description;
        $issue->html_short_description = $raw_html_short_description;
        $issue->save();

        if ($returnTo) {
            return redirect($returnTo);
        }

        return redirect()
            ->route('issues.edit', array('id' => $issue->id))
            ->with('status', 'Successfully edited the issue');
    }
}
