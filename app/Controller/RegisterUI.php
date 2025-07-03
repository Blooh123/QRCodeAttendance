<?php 


class RegisterUI extends \Controller{
    public function index(): void
    {
        $this->loadView('register_ui');
    }
}

$registerUI = new RegisterUI();
$registerUI->index();