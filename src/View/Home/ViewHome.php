<?php

namespace Src\View\Home;

use Src\View\Layout\LayoutBase;

/**
 * View da Home do sistema
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage View
 */
class ViewHome extends LayoutBase
{
    const TITULO_PAGINA = 'Magazord Contatos';

    /**
     * Monta e renderiza a página inicial
     */
    public function montaPagina()
    {
        $this->setConteudo($this->htmlPagina());
        $this->setTitulo(self::TITULO_PAGINA);
        $this->setPathCss('/assets/css/app.css');

        $this->render();
    }

    /**
     * Monta o html de boas-vindas da home
     * @return string
     */
    private function htmlPagina()
    {
        $sHtml = '
            <section class="container-form">
            <section class="container-form">
                <h1 style="margin-bottom: 1.5rem;">Bem-vindo</h1>
                <p class="texto-explicativo">Gerencie o cadastro de pessoas e seus contatos de forma simples: adicione, consulte e organize as informações pelos menus de navegação acima.</p>
            </section>
        ';

        return $sHtml;
    }

};