<?php
session_start();

class Bot{
    private $field = [];
    private $counter = 0;
    private $symbols = [];
    private $cost_field = [];
    private $values_field = [3,2,3,2,4,2,3,2,3];
    private $response = ['id'=>-1,'level'=>1,'result'=>''];

    public function __construct()
    {
        $this->field = $_POST['game_field'];
        $this->response['level'] = $_POST['level'];
        $this->symbols= json_decode($_POST['symbols'], true);
    }
    public function getFieldInfo()
    {
        for($i=0;$i<9;$i++){
            if($this->field[$i]!='')
            {
                $this->counter++;
                $this->values_field[$i] = 0;
                if ($this->field[$i]==$this->symbols['bot'])
                {
                    $this->cost_field[$i] = 1;
                }
                else
                {
                    $this->cost_field[$i] = -1;
                }
            }
            else
            {
                $this->cost_field[$i] = 0;
            }

        }
    }

    public function getBotWinningCell()
    {
        return $this->getAnyWinningCell(2);
    }
    public function getPlayerWinningCell()
    {
        return $this->getAnyWinningCell(-2);
    }
    public function checkPlayerWin()
    {
        for($i = 0;$i<9;$i+=3) {
            if ($this->cost_field[$i] + $this->cost_field[$i + 1] + $this->cost_field[$i + 2] == -3)
            {
               return true;
            }
        }
        for($i = 0;$i<3;$i++) {
            if($this->cost_field[$i] + $this->cost_field[$i+3] + $this->cost_field[$i+6] == -3)
            {
                return true;
            }
        }
        if($this->cost_field[0] + $this->cost_field[4] + $this->cost_field[8] == -3)
        {
            return true;
        }
        if($this->cost_field[2] + $this->cost_field[4] + $this->cost_field[6] == -3)
        {
            return true;
        }
        return false;
    }
    public function getAnyWinningCell($cost)
    {//суммирует кост филд, если не хватает одного для победы, сумма столбца будет 2.тогда вернём элемент со значением 0
        for($i = 0;$i<9;$i+=3) {
            if ($this->cost_field[$i] + $this->cost_field[$i + 1] + $this->cost_field[$i + 2] == $cost) {
                if ($this->cost_field[$i] == 0) return $i;
                if ($this->cost_field[$i + 1] == 0) return $i + 1;
                if ($this->cost_field[$i + 2] == 0) return $i + 2;
            }
        }
        for($i = 0;$i<3;$i++) {
            if($this->cost_field[$i] + $this->cost_field[$i+3] + $this->cost_field[$i+6] == $cost)
            {
                if($this->cost_field[$i]==0) return $i;
                if($this->cost_field[$i+3]==0) return $i+3;
                if($this->cost_field[$i+6]==0) return $i+6;
            }
        }
        if($this->cost_field[0] + $this->cost_field[4] + $this->cost_field[8] == $cost)
        {
            if($this->cost_field[0]==0) return 0;
            if($this->cost_field[4]==0) return 4;
            if($this->cost_field[8]==0) return 8;
        }
        if($this->cost_field[2] + $this->cost_field[4] + $this->cost_field[6] == $cost)
        {
            if($this->cost_field[2]==0) return 2;
            if($this->cost_field[4]==0) return 4;
            if($this->cost_field[6]==0) return 6;
        }
        return false;
    }
    public function getMostValuableCell()
    {
        return array_search(max($this->values_field),$this->values_field);
    }
    public function makeMoveO()
    {
        if($this->counter<=1)// в начале
        {
            $this->response['id'] = $this->getMostValuableCell();
        }
        /*else if($this->counter == 2)// особый случай
        {

        }*/
        else if($this->checkPlayerWin())
        {
            $this->response['result'] =  'win';
            //$this->response['id'] = -1;
        }
        else if ($cell = $this->getBotWinningCell())
        {
            $this->response['id'] =  $cell;
            $this->response['result'] = 'lose';
        }
        //проверка на атаку должна идти до проверки на защиту
        else if($cell = $this->getPlayerWinningCell()) //если это уже не первый ход, проверить, что придумал игрок
        {
            $this->response['id'] =  $cell;
        }
        else if($this->counter >= 8)
        {
            $this->response['result'] = 'draw';
            if($this->counter == 8)
            {
                $this->response['id'] = $this->getMostValuableCell();
            }
        }
        else  //разумные действия закончились, делаем случайный ход
        {
            /*$i=0;
            while($this->field[$i]!='')
            {
                $i++;
            }
            $this->response['id'] =  $i;*/
            $this->response['id'] = $this->getMostValuableCell();
        }
    }

    public function updateLevel(){
        $result = $this->response['result'];

        if($result=='' || $result=='draw') return;

        if($result=='lose' && $this->response['level']==1) return;

        if($result=='win') $this->response['level']++;

        if($result=='lose')  $this->response['level']--;

        require_once("connectDB.php");
        $conn = DataBase::connect();

        $sql="UPDATE players SET level =:plevel WHERE login=:plogin";
        $stmt=$conn->prepare($sql);
        $stmt->execute([
            'plevel'=>$this->response['level'],
            'plogin'=>$_SESSION['login']
        ]);
    }
    public function echoResponse(){
        echo json_encode($this->response);
    }

    public function makeMoveX()
    {
        if($this->counter<=1)// в начале
        {
            $this->response['id'] = array_search(max($this->values_field),$this->values_field);
        }
        /*else if($this->counter == 2)// особый случай
        {

        }*/
        else if($this->checkPlayerWin())
        {
            $this->response['result'] =  'win';
        }
        else if ($cell = $this->getBotWinningCell())
        {
            $this->response['id'] =  $cell;
            $this->response['result'] = 'lose';
        }
        //проверка на атаку должна идти до проверки на защиту
        else if($cell = $this->getPlayerWinningCell()) //если это уже не первый ход, проверить, что придумал игрок
        {
            $this->response['id'] =  $cell;
        }
        else if($this->counter >= 8)
        {
            $this->response['result'] = 'draw';
        }
        else  //разумные действия закончились, делаем случайный ход
        {
            $i=0;
            while($this->field[$i]!='')
            {
                $i++;
            }
            $this->response['id'] =  $i;
        }
        echo json_encode($this->response);
    }

}




$bot = new Bot();
$bot->getFieldInfo();
$bot->makeMoveO();
$bot->updateLevel();
$bot->echoResponse();