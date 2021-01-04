<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobRepository::class)
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $requirements;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description_paragraphs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="date")
     */
    private $fetch_date;

    /**
     * @ORM\Column(type="date")
     */
    private $last_update;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $workplace_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $salary;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getRequirements(): ?string
    {
        return $this->requirements;
    }

    public function setRequirements(?string $requirements): self
    {
        $this->requirements = $requirements;

        return $this;
    }

    public function getDescriptionParagraphs(): ?string
    {
        return $this->description_paragraphs;
    }

    public function setDescriptionParagraphs(?string $description_paragraphs): self
    {
        $this->description_paragraphs = $description_paragraphs;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getFetchDate(): ?\DateTimeInterface
    {
        return $this->fetch_date;
    }

    public function setFetchDate(\DateTimeInterface $fetch_date): self
    {
        $this->fetch_date = $fetch_date;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->last_update;
    }

    public function setLastUpdate(\DateTimeInterface $last_update): self
    {
        $this->last_update = $last_update;

        return $this;
    }

    public function getWorkplaceName(): ?string
    {
        return $this->workplace_name;
    }

    public function setWorkplaceName(?string $workplace_name): self
    {
        $this->workplace_name = $workplace_name;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(?string $salary): self
    {
        $this->salary = $salary;

        return $this;
    }
}
