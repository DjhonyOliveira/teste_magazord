<?php

namespace Src\View\Contato;

use Src\Enum\EnumTipoContato;
use Src\Model\ModelContato;
use Src\View\Layout\LayoutBase;

/**
 * View de formulário da rotina de contatos
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage View
 */
class ViewContatoForm extends LayoutBase
{
    const TITULO_PAGINA_INSERIR = 'Criar Contato';
    const TITULO_PAGINA_ALTERAR = 'Alterar Contato';

    private bool $alteracao = false;
    private array $pessoas  = [];

    public function getAlteracao()
    {
        return $this->alteracao;
    }

    public function setAlteracao($alteracao)
    {
        $this->alteracao = $alteracao;

        return $this;
    }

    public function getPessoas()
    {
        return $this->pessoas;
    }

    public function setPessoas(array $pessoas)
    {
        $this->pessoas = $pessoas;

        return $this;
    }

    function __construct(array $aRegistros = [], $bAlteracao = false)
    {
        $this->setRegistros($aRegistros);
        $this->setAlteracao($bAlteracao);
    }

    function montaPagina()
    {
        $this->setTitulo($this->getAlteracao() ? self::TITULO_PAGINA_ALTERAR : self::TITULO_PAGINA_INSERIR);
        $this->setConteudo($this->getHtmlPagina());
        $this->setPathCss('/assets/css/app.css');
        $this->setPathJs('/assets/js/form-ajax.js');

        $this->render();
    }

    function getHtmlPagina()
    {
        $oRegistro = $this->getRegistros();
        $readonly  = $this->getReadOnly() ? 'readonly' : '';
        $disabled  = $this->getReadOnly() ? 'disabled' : '';

        $sDescricao   = htmlspecialchars($oRegistro instanceof ModelContato ? $oRegistro->getDescricao() : '', ENT_QUOTES, 'UTF-8');
        $iPessoaAtual = $oRegistro instanceof ModelContato ? $oRegistro->getPessoa()->getId() : null;
        $iTipoAtual   = $oRegistro instanceof ModelContato ? $oRegistro->getTipo()->value : null;

        $sOpcoesPessoas = '';

        foreach($this->getPessoas() as $oPessoa){
            $sSelected = $oPessoa->getId() === $iPessoaAtual ? 'selected' : '';
            $sNome     = htmlspecialchars($oPessoa->getNome(), ENT_QUOTES, 'UTF-8');

            $sOpcoesPessoas .= "<option value=\"{$oPessoa->getId()}\" {$sSelected}>{$sNome}</option>";
        }

        $sOpcoesTipo = '';

        foreach(EnumTipoContato::cases() as $oTipo){
            $sSelected = $oTipo->value === $iTipoAtual ? 'selected' : '';

            $sOpcoesTipo .= "<option value=\"{$oTipo->value}\" {$sSelected}>{$oTipo->getDescricao()}</option>";
        }

        $sPlaceholderPessoa = $iPessoaAtual === null ? 'selected' : '';
        $sPlaceholderTipo   = $iTipoAtual   === null ? 'selected' : '';

        $sHtml = "
            <section class=\"container-form\">
                <form method=\"post\" class=\"form-pessoa\">
                    <div class=\"campo-form\">
                        <label for=\"idPessoa\">Pessoa</label>
                        <select id=\"idPessoa\" name=\"idPessoa\" class=\"input-texto\" {$disabled} required>
                            <option value=\"\" disabled {$sPlaceholderPessoa}>Selecione...</option>
                            {$sOpcoesPessoas}
                        </select>
                    </div>

                    <div class=\"campo-form\">
                        <label for=\"tipo\">Tipo</label>
                        <select id=\"tipo\" name=\"tipo\" class=\"input-texto\" {$disabled} required>
                            <option value=\"\" disabled {$sPlaceholderTipo}>Selecione...</option>
                            {$sOpcoesTipo}
                        </select>
                    </div>

                    <div class=\"campo-form\">
                        <label for=\"descricao\">Descrição</label>
                        <input type=\"text\" id=\"descricao\" name=\"descricao\" value=\"{$sDescricao}\" {$readonly} class=\"input-texto\" required>
                    </div>";

        if(!$this->getReadOnly()){
            $sHtml .= "<button type=\"submit\" class=\"botao botao-primario\">Salvar</button>";
        }

        $sHtml .= "</form>
                </section>";

        return $sHtml;
    }

}