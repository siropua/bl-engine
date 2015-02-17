#! /bin/sh

find . -name \*.php | cut -d. -f1,2 | xargs -I {} mv -v {}.php {}.class.php
