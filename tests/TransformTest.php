<?
require_once __DIR__ . '/../src/Transform.php';

use PHPUnit\Framework\TestCase;

class TransformTest extends TestCase {
    
    private $transform;

    protected function setUp(): void {

        $this->transform = new Transform();
    }

    protected function tearDown(): void {
        $this->transform = NULL;
    }

    /**
     * @dataProvider providerPower
    */
    public function testGetSource($v): void {

        $str = $this->transform->revertCharacters($v);
        $this->assertSame($v,  $this->transform->revertCharacters($str));
    }

    /**
     * @dataProvider providerPower
    */
    public function testregisterCheck($v): void {

        $arrResultWords = explode(" ", $this->transform->revertCharacters($v));
        $arrWords = explode(" ", $v);

        foreach($arrWords as $ke=>$val) {
            
            $val = preg_replace('/[^a-zа-яё\d]/ui', '', $val);
            $arrResultLetters = preg_split('//u',  preg_replace('/[^a-zа-яё\d]/ui', '', $arrResultWords[$ke]), -1, PREG_SPLIT_NO_EMPTY);
            $arrLetters = preg_split("//u", $val, -1, PREG_SPLIT_NO_EMPTY);

            foreach($arrLetters as $key=>$value) {
                if(preg_match('/[A-ZА-ЯЁ]/u', $value, $m)) {
                    $this->assertSame(mb_strtolower($value),   mb_strtolower($arrResultLetters[count($arrResultLetters)-$key-1]));
                }
            }
        }
    }

    /**
     * @dataProvider providerPower
    */
    public function testPunctuationCheck($v): void {

        $arrResultLetters = preg_split('//u',  $this->transform->revertCharacters($v), -1, PREG_SPLIT_NO_EMPTY);
        $arrLetters = preg_split('//u',  $v, -1, PREG_SPLIT_NO_EMPTY);

        foreach($arrLetters as $key=>$val) {
            if(preg_match('/[^a-zа-яё\d]/ui', $val, $m)) {
                $this->assertSame($val, $arrResultLetters[$key]);
            }
        }

    }

    public function providerPower(): iterable {
        return array(
            //array('123456789', ''),
            array('Привет! Давно не виделись.', ''),
            array('ПривеТ. Давно, нЕ виделись!', ''),
            array('Прив.ет. давно не виделисЬ!', ''),
            //array('', '')
        );
    }
}