<?php

namespace Src\View\Contato;

use Src\View\Layout\LayoutBase;

/**
 * View de listagem de contatos
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage View
 */
class ViewContato extends LayoutBase
{
    const TITULO_PAGINA = 'Lista de Contatos';
    const OPCOES_FILTRO = [
        'Descrição' => 'descricao',
    ];

    function __construct(array $Registros = [])
    {
        $this->setRegistros($Registros);
    }

    function montaPagina()
    {
        $this->setConteudo($this->getHtmlPagina());
        $this->setTitulo(self::TITULO_PAGINA);
        $this->setOpcoesFiltro(self::OPCOES_FILTRO);
        $this->setPathCriarRegistro('/contatos/criar');
        $this->setPathCss('/assets/css/app.css');
        $this->setPathJs('/assets/js/form-ajax.js');

        $this->render();
    }

    private function getHtmlPagina()
    {
        $sLinhas = '';

        foreach ($this->getRegistros() as $oModel) {
            $sPessoa    = htmlspecialchars($oModel->getPessoa()->getNome(), ENT_QUOTES, 'UTF-8');
            $sTipo      = htmlspecialchars($oModel->getTipo()->getDescricao(), ENT_QUOTES, 'UTF-8');
            $sDescricao = htmlspecialchars($oModel->getDescricao(), ENT_QUOTES, 'UTF-8');
            $id         = $oModel->getId();

            $sLinhas .= "
                    <tr>
                        <td>{$sPessoa}</td>
                        <td>{$sTipo}</td>
                        <td>{$sDescricao}</td>
                        <td>
                            <div class=\"area-acoes-linha\">
                                <a href=\"/contatos/visualizar?id={$id}\" class=\"botao botao-secundario botao-pequeno\">Visualizar</a>
                                <a href=\"/contatos/editar?id={$id}\" class=\"botao botao-secundario botao-pequeno\">Alterar</a>
                                <form method=\"post\" class=\"form-excluir\">
                                    <input type=\"hidden\" name=\"_method\" value=\"DELETE\">
                                    <input type=\"hidden\" name=\"id\" value=\"{$id}\">
                                    <button type=\"submit\" class=\"botao botao-perigo botao-pequeno\">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
            ";
        }

        $sHtml = "
            <table class=\"table-padrao\">
                <thead>
                    <tr>
                        <th>Pessoa</th>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {$sLinhas}
                </tbody>
            </table>
        ";

        return $sHtml;
    }

}
