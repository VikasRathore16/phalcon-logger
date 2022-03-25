<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;


class IndexController extends Controller
{
    public function indexAction()
    {
        $adapter = new Stream('../app/logs/login.log');
        $logger  = new Logger(
            'messages',
            [
                'main' => $adapter,
            ]
        );

        if ($this->cookies->has("email")) {
            //Get the cookie 
            $this->cookies->useEncryption(false);
            $loginCookie = $this->cookies->get("email");

            // Get the cookie's value 

            $value = $loginCookie->getValue();
            $this->view->value = $value;
            header("Location:  http://localhost:8080/index/dashboard");
        }


        $request = new Request();
        $escape = new \App\Components\Myescaper();
        $data = $request->get();
        $postdata = $escape->santize($data);
         $this->view->request = $postdata;
        $email = $postdata['email'];
        $password = $postdata['password'];
        $remember = $postdata['Remember'];
    
        $checkemail = Users::find("email = '$email'");
        $checkpassword = Users::find("password = '$password'");

        $user = Users::query()
            ->where("email = '$email'")
            ->andWhere("password = '$password'")
            ->execute();
        $this->view->user = $user;

        if (count($checkemail) > 0 && count($password)) {
            $this->view->msg = 'Authentication failed';
            $logger->error(" Login by $email. Password is Wrong");
        }

        if (count($checkemail) == 0) {
            $this->view->msg = 'Email does not Exists! Please Sign Up';
            $logger->error(" Login by $email. User not Exists");
        }

        if (count($user) > 0) {
            if ($remember == 'on') {
                $this->cookies->useEncryption(false);
                $this->cookies->set(
                    'email',
                    $email
                );
            }
            $this->view->msg = $this->session->set('email', $email);
            $this->view->msg = $this->session->set('password', $password);
            $logger->info(" Login by $email. User Loggin Successfully");
            header("Location: http://localhost:8080/index/dashboard");
        }
    }


    public function dashboardAction()
    {
        if ($this->session->get('email') == null) {
            $response = new Response();
            $response->setStatusCode(403);
            $response->setContent("<h1>Authentication Failed ! 403</h1> <p>Please Login</p>");
            $response->send();
            die();
        }

        $appName = $this->getAppName;
        $this->view->appName = $appName;
    }

    public function logoutAction()
    {

        $remember = $this->cookies->get('email');
        $remember->delete("email");
        $this->session->remove('email');
        unset($this->session);
        header("Location: http://localhost:8080/");
    }
}
