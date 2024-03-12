<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class EmailController extends Controller
{
    public function sendSMTP($template, $user)
    {
        $tmpl = 'emails.' . $template['template'];
        $view = View::make($tmpl, [
            'name' => $user->real_name,
            'email' => $user->email,
            'subject' => $template['subject'],
            'extra' => $template['extra'],
            'category' => $template['category']
        ]);

        $body = $this->mailBody2($view);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://send.api.mailtrap.io/api/send");
        //curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer 6d027b9ed6bed728f7c61b7688b0cf19'
        ]);

        $response = json_decode(curl_exec($ch), true);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }

    public function mailBody2($view)
    {
        $body = [
            "from" => [
                "email" => 'contato@betfyre.com',
                "name" => 'Betfyre'
            ],
            "to" => [
                [
                    "name" => strval($view->name),
                    "email" => strval($view->email)
                ]
            ],
            "subject" => strval($view->subject),
            "html" => $this->compress_html($view->render()),
            "category" => strval($view->category)
        ];

        return $body;
    }

    // Format HTML Body
    private function replace_double_quotes($string) {
        return str_replace('"', "'", $string);
    }

    // remove whitespaces before tags, except space
    private function compress_html($html) {
        $search = array(
            '/\>[^\S ]+/s',  // remove whitespaces after tags, except space
            '/[^\S ]+\</s',  // remove whitespaces before tags, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );
        $replace = array(
            '>',
            '<',
            '\\1'
        );
        $html = preg_replace($search, $replace, $html);
        return $this->replace_double_quotes($html);
    }
}

