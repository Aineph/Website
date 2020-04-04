<?php
/**
 * WebsiteController.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 01, 2020 at 12:24:16
 */

namespace App\Controller;

use App\Form\ContactFormType;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * The website index route.
     * @var string
     */
    const ROUTE_WEBSITE_INDEX = 'website_index';

    /**
     * The website index template.
     * @var string
     */
    const TEMPLATE_WEBSITE_INDEX = 'website/index.html.twig';

    /**
     * The contact form parameter.
     * @var string
     */
    const CONTACT_FORM_PARAMETER = 'contactForm';

    /**
     * The control for the website index.
     * @param MessageService $messageService
     * @return Response
     * @Route("/", methods="GET", name="website_index")
     */
    public function index(MessageService $messageService): Response
    {
        $contactForm = $this->createForm(ContactFormType::class, $messageService->getMessage());

        return $this->render(self::TEMPLATE_WEBSITE_INDEX, [
            self::CONTACT_FORM_PARAMETER => $contactForm->createView()
        ]);
    }

    /**
     * The control for the website contact.
     * @param Request $request
     * @param MessageService $messageService
     * @return RedirectResponse
     * @Route("/contact", methods="POST", name="website_contact")
     */
    public function contact(Request $request, MessageService $messageService): RedirectResponse
    {
        $contactForm = $this->createForm(ContactFormType::class, $messageService->getMessage());

        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $messageService->create();
        }
        return $this->redirectToRoute(self::ROUTE_WEBSITE_INDEX);
    }
}
