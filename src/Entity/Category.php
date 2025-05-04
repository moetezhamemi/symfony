<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'category')]
    private Collection $articles;

    /**
     * @var Collection<int, CategotySearch>
     */
    #[ORM\OneToMany(targetEntity: CategotySearch::class, mappedBy: 'category')]
    private Collection $categotySearches;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->categotySearches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CategotySearch>
     */
    public function getCategotySearches(): Collection
    {
        return $this->categotySearches;
    }

    public function addCategotySearch(CategotySearch $categotySearch): static
    {
        if (!$this->categotySearches->contains($categotySearch)) {
            $this->categotySearches->add($categotySearch);
            $categotySearch->setCategory($this);
        }

        return $this;
    }

    public function removeCategotySearch(CategotySearch $categotySearch): static
    {
        if ($this->categotySearches->removeElement($categotySearch)) {
            // set the owning side to null (unless already changed)
            if ($categotySearch->getCategory() === $this) {
                $categotySearch->setCategory(null);
            }
        }

        return $this;
    }
}
