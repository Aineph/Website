<?php
/**
 * WebsiteController.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 01, 2020 at 12:24:16
 */

namespace App\Controller;

use App\Entity\Message;
use App\Form\ContactFormType;
use App\Service\MessageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WebsiteController
 * @package App\Controller
 * @Route("/")
 */
class WebsiteController extends AbstractController
{
    /**
     * @var string
     */
    const ROUTE_WEBSITE_INDEX = 'website_index';

    /**
     * @param Request $request
     * @param MessageManager $messageManager
     * @return Response
     * @Route("/", name="website_index")
     */
    public function index(Request $request, MessageManager $messageManager): Response
    {
        $messageManager->setMessage(new Message());
        $contactForm = $this->createForm(ContactFormType::class, $messageManager->getMessage());

        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $messageManager->setEntityManager($this->getDoctrine()->getManager());
            $messageManager->save($this->getUser());
        }
        return $this->render('website/index.html.twig', [
            'contactForm' => $contactForm->createView()
        ]);
    }
}
