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
        $validation = $request->validate([
            "name" => "required",
            "email" => "required",
            "frieght_type" => "required",
            "pickup_location" => "required",
            "destination" => "required",
            "multidrop" => "required",
            "message" => "required"
        ]);


        if ($validation) {
            // echo $request;

            $message = "
                <table>
<tbody>
<tr>
<td>name</td>
<td>{$request->name}</td>
</tr>
<tr>
<td>email</td>
<td>{$request->email}</td>
</tr>
<tr>
<td>phone</td>
<td>{$request->phone}</td>
</tr>
<tr>
<td>Frieght type</td>
<td>{$request->frieght_type}</td>
</tr>
<tr>
<td>destination</td>
<td>{$request->destination}</td>
</tr>
<tr>
<td>multidrop</td>
<td>{$request->multidrop}</td>
</tr>
<tr>
<td>length</td>
<td>{$request->length}</td>
</tr>
<tr>
<td>breadth</td>
<td>{$request->breadth}</td>
</tr>
<tr>
<td>height</td>
<td>{$request->height}</td>
</tr>
<tr>
<td>weight</td>
<td>{$request->weight}</td>
</tr>
<tr>
<td>message</td>
<td>{$request->message}</td>
</tr>
</tbody>
</table>
<!-- DivTable.com -->
                ";

            // $mailer = new SmtpMailer(
            //     host: 'smtp.gmail.com',
            //     username: 'pran4476@gmail.com',
            //     password: 'zdsiywtvmrohapvg',
            //     encryption: SmtpMailer::EncryptionSSL,
            // );
            // $mail = new Message();
            // $mail->setFrom('pran4476@gmail.com')
            //     ->addTo('navinkrv@gmail.com')
            //     ->setSubject('Load Request')
            //     ->setHtmlBody("");

            $to_name = "Test";
            $to_email = "navinkrv@gmail.com";
            $testMailData = [
                'title' => 'Load Request',
                'body' => $request
            ];

            try {
                Mail::to('navinkey5@gmail.com')->send(new SendMail($testMailData));

                // dd('Success! Email has been sent successfully.');
                // $mailer->send($mail);
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
