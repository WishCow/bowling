<?php

namespace KNorbert\Tests;
use KNorbert\ScoreLexer;
use KNorbert\ScoreParser;
use PHPUnit_Framework_TestCase;

class ScoreParserTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider scoreProvider
     */
    public function testCalculations($input, $expected) {
        $parser = new ScoreParser(new ScoreLexer());
        $this->assertEquals($expected, $parser->calculate($input));
    }

    public function scoreProvider() {
        return array(
            array('123144', 15),
            array('------', 0),
            array('5/1244', 22),
            array('X7185', 39),
            array('X6/11', 33),
            array('X6/1/3', 44),
            array('XX66', 60),
            array('437/9-X11', 49),
            array('9-9-9-9-9-9-9-9-9-9-', 90),
            array('5/5/5/5/5/5/5/5/5/5/5', 150),
            array('XXXXXXXXXXXX', 300)
        );
    }
}
