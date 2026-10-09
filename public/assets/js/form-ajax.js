import { aplicarMascaraCpf, aplicarTipoDescricao } from './form-helpers.js';

(function () {
    function getContainer() {
        return document.getElementById('area-mensagens');
    }

    function exibirSucesso(sMensagem) {
        var oContainer = getContainer();

        if (!oContainer) {
            return;
        }

        oContainer.innerHTML = '<div class="mensagem mensagem-sucesso">' + sMensagem + '</div>';
    }

    function exibirErros(aErros) {
        var oContainer = getContainer();

        if (!oContainer) {
            return;
        }

        var sItens = aErros.map(function (sErro) {
            return '<li>' + sErro + '</li>';
        }).join('');

        oContainer.innerHTML = '<div class="mensagem mensagem-erro"><ul>' + sItens + '</ul></div>';
    }

    function isDelete(oForm) {
        var oMetodo = oForm.querySelector('input[name="_method"]');

        return !!oMetodo && oMetodo.value.toUpperCase() === 'DELETE';
    }

    function enviarFormulario(event) {
        event.preventDefault();

        var oForm = event.target;

        if (isDelete(oForm) && !window.confirm('Tem certeza que deseja excluir este registro?')) {
            return;
        }

        var oDados = new FormData(oForm);

        fetch(oForm.action, {
            method: 'POST',
            body: oDados,
            headers: { 'Accept': 'application/json' },
        })
        .then(function (oResposta) {
            return oResposta.json();
        })
        .then(function (oJson) {
            if (oJson.sucesso) {
                exibirSucesso(oJson.mensagem);

                if (oJson.redirect) {
                    setTimeout(function () {
                        window.location.href = oJson.redirect;
                    }, 800);
                }

                return;
            }

            exibirErros(oJson.erros || []);
        })
        .catch(function () {
            exibirErros(['Não foi possível concluir a operação. Tente novamente.']);
        });
    }

    document.querySelectorAll('form').forEach(function (oForm) {
        if (oForm.method.toUpperCase() === 'GET') {
            return;
        }

        oForm.addEventListener('submit', enviarFormulario);
        aplicarTipoDescricao(oForm);
    });

    document.querySelectorAll('input[name="cpf"]').forEach(aplicarMascaraCpf);

})();