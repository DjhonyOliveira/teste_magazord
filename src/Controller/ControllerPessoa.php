<?php

namespace Src\Controller;

use Src\Core\Response;
use Src\Model\ModelPessoa;
use Src\View\Pessoa\ViewPessoa;
use Src\View\Pessoa\ViewPessoaForm;

/**
 * Controller da rotina de pessoa
 * @author Djonatan R. de Oliveira
 * @package Src
 * @subpackage Controller
 */
class ControllerPessoa extends Controller
{

    protected function getInstanceModel()
    {
        return new ModelPessoa();
    }

    private function getViewConsultaPessoas()
    {
        return new ViewPessoa();
    }

    private function getViewForm()
    {
        return new ViewPessoaForm();
    }
    
    function create()
    {
        if($this->isGet()){
            $this->montaTelaIncluirPaciente();
        } elseif($this->isPost()){
            $aDados = $this->getRequest()->all();
            $aErros = $this->validaPessoa($aDados);

            if(!empty($aErros)){
                Response::json(['sucesso' => false, 'erros' => $aErros], 422);
            }

            $oModel = $this->povoaModel($this->getInstanceModel());
            $this->insere($oModel);

            Response::json([
                'sucesso'  => true,
                'mensagem' => 'Pessoa criada com sucesso.',
                'redirect' => '/pessoas',
            ]);
        }
    }

    function update()
    {
        $iId     = (int) $this->getRequest()->query('id');
        $oPessoa = $this->getEntityManager()->getRepository(ModelPessoa::class)->find($iId);

        if($this->isGet()){
            $this->montaTelaFormPacientePovoada($oPessoa)->montaPagina();
        } elseif($this->isPost()){
            $aDados = $this->getRequest()->all();
            $aErros = $this->validaPessoa($aDados, $iId);

            if(!empty($aErros)){
                Response::json(['sucesso' => false, 'erros' => $aErros], 422);
            }

            $oPessoa = $this->povoaModel($oPessoa);
            $this->altera();

            Response::json([
                'sucesso'  => true,
                'mensagem' => 'Pessoa alterada com sucesso.',
                'redirect' => '/pessoas',
            ]);
        }
    }

    function delete()
    {
        $iId     = (int) $this->getRequest()->all()['id'];
        $oPessoa = $this->getEntityManager()->getRepository(ModelPessoa::class)->find($iId);

        if(!$oPessoa){
            Response::json([
                'sucesso'  => false,
                'mensagem' => 'Pessoa não encontrada',
                'redirect' => '/pessoas'
            ], 404);
        }

        $this->deleta($oPessoa);

        Response::json([
                'sucesso'  => true,
                'mensagem' => 'Pessoa Deletada com sucesso',
                'redirect' => '/pessoas'
            ]);
    }

    function list()
    {
        $sCampo = (string) $this->getRequest()->query('campo', '');
        $sBusca = (string) $this->getRequest()->query('busca', '');

        $aRegistros = $this->buscaPessoas($sCampo, $sBusca);

        $oView = $this->getViewConsultaPessoas();
        $oView->setRegistros($aRegistros);
        $oView->setFiltroAtual(['campo' => $sCampo, 'busca' => $sBusca]);

        $oView->montaPagina();
    }

    private function buscaPessoas(string $sCampo, string $sBusca)
    {
        $oRepositorio = $this->getEntityManager()->getRepository(ModelPessoa::class);

        if($sCampo === '' || $sBusca === '' || !in_array($sCampo, ViewPessoa::OPCOES_FILTRO, true)){
            return $oRepositorio->findAll();
        }

        $sValorBusca = $sCampo === 'cpf' ? preg_replace('/\D/', '', $sBusca) : $sBusca;

        return $oRepositorio->createQueryBuilder('p')
            ->where("LOWER(p.{$sCampo}) LIKE LOWER(:busca)")
            ->setParameter('busca', '%' . $sValorBusca . '%')
            ->getQuery()
            ->getResult();
    }

    function show()
    {
        $iId     = (int) $this->getRequest()->query('id');
        $oPessoa = $this->getEntityManager()->getRepository(ModelPessoa::class)->find($iId);

        $oView = $this->montaTelaFormPacientePovoada($oPessoa);
        $oView->setReadOnly(true);

        $oView->montaPagina();
    }

    private function montaTelaIncluirPaciente()
    {
        $oView = $this->getViewForm();
        $oView->setMontaAreaFiltros(false);
        $oView->setMontaBotaoCriar(false);

        $oView->montaPagina();
    }

    private function montaTelaFormPacientePovoada($aModel = []){
        $oView = $this->getViewForm();
        $oView->setMontaAreaFiltros(false);
        $oView->setMontaBotaoCriar(false);
        $oView->setRegistros($aModel);

        return $oView;
    }

    private function validaPessoa(array $aDados, ?int $iIdIgnorar = null)
    {
        $aErros = [];

        if(trim((string) ($aDados['nome'] ?? '')) === ''){
            $aErros[] = 'O nome é obrigatório.';
        }

        $sCpf = preg_replace('/\D/', '', (string) ($aDados['cpf'] ?? ''));

        if(strlen($sCpf) !== 11){
            $aErros[] = 'O CPF deve conter 11 dígitos.';
        } elseif($this->validaCpfJaExiste($sCpf, $iIdIgnorar)){
            $aErros[] = 'Já existe uma pessoa cadastrada com esse CPF.';
        }

        return $aErros;
    }

    private function validaCpfJaExiste(string $sCpf, ?int $iIdIgnorar = null): bool
    {
        $oPessoa = $this->getEntityManager()
            ->getRepository(ModelPessoa::class)
            ->findOneBy(['cpf' => $sCpf]);

        if($oPessoa === null){
            return false;
        }

        return $oPessoa->getId() !== $iIdIgnorar;
    }

}