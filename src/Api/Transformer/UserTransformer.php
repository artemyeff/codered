<?php
declare(strict_types=1);

namespace App\Api\Transformer;

use App\Entity\User;
use League\Fractal\Resource\Collection;

/**
 * Class UserTransformer
 * @package App\Api\Transformer
 */
final class UserTransformer extends AbstractTransformer
{
    protected $availableIncludes = [
        'news',
    ];

    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user): array
    {
        return [
            'id' => $user->getId(),
            'login' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'fullName' => $user->getFullName(),
        ];
    }

    /**
     * @param User $user
     * @return Collection
     */
    public function includeNews(User $user): Collection
    {
        return $this->collection($user->getNews(), new NewsTransformer(), '');
    }
}
