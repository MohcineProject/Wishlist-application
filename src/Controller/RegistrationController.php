<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            dump($form->isValid()) ;
            if ($form->isValid()){
                // Hacher le mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, plainPassword: $form->get('password')->getData());
                $user->setPassword($hashedPassword);

                // Sauvegarder l'utilisateur
                $entityManager->persist($user);
                $entityManager->flush();

                // Rediriger vers la page de connexion
                return $this->redirectToRoute('login');
            }

            if (!$form->isValid()) {
                dump("I am here ") ; 
                $errors = [];
                foreach ($form->getErrors(true) as $error) {
                    $errors[] = $error->getMessage();
                }
                dump($errors);
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                    'formErrors' => $errors,
                ]);
            }

        }
        else {
            // Si le formulaire n'est pas valide, les erreurs seront disponibles dans la vue
        }

       

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}