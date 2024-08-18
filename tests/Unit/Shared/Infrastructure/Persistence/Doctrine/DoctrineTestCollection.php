<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;

/**
 * @extends AbstractLazyCollection<int, object>
 * @implements Selectable<int, object>
 */
class DoctrineTestCollection extends AbstractLazyCollection implements Selectable
{
    /**
     * @param object[] $elements
     * @param Collection<int, object>|null $collection
     */
    public function __construct(
        private readonly array $elements,
        protected ?Collection $collection = null
    ) {
    }

    protected function doInitialize(): void
    {
        $this->collection = new ArrayCollection($this->elements);
    }

    /**
     * @return Collection<int, object>
     */
    public function matching(Criteria $criteria): Collection
    {
        return $this->collection;
    }
}
