<?php 
  include "server/auth.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rota Ads — Protótipo</title>

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
       ROTA ADS — PROTÓTIPO (Dashboard do anunciante)
       --------------------------------------------------------------------------
       Sumário deste arquivo:
         1. Reset básico + variáveis globais (cores, fontes)
         2. Layout base (sidebar + área principal)
         3. Sidebar responsiva (completa / só ícones / off-canvas no mobile)
         4. Barra superior (topbar) + menu do usuário
         5. Tela: Dashboard
         6. Tela: Criar Anúncio (formulário)
         7. Tela: Meus Anúncios (tabela + ações)
         8. Tela: Preview no Tablet (elemento de destaque, mockup animado)
         9. Tela: Planos
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

      --info: #3EC6E0;
      --info-light: #E9F9FC;

      --font-display: 'Poppins', sans-serif;
      --font-body: 'Inter', sans-serif;

      --shadow: 0 4px 18px rgba(43, 43, 64, 0.06);

      /* Larguras da sidebar nos três estados possíveis */
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
    /* 2. Layout base                                                        */
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

    /* -------------------------------------------------------------------- */
    /* 3. Sidebar responsiva                                                 */
    /*    Estados controlados via JS (ver bloco "Sidebar responsiva" no      */
    /*    <script> no final do arquivo):                                     */
    /*      - padrão (>=1280px): completa, ícone + texto                     */
    /*      - .collapsed (900–1279px por padrão, ou manual): só ícone        */
    /*      - .mobile-open (<900px): abre como painel sobre o conteúdo       */
    /* -------------------------------------------------------------------- */
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

    /* Off-canvas no mobile: a sidebar sai da flexbox e vira um painel fixo */
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
        /* No mobile, "collapsed" não reduz largura — o menu ou está          */
        /* totalmente aberto (mobile-open) ou totalmente escondido.          */
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
    /* 4. Topbar + menu do usuário                                           */
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
      .topbar {
        padding: 0 16px;
      }

      .topbar h1 {
        font-size: 17px;
      }
    }

    @media (max-width: 420px) {
      .topbar h1 {
        font-size: 15px;
      }
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

    .user-text {
      line-height: 1.3;
      text-align: right;
    }

    .user-hello {
      color: var(--text-muted);
      font-size: 13px;
    }

    .user-hello b {
      color: var(--text);
      font-weight: 700;
    }

    .user-plan {
      color: var(--primary);
      font-size: 11.5px;
      font-weight: 500;
    }

    /* Em telas pequenas, oculta o texto (saudação + plano) e mantém          */
    /* apenas o avatar — que continua clicável, abrindo o mesmo dropdown     */
    /* com Perfil / Assinatura / Sair.                                       */
    @media (max-width: 560px) {
      .user-text {
        display: none;
      }

      .user-info {
        gap: 0;
        padding: 4px;
      }
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

    .user-menu.show {
      display: block;
    }

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

    .user-menu-item:hover {
      background: var(--surface-2);
    }

    .user-menu-item.danger {
      color: var(--danger);
    }

    .user-menu-divider {
      height: 1px;
      margin: 6px 4px;
      background: var(--border);
    }

    /* -------------------------------------------------------------------- */
    /* 5. Dashboard                                                          */
    /* -------------------------------------------------------------------- */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 18px;
      margin-bottom: 24px;
    }

    .stat-card {
      display: flex;
      flex-direction: column;
      gap: 12px;
      padding: 20px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      box-shadow: var(--shadow);
    }

    .stat-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .stat-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 42px;
      height: 42px;
      border-radius: 11px;
      font-size: 18px;
    }

    .stat-icon.amber { background: var(--warning-light); color: var(--warning); }
    .stat-icon.green { background: var(--success-light); color: var(--success); }
    .stat-icon.blue  { background: var(--primary-light);  color: var(--primary); }

    .stat-label {
      color: var(--text-muted);
      font-size: 13px;
    }

    .stat-value {
      font-family: var(--font-display);
      font-size: 26px;
      font-weight: 700;
    }

    .panel {
      padding: 24px;
      margin-bottom: 20px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      box-shadow: var(--shadow);
    }

    .panel h3 {
      margin-bottom: 16px;
      font-family: var(--font-display);
      font-size: 15px;
      font-weight: 600;
    }

    /* -------------------------------------------------------------------- */
    /* 5b. Dashboard — hero, cards em destaque, mini preview, dica           */
    /* -------------------------------------------------------------------- */
    .dash-hero {
      position: relative;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 18px;
      overflow: hidden;
      padding: 26px 28px;
      margin-bottom: 22px;
      background: linear-gradient(135deg, var(--primary) 0%, #6B8CFF 100%);
      border-radius: 16px;
      color: #fff;
    }

    .dash-hero::before {
      content: '';
      position: absolute;
      top: -80px;
      right: -60px;
      width: 240px;
      height: 240px;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.16), transparent 70%);
      border-radius: 50%;
    }

    .dash-hero-text {
      position: relative;
      z-index: 1;
    }

    .dash-hero-text h2 {
      margin-bottom: 4px;
      font-family: var(--font-display);
      font-size: 22px;
      font-weight: 700;
    }

    .dash-hero-text p {
      color: rgba(255, 255, 255, 0.85);
      font-size: 13px;
      text-transform: capitalize;
    }

    .dash-hero-actions {
      position: relative;
      z-index: 1;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .btn-hero {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 10px 16px;
      color: #fff;
      background: rgba(255, 255, 255, 0.14);
      border: 1px solid rgba(255, 255, 255, 0.35);
      border-radius: 10px;
      font-family: var(--font-body);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.15s;
      white-space: nowrap;
    }

    .btn-hero:hover {
      background: rgba(255, 255, 255, 0.24);
    }

    .btn-hero.solid {
      color: var(--primary-dark);
      background: #fff;
      border-color: #fff;
    }

    .btn-hero.solid:hover {
      background: #EEF1FF;
    }

    .stats-featured {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
      margin-bottom: 18px;
    }

    @media (max-width: 700px) {
      .stats-featured {
        grid-template-columns: 1fr;
      }
    }

    .stat-card-featured {
      display: flex;
      flex-direction: column;
      gap: 8px;
      padding: 22px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 16px;
      box-shadow: var(--shadow);
    }

    .stat-card-featured .stat-icon {
      width: 46px;
      height: 46px;
      border-radius: 12px;
      font-size: 20px;
    }

    .stat-card-featured .stat-value {
      font-family: var(--font-display);
      font-size: 30px;
      font-weight: 700;
    }

    .stat-card-featured .stat-sub {
      display: flex;
      align-items: center;
      gap: 5px;
      color: var(--text-muted);
      font-size: 12px;
    }

    .progress-track {
      width: 100%;
      height: 8px;
      margin-top: 4px;
      background: var(--surface-2);
      border-radius: 20px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--primary), #6B8CFF);
      border-radius: 20px;
    }

    .dash-secondary-grid {
      display: grid;
      grid-template-columns: 1.6fr 1fr;
      gap: 20px;
      margin-top: 20px;
    }

    @media (max-width: 900px) {
      .dash-secondary-grid {
        grid-template-columns: 1fr;
      }
    }

    .dash-live-card {
      display: flex;
      align-items: center;
      gap: 22px;
      padding: 24px;
      background: linear-gradient(160deg, #1C1F2B, #0C0E15);
      border-radius: 16px;
      color: #fff;
    }

    @media (max-width: 560px) {
      .dash-live-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }
    }

    .tablet-mini {
      flex-shrink: 0;
      width: 230px;
      padding: 8px;
      background: #111318;
      border: 5px solid #23262E;
      border-radius: 18px;
    }

    @media (max-width: 560px) {
      .tablet-mini {
        width: 100%;
        max-width: 280px;
      }
    }

    .tablet-screen-mini {
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      aspect-ratio: 16 / 10;
      padding: 16px;
      overflow: hidden;
      background: linear-gradient(135deg, var(--primary), #26399C);
      border-radius: 11px;
    }

    .live-tag-mini {
      position: absolute;
      top: 12px;
      left: 14px;
      display: flex;
      align-items: center;
      gap: 5px;
      color: #FF6B6B;
      font-size: 10.5px;
      font-weight: 700;
      letter-spacing: 0.4px;
    }

    .ad-brand-mini {
      color: #fff;
      font-family: var(--font-display);
      font-size: 18px;
      font-weight: 700;
      line-height: 1.25;
    }

    .ad-tag-mini {
      color: rgba(255, 255, 255, 0.85);
      font-size: 12.5px;
      margin-top: 2px;
    }

    .dash-live-info {
      flex: 1;
      min-width: 0;
    }

    .dash-live-label {
      color: #7C9BFF;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .dash-live-info h4 {
      margin: 6px 0 4px;
      font-family: var(--font-display);
      font-size: 16px;
      font-weight: 600;
    }

    .dash-live-info p {
      margin-bottom: 14px;
      color: #B7BBCB;
      font-size: 12.5px;
      line-height: 1.5;
    }

    .btn-outline-light {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 9px 14px;
      color: #fff;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 9px;
      font-family: var(--font-body);
      font-size: 12.5px;
      font-weight: 600;
      cursor: pointer;
    }

    .btn-outline-light:hover {
      background: rgba(255, 255, 255, 0.16);
    }

    .dash-tip-card {
      display: flex;
      gap: 14px;
      padding: 22px;
      background: var(--info-light);
      border: 1px solid #CDEFF6;
      border-radius: 16px;
    }

    .dash-tip-icon {
      display: flex;
      flex-shrink: 0;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background: var(--info);
      color: #fff;
      border-radius: 10px;
      font-size: 17px;
    }

    .dash-tip-card h4 {
      margin-bottom: 4px;
      font-family: var(--font-display);
      font-size: 14px;
      font-weight: 600;
    }

    .dash-tip-card p {
      color: var(--text-muted);
      font-size: 12.5px;
      line-height: 1.55;
    }

    /* -------------------------------------------------------------------- */
    /* 6. Criar Anúncio (formulário)                                         */
    /* -------------------------------------------------------------------- */
    .form-grid {
      display: grid;
      grid-template-columns: 1.1fr 0.9fr;
      gap: 24px;
    }

    @media (max-width: 900px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
    }

    .upload-zone {
      padding: 36px 20px;
      margin-bottom: 18px;
      text-align: center;
      color: var(--text-muted);
      background: var(--surface-2);
      border: 2px dashed var(--border);
      border-radius: 14px;
      cursor: pointer;
      transition: border-color 0.15s, color 0.15s;
    }

    .upload-zone:hover {
      color: var(--primary);
      border-color: var(--primary);
    }

    .upload-zone .upicon {
      display: block;
      margin-bottom: 8px;
      font-size: 24px;
    }

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

    input[type="text"],
    input[type="email"],
    input[type="password"],
    textarea,
    select {
      width: 100%;
      padding: 11px 14px;
      color: var(--text);
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 10px;
      font-family: var(--font-body);
      font-size: 14px;
    }

    input:focus,
    textarea:focus {
      border-color: var(--primary);
      outline: none;
    }

    textarea {
      min-height: 80px;
      resize: vertical;
    }

    .duration-options {
      display: flex;
      gap: 8px;
      margin-top: 6px;
    }

    .dur-chip {
      padding: 8px 14px;
      color: var(--text-muted);
      background: none;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-family: var(--font-body);
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
    }

    .dur-chip.selected {
      color: var(--primary);
      background: var(--primary-light);
      border-color: var(--primary);
    }

    .btn-primary {
      width: 100%;
      margin-top: 22px;
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

    .preview-card {
      overflow: hidden;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      box-shadow: var(--shadow);
    }

    .preview-card .ph {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 140px;
      color: var(--primary);
      background: linear-gradient(135deg, var(--primary-light), #DFE6FF);
      background-size: cover;
      background-position: center;
      font-size: 12px;
      font-weight: 500;
    }

    .preview-card .pc-body {
      padding: 14px;
    }

    .preview-card .pc-title {
      margin-bottom: 4px;
      font-weight: 600;
      font-size: 14px;
    }

    .preview-card .pc-desc {
      color: var(--text-muted);
      font-size: 12px;
    }

    /* -------------------------------------------------------------------- */
    /* 7. Meus Anúncios (cards + ações)                                      */
    /* -------------------------------------------------------------------- */
    .ads-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
    }

    @media (max-width: 1300px) {
      .ads-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 640px) {
      .ads-grid {
        grid-template-columns: 1fr;
      }
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
    }

    .ad-card-thumb .thumb-fallback {
      opacity: 0.55;
    }

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
    .badge.expirado  { color: var(--danger);  background: var(--danger-light); }

    .ad-card-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 6px 14px;
      margin-bottom: 16px;
      color: var(--text-muted);
      font-size: 12.5px;
    }

    .ad-card-meta span {
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .pay-ok   { color: var(--success); font-weight: 500; }
    .pay-pend { color: var(--warning); font-weight: 500; }

    .ad-card-actions {
      display: flex;
      gap: 6px;
      margin-top: auto;
      padding-top: 14px;
      border-top: 1px solid var(--border);
    }

    .act-btn {
      display: inline-flex;
      flex: 1;
      align-items: center;
      justify-content: center;
      height: 34px;
      background: var(--surface-2);
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 14px;
      color: var(--text-muted);
      cursor: pointer;
    }

    .act-btn:hover {
      color: var(--primary);
      background: var(--primary-light);
      border-color: var(--primary);
    }

    .act-btn.danger:hover {
      color: var(--danger);
      background: var(--danger-light);
      border-color: var(--danger);
    }

    /* -------------------------------------------------------------------- */
    /* 8. Preview no Tablet (elemento de destaque)                           */
    /* -------------------------------------------------------------------- */
    .dash-scene {
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      overflow: hidden;
      padding: 60px 40px 40px;
      background: linear-gradient(180deg, #1C1F2B, #0C0E15 75%);
      border-radius: 18px;
    }

    @media (max-width: 480px) {
      .dash-scene {
        padding: 52px 18px 28px;
      }
    }

    .live-tag {
      position: absolute;
      top: 20px;
      left: 24px;
      display: flex;
      align-items: center;
      gap: 6px;
      color: #FF6B6B;
      font-family: var(--font-body);
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .live-dot {
      width: 7px;
      height: 7px;
      background: #FF6B6B;
      border-radius: 50%;
      animation: pulse 1.4s infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50%      { opacity: 0.25; }
    }

    .tablet {
      width: 340px;
      max-width: 100%;
      padding: 10px;
      background: #111318;
      border: 6px solid #23262E;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }

    .tablet-screen {
      position: relative;
      aspect-ratio: 16 / 10;
      overflow: hidden;
      background: #0B0C0F;
      border-radius: 10px;
    }

    .ad-slide {
      position: absolute;
      inset: 0;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 18px;
      opacity: 0;
      transition: opacity 0.6s ease;
    }

    .ad-slide.show {
      opacity: 1;
    }

    .ad-content {
      position: relative;
      z-index: 1;
    }

    .ad-brand {
      margin-bottom: 2px;
      color: #fff;
      font-family: var(--font-display);
      font-size: 18px;
      font-weight: 700;
    }

    .ad-tag {
      color: rgba(255, 255, 255, 0.8);
      font-size: 11px;
    }

    .dash-base {
      width: 400px;
      max-width: calc(100% - 20px);
      height: 26px;
      margin-top: -4px;
      background: linear-gradient(to bottom, #22252E, #0C0D10);
      border-radius: 0 0 20px 20px;
    }

    .dash-caption {
      max-width: 380px;
      width: 100%;
      margin-top: 24px;
      color: #B7BBCB;
      font-size: 13px;
      text-align: center;
    }

    .dash-caption b {
      color: #fff;
    }

    .dots {
      display: flex;
      justify-content: center;
      gap: 6px;
      margin-top: 14px;
    }

    .dot {
      width: 6px;
      height: 6px;
      background: #3A3E4D;
      border-radius: 50%;
    }

    .dot.active {
      background: var(--primary);
    }

    /* -------------------------------------------------------------------- */
    /* 9. Planos                                                             */
    /* -------------------------------------------------------------------- */
    .plans-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    @media (max-width: 900px) {
      .plans-grid {
        grid-template-columns: 1fr;
      }
    }

    .plan-card {
      display: flex;
      flex-direction: column;
      padding: 26px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 16px;
      box-shadow: var(--shadow);
    }

    .plan-card.featured {
      position: relative;
      border-color: var(--primary);
    }

    .plan-tag {
      position: absolute;
      top: -11px;
      left: 24px;
      padding: 4px 12px;
      color: #fff;
      background: var(--primary);
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
    }

    .plan-name {
      margin-bottom: 6px;
      font-family: var(--font-display);
      font-size: 16px;
      font-weight: 600;
    }

    .plan-price {
      margin-bottom: 2px;
      color: var(--text);
      font-family: var(--font-display);
      font-size: 30px;
      font-weight: 700;
    }

    .plan-price span {
      color: var(--text-muted);
      font-size: 13px;
      font-weight: 400;
    }

    .plan-desc {
      margin-bottom: 18px;
      color: var(--text-muted);
      font-size: 12px;
    }

    .plan-feats {
      display: flex;
      flex: 1;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 22px;
      list-style: none;
      font-size: 13px;
    }

    .plan-feats li {
      display: flex;
      align-items: flex-start;
      gap: 8px;
    }

    .plan-feats li::before {
      content: '✓';
      color: var(--success);
      font-weight: 700;
    }

    .btn-outline {
      padding: 11px;
      color: var(--text);
      background: var(--surface-2);
      border: 1px solid var(--border);
      border-radius: 10px;
      font-family: var(--font-body);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
    }

    .btn-outline:hover {
      color: var(--primary);
      border-color: var(--primary);
    }

    /* -------------------------------------------------------------------- */
    /* 9.5. Perfil do usuário                                                */
    /* -------------------------------------------------------------------- */
    .profile-back {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 18px;
      color: var(--text-muted);
      background: none;
      border: none;
      font-family: var(--font-body);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
    }

    .profile-back:hover {
      color: var(--primary);
    }

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

    .info-badge.plan {
      color: var(--primary);
      background: var(--primary-light);
    }

    .profile-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 20px;
    }

    @media (max-width: 900px) {
      .profile-grid {
        grid-template-columns: 1fr;
      }
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
      margin-bottom: 4px;
      color: var(--text-muted);
      font-size: 12.5px;
    }

    .profile-plan-card {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }

    .profile-plan-info .plan-current-name {
      font-family: var(--font-display);
      font-size: 17px;
      font-weight: 600;
    }

    .profile-plan-info .plan-current-price {
      color: var(--primary);
      font-size: 13px;
      font-weight: 600;
    }

    .profile-plan-info .plan-current-desc {
      margin-top: 4px;
      color: var(--text-muted);
      font-size: 12.5px;
    }

    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 22px;
    }

    .form-actions .btn-primary,
    .form-actions .btn-outline {
      width: auto;
      margin-top: 0;
      padding: 11px 22px;
    }

    /* -------------------------------------------------------------------- */
    /* 10. Sistema de alertas (Toasts)                                       */
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

    .toast-icon {
      flex-shrink: 0;
      margin-top: 1px;
      font-size: 18px;
    }

    .toast-text {
      flex: 1;
      min-width: 0;
    }

    .toast-title {
      margin-bottom: 2px;
      font-size: 13.5px;
      font-weight: 600;
    }

    .toast-sub {
      color: var(--text-muted);
      font-size: 12px;
    }

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
    .toast.info { border-left-color: var(--info); }
    .toast.info .toast-icon { color: var(--info); }

    @keyframes toastIn {
      from { opacity: 0; transform: translateX(30px); }
      to   { opacity: 1; transform: translateX(0); }
    }

    @keyframes toastOut {
      from { opacity: 1; transform: translateX(0); }
      to   { opacity: 0; transform: translateX(30px); }
    }

    /* -------------------------------------------------------------------- */
    /* 11. Painel de testes (apenas protótipo, sem backend)                  */
    /* -------------------------------------------------------------------- */
    .dev-test-panel {
      padding: 16px 18px;
      margin-top: 22px;
      background: var(--surface-2);
      border: 1px dashed var(--border);
      border-radius: 12px;
    }

    .dev-test-panel .dev-label {
      margin-bottom: 10px;
      color: var(--text-muted);
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 0.3px;
    }

    .dev-test-panel .dev-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .dev-btn {
      padding: 8px 14px;
      color: var(--text);
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 8px;
      font-family: var(--font-body);
      font-size: 12.5px;
      font-weight: 500;
      cursor: pointer;
    }

    .dev-btn:hover {
      border-color: var(--primary);
      color: var(--primary);
    }
  </style>
</head>
<body>

  <!-- Container dos alertas/toasts (fica acima de tudo, posição fixa) -->
  <div class="toast-container" id="toastContainer"></div>

  <!-- ======================= SIDEBAR ======================= -->
  <div class="sidebar" id="sidebar">
    <div class="logo">
      <span class="logo-badge">R</span>
      <span class="logo-text">ROTA<span class="logo-accent">ADS</span></span>
    </div>

    <div class="nav-section-label">Menu</div>
    <nav>
      <button class="nav-item active" type="button" data-screen="dashboard">
        <span class="nav-icon"><i class="bi bi-grid-1x2-fill"></i></span><span class="nav-label">Dashboard</span>
      </button>
      <button class="nav-item" type="button" data-screen="criar">
        <span class="nav-icon"><i class="bi bi-plus-circle-fill"></i></span><span class="nav-label">Criar Anúncio</span>
      </button>
      <button class="nav-item" type="button" data-screen="lista">
        <span class="nav-icon"><i class="bi bi-megaphone-fill"></i></span><span class="nav-label">Meus Anúncios</span>
      </button>
      <button class="nav-item" type="button" data-screen="preview">
        <span class="nav-icon"><i class="bi bi-tablet-landscape-fill"></i></span><span class="nav-label">Preview no Tablet</span>
      </button>
      <button class="nav-item" type="button" data-screen="planos">
        <span class="nav-icon"><i class="bi bi-credit-card-fill"></i></span><span class="nav-label">Planos</span>
      </button>
    </nav>
  </div>

  <!-- Fundo escuro atrás do menu quando aberto no mobile (fecha ao clicar) -->
  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <!-- ======================= ÁREA PRINCIPAL ======================= -->
  <div class="main">

    <div class="topbar">
      <div class="topbar-left">
        <button class="menu-toggle" id="menuToggle" type="button" aria-label="Abrir ou recolher o menu"><i class="bi bi-list"></i></button>
        <h1 id="screen-title">Dashboard</h1>
      </div>

      <div class="topbar-right">
        <div class="user-info" id="userInfo">
          <div class="user-text">
            <div class="user-hello">Olá, <b>Arthur</b></div>
            <div class="user-plan">Plano Profissional</div>
          </div>
          <div class="avatar">JL</div>

          <div class="user-menu" id="userMenu">
            <div class="user-menu-item" onclick="irParaTela('perfil')"><i class="bi bi-person"></i> Perfil</div>
            <div class="user-menu-item"><i class="bi bi-credit-card"></i> Assinatura</div>
            <div class="user-menu-divider"></div>
            <a href="server/logout.php" style="text-decoration: none;" class="user-menu-item danger"><i class="bi bi-box-arrow-right"></i> Sair</a>
          </div>
        </div>
      </div>
    </div>

    <div class="content">

      <!-- ----------------- TELA: DASHBOARD ----------------- -->
      <div class="screen active" id="screen-dashboard">

        <!-- Hero de boas-vindas -->
        <div class="dash-hero">
          <div class="dash-hero-text">
            <h2>Olá, Arthur</h2>
            <p id="heroDate">Carregando data...</p>
          </div>
          <div class="dash-hero-actions">
            <button class="btn-hero solid" type="button" onclick="document.querySelector('[data-screen=criar]').click()">
              <i class="bi bi-plus-circle-fill"></i> Criar novo anúncio
            </button>
            <button class="btn-hero" type="button" onclick="document.querySelector('[data-screen=lista]').click()">
              <i class="bi bi-megaphone"></i> Meus anúncios
            </button>
            <button class="btn-hero" type="button" onclick="document.querySelector('[data-screen=preview]').click()">
              <i class="bi bi-tablet-landscape"></i> Preview no tablet
            </button>
          </div>
        </div>

        <!-- Cards em destaque -->
        <div class="stats-featured">
          <div class="stat-card-featured">
            <div class="stat-top">
              <div class="stat-icon blue"><i class="bi bi-lightning-charge-fill"></i></div>
            </div>
            <div class="stat-label">Seu anúncio apareceu hoje</div>
            <div class="stat-value">127 <span style="font-size:14px; color:var(--text-muted); font-weight:400;">vezes</span></div>
            <div class="stat-sub"><i class="bi bi-arrow-repeat"></i> Atualizado a cada rodízio</div>
          </div>

          <div class="stat-card-featured">
            <div class="stat-top">
              <div class="stat-icon amber"><i class="bi bi-calendar-check-fill"></i></div>
            </div>
            <div class="stat-label">Dias restantes da campanha</div>
            <div class="stat-value">6 <span style="font-size:14px; color:var(--text-muted); font-weight:400;">de 15 dias</span></div>
            <div class="progress-track"><div class="progress-fill" style="width:60%;"></div></div>
            <div class="stat-sub">9 dias já decorridos</div>
          </div>
        </div>

        <!-- Cards secundários -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-top"><div class="stat-icon amber"><i class="bi bi-megaphone-fill"></i></div></div>
            <div class="stat-label">Anúncios ativos</div>
            <div class="stat-value">3 <span style="font-size:14px; color:var(--text-muted); font-weight:400;">/ 5</span></div>
          </div>

          <div class="stat-card">
            <div class="stat-top"><div class="stat-icon green"><i class="bi bi-car-front-fill"></i></div></div>
            <div class="stat-label">Status do carro</div>
            <div class="stat-value" style="font-size:20px; color:var(--success);"><i class="bi bi-circle-fill" style="font-size:10px; vertical-align:middle;"></i> Ativo</div>
          </div>

          <div class="stat-card">
            <div class="stat-top"><div class="stat-icon blue"><i class="bi bi-eye-fill"></i></div></div>
            <div class="stat-label">Visualizações estimadas (7 dias)</div>
            <div class="stat-value">42.6K</div>
          </div>
        </div>

        <!-- Mini preview ao vivo + dica -->
        <div class="dash-secondary-grid">
          <div class="dash-live-card">
            <div class="tablet-mini">
              <div class="tablet-screen-mini">
                <span class="live-tag-mini"><span class="live-dot"></span>AO VIVO</span>
                <div class="ad-brand-mini">Loja do João</div>
                <div class="ad-tag-mini">Promoção de Verão · até 30% OFF</div>
              </div>
            </div>
            <div class="dash-live-info">
              <div class="dash-live-label">RODANDO AGORA NO CARRO</div>
              <h4>Promoção de Verão</h4>
              <p>É este o anúncio que os passageiros estão vendo neste exato momento.</p>
              <button class="btn-outline-light" type="button" onclick="document.querySelector('[data-screen=preview]').click()">
                Ver preview completo <i class="bi bi-arrow-right"></i>
              </button>
            </div>
          </div>

          <div class="dash-tip-card">
            <div class="dash-tip-icon"><i class="bi bi-lightbulb-fill"></i></div>
            <div>
              <h4>Dica</h4>
              <p>Anúncios com imagem vertical e texto curto costumam se destacar melhor na tela do tablet.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- ----------------- TELA: CRIAR ANÚNCIO ----------------- -->
      <div class="screen" id="screen-criar">
        <p class="section-intro">Suba a arte do seu anúncio e defina por quanto tempo ele vai rodar nos tablets.</p>

        <!--
          Formulário pronto para integração com PHP:
            - method="post" + enctype multipart (por causa do upload de imagem)
            - cada campo tem "name" (é o que o PHP vai ler em $_POST / $_FILES)
            - "action" está vazio de propósito — aponte para o script PHP depois
            - o preventDefault() no JS evita reload aqui no protótipo; remova-o
              quando o formulário passar a enviar de verdade para o backend
        -->
        <form
          class="form-grid"
          id="createAdForm"
          name="createAdForm"
          method="post"
          action=""
          enctype="multipart/form-data"
          autocomplete="off"
        >
          <div>
            <div class="upload-zone" id="uploadZone">
              <span class="upicon"><i class="bi bi-cloud-arrow-up"></i></span>
              <div id="uploadLabel">Clique para enviar a imagem do anúncio (JPEG, JPG ou PNG)</div>
              <input
                type="file"
                id="adFile"
                name="ad_image"
                accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                style="display:none"
              >
            </div>

            <label for="adName">Nome do anúncio</label>
            <input
              type="text"
              id="adName"
              name="ad_name"
              autocomplete="off"
              placeholder="Ex: Promoção de Verão — Loja do João"
            >

            <label for="adDescription">Descrição do produto/serviço</label>
            <textarea
              id="adDescription"
              name="ad_description"
              autocomplete="off"
              placeholder="Conte o que você está anunciando..."
            ></textarea>

            <label id="durationLabel">Duração da campanha</label>
            <div class="duration-options" role="group" aria-labelledby="durationLabel">
              <button class="dur-chip" type="button" data-value="7">7 dias</button>
              <button class="dur-chip selected" type="button" data-value="15">15 dias</button>
              <button class="dur-chip" type="button" data-value="30">30 dias</button>
            </div>
            <!-- Guarda o valor da duração escolhida para ser enviado com o form -->
            <input type="hidden" id="adDuration" name="ad_duration" value="15">

            <button class="btn-primary" type="submit">Enviar anúncio para aprovação</button>
          </div>

          <div>
            <label style="margin-top:0">Prévia do card</label>
            <div class="preview-card">
              <div class="ph" id="pcPreview">imagem do anúncio</div>
              <div class="pc-body">
                <div class="pc-title" id="pcTitle">Promoção de Verão</div>
                <div class="pc-desc">Loja do João · 15 dias de campanha</div>
              </div>
            </div>
          </div>
        </form>

        <!-- Painel apenas para testar o sistema de alertas — sem backend ainda -->
        <div class="dev-test-panel">
          <div class="dev-label">TESTAR ALERTAS (uso interno, sem backend)</div>
          <div class="dev-buttons">
            <button class="dev-btn" type="button" onclick="mostrarAlerta('Anúncio enviado para aprovação', 'Você recebe um aviso assim que for aprovado', 'sucesso')">Sucesso</button>
            <button class="dev-btn" type="button" onclick="mostrarAlerta('Não foi possível enviar', 'Verifique sua conexão e tente novamente', 'erro')">Erro</button>
            <button class="dev-btn" type="button" onclick="mostrarAlerta('Pagamento pendente', 'Regularize para manter o anúncio ativo', 'aviso')">Aviso</button>
            <button class="dev-btn" type="button" onclick="mostrarAlerta('Dica', 'Imagens verticais performam melhor no tablet', 'info')">Info</button>
          </div>
        </div>
      </div>

      <!-- ----------------- TELA: MEUS ANÚNCIOS ----------------- -->
      <div class="screen" id="screen-lista">
        <p class="section-intro">Acompanhe o status de exibição e pagamento de cada anúncio.</p>

        <div class="ads-grid">
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
                <span><i class="bi bi-calendar3"></i> 15 dias</span>
                <span class="pay-ok"><i class="bi bi-check-circle-fill"></i> Pago</span>
                <span><i class="bi bi-eye"></i> 18.2K</span>
              </div>
              <div class="ad-card-actions">
                <span class="act-btn" title="Editar"><i class="bi bi-pencil"></i></span>
                <span class="act-btn" title="Pausar"><i class="bi bi-pause-fill"></i></span>
                <span class="act-btn" title="Duplicar"><i class="bi bi-files"></i></span>
                <span class="act-btn danger" title="Excluir"><i class="bi bi-trash"></i></span>
              </div>
            </div>
          </div>

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
                <span><i class="bi bi-calendar3"></i> 30 dias</span>
                <span class="pay-ok"><i class="bi bi-check-circle-fill"></i> Pago</span>
                <span><i class="bi bi-eye"></i> 24.4K</span>
              </div>
              <div class="ad-card-actions">
                <span class="act-btn" title="Editar"><i class="bi bi-pencil"></i></span>
                <span class="act-btn" title="Pausar"><i class="bi bi-pause-fill"></i></span>
                <span class="act-btn" title="Duplicar"><i class="bi bi-files"></i></span>
                <span class="act-btn danger" title="Excluir"><i class="bi bi-trash"></i></span>
              </div>
            </div>
          </div>

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
                <span><i class="bi bi-calendar3"></i> 7 dias</span>
                <span class="pay-pend"><i class="bi bi-clock-history"></i> Pendente</span>
                <span><i class="bi bi-eye"></i> 2.1K</span>
              </div>
              <div class="ad-card-actions">
                <span class="act-btn" title="Editar"><i class="bi bi-pencil"></i></span>
                <span class="act-btn" title="Retomar"><i class="bi bi-play-fill"></i></span>
                <span class="act-btn" title="Duplicar"><i class="bi bi-files"></i></span>
                <span class="act-btn danger" title="Excluir"><i class="bi bi-trash"></i></span>
              </div>
            </div>
          </div>

          <div class="ad-card">
            <div class="ad-card-thumb" style="background:linear-gradient(135deg,#8A8CA5,#565875);">
              <i class="bi bi-image thumb-fallback"></i>
            </div>
            <div class="ad-card-body">
              <div class="ad-card-head">
                <div class="ad-card-title">Feira de Setembro</div>
                <span class="badge expirado">Expirado</span>
              </div>
              <div class="ad-card-meta">
                <span><i class="bi bi-calendar3"></i> 15 dias</span>
                <span class="pay-ok"><i class="bi bi-check-circle-fill"></i> Pago</span>
                <span><i class="bi bi-eye"></i> 31.7K</span>
              </div>
              <div class="ad-card-actions">
                <span class="act-btn" title="Renovar"><i class="bi bi-arrow-repeat"></i></span>
                <span class="act-btn" title="Duplicar"><i class="bi bi-files"></i></span>
                <span class="act-btn danger" title="Excluir"><i class="bi bi-trash"></i></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ----------------- TELA: PREVIEW NO TABLET ----------------- -->
      <div class="screen" id="screen-preview">
        <p class="section-intro">Simulação de como o anúncio aparece no tablet fixado no carro, do ponto de vista do passageiro.</p>

        <div class="dash-scene">
          <div class="live-tag"><span class="live-dot"></span>AO VIVO NO CARRO</div>

          <div class="tablet">
            <div class="tablet-screen" id="tabletScreen">
              <div class="ad-slide show" data-i="0" style="background:linear-gradient(135deg,#3E5EE0,#26399C)">
                <div class="ad-content">
                  <div class="ad-brand">Loja do João</div>
                  <div class="ad-tag">Promoção de Verão · até 30% OFF</div>
                </div>
              </div>
              <div class="ad-slide" data-i="1" style="background:linear-gradient(135deg,#17C666,#0E8548)">
                <div class="ad-content">
                  <div class="ad-brand">Combo Lanche+Suco</div>
                  <div class="ad-tag">Só essa semana no Centro</div>
                </div>
              </div>
              <div class="ad-slide" data-i="2" style="background:linear-gradient(135deg,#F3A638,#B9721B)">
                <div class="ad-content">
                  <div class="ad-brand">Feira de Setembro</div>
                  <div class="ad-tag">Sáb e Dom · Praça Central</div>
                </div>
              </div>
            </div>
          </div>

          <div class="dash-base"></div>
          <div class="dots" id="dots"></div>
          <div class="dash-caption">
            O tablet fica fixado no painel do carro e alterna os anúncios contratados durante toda a corrida —
            <b>visibilidade garantida a cada passageiro.</b>
          </div>
        </div>
      </div>

      <!-- ----------------- TELA: PLANOS ----------------- -->
      <div class="screen" id="screen-planos">
        <p class="section-intro">Assinatura mensal por número de tablets em que seu anúncio roda.</p>

        <div class="plans-grid">
          <div class="plan-card">
            <div class="plan-name">Básico</div>
            <div class="plan-price">R$ 79<span>/mês</span></div>
            <div class="plan-desc">Para começar a testar</div>
            <ul class="plan-feats">
              <li>1 anúncio ativo</li>
              <li>Roda em até 20 carros</li>
              <li>Relatório semanal</li>
            </ul>
            <button class="btn-outline" type="button">Assinar Básico</button>
          </div>

          <div class="plan-card featured">
            <div class="plan-tag">MAIS ESCOLHIDO</div>
            <div class="plan-name">Profissional</div>
            <div class="plan-price">R$ 179<span>/mês</span></div>
            <div class="plan-desc">Para negócios em crescimento</div>
            <ul class="plan-feats">
              <li>Até 3 anúncios ativos</li>
              <li>Roda em até 100 carros</li>
              <li>Escolha de rotas/bairros</li>
              <li>Relatório em tempo real</li>
            </ul>
            <button class="btn-primary" type="button" style="margin-top:0">Assinar Profissional</button>
          </div>

          <div class="plan-card">
            <div class="plan-name">Premium</div>
            <div class="plan-price">R$ 349<span>/mês</span></div>
            <div class="plan-desc">Para máxima exposição</div>
            <ul class="plan-feats">
              <li>Anúncios ilimitados</li>
              <li>Roda em toda a frota</li>
              <li>Prioridade de exibição</li>
              <li>Gerente de conta dedicado</li>
            </ul>
            <button class="btn-outline" type="button">Assinar Premium</button>
          </div>
        </div>
      </div>

      <!-- ----------------- TELA: PERFIL DO USUÁRIO ----------------- -->
      <div class="screen" id="screen-perfil">

        <button class="profile-back" type="button" onclick="irParaTela('dashboard')">
          <i class="bi bi-arrow-left"></i> Voltar ao Dashboard
        </button>

        <!-- Hero do perfil -->
        <div class="profile-hero">
          <div class="profile-avatar-wrap">
            <div class="profile-avatar-lg" id="profileAvatarPreview">JL</div>
            <div class="profile-avatar-edit" id="avatarEditBtn" title="Alterar foto">
              <i class="bi bi-camera-fill"></i>
            </div>
            <!-- Sem backend ainda: só troca o preview localmente. No PHP, o campo
                 "avatar_image" (dentro do #profileForm) chega em $_FILES. -->
            <input
              type="file"
              id="avatarFile"
              name="avatar_image"
              accept=".jpg,.jpeg,.png,image/jpeg,image/png"
              style="display:none"
              form="profileForm"
            >
          </div>
          <div class="profile-hero-info">
            <div class="profile-hero-name">Arthur J. Lima</div>
            <div class="profile-hero-badges">
              <span class="info-badge plan"><i class="bi bi-credit-card-fill"></i> Plano Profissional</span>
              <span class="info-badge"><i class="bi bi-calendar3"></i> Membro desde mar/2025</span>
            </div>
          </div>
        </div>

        <!--
          Formulário pronto para integração com PHP:
            - method="post" + enctype multipart (por causa do upload de avatar)
            - cada campo tem "name" (é o que o PHP vai ler em $_POST / $_FILES)
            - "action" está vazio de propósito — aponte para o script PHP depois
            - o preventDefault() no JS evita reload aqui no protótipo; remova-o
              quando o formulário passar a enviar de verdade para o backend
        -->
        <form
          class="profile-grid"
          id="profileForm"
          name="profileForm"
          method="post"
          action=""
          enctype="multipart/form-data"
          autocomplete="off"
        >
          <!-- Dados pessoais -->
          <div class="profile-card">
            <div class="profile-card-title"><i class="bi bi-person-fill"></i> Dados pessoais</div>
            <p class="profile-card-desc">Suas informações de acesso e contato.</p>

            <label for="fullName">Nome completo</label>
            <input
              type="text"
              id="fullName"
              name="full_name"
              autocomplete="name"
              value="Arthur J. Lima"
            >

            <label for="username">Nome de usuário</label>
            <input
              type="text"
              id="username"
              name="username"
              autocomplete="username"
              value="arthur.lima"
            >

            <label for="email">E-mail</label>
            <input
              type="email"
              id="email"
              name="email"
              autocomplete="email"
              value="arthur.lima@email.com"
            >

            <label for="phone">Telefone</label>
            <input
              type="tel"
              id="phone"
              name="phone"
              autocomplete="tel"
              placeholder="(99) 99999-9999"
              value="(11) 98888-7766"
            >
          </div>

          <!-- Dados do negócio -->
          <div class="profile-card">
            <div class="profile-card-title"><i class="bi bi-shop"></i> Dados do negócio</div>
            <p class="profile-card-desc">Informações usadas nos seus anúncios.</p>

            <label for="companyName">Nome da empresa/serviço/produto</label>
            <input
              type="text"
              id="companyName"
              name="company_name"
              autocomplete="off"
              value="Loja do João"
            >

            <label for="businessCategory">Categoria do negócio</label>
            <select id="businessCategory" name="business_category">
              <option value="alimentacao">Alimentação</option>
              <option value="beleza">Beleza e Estética</option>
              <option value="servicos" selected>Serviços</option>
              <option value="comercio">Comércio/Varejo</option>
              <option value="saude">Saúde</option>
              <option value="outros">Outros</option>
            </select>

            <label for="socialLink">Site ou Instagram</label>
            <input
              type="text"
              id="socialLink"
              name="social_link"
              autocomplete="off"
              placeholder="@seuinsta ou www.seusite.com.br"
              value="@lojadojoao"
            >
          </div>

          <div class="form-actions" style="grid-column: 1 / -1;">
            <button class="btn-outline" type="reset">Cancelar</button>
            <button class="btn-primary" type="submit">Salvar alterações</button>
          </div>
        </form>

        <!-- Plano atual -->
        <div class="profile-card profile-plan-card" style="margin-bottom:20px;">
          <div class="profile-plan-info">
            <div class="profile-card-title"><i class="bi bi-credit-card-fill"></i> Plano atual</div>
            <div class="plan-current-name">Profissional <span class="plan-current-price">R$ 179/mês</span></div>
            <div class="plan-current-desc">Até 3 anúncios ativos · roda em até 100 carros</div>
          </div>
          <button class="btn-outline" type="button" onclick="irParaTela('planos')">
            Ver planos disponíveis
          </button>
        </div>

        <!-- Segurança / alterar senha -->
        <div class="profile-card">
          <div class="profile-card-title"><i class="bi bi-shield-lock-fill"></i> Segurança</div>
          <p class="profile-card-desc">Altere sua senha de acesso periodicamente.</p>

          <!--
            Formulário separado de senha, próprio para integração com PHP:
              - envia apenas os 3 campos de senha, sem misturar com os dados de perfil
              - o preventDefault() no JS evita reload aqui no protótipo; remova-o
                quando o formulário passar a enviar de verdade para o backend
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
       ROTA ADS — LÓGICA DO PROTÓTIPO
       Sumário:
         1. Navegação entre telas (mostra/esconde, sem reload de página)
         2. Sidebar responsiva (colapsar / abrir no mobile)
         3. Upload de imagem com preview real + validação de formato
         4. Chips de duração da campanha (grava no input hidden do form)
         5. Menu dropdown do usuário no cabeçalho
         6. Sistema global de alertas (Toasts)
         7. Data de boas-vindas no dashboard
       ==================================================================== */

    /* ---- 1. Navegação entre telas -------------------------------------- */
    const screenTitles = {
      dashboard: 'Dashboard',
      criar: 'Criar Anúncio',
      lista: 'Meus Anúncios',
      preview: 'Preview no Tablet',
      planos: 'Planos',
      perfil: 'Meu Perfil',
    };

    // Função reaproveitável de navegação: usada pelos itens da sidebar (que têm
    // um .nav-item correspondente) e por telas sem item na sidebar, como o
    // Perfil (acessado pelo dropdown do avatar) ou botões internos ("Voltar
    // ao Dashboard", "Ver planos disponíveis" etc.).
    function irParaTela(screenId) {
      document.querySelectorAll('.nav-item').forEach((b) => b.classList.remove('active'));
      document.querySelectorAll('.screen').forEach((s) => s.classList.remove('active'));

      const navButton = document.querySelector(`.nav-item[data-screen="${screenId}"]`);
      if (navButton) navButton.classList.add('active');

      document.getElementById(`screen-${screenId}`).classList.add('active');
      document.getElementById('screen-title').innerText = screenTitles[screenId];

      // Em telas pequenas, fecha o menu automaticamente após navegar
      if (window.innerWidth < 900) {
        sidebar.classList.remove('mobile-open');
        sidebarBackdrop.classList.remove('show');
      }
    }

    document.querySelectorAll('.nav-item').forEach((button) => {
      button.addEventListener('click', () => irParaTela(button.dataset.screen));
    });

    /* ---- 2. Sidebar responsiva ------------------------------------------
       Três estados possíveis (ver CSS na seção 3):
         - completa  : padrão em telas >= 1280px
         - .collapsed: só ícones, padrão em telas entre 900px e 1279px
         - .mobile-open: painel sobre o conteúdo, usado em telas < 900px
       O botão do cabeçalho (menuToggle) alterna manualmente o estado
       correspondente à largura atual da tela. -------------------------- */
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

    /* ---- 3. Upload de imagem com preview + validação --------------------
       No backend em PHP, o mesmo tipo de validação (extensão/mime type)
       precisa ser repetida no servidor — validação no front-end é só
       conveniência para o usuário, nunca segurança. --------------------- */
    const adFile = document.getElementById('adFile');
    const uploadZone = document.getElementById('uploadZone');
    const uploadLabel = document.getElementById('uploadLabel');
    const pcPreview = document.getElementById('pcPreview');

    uploadZone.addEventListener('click', () => adFile.click());

    adFile.addEventListener('change', () => {
      const file = adFile.files[0];
      if (!file) return;

      const allowedTypes = ['image/jpeg', 'image/png'];
      if (!allowedTypes.includes(file.type)) {
        uploadLabel.innerText = 'Formato inválido. Envie apenas JPEG, JPG ou PNG.';
        uploadLabel.style.color = 'var(--danger)';
        adFile.value = '';
        return;
      }

      uploadLabel.style.color = '';
      uploadLabel.innerText = file.name;

      const objectUrl = URL.createObjectURL(file);
      pcPreview.style.backgroundImage = `url(${objectUrl})`;
      pcPreview.innerText = '';
    });

    /* ---- 4. Chips de duração da campanha --------------------------------- */
    const adDuration = document.getElementById('adDuration');

    document.querySelectorAll('.dur-chip').forEach((chip) => {
      chip.addEventListener('click', () => {
        document.querySelectorAll('.dur-chip').forEach((c) => c.classList.remove('selected'));
        chip.classList.add('selected');
        adDuration.value = chip.dataset.value;
      });
    });

    // Protótipo apenas: evita reload da página ao "enviar" o formulário.
    // Remova este bloco quando o "action" apontar para o script PHP real.
    document.getElementById('createAdForm').addEventListener('submit', (e) => {
      e.preventDefault();
    });

    /* ---- 4.1 Tela de Perfil: avatar, formulário de dados e troca de senha */

    // Clique no ícone da câmera abre o seletor de arquivo do avatar
    const avatarEditBtn = document.getElementById('avatarEditBtn');
    const avatarFile = document.getElementById('avatarFile');
    const profileAvatarPreview = document.getElementById('profileAvatarPreview');

    avatarEditBtn.addEventListener('click', () => avatarFile.click());

    // Prévia local da nova foto (sem backend ainda). No PHP, este mesmo
    // input ("avatar_image") chega em $_FILES dentro do #profileForm.
    avatarFile.addEventListener('change', () => {
      const file = avatarFile.files[0];
      if (!file) return;

      const url = URL.createObjectURL(file);
      profileAvatarPreview.style.backgroundImage = `url('${url}')`;
      profileAvatarPreview.style.backgroundSize = 'cover';
      profileAvatarPreview.style.backgroundPosition = 'center';
      profileAvatarPreview.textContent = '';
    });

    // Protótipo apenas: evita reload da página ao "salvar" o perfil.
    // Remova este bloco quando o "action" apontar para o script PHP real.
    document.getElementById('profileForm').addEventListener('submit', (e) => {
      e.preventDefault();
      mostrarAlerta('Perfil atualizado', 'Suas informações foram salvas com sucesso', 'sucesso');
    });

    // Protótipo apenas: valida localmente e evita reload da página.
    // A validação "de verdade" (senha atual correta, força da senha, etc.)
    // deve ser feita no PHP; isso aqui é só para simular o feedback visual.
    document.getElementById('changePasswordForm').addEventListener('submit', (e) => {
      e.preventDefault();

      const novaSenha = document.getElementById('newPassword').value;
      const confirmarSenha = document.getElementById('confirmPassword').value;

      if (!novaSenha || !confirmarSenha) {
        mostrarAlerta('Preencha a nova senha', 'Digite e confirme a nova senha para continuar', 'aviso');
        return;
      }

      if (novaSenha !== confirmarSenha) {
        mostrarAlerta('As senhas não coincidem', 'Verifique a nova senha e a confirmação', 'erro');
        return;
      }

      mostrarAlerta('Senha atualizada', 'Use a nova senha no seu próximo acesso', 'sucesso');
      e.target.reset();
    });

    /* ---- 5. Menu dropdown do usuário -------------------------------------- */
    const userInfo = document.getElementById('userInfo');
    const userMenu = document.getElementById('userMenu');

    userInfo.addEventListener('click', (e) => {
      e.stopPropagation();
      userMenu.classList.toggle('show');
    });

    document.addEventListener('click', () => {
      userMenu.classList.remove('show');
    });

    /* ---- Rotação automática dos anúncios no tablet (tela "Preview") ------ */
    const adSlides = document.querySelectorAll('.ad-slide');
    const dotsWrap = document.getElementById('dots');

    adSlides.forEach((_, i) => {
      const dot = document.createElement('div');
      dot.className = `dot${i === 0 ? ' active' : ''}`;
      dotsWrap.appendChild(dot);
    });

    let currentSlide = 0;
    setInterval(() => {
      adSlides[currentSlide].classList.remove('show');
      dotsWrap.children[currentSlide].classList.remove('active');

      currentSlide = (currentSlide + 1) % adSlides.length;

      adSlides[currentSlide].classList.add('show');
      dotsWrap.children[currentSlide].classList.add('active');
    }, 3000);

    /* ---- 6. Sistema global de alertas (Toasts) ---------------------------
       Uso: mostrarAlerta('Título', 'Subtítulo opcional', 'sucesso' | 'erro' | 'aviso' | 'info')
       - Duração fixa de 4s para todos os alertas.
       - Suporta múltiplos alertas empilhados (cada chamada adiciona um novo).
       - Sem dependência de backend: é só uma função de UI. -------------- */
    const TOAST_ICONS = {
      sucesso: 'bi-check-circle-fill',
      erro: 'bi-x-circle-fill',
      aviso: 'bi-exclamation-triangle-fill',
      info: 'bi-info-circle-fill',
    };
    const TOAST_DURATION_MS = 4000;

    function mostrarAlerta(mensagem, subtitulo, tipo) {
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
      // Usa textContent (não innerHTML) para o texto vindo de fora,
      // evitando problemas caso a mensagem contenha caracteres especiais.
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

    /* ---- 7. Data de boas-vindas no dashboard ------------------------------ */
    const heroDate = document.getElementById('heroDate');
    if (heroDate) {
      const hoje = new Date();
      const dataFormatada = hoje.toLocaleDateString('pt-BR', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
      });
      heroDate.textContent = `${dataFormatada} · aqui está um resumo da sua campanha.`;
    }
  </script>

</body>
</html>
