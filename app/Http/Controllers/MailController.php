<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Nette\Mail\SmtpMailer;
use Nette\Mail\Message;

class MailController extends Controller
{
    public function sendLoad(Request $request)
    {

        $validation = true;


        if ($validation) {
            // echo $request;
            $job_location_formatted = str_replace("},{", "}   ,   {", $request->job_location_data);
            $message = "

                <table style = 'border:1px solid black;'>
<tbody>
<tr >
<td style = 'border:1px solid black; padding:5px;' >Name</td>
<td style = 'border:1px solid black; padding:5px;' >{$request->name}</td>
</tr>
<tr>
<td style = 'border:1px solid black; padding:5px;'>Email</td>
<td style = 'border:1px solid black; padding:5px;'>{$request->email}</td>
</tr>
<tr>
<td style = 'border:1px solid black; padding:5px;'>Phone</td>
<td style = 'border:1px solid black; padding:5px;'>{$request->phone}</td>
</tr>
<tr>
<td style = 'border:1px solid black; padding:5px;'>Frieght type</td>
<td style = 'border:1px solid black; padding:5px;'>{$request->frieght_type}</td>
</tr>
<tr>
<td style = 'border:1px solid black; padding:5px;'>Multidrop</td>
<td style = 'border:1px solid black; padding:5px;'>{$request->multidrop}</td>
</tr>
<tr>
<td style = 'border:1px solid black; padding:5px;'>Location Data</td>
<td style = 'border:1px solid black; padding:5px;'>{$job_location_formatted}</td>
</tr>
<tr>
<td style = 'border:1px solid black; padding:5px;'>Length</td>
<td style = 'border:1px solid black; padding:5px;'>{$request->length}</td>
</tr>
<tr>
<td style = 'border:1px solid black; padding:5px;'>Breadth</td>
<td style = 'border:1px solid black; padding:5px;'>{$request->breadth}</td>
</tr>
<tr>
<td style = 'border:1px solid black; padding:5px;'>Height</td>
<td style = 'border:1px solid black; padding:5px;'>{$request->height}</td>
</tr>
<tr>
<td style = 'border:1px solid black; padding:5px;'>Weight</td>
<td style = 'border:1px solid black; padding:5px;'>{$request->weight}</td>
</tr>
<tr>
<td style = 'border:1px solid black; padding:5px;'>Message</td>
<td style = 'border:1px solid black; padding:5px;'>{$request->message}</td>
</tr>
</tbody>
</table>
<!-- DivTable.com -->
                ";

            $mailer = new SmtpMailer(
                host: 'smtp.zoho.in',
                username: 'send.loads@petrocomlogistics.co.uk',
                password: 'Sendloads@2989',
                encryption: "tls",
                port: 587
            );

            $mail = new Message();
            $mail->setFrom('send.loads@petrocomlogistics.co.uk')
                ->addTo('info@petrocomlogistics.co.uk')
                ->setSubject('Load Request')
                ->setHtmlBody($message);


            try {

                $mailer->send($mail);
                return response()->json([
                    "message" => "Sent Successfully",
                    "status" => 1
                ]);

            } catch (Exception $e) {

                return response()->json([
                    "message" => "Something went wrong",
                    "error" => $e->getMessage(),
                    "status" => 0
                ]);


            }

        }

    }
}
