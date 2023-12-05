<?php

namespace App\Entity;

use App\Repository\HashRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HashRepository::class)]
#[Index(columns: ['hashcode'], name: 'hashcode_idx')]
class Hash
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type('string')]
    private ?string $data = null;

    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    private ?string $hashcode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getHashcode(): ?string
    {
        return $this->hashcode;
    }

    public function setHashcode(string $hashcode): static
    {
        $this->hashcode = $hashcode;

        return $this;
    }
}
