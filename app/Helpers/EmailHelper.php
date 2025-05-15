<?php

namespace App\Helpers;

use App\Mail\PostOfficeMail;
use App\Models\EmailSubject;
use App\Models\EmailTemplate;
use App\Models\EmailProject;
use Illuminate\Support\Facades\Mail;

class EmailHelper
{

    /**
     * 
     * @param string $templateToken
     * @param string|array $to
     * @param array $data
     * @return void
     * 
     */
    public static function sendPostOfficeEmail($templateToken, $to, $data)
    {
        if (!is_array($to)) {
            $to = [$to];
        }
        $emailSubject = EmailSubject::where('token', $templateToken)->first();
        if ($emailSubject != null) {
            $emailTemplate = EmailTemplate::where('subject_id', $emailSubject->id)->first();
            if ($emailTemplate != null) {
                $domainModel = EmailProject::where('id', $emailTemplate->domain)->first();
                if ($domainModel != null) {
                    $masterTemplate = $domainModel->master_template;
                    $templateHTML = $emailTemplate->content;
                    foreach ($data as $key => $value) {
                        $templateHTML = str_replace("{" . $key . "}", $value, $templateHTML);
                    }
                    $subject = $emailSubject->subject;
                    if($masterTemplate!=null){
                        // $templateHTML = str_replace("{EmailBody}", $templateHTML, $masterTemplate);
                    }
                    // echo $templateHTML;die;
                    Mail::to($to)->send(new PostOfficeMail($subject, $templateHTML));
                }else{
                    // echo "email tem 2 not found";die;
                }
            }else{
                // echo "email tem not found";die;
            }
        }else{
            // echo "email subj not found";die;
        }
    }
}
