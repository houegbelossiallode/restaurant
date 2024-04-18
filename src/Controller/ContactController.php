<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\MailerService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerService $mailer, EntityManagerInterface $manager): Response
    {
        $contact = new Contact;
        $form = $this->createForm(ContactType::class,$contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $from = $contact->getEmail();
            $to = $from;
            $subject = 'Demande de contact sur votre site ';
            $text = $contact->getSujet();
            $content = $contact->getMessage();
            $mailer->sendEmail(subject: $subject, to: $to, text: $text, content: $content);
            $manager->persist($contact);
            $manager->flush();
            $this->addFlash('success', 'Votre message a été envoyé');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}