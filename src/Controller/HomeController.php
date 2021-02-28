<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", methods="GET", name="home_index")
     */
    public function index(): Response
    {
        $form = $this->createForm(MessageType::class, new Message());

        return $this->render('home/index.html.twig', [
            'messageForm' => $form->createView()
        ]);
    }
}
