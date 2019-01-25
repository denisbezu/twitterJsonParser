<?php
/**
 * Created by PhpStorm.
 * User: denys
 * Date: 22.01.19
 * Time: 21:24
 */

namespace App\Service;


use function Sodium\add;
use Symfony\Component\VarDumper\VarDumper;

class TweetParser
{
    protected $tweetJsonProcessor;

    public function __construct(TweetJsonProcessor $jsonProcessor)
    {
        $this->tweetJsonProcessor = $jsonProcessor;
    }

    /**
     * Parse json line by line in path
     * @param $path
     * @throws \Exception
     */
    public function parseTweets($path)
    {
        $results = array(AddResult::ADDED => 0, AddResult::SKIPPED => 0, AddResult::FAILED => 0);
        $file = fopen($path, 'r');
        ob_start();
        while (!feof($file)) {
            $addResult = $this->tweetJsonProcessor->processInput(fgets($file), true);
            switch ($addResult->getResult()) {
                case AddResult::ADDED:
                    $results[AddResult::ADDED]++;
                    break;
                case AddResult::FAILED:
                    $results[AddResult::FAILED]++;
                    break;
                case AddResult::SKIPPED:
                    $results[AddResult::SKIPPED]++;
                    break;
            }
            echo 'Tweet processed: ' . $addResult->getResultId() . '</br>';
            ob_flush();
            flush();
        }
        ob_end_flush();
        fclose($file);
        VarDumper::dump($results);
        die();
    }
}