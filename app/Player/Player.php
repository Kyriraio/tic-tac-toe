<?php
namespace App\Player;

use App\DataBase\DB;

require_once __DIR__ . '/../../vendor/autoload.php';

class Player {
    protected DB $db;
    private string $pepper='1234';
    private array $input = array();

    public function __construct(DB $db)
    {
        $this->db = $db;
        $this->input['login']=htmlentities(trim($_POST['login']), ENT_QUOTES, 'UTF-8');
        $this->input['password']=htmlentities(trim($_POST['password']), ENT_QUOTES, 'UTF-8');
    }

    public function auth() : void
    {
        $errors = $this->getInputErrors();

        if(empty($errors))
        {
            $playerData = $this->db->run("SELECT salt, password FROM players WHERE login=?",
                [$this->input['login']]
            )->fetch();

            if(empty($playerData))
            {
                $this->register();
            }
            else
            {
                $errors = $this->getLoginErrors($playerData);
            }
        }

        if(empty($errors)){
            $_SESSION['login'] = $this->input['login'];
        }

        echo json_encode($errors);
    }

    protected function getLoginErrors($playerData): array
    {
        return (!password_verify($playerData['salt'].$this->input['password'].$this->pepper,
            $playerData['password']))
        ?  array('password'=>'Введён неправильный пароль')
        :  array();
    }

    protected function getInputErrors(): array
    {
        $errors = array();

        if($this->input['login']=="") {
            $errors=array_merge($errors,array('login'=>'Пожалуйста, введите ваш логин'));
        }
        elseif (strlen($this->input['login'])<3){
            $errors=array_merge($errors,array('login'=>'Минимальная длина логина - 3 символа'));
        }
        elseif (strpos($this->input['login'],' ')){
            $errors=array_merge($errors,array('login'=>'Ваш логин не должен содержать пробелов'));
        }
        if($this->input['password']=="") {
            $errors=array_merge($errors,array('password'=>'Пожалуйста, введите ваш пароль'));
        }
        elseif (strlen($this->input['password'])<8){
            $errors=array_merge($errors,array('password'=>'Минимальная длина пароля - 8 символов'));
        }
        elseif (strpos($this->input['password'],' ')){
            $errors=array_merge($errors,array('password'=>'Ваш пароль не должен содержать пробелов'));
        }
        return $errors;
    }

    protected function register(): void
    {
            $salt = $this->GetRandomSalt();
            $this->db->run("INSERT INTO PLAYERS (login, password, salt) VALUES (:login, :pass, :salt)",
                [
                    'login'=>$this->input['login'],
                    'pass'=>password_hash($salt.$this->input['password'].$this->pepper,PASSWORD_ARGON2ID),
                    'salt'=>$salt
                ]
            );

    }

    protected function GetRandomSalt($len = 8): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`~!@#$%^&*()-=_+';
        $l = strlen($chars) - 1;
        $str = '';
        for ($i = 0; $i<$len; ++$i) {
            $str .= $chars[rand(0, $l)];
        }
        return $str;
    }
}




