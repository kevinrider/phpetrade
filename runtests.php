<?php

//First setup E*Trade Tokens if not already setup
$token_file = dirname(__FILE__) . "/src/tokens.inc";
$run_auth = true;
if(file_exists($token_file))
{
    $last_access = fileatime($token_file);
    if($last_access != false)
    {
        $diff = time() - $last_access;
        if($diff <= 7200)
        {
            $run_auth = false;
        }
    }
}

if($run_auth)
{
    $command = "php " . dirname(__FILE__) . "/auth.php";
    system($command);
}

//Now run PHPUnit Tests against live API.
if(file_exists($token_file))
{
    $command = "php " . dirname(__FILE__) . "/vendor/bin/phpunit --testdox tests";
    system($command);
}
