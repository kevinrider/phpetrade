<?php

//First setup E*Trade Tokens if not already setup.
$token_file = dirname(__FILE__) . "/src/tokens.inc";
$run_auth = false;
if(file_exists($token_file))
{
    $last_access = fileatime($token_file);
    $last_modified = filemtime($token_file);
    if($last_access != false)
    {
        $diff = time() - $last_access;
        $diffm = time() - $last_modified;
        if($diff >= 7200 && $diffm >= 7200)
        {
            $run_auth = true;
        }
    }
}
else
{
    $run_auth = true;
}

if($run_auth)
{
    $command = "php " . dirname(__FILE__) . "/auth.php";
    system($command);
}

if(file_exists($token_file))
{
    $command = "php " . dirname(__FILE__) . "/vendor/bin/phpunit --coverage-text --debug --testdox --testsuite phpetrade";
    system($command);
}
