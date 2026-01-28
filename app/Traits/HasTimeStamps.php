<?php

namespace App\Traits;

use AllowDynamicProperties;
use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

#[HasLifecycleCallbacks]
Trait HasTimeStamps
{
    #[Column(name: 'created_at')]
    private Datetime $createdAt;
    #[Column(name: 'updated_at')]
    private Datetime $updatedAt;
    #[PrePersist, PreUpdate]
    public function setTimeStamps(LifecycleEventArgs $args): void
    {
        if (! isset($this->createdAt))
            $this->createdAt = new DateTime();

        $this->updatedAt = new DateTime();
    }
}