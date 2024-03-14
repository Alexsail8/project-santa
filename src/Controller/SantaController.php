<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Employee;
use App\Service\EmailSenderService;
use Symfony\Component\Form\FormFactoryInterface;

class SantaController extends AbstractController
{
    private $entityManager;
    private $formFactory;
    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    #[Route('/santa', name: 'app_santa')]
    public function index(): Response
    {
        return $this->render('santa/santa_form.html.twig');
    }

    /**
     * @Route("/send-email", name="send_email", methods={"POST"})
     */
    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(Request $request, EmailSenderService $emailSenderService): Response
    {
        $formData = $request->request->all();

        if (!empty($formData)) {
            $employees = $this->entityManager->getRepository(Employee::class)->findAll();

            if(!$employees) {
                $this->addFlash('error', 'Список участников отсутсвует!');
            }
            shuffle($employees);
            foreach ($employees as $key => $employee) {
                $nextEmployee = $employees[($key + 1) % count($employees)];

                $emailTo = $employee->getEmail();
                $message = 'Уважаемый (ая) '.$employee->getName(). ', Вы должны поздравить ' . $nextEmployee->getName() . ' и подарить подарок!';

                if ($emailSenderService->sendEmail($emailTo, 'Тайный Санта', $message)) {
                    $this->addFlash('success', 'Письма успешно отправлены');
                }
            }
        }

        return $this->redirectToRoute('app_santa');
    }

}
