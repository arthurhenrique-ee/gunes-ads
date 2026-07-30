<?php 
  include "server/auth.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GunesAds — Painel do Usuário</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap"
    rel="stylesheet"
  >
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    rel="stylesheet"
  >

  <style>
    /* ==========================================================================
       GUNESADS — PAINEL DO USUÁRIO (V1 — Documento Mestre)
       --------------------------------------------------------------------------
       Este arquivo é a base definitiva de template PHP para o painel do
       cliente final. Conforme o Documento Mestre, o cadastro (usuário e
       anúncios) é feito exclusivamente pelo administrador — este painel é
       somente de consulta, com apenas duas exceções de escrita: foto de
       perfil e alteração de senha.

       Sumário deste arquivo:
         1. Reset básico + variáveis globais
         2. Layout base + sidebar responsiva
         3. Topbar + menu do usuário
         4. Tela: Meus Anúncios (somente leitura)
         5. Tela: Perfil (dados somente leitura + foto + alterar senha)
         6. Sistema de alertas (Toasts)
       ========================================================================== */

    /* -------------------------------------------------------------------- */
    /* 1. Reset + variáveis globais                                          */
    /* -------------------------------------------------------------------- */
    :root {
      --bg: #F4F5FB;
      --surface: #FFFFFF;
      --surface-2: #F7F8FD;
      --border: #EBECF5;

      --text: #2B2B40;
      --text-muted: #8A8CA5;

      --primary: #3E5EE0;
      --primary-dark: #2E48B8;
      --primary-light: #EBEEFE;

      --success: #17C666;
      --success-light: #E7FAF0;

      --warning: #F3A638;
      --warning-light: #FDF3E4;

      --danger: #F4433E;
      --danger-light: #FDECEB;

      --font-display: 'Poppins', sans-serif;
      --font-body: 'Inter', sans-serif;

      --shadow: 0 4px 18px rgba(43, 43, 64, 0.06);

      --sidebar-w-full: 240px;
      --sidebar-w-collapsed: 76px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      display: flex;
      min-height: 100vh;
      overflow-x: hidden;
      background: var(--bg);
      color: var(--text);
      font-family: var(--font-body);
    }

    /* -------------------------------------------------------------------- */
    /* 2. Layout base + sidebar responsiva                                   */
    /* -------------------------------------------------------------------- */
    .main {
      display: flex;
      flex: 1;
      flex-direction: column;
      min-width: 0;
    }

    .content {
      flex: 1;
      overflow-y: auto;
      padding: 32px;
    }

    .screen {
      display: none;
      animation: fadeIn 0.35s ease;
    }

    .screen.active {
      display: block;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(6px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .section-intro {
      max-width: 560px;
      margin-bottom: 22px;
      color: var(--text-muted);
      font-size: 13px;
    }

    .sidebar {
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      width: var(--sidebar-w-full);
      padding: 22px 16px;
      background: var(--surface);
      border-right: 1px solid var(--border);
      transition: width 0.2s ease, transform 0.2s ease;
    }

    .sidebar.collapsed {
      width: var(--sidebar-w-collapsed);
    }

    .sidebar.collapsed .logo-text,
    .sidebar.collapsed .nav-label,
    .sidebar.collapsed .nav-section-label {
      display: none;
    }

    .sidebar.collapsed .logo {
      justify-content: center;
      padding-right: 0;
      padding-left: 0;
    }

    .sidebar.collapsed .nav-item {
      justify-content: center;
      padding: 11px 0;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 9px;
      padding: 0 8px 28px 8px;
      font-family: var(--font-display);
      font-weight: 700;
      font-size: 19px;
      letter-spacing: 0.3px;
      color: var(--text);
    }

    .logo-badge {
      display: flex;
      flex-shrink: 0;
      align-items: center;
      justify-content: center;
      width: 32px;
      height: 32px;
      border-radius: 9px;
      background: linear-gradient(135deg, var(--primary), #6B8CFF);
      color: #fff;
      font-size: 14px;
      font-weight: 700;
    }

    .logo-accent {
      color: var(--primary);
    }

    .nav-section-label {
      padding: 10px 12px 8px;
      color: var(--text-muted);
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.6px;
      text-transform: uppercase;
    }

    nav {
      display: flex;
      flex-direction: column;
      gap: 3px;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      width: 100%;
      padding: 11px 14px;
      border: none;
      border-radius: 10px;
      background: none;
      color: var(--text-muted);
      font-family: var(--font-body);
      font-size: 14px;
      font-weight: 500;
      text-align: left;
      cursor: pointer;
      transition: background 0.15s, color 0.15s;
    }

    .nav-item:hover {
      background: var(--surface-2);
      color: var(--text);
    }

    .nav-item.active {
      background: var(--primary);
      color: #fff;
      box-shadow: 0 6px 14px rgba(62, 94, 224, 0.28);
    }

    .nav-icon {
      width: 18px;
      flex-shrink: 0;
      text-align: center;
      font-size: 15px;
    }

    @media (max-width: 899px) {
      .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 50;
        height: 100vh;
        width: var(--sidebar-w-full);
        transform: translateX(-100%);
        box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
      }

      .sidebar.collapsed {
        width: var(--sidebar-w-full);
      }

      .sidebar.collapsed .logo-text,
      .sidebar.collapsed .nav-label,
      .sidebar.collapsed .nav-section-label {
        display: block;
      }

      .sidebar.collapsed .nav-item {
        justify-content: flex-start;
        padding: 11px 14px;
      }

      .sidebar.mobile-open {
        transform: translateX(0);
      }
    }

    .sidebar-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 40;
      background: rgba(15, 17, 23, 0.35);
    }

    .sidebar-backdrop.show {
      display: block;
    }

    /* -------------------------------------------------------------------- */
    /* 3. Topbar + menu do usuário                                           */
    /* -------------------------------------------------------------------- */
    .topbar {
      display: flex;
      flex-shrink: 0;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      height: 68px;
      padding: 0 32px;
      background: var(--surface);
      border-bottom: 1px solid var(--border);
    }

    .topbar-left {
      display: flex;
      align-items: center;
      gap: 16px;
      min-width: 0;
    }

    .topbar h1 {
      overflow: hidden;
      font-family: var(--font-display);
      font-size: 20px;
      font-weight: 600;
      white-space: nowrap;
      text-overflow: ellipsis;
    }

    @media (max-width: 640px) {
      .topbar { padding: 0 16px; }
      .topbar h1 { font-size: 17px; }
    }

    @media (max-width: 420px) {
      .topbar h1 { font-size: 15px; }
    }

    .menu-toggle {
      display: flex;
      flex-shrink: 0;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      border: 1px solid var(--border);
      border-radius: 10px;
      background: var(--surface-2);
      color: var(--text);
      font-size: 16px;
      cursor: pointer;
    }

    .topbar-right {
      position: relative;
      display: flex;
      flex-shrink: 0;
      align-items: center;
      gap: 18px;
    }

    .avatar {
      display: flex;
      flex-shrink: 0;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--primary), #6B8CFF);
      color: #fff;
      font-family: var(--font-display);
      font-weight: 700;
      font-size: 13px;
    }

    .user-info {
      position: relative;
      display: flex;
      align-items: center;
      gap: 11px;
      padding: 6px 8px;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.15s;
    }

    .user-info:hover {
      background: var(--surface-2);
    }

    .user-hello {
      color: var(--text-muted);
      font-size: 13px;
    }

    .user-hello b {
      color: var(--text);
      font-weight: 700;
    }

    @media (max-width: 560px) {
      .user-hello { display: none; }
      .user-info { gap: 0; padding: 4px; }
    }

    .user-menu {
      display: none;
      position: absolute;
      top: 52px;
      right: 0;
      z-index: 20;
      width: 190px;
      padding: 8px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 12px;
      box-shadow: 0 12px 30px rgba(43, 43, 64, 0.14);
    }

    .user-menu.show { display: block; }

    .user-menu-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      border-radius: 8px;
      color: var(--text);
      font-size: 13.5px;
      cursor: pointer;
    }

    .user-menu-item:hover { background: var(--surface-2); }
    .user-menu-item.danger { color: var(--danger); }

    .user-menu-divider {
      height: 1px;
      margin: 6px 4px;
      background: var(--border);
    }

    /* -------------------------------------------------------------------- */
    /* 4. Meus Anúncios (somente leitura)                                    */
    /* -------------------------------------------------------------------- */
    .ads-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
    }

    @media (max-width: 1300px) {
      .ads-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 640px) {
      .ads-grid { grid-template-columns: 1fr; }
    }

    .ad-card {
      display: flex;
      flex-direction: column;
      overflow: hidden;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      box-shadow: var(--shadow);
    }

    .ad-card-thumb {
      position: relative;
      display: flex;
      align-items: flex-end;
      height: 110px;
      padding: 12px 14px;
      color: rgba(255, 255, 255, 0.85);
      font-size: 26px;
      background-size: cover;
      background-position: center;
    }

    .ad-card-thumb .thumb-fallback { opacity: 0.55; }

    .ad-card-body {
      display: flex;
      flex: 1;
      flex-direction: column;
      padding: 16px;
    }

    .ad-card-head {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 10px;
    }

    .ad-card-title {
      font-weight: 600;
      font-size: 14.5px;
      line-height: 1.35;
    }

    .badge {
      display: inline-flex;
      flex-shrink: 0;
      align-items: center;
      gap: 4px;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      white-space: nowrap;
    }

    .badge.ativo     { color: var(--success); background: var(--success-light); }
    .badge.pausado   { color: var(--warning); background: var(--warning-light); }
    .badge.encerrado { color: var(--text-muted); background: var(--surface-2); }

    /* Etiqueta compacta do plano contratado (nível + tempo de exibição).
       A duração em dias não é repetida aqui — já aparece nas datas e nos
       "dias restantes" logo abaixo, então a etiqueta fica só com o essencial
       para identificar o plano de forma rápida e visual. */
    .plan-pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 11px;
      color: var(--primary);
      background: var(--primary-light);
      border-radius: 20px;
      font-size: 11.5px;
      font-weight: 600;
      white-space: nowrap;
    }

    .ad-card-meta {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 8px 14px;
      margin-bottom: 12px;
      color: var(--text-muted);
      font-size: 12.5px;
    }

    .ad-card-meta span {
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .ad-card-metrics {
      margin-top: auto;
      padding-top: 12px;
      border-top: 1px dashed var(--border);
    }

    .ad-card-progress-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 5px;
      color: var(--text-muted);
      font-size: 11.5px;
    }

    .ad-card-progress-head b { color: var(--text); font-weight: 600; }

    .progress-track {
      width: 100%;
      height: 6px;
      background: var(--surface-2);
      border-radius: 20px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--primary), #6B8CFF);
      border-radius: 20px;
    }

    /* -------------------------------------------------------------------- */
    /* Estado vazio — quando o usuário ainda não tem anúncios cadastrados    */
    /* -------------------------------------------------------------------- */
    .empty-list-state {
      display: none;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 12px;
      padding: 60px 24px;
      text-align: center;
      background: var(--surface);
      border: 1px dashed var(--border);
      border-radius: 16px;
    }

    .empty-list-state.show { display: flex; }

    .empty-list-state i {
      font-size: 30px;
      color: var(--text-muted);
    }

    .empty-list-state h2 {
      font-family: var(--font-display);
      font-size: 16px;
      font-weight: 600;
    }

    .empty-list-state p {
      max-width: 340px;
      color: var(--text-muted);
      font-size: 13px;
    }

    /* -------------------------------------------------------------------- */
    /* 5. Perfil                                                             */
    /* -------------------------------------------------------------------- */
    .profile-hero {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 20px;
      padding: 26px;
      margin-bottom: 22px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 16px;
      box-shadow: var(--shadow);
    }

    .profile-avatar-wrap {
      position: relative;
      flex-shrink: 0;
    }

    .profile-avatar-lg {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 84px;
      height: 84px;
      background: linear-gradient(135deg, var(--primary), #6B8CFF);
      border-radius: 18px;
      color: #fff;
      font-family: var(--font-display);
      font-size: 26px;
      font-weight: 700;
      background-size: cover;
      background-position: center;
    }

    .profile-avatar-edit {
      position: absolute;
      right: -6px;
      bottom: -6px;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 30px;
      height: 30px;
      background: var(--primary);
      border: 3px solid var(--surface);
      border-radius: 50%;
      color: #fff;
      font-size: 13px;
      cursor: pointer;
    }

    .profile-avatar-edit:hover {
      background: var(--primary-dark);
    }

    .profile-hero-info {
      flex: 1;
      min-width: 200px;
    }

    .profile-hero-name {
      margin-bottom: 6px;
      font-family: var(--font-display);
      font-size: 19px;
      font-weight: 600;
    }

    .profile-hero-badges {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .info-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 12px;
      background: var(--surface-2);
      border-radius: 20px;
      color: var(--text-muted);
      font-size: 12px;
      font-weight: 500;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .status-badge.ativo   { color: var(--success); background: var(--success-light); }
    .status-badge.inativo { color: var(--text-muted); background: var(--surface-2); }

    .profile-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 20px;
    }

    @media (max-width: 900px) {
      .profile-grid { grid-template-columns: 1fr; }
    }

    .profile-card {
      padding: 22px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 16px;
      box-shadow: var(--shadow);
    }

    .profile-card-title {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 4px;
      color: var(--text);
      font-family: var(--font-display);
      font-size: 15px;
      font-weight: 600;
    }

    .profile-card-title i {
      color: var(--primary);
    }

    .profile-card-desc {
      margin-bottom: 16px;
      color: var(--text-muted);
      font-size: 12.5px;
    }

    /* Dados pessoais: exibição somente leitura (rótulo + valor). O usuário
       final não edita esses campos diretamente — apenas o administrador. */
    .info-row {
      display: flex;
      align-items: baseline;
      justify-content: space-between;
      gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid var(--border);
    }

    .info-row:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }

    .info-row:first-child {
      padding-top: 0;
    }

    .info-row-label {
      flex-shrink: 0;
      color: var(--text-muted);
      font-size: 13px;
    }

    .info-row-value {
      text-align: right;
      color: var(--text);
      font-size: 13.5px;
      font-weight: 500;
    }

    /* Formulário de alteração de senha — única escrita além da foto */
    label {
      display: block;
      margin-top: 16px;
      margin-bottom: 6px;
      color: var(--text-muted);
      font-size: 13px;
      font-weight: 500;
    }

    label:first-child {
      margin-top: 0;
    }

    input[type="password"] {
      width: 100%;
      padding: 11px 14px;
      color: var(--text);
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 10px;
      font-family: var(--font-body);
      font-size: 14px;
    }

    input[type="password"]:focus {
      border-color: var(--primary);
      outline: none;
    }

    .btn-primary {
      padding: 13px 24px;
      color: #fff;
      background: var(--primary);
      border: none;
      border-radius: 10px;
      font-family: var(--font-body);
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 8px 18px rgba(62, 94, 224, 0.28);
    }

    .btn-primary:hover {
      background: var(--primary-dark);
    }

    .form-actions {
      display: flex;
      justify-content: flex-end;
      margin-top: 22px;
    }

    /* -------------------------------------------------------------------- */
    /* 6. Sistema de alertas (Toasts)                                        */
    /* -------------------------------------------------------------------- */
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 500;
      display: flex;
      flex-direction: column;
      gap: 10px;
      width: 320px;
    }

    @media (max-width: 480px) {
      .toast-container {
        top: 12px;
        right: 12px;
        left: 12px;
        width: auto;
      }
    }

    .toast {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 14px 14px 14px 16px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-left: 4px solid var(--primary);
      border-radius: 10px;
      box-shadow: 0 14px 34px rgba(43, 43, 64, 0.16);
      animation: toastIn 0.25s ease;
    }

    .toast.removing {
      animation: toastOut 0.2s ease forwards;
    }

    .toast-icon { flex-shrink: 0; margin-top: 1px; font-size: 18px; }
    .toast-text { flex: 1; min-width: 0; }
    .toast-title { margin-bottom: 2px; font-size: 13.5px; font-weight: 600; }
    .toast-sub { color: var(--text-muted); font-size: 12px; }

    .toast-close {
      flex-shrink: 0;
      padding: 0;
      color: var(--text-muted);
      background: none;
      border: none;
      font-size: 15px;
      line-height: 1;
      cursor: pointer;
    }

    .toast.sucesso { border-left-color: var(--success); }
    .toast.sucesso .toast-icon { color: var(--success); }
    .toast.erro { border-left-color: var(--danger); }
    .toast.erro .toast-icon { color: var(--danger); }
    .toast.aviso { border-left-color: var(--warning); }
    .toast.aviso .toast-icon { color: var(--warning); }
    .toast.info { border-left-color: var(--primary); }
    .toast.info .toast-icon { color: var(--primary); }

    @keyframes toastIn {
      from { opacity: 0; transform: translateX(30px); }
      to   { opacity: 1; transform: translateX(0); }
    }

    @keyframes toastOut {
      from { opacity: 1; transform: translateX(0); }
      to   { opacity: 0; transform: translateX(30px); }
    }
  </style>
</head>
<body>

  <!-- Container dos alertas/toasts -->
  <div class="toast-container" id="toastContainer"></div>

  <!-- ======================= SIDEBAR ======================= -->
  <div class="sidebar" id="sidebar">
    <div class="logo">
      <span class="logo-badge">G</span>
      <span class="logo-text">GUNES<span class="logo-accent">ADS</span></span>
    </div>

    <div class="nav-section-label">Menu</div>
    <nav>
      <button class="nav-item active" type="button" data-screen="anuncios">
        <span class="nav-icon"><i class="bi bi-megaphone-fill"></i></span><span class="nav-label">Meus Anúncios</span>
      </button>
    </nav>
  </div>

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <!-- ======================= ÁREA PRINCIPAL ======================= -->
  <div class="main">

    <div class="topbar">
      <div class="topbar-left">
        <button class="menu-toggle" id="menuToggle" type="button" aria-label="Abrir ou recolher o menu"><i class="bi bi-list"></i></button>
        <h1 id="screen-title">Meus Anúncios</h1>
      </div>

      <div class="topbar-right">
        <!--
          ============================================================================
          DADOS DO USUÁRIO LOGADO (topbar)
          Mesma fonte de dados do nome exibido no hero da tela de Perfil
          (#profileHero) — evitar dessincronização, ambos vêm da sessão:

            $usuario['nome']  -> ex.: $_SESSION['usuario']['nome']
            iniciais do avatar -> calcular no PHP a partir de $usuario['nome']
                                   (mesma função iniciais() usada no painel
                                   admin), ou usar $usuario['fotoPerfil']
                                   quando o usuário tiver foto cadastrada

          Use htmlspecialchars() no nome antes de exibir.
          ============================================================================
        -->
        <div class="user-info" id="userInfo">
          <div class="user-hello">Olá, <b><?= $firstName ?></b></div>
          <div class="avatar" id="topbarAvatar"><?= $iniciais ?></div>

          <div class="user-menu" id="userMenu">
            <div class="user-menu-item" onclick="irParaTela('perfil')"><i class="bi bi-person"></i> Perfil</div>
            <div class="user-menu-divider"></div>
            <a href="server/logout.php" class="user-menu-item danger" style="text-decoration: none;"><i class="bi bi-box-arrow-right"></i> Sair</a>
          </div>
        </div>
      </div>
    </div>

    <div class="content">

      <!-- ================================================================ -->
      <!-- TELA: MEUS ANÚNCIOS (somente leitura)                             -->
      <!-- ================================================================ -->
      <div class="screen active" id="screen-anuncios">
        <p class="section-intro">Acompanhe suas campanhas cadastradas pelo GunesAds: status, plano contratado e exibições registradas nos tablets.</p>

        <!--
          ============================================================================
          MEUS ANÚNCIOS
          Um card por registro da tabela "anuncios" pertencente ao usuário logado
          (WHERE usuario_id = usuário_logado), ORDER BY dataInicio DESC. Campos
          (viriam de $anuncio['campo'] no PHP):

            titulo              -> nome do anúncio
            imagem              -> URL da arte enviada (se vazio, mostra fundo +
                                    ícone de fallback)
            cor                 -> gradiente CSS de fallback (mesma paleta usada
                                    no restante do sistema, usado quando 'imagem'
                                    for vazio)
            plano.tempoExibicao -> 10, 20 ou 30 (segundos)
            plano.duracaoDias   -> 30, 60 ou 90 — usado só para derivar o nível
                                    do plano (30=Básico, 60=Profissional,
                                    90=Premium), a mesma nomenclatura já usada
                                    na tela "Planos" do Painel Admin
            dataInicio          -> formatada (ex.: 01/07/2026)
            dataFim             -> formatada (ex.: 30/07/2026)
            status              -> 'Ativo', 'Pausado' ou 'Encerrado'
            exibicoesTotais     -> COUNT total de exibições (formatado, ex:
                                    "18.2K") — exibido na mesma linha do
                                    plano e das datas, junto de um ícone de
                                    olho, sem rótulo de texto
            diasRestantes       -> DATEDIFF(dataFim, CURRENT_DATE), nunca
                                    negativo (0 quando encerrado)
            progressoPercentual -> round((duracaoDias - diasRestantes) /
                                    duracaoDias * 100) — usado direto no
                                    "width" da barra de progresso

          Este painel é somente de consulta: não há botões de ação (editar,
          pausar, excluir) em nenhum card — apenas o administrador altera
          anúncios. Use htmlspecialchars() no título.

          Se o usuário não tiver nenhum anúncio cadastrado, renderizar SOMENTE
          o bloco .empty-list-state (mais abaixo) e omitir o ads-grid.
          ============================================================================
        -->
        <div class="ads-grid" id="adsGrid">

          <!-- INÍCIO DO LOOP: repetir este bloco para cada $anuncio em $anuncios -->

          <!-- exemplo com status "Ativo" -->
          <div class="ad-card">
            <div class="ad-card-thumb" style="background:linear-gradient(135deg,#3E5EE0,#26399C);">
              <i class="bi bi-image thumb-fallback"></i>
            </div>
            <div class="ad-card-body">
              <div class="ad-card-head">
                <div class="ad-card-title">Promoção de Verão</div>
                <span class="badge ativo">Ativo</span>
              </div>
              <div class="ad-card-meta">
                <span class="plan-pill"><i class="bi bi-award"></i> Básico · 10s</span>
                <span><i class="bi bi-calendar3"></i> 01/07 – 31/07</span>
                <span><i class="bi bi-eye"></i> 3.480</span>
              </div>
              <div class="ad-card-metrics">
                <div class="ad-card-progress-head">
                  <span>Campanha</span>
                  <span><b>2</b> dias restantes</span>
                </div>
                <div class="progress-track"><div class="progress-fill" style="width:93%;"></div></div>
              </div>
            </div>
          </div>

          <!-- exemplo com status "Ativo" -->
          <div class="ad-card">
            <div class="ad-card-thumb" style="background:linear-gradient(135deg,#17C666,#0E8548);">
              <i class="bi bi-image thumb-fallback"></i>
            </div>
            <div class="ad-card-body">
              <div class="ad-card-head">
                <div class="ad-card-title">Lançamento Combo</div>
                <span class="badge ativo">Ativo</span>
              </div>
              <div class="ad-card-meta">
                <span class="plan-pill"><i class="bi bi-award-fill"></i> Profissional · 20s</span>
                <span><i class="bi bi-calendar3"></i> 20/06 – 19/08</span>
                <span><i class="bi bi-eye"></i> 8.120</span>
              </div>
              <div class="ad-card-metrics">
                <div class="ad-card-progress-head">
                  <span>Campanha</span>
                  <span><b>23</b> dias restantes</span>
                </div>
                <div class="progress-track"><div class="progress-fill" style="width:62%;"></div></div>
              </div>
            </div>
          </div>

          <!-- exemplo com status "Pausado" -->
          <div class="ad-card">
            <div class="ad-card-thumb" style="background:linear-gradient(135deg,#F3A638,#B9721B);">
              <i class="bi bi-image thumb-fallback"></i>
            </div>
            <div class="ad-card-body">
              <div class="ad-card-head">
                <div class="ad-card-title">Cupom Primeira Compra</div>
                <span class="badge pausado">Pausado</span>
              </div>
              <div class="ad-card-meta">
                <span class="plan-pill"><i class="bi bi-award"></i> Básico · 10s</span>
                <span><i class="bi bi-calendar3"></i> 10/06 – 09/07</span>
                <span><i class="bi bi-eye"></i> 2.140</span>
              </div>
              <div class="ad-card-metrics">
                <div class="ad-card-progress-head">
                  <span>Campanha</span>
                  <span><b>10</b> dias restantes</span>
                </div>
                <div class="progress-track"><div class="progress-fill" style="width:67%;"></div></div>
              </div>
            </div>
          </div>

          <!-- exemplo com status "Encerrado" -->
          <div class="ad-card">
            <div class="ad-card-thumb" style="background:linear-gradient(135deg,#8A8CA5,#565875);">
              <i class="bi bi-image thumb-fallback"></i>
            </div>
            <div class="ad-card-body">
              <div class="ad-card-head">
                <div class="ad-card-title">Feira de Setembro</div>
                <span class="badge encerrado">Encerrado</span>
              </div>
              <div class="ad-card-meta">
                <span class="plan-pill"><i class="bi bi-gem"></i> Premium · 30s</span>
                <span><i class="bi bi-calendar3"></i> 15/05 – 14/06</span>
                <span><i class="bi bi-eye"></i> 31.700</span>
              </div>
              <div class="ad-card-metrics">
                <div class="ad-card-progress-head">
                  <span>Campanha</span>
                  <span><b>0</b> dias restantes</span>
                </div>
                <div class="progress-track"><div class="progress-fill" style="width:100%;"></div></div>
              </div>
            </div>
          </div>

          <!-- FIM DO LOOP -->

        </div>

        <!--
          Estado vazio: no PHP, isto entra num "else", tipo:
          <?php if (empty($anuncios)): ?> ... este bloco ... <?php endif; ?>
          Por enquanto fica escondido porque já temos cards de exemplo acima.
        -->
        <div class="empty-list-state" id="emptyAds">
          <i class="bi bi-megaphone"></i>
          <h2>Nenhum anúncio cadastrado</h2>
          <p>Assim que o administrador cadastrar uma campanha para você, ela aparecerá aqui.</p>
        </div>
      </div>

      <!-- ================================================================ -->
      <!-- TELA: PERFIL                                                      -->
      <!-- ================================================================ -->
      <div class="screen" id="screen-perfil">

        <!--
          ============================================================================
          HERO DO PERFIL
          Bloco só de leitura, preenchido a partir da sessão do usuário logado.
          Mesma fonte de dados do nome exibido na topbar (#userInfo) — evitar
          dessincronização entre os dois pontos. Campos, vindos de $usuario:

            $usuario['fotoPerfil']   -> se preenchido, renderizar uma <img> (ou
                                         background-image) no lugar do <div> de
                                         iniciais — ver os DOIS ramos comentados
                                         abaixo (com foto / sem foto)
            $usuario['nomeCompleto'] -> nome completo, também usado para calcular
                                         as iniciais (função iniciais(), mesma
                                         lógica usada no painel admin) quando não
                                         há foto
            $usuario['dataCadastro'] -> formatada como "mês/ano"
                                         (ex.: date('M/Y', strtotime($usuario['dataCadastro'])))

          Use htmlspecialchars() no nome antes de exibir.
          ============================================================================
        -->
        <div class="profile-hero" id="profileHero">
          <div class="profile-avatar-wrap">
            <!-- CASO SEM FOTO (fotoPerfil vazio) — usar as iniciais do nome:
            <div class="profile-avatar-lg" id="profileAvatarPreview">AL</div>
            -->
            <!-- CASO COM FOTO (fotoPerfil preenchido) — trocar o <div> acima por:
            <div class="profile-avatar-lg" id="profileAvatarPreview" style="background-image:url('<?= htmlspecialchars($usuario['fotoPerfil']) ?>');"></div>
            -->
            <div class="profile-avatar-lg" id="profileAvatarPreview"><?= $iniciais ?></div>
            <div class="profile-avatar-edit" id="avatarEditBtn" title="Alterar foto">
              <i class="bi bi-camera-fill"></i>
            </div>
            <!-- O campo "avatar_image" (dentro do #avatarForm) chega em $_FILES. -->
            <input
              type="file"
              id="avatarFile"
              name="avatar_image"
              accept=".jpg,.jpeg,.png,image/jpeg,image/png"
              style="display:none"
            >
          </div>
          <div class="profile-hero-info">
            <div class="profile-hero-name"><?= $fullName ?></div>
            <div class="profile-hero-badges">
              <span class="info-badge"><i class="bi bi-calendar3"></i> Membro desde <?= formatData($criadoEm) ?></span>
            </div>
          </div>
        </div>

        <!--
          ============================================================================
          DADOS PESSOAIS — SOMENTE LEITURA
          O usuário final não edita nome, e-mail, telefone ou status: esses
          campos são cadastrados/alterados exclusivamente pelo administrador
          (Painel Admin > Usuários). Aqui apenas exibimos o que já está salvo,
          vindo da mesma sessão usada no hero acima ($usuario).

          Use htmlspecialchars() em nome, email e telefone antes de exibir.
          ============================================================================
        -->
        <div class="profile-card" style="margin-bottom:20px;">
          <div class="profile-card-title"><i class="bi bi-person-fill"></i> Dados pessoais</div>
          <p class="profile-card-desc">Essas informações são cadastradas pelo administrador do sistema.</p>

          <div class="info-row">
            <span class="info-row-label">Nome completo</span>
            <span class="info-row-value"><?= $fullName ?></span>
          </div>
          <div class="info-row">
            <span class="info-row-label">E-mail</span>
            <span class="info-row-value"><?= $email ?></span>
          </div>
          <div class="info-row">
            <span class="info-row-label">Telefone</span>
            <span class="info-row-value"><?= formatTel($telefone) ?></span>
          </div>
          <div class="info-row">
            <span class="info-row-label">Status da conta</span>
            <span class="info-row-value"><span class="status-badge ativo"><i class="bi bi-check-circle-fill"></i> <?= ucfirst($status) ?></span></span>
          </div>
          <div class="info-row">
            <span class="info-row-label">Cliente desde</span>
            <span class="info-row-value"><?= formatData($criadoEm) ?></span>
          </div>
        </div>

        <!-- Segurança / alterar senha -->
        <div class="profile-card">
          <div class="profile-card-title"><i class="bi bi-shield-lock-fill"></i> Segurança</div>
          <p class="profile-card-desc">Altere sua senha de acesso periodicamente.</p>

          <!--
            Formulário pronto para integração com PHP:
              - envia apenas os 3 campos de senha
              - o preventDefault() no JS evita reload aqui no protótipo;
                remova-o quando o formulário passar a enviar de verdade
                para o backend (ex.: action="alterar_senha.php")
          -->
          <form
            id="changePasswordForm"
            name="changePasswordForm"
            method="post"
            action=""
            autocomplete="off"
          >
            <label for="currentPassword">Senha atual</label>
            <input
              type="password"
              id="currentPassword"
              name="current_password"
              autocomplete="current-password"
              placeholder="Digite sua senha atual"
            >

            <label for="newPassword">Nova senha</label>
            <input
              type="password"
              id="newPassword"
              name="new_password"
              autocomplete="new-password"
              placeholder="Mínimo de 8 caracteres"
            >

            <label for="confirmPassword">Confirmar nova senha</label>
            <input
              type="password"
              id="confirmPassword"
              name="confirm_password"
              autocomplete="new-password"
              placeholder="Repita a nova senha"
            >

            <div class="form-actions">
              <button class="btn-primary" type="submit">Atualizar senha</button>
            </div>
          </form>
        </div>

      </div>

    </div>
  </div>

  <script>
    /* ========================================================================
       GUNESADS — PAINEL DO USUÁRIO — LÓGICA (V1)
       Sumário:
         1. Navegação entre telas
         2. Sidebar responsiva
         3. Menu dropdown do usuário
         4. Upload de foto de perfil (prévia local)
         5. Alteração de senha
         6. Sistema global de alertas (Toasts)

       Este painel é somente de consulta: nenhuma função abaixo simula
       persistência de dados de anúncio (sem CRUD, sem status alternável).
       As únicas ações reais que o usuário final executa são atualizar a
       foto de perfil e trocar a senha — ambas via <form> pronto para
       apontar para o backend PHP (ver comentários específicos abaixo).
       ==================================================================== */

    /* ---- 1. Navegação entre telas -------------------------------------- */
    const screenTitles = {
      anuncios: 'Meus Anúncios',
      perfil: 'Meu Perfil',
    };

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

    document.querySelectorAll('.nav-item').forEach((button) => {
      button.addEventListener('click', () => irParaTela(button.dataset.screen));
    });

    /* ---- 2. Sidebar responsiva ------------------------------------------- */
    const sidebar = document.getElementById('sidebar');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const menuToggle = document.getElementById('menuToggle');

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

    /* ---- 3. Menu dropdown do usuário --------------------------------------- */
    const userInfo = document.getElementById('userInfo');
    const userMenu = document.getElementById('userMenu');

    userInfo.addEventListener('click', (e) => {
      e.stopPropagation();
      userMenu.classList.toggle('show');
    });

    document.addEventListener('click', () => {
      userMenu.classList.remove('show');
    });

    /* ---- 4. Upload de foto de perfil --------------------------------------
       Prévia local da nova foto (sem backend ainda). No PHP, este mesmo
       input ("avatar_image") chega em $_FILES quando o formulário for
       enviado de verdade. O mesmo preview é replicado no avatar do topbar
       só por comportamento de UI (não gera dado novo, só reflete a mesma
       foto escolhida nos dois lugares que exibem o avatar do usuário). --- */
    const avatarEditBtn = document.getElementById('avatarEditBtn');
    const avatarFile = document.getElementById('avatarFile');
    const profileAvatarPreview = document.getElementById('profileAvatarPreview');
    const topbarAvatar = document.getElementById('topbarAvatar');

    avatarEditBtn.addEventListener('click', () => avatarFile.click());

    avatarFile.addEventListener('change', () => {
      const file = avatarFile.files[0];
      if (!file) return;

      const allowedTypes = ['image/jpeg', 'image/png'];
      if (!allowedTypes.includes(file.type)) {
        mostrarToast('Formato inválido', 'Envie apenas imagens JPEG, JPG ou PNG', 'erro');
        avatarFile.value = '';
        return;
      }

      const url = URL.createObjectURL(file);

      [profileAvatarPreview, topbarAvatar].forEach((el) => {
        el.style.backgroundImage = `url('${url}')`;
        el.textContent = '';
      });

      // No PHP real: o envio do arquivo acontece via <form method="post"
      // enctype="multipart/form-data" action="atualizar_foto.php">. Aqui no
      // protótipo, apenas simulamos a confirmação visual com um toast.
      mostrarToast('Foto atualizada', 'Sua nova foto de perfil foi salva', 'sucesso');
    });

    /* ---- 5. Alteração de senha ---------------------------------------------
       Esta checagem só confere se "Nova senha" e "Confirmar nova senha" são
       iguais — é conveniência de UX, não segurança. Toda validação real
       (senha atual bater com o hash salvo, força mínima da nova senha, etc.)
       tem que ser feita no PHP ao processar o POST; o front-end nunca deve
       ser a única barreira aqui. -------------------------------------------- */
    document.getElementById('changePasswordForm').addEventListener('submit', (e) => {
      e.preventDefault();

      const novaSenha = document.getElementById('newPassword').value;
      const confirmarSenha = document.getElementById('confirmPassword').value;

      if (!novaSenha || !confirmarSenha) {
        mostrarToast('Preencha a nova senha', 'Digite e confirme a nova senha para continuar', 'aviso');
        return;
      }

      if (novaSenha !== confirmarSenha) {
        mostrarToast('As senhas não coincidem', 'Verifique a nova senha e a confirmação', 'erro');
        return;
      }

      mostrarToast('Senha atualizada', 'Use a nova senha no seu próximo acesso', 'sucesso');
      e.target.reset();
    });

    /* ========================================================================
       6. Sistema global de alertas (Toasts)
       Uso: mostrarToast('Título', 'Subtítulo opcional', 'sucesso' | 'erro' | 'aviso' | 'info')
       Sem dependência de backend: é só uma função de UI, que o PHP dispara
       via <script> inserido após uma operação real (ver Documento Mestre).
       ==================================================================== */
    const TOAST_ICONS = {
      sucesso: 'bi-check-circle-fill',
      erro: 'bi-x-circle-fill',
      aviso: 'bi-exclamation-triangle-fill',
      info: 'bi-info-circle-fill',
    };
    const TOAST_DURATION_MS = 4000;

    function mostrarToast(mensagem, subtitulo, tipo) {
      const tipoFinal = TOAST_ICONS[tipo] ? tipo : 'info';
      const container = document.getElementById('toastContainer');

      const toast = document.createElement('div');
      toast.className = `toast ${tipoFinal}`;
      toast.innerHTML = `
        <span class="toast-icon"><i class="bi ${TOAST_ICONS[tipoFinal]}"></i></span>
        <div class="toast-text">
          <div class="toast-title"></div>
          ${subtitulo ? '<div class="toast-sub"></div>' : ''}
        </div>
        <button class="toast-close" type="button" aria-label="Fechar alerta">
          <i class="bi bi-x"></i>
        </button>
      `;
      // Usa textContent (não innerHTML) para o texto vindo de fora, evitando
      // problemas caso a mensagem contenha caracteres especiais.
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
