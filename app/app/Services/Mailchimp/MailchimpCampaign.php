<?php

namespace App\Services\Mailchimp;

use Newsletter;
use Exception;
use App\Issue;
use App\Campaign;

class MailchimpCampaign
{
    public function __construct()
    {
        $this->factory = new NewsletterHtmlFactory();
        $this->list_id = env('MAILCHIMP_LIST_ID');
        $this->title = '';
        $this->subject_line = '';
        $this->preview_text = '';
        $this->from_name = 'Clarity Hub';
        $this->reply_to = 'opensource@clarityhub.io';
        $this->auto_footer = true;
    }

    public function useCampaign(Campaign $campaign)
    {
        $this->title        = $campaign->title;
        $this->subject_line = $campaign->subject_line;
        $this->preview_text = $campaign->preview_text;
    }

    public function create()
    {
        $result = Newsletter::getApi()->post('/campaigns', array(
            'type' => 'regular',
            'recipients' => array(
                'list_id' => $this->list_id,
            ),
            'settings' => array(
                'subject_line' => $this->subject_line,
                'preview_text' => $this->preview_text,
                'title'        => $this->title,
                'from_name'    => $this->from_name,
                'reply_to'     => $this->reply_to,
                'auto_footer'  => $this->auto_footer,
            ),
        ));

        
        return $this->check($result);
    }

    public function update($mailchimp_campaign_id)
    {
        $result = Newsletter::getApi()->patch("/campaigns/$mailchimp_campaign_id", array(
            'type' => 'regular',
            'recipients' => array(
                'list_id' => $this->list_id,
            ),
            'settings' => array(
                'subject_line' => $this->subject_line,
                'preview_text' => $this->preview_text,
                'title'        => $this->title,
                'from_name'    => $this->from_name,
                'reply_to'     => $this->reply_to,
                'auto_footer'  => $this->auto_footer,
            ),
        ));

        return $this->check($result);
    }

    public function setContent($mailchimp_campaign_id, $campaign, $issues)
    {
        $html = $this->factory->build($campaign, $issues);
        
        $result = Newsletter::getApi()->put("/campaigns/$mailchimp_campaign_id/content", array(
            'html' => $html,
        ));

        return $this->check($result);
    }

    public function sendTestEmail($mailchimp_campaign_id, $emails)
    {
        $result = Newsletter::getApi()->post("/campaigns/$mailchimp_campaign_id/actions/test", array(
            'test_emails' => $emails,
            'send_type' => 'html',
        ));

        
        return $this->check($result);
    }

    public function schedule($mailchimp_campaign_id, $time)
    {
        $result = Newsletter::getApi()->post("/campaigns/$mailchimp_campaign_id/actions/schedule", array(
            'schedule_time' => gmdate('Y-m-d\T15:00:00+00:00', $time),
        ));

        return $this->check($result);
    }

    private function check($result)
    {
        if (is_array($result) && array_key_exists('errors', $result))
        {
            $dump = var_export($result, true);
            throw new Exception("Something bad happened with Mailchimp. Dump: $dump");
        }

        return $result;
    }
}