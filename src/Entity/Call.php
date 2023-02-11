<?php

namespace App\Entity;

use App\Repository\CallRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CallRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Call
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CallStatus $status = null;

    #[
        ORM\Column(
            type: Types::DATETIME_MUTABLE,
            options: ['comment' => 'Дата/время звонка']
        )
    ]
    private ?DateTimeInterface $dateCreate = null;

    #[ORM\ManyToOne(inversedBy: 'call')]
    private ?Friend $friend = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?CallStatus
    {
        return $this->status;
    }

    public function setStatus(?CallStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDateCreate(): ?DateTimeInterface
    {
        return $this->dateCreate;
    }

    #[ORM\PrePersist]
    public function setDateCreate(): self
    {
        $this->dateCreate = new DateTime();

        return $this;
    }

    public function getFriend(): ?Friend
    {
        return $this->friend;
    }

    public function setFriend(?Friend $friend): self
    {
        $this->friend = $friend;

        return $this;
    }
}
