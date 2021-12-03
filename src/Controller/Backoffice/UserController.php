<?php

namespace App\Controller\Backoffice;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

/**
 * @Route("/user/", name="user_", methods={"GET"})
 */
class UserController extends AbstractController
{
    /**
     * @Route("browse", name="browse", methods={"GET"})
     */
    public function browse(UserRepository $userRepository): Response
    {
        return $this->render('user/browse.html.twig', [
            'user_list' => $userRepository->findAll()
        ]);
    }

    /**
     * @Route("read/{id}", name="read", methods={"GET"}, requirements={"id"="\d+"})
     * 
     * */
    public function read(User $user): Response
    {
        return $this->render('user/read.html.twig', [
            'current_user' => $user,
        ]);
    }

    /**
     * @Route("edit/{id}", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * 
     */
    public function edit(Request $request,ManagerRegistry $managerRegistry, User $user, UserPasswordHasherInterface $passwordHasher): Response
    {
        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManager = $managerRegistry->getManager();

            $clearPassword = $request->request->get('user')['password']['first'];

            if (! empty($clearPassword))
            {
                $hashedPassword = $passwordHasher->hashPassword($user, $clearPassword);
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur dont l'email est : '{$user->getUserIdentifier()}' a bien été mis à jour");

            return $this->redirectToRoute('user_browse');
        }

        $userForm
        ->remove('password')
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class, 
            'required' => false,
            'mapped' => false,
            'first_options'  => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Répéter le mot de passe'],
        ]);

        return $this->render('user/editadd.html.twig', [
            'user_form' => $userForm->createView(),
            'user' => $user,
            'page' => 'edit',
        ]);
    }

    /**
     * @Route("add", name="add", methods={"GET", "POST"})
     */
    public function add(Request $request,ManagerRegistry $managerRegistry, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($user);

            $clearPassword = $request->request->get('user')['password']['first'];

            if (! empty($clearPassword))
            {
                $hashedPassword = $passwordHasher->hashPassword($user, $clearPassword);
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur dont l'email est : '{$user->getUserIdentifier()}' a bien été ajouté");
            return $this->redirectToRoute('user_browse');
        }

        return $this->render('user/editadd.html.twig', [
            'user_form' => $userForm->createView(),
            'page' => 'create',
        ]);
    }

    /**
     * @Route("delete/{id}", name="delete", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        if (empty($user)) 
        {
            $this->addFlash('success', "L'utilisateur dont l'email est : '{$user->getUserIdentifier()}' a bien été supprimé");
        }

        return $this->redirectToRoute('user_browse');
    }
}
