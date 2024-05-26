<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Domain\Messenger\MessageBus\QueryBus;
use App\ReadModel\EmployeesPayroll\EmployeesPayrollQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PayrollController extends AbstractController
{
    #[Route('/payroll', name: 'app_payroll', methods: ['GET'])]
    public function showPayroll(QueryBus $queryBus): Response
    {
        $query = new EmployeesPayrollQuery();
        $data = $queryBus->find($query);

        return $this->render('payroll/payroll.html.twig', [
            'employees' => $data,
        ]);
    }
}
