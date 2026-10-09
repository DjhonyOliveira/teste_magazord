<?php

namespace Src\Controller;

use Src\Core\Response;
use Src\Enum\EnumTipoContato;
use Src\Model\ModelContato;
use Src\Model\ModelPessoa;
use Src\View\Contato\ViewContato;
use Src\View\Contato\ViewContatoForm;

/**
 * Controller da rotina de contatos
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage Controller
 */
class ControllerContato extends Controller
{
    protected function getInstanceModel()
    {
        return new ModelContato();
    }

    private function getViewConsultaContatos()
    {
        return new ViewContato();
    }

    private function getViewForm()
    {
        return new ViewContatoForm();
    }

    function create()
    {
        if($this->isGet()){
            $this->montaTelaIncluirContato();
        } elseif($this->isPost()){
            $aDados = $this->getRequest()->all();
            $aErros = $this->validaContato($aDados);

            if(!empty($aErros)){
                Response::json(['sucesso' => false, 'erros' => $aErros], 422);
            }

            $oModel = $this->povoaContato($this->getInstanceModel(), $aDados);
            $this->insere($oModel);

            Response::json([
                'sucesso'  => true,
                'mensagem' => 'Contato criado com sucesso.',
                'redirect' => '/contatos',
            ]);
        }
    }

    function update()
    {
        $iId      = (int) $this->getRequest()->query('id');
        $oContato = $this->getEntityManager()->getRepository(ModelContato::class)->find($iId);

        if($this->isGet()){
            $this->montaTelaFormContatoPovoada($oContato)->montaPagina();
        } elseif($this->isPost()){
            $aDados = $this->getRequest()->all();
            $aErros = $this->validaContato($aDados);

            if(!empty($aErros)){
                Response::json(['sucesso' => false, 'erros' => $aErros], 422);
            }

            $oContato = $this->povoaContato($oContato, $aDados);
            $this->altera();

            Response::json([
                'sucesso'  => true,
                'mensagem' => 'Contato alterado com sucesso.',
                'redirect' => '/contatos',
            ]);
        }
    }

    function delete()
    {
        $iId      = (int) $this->getRequest()->all()['id'];
        $oContato = $this->getEntityManager()->getRepository(ModelContato::class)->find($iId);

        if(!$oContato){
            Response::json([
                'sucesso'  => false,
                'mensagem' => 'Contato não encontrado',
                'redirect' => '/contatos'
            ], 404);
        }

        $this->deleta($oContato);

        Response::json([
            'sucesso'  => true,
            'mensagem' => 'Contato excluído com sucesso',
            'redirect' => '/contatos'
        ]);
    }

    function list()
    {
        $sCampo = (string) $this->getRequest()->query('campo', '');
        $sBusca = (string) $this->getRequest()->query('busca', '');

        $aRegistros = $this->buscaContatos($sCampo, $sBusca);

        $oView = $this->getViewConsultaContatos();
        $oView->setRegistros($aRegistros);
        $oView->setFiltroAtual(['campo' => $sCampo, 'busca' => $sBusca]);
        $oView->montaPagina();
    }

    private function buscaContatos(string $sCampo, string $sBusca)
    {
        $oRepositorio = $this->getEntityManager()->getRepository(ModelContato::class);

        if($sCampo === '' || $sBusca === '' || !in_array($sCampo, ViewContato::OPCOES_FILTRO, true)){
            return $oRepositorio->findAll();
        }

        return $oRepositorio->createQueryBuilder('c')
            ->where("LOWER(c.{$sCampo}) LIKE LOWER(:busca)")
            ->setParameter('busca', '%' . $sBusca . '%')
            ->getQuery()
            ->getResult();
    }

    function show()
    {
        $iId      = (int) $this->getRequest()->query('id');
        $oContato = $this->getEntityManager()->getRepository(ModelContato::class)->find($iId);

        $oView = $this->montaTelaFormContatoPovoada($oContato);
        $oView->setReadOnly(true);

        $oView->montaPagina();
    }

    private function montaTelaIncluirContato()
    {
        $oView = $this->getViewForm();
        $oView->setMontaAreaFiltros(false);
        $oView->setMontaBotaoCriar(false);
        $oView->setPessoas($this->getTodasPessoas());

        $oView->montaPagina();
    }

    private function montaTelaFormContatoPovoada($oModel = [])
    {
        $oView = $this->getViewForm();
        $oView->setMontaAreaFiltros(false);
        $oView->setMontaBotaoCriar(false);
        $oView->setPessoas($this->getTodasPessoas());
        $oView->setRegistros($oModel);
        $oView->setAlteracao(true);

        return $oView;
    }

    private function getTodasPessoas()
    {
        return $this->getEntityManager()->getRepository(ModelPessoa::class)->findAll();
    }

    private function validaContato(array $aDados): array
    {
        $aErros = [];

        if(trim((string) ($aDados['descricao'] ?? '')) === ''){
            $aErros[] = 'A descrição é obrigatória.';
        }

        if(EnumTipoContato::tryFrom((int) ($aDados['tipo'] ?? 0)) === null){
            $aErros[] = 'Selecione um tipo de contato válido.';
        }

        $oPessoa = $this->getEntityManager()->getRepository(ModelPessoa::class)->find((int) ($aDados['idPessoa'] ?? 0));

        if($oPessoa === null){
            $aErros[] = 'Selecione uma pessoa válida.';
        }

        return $aErros;
    }

    private function povoaContato($oModel, array $aDados)
    {
        $oPessoa = $this->getEntityManager()->getRepository(ModelPessoa::class)->find((int) $aDados['idPessoa']);

        $oModel->setDescricao(trim((string) $aDados['descricao']));
        $oModel->setTipo(EnumTipoContato::from((int) $aDados['tipo']));
        $oModel->setPessoa($oPessoa);

        return $oModel;
    }

}
