<?php

namespace Src\View\Pessoa;

use Src\View\Layout\LayoutBase;

/**
 * View de listagem de Pessoas
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage View
 */
class ViewPessoa extends LayoutBase
{
    const TITULO_PAGINA = 'Listas Pessoas';
    const OPCOES_FILTRO = [
        'Nome' => 'nome',
        'Cpf'  => 'cpf'
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
        $this->setPathCriarRegistro('/pessoas/criar');
        $this->setPathCss('/assets/css/app.css');
        $this->setPathJs('/assets/js/form-ajax.js');

        $this->render();
    }

    private function getHtmlPagina()
    {
        $sLinhas = '';

        foreach ($this->getRegistros() as $oModel) {
            $sNome = htmlspecialchars($oModel->getNome(), ENT_QUOTES, 'UTF-8');
            $sCpf  = htmlspecialchars($this->formatarCpf($oModel->getCpf()), ENT_QUOTES, 'UTF-8');
            $id    = $oModel->getId();

            $sLinhas .= "
                    <tr>
                        <td>{$sNome}</td>
                        <td>{$sCpf}</td>
                        <td>
                            <div class=\"area-acoes-linha\">
                                <a href=\"/pessoas/visualizar?id={$id}\" class=\"botao botao-secundario botao-pequeno\">Visualizar</a>
                                <a href=\"/pessoas/editar?id={$id}\" class=\"botao botao-secundario botao-pequeno\">Alterar</a>
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
                        <th>Nome</th>
                        <th>CPF</th>
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

    private function formatarCpf(string $sCpf): string
    {
        $sCpf = preg_replace('/\D/', '', $sCpf);

        if (strlen($sCpf) !== 11) {
            return $sCpf;
        }

        return substr($sCpf, 0, 3) . '.' . substr($sCpf, 3, 3) . '.' . substr($sCpf, 6, 3) . '-' . substr($sCpf, 9, 2);
    }

}