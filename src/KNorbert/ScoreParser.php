<?php

namespace KNorbert;

class ScoreParser {

    private $lexer;

    public function __construct(ScoreLexer $lexer) {
        $this->lexer = $lexer;
    }

    public function calculate($input) {
        $this->lexer->setInput($input);
        $score = 0;
        while (!$this->isEndOfGame()) {
            switch ($this->lexer->lookahead['type']) {
                case ScoreLexer::T_NUMBER:
                    $score += $this->calculateOpenFrame();
                    break;
                case ScoreLexer::T_SPARE:
                    $score += $this->calculateSpare();
                    break;
                case ScoreLexer::T_STRIKE:
                    $score += $this->calculateStrike();
                    break;
            }
        }
        return $score;
    }

    private function calculateSpare() {
        $score = 10 - $this->lexer->token['value'];
        $next = $this->lexer->glimpse();
        $score += $next['value'];
        return $score;
    }

    private function calculateOpenFrame() {
        return $this->lexer->lookahead['value'];
    }

    private function isEndOfGame() {
        return $this->isFinalFrame() || !$this->lexer->moveNext();
    }

    private function calculateStrike() {
        $score = 10;
        list($first, $second) = array($this->lexer->peek(), $this->lexer->peek());
        foreach (array($first, $second) as $token) {
            if ($token['type'] === ScoreLexer::T_NUMBER) {
                $score += $token['value'];
            } elseif ($token['type'] === ScoreLexer::T_SPARE) {
                $score += 10 - $first['value'];
            } elseif ($token['type'] === ScoreLexer::T_STRIKE) {
                $score += 10;
            }
        }
        $this->lexer->resetPeek();
        return $score;
    }

    private function isFinalFrame() {
        $current = $this->lexer->lookahead;

        /**
         * If we are currently looking at a spare, check to see if there is only one digit left, which means that the last digit
         * should "only" be counted as a bonus point to the previous frame, and should not be added to the points as a stand alone.
         */
        if ($current['type'] === ScoreLexer::T_SPARE) {
            $first = $this->lexer->peek();
            $second = $this->lexer->peek();
            $this->lexer->resetPeek();
            if ($first && !$second) {
                return true;
            }
        /**
         * Same here for strikes, but look for the next 2 tokens, instead of 1, and make sure they are also strikes.
         */
        } elseif ($current['type'] === ScoreLexer::T_STRIKE) {
            $first = $this->lexer->peek();
            $second = $this->lexer->peek();
            $third = $this->lexer->peek();
            $this->lexer->resetPeek();
            if ($first && $second && !$third &&
                $first['type'] === ScoreLexer::T_STRIKE && $second['type'] === ScoreLexer::T_STRIKE) {
                return true;
            }
        }
        return false;
    }
}
