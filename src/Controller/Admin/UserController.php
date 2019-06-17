<?php

namespace App\Controller\Admin;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\Breadcrumb;
use App\Service\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/users")
 *
 * @IsGranted("ROLE_ADMIN")
 *
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class UserController extends AbstractController
{

    /**
     * @var \App\Service\Breadcrumb
     */
    private $breadcrumb;

    /**
     * @var \App\Repository\UserRepository
     */
    private $userRepository;

    /**
     * UserController constructor.
     *
     * @param  \App\Service\Breadcrumb  $breadcrumb
     */
    public function __construct(Breadcrumb $breadcrumb, UserRepository $userRepository)
    {
        $this->breadcrumb = $breadcrumb->addItem("user.manager", "user_index");
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/{page<\d+>}", defaults={"page":1}, name="user_index")
     *
     * @param  int  $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(int $page)
    {
        $users = $this->userRepository->findAllOrderByActive($page);
        $totalUsers = $users->count();

        $maxPages = ceil($totalUsers / USER::ITEMS_PER_PAGE);

        return $this->render("Admin/User/index.html.twig", array(
          "breadcrumb" => $this->breadcrumb->createView(),
          "users" => $users,
          "totalUsers" => $totalUsers,
          "maxPages" => $maxPages,
          "thisPage" => $page
        ));
    }

    /**
     * @Route("/update/{user}", name="user_update")
     *
     * @param  \App\Entity\User  $user
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(User $user, Request $request)
    {
        $this->breadcrumb->addItem("user.update", "user_update");

        $form = $this->createForm(UserType::class, $user)
          ->add("updateAndSave", SubmitType::class, array("label" => "update.and.save"));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->merge($user);
            $em->flush();


            $this->addFlash("success", "Uživatel byl úspěšně upraven");
            return $this->redirectToRoute("user_index");
        }

        return $this->render("Admin/User/update.html.twig", array(
          "breadcrumb" => $this->breadcrumb->createView(),
          "user" => $user,
          "form" => $form->createView(),
        ));
    }

    /**
     * @Route("/deactivate/{user}", name="user_deactivate")
     * @param  \App\Entity\User  $user
     * @param  \App\Service\UserManager  $userManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivate(User $user, UserManager $userManager)
    {
        $result = $userManager->resetVerification($user);
        if($result == "success")
        {
            $this->addFlash("success", "Uživatelský učet bude znovu vyžadovat aktivaci");
            return $this->redirectToRoute("user_index");
        }
        $this->addFlash("danger", $result);
    }

    /**
     * Permanently deletes user
     *
     * @Route("/delete/{user}", name="user_delete")
     * @param  \App\Entity\User  $user
     */
    public function delete(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
    }

    /**
     * @Route("/reset/{user}", name="user_reset", methods={"POST"})
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \App\Entity\User  $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reset(Request $request, User $user, UserManager $userManager)
    {
        $password = $request->request->get('password');
        $password_again = $request->request->get('password_again');
        if($password == $password_again)
        {
            $encodedPassword = $userManager->encodePassword($user,$password);
            $user->setPassword($encodedPassword);

            $em = $this->getDoctrine()->getManager();
            $em->merge($user);
            $em->flush();

            $this->addFlash("success", "Heslo bylo změněno.");
            return $this->redirectToRoute("user_update", ["user" => $user->getId()]);
        } else
        {
            $this->addFlash("danger", "Hesla se neshodují!");
            return $this->redirectToRoute("user_update", ["user" => $user->getId()]);
        }
    }
}