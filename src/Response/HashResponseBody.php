<?php

namespace App\Response;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

class HashResponseBody
{
    #[Groups(['show_item', 'show_all'])]
    private string $item;

    /**
     * @var string[]|null
     */
    #[Groups(['show_all'])]
    private ?array $collisions;

    public function getItem(): string
    {
        return $this->item;
    }

    public function setItem(string $item): void
    {
        $this->item = $item;
    }

    /**
     * @return string[]
     */
    public function getCollisions(): array
    {
        return $this->collisions;
    }

    public function setCollision(string $collision): void
    {
        $this->collisions[] = $collision;
    }

    #[Ignore]
    public function isCollision(): bool
    {
        return !empty($this->collisions);
    }
}
