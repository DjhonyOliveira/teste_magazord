<?php

namespace Src\Model;

use Src\Enum\EnumTipoContato;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo de contato
 * @author Djonatan R. de Oliveira
 * @package App
 * @subpackage Model
 */
#[ORM\Entity]
#[ORM\Table(name: 'contato')]
class ModelContato
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::INTEGER)]
    private int $tipo;

    #[ORM\Column(type: Types::STRING, length:255)]
    private string $descricao;

    #[ORM\ManyToOne(targetEntity: ModelPessoa::class, inversedBy: 'contatos')]
    #[ORM\JoinColumn(name: 'idPessoa', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ModelPessoa $pessoa;

    /**
     * Retorna o id do contato
     * @return int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retorna o tipo do contato
     * @return EnumTipoContato
     */
    public function getTipo(): EnumTipoContato
    {
        return EnumTipoContato::from($this->tipo);
    }

    /**
     * Seta o tipo do contato
     * @param EnumTipoContato $tipo
     * @return self
     */
    public function setTipo(EnumTipoContato $tipo)
    {
        $this->tipo = $tipo->value;

        return $this;
    }

    /**
     * Retorna a descrição do contato
     * @return string
     */ 
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Seta a descricao do contato
     * @param string $descricao
     * @return self
     */ 
    public function setDescricao(string $descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Retorna a pessoa do contato
     * @return ModelPessoa
     */ 
    public function getPessoa(): ModelPessoa
    {
        return $this->pessoa;
    }

    /**
     * Seta a pessoa do contato
     * @param ModelPessoa $pessoa
     * @return  self
     */
    public function setPessoa(ModelPessoa $pessoa)
    {
        $this->pessoa = $pessoa;

        return $this;
    }

}