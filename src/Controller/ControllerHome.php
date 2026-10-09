<?php

namespace Src\Controller;

use Src\View\Home\ViewHome;

/**
 * Controller da Home do sistema
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage Controller
 */
class ControllerHome extends Controller
{

    protected function getInstanceModel(){}

    function list()
    {
        $oView = $this->getViewConsulta();
        $oView->setMontaAreaFiltros(false);
        $oView->setMontaBotaoCriar(false);
        
        $oView->montaPagina();
    }

    private function getViewConsulta()
    {
        return new ViewHome();
    }

}