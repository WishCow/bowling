<?php

namespace KNorbert;
use Doctrine\Common\Lexer\AbstractLexer;

class ScoreLexer extends AbstractLexer {

    const T_NUMBER = 1;
    const T_STRIKE = 2;
    const T_SPARE = 3;
    const T_MISS = 4;

    public function getType(&$value) {
        if (is_numeric($value)) {
            return self::T_NUMBER;
        }
        switch ($value) {
            case 'X':
                return self::T_STRIKE;
            case '/':
                return self::T_SPARE;
            case '-':
                return self::T_MISS;
        }
    }

    public function getCatchablePatterns() {
        return array('\d', '[\/X\-]');
    }

    public function getNonCatchablePatterns() {
        return array('\s+');
    }
}
