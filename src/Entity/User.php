<?php

namespace App\Entity;

use App\Component\Interface\Controller\ControllerResponseInterface;
use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[
    ORM\Index(
        name: 'user_sessionId',
        columns: ['session_id']
    )
]
#[
    ORM\Index(
        name: 'user_externalId',
        columns: ['external_id']
    )
]
#[ORM\HasLifecycleCallbacks]
class User implements ControllerResponseInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[
        ORM\Column(
            type: Types::STRING,
            length: 30,
            options: ['comment' => 'Идентификатор сессии']
        )
    ]
    private ?string $sessionId = null;

    #[
        ORM\Column(
            type: Types::BIGINT,
            options: ['comment' => 'Внешний идентификатор пользователя']
        )
    ]
    private ?int $externalId = null;

    #[
        ORM\Column(
            type: Types::TEXT,
            options: ['comment' => 'Специальный ключ доступа']
        )
    ]
    private ?string $accessToken = null;

    #[
        ORM\Column(
            type: Types::DATETIME_MUTABLE,
            options: ['comment' => 'Дата/время добавления записи']
        )
    ]
    private ?DateTimeInterface $dateCreate = null;

    #[
        ORM\OneToMany(
            mappedBy: 'user',
            cascade: ['persist', 'remove'],
            targetEntity: Friend::class
        )
    ]
    private Collection $friend;

    public function __construct()
    {
        $this->friend = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
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

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

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

    /**
     * @return Collection<int, Friend>
     */
    public function getFriend(): Collection
    {
        return $this->friend;
    }

    public function addFriend(Friend $friend): self
    {
        if (!$this->friend->contains($friend)) {
            $this->friend->add($friend);
            $friend->setUser($this);
        }

        return $this;
    }

    public function removeFriend(Friend $friend): self
    {
        if ($this->friend->removeElement($friend)) {
            if ($friend->getUser() === $this) {
                $friend->setUser(null);
            }
        }

        return $this;
    }
}
