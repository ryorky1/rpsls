<?php

namespace AppBundle\Controller;

use AppBundle\Form\RoundType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * GameController
 * 
 * @Route("/game")
 */
class GameController extends Controller
{
    /**
     * @Route("/dashboard/{identifier}", name="game_dashboard")
     */
    public function dashboardAction(Request $request)
    {
        //get the game identifier
        $gameIdentifier = $request->get('identifier');
        //get the game object from the session
        if (!$this->container->get('session')->has($gameIdentifier)) {
            return $this->redirectToRoute('homepage');
        }
        $game = $this->container->get('session')->get($gameIdentifier);
        $form = $this->createForm(RoundType::class, null, array(
            'action' => $this->generateUrl('game_dashboard', array('identifier' => $gameIdentifier)),
            'method' => 'POST',
            'game_identifier' => $gameIdentifier,
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            //get Sign - transformed
            $playedSign = $data['sign'];
            //play round
            $roundResult = $this->container->get('game_round.manager')->playRound($gameIdentifier, $playedSign);

            if (true == $game->isGameFinished()) {
                return $this->redirectToRoute('finish_game', array('identifier' => $gameIdentifier));
            }
            return $this->render(
                ':game:dashboard.html.twig',
                [
                    'game' => $game,
                    'form' => $form->createView(),
                    'roundResult' => $roundResult
                ]
            );
        }

        return $this->render(
            ':game:dashboard.html.twig',
            [
                'game' => $game,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * finishGameAction
     * Show game result and destroy the game
     * 
     * @Route("/{identifier}/stop", name="finish_game")
     */
    public function finishGameAction(Request $request)
    {
        $game = $this->container->get('session')->get($request->get('identifier'));
        $this->container->get('game.manager')->destroyGameSession($request->get('identifier'));

        return $this->render(
            ':game:finish.html.twig',
            [
                'game' => $game,
            ]
        );
    }
}
