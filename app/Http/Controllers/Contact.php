<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Intelligent\Kernel\View;
use Intelligent\Kernel\Controller;

use Exception;

use Intelligent\Kernel\Config;
use Intelligent\Kernel\Request;
use Intelligent\Mail\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use Intelligent\Mail\Validation\Validator;


class Contact extends Controller
{
    public $request;
    /** 
     * Submit a form data to email 
     * 
     * @param 
     * @return void  
     */
    public function index()
    {
        if (isset($_POST['Submit'])) {

            $request = Request::createFromGlobals([
                'name' => 'required',
                'email' => 'required|email',
                'message' => 'required'
            ]);

            $errors =  Validator::validate($request->errors);

            // Populate Email Template  @TODO
            $email_template = file_get_contents(resource_path('views/template') . '/email_template.html');
            $email_template = str_replace('{{name}}', $request->post['name'], $email_template);
            $email_template = str_replace('{{email}}', $request->post['email'], $email_template);
            $email_template = str_replace('{{phone}}', $request->post['phone'], $email_template);
            $email_template = str_replace('{{message}}', $request->post['message'], $email_template);

            try {
                $mail = new Mail(false,  $email_template);
                $mail->setFrom($request->post['email'], $request->post['name']);
                $mail->addAddress(Config::get('mail.from.address'), Config::get('mail.from.name'));
                $mail->Subject = Config::get('mail.from.subject');

                //send the message, check for errors
                if ($errors) {
                    return View::render('pages/contact.html', [
                        'errors' => $errors,
                        'field' => $request
                    ]);
                } else {

                    $mail->sendMail();
                    return View::render('pages/contact.html', [
                        'success' => 'message sent'
                    ]);
                }
            } catch (Exception $e) {
                echo "Caught a " . get_class($e) . ": " . $e->getMessage();
            }
        } else {

            return View::render('pages/contact.html');
        }
    }
}
