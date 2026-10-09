<?php

namespace Src\View\Layout;

/**
 * View base do sistema
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage View
 */
abstract class LayoutBase
{
    private string $conteudo          = '';
    private string $titulo            = '';
    private string $pathCss           = '';
    private string $pathJs            = '';
    protected mixed $registros        = [];
    private bool $montaAreaFiltros    = true;
    private bool $montaBotaoCriar     = true;
    private array $opcoesFiltro       = [];
    private string $pathCriarRegistro = '';
    private bool $readOnly            = false;
    private array $filtroAtual        = ['campo' => '', 'busca' => ''];

    /**
     * Retorna o conteudo da página
     * @return string
     */ 
    protected function getConteudo()
    {
        return $this->conteudo;
    }

    /**
     * Seta o conteudo da página
     * @param string $conteudo
     * @return  self
     */ 
    protected function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;

        return $this;
    }

    /**
     * Retorna o titulo da página
     */ 
    protected function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Seta o titulo da página
     * @param $titulo
     * @return  self
     */ 
    protected function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Retorna o path css da página
     */ 
    protected function getPathCss()
    {
        return $this->pathCss;
    }

    /**
     * Seta o path css da página
     * @param string $pathCss
     * @return  self
     */ 
    protected function setPathCss($pathCss)
    {
        $this->pathCss = $pathCss;

        return $this;
    }

    /**
     * retorna o path js da página
     * @return string
     */
    protected function getPathJs()
    {
        return $this->pathJs;
    }

    /**
     * Seta o path js da página
     * @param string $pathJs
     * @return self
     */
    protected function setPathJs($pathJs)
    {
        $this->pathJs = $pathJs;

        return $this;
    }

    /**
     * Retornar os registros da tela
     * @return array
     */ 
    public function getRegistros()
    {
        return $this->registros;
    }

    /**
     * Seta os registros da tela
     * @var array $registros
     * @return  self
     */ 
    public function setRegistros($registros)
    {
        $this->registros = $registros;

        return $this;
    }

    /**
     * Retorna se deve montar a area de filtros
     * @return bool
     */ 
    public function getMontaAreaFiltros()
    {
        return $this->montaAreaFiltros;
    }

    /**
     * define se deve apresentar a area de filtros, default = true
     * @param bool $montaAreaFiltros
     * @return  self
     */ 
    public function setMontaAreaFiltros($montaAreaFiltros)
    {
        $this->montaAreaFiltros = $montaAreaFiltros;

        return $this;
    }

    /**
     * Retorna se deve adicionar o botão de criar
     */ 
    public function getMontaBotaoCriar()
    {
        return $this->montaBotaoCriar;
    }

    /**
     * Seta se deve mostrar o botão de incluir
     * @param bool $montaBotaoCriar
     * @return  self
     */ 
    public function setMontaBotaoCriar($montaBotaoCriar)
    {
        $this->montaBotaoCriar = $montaBotaoCriar;

        return $this;
    }

    /**
     * Retorna as opções de filtro
     * @return array
     */ 
    public function getOpcoesFiltro()
    {
        return $this->opcoesFiltro;
    }

    /**
     * Seta as opções de filtro da página
     * @param array $opcoesFiltro
     * @return  self
     */ 
    public function setOpcoesFiltro(array $opcoesFiltro)
    {
        $this->opcoesFiltro = $opcoesFiltro;

        return $this;
    }

    /**
     * Retorna o path do botão de criar registro
     * @return string
     */ 
    public function getPathCriarRegistro()
    {
        return $this->pathCriarRegistro;
    }

    /**
     * Seta o path do botão de criar registro
     * @param string $pathCriarRegistro
     * @return  self
     */ 
    public function setPathCriarRegistro(string $pathCriarRegistro)
    {
        $this->pathCriarRegistro = $pathCriarRegistro;

        return $this;
    }

    /**
     * Retorna o filtro atualmente aplicado na tela
     * @return array
     */
    public function getFiltroAtual()
    {
        return $this->filtroAtual;
    }

    /**
     * Seta o filtro atualmente aplicado na tela
     * @param array $filtroAtual
     * @return  self
     */
    public function setFiltroAtual(array $filtroAtual)
    {
        $this->filtroAtual = $filtroAtual;

        return $this;
    }

    /**
     * Retorna se os campos são readonly
     * @return bool
     */
    public function getReadOnly()
    {
        return $this->readOnly;
    }

    /**
     * Seta se os campos são readonly
     * @param bool
     * @return  self
     */ 
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    protected function render()
    {
        $sTitulo  = htmlspecialchars($this->getTitulo(),  ENT_QUOTES, 'UTF-8');
        $sPathCss = htmlspecialchars($this->getPathCss(), ENT_QUOTES, 'UTF-8');
        $sPathJs  = htmlspecialchars($this->getPathJs(),  ENT_QUOTES, 'UTF-8');

        $sAreaFiltros = $this->getMontaAreaFiltros() ? $this->montaAreaFiltros() : '';
        $sBotaoCriar  = $this->getMontaBotaoCriar()  ? $this->criaBotaoIncluir() : '';

        $sLinkCss  = $sPathCss !== '' ? "<link rel=\"stylesheet\" href=\"{$sPathCss}\">" : '';
        $sScriptJs = $sPathJs !== '' ? "<script type=\"module\" src=\"{$sPathJs}\"></script>" : '';

        $sHtml = "
            <!DOCTYPE html>
                <html lang=\"pt-BR\">
                <head>
                    <meta charset=\"utf-8\">
                    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
                    <title> {$sTitulo} </title>
                    {$sLinkCss}
                </head>
                <body>
                    <header>
                        <div class=\"header-container\">
                            <div>
                                <a href=\"/\">
                                    <img src=\"/assets/img/logo-magazord.png\" alt=\"Logo\" class=\"header-logo\">
                                </a>
                            </div>    
                            <div>
                            <nav>
                                <a href=\"/pessoas\">Pessoas</a>
                                <a href=\"/contatos\">Contatos</a>
                            </nav>
                        </div>
                    </header>

                    <main>
                        <div id=\"area-mensagens\" class=\"area-mensagens\"></div>
                        {$sAreaFiltros}
                        {$sBotaoCriar}
                        {$this->getConteudo()}
                    </main>

                    {$sScriptJs}
                </body>
            </html>
        ";

        echo $sHtml;
    }

    private function montaAreaFiltros()
    {
        $sCampoAtual = htmlspecialchars($this->getFiltroAtual()['campo'] ?? '', ENT_QUOTES, 'UTF-8');
        $sBuscaAtual = htmlspecialchars($this->getFiltroAtual()['busca'] ?? '', ENT_QUOTES, 'UTF-8');

        $sHtml = '
            <div class="area-filtros">
                <form method="get" class="form-filtros">';

        $sSelecioneSelected = $sCampoAtual === '' ? 'selected' : '';
        $sHtml .= "<select name=\"campo\">
                    <option value=\"\" disabled {$sSelecioneSelected}>Selecione...</option>";

        foreach($this->getOpcoesFiltro() as $nome => $valor){
            $sSelected = $valor === $sCampoAtual ? 'selected' : '';
            $sHtml .= "<option value=\"$valor\" $sSelected>$nome</option>";
        }

        $sHtml .= '</select>';


        $sHtml .= "<input type=\"text\" name=\"busca\" value=\"{$sBuscaAtual}\" placeholder=\"Buscar...\" class=\"input-filtro\">
                    <button type=\"submit\" class=\"botao botao-secundario\">Filtrar</button>
                </form>
            </div>
        ";

        return $sHtml;
    }

    private function criaBotaoIncluir()
    {
        $sHtml = '
            <div class="area-acoes">
                <a href="'.$this->getPathCriarRegistro().'" class="botao botao-primario">+ Novo</a>
            </div>
        ';

        return $sHtml;
    }
    
    

    






}