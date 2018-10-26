<?php
namespace root;

class Fighter
{

    const MIN_STRENGTH = 1;
    const MAX_STRENGTH = 10;
    const MIN_AGILITY = 1;
    const MAX_AGILITY = 10;
    const MIN_STAMINA = 1;
    const MAX_STAMINA = 10;
    const MIN_HP = 30;
    const MAX_HP = 70;
    const PROPERTIES_COUNT = 20;

    const BASIC_ATTACK_POWER = 0;

    protected $strength = 0;
    protected $agility = 0;
    protected $stamina = 0;
    protected $hp = 0;

    protected $avatar = [
        'x' => 1,
        'y' => 1,
    ];

    public function __construct()
    {
        $this->setProperties();
        $this->setHp();
        $this->selectAvatar();
    }

    public function __get($name) {
        $value = null;
        if(isset($this->$name)) {
            $value = $this->$name;
        }
        return $value;
    }

    private function setProperties() {
        $result = false;
        if($this->strength===0||$this->agility===0||$this->stamina===0) {
            $del = 0;
            $this->strength = self::MIN_STRENGTH; $del+=self::MIN_STRENGTH;
            $this->agility = self::MIN_AGILITY; $del+=self::MIN_AGILITY;
            $this->stamina = self::MIN_STAMINA; $del+=self::MIN_STAMINA;
            for($i=$del;$i<self::PROPERTIES_COUNT;$i++) {
                $r = rand(1,3);
                switch ($r) {
                    case 1:
                        if($this->strength>=self::MAX_STRENGTH) {
                            $i--;
                        } else {
                            $this->strength++;
                        }
                        break;
                    case 2:
                        if($this->agility>=self::MAX_AGILITY) {
                            $i--;
                        } else {
                            $this->agility++;
                        }
                        break;
                    case 3:
                        if($this->stamina>=self::MAX_STAMINA) {
                            $i--;
                        } else {
                            $this->stamina++;
                        }
                        break;
                    default:
                        $i--;
                        break;
                }
            }
            $result = true;
        }
        return $result;
    }

    private function setHp() {
        $result = false;
        if ($this->hp===0&&$this->stamina!==0) {
            $hp_value = self::MIN_HP + $this->stamina * (self::MAX_HP - self::MIN_HP)/(self::MAX_STAMINA - self::MIN_STAMINA+1);
            $this->hp = intval($hp_value);
            $result = true;
        }
        return $result;
    }

    private function selectAvatar() {
        $this->avatar = [
            'x' => rand(1,4),
            'y' => rand(1,3),
        ];
    }

    public function attack() {
        $power = self::BASIC_ATTACK_POWER;
        $power += $this->strength;
        $k = rand(-10, 10);
        $power *= 1+($k*0.01);
        return intval(round($power));
    }

    public function hit($power)
    {
        $power = max(0, $power);
        $r = rand(0,100);
        $r2 = rand(0,100);
        if($r <= $this->agility) {
            $power = 0;
        } elseif ($r2 <= $this->agility*5) {
            $power *= (100-$this->agility*2)/100;
            $power = intval(round($power));
            $hp = $this->hp - $power;
            $this->hp = max(0, $hp);
        } else {
            $hp = $this->hp - $power;
            $this->hp = max(0, $hp);
        }
        return $power;
    }


}