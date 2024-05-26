<?php

declare(strict_types=1);

namespace App\UI\Controller\Traits;

use App\Domain\Entity\Enum\BonusTypeEnum;
use App\UseCase\CreateDepartment\CreateDepartmentCommand;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\Constraints as Assert;

trait DepartmentControllerTraits
{
    private function createDepartment(array $parameters): void
    {
        $departmentName = $parameters['departmentName'];
        $bonusType = $parameters['bonusType'];
        $bonusFactor = (int)$parameters['bonusFactor'];

        try {
            $command = new CreateDepartmentCommand(
                $departmentName,
                $bonusType,
                $bonusFactor
            );
            $this->commandBus->dispatch($command);

            $this->addFlash('success', 'New Department Created!');
        } catch (ValidationFailedException) {
            $this->addFlash('error', 'Department with that name already exists');
        }
    }

    private function buildForm(): FormInterface
    {
        return $this->createFormBuilder()
            ->add('departmentName', TextType::class)
            ->add('bonusType', EnumType::class, [
                'expanded' => true,
                'class' => BonusTypeEnum::class
            ])
            ->add('bonusFactor', NumberType::class, [
                'constraints' => [
                    new Assert\Range([
                        'min' => 0,
                        'max' => 1000,
                        'minMessage' => 'The bonus factor must be between {{ min }} and {{ max }}',
                        'maxMessage' => 'The bonus factor must be between {{ min }} and {{ max }}',
                        'invalidMessage' => 'The bonus factor must be a number',
                    ]),
                ],
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                /** @var array $data */
                $data = $event->getData();
                $form = $event->getForm();
                if ('fixed' === $data['bonusType']) {
                    $form->add('bonusFactor', NumberType::class, [
                        'constraints' => [
                            new Assert\Range([
                                'min' => 0,
                                'max' => 1000,
                                'minMessage' => 'The bonus factor must be between {{ min }} and {{ max }}',
                                'maxMessage' => 'The bonus factor must be between {{ min }} and {{ max }}',
                                'invalidMessage' => 'The bonus factor must be a number',
                            ]),
                        ],
                    ]);
                } elseif ('percentage' === $data['bonusType']) {
                    $form->add('bonusFactor', NumberType::class, [
                        'constraints' => [
                            new Assert\Range([
                                'min' => 0,
                                'max' => 100,
                                'minMessage' => 'The bonus factor must be between {{ min }} and {{ max }}',
                                'maxMessage' => 'The bonus factor must be between {{ min }} and {{ max }}',
                                'invalidMessage' => 'The bonus factor must be a number',
                            ]),
                        ],
                    ]);
                }
            })
            ->getForm();
    }
}
