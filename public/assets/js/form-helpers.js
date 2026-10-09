export function aplicarMascaraCpf(oInput) {
    oInput.addEventListener('input', function () {
        var sValor = oInput.value.replace(/\D/g, '').slice(0, 11);

        sValor = sValor.replace(/(\d{3})(\d)/, '$1.$2');
        sValor = sValor.replace(/(\d{3})(\d)/, '$1.$2');
        sValor = sValor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        oInput.value = sValor;
    });
}

function formatarTelefone(sValor) {
    sValor = sValor.replace(/\D/g, '').slice(0, 11);

    if (sValor.length > 10) {
        sValor = sValor.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
    } else if (sValor.length > 6) {
        sValor = sValor.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
    } else if (sValor.length > 2) {
        sValor = sValor.replace(/(\d{2})(\d{0,5})/, '($1) $2');
    } else if (sValor.length > 0) {
        sValor = sValor.replace(/(\d{0,2})/, '($1');
    }

    return sValor;
}

export function aplicarTipoDescricao(oForm) {
    var oTipo = oForm.querySelector('select[name="tipo"]');
    var oDescricao = oForm.querySelector('input[name="descricao"]');

    if (!oTipo || !oDescricao) {
        return;
    }

    function atualizarTipo() {
        if (oTipo.value === '2') {
            oDescricao.type = 'email';
            oDescricao.maxLength = 255;
        } else if (oTipo.value === '1') {
            oDescricao.type = 'tel';
            oDescricao.maxLength = 15;
            oDescricao.value = formatarTelefone(oDescricao.value);
        } else {
            oDescricao.type = 'text';
            oDescricao.maxLength = 255;
        }
    }

    oDescricao.addEventListener('input', function () {
        if (oTipo.value === '1') {
            oDescricao.value = formatarTelefone(oDescricao.value);
        }
    });

    oTipo.addEventListener('change', function(){
        oDescricao.value = '';
    });

    oTipo.addEventListener('change', atualizarTipo);

    atualizarTipo();
}