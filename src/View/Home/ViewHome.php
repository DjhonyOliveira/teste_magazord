<?php

namespace Src\View\Home;

use Src\View\Layout\LayoutBase;

class ViewHome extends LayoutBase
{
    const TITULO_PAGINA = 'Magazord Contatos';

    private array $dadosConsulta = [];

    public function montaPagina()
    {
        $this->setConteudo($this->htmlPagina());
        $this->setTitulo(self::TITULO_PAGINA);

        $this->render();
    }

    private function htmlPagina()
    {
        $sHtml = '
            <h1>teste</h1>
        ';

        return $sHtml;
    }

};