Bowling score calculator
===

This little class calculates your final score in an American ten-pin bowling game, based on the rules described [here](http://www.topendsports.com/sport/tenpin/scoring.htm). The expected input is in the form of the following characters:

```
[1-9] number of pins struck down
/     spare
X     strike
-     miss
```

So, for example the following sequence would mean:

    437/9-X11

The player hit 4 pins, and then 3 pins in the first frame, then hit 7 pins, and 3 pins (resulting in a spare) in the second frame, 9 pins and nothing in the third, and hitting a strike in the fourth, then hitting one pins two times in the fifth, resulting in the total score of 49.

Example usage
---

```php
<?php
use KNorbert\ScoreLexer;
use KNorbert\ScoreParser;

$parser = new ScoreParser(new ScoreLexer());
$parser->calculate('437/9-X11');
```

The class does not validate the input, nor syntactically, nor length-wise. Written in PHP 5.5, but it should work (however not tested) with >=5.3.

Tests
---

Tests are included in the Tests/ folder, to run them install the dependencies with:

```
php composer.phar install --dev
```

and run PHPUnit with (while in the root):

```
./vendor/bin/phpunit
```
