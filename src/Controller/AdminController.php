<?php
/**
 * AdminController.php
 * Created by nicolas for MyWebsite
 * Developed and maintained using PhpStorm
 * Started on févr. 23, 2020 at 15:46:01
 */

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Service\AccountManager;
use App\Service\MessageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @return Response
     * @Route("/", name="admin_index")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @param int $page
     * @param AccountManager $accountManager
     * @return Response
     * @Route("/users/{page?0}", name="admin_users")
     */
    public function users(int $page, AccountManager $accountManager)
    {
        $accountManager->setUserRepository($this->getDoctrine()->getRepository(User::class));
        return $this->render('admin/users.html.twig', [
            'userPaginator' => $accountManager->getUserPage($page)
        ]);
    }

    /**
     * @param int $page
     * @param AccountManager $accountManager
     * @return Response
     * @Route("/user/{page?0}", name="admin_user", requirements={"page"="\d+"})
     */
    public function user(int $page, AccountManager $accountManager)
    {
        return $this->render('admin/user.html.twig', [
            // TODO: Add User Value.
        ]);
    }

    /**
     * @param int $user
     * @param AccountManager $accountManager
     * @return RedirectResponse
     * @Route("/user_delete/{user?0}", name="admin_user_delete", requirements={"user"="\d+"})
     */
    public function user_delete(int $user, AccountManager $accountManager)
    {
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @param int $page
     * @param MessageManager $messageManager
     * @return Response
     * @Route("/messages/{page?0}", name="admin_messages", requirements={"page"="\d+"})
     */
    public function messages(int $page, MessageManager $messageManager)
    {
        $messageManager->setMessageRepository($this->getDoctrine()->getRepository(Message::class));
        return $this->render('admin/messages.html.twig', [
            'messagePaginator' => $messageManager->getMessagePage($page)
        ]);
    }

    /**
     * @param int $message
     * @param MessageManager $messageManager
     * @return Response
     * @Route("/message/{message?0}", name="admin_message", requirements={"message"="\d+"})
     */
    public function message(int $message, MessageManager $messageManager)
    {
        $messageManager->setMessageRepository($this->getDoctrine()->getRepository(Message::class));
        return $this->render('admin/message.html.twig', [
            'message' => $messageManager->get($message)
        ]);
    }

    /**
     * @param int $message
     * @param MessageManager $messageManager
     * @return RedirectResponse
     * @Route("/message_delete/{message?0}", name="admin_message_delete", requirements={"message"="\d+"})
     */
    public function message_delete(int $message, MessageManager $messageManager)
    {
        $messageManager->setEntityManager($this->getDoctrine()->getManager());
        $messageManager->setMessageRepository($this->getDoctrine()->getRepository(Message::class));
        $messageManager->delete($message);
        return $this->redirectToRoute('admin_messages');
    }
}
