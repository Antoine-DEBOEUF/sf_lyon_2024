<?php

namespace App\Entity;

use App\Entity\Traits\DateTimeTrait;
use App\Entity\Traits\EnableTrait;
use App\Repository\ArticleCommentaryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleCommentaryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ArticleCommentary
{
    use DateTimeTrait;
    use EnableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'articleCommentaries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?article $article = null;

    #[ORM\ManyToOne(inversedBy: 'articleCommentaries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    #[ORM\Column]
    private ?int $note = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $Content): static
    {
        $this->content = $Content;

        return $this;
    }

    public function getArticle(): ?article
    {
        return $this->article;
    }

    public function setArticle(?article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }
}
