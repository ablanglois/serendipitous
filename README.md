Serendipitous
=============

Finds one sentence in a given text that matches the 5-7-5 syllabic structure of a haiku. So far it only has a language definition for French, but it should be trivial to add more.

This is a side-project devloped for [jdqhaiku](https://github.com/ablanglois/jdqhaiku).

Usage
-----

    $s = new Serendipitous($lang);
    $haiku = $s->find($text);