<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\UI\Controller\Traits\DepartmentControllerTraits;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class DepartmentController extends AbstractController
{
    use DepartmentControllerTraits;

    public function __construct(
        private readonly MessageBusInterface $commandBus
    ) {
    }

    #[Route('/department', name: 'app_add_department')]
    public function index(Request $request): Response
    {
        $form = $this->buildForm();

        if ($request->isMethod('POST')) {
            $form->submit($request->request->all($form->getName()));

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var array $parameters */
                $parameters = $request->request->all()['form'];
                $this->createDepartment($parameters);

                return $this->redirectToRoute('app_add_department');
            }
        }

        return $this->render('department/department.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
