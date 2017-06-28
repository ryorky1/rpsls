<?php

namespace AppBundle\Controller;

use AppBundle\Form\StartGame;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\StartGameType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Route("/{username}", name="restart_homepage")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(StartGameType::class, null, array(
                'action' => $this->generateUrl('homepage'),
                'method' => 'POST',
                'username' => $request->get('username')
            ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $gameIdentifier = $this->get('game.manager')->startHumanGame($data['username']);
            //init game
            return $this->redirectToRoute('game_dashboard', array('identifier' => $gameIdentifier));
        }
//        var_dump($this->container->get('session'));
        return $this->render('default/index.html.twig', [
                'form' => $form->createView(),
            ]);
    }
    /**
     * @Route("/restart/{identifier}/{username}", name="restart")
     *
     * @param Request $request
     */
    public function restartAction(Request $request)
    {
        if ($request->get('username')) {
            $this->container->get('game.manager')->destroyGameSession($request->get('identifier'));
        }

        return $this->redirectToRoute('restart_homepage', array('username' => $request->get('username')));
    }
}
