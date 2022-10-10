<?php

namespace App\Entity;

use App\Repository\ResponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponseRepository::class)]
class Response
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $response = null;

    #[ORM\OneToMany(mappedBy: 'response', targetEntity: Opinion::class)]
    private Collection $opinion;

    public function __construct()
    {
        $this->opinion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): self
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return Collection<int, Opinion>
     */
    public function getOpinion(): Collection
    {
        return $this->opinion;
    }

    public function addOpinion(Opinion $opinion): self
    {
        if (!$this->opinion->contains($opinion)) {
            $this->opinion->add($opinion);
            $opinion->setResponse($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): self
    {
        if ($this->opinion->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getResponse() === $this) {
                $opinion->setResponse(null);
            }
        }

        return $this;
    }
}
