<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\UI\Controller\Traits\EmployeeControllerTraits;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class EmployeeController extends AbstractController
{
    use EmployeeControllerTraits;

    public function __construct(
        private readonly MessageBusInterface $commandBus
    ) {
    }
    #[Route('/employee', name: 'app_add_employee')]
    public function index(Request $request): Response
    {
        $form = $this->buildForm();

        if ($request->isMethod('POST')) {
            $form->submit($request->request->all($form->getName()));

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var array $parameters */
                $parameters = $request->request->all()['form'];

                $salaryId = $this->createSalary($parameters);
                $this->createEmployee($parameters, $salaryId);


                return $this->redirectToRoute('app_add_employee');
            }
        }

        return $this->render('employee/employee.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
