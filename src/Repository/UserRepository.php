<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Handler\Request\RequestHandlers;
use App\Repository\Traits\{RelationsTrait, RemoveTrait};
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\{OptimisticLockException, ORMException, Tools\Pagination\Paginator};
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\{
    Exception\UnsupportedUserException,
    User\PasswordUpgraderInterface,
    User\UserInterface
};

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    use RemoveTrait, RelationsTrait;

    private RequestHandlers $requestHandlers;

    /**
     * AlbumRepository constructor.
     * @param ManagerRegistry $registry
     * @param RequestHandlers $requestHandlers
     */
    public function __construct(
        ManagerRegistry $registry,
        RequestHandlers $requestHandlers
    ) {
        parent::__construct($registry, User::class);
        $this->requestHandlers = $requestHandlers;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @return User[]
     */
    public function findAllAuthors(): array
    {
        $builder = $this->createQueryBuilder('u')
            ->select('u')
            ->innerJoin('u.albums', 'albums')
            ->innerJoin('albums.authors', 'authors')
            ->addSelect('albums')
            ->andWhere('albums.is_public = :is_public')
            ->setParameter('is_public', true)
            ->addOrderBy('u.last_name')
            ->addOrderBy('u.first_name')
            ->addOrderBy('u.patronymic');

        return $builder->getQuery()->execute();
    }

    /**
     * @param string[] $relations
     * @return Paginator
     */
    public function findAllForAdmin(array $relations = []): Paginator
    {

        $builder = $this->createQueryBuilder('a');

        $this->requestHandlers->getSortHandler()->handle($builder);

        $this->requestHandlers->getFilterHandler()
            ->handle(static function (string $field, string $value) use ($builder) {
                if ($field === 'fullName') {
                    $builder
                        ->andWhere("lower(concat_ws(' ', a.first_name, a.patronymic, a.last_name)) LIKE lower(:full_name)")
                        ->setParameter('full_name', '%' . $value . '%');
                }
            });

        if (!empty($relations)) {
            $builder = $this->withRelations($builder, $relations);
        }

        return $this->requestHandlers->getPaginationHandler()->handle($builder);
    }
}
