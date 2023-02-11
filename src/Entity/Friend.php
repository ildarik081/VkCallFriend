<?php

namespace App\Entity;

use App\Component\Interface\Controller\ControllerResponseInterface;
use App\Repository\FriendRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FriendRepository::class)]
#[
    ORM\Index(
        name: 'friend_externalId',
        columns: ['external_id']
    )
]
class Friend implements ControllerResponseInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[
        ORM\Column(
            type: Types::BIGINT,
            options: ['comment' => 'Внешний идентификатор друга']
        )
    ]
    private ?int $externalId = null;

    #[
        ORM\Column(
            type: Types::STRING,
            length: 80,
            options: ['comment' => 'Имя друга']
        )
    ]
    private ?string $firstName = null;

    #[
        ORM\Column(
            type: Types::STRING,
            length: 80,
            nullable: true,
            options: ['comment' => 'Фамилия друга']
        )
    ]
    private ?string $lastName = null;

    #[ORM\ManyToOne(inversedBy: 'friend')]
    private ?User $user = null;

    #[
        ORM\OneToMany(
            mappedBy: 'friend',
            cascade: ['persist'],
            targetEntity: Call::class
        )
    ]
    private Collection $call;

    public function __construct()
    {
        $this->call = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalId(): ?int
    {
        return $this->externalId;
    }

    public function setExternalId(int $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Call>
     */
    public function getCall(): Collection
    {
        return $this->call;
    }

    public function addCall(Call $call): self
    {
        if (!$this->call->contains($call)) {
            $this->call->add($call);
            $call->setFriend($this);
        }

        return $this;
    }

    public function removeCall(Call $call): self
    {
        if ($this->call->removeElement($call)) {
            if ($call->getFriend() === $this) {
                $call->setFriend(null);
            }
        }

        return $this;
    }
}
