<?php
namespace Devoir;

if (!defined('YES')) {
    define('YES', true);
}
if (!defined('Yes')) {
    define('Yes', true);
}
if (!defined('yes')) {
    define('yes', true);
}
if (!defined('NO')) {
    define('NO', false);
}
if (!defined('No')) {
    define('No', false);
}
if (!defined('no')) {
    define('no', false);
}

if(!defined('SEC_SECOND'))
    define('SEC_SECOND', 1);
if(!defined('SEC_MINUTE'))
    define('SEC_MINUTE', 60);
if(!defined('SEC_HOUR'))
    define('SEC_HOUR', 3600);
if(!defined('SEC_DAY'))
    define('SEC_DAY', 86400);
if(!defined('SEC_WEEK'))
    define('SEC_WEEK', 604800);
if(!defined('SEC_MONTH'))
    define('SEC_MONTH', 2592000);
if(!defined('SEC_YEAR'))
    define('SEC_YEAR', 31536000);