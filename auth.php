<?php
require_once("connectDB.php");
session_start();

class Player {
    private $password;
    private $login;
    private $errors=array();
    private $pepper='1234';

    public function inputValid(){
        return !$this->errors;
    }
    public function validate(){
        $this->login=htmlentities($this->login, ENT_QUOTES, 'UTF-8');
        $this->password=htmlentities($this->password, ENT_QUOTES, 'UTF-8');

        if($this->login==""){
            $this->errors=array_merge($this->errors,array('login'=>'Пожалуйста, введите ваш логин'));
        } elseif (strlen($this->login)<3){
            $this->errors=array_merge($this->errors,array('login'=>'Минимальная длина логина - 3 символа'));
        } elseif (strpos($this->login,' ')){
            $this->errors=array_merge($this->errors,array('login'=>'Ваш логин не должен содержать пробелов'));
        }
        if($this->password==""){
            $this->errors=array_merge($this->errors,array('password'=>'Пожалуйста, введите ваш пароль'));
        } elseif (strlen($this->password)<8){
            $this->errors=array_merge($this->errors,array('password'=>'Минимальная длина пароля - 8 символов'));
        } elseif (strpos($this->password,' ')){
            $this->errors=array_merge($this->errors,array('password'=>'Ваш пароль не должен содержать пробелов'));
        }

    }
    public function __construct(){
        $this->login=trim($_POST['login']);
        $this->password=trim($_POST['password']);
    }
    public function auth(){

        $conn = DataBase::connect();

        $sql="SELECT * FROM players WHERE login=:plogin";
        $stmt=$conn->prepare($sql);
        $stmt->execute([
            'plogin'=>$this->login,
        ]);
        $playerData=$stmt->fetch();

        if(!$playerData){
            $salt = $this->randomSalt();
            $password =password_hash($salt.$this->password.$this->pepper,PASSWORD_ARGON2ID);
            $sql="INSERT INTO PLAYERS (login, password, salt) VALUES (:plogin, :ppass, :psalt)";
            $stmt=$conn->prepare($sql);
            $stmt->execute([
                'plogin'=>$this->login,
                'ppass'=>$password,
                'psalt'=>$salt
            ]);
        }
        elseif(!password_verify($playerData['salt'].$this->password.$this->pepper,$playerData['password'])){
            $this->errors=array_merge($this->errors,array('password'=>'Your password is incorrect'));
        }

        if(!$this->errors){
            $_SESSION['login'] = $this->login;//для проверки в game.php
/*
            setcookie(session_name(), session_id(), strtotime("+10 years"));

            $sql="UPDATE players SET session_id =:psession_id WHERE login=:plogin";
            $stmt=$conn->prepare($sql);
            $stmt->execute([
                'psession_id'=>session_id(),
                'plogin'     =>$this->login
            ]);*/
        }
    }
    public function echoResponse(){
        echo json_encode($this->errors);
    }
    public function randomSalt($len = 8) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`~!@#$%^&*()-=_+';
        $l = strlen($chars) - 1;
        $str = '';
        for ($i = 0; $i<$len; ++$i) {
            $str .= $chars[rand(0, $l)];
        }
        return $str;
    }
}

$player = new Player();
$player->validate();
if($player->inputValid())
$player->auth();
$player->echoResponse();
//search for player



