<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Service\TriService;
use App\Form\UserType;
use App\Entity\Theater;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     *Create Index user

     * @Route("/index", name="user_index", methods={"GET"})
     * @Route("/index/{champ}/{sens}", name="user_index", methods={"GET"}, defaults={"champ":"" , "sens":""})

     * @IsGranted("ROLE_ADMIN")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(string $champ, string $sens, UserRepository $userRepository, TriService $tri): Response
    {
        if ($champ != "") {
            $users = $tri->tri($champ, $sens);
        } else {
            $users = $userRepository->findAll();
        }
        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }
    /**
     * Create New user
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function new(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $theater = new Theater();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Haschage Password
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $user->setEmail($request->request->get('registration')['User']['email']);
            // Set ROLE_THEATER for every new users
            $user->setRoles(['ROLE_THEATER']);

            $manager->persist($user);
            dump($user);
            $email = $user->getEmail();
            dump($email);
            $theater->setName($request->request->get('registration')['theater']['name'])
                    ->setEmail($email)
                    ->setUser($user);

            $manager->persist($theater);
            $manager->flush();

//             return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'theater' => $theater
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     * @IsGranted("ROLE_THEATER")
     * @param User $user
     * @param Theater $theater
     * @return Response
     */
    public function show(User $user, Theater $theater): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'theater' => $theater
        ]);
    }


    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_THEATER")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
