<?
class Transform {
    private $str, $result;

    public function revertCharacters($str=''): string {
        if(strlen($str) === 0) return '';

        $this->str = $str; 
        $this->result = array(
            'TEXT'=>'',
        );
        foreach($arr=explode(" ", $this->str) as $k=>$v) {
            if(mb_strlen($v)) {
                $letters =  preg_split('//u', $v, -1, PREG_SPLIT_NO_EMPTY);
                $position = 0;

                for($i = count($letters)-1; $i >= 0; $i--) {
                    if(preg_match('/[^\w]/iu', $letters[$i])) {
                        $this->result[$k][$i] = $letters[$i];
                    } else {

                        $bias =$position;
                        if($this->result[$k][$position]) $bias = $position+1;

                        $this->result[$k][$bias] = $letters[$i];
                        $position++;
                    }
                }
    
                foreach($letters as $key => $val) {
                    switch($this->registerCheck(array($val, $this->result[$k][$key]))) {
                        case 1:
                            $this->result[$k][$key] = mb_strtoupper($this->result[$k][$key]);
                            break;
                        case 2:
                            $this->result[$k][$key] = mb_strtolower($this->result[$k][$key]);
                            break;
                    } 
                } 
            } else {
                $this->result[$k][] = '';
            }
    
            if(count($arr)-1 != $k) $this->result[$k][] = ' ';
            ksort($this->result[$k]);
            
            $this->result['TEXT'] .= implode($this->result[$k]);
        }
        return $this->result['TEXT'];
    }

    private function registerCheck($arr) {
        if($arr[0] != $arr[1]) {
            if(preg_match('/[A-ZА-ЯЁ]/u', $arr[0], $m)) {
                return 1;
            } else {
                return 2;
            }
        }
        return false;
    }
}