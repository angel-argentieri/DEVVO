const API = 'http://localhost/Devvo/API/PUBLIC';

function toast(msg, tipo) {
    if (!tipo) tipo = 'ok';

    let el = document.getElementById('toast');
    if (!el) {
        el = document.createElement('div');
        el.id = 'toast';
        document.body.appendChild(el);
    }

    el.textContent = msg;
    el.className = 'toast show toast-' + tipo;
    setTimeout(function() { el.className = 'toast'; }, 3000);
}

function formatarData(dataStr) {
    const d = new Date(dataStr);
    const agora = new Date();
    const diff = Math.floor((agora - d) / 1000);

    if (diff < 60)     return 'agora mesmo';
    if (diff < 3600)   return 'ha ' + Math.floor(diff / 60) + ' min';
    if (diff < 86400)  return 'ha ' + Math.floor(diff / 3600) + 'h';
    if (diff < 604800) return 'ha ' + Math.floor(diff / 86400) + ' dias';

    return d.toLocaleDateString('pt-BR');
}

function badgeStatus(status) {
    if (status === 'disponivel') return '<span class="badge badge-ok">Disponivel</span>';
    if (status === 'entregue')   return '<span class="badge badge-gray">Entregue</span>';
    if (status === 'doado')      return '<span class="badge badge-gray">Doado</span>';
    return '';
}

function pillStatus(status) {
    if (status === 'disponivel') return '<span class="pill pill-ok">Disponivel</span>';
    if (status === 'entregue')   return '<span class="pill pill-gray">Entregue</span>';
    if (status === 'doado')      return '<span class="pill pill-gray">Doado</span>';
    return status;
}

function getUsuario() {
    const salvo = localStorage.getItem('devvo_usuario');
    if (!salvo) return null;
    return JSON.parse(salvo);
}

function salvarUsuario(dados) {
    localStorage.setItem('devvo_usuario', JSON.stringify(dados));
}

function sair() {
    localStorage.removeItem('devvo_usuario');
    window.location.href = 'login.html';
}

function redirecionarSeNaoLogado(tipoEsperado) {
    const u = getUsuario();

    if (!u) {
        window.location.href = 'login.html';
        return null;
    }

    if (tipoEsperado && u.tipo_acesso !== tipoEsperado) {
        window.location.href = 'index.html';
        return null;
    }

    return u;
}
