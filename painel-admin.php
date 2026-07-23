<?php 
    include "server/auth.php";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Gunes Ads - Painel Administrador</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <!-- Container dos alertas/toasts -->
    <div class="toast-container" id="toast-container"></div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <div class="logo-icon"><i class="bi bi-megaphone-fill"></i></div>
            <div class="logo-text">Gunes<span>ADS</span></div>
        </div>

        <div class="nav-section-label">Administração</div>

        <nav>
            <button class="nav-item active" type="button" data-screen="anuncios-admin">
                <span class="nav-icon"><i class="bi bi-megaphone-fill"></i></span>
                <span class="nav-label">Anúncios do Admin</span>
            </button>
            <button type="button" class="nav-item" data-screen="usuarios">
                <span class="nav-icon"><i class="bi bi-people-fill"></i></span>
                <span class="nav-label">Usuários</span>
            </button>
        </nav>
    </div>

    <!-- Fundo escuro atrás do menu quando aberto no mobile -->
    <div class="sidebar-backdrop" id="sidebar-backdrop"></div>

    <!-- ÁREA PRINCIPAL -->
    <main>

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="interface">

                <div class="topbar-left">
                    <button type="button" class="menu-toggle" id="menu-toggle">
                        <div class="line ln1"></div>
                        <div class="line ln2"></div>
                        <div class="line ln3"></div>
                    </button>
                    <h1 class="screen-title" id="screen-title">Anúncios do Admin</h1>
                </div>

                <div class="topbar-right">
                    <!--
                        ADMIN LOGADO — mesma fonte de dados usada no avatar, na
                        saudação e na tela de Perfil (ver #perfilHero). No PHP:
                        $_SESSION['admin']['nome'] / ['iniciais'] / ['cargo'].
                        Use htmlspecialchars() no nome em todos os pontos.
                    -->
                    <div class="user-info" id="user-info">

                        <div class="user-text">
                            <div class="user-hello">Olá, <b><?= $firstName ?></b></div>
                            <div class="user-plan"><i class="bi bi-shield-fill-check"></i> Administrador</div>
                        </div>

                        <div class="avatar" id="topbar-avatar"><?= $iniciais ?></div>

                        <div class="user-menu" id="user-menu">
                            <div class="user-menu-item" data-screen="perfil"><i class="bi bi-person"></i> Perfil</div>
                            <div class="user-menu-divider"></div>
                            <a href="server/logout.php" style="text-decoration: none;" class="user-menu-item danger"><i
                                    class="bi bi-box-arrow-right"></i> Sair</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="interface">

                <!-- ============================================================
                     TELA: ANÚNCIOS DO ADMIN
                     ============================================================ -->
                <div class="screen active" id="screen-anuncios-admin">

                    <p class="screen-intro">Cadastre anúncios próprios ou de teste — institucionais, "Anuncie aqui", produtos da motorista ou qualquer campanha administrada diretamente pelo sistema. Eles seguem o mesmo padrão visual e entram na rotação normalmente junto aos anúncios dos usuários.</p>

                    <div class="screen-tool-bar">
                        <button type="button" class="btn-novo-anuncio" id="btn-novo-anuncio">
                            <i class="bi bi-plus-circle-fill"></i> Novo anúncio
                        </button>
                    </div>

                    <!--
                        ============================================================
                        Um .admin-ad-card por linha da tabela "anuncios_admin".
                        Campos vindos de $anuncio no PHP:
                          id, nome, descricao, categoria, status ('ativo'|'pausado'),
                          imagem (URL, pode ser vazia), cor (gradiente CSS de
                          fallback usado quando 'imagem' estiver vazia).

                        Os valores ficam guardados em atributos data-* no próprio
                        card, para o JS ler direto do HTML já renderizado — sem
                        precisar de array próprio. Use htmlspecialchars() em nome,
                        descricao e categoria.

                        Se $anuncios estiver vazio, renderize só o bloco
                        #empty-anuncios (mais abaixo) e omita o .ads-grid.
                        ============================================================
                    -->
                    <div class="ads-grid" id="ads-grid">

                        <?php foreach($anuncios as $anuncio): ?>
                        <div
                            class="admin-ad-card"
                            data-id="<?= $anuncio["id"] ?>"
                            data-nome="<?= $anuncio["nome"] ?>"
                            data-descricao="<?= $anuncio["descricao"] ?>"
                            data-categoria="<?= $anuncio["categoria"] ?>"
                            data-status="<?= $anuncio["status"] ?>"
                            data-imagem="<?= $anuncio["imagem"] ?>"
                            data-cor="linear-gradient(135deg, #3E5EE0, #1B2C7A)"
                        >
                            <div class="ad-thumb" style="background:linear-gradient(135deg, #3E5EE0, #1B2C7A);">
                                <img src="<?= "uploads/".$anuncio["imagem"] ?>" alt="" class="img-ad-thumb">
                            </div>

                            <div class="ad-info">
                                <div class="ad-info-head">
                                    <span class="ad-title"><?= $anuncio["nome"] ?></span>
                                    <span class="ad-status"><?= $anuncio["status"] ?></span>
                                </div>

                                <div class="ad-info-meta">
                                    <span class="ad-categoria"><i class="bi bi-tag"></i> Anuncie aqui</span>
                                </div>

                                <div class="ad-info-desc">
                                    <span class="ad-descricao"><?= $anuncio["descricao"] ?></span>
                                </div>
                            </div>

                            <div class="ad-actions">
                                <button type="button" class="btn-act" data-action="editar-anuncio" title="Editar"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn-act" data-action="pausar-anuncio" title="Pausar"><i class="bi bi-pause-fill"></i></button>
                                <button type="button" class="btn-act danger" data-action="excluir-anuncio" title="Excluir"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                        <?php endforeach; ?>

                    </div>

                    <!-- Estado vazio: <?php if (empty($anuncios)): ?> ... <?php endif; ?> -->
                    <div class="empty-state" id="empty-anuncios">
                        <i class="bi bi-megaphone"></i>
                        <h2>Nenhum anúncio do admin cadastrado</h2>
                        <p>Cadastre campanhas institucionais ou de teste para que participem da rotação nos tablets.</p>
                    </div>

                </div>

                <!-- ============================================================
                     TELA: USUÁRIOS
                     ============================================================ -->
                <div class="screen" id="screen-usuarios">

                    <p class="screen-intro">Gerencie os anunciantes cadastrados no GunesAds e o plano de cada um.</p>

                    <div class="screen-tool-bar">
                        <div class="list-count">Total: <b id="usuarios-count">0</b> usuários</div>
                        <button type="button" class="btn-novo-usuario" id="btn-novo-usuario">
                            <i class="bi bi-person-plus-fill"></i> Novo usuário
                        </button>
                    </div>

                    <!--
                        ============================================================
                        Uma .user-row por linha da tabela "usuarios".
                        Campos vindos de $usuario no PHP:
                          id, nome, empresa, email, telefone,
                          plano ('Básico'|'Profissional'|'Premium'),
                          status ('ativo'|'aguardando'|'inativo').

                        Guardados em atributos data-* na própria linha, para o JS
                        ler direto do HTML renderizado. Use htmlspecialchars() em
                        nome e empresa. As iniciais do avatar podem vir prontas do
                        PHP (ex.: função iniciais($nome)) ou ser calculadas no JS,
                        como está aqui.

                        Se $usuarios estiver vazio, renderize só o bloco
                        #empty-usuarios (mais abaixo) e omita a .users-list.
                        ============================================================
                    -->
                    <div class="users-list" id="users-list">

                        <!-- INÍCIO DO LOOP: repetir para cada $usuario em $usuarios -->
                        <div
                            class="user-row"
                            data-id="1"
                            data-nome="Arthur J. Lima"
                            data-empresa="Loja do João"
                            data-email="arthur.lima@email.com"
                            data-telefone="(11) 98888-7766"
                            data-plano="Profissional"
                            data-status="ativo"
                        >
                            <div class="user-row-avatar">AL</div>
                            <div class="user-row-info">
                                <div class="user-row-name">Arthur J. Lima</div>
                                <div class="user-row-sub">
                                    <span><i class="bi bi-shop"></i><span class="sub-empresa">Loja do João</span></span>
                                    <span><i class="bi bi-envelope"></i><span class="sub-email">arthur.lima@email.com</span></span>
                                </div>
                            </div>
                            <span class="status-badge ativo"><i class="bi bi-check-circle-fill"></i> Ativo</span>
                            <select class="user-row-plano" data-action="plano">
                                <option value="Básico">Básico</option>
                                <option value="Profissional" selected>Profissional</option>
                                <option value="Premium">Premium</option>
                            </select>
                            <div class="user-row-actions">
                                <button class="btn-act" type="button" data-action="editar-usuario" title="Editar dados"><i class="bi bi-pencil"></i></button>
                                <button class="btn-act" type="button" data-action="status-usuario" title="Desativar"><i class="bi bi-slash-circle"></i></button>
                            </div>
                        </div>

                        <div
                            class="user-row"
                            data-id="2"
                            data-nome="Vitória Nogueira"
                            data-empresa="Pizzaria Dom Vitto"
                            data-email="vitoria@domvitto.com.br"
                            data-telefone="(11) 97777-2211"
                            data-plano="Premium"
                            data-status="ativo"
                        >
                            <div class="user-row-avatar">VN</div>
                            <div class="user-row-info">
                                <div class="user-row-name">Vitória Nogueira</div>
                                <div class="user-row-sub">
                                    <span><i class="bi bi-shop"></i><span class="sub-empresa">Pizzaria Dom Vitto</span></span>
                                    <span><i class="bi bi-envelope"></i><span class="sub-email">vitoria@domvitto.com.br</span></span>
                                </div>
                            </div>
                            <span class="status-badge ativo"><i class="bi bi-check-circle-fill"></i> Ativo</span>
                            <select class="user-row-plano" data-action="plano">
                                <option value="Básico">Básico</option>
                                <option value="Profissional">Profissional</option>
                                <option value="Premium" selected>Premium</option>
                            </select>
                            <div class="user-row-actions">
                                <button class="btn-act" type="button" data-action="editar-usuario" title="Editar dados"><i class="bi bi-pencil"></i></button>
                                <button class="btn-act" type="button" data-action="status-usuario" title="Desativar"><i class="bi bi-slash-circle"></i></button>
                            </div>
                        </div>

                        <div
                            class="user-row"
                            data-id="3"
                            data-nome="Marcos Herrera"
                            data-empresa="Vértice Consultoria"
                            data-email="marcos@verticeconsult.com.br"
                            data-telefone="(11) 96666-4433"
                            data-plano="Básico"
                            data-status="aguardando"
                        >
                            <div class="user-row-avatar">MH</div>
                            <div class="user-row-info">
                                <div class="user-row-name">Marcos Herrera</div>
                                <div class="user-row-sub">
                                    <span><i class="bi bi-shop"></i><span class="sub-empresa">Vértice Consultoria</span></span>
                                    <span><i class="bi bi-envelope"></i><span class="sub-email">marcos@verticeconsult.com.br</span></span>
                                </div>
                            </div>
                            <span class="status-badge aguardando"><i class="bi bi-hourglass-split"></i> Aguardando 1º acesso</span>
                            <select class="user-row-plano" data-action="plano">
                                <option value="Básico" selected>Básico</option>
                                <option value="Profissional">Profissional</option>
                                <option value="Premium">Premium</option>
                            </select>
                            <div class="user-row-actions">
                                <button class="btn-act" type="button" data-action="editar-usuario" title="Editar dados"><i class="bi bi-pencil"></i></button>
                                <button class="btn-act" type="button" data-action="status-usuario" title="Desativar"><i class="bi bi-slash-circle"></i></button>
                            </div>
                        </div>

                        <div
                            class="user-row inactive"
                            data-id="4"
                            data-nome="Bella Ferraz"
                            data-empresa="Studio Bella Estética"
                            data-email="contato@studiobella.com.br"
                            data-telefone="(11) 95555-8899"
                            data-plano="Profissional"
                            data-status="inativo"
                        >
                            <div class="user-row-avatar">BF</div>
                            <div class="user-row-info">
                                <div class="user-row-name">Bella Ferraz</div>
                                <div class="user-row-sub">
                                    <span><i class="bi bi-shop"></i><span class="sub-empresa">Studio Bella Estética</span></span>
                                    <span><i class="bi bi-envelope"></i><span class="sub-email">contato@studiobella.com.br</span></span>
                                </div>
                            </div>
                            <span class="status-badge inativo"><i class="bi bi-slash-circle-fill"></i> Inativo</span>
                            <select class="user-row-plano" data-action="plano">
                                <option value="Básico">Básico</option>
                                <option value="Profissional" selected>Profissional</option>
                                <option value="Premium">Premium</option>
                            </select>
                            <div class="user-row-actions">
                                <button class="btn-act toggle-on" type="button" data-action="status-usuario" title="Reativar"><i class="bi bi-arrow-counterclockwise"></i></button>
                                <button class="btn-act" type="button" data-action="editar-usuario" title="Editar dados"><i class="bi bi-pencil"></i></button>
                            </div>
                        </div>
                        <!-- FIM DO LOOP -->

                    </div>

                    <!-- Estado vazio: <?php if (empty($usuarios)): ?> ... <?php endif; ?> -->
                    <div class="empty-state" id="empty-usuarios">
                        <i class="bi bi-people"></i>
                        <h2>Nenhum usuário cadastrado</h2>
                        <p>Cadastre o primeiro anunciante para que ele possa acessar o painel e enviar campanhas.</p>
                    </div>

                </div>

                <!-- ============================================================
                     TELA: PERFIL (do administrador logado)
                     ============================================================ -->
                <div class="screen" id="screen-perfil">

                    <p class="screen-intro">Seus dados de acesso ao painel administrativo.</p>

                    <!--
                        ============================================================
                        Bloco só de leitura, preenchido a partir da sessão do
                        admin logado. Fonte:
                          $_SESSION['admin']['nome']
                          $_SESSION['admin']['avatar_url'] (opcional — se vazio,
                            usar as iniciais calculadas do nome, mesma lógica do
                            avatar da topbar)
                          $_SESSION['admin']['cargo'] (ex.: "Administrador")

                        Este mesmo nome/iniciais também aparece na topbar
                        (#user-info) — vem da MESMA sessão, para não haver o
                        clássico problema de nome/avatar dessincronizado entre
                        dois pontos da tela.
                        ============================================================
                    -->
                    <div class="profile-hero" id="perfil-hero" data-nome="Arthur Henrique" data-cargo="Administrador">
                        <div class="profile-avatar-wrap">
                            <div class="profile-avatar-lg" id="perfil-avatar-preview">AH</div>
                            <div class="profile-avatar-edit" id="avatar-edit-btn" title="Alterar foto">
                                <i class="bi bi-camera-fill"></i>
                            </div>
                            <!-- Sem backend ainda: só troca o preview localmente.
                                 No PHP, "avatar_image" chega em $_FILES dentro do #perfilForm. -->
                            <input type="file" id="avatar-file" name="avatar_image" accept=".jpg,.jpeg,.png,image/jpeg,image/png" style="display:none" form="perfil-form">
                        </div>
                        <div class="profile-hero-info">
                            <div class="profile-hero-name">Arthur Henrique</div>
                            <div class="profile-hero-badges">
                                <span class="info-badge plan"><i class="bi bi-shield-fill-check"></i> Administrador</span>
                            </div>
                        </div>
                    </div>

                    <!--
                        Formulário pronto para integração com PHP:
                          - method="post" + enctype multipart (upload de avatar)
                          - cada campo tem "name" (é o que o PHP lê em $_POST/$_FILES)
                          - "action" vazio de propósito — aponte para o script real
                          - preventDefault() no JS evita reload aqui no protótipo;
                            remova-o quando o form for enviar de verdade
                    -->
                    <form class="profile-grid" id="perfil-form" method="post" action="" enctype="multipart/form-data" autocomplete="off">

                        <div class="profile-card">
                            <div class="profile-card-title"><i class="bi bi-person-fill"></i> Dados pessoais</div>
                            <p class="profile-card-desc">Suas informações de acesso ao painel.</p>

                            <label for="perfil-nome">Nome completo</label>
                            <input type="text" id="perfil-nome" name="full_name" autocomplete="name" value="Arthur Henrique">

                            <label for="perfil-email">E-mail</label>
                            <input type="email" id="perfil-email" name="email" autocomplete="email" value="arthur@gunesads.com.br">

                            <label for="perfil-telefone">Telefone</label>
                            <input type="tel" id="perfil-telefone" name="phone" autocomplete="tel" placeholder="(99) 99999-9999" value="(11) 99999-0000">

                            <div class="form-actions">
                                <button class="btn-outline" type="reset">Cancelar</button>
                                <button class="btn-primary" type="submit">Salvar alterações</button>
                            </div>
                        </div>

                        <!-- Segurança / alterar senha — form separado, envia só os 3 campos de senha -->
                        <div class="profile-card">
                            <div class="profile-card-title"><i class="bi bi-shield-lock-fill"></i> Segurança</div>
                            <p class="profile-card-desc">Altere sua senha de acesso periodicamente.</p>

                            <label for="senha-atual">Senha atual</label>
                            <input type="password" id="senha-atual" name="current_password" autocomplete="current-password" placeholder="Digite sua senha atual">

                            <label for="senha-nova">Nova senha</label>
                            <input type="password" id="senha-nova" name="new_password" autocomplete="new-password" placeholder="Mínimo de 8 caracteres">

                            <label for="senha-confirmar">Confirmar nova senha</label>
                            <input type="password" id="senha-confirmar" name="confirm_password" autocomplete="new-password" placeholder="Repita a nova senha">

                            <div class="field-error" id="err-senha">As senhas não coincidem.</div>

                            <div class="form-actions">
                                <button class="btn-primary" type="button" id="btn-salvar-senha">Atualizar senha</button>
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        </div>

    </main>

    <!-- ======================= MODAL: CADASTRAR / EDITAR ANÚNCIO ADMIN ======================= -->
    <div class="modal-overlay" id="modal-anuncio">
        <div class="modal-box wide">
            <div class="modal-head">
                <div>
                    <h3 id="modal-anuncio-titulo">Novo anúncio</h3>
                    <div class="modal-sub">Anúncio institucional / de teste</div>
                </div>
                <button class="modal-close" type="button" data-close="modal-anuncio"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <!--
                    FORM REAL, PRONTO PARA O PHP:
                      - method="post" + enctype multipart (por causa do upload)
                      - action="server/salvar_anuncio_admin.php" -> UM script só
                        cuida de criar E editar. A regra é simples:
                          $id = $_POST['anuncio_id'] ?? null;
                          if ($id) { ... UPDATE ... } else { ... INSERT ... }
                        Por isso o mesmo modal/form serve pros dois casos — o
                        que muda é só se o campo "anuncio_id" veio vazio ou não.
                      - CADA campo tem "name": é a chave que vai aparecer em
                        $_POST (ou $_FILES, no caso do arquivo). Sem "name", o
                        PHP não recebe nada, não importa o que o JS faça.
                -->
                <form id="anuncio-form" name="anuncioForm" method="post" action="server/salvar_anuncio_admin.php" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" id="anuncio-id" name="anuncio_id" value="">
                    <!-- guarda a URL da imagem atual (edição), pro PHP saber
                         qual arquivo já existe caso nenhuma imagem nova seja enviada -->
                    <input type="hidden" id="anuncio-imagem-atual" name="ad_image_atual" value="">

                    <label>Imagem do anúncio (opcional)</label>
                    <div class="upload-zone-preview" id="anuncio-img-preview"></div>
                    <div class="upload-zone" id="anuncio-upload-zone">
                        <span class="upicon"><i class="bi bi-cloud-arrow-up"></i></span>
                        <div id="anuncio-upload-label">Clique para enviar a imagem (JPEG, JPG ou PNG). Sem imagem, um fundo colorido é usado no lugar.</div>
                        <input type="file" id="anuncio-file" name="ad_image" accept=".jpg,.jpeg,.png,image/jpeg,image/png" style="display:none">
                    </div>

                    <label for="anuncio-nome">Nome do anúncio</label>
                    <input type="text" id="anuncio-nome" name="ad_nome" placeholder="Ex: Lojinha do Carro">
                    <div class="field-error" id="err-anuncio-nome">Informe o nome do anúncio.</div>

                    <label for="anuncio-descricao">Descrição / chamada</label>
                    <input type="text" id="anuncio-descricao" name="ad_descricao" placeholder="Ex: Água, balas e salgadinhos disponíveis a bordo">
                    <div class="field-error" id="err-anuncio-descricao">Informe a descrição do anúncio.</div>

                    <label for="anuncio-categoria">Categoria</label>
                    <select id="anuncio-categoria" name="ad_categoria">
                        <option value="Institucional">Institucional</option>
                        <option value="Anuncie aqui">Anuncie aqui</option>
                        <option value="Produto da motorista">Produto da motorista</option>
                        <option value="Teste">Teste</option>
                    </select>
                </form>

                <div class="modal-info-box">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Esse anúncio não tem anunciante nem cobrança associada — ele roda no carrossel junto aos anúncios dos usuários, seguindo o mesmo padrão visual.</span>
                </div>
            </div>
            <div class="modal-foot">
                <button class="modal-btn ghost" type="button" data-close="modal-anuncio">Cancelar</button>
                <!-- type="submit" + form="anuncio-form": manda o <form> acima
                     de verdade, mesmo o botão estando fisicamente fora dele
                     (aqui no rodapé do modal). Sem isso, o botão não manda nada. -->
                <button class="modal-btn primary" type="submit" form="anuncio-form" id="btn-salvar-anuncio">Salvar anúncio</button>
            </div>
        </div>
    </div>

    <!-- ======================= MODAL: CONFIRMAR EXCLUSÃO DE ANÚNCIO ======================= -->
    <div class="modal-overlay" id="modal-excluir-anuncio">
        <div class="modal-box">
            <div class="modal-head">
                <div><h3>Excluir anúncio?</h3></div>
                <button class="modal-close" type="button" data-close="modal-excluir-anuncio"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <div class="modal-warning-box">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>O anúncio <b id="excluir-anuncio-nome">este anúncio</b> será removido e deixará de aparecer no carrossel dos tablets.</span>
                </div>
            </div>
            <!--
                Form próprio, separado do de editar: um DELETE não deve
                dividir form com um INSERT/UPDATE. Só manda o id.
            -->
            <form id="excluir-anuncio-form" method="post" action="server/excluir_anuncio_admin.php">
                <input type="hidden" id="excluir-anuncio-id" name="anuncio_id" value="">
                <div class="modal-foot">
                    <button class="modal-btn ghost" type="button" data-close="modal-excluir-anuncio">Cancelar</button>
                    <button class="modal-btn danger" type="submit" id="btn-confirmar-excluir-anuncio">Sim, excluir</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================= MODAL: CADASTRAR / EDITAR USUÁRIO ======================= -->
    <div class="modal-overlay" id="modal-usuario">
        <div class="modal-box">
            <div class="modal-head">
                <div>
                    <h3 id="modal-usuario-titulo">Novo usuário</h3>
                    <div class="modal-sub">Dados do anunciante</div>
                </div>
                <button class="modal-close" type="button" data-close="modal-usuario"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <!--
                    Mesmo esquema do form de anúncio: UM script (INSERT ou UPDATE
                    conforme $_POST['usuario_id'] vir vazio ou não), campos com
                    "name" pra chegar em $_POST, botão "submit" vinculado via
                    form="usuario-form" lá embaixo no rodapé do modal.
                -->
                <form id="usuario-form" name="usuarioForm" method="post" action="server/salvar_usuario.php" autocomplete="off">
                    <input type="hidden" id="usuario-id" name="usuario_id" value="">

                    <label for="usuario-nome">Nome completo</label>
                    <input type="text" id="usuario-nome" name="nome" placeholder="Ex: Arthur J. Lima">
                    <div class="field-error" id="err-usuario-nome">Informe o nome completo.</div>

                    <label for="usuario-email">E-mail</label>
                    <input type="email" id="usuario-email" name="email" placeholder="voce@exemplo.com">
                    <div class="field-error" id="err-usuario-email">Informe um e-mail válido.</div>

                    <label for="usuario-empresa">Nome da empresa/serviço</label>
                    <input type="text" id="usuario-empresa" name="empresa" placeholder="Ex: Loja do João">

                    <label for="usuario-telefone">Telefone</label>
                    <input type="tel" id="usuario-telefone" name="telefone" placeholder="(99) 99999-9999">

                    <label for="usuario-plano">Plano / nível de acesso</label>
                    <select id="usuario-plano" name="plano">
                        <option value="Básico">Básico</option>
                        <option value="Profissional" selected>Profissional</option>
                        <option value="Premium">Premium</option>
                    </select>
                </form>

                <div class="modal-info-box" id="senha-padrao-aviso">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>O usuário não define a senha no cadastro. Ele receberá uma <b>senha padrão</b> e será obrigado a alterá-la no primeiro acesso ao painel.</span>
                </div>
            </div>
            <div class="modal-foot">
                <button class="modal-btn ghost" type="button" data-close="modal-usuario">Cancelar</button>
                <button class="modal-btn primary" type="submit" form="usuario-form" id="btn-salvar-usuario">Salvar usuário</button>
            </div>
        </div>
    </div>

    <!-- ======================= MODAL: CONFIRMAR DESATIVAÇÃO/REATIVAÇÃO ======================= -->
    <div class="modal-overlay" id="modal-status-usuario">
        <div class="modal-box">
            <div class="modal-head">
                <div><h3 id="modal-status-titulo">Desativar usuário?</h3></div>
                <button class="modal-close" type="button" data-close="modal-status-usuario"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <div class="modal-warning-box" id="modal-status-texto">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>O usuário perderá o acesso ao painel até ser reativado.</span>
                </div>
            </div>
            <!-- Só manda o id; o PHP decide ativar ou desativar olhando o
                 status atual do usuário no banco (não precisa mandar o novo
                 status pelo front, evita manipulação). -->
            <form id="status-usuario-form" method="post" action="server/alternar_status_usuario.php">
                <input type="hidden" id="status-usuario-id" name="usuario_id" value="">
                <div class="modal-foot">
                    <button class="modal-btn ghost" type="button" data-close="modal-status-usuario">Cancelar</button>
                    <button class="modal-btn danger" type="submit" id="btn-confirmar-status-usuario">Confirmar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        /* ====================================================================
           GUNESADS — PAINEL ADMIN — LÓGICA
           Sumário:
             1. Navegação entre telas
             2. Sidebar responsiva
             3. Menu dropdown do usuário
             4. Sistema de modais (abrir/fechar genérico)
             5. Anúncios do Admin (cards, upload, modais, ações)
             6. Usuários (linhas, modais, ações)
             7. Perfil (avatar, dados, senha)
             8. Toasts
           ==================================================================== */

        /* ---- 1. Navegação entre telas -------------------------------------- */
        const screenTitles = {
            'anuncios-admin': 'Anúncios do Admin',
            usuarios: 'Usuários',
            perfil: 'Meu Perfil',
        };

        const sidebar = document.getElementById('sidebar');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');

        function irParaTela(screenId) {
            document.querySelectorAll('.nav-item').forEach((b) => b.classList.remove('active'));
            document.querySelectorAll('.screen').forEach((s) => s.classList.remove('active'));

            const navButton = document.querySelector(`.nav-item[data-screen="${screenId}"]`);
            if (navButton) navButton.classList.add('active');

            document.getElementById(`screen-${screenId}`).classList.add('active');
            document.getElementById('screen-title').innerText = screenTitles[screenId];

            if (window.innerWidth < 900) {
                sidebar.classList.remove('mobile-open');
                sidebarBackdrop.classList.remove('show');
            }
        }

        document.querySelectorAll('.nav-item[data-screen]').forEach((button) => {
            button.addEventListener('click', () => irParaTela(button.dataset.screen));
        });

        // "Perfil" é acessado pelo dropdown do avatar (não tem item na sidebar)
        document.querySelector('.user-menu-item[data-screen="perfil"]').addEventListener('click', () => irParaTela('perfil'));

        /* ---- 2. Sidebar responsiva ------------------------------------------- */
        const menuToggle = document.getElementById('menu-toggle');

        function applyResponsiveSidebarDefault() {
            const width = window.innerWidth;
            sidebar.classList.remove('mobile-open');
            sidebarBackdrop.classList.remove('show');

            if (width < 900) {
                sidebar.classList.remove('collapsed');
            } else if (width < 1280) {
                sidebar.classList.add('collapsed');
            } else {
                sidebar.classList.remove('collapsed');
            }
        }

        window.addEventListener('resize', applyResponsiveSidebarDefault);
        applyResponsiveSidebarDefault();

        menuToggle.addEventListener('click', () => {
            if (window.innerWidth < 900) {
                sidebar.classList.toggle('mobile-open');
                sidebarBackdrop.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        });

        sidebarBackdrop.addEventListener('click', () => {
            sidebar.classList.remove('mobile-open');
            sidebarBackdrop.classList.remove('show');
        });

        /* ---- 3. Menu dropdown do usuário -------------------------------------- */
        const userInfo = document.getElementById('user-info');
        const userMenu = document.getElementById('user-menu');

        userInfo.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('show');
        });

        document.addEventListener('click', () => userMenu.classList.remove('show'));

        /* ---- 4. Sistema de modais (abrir/fechar genérico) --------------------- */
        function abrirModal(id) {
            document.getElementById(id).classList.add('show');
        }

        function fecharModal(id) {
            document.getElementById(id).classList.remove('show');
        }

        document.querySelectorAll('[data-close]').forEach((btn) => {
            btn.addEventListener('click', () => fecharModal(btn.dataset.close));
        });

        document.querySelectorAll('.modal-overlay').forEach((overlay) => {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) overlay.classList.remove('show');
            });
        });

        /* ====================================================================
           5. ANÚNCIOS DO ADMIN
           Os cards já vêm prontos no HTML (no futuro, gerados pelo PHP com
           foreach $anuncios as $anuncio). O JS lê e atualiza direto os
           elementos que já estão na página — sem guardar cópia em array.
           ==================================================================== */
        const adsGrid = document.getElementById('ads-grid');
        const emptyAnuncios = document.getElementById('empty-anuncios');

        function atualizarEstadoVazioAnuncios() {
            const temCards = adsGrid.querySelector('.admin-ad-card') !== null;
            emptyAnuncios.classList.toggle('show', !temCards);
        }
        atualizarEstadoVazioAnuncios();

        function getAdCard(id) {
            return adsGrid.querySelector(`.admin-ad-card[data-id="${id}"]`);
        }

        /* Upload de imagem do anúncio, com preview.
           O ARQUIVO em si vai pro PHP pelo próprio <input type="file"
           name="ad_image">, dentro do <form> — é assim que $_FILES['ad_image']
           chega no servidor. O JS aqui só cuida da prévia visual, não precisa
           (nem deve) guardar o arquivo em nenhum outro lugar. */
        const anuncioFile = document.getElementById('anuncio-file');
        const anuncioUploadZone = document.getElementById('anuncio-upload-zone');
        const anuncioUploadLabel = document.getElementById('anuncio-upload-label');
        const anuncioImgPreview = document.getElementById('anuncio-img-preview');
        // Hidden que guarda a URL da imagem JÁ CADASTRADA (edição) — usado
        // pelo PHP só se nenhum arquivo novo for enviado no upload.
        const anuncioImagemAtual = document.getElementById('anuncio-imagem-atual');

        anuncioUploadZone.addEventListener('click', () => anuncioFile.click());

        anuncioFile.addEventListener('change', () => {
            const file = anuncioFile.files[0];
            if (!file) return;

            const allowedTypes = ['image/jpeg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                anuncioUploadLabel.innerText = 'Formato inválido. Envie apenas JPEG, JPG ou PNG.';
                anuncioUploadLabel.style.color = 'var(--danger)';
                anuncioFile.value = '';
                return;
            }

            anuncioUploadLabel.style.color = '';
            anuncioUploadLabel.innerText = file.name;

            const objectUrl = URL.createObjectURL(file);
            anuncioImgPreview.style.backgroundImage = `url('${objectUrl}')`;
            anuncioImgPreview.classList.add('show');
        });

        function abrirModalAnuncio(modo, id) {
            document.getElementById('anuncio-form').reset();
            document.getElementById('err-anuncio-nome').classList.remove('show');
            document.getElementById('err-anuncio-descricao').classList.remove('show');
            anuncioImagemAtual.value = '';
            anuncioImgPreview.classList.remove('show');
            anuncioImgPreview.style.backgroundImage = '';
            anuncioUploadLabel.style.color = '';
            anuncioUploadLabel.innerText = 'Clique para enviar a imagem (JPEG, JPG ou PNG). Sem imagem, um fundo colorido é usado no lugar.';

            if (modo === 'novo') {
                document.getElementById('modal-anuncio-titulo').textContent = 'Novo anúncio';
                document.getElementById('anuncio-id').value = '';
                document.getElementById('anuncio-form').action = 'server/salvar_anuncio_admin.php';
            } else {
                const card = getAdCard(id);
                if (!card) return;
                document.getElementById('modal-anuncio-titulo').textContent = 'Editar anúncio';
                document.getElementById('anuncio-id').value = id;
                document.getElementById('anuncio-nome').value = card.dataset.nome;
                document.getElementById('anuncio-descricao').value = card.dataset.descricao;
                document.getElementById('anuncio-categoria').value = card.dataset.categoria;

                if (card.dataset.imagem) {
                    anuncioImagemAtual.value = card.dataset.imagem;
                    anuncioImgPreview.style.backgroundImage = `url('${card.dataset.imagem}')`;
                    anuncioImgPreview.classList.add('show');
                    anuncioUploadLabel.innerText = 'Imagem atual — clique para substituir';
                }
            }

            abrirModal('modal-anuncio');
        }

        document.getElementById('btn-novo-anuncio').addEventListener('click', () => abrirModalAnuncio('novo'));

        // ⚠️ Este listener escuta o SUBMIT do <form id="anuncio-form">, não um
        // clique solto de botão — dispara tanto se apertar Enter quanto se
        // clicar no "Salvar anúncio" do rodapé (ele é type="submit" e tem
        // form="anuncio-form").
        //
        // Aqui no protótipo, e.preventDefault() bloqueia o envio de verdade
        // (porque "server/salvar_anuncio_admin.php" ainda não existe) e só
        // simula o resultado atualizando o card na tela. QUANDO O SCRIPT PHP
        // EXISTIR, é só apagar o e.preventDefault() (e o resto do bloco de
        // simulação, se quiser) — o <form> já está 100% pronto pra mandar
        // pro servidor sozinho.
        document.getElementById('anuncio-form').addEventListener('submit', (e) => {
            const id = document.getElementById('anuncio-id').value;
            const nome = document.getElementById('anuncio-nome').value.trim();
            const descricao = document.getElementById('anuncio-descricao').value.trim();
            const categoria = document.getElementById('anuncio-categoria').value;

            let valido = true;
            document.getElementById('err-anuncio-nome').classList.toggle('show', !nome);
            document.getElementById('err-anuncio-descricao').classList.toggle('show', !descricao);
            if (!nome || !descricao) valido = false;

            if (!valido) return;

            if (id) {
                const card = getAdCard(id);
                if (card) {
                    card.dataset.nome = nome;
                    card.dataset.descricao = descricao;
                    card.dataset.categoria = categoria;
                    card.querySelector('.ad-title').textContent = nome;
                    card.querySelector('.ad-categoria').innerHTML = `<i class="bi bi-tag"></i> ${categoria}`;
                    card.querySelector('.ad-descricao').textContent = descricao;

                    const novaImagem = anuncioImgPreview.style.backgroundImage;
                    if (novaImagem && novaImagem !== 'none') {
                        const url = novaImagem.slice(5, -2);
                        card.dataset.imagem = url;
                        const img = card.querySelector('.img-ad-thumb');
                        img.src = url;
                        img.classList.add('show');
                        const fallback = card.querySelector('.thumb-fallback');
                        if (fallback) fallback.style.display = 'none';
                    }
                }
                mostrarAlerta('Anúncio atualizado', `As alterações em "${nome}" foram salvas`, 'sucesso');
            } else {
                // CADASTRO NOVO: de propósito, não criamos um card via JS — isso
                // seria o JS "inventando" HTML. Quando o form realmente submeter
                // pro server/salvar_anuncio_admin.php, o PHP faz o INSERT e
                // recarrega a página; o novo card aparece porque vem do foreach.
                mostrarAlerta('Anúncio pronto para salvar', `"${nome}" vai aparecer na lista assim que o servidor "server/salvar_anuncio_admin.php" existir`, 'info');
            }

            fecharModal('modal-anuncio');
        });

        function alternarStatusAnuncio(id) {
            const card = getAdCard(id);
            if (!card) return;

            // No PHP real: pequeno <form> fazendo UPDATE de status no banco
            // (ex.: action="pausar_anuncio_admin.php").
            const ativo = card.dataset.status === 'ativo';
            const novoStatus = ativo ? 'pausado' : 'ativo';
            card.dataset.status = novoStatus;

            const badge = card.querySelector('.ad-status');
            badge.classList.toggle('pausado', novoStatus === 'pausado');
            badge.textContent = novoStatus === 'ativo' ? 'Ativo' : 'Pausado';

            const btnPausar = card.querySelector('[data-action="pausar-anuncio"]');
            btnPausar.title = novoStatus === 'ativo' ? 'Pausar' : 'Retomar';
            btnPausar.querySelector('i').className = novoStatus === 'ativo' ? 'bi bi-pause-fill' : 'bi bi-play-fill';

            mostrarAlerta(
                novoStatus === 'ativo' ? 'Anúncio retomado' : 'Anúncio pausado',
                `"${card.dataset.nome}" ${novoStatus === 'ativo' ? 'voltou a entrar na rotação' : 'não entra mais na rotação até ser retomado'}`,
                novoStatus === 'ativo' ? 'sucesso' : 'aviso'
            );
        }

        let anuncioSelecionadoId = null;

        function abrirConfirmarExcluirAnuncio(id) {
            const card = getAdCard(id);
            if (!card) return;
            anuncioSelecionadoId = id;
            document.getElementById('excluir-anuncio-nome').textContent = `"${card.dataset.nome}"`;
            document.getElementById('excluir-anuncio-id').value = id;
            abrirModal('modal-excluir-anuncio');
        }

        // Escuta o SUBMIT do <form id="excluir-anuncio-form">. O e.preventDefault()
        // aqui é só pra não navegar pro "server/excluir_anuncio_admin.php" que
        // ainda não existe — quando existir, é só remover essa linha e o form
        // manda o "anuncio_id" via POST sozinho (o PHP faz o DELETE).
        document.getElementById('excluir-anuncio-form').addEventListener('submit', (e) => {
            const card = getAdCard(anuncioSelecionadoId);
            fecharModal('modal-excluir-anuncio');
            if (!card) return;

            const nome = card.dataset.nome;
            card.classList.add('removing');
            setTimeout(() => {
                card.remove();
                atualizarEstadoVazioAnuncios();
            }, 220);

            mostrarAlerta('Anúncio excluído', `"${nome}" não aparece mais no carrossel`, 'erro');
        });

        // Delegação de eventos: cobre todos os cards, mesmo após re-renderizações
        adsGrid.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-action]');
            if (!btn) return;
            const id = btn.closest('.admin-ad-card').dataset.id;

            if (btn.dataset.action === 'editar-anuncio') abrirModalAnuncio('editar', id);
            if (btn.dataset.action === 'pausar-anuncio') alternarStatusAnuncio(id);
            if (btn.dataset.action === 'excluir-anuncio') abrirConfirmarExcluirAnuncio(id);
        });

        /* ====================================================================
           6. USUÁRIOS
           As linhas já vêm prontas no HTML (no futuro, geradas pelo PHP com
           foreach $usuarios as $usuario). O JS lê e atualiza direto os
           elementos que já estão na página.
           ==================================================================== */
        const usersList = document.getElementById('users-list');
        const usersCount = document.getElementById('usuarios-count');
        const emptyUsuarios = document.getElementById('empty-usuarios');

        function iniciais(nomeCompleto) {
            const partes = nomeCompleto.trim().split(/\s+/);
            const primeira = partes[0]?.[0] || '';
            const ultima = partes.length > 1 ? partes[partes.length - 1][0] : '';
            return (primeira + ultima).toUpperCase();
        }

        const STATUS_LABEL = {
            ativo: { texto: 'Ativo', icon: 'bi-check-circle-fill' },
            aguardando: { texto: 'Aguardando 1º acesso', icon: 'bi-hourglass-split' },
            inativo: { texto: 'Inativo', icon: 'bi-slash-circle-fill' },
        };

        function atualizarContadorUsuarios() {
            const linhas = usersList.querySelectorAll('.user-row').length;
            usersCount.textContent = linhas;
            emptyUsuarios.classList.toggle('show', linhas === 0);
        }
        atualizarContadorUsuarios();

        function getUserRow(id) {
            return usersList.querySelector(`.user-row[data-id="${id}"]`);
        }

        const userModalTitulo = document.getElementById('modal-usuario-titulo');
        const senhaPadraoAviso = document.getElementById('senha-padrao-aviso');
        const usuarioForm = document.getElementById('usuario-form');

        function abrirModalUsuario(modo, id) {
            usuarioForm.reset();
            document.getElementById('err-usuario-nome').classList.remove('show');
            document.getElementById('err-usuario-email').classList.remove('show');

            if (modo === 'novo') {
                userModalTitulo.textContent = 'Novo usuário';
                senhaPadraoAviso.style.display = 'flex';
                document.getElementById('usuario-id').value = '';
            } else {
                const row = getUserRow(id);
                if (!row) return;
                userModalTitulo.textContent = 'Editar usuário';
                senhaPadraoAviso.style.display = 'none'; // não se aplica na edição
                document.getElementById('usuario-id').value = id;
                document.getElementById('usuario-nome').value = row.dataset.nome;
                document.getElementById('usuario-email').value = row.dataset.email;
                document.getElementById('usuario-empresa').value = row.dataset.empresa;
                document.getElementById('usuario-telefone').value = row.dataset.telefone;
                document.getElementById('usuario-plano').value = row.dataset.plano;
            }

            abrirModal('modal-usuario');
        }

        document.getElementById('btn-novo-usuario').addEventListener('click', () => abrirModalUsuario('novo'));

        // Escuta o SUBMIT do <form id="usuario-form"> (o botão "Salvar usuário"
        // é type="submit" com form="usuario-form", então dispara este evento).
        // e.preventDefault() só existe pq "server/salvar_usuario.php" ainda não
        // existe — remova essa linha assim que o script PHP estiver no ar; o
        // form já está pronto (method, action e name de cada campo certos).
        usuarioForm.addEventListener('submit', (e) => {
            const id = document.getElementById('usuario-id').value;
            const nome = document.getElementById('usuario-nome').value.trim();
            const email = document.getElementById('usuario-email').value.trim();
            const empresa = document.getElementById('usuario-empresa').value.trim();
            const telefone = document.getElementById('usuario-telefone').value.trim();
            const plano = document.getElementById('usuario-plano').value;

            const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            document.getElementById('err-usuario-nome').classList.toggle('show', !nome);
            document.getElementById('err-usuario-email').classList.toggle('show', !emailValido);

            e.preventDefault(); // <- remover quando o PHP existir
            if (!nome || !emailValido) return;

            if (id) {
                // EDIÇÃO: atualiza a linha já existente (feedback visual do
                // protótipo). No PHP real: <form action="editar_usuario.php">
                // enviando "id" num campo hidden — UPDATE no banco, página
                // recarrega já com a linha atualizada, vinda do foreach.
                const row = getUserRow(id);
                if (row) {
                    row.dataset.nome = nome;
                    row.dataset.email = email;
                    row.dataset.empresa = empresa;
                    row.dataset.telefone = telefone;
                    row.dataset.plano = plano;

                    row.querySelector('.user-row-avatar').textContent = iniciais(nome);
                    row.querySelector('.user-row-name').textContent = nome;
                    row.querySelector('.sub-empresa').textContent = empresa || '—';
                    row.querySelector('.sub-email').textContent = email;
                    row.querySelector('.user-row-plano').value = plano;
                }
                mostrarAlerta('Usuário atualizado', `Os dados de ${nome} foram salvos`, 'sucesso');
            } else {
                // CADASTRO NOVO: de propósito, não criamos uma linha via JS. No
                // PHP real: <form action="criar_usuario.php"> gera a senha
                // padrão, faz o INSERT com status "aguardando" e redireciona de
                // volta — o novo usuário já aparece porque vem do foreach.
                mostrarAlerta('Usuário pronto para salvar', `${nome} vai aparecer na lista e receber a senha padrão assim que o formulário for enviado ao servidor`, 'sucesso');
            }

            fecharModal('modal-usuario');
        });

        let usuarioStatusSelecionadoId = null;

        function abrirConfirmarStatus(id) {
            const row = getUserRow(id);
            if (!row) return;
            usuarioStatusSelecionadoId = id;

            const vaiDesativar = row.dataset.status !== 'inativo';
            document.getElementById('modal-status-titulo').textContent = vaiDesativar ? 'Desativar usuário?' : 'Reativar usuário?';
            document.getElementById('modal-status-texto').innerHTML = vaiDesativar
                ? `<i class="bi bi-exclamation-triangle-fill"></i><span><b>${row.dataset.nome}</b> perderá o acesso ao painel até ser reativado. Os anúncios já ativos não são afetados automaticamente.</span>`
                : `<i class="bi bi-info-circle-fill"></i><span><b>${row.dataset.nome}</b> voltará a ter acesso normal ao painel.</span>`;

            document.getElementById('status-usuario-id').value = id;
            abrirModal('modal-status-usuario');
        }

        // Escuta o SUBMIT do <form id="status-usuario-form">. Remova o
        // e.preventDefault() quando "server/alternar_status_usuario.php" existir.
        document.getElementById('status-usuario-form').addEventListener('submit', (e) => {
            e.preventDefault(); // <- remover quando o PHP existir
            const row = getUserRow(usuarioStatusSelecionadoId);
            fecharModal('modal-status-usuario');
            if (!row) return;

            const vaiDesativar = row.dataset.status !== 'inativo';
            const novoStatus = vaiDesativar ? 'inativo' : 'ativo';
            row.dataset.status = novoStatus;
            row.classList.toggle('inactive', novoStatus === 'inativo');

            const st = STATUS_LABEL[novoStatus];
            const badge = row.querySelector('.status-badge');
            badge.className = `status-badge ${novoStatus}`;
            badge.innerHTML = `<i class="bi ${st.icon}"></i> ${st.texto}`;

            const btnStatus = row.querySelector('[data-action="status-usuario"]');
            btnStatus.classList.toggle('toggle-on', novoStatus === 'inativo');
            btnStatus.title = novoStatus === 'inativo' ? 'Reativar' : 'Desativar';
            btnStatus.querySelector('i').className = novoStatus === 'inativo' ? 'bi bi-arrow-counterclockwise' : 'bi bi-slash-circle';

            mostrarAlerta(
                vaiDesativar ? 'Usuário desativado' : 'Usuário reativado',
                `${row.dataset.nome} ${vaiDesativar ? 'não tem mais acesso ao painel' : 'já pode acessar o painel normalmente'}`,
                vaiDesativar ? 'aviso' : 'sucesso'
            );
        });

        usersList.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-action]');
            if (!btn || btn.tagName === 'SELECT') return;
            const id = btn.closest('.user-row').dataset.id;

            if (btn.dataset.action === 'editar-usuario') abrirModalUsuario('editar', id);
            if (btn.dataset.action === 'status-usuario') abrirConfirmarStatus(id);
        });

        // Troca de plano direto no <select> da linha — no PHP real vira um
        // pequeno form/AJAX que faz UPDATE plano no banco.
        usersList.addEventListener('change', (e) => {
            if (e.target.dataset.action !== 'plano') return;
            const row = e.target.closest('.user-row');
            row.dataset.plano = e.target.value;
            mostrarAlerta('Plano atualizado', `${row.dataset.nome} agora está no plano ${e.target.value}`, 'info');
        });

        /* ====================================================================
           7. PERFIL DO ADMINISTRADOR
           ==================================================================== */
        const avatarEditBtn = document.getElementById('avatar-edit-btn');
        const avatarFile = document.getElementById('avatar-file');
        const perfilAvatarPreview = document.getElementById('perfil-avatar-preview');
        const topbarAvatar = document.getElementById('topbar-avatar');

        avatarEditBtn.addEventListener('click', () => avatarFile.click());

        // Prévia local (sem backend ainda). No PHP, "avatar_image" chega em
        // $_FILES dentro do #perfil-form. O mesmo preview é replicado no avatar
        // da topbar só por comportamento de UI — os dois pontos mostram a
        // mesma foto do mesmo usuário logado.
        avatarFile.addEventListener('change', () => {
            const file = avatarFile.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);

            [perfilAvatarPreview, topbarAvatar].forEach((el) => {
                el.style.backgroundImage = `url('${url}')`;
                el.style.backgroundSize = 'cover';
                el.style.backgroundPosition = 'center';
                el.textContent = '';
            });
        });

        // Protótipo apenas: evita reload ao "salvar" o perfil. Remova quando o
        // "action" apontar para o script PHP real.
        document.getElementById('perfil-form').addEventListener('submit', (e) => {
            e.preventDefault();
            mostrarAlerta('Perfil atualizado', 'Suas informações foram salvas com sucesso', 'sucesso');
        });

        // Checagem de senha: só conveniência de UX. A validação real (senha
        // atual bater com o hash salvo, força mínima, etc.) precisa ser feita
        // no PHP ao processar o POST.
        document.getElementById('btn-salvar-senha').addEventListener('click', () => {
            const atual = document.getElementById('senha-atual').value;
            const nova = document.getElementById('senha-nova').value;
            const confirmar = document.getElementById('senha-confirmar').value;
            const erro = document.getElementById('err-senha');

            if (!atual || !nova || !confirmar) {
                mostrarAlerta('Preencha todos os campos', 'Informe a senha atual, a nova senha e a confirmação', 'aviso');
                return;
            }

            if (nova !== confirmar) {
                erro.classList.add('show');
                return;
            }

            erro.classList.remove('show');
            mostrarAlerta('Senha atualizada', 'Use a nova senha no seu próximo acesso', 'sucesso');
            document.getElementById('senha-atual').value = '';
            document.getElementById('senha-nova').value = '';
            document.getElementById('senha-confirmar').value = '';
        });

        /* ====================================================================
           8. TOASTS
           Uso: mostrarAlerta('Título', 'Subtítulo opcional', 'sucesso'|'erro'|'aviso'|'info')
           ==================================================================== */
        const TOAST_ICONS = {
            sucesso: 'bi-check-circle-fill',
            erro: 'bi-x-circle-fill',
            aviso: 'bi-exclamation-triangle-fill',
            info: 'bi-info-circle-fill',
        };
        const TOAST_DURATION_MS = 4000;

        function mostrarAlerta(mensagem, subtitulo, tipo) {
            const tipoFinal = TOAST_ICONS[tipo] ? tipo : 'info';
            const container = document.getElementById('toast-container');

            const toast = document.createElement('div');
            toast.className = `toast ${tipoFinal}`;
            toast.innerHTML = `
                <span class="toast-icon"><i class="bi ${TOAST_ICONS[tipoFinal]}"></i></span>
                <div class="toast-text">
                    <div class="toast-title"></div>
                    ${subtitulo ? '<div class="toast-sub"></div>' : ''}
                </div>
                <button class="toast-close" type="button" aria-label="Fechar alerta"><i class="bi bi-x"></i></button>
            `;
            toast.querySelector('.toast-title').textContent = mensagem;
            if (subtitulo) toast.querySelector('.toast-sub').textContent = subtitulo;

            container.appendChild(toast);

            const remover = () => {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 200);
            };

            toast.querySelector('.toast-close').addEventListener('click', remover);
            setTimeout(remover, TOAST_DURATION_MS);
        }
    </script>

</body>

</html>
