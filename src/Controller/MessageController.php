<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MessageController
 * @package App\Controller
 * @Route("/api/message")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/store", methods="POST", name="message_store")
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $entityManager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && empty($message->getHoneypot())) {
            $entityManager->persist($message);
            $entityManager->flush();
        }
        return $this->redirectToRoute('home_index');
    }
}
