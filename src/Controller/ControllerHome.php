<?php

namespace Src\Controller;

use Src\View\Home\ViewHome;

/**
 * Controller da Home do sistema
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage Controller
 */
class ControllerHome
{
    function create()
    {

    }

    function update()
    {

    }

    function delete()
    {

    }

    function list()
    {
        $this->getViewConsulta()->montaPagina();
    }

    private function getViewConsulta()
    {
        return new ViewHome();
    }

}