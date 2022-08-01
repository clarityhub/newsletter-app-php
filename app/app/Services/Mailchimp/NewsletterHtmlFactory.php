<?php

namespace App\Services\Mailchimp;

class NewsletterHtmlFactory
{
    public function __construct()
    {
        $this->template = 'admin.templates.emails.issues';
        $this->actionFlavorText = array(
            'Help Out',
            'Contribute',
            'Pitch In',
            'Commit',
            'Fix It',
            'Check It Out',
        );
    }

    public function build($campaign, $issues)
    {
        $sections = array();
        
        foreach ($issues as $index => $issue)
        {
            $flavorText = $this->actionFlavorText[ $index % sizeof($this->actionFlavorText) ];

            $section = array(
                'title'       => $issue->title . ' <i>in ' . $issue->repository_title . '</i>',
                'description' => $issue->html_short_description,
                'link_text'   => $flavorText,
                'link_url'    => $issue->html_url,
                'repo_description' => !empty($issue->repository_description) ? '<i><b>' . $issue->repository_title . '</b> – '. $issue->repository_description . '</i>' : false,
            );

            array_push($sections, $section);
        }

        $html = view($this->template, [
            'campaign' => $campaign,
            'sections' => $sections,
        ])->render();

        return $html;
    }
}