<?php

namespace App\Services\Github;

use GrahamCampbell\GitHub\Facades\GitHub;

class GithubIssues
{
    public function getIssue($org, $repo_title, $issue_number, $with_repo = true)
    {
        $issue = Github::issues()->show(
            $org,
            $repo_title,
            $issue_number
        );

        if ($with_repo)
        {
            $repo = Github::repo()->show(
                $org,
                $repo_title
            );

            $issue['repo_description'] = $repo['description'];
        }

        return $issue;
    }
}