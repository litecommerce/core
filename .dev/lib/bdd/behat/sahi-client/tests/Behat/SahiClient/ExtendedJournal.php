<?php

namespace Test\Behat\SahiClient;

use Buzz\History\Journal;

class ExtendedJournal extends Journal
{
    public function getFirst()
    {
        return $this->get(0);
    }

    public function get($num)
    {
        foreach ($this as $i => $val) {
            if ($num === (count($this) - $i - 1)) {
                return $val;
            }
        }
    }
}
