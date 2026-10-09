<?php

namespace Src\View\Pessoa;

use Src\Model\ModelPessoa;
use Src\View\Layout\LayoutBase;

/**
 * View de formulário da rotina de pessoas
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage View
 */
class ViewPessoaForm extends LayoutBase
{
    const TITULO_PAGINA_INSERIR = 'Criar Pessoa';
    const TITULO_PAGINA_ALTERAR = 'Alterar Pessoa';

    private bool $alteracao = false;

    public function getAlteracao()
    {
        return $this->alteracao;
    }

    public function setAlteracao($alteracao)
    {
        $this->alteracao = $alteracao;

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

        $sNome    = htmlspecialchars($oRegistro instanceof ModelPessoa ? $oRegistro->getNome() : '', ENT_QUOTES, 'UTF-8');
        $sCpf     = htmlspecialchars($oRegistro instanceof ModelPessoa ? $oRegistro->getCpf()  : '', ENT_QUOTES, 'UTF-8');
        $readonly = $this->getReadOnly() ? 'readonly' : '';

        $sHtml = "
            <section class=\"container-form\">
                <form method=\"post\" class=\"form-pessoa\">
                    <div class=\"campo-form\">
                        <label for=\"nome\">Nome</label>
                        <input type=\"text\" id=\"nome\" name=\"nome\" value=\"{$sNome}\" {$readonly} class=\"input-texto\" required>
                    </div>

                    <div class=\"campo-form\">
                        <label for=\"cpf\">CPF</label>
                        <input type=\"text\" id=\"cpf\" name=\"cpf\" value=\"{$sCpf}\" {$readonly} maxlength=\"14\" placeholder=\"000.000.000-00\" class=\"input-texto\" required>
                    </div>";

        if(!$this->getReadOnly()){
            $sHtml .= "<button type=\"submit\" class=\"botao botao-primario\">Salvar</button>";
        }

        $sHtml .= "</form>
                </section>";

        return $sHtml;
    }
    
}