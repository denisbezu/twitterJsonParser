<?php
/**
 * Created by PhpStorm.
 * User: denys
 * Date: 22.01.19
 * Time: 21:24
 */

namespace App\Service;


use Symfony\Component\VarDumper\VarDumper;

class TweetParser
{
    protected $tweetJsonProcessor;

    public function __construct(TweetJsonProcessor $jsonProcessor)
    {
        $this->tweetJsonProcessor = $jsonProcessor;
    }

    public function parseTweets()
    {
        $results = array('success' => 0, 'error' => 0);
        $file = fopen(__DIR__ . '/../Data/test.json', 'r');
        while (!feof($file)) {
            $result = $this->tweetJsonProcessor->processInput(fgets($file));
            if ($result) {
                $results['success']++;
            } else {
                $results['error']++;
            }
        }
        fclose($file);
        VarDumper::dump($results);
        die();
    }
}