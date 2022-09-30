<?php
namespace App\Bot;

use App\DataBase\DataBase;

require_once __DIR__ . '/../../vendor/autoload.php';


class Bot{
    private const BOT_INTELLIGENCE = 50;
    private int $counter = 0;
    private array $field;
    private array $symbols;
    private array $cost_field = [];
    private array $values_field = [3,2,3,2,4,2,3,2,3];
    private array $response = ['id'=>-1,'level'=>1,'result'=>''];

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

                $this->cost_field[$i] = ($this->field[$i]==$this->symbols['bot']) ? 1 : -1;
            }
            else
            {
                $this->cost_field[$i] = 0;
            }

        }
    }
    public function getBotWinningCell(): bool|int
    {
        return $this->getAnyWinningCell(2);
    }
    public function getPlayerWinningCell(): bool|int
    {
        return $this->getAnyWinningCell(-2);
    }
    public function checkBotWin(): bool
    {
        return $this->checkSomeoneWin(3);
    }
    public function checkPlayerWin(): bool
    {
        return $this->checkSomeoneWin(-3);
    }
    public function checkSomeoneWin($cost): bool
    {
        for($i = 0;$i<9;$i+=3) {
            if ($this->cost_field[$i] + $this->cost_field[$i + 1] + $this->cost_field[$i + 2] == $cost)
            {
               return true;
            }
        }
        for($i = 0;$i<3;$i++) {
            if($this->cost_field[$i] + $this->cost_field[$i+3] + $this->cost_field[$i+6] == $cost)
            {
                return true;
            }
        }
        if($this->cost_field[0] + $this->cost_field[4] + $this->cost_field[8] == $cost)
        {
            return true;
        }
        if($this->cost_field[2] + $this->cost_field[4] + $this->cost_field[6] == $cost)
        {
            return true;
        }
        return false;
    }

    public function getEmptyDiagonalCell($cost): bool|int
    {
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
    public function getAnyWinningCell($cost): bool|int
    {
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
        $cell = $this->getEmptyDiagonalCell($cost);
        if($cell!==false) return $cell;
        return false;
    }
    public function getSomeValuableCell(): bool|int
    {
        if($this->botIsSmart()){
            $cell = $this->getMostValuableCell();
        }
        else if($this->botIsSmart()){
            $cell = $this->getMostValuableCell(-1);
        }
        else {
            $cell = $this->getMostValuableCell(-2);
        }
        return ($cell!==false) ? $cell : $this->getMostValuableCell();
    }
    public function getMostValuableCell($stupidShift=0): bool|int
    {
        $max = max($this->values_field);
        if ($max==2) $stupidShift = 0;
        return array_search(max($this->values_field)+$stupidShift, $this->values_field);
    }
    public function botIsSmart($intelligencePercentage = self::BOT_INTELLIGENCE   ): bool
    {
        return ((rand(0, 100) <= $intelligencePercentage));
    }
    private function specialEarlyStrategy()
    {
        if($this->counter==2 && $this->field[4]==$this->symbols['player'])
        {
            $cell = $this->getEmptyDiagonalCell(0);
            $this->response['id']=($cell!==false) ? $cell : $this->getMostValuableCell();
        }
        else if($this->counter == 3 && $this->field[4]==$this->symbols['bot'])
        {
            if($this->field[2]==$this->symbols['player'] && $this->field[6]==$this->symbols['player']
                || $this->field[0]==$this->symbols['player'] && $this->field[8]==$this->symbols['player'])
            {
                $this->response['id'] = 1+2*rand(0,3);
            }

        }
    }
    public function makeMove()
    {

        if($this->checkPlayerWin())
        {
            $this->response['result'] =  'win';

        }
        else if ($this->botIsSmart(98) && ($cell = $this->getBotWinningCell()) )
        {
            $this->response['id'] =  $cell;
            $this->response['result'] = 'lose';
        }
        else if($this->botIsSmart(95) && ($cell = $this->getPlayerWinningCell() ))
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
        if($this->botIsSmart() )
        {
            $this->specialEarlyStrategy();
        }
        if($this->checkBotWin())
        {
            $this->response['result'] =  'lose';
        }
        if($this->response['id']==-1 && $this->response['result']=='')  {
            $this->response['id'] = $this->getSomeValuableCell();
        }

    }

    public function updateLevel(){
        $result = $this->response['result'];

        if($result=='' || $result=='draw') return;

        if($result=='lose' && $this->response['level']==1) return;

        if($result=='win') $this->response['level']++;

        if($result=='lose')  $this->response['level']--;

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


}
