<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
final class Version20240105111508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE department (id UUID NOT NULL, department_name VARCHAR(255) NOT NULL, bonus_type VARCHAR(255) NOT NULL, bonus_factor INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN department.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE employee (id UUID NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, employment_date DATE NOT NULL, department_id UUID NOT NULL, salary_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN employee.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN employee.employment_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN employee.department_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN employee.salary_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE salary (id UUID NOT NULL, base_salary INT NOT NULL, bonus_salary INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN salary.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE salary');
    }
}
