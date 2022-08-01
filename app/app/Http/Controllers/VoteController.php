<?php

namespace App\Http\Controllers;

use Exception;
use App\Vote;
use App\Issue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function vote(Issue $issue)
    {
        $current_user_id = Auth::id();
        
        // Constraint in the database
        // There can only exist 1 of these
        try
        {
            Vote::create(array(
                'issue_id' => $issue->id,
                'user_id' => $current_user_id,
            ));

            return redirect()->back();
        }
        catch (Exception $e)
        {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function unvote(Issue $issue)
    {
        $current_user_id = Auth::id();

        try
        {
            Vote::where(array(
                'issue_id' => $issue->id,
                'user_id' => $current_user_id,
            ))->delete();

            return redirect()->back();
        }
        catch (Exception $e)
        {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
