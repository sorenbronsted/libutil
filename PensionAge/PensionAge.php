<?php
class PensionAge {
  private $cachefile = 'db.txt';
  private $cache = null;
  private function cache() {
    if(is_array($this->cache))
      return;

    if(!file_exists(__DIR__.'/'.$this->cachefile))
      throw new Exception('Pensions filen findes ikke i '.$this->cachefile);

    $this->cache = array();

    $line = 0;
    if (count($lines = explode("\n", $this->__get_content())) > 0) {
      foreach($lines as $row) { $row = explode(";", $row);
        if($line > 0) {
          $data = array();
          foreach($row as $idx => $val) {
            switch($idx) {
              case 0: $data['from_date'] = Date::parse($val);                  break;
              case 1: $data['to_date'] = Date::parse($val);                    break;
              case 2: $data['age'] = $val;                                     break;
              case 2: $data['increased_age'] = ($val == 'y' ? true : false);   break;
            }
          }
          $this->cache[] = (object)$data;
        }
        $line++;
      }
    }
  }

  private $data = null;
  private $type = null;
  public function __construct($data, $type='1') {
    if(!($data InstanceOf PensionData) && !($data InstanceOf Date)) {
      throw new Exception('Forkert data brugt imod PensionAge');
    }
    $this->data = $data;
    $this->type = $type;
  }

  public static function calculatedExpireDate($data, $type='1') {
    $age = new PensionAge($data, $type);
    return $age->getCalculatedExpireDate();
  }

  public static function calculatedAge($data, $type='1') {
    $age = new PensionAge($data, $type);
    return $age->getCalculatedAge();
  }

  public function getCalculatedExpireDate() {
    $type = ($this->data InstanceOf PensionData ? $this->data->type : $this->type);

    $result = null;
    switch($type) {
      case 'T':
      case '1':
        if($this->data InstanceOf PensionData) {
          $result = new Date($this->data->birthdate);
        } else $result = $this->data;

        $result->year += $this->getCalculatedAge();
        break;

      default:
        if($this->data InstanceOf PensionData) {
          $result = $this->data->expire_date;
        }
        break;
    }
    return $result;
  }

  public function getCalculatedAge() {
    $type = ($this->data InstanceOf PensionData ? $this->data->type : $this->type);
    $birthdate = ($this->data InstanceOf PensionData ? $this->data->birthdate : $this->data);

    $ageEnd = 0;
    switch($type) {
      case 'T':
        $ageEnd = 120;
        break;

      case '1':
        $this->cache();

        $ageEnd = 67;
        foreach($this->cache as $idx => $PensionAge) {
          if($birthdate->isAfter($PensionAge->from_date) && $birthdate->isBefore($PensionAge->to_date)) {
            $ageEnd = $PensionAge->age;
            break;
          }
        }
        break;
    }
    return $ageEnd;
  }

  private function __get_content() {
    return file_get_contents(__DIR__.'/'.$this->cachefile);
  }
}