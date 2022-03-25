<?php

use Phalcon\Mvc\Controller;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Http\Request;

class SignupController extends Controller{

    public function IndexAction(){

    }

    public function registerAction(){
        
        $adapter = new Stream('../app/logs/signup.log');
        $logger  = new Logger(
            'messages',
            [
                'main' => $adapter,
            ]
        );

        $user = new Users();

        $request = new Request();
        $escape = new \App\Components\Myescaper();
        $data = $request->get();
        $postdata = $escape->santize($data);
         $this->view->request = $postdata;
         $email = $postdata['email'];
        // $password = $postdata['password'];
        // $remember = $postdata['Remember'];

        $user->assign(
            $postdata,
            [
                'email',
                'password'
            ]
        );

        $success = $user->save();

        $this->view->success = $success;

        if($success){
            $logger->info(" Signup by $email. Register succesfully");
            $this->view->message = "Register succesfully";
        }else{
            $logger->info(" Signup by $email. ".implode(" . ", $user->getMessages())."");
            $this->view->message = "Not Register succesfully due to following reason: <br>".implode("<br>", $user->getMessages());
        }
    }
}