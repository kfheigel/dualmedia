<?php

declare(strict_types=1);

namespace App\UI\Controller\Traits;

use App\Domain\Entity\Department;
use App\UseCase\CreateEmployee\CreateEmployeeCommand;
use App\UseCase\CreateSalary\CreateSalaryCommand;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

trait EmployeeControllerTraits
{
    private function createEmployee(array $parameters, Uuid $salaryId): void
    {
        $name = $parameters['name'];
        $surname = $parameters['surname'];
        $departmentId = Uuid::fromString($parameters['departmentName']);
        $employmentDate = $parameters['employmentDate'];

        /** @var DateTimeInterface $employmentDay */
        $employmentDay = (new DateTimeImmutable())->createFromFormat('Y-m-d', $employmentDate);

        try {
            $employeeCommand = new CreateEmployeeCommand(
                $name,
                $surname,
                $departmentId,
                $salaryId,
                $employmentDay
            );
            $this->commandBus->dispatch($employeeCommand);

            $this->addFlash('success', 'New Employee Created!');
        } catch (ValidationFailedException) {
            $this->addFlash('error', 'Creating new employee failed');
        }
    }

    private function createSalary(array $parameters): Uuid
    {
        $baseSalary = (int)$parameters['baseSalary'];

        $salaryCommand = new CreateSalaryCommand(
            $salaryId = Uuid::v4(),
            $baseSalary
        );
        $this->commandBus->dispatch($salaryCommand);

        return $salaryId;
    }

    private function buildForm(): FormInterface
    {
        return $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('departmentName', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'departmentName'
            ])
            ->add('baseSalary', NumberType::class, [
                'constraints' => [
                    new Assert\Range([
                        'min' => 0,
                        'minMessage' => 'The bonus factor must be above {{ min }}',
                        'invalidMessage' => 'The bonus factor must be a number',
                    ]),
                ],
            ])
            ->add('employmentDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->getForm();
    }
}
