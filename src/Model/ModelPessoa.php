<?php

namespace Src\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo de pessoa
 * @author Djonatan R. de Oliveira
 * @package App
 * @subpackage Model
 */
#[ORM\Entity]
#[ORM\Table(name: 'pessoa')]
class ModelPessoa
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 150)]
    private string $nome;

    #[ORM\Column(type: Types::STRING, length: 11, unique: true)]
    private $cpf;

    #[ORM\OneToMany(targetEntity: ModelContato::class, mappedBy: 'pessoa')]
    private Collection $contatos;

    /**
     * Retorna o id da pessoa
     * @return int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retorna o nome da Pessoa
     * @return string
     */ 
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Seta o nome da pessoa
     * @param string $nome
     * @return  self
     */ 
    public function setNome(string $nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * retorna o cpf da pessoa
     * @return string
     */ 
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Seta o cpf da pessoa
     * @param string $cpf
     * @return self
     */ 
    public function setCpf(string $cpf)
    {
        $this->cpf = (string) preg_replace('/\D/', '', $cpf);;

        return $this;
    }

    /**
     * Adiciona contatos vinculados ao modelo.
     * @param ModelContato $contato
     */
    public function addContato(ModelContato $contato) {
        if(!$this->contatos->contains($contato)){
            $contato->setPessoa($this);
            $this->contatos->add($contato);
        }
    }

    /**
     * Retorna os contatos da pessa
     * @return Collection<int, ModelContato>
     */ 
    public function getContatos(): Collection
    {
        return $this->contatos;
    }
   
}