<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Infrastructure\Repository\EmployeeRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $surname;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeInterface $employmentDate;

    #[ORM\Column(type: 'uuid')]
    private Uuid $departmentId;

    #[ORM\Column(type: 'uuid')]
    private Uuid $salaryId;

    public function __construct(
        string $name,
        string $surname,
        Uuid $departmentId,
        Uuid $salaryId,
        DateTimeInterface $employmentDate,
        ?Uuid $id = null
    ) {
        $this->name = $name;
        $this->surname = $surname;
        $this->departmentId = $departmentId;
        $this->salaryId = $salaryId;
        $this->employmentDate = $employmentDate;
        $this->id = $id ?? Uuid::v4();
    }
    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getEmploymentDate(): DateTimeInterface
    {
        return $this->employmentDate;
    }

    public function setEmploymentDate(DateTimeInterface $employmentDate): void
    {
        $this->employmentDate = $employmentDate;
    }

    public function getDepartmentId(): Uuid
    {
        return $this->departmentId;
    }

    public function setDepartmentId(Uuid $departmentId): void
    {
        $this->departmentId = $departmentId;
    }

    public function getSalaryId(): Uuid
    {
        return $this->salaryId;
    }

    public function setSalaryId(Uuid $salaryId): void
    {
        $this->salaryId = $salaryId;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
