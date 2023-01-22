<?php

namespace App\Model\Filesystem;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

class Directory extends Resource
{
    #[Groups(['filesystem:read'])]
    private Collection $childs;

    public function __construct()
    {
        $this->childs = new ArrayCollection();
    }

    public function getChilds(): Collection
    {
        return $this->childs;
    }

    public function addChild(Resource $child): self
    {
        if (!$this->childs->contains($child)) {
            $this->childs[] = $child;
        }

        return $this;
    }

    public function removeChild(Resource $child): self
    {
        $this->childs->removeElement($child);

        return $this;
    }
}
