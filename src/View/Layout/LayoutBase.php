<?php

namespace Src\View\Layout;

abstract class LayoutBase
{
    private string $conteudo = '';
    private string $titulo   = '';
    private string $pathCss  = '';
    private string $pathJs   = '';

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

    protected function render()
    {
        $sHtml = "
            <!DOCTYPE html>
                <html lang=\"pt-BR\">
                <head>
                    <meta charset=\"utf-8\">
                    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
                    <title> {$this->getTitulo()} </title>
                    <link rel=\"stylesheet\" href=\"{$this->getPathCss()}\">
                </head>
                <body>
                    <header>
                        <nav>
                            <a href=\"/\"><strong>Contatos</strong></a>
                            <a href=\"/pessoas\">Pessoas</a>
                            <a href=\"/contatos\">Contatos</a>
                        </nav>
                    </header>

                    <main>
                        <?= {$this->getConteudo()} ?>
                    </main>

                    <script defer src=\"{$this->getPathJs()}\"></script>
                </body>
            </html>
        ";

        echo $sHtml;
    }

}