<?php

namespace App\Components;

use Phalcon\Escaper;

class Myescaper
{
    public $escaper;
    public function __construct()
    {
        $this->escaper =  new Escaper();
    }
    public function santize($request)
    {
        $email = $this->escaper->escapeHtml($request['email']);
        $password =$this->escaper->escapeHtml($request['password']);
        $remember = $this->escaper->escapeHtml($request['Remember']);
        
        $postdata = array(
            'email' => $email,
            'password' => $password,
            'remember' => $remember
        );
        return $postdata;
    }
}
