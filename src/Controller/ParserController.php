<?php

namespace App\Controller;

use App\Service\TweetParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ParserController extends AbstractController
{
    /**
     * @Route("/parser", name="parser")
     * @param TweetParser $parser
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(TweetParser $parser)
    {
        $parser->parseTweets();
        return $this->render('parser/index.html.twig', [
            'controller_name' => 'ParserController',
        ]);
    }
}
