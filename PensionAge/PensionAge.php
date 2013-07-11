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

  private $PensionData = null;
  public function __construct(PensionData $PensionData) {
    if(!($PensionData InstanceOf PensionData))
      throw new Exception('Ugyldigt data brugt imod PensionAge.');

    $this->PensionData = $PensionData;
  }

  public static function calculatedExpireDate(PensionData $PensionData) {
    $age = new PensionAge($PensionData);
    return $age->getCalculatedExpireDate();
  }

  public function getCalculatedExpireDate() {
    $result = null;
    switch($this->PensionData->type) {
      case 'T':
      case '1':
        $result = new Date($this->PensionData->birthdate);
        $result->year += $this->getCalculatedAge();
        break;

      default:
        $result = $this->PensionData->expire_date;
        break;
    }
    return $result;
  }

  public function getCalculatedAge() {
    $ageEnd = 0;
    switch($this->PensionData->type) {
      case 'T':
        $ageEnd = 120;
        break;

      case '1':
        $this->cache();

        $ageEnd = 67;
        foreach($this->cache as $idx => $PensionAge) {
          if($this->PensionData->birthdate->isAfter($PensionAge->from_date) && $this->PensionData->birthdate->isBefore($PensionAge->to_date)) {
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