<?php

namespace Src\Controller;

use Doctrine\ORM\EntityManager;
use Src\Core\Request;

/**
 * Controller base do sistema
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage Controller
 */
abstract class Controller
{
    private Request $request;
    private EntityManager $entityManager;

    abstract protected function getInstanceModel();

    function __construct(Request $request, EntityManager $entityManager)
    {
        $this->request       = $request;
        $this->entityManager = $entityManager;
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }

    protected function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    function isPost(){
        return $this->request->method() == 'POST';
    }

    function isDelete()
    {
        return $this->request->method() == 'DELETE';
    }

    function isUpdate()
    {
        return $this->request->method() == 'PUT';
    }

    function isGet()
    {
        return $this->request->method() == 'GET';
    }

    protected function insere($model)
    {
        $this->getEntityManager()->persist($model);
        $this->getEntityManager()->flush();
    }

    protected function deleta($model)
    {
        $this->getEntityManager()->remove($model);
        $this->getEntityManager()->flush();
    }

    protected function altera()
    {
        $this->getEntityManager()->flush();
    }

    protected function povoaModel($model)
    {
        $data = $this->request->all();

        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($model, $method)) {
                $model->$method($value);
            }
        }

        return $model;
    }

}
