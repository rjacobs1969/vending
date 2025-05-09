<?php

namespace App\Auth\Repository;

use InvalidArgumentException;

use App\Auth\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

use App\Shared\Doctrine\Types\BinaryUuidType;
use Symfony\Component\Uid\Uuid;
use Doctrine\DBAL\Types\Types;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method PasswordAuthenticatedSubjectEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordAuthenticatedSubjectEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordAuthenticatedSubjectEntity[]    findAll()
 * @method PasswordAuthenticatedSubjectEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function loadUserByIdentifier(string $usernameOrUuid): ?User
    {
        $entityManager = $this->getEntityManager();

        try {
            $uuid = new Uuid($usernameOrUuid, true);
        } catch(InvalidArgumentException $e) {
            $uuid = null;
        }

        if ($uuid) {
            $query = sprintf(
                'SELECT u
                    FROM %s u
                    WHERE u.uuid = :param',
                User::class
            );
            $param = $uuid;
            $type = BinaryUuidType::NAME;
        } else {
            $query = sprintf(
                'SELECT u
                    FROM %s u
                    WHERE u.username = :param',
                User::class
            );
            $param = $usernameOrUuid;
            $type = Types::STRING;
        }



        return $entityManager->createQuery($query)
            ->setParameter('param', $param, $type)
            ->getOneOrNullResult();
    }

}
