<?php

namespace App\Repository;

use App\Component\Exception\RepositoryException;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Получить пользователя по его внешнему идентификатору
     *
     * @param integer $externalId
     * @return User|null
     */
    public function getUserByExternalId(int $externalId): ?User
    {
        return $this->findOneBy(['externalId' => $externalId]);
    }

    /**
     * Получить пользователя по идентификатору сессии
     *
     * @param string $sessionId
     * @return User
     * @throws RepositoryException
     */
    public function getUserBySessionId(string $sessionId): User
    {
        $user = $this->findOneBy(['sessionId' => $sessionId]);

        if (null === $user) {
            throw new RepositoryException(
                message: 'Требуется предоставление доступа к данным ВК',
                code: ResponseAlias::HTTP_BAD_REQUEST,
                responseCode: 'USER_NOT_FOUND',
                logLevel: LogLevel::CRITICAL
            );
        }

        return $user;
    }
}
