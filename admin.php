<?php 
  include "server/auth.php";
  if ($nivel != "admin") {
    header("location: painel.php");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GunesAds — Painel Administrativo</title>

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
       GUNESADS — PAINEL ADMINISTRATIVO (V1 — Documento Mestre)
       --------------------------------------------------------------------------
       Este arquivo é a base definitiva de template PHP para o painel do
       administrador, contemplando somente as 4 telas previstas na V1:
       Dashboard, Usuários, Anúncios e Planos. Fluxos anteriores (anúncios
       pendentes/aprovação, anúncios institucionais separados, planos de
       assinatura mensal) foram descontinuados conforme o Documento Mestre.

       Sumário deste arquivo:
         1. Reset básico + variáveis globais
         2. Layout base + sidebar responsiva
         3. Topbar + menu do usuário
         4. Tela: Dashboard (banner de boas-vindas + métricas)
         5. Tela: Usuários (busca + listagem + CRUD completo)
         6. Tela: Anúncios (busca + listagem com filtro + CRUD + status)
         7. Tela: Planos (grade por nível — Básico/Profissional/Premium — + serviço de arte, edição de preço)
         8. Sistema de modais
         9. Sistema de alertas (Toasts)
         10. Tela: Perfil (administrador — dados editáveis, sem item na sidebar)
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
      max-width: 620px;
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

    @media (max-width: 640px) {
      .topbar { padding: 0 16px; }
      .topbar h1 { font-size: 17px; }
    }

    @media (max-width: 420px) {
      .topbar h1 { font-size: 15px; }
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
      background-size: cover;
      background-position: center;
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
      display: inline-flex;
      align-items: center;
      gap: 4px;
      color: var(--primary);
      font-size: 11.5px;
      font-weight: 500;
    }

    @media (max-width: 560px) {
      .user-text { display: none; }
      .user-info { gap: 0; padding: 4px; }
    }

    .user-menu {
      display: none;
      position: absolute;
      top: 52px;
      right: 0;
      z-index: 20;
      width: 200px;
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
    /* 4. Dashboard                                                          */
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
    .stat-icon.red   { background: var(--danger-light);   color: var(--danger); }
    .stat-icon.cyan  { background: var(--info-light);     color: var(--info); }

    .stat-label {
      color: var(--text-muted);
      font-size: 13px;
    }

    .stat-value {
      font-family: var(--font-display);
      font-size: 26px;
      font-weight: 700;
    }

    /* -------------------------------------------------------------------- */
    /* 4b. Dashboard — banner de boas-vindas + cards em destaque             */
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

    .stat-card.featured {
      position: relative;
      overflow: hidden;
      color: #fff;
      background: linear-gradient(160deg, #1C1F2B, #0C0E15);
      border: none;
    }

    .stat-card.featured .stat-label {
      color: #B7BBCB;
    }

    .stat-card.featured .stat-value {
      color: #fff;
    }

    .stat-card.featured .stat-icon {
      background: rgba(255, 255, 255, 0.1);
      color: #7C9BFF;
    }

    /* -------------------------------------------------------------------- */
    /* 5. Usuários                                                           */
    /* -------------------------------------------------------------------- */
    .screen-toolbar {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 18px;
    }

    .toolbar-count {
      color: var(--text-muted);
      font-size: 13px;
    }

    .toolbar-count b { color: var(--text); }

    .btn-new {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 11px 18px;
      color: #fff;
      background: var(--primary);
      border: none;
      border-radius: 10px;
      font-family: var(--font-body);
      font-size: 13.5px;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 8px 18px rgba(62, 94, 224, 0.28);
    }

    .btn-new:hover { background: var(--primary-dark); }

    /* Campo de busca — reutilizado nas telas Usuários e Anúncios */
    .search-box {
      position: relative;
      flex: 1 1 260px;
      max-width: 360px;
    }

    .search-box i {
      position: absolute;
      top: 50%;
      left: 14px;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 14px;
    }

    .search-box input {
      width: 100%;
      padding: 10px 14px 10px 38px;
      color: var(--text);
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 10px;
      font-family: var(--font-body);
      font-size: 13.5px;
    }

    .search-box input:focus {
      border-color: var(--primary);
      outline: none;
    }

    .users-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .user-row {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 16px;
      padding: 16px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      box-shadow: var(--shadow);
      transition: opacity 0.25s ease, transform 0.25s ease;
    }

    .user-row.removing {
      opacity: 0;
      transform: scale(0.97);
    }

    .user-row.inativo { opacity: 0.4; }

    .user-row-avatar {
      display: flex;
      flex-shrink: 0;
      align-items: center;
      justify-content: center;
      width: 42px;
      height: 42px;
      background: linear-gradient(135deg, var(--primary), #6B8CFF);
      background-size: cover;
      background-position: center;
      color: #fff;
      border-radius: 10px;
      font-family: var(--font-display);
      font-weight: 700;
      font-size: 13.5px;
    }

    .user-row-info { flex: 1 1 200px; min-width: 0; }

    .user-row-name { font-weight: 600; font-size: 14px; }

    .user-row-sub {
      display: flex;
      flex-wrap: wrap;
      gap: 4px 12px;
      margin-top: 3px;
      color: var(--text-muted);
      font-size: 12.5px;
    }

    .user-row-sub span {
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .status-badge {
      display: inline-flex;
      flex-shrink: 0;
      align-items: center;
      gap: 5px;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      white-space: nowrap;
    }

    .status-badge.ativo   { color: var(--success); background: var(--success-light); }
    .status-badge.inativo { color: var(--text-muted); background: var(--surface-2); }

    /* Nível de acesso do usuário (Admin/Usuário) — mesmo formato visual do
       status-badge, cores próprias para não ser confundido com Ativo/Inativo. */
    .nivel-badge {
      display: inline-flex;
      flex-shrink: 0;
      align-items: center;
      gap: 5px;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      white-space: nowrap;
    }

    .nivel-badge.admin   { color: var(--primary); background: var(--primary-light); }
    .nivel-badge.usuario { color: var(--info); background: var(--info-light); }

    .user-row-actions {
      display: flex;
      flex-shrink: 0;
      gap: 6px;
    }

    .row-act-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 34px;
      height: 34px;
      background: var(--surface-2);
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 14px;
      color: var(--text-muted);
      cursor: pointer;
    }

    .row-act-btn:hover {
      color: var(--primary);
      background: var(--primary-light);
      border-color: var(--primary);
    }

    .row-act-btn.toggle-off:hover {
      color: var(--danger);
      background: var(--danger-light);
      border-color: var(--danger);
    }

    .row-act-btn.toggle-on:hover {
      color: var(--success);
      background: var(--success-light);
      border-color: var(--success);
    }

    .row-act-btn.danger:hover {
      color: var(--danger);
      background: var(--danger-light);
      border-color: var(--danger);
    }

    /* Ação "Ver contrato": fica desabilitada quando o usuário não tem
       nenhum PDF de contrato cadastrado. */
    .row-act-btn.disabled {
      color: var(--border);
      cursor: not-allowed;
      pointer-events: none;
    }

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
    /* 6. Anúncios                                                           */
    /* -------------------------------------------------------------------- */
    .filter-chips {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 18px;
    }

    .filter-chip {
      padding: 8px 16px;
      color: var(--text-muted);
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 20px;
      font-family: var(--font-body);
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
    }

    .filter-chip.selected {
      color: #fff;
      background: var(--primary);
      border-color: var(--primary);
    }

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
      transition: opacity 0.25s ease, transform 0.25s ease;
    }

    .ad-card.removing {
      opacity: 0;
      transform: scale(0.97);
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
      margin-bottom: 8px;
    }

    .ad-card-title {
      font-weight: 600;
      font-size: 14.5px;
      line-height: 1.35;
    }

    .ad-card-anunciante {
      display: flex;
      align-items: center;
      gap: 5px;
      margin-bottom: 10px;
      color: var(--text-muted);
      font-size: 12.5px;
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

    .ad-card-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 6px 14px;
      margin-bottom: 8px;
      color: var(--text-muted);
      font-size: 12.5px;
    }

    .ad-card-meta span {
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .ad-card-metrics {
      margin-bottom: 16px;
      padding-top: 12px;
      border-top: 1px dashed var(--border);
    }

    .ad-card-views {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 10px;
      color: var(--text-muted);
      font-size: 12.5px;
      font-weight: 600;
    }

    .ad-card-views i { color: var(--primary); }

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

    .ad-card-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: auto;
      padding-top: 14px;
      border-top: 1px solid var(--border);
    }

    .act-btn {
      display: inline-flex;
      flex: 1 1 auto;
      align-items: center;
      justify-content: center;
      height: 34px;
      min-width: 34px;
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

    /* Upload de imagem + prévia do card — mesmo padrão do restante do sistema */
    .form-grid {
      display: grid;
      grid-template-columns: 1.1fr 0.9fr;
      gap: 24px;
    }

    @media (max-width: 900px) {
      .form-grid { grid-template-columns: 1fr; }
    }

    .upload-zone {
      padding: 30px 20px;
      margin-bottom: 4px;
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
      font-size: 22px;
    }

    .upload-zone-preview {
      display: none;
      align-items: center;
      justify-content: center;
      height: 140px;
      margin-bottom: 4px;
      overflow: hidden;
      background-position: center;
      background-size: cover;
      border-radius: 14px;
    }

    .upload-zone-preview.show { display: flex; }

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

    .preview-card .pc-body { padding: 14px; }

    .preview-card .pc-title {
      margin-bottom: 4px;
      font-weight: 600;
      font-size: 14px;
    }

    .preview-card .pc-desc {
      color: var(--text-muted);
      font-size: 12px;
    }

    .checkbox-row {
      display: flex;
      align-items: center;
      gap: 9px;
      margin-top: 16px;
      font-size: 13px;
      font-weight: 500;
      color: var(--text);
    }

    .checkbox-row input {
      width: 16px;
      height: 16px;
      accent-color: var(--primary);
    }

    .form-hint {
      margin-top: 6px;
      color: var(--text-muted);
      font-size: 12px;
    }

    /* -------------------------------------------------------------------- */
    /* 7. Planos                                                             */
    /* -------------------------------------------------------------------- */
    .plan-group {
      margin-bottom: 26px;
    }

    .plan-group-title {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 14px;
      font-family: var(--font-display);
      font-size: 15px;
      font-weight: 600;
    }

    .plan-group-title i { color: var(--primary); }

    .plan-group-duracao {
      margin-left: auto;
      padding: 4px 12px;
      background: var(--primary-light);
      color: var(--primary);
      border-radius: 20px;
      font-family: var(--font-body);
      font-size: 11.5px;
      font-weight: 600;
    }

    .plans-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
    }

    @media (max-width: 900px) {
      .plans-grid { grid-template-columns: 1fr; }
    }

    .plan-card {
      display: flex;
      flex-direction: column;
      padding: 22px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 16px;
      box-shadow: var(--shadow);
    }

    .plan-card-duracao {
      color: var(--text-muted);
      font-size: 12.5px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.4px;
    }

    .plan-card-preco {
      margin: 6px 0 4px;
      color: var(--text);
      font-family: var(--font-display);
      font-size: 26px;
      font-weight: 700;
    }

    .plan-card-desc {
      margin-bottom: 18px;
      color: var(--text-muted);
      font-size: 12.5px;
    }

    .plan-card-edit {
      margin-top: auto;
      padding-top: 14px;
      border-top: 1px solid var(--border);
    }

    .plan-card-edit .pend-btn {
      width: 100%;
    }

    .pend-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      height: 36px;
      padding: 0 14px;
      background: var(--surface-2);
      border: 1px solid var(--border);
      border-radius: 8px;
      font-family: var(--font-body);
      font-size: 12.5px;
      font-weight: 600;
      color: var(--text-muted);
      cursor: pointer;
    }

    .pend-btn:hover {
      color: var(--primary);
      background: var(--primary-light);
      border-color: var(--primary);
    }

    .plan-service-card {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 22px;
      background: var(--info-light);
      border: 1px solid #CDEFF6;
      border-radius: 16px;
    }

    .plan-service-info {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .plan-service-icon {
      display: flex;
      flex-shrink: 0;
      align-items: center;
      justify-content: center;
      width: 42px;
      height: 42px;
      background: var(--info);
      color: #fff;
      border-radius: 11px;
      font-size: 18px;
    }

    .plan-service-info h4 {
      font-family: var(--font-display);
      font-size: 14.5px;
      font-weight: 600;
    }

    .plan-service-info p {
      margin-top: 3px;
      color: var(--text-muted);
      font-size: 12.5px;
    }

    .plan-service-price {
      font-family: var(--font-display);
      font-size: 20px;
      font-weight: 700;
      color: var(--text);
      white-space: nowrap;
    }

    /* -------------------------------------------------------------------- */
    /* 8. Sistema de modais                                                  */
    /* -------------------------------------------------------------------- */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 200;
      align-items: center;
      justify-content: center;
      padding: 20px;
      background: rgba(15, 17, 23, 0.45);
      animation: overlayIn 0.18s ease;
    }

    .modal-overlay.show { display: flex; }

    @keyframes overlayIn {
      from { opacity: 0; }
      to   { opacity: 1; }
    }

    .modal-box {
      width: 100%;
      max-width: 460px;
      max-height: 88vh;
      overflow-y: auto;
      background: var(--surface);
      border-radius: 16px;
      box-shadow: 0 24px 60px rgba(15, 17, 23, 0.3);
      animation: modalIn 0.2s ease;
    }

    .modal-box.wide { max-width: 640px; }

    @keyframes modalIn {
      from { opacity: 0; transform: translateY(10px) scale(0.98); }
      to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-head {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 12px;
      padding: 20px 22px 16px;
      border-bottom: 1px solid var(--border);
    }

    .modal-head h3 {
      font-family: var(--font-display);
      font-size: 16px;
      font-weight: 600;
    }

    .modal-head .modal-sub {
      margin-top: 3px;
      color: var(--text-muted);
      font-size: 12.5px;
    }

    .modal-close {
      display: flex;
      flex-shrink: 0;
      align-items: center;
      justify-content: center;
      width: 30px;
      height: 30px;
      color: var(--text-muted);
      background: var(--surface-2);
      border: none;
      border-radius: 8px;
      font-size: 15px;
      cursor: pointer;
    }

    .modal-close:hover { color: var(--text); }

    .modal-body { padding: 20px 22px; }

    .modal-body label {
      display: block;
      margin-top: 14px;
      margin-bottom: 6px;
      color: var(--text-muted);
      font-size: 13px;
      font-weight: 500;
    }

    .modal-body label:first-child { margin-top: 0; }

    .modal-body input[type="text"],
    .modal-body input[type="email"],
    .modal-body input[type="tel"],
    .modal-body input[type="date"],
    .modal-body textarea,
    .modal-body select {
      width: 100%;
      padding: 11px 14px;
      color: var(--text);
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 10px;
      font-family: var(--font-body);
      font-size: 14px;
    }

    .modal-body input:focus,
    .modal-body textarea:focus,
    .modal-body select:focus {
      border-color: var(--primary);
      outline: none;
    }

    .modal-body input[readonly] {
      color: var(--text-muted);
      background: var(--surface-2);
    }

    .modal-body textarea {
      min-height: 70px;
      resize: vertical;
    }

    .modal-grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }

    .modal-warning-box {
      display: flex;
      gap: 10px;
      padding: 14px;
      background: var(--danger-light);
      border-radius: 10px;
      color: var(--danger);
      font-size: 13px;
      line-height: 1.5;
    }

    .modal-foot {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      padding: 16px 22px 22px;
    }

    .modal-btn {
      padding: 10px 18px;
      border: none;
      border-radius: 10px;
      font-family: var(--font-body);
      font-size: 13.5px;
      font-weight: 600;
      cursor: pointer;
    }

    .modal-btn.ghost {
      color: var(--text);
      background: var(--surface-2);
      border: 1px solid var(--border);
    }

    .modal-btn.ghost:hover { border-color: var(--text-muted); }

    .modal-btn.primary {
      color: #fff;
      background: var(--primary);
      box-shadow: 0 8px 18px rgba(62, 94, 224, 0.28);
    }

    .modal-btn.primary:hover { background: var(--primary-dark); }

    .modal-btn.danger {
      color: #fff;
      background: var(--danger);
    }

    .modal-btn.danger:hover { background: #D6362F; }

    .field-error {
      display: none;
      margin-top: 6px;
      color: var(--danger);
      font-size: 12px;
    }

    .field-error.show { display: block; }

    .modal-body .form-grid {
      display: grid;
      grid-template-columns: 1.1fr 0.9fr;
      gap: 20px;
    }

    @media (max-width: 560px) {
      .modal-body .form-grid { grid-template-columns: 1fr; }
    }

    /* -------------------------------------------------------------------- */
    /* 9. Sistema de alertas (Toasts)                                        */
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

    .toast.removing { animation: toastOut 0.2s ease forwards; }

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
    /* 10. Perfil administrativo                                             */
    /* Acesso somente pelo menu do usuário (topbar) — não entra na sidebar.  */
    /* Diferente do painel do cliente, aqui TODOS os dados são editáveis     */
    /* pelo próprio administrador (nome, e-mail, telefone, cargo).           */
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

    /* Formulários do Perfil (dados pessoais editáveis + alterar senha) */
    .profile-card label {
      display: block;
      margin-top: 16px;
      margin-bottom: 6px;
      color: var(--text-muted);
      font-size: 13px;
      font-weight: 500;
    }

    .profile-card label:first-child {
      margin-top: 0;
    }

    .profile-card input[type="text"],
    .profile-card input[type="email"],
    .profile-card input[type="tel"],
    .profile-card input[type="password"] {
      width: 100%;
      padding: 11px 14px;
      color: var(--text);
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 10px;
      font-family: var(--font-body);
      font-size: 14px;
    }

    .profile-card input:focus {
      border-color: var(--primary);
      outline: none;
    }

    .profile-card .btn-primary {
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

    .profile-card .btn-primary:hover {
      background: var(--primary-dark);
    }

    .profile-card .form-actions {
      display: flex;
      justify-content: flex-end;
      margin-top: 22px;
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

    <div class="nav-section-label">Administração</div>
    <nav>
      <button class="nav-item active" type="button" data-screen="dashboard">
        <span class="nav-icon"><i class="bi bi-grid-1x2-fill"></i></span><span class="nav-label">Dashboard</span>
      </button>
      <button class="nav-item" type="button" data-screen="usuarios">
        <span class="nav-icon"><i class="bi bi-people-fill"></i></span><span class="nav-label">Usuários</span>
      </button>
      <button class="nav-item" type="button" data-screen="anuncios">
        <span class="nav-icon"><i class="bi bi-megaphone-fill"></i></span><span class="nav-label">Anúncios</span>
      </button>
      <button class="nav-item" type="button" data-screen="planos">
        <span class="nav-icon"><i class="bi bi-credit-card-fill"></i></span><span class="nav-label">Planos</span>
      </button>
    </nav>
  </div>

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
            <div class="user-hello">Olá, <b><?= $firstName ?></b></div>
            <div class="user-plan"><i class="bi bi-shield-fill-check"></i> Administrador</div>
          </div>
          <!--
            AVATAR DO ADMIN LOGADO (topbar — visível em todas as telas,
            incluindo o Dashboard). Mesma lógica de fallback já usada no
            Painel do Usuário: se houver foto de perfil cadastrada, mostra a
            imagem; senão, mostra as iniciais ($iniciais, já calculado em
            server/auth.php). A variável $fotoPerfil precisa ser confirmada/
            adicionada em server/auth.php (ex.: $fotoPerfil = $admin['foto_perfil']
            ?? null;) — como o código usa empty(), nada quebra caso ainda não
            exista.
          -->
          <div class="avatar" id="topbarAvatarAdmin"<?= !empty($fotoPerfil) ? ' style="background-image:url(\'' . htmlspecialchars($fotoPerfil) . '\');"' : '' ?>><?= !empty($fotoPerfil) ? '' : $iniciais ?></div>

          <div class="user-menu" id="userMenu">
            <div class="user-menu-item" onclick="irParaTela('perfil')"><i class="bi bi-person"></i> Perfil</div>
            <div class="user-menu-item"><i class="bi bi-gear"></i> Configurações da conta</div>
            <div class="user-menu-divider"></div>
            <a href="server/logout.php" class="user-menu-item danger" style="text-decoration: none;"><i class="bi bi-box-arrow-right"></i> Sair</a>
          </div>
        </div>
      </div>
    </div>

    <div class="content">

      <!-- ================================================================ -->
      <!-- TELA: DASHBOARD                                                   -->
      <!-- ================================================================ -->
      <div class="screen active" id="screen-dashboard">
        <div class="dash-hero">
          <div class="dash-hero-text">
            <h2>Olá, <?= $firstName ?></h2>
            <p id="heroDate">Carregando data...</p>
          </div>
          <div class="dash-hero-actions">
            <button class="btn-hero" type="button" onclick="document.querySelector('[data-screen=usuarios]').click()">
              <i class="bi bi-people-fill"></i> Usuários
            </button>
            <button class="btn-hero" type="button" onclick="document.querySelector('[data-screen=anuncios]').click()">
              <i class="bi bi-megaphone-fill"></i> Anúncios
            </button>
            <button class="btn-hero" type="button" onclick="document.querySelector('[data-screen=planos]').click()">
              <i class="bi bi-credit-card-fill"></i> Planos
            </button>
          </div>
        </div>

        <p class="section-intro">Panorama geral do GunesAds: usuários, campanhas ativas e exibições registradas no sistema.</p>

        <!--
          ============================================================================
          CARDS DE MÉTRICA
          Todos os valores devem vir de contagens reais no banco (sem estimativas,
          exceto o campo já sinalizado como estimado):

            $metricas['usuarios_cadastrados']  -> COUNT(*) FROM usuarios
            $metricas['anuncios_ativos']       -> COUNT(*) FROM anuncios WHERE status = 'Ativo'
            $metricas['exibicoes_totais']      -> COUNT(*) FROM exibicoes (ou SUM de contador agregado)
            $metricas['anuncios_encerrados_mes']-> COUNT(*) FROM anuncios WHERE status = 'Encerrado'
                                                     AND MONTH(dataFim) = MONTH(CURRENT_DATE)
            $metricas['novos_cadastros_7dias'] -> COUNT(*) FROM usuarios
                                                     WHERE dataCadastro >= CURRENT_DATE - INTERVAL 7 DAY
            $metricas['faturamento_mes']       -> SUM(plano.preco [+ 39.90 se arte = true])
                                                     FROM anuncios WHERE MONTH(dataInicio) = MONTH(CURRENT_DATE)

          O card "Exibições totais estimadas" usa a classe extra ".featured" apenas
          para destaque visual (fundo escuro) — não muda a fonte do dado.
          ============================================================================
        -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-top"><div class="stat-icon blue"><i class="bi bi-people-fill"></i></div></div>
            <div class="stat-label">Usuários cadastrados</div>
            <div class="stat-value">86</div>
          </div>

          <div class="stat-card">
            <div class="stat-top"><div class="stat-icon green"><i class="bi bi-broadcast"></i></div></div>
            <div class="stat-label">Anúncios ativos</div>
            <div class="stat-value">54</div>
          </div>

          <div class="stat-card featured">
            <div class="stat-top"><div class="stat-icon cyan"><i class="bi bi-eye-fill"></i></div></div>
            <div class="stat-label">Exibições totais estimadas</div>
            <div class="stat-value">128.4K</div>
          </div>

          <div class="stat-card">
            <div class="stat-top"><div class="stat-icon red"><i class="bi bi-calendar-x-fill"></i></div></div>
            <div class="stat-label">Campanhas encerradas (mês)</div>
            <div class="stat-value">12</div>
          </div>

          <div class="stat-card">
            <div class="stat-top"><div class="stat-icon amber"><i class="bi bi-person-plus-fill"></i></div></div>
            <div class="stat-label">Novos cadastros (7 dias)</div>
            <div class="stat-value">6</div>
          </div>

          <div class="stat-card">
            <div class="stat-top"><div class="stat-icon blue"><i class="bi bi-cash-stack"></i></div></div>
            <div class="stat-label">Faturamento estimado do mês</div>
            <div class="stat-value">R$ 9.680</div>
          </div>
        </div>
      </div>

      <!-- ================================================================ -->
      <!-- TELA: USUÁRIOS                                                    -->
      <!-- ================================================================ -->
      <div class="screen" id="screen-usuarios">
        <p class="section-intro">Gerencie os usuários cadastrados no GunesAds. O cadastro é feito exclusivamente pelo administrador.</p>

        <div class="screen-toolbar">
          <div class="toolbar-count">Total: <b id="usersCount">0</b> usuários</div>
          <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="userSearchInput" placeholder="Buscar por nome ou e-mail...">
          </div>
          <button class="btn-new" type="button" id="btnNovoUsuario">
            <i class="bi bi-person-plus-fill"></i> Novo usuário
          </button>
        </div>
        
        <div class="users-list" id="usersList">

          <!--
            nivel        -> "Admin" ou "Usuário" (tipo de acesso ao sistema)
            foto_perfil  -> URL da foto de perfil (opcional); quando vazio, o
                             avatar mostra as iniciais do nome via iniciais().
            contrato_pdf -> URL do PDF do contrato assinado (opcional). Quando
                             vazio, o botão "Ver contrato" fica desabilitado.
          -->
          <?php foreach ($usuarios as $usuario): ?>
          <div
            class="user-row <?= $usuario["status"] ?>"
            data-id="<?= $usuario["id"] ?>"
            data-nome="<?= $usuario["nome"] ?>"
            data-email="<?= $usuario["email"] ?>"
            data-telefone="<?= formatTel($usuario["telefone"]) ?>"
            data-status="<?= ucfirst($usuario["status"]) ?>"
            data-nivel="<?= $usuario["nivel"] ?>"
            data-data-cadastro="<?= formatData($usuario["criado_em"]) ?>"
            data-observacoes="<?= $usuario["observacoes"] ?>"
            data-contrato="<?= htmlspecialchars($usuario["contrato_pdf"] ?? "") ?>"
          >
            <!-- Avatar: mostra a foto de perfil quando cadastrada, senão as iniciais. -->
            <div class="user-row-avatar"<?= !empty($usuario["foto_perfil"]) ? ' style="background-image:url(\'' . htmlspecialchars($usuario["foto_perfil"]) . '\');"' : '' ?>><?= !empty($usuario["foto_perfil"]) ? '' : iniciais($usuario["nome"]) ?></div>
            <div class="user-row-info">
              <div class="user-row-name"><?= $usuario["nome"] ?></div>
              <div class="user-row-sub">
                <span><i class="bi bi-envelope"></i><span class="sub-email"><?= $usuario["email"] ?></span></span>
                <span><i class="bi bi-telephone"></i><span class="sub-telefone"><?= formatTel($usuario["telefone"]) ?></span></span>
                <span><i class="bi bi-calendar3"></i> Desde <?= formatData($usuario["criado_em"]) ?></span>
              </div>
            </div>
            <span class="nivel-badge <?= $usuario["nivel"] === "Admin" ? "admin" : "usuario" ?>"><i class="bi bi-shield-fill-check"></i> <?= ucfirst($usuario["nivel"]) ?></span>
            <span class="status-badge <?= $usuario["status"] ?>"><i class="bi bi-check-circle-fill"></i> <?= ucfirst($usuario["status"]) ?></span>
            <div class="user-row-actions">
              <button class="row-act-btn" type="button" data-action="editar" title="Editar dados"><i class="bi bi-pencil"></i></button>
              <!-- "Ver contrato": abre o PDF numa nova aba. Fica desabilitado
                   (classe "disabled") quando o usuário não tem contrato. -->
              <button class="row-act-btn<?= empty($usuario["contrato_pdf"]) ? " disabled" : "" ?>" type="button" data-action="contrato" title="<?= empty($usuario["contrato_pdf"]) ? "Nenhum contrato enviado" : "Ver contrato" ?>"><i class="bi bi-file-earmark-pdf-fill"></i></button>
              <button class="row-act-btn toggle-off" type="button" data-action="status" title="Desativar"><i class="bi bi-slash-circle"></i></button>
              <button class="row-act-btn danger" type="button" data-action="excluir" title="Excluir"><i class="bi bi-trash"></i></button>
            </div>
          </div>
          <?php endforeach; ?>

        </div>

        <!-- Estado vazio específico da busca (nenhum resultado para o termo digitado) -->
        <div class="empty-list-state" id="emptyUsersSearch">
          <i class="bi bi-search"></i>
          <h2>Nenhum usuário encontrado</h2>
          <p>Tente buscar por outro nome ou e-mail.</p>
        </div>

        <div class="empty-list-state" id="emptyUsers">
          <i class="bi bi-people"></i>
          <h2>Nenhum usuário cadastrado</h2>
          <p>Cadastre o primeiro usuário para que ele possa acessar o painel e acompanhar seus anúncios.</p>
        </div>
      </div>

      <!-- ================================================================ -->
      <!-- TELA: ANÚNCIOS                                                    -->
      <!-- ================================================================ -->
      <div class="screen" id="screen-anuncios">
        <p class="section-intro">Cadastre e gerencie as campanhas de todos os usuários. Cada anúncio segue o plano contratado (tempo de exibição × duração).</p>

        <div class="screen-toolbar">
          <div class="toolbar-count">Total: <b id="adsCount">0</b> anúncios</div>
          <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="adSearchInput" placeholder="Buscar por anúncio ou usuário...">
          </div>
          <button class="btn-new" type="button" id="btnNovoAnuncio">
            <i class="bi bi-plus-circle-fill"></i> Novo anúncio
          </button>
        </div>

        <!-- Filtro por status: apenas exibe/oculta os cards já renderizados no DOM -->
        <div class="filter-chips" id="filterChips">
          <button class="filter-chip selected" type="button" data-filter="todos">Todos</button>
          <button class="filter-chip" type="button" data-filter="Ativo">Ativos</button>
          <button class="filter-chip" type="button" data-filter="Pausado">Pausados</button>
          <button class="filter-chip" type="button" data-filter="Encerrado">Encerrados</button>
        </div>

        <!--
          ============================================================================
          ANÚNCIOS
          Um card por registro da tabela "anuncios" (todos os usuários). Campos
          (viriam de $anuncio['campo'] no PHP):

            id                 -> id do anúncio no banco
            usuario_id         -> id do usuário vinculado
            usuarioNome        -> nome do usuário/anunciante (via JOIN com usuarios)
            titulo             -> nome do anúncio
            imagem             -> URL da arte enviada (se vazio, mostra fundo + ícone)
            cor                -> gradiente CSS de fallback (usado quando 'imagem' for
                                   vazio — mesma paleta usada no restante do sistema)
            plano_id           -> id do plano contratado (null quando 'institucional' = true)
            planoResumo        -> ex.: "10s · 30 dias" (via JOIN com planos), ou
                                   "10s · institucional" quando não há plano
            institucional      -> true/false — anúncio do próprio GunesAds, sem
                                   cliente vinculado. Quando true, usuario_id deve
                                   apontar automaticamente para o admin logado (ou
                                   um usuário "Sistema" dedicado — decisão de
                                   backend) e não há plano_id; tempoExibicao e
                                   dataFim são escolhidos livremente pelo admin.
            tempoExibicao      -> 10, 20 ou 30 (segundos) — só usado quando
                                   'institucional' = true (substitui o tempo que
                                   viria do plano)
            arte               -> true/false — se contratou o serviço de arte (+R$39,90)
            dataInicio         -> data de início da campanha
            dataFim            -> data de término (calculada: dataInicio + duracaoDias)
            status             -> 'Ativo', 'Pausado' ou 'Encerrado'
            exibicoesTotais    -> COUNT total de exibições (estimado, ver Dashboard)
            diasRestantes      -> DATEDIFF(dataFim, CURRENT_DATE), nunca negativo
            progressoPercentual-> percentual já decorrido da campanha, calculado no
                                   PHP como round((duracaoDias - diasRestantes) /
                                   duracaoDias * 100) — usado direto no "width" da
                                   barra de progresso (.progress-fill)
            observacoesInternas-> texto livre, visível só ao admin

          Guardei esses valores em atributos data-* no próprio card, pro
          JavaScript conseguir ler os dados direto do HTML já renderizado. Use
          htmlspecialchars() em título e observações.

          A busca (campo #adSearchInput) filtra os cards já renderizados no DOM
          por título do anúncio ou nome do usuário — não faz nenhuma consulta ao
          banco; é apenas comportamento de interface, combinável com o filtro de
          status já existente (#filterChips).

          Se não houver nenhum anúncio cadastrado, renderizar SOMENTE o bloco
          .empty-list-state (mais abaixo) e omitir o ads-grid.
          ============================================================================
        -->
        <div class="ads-grid" id="adsGrid">

          <!-- INÍCIO DO LOOP: repetir este bloco para cada $anuncio em $anuncios -->
          <?php foreach($anuncios as $anuncio): ?>
          <div
            class="ad-card"
            data-id="<?= $anuncio["id"] ?>"
            data-usuario-id="1"
            data-usuario-nome="<?= htmlspecialchars($anuncio["usuario_nome"]) ?>"
            data-titulo="Promoção de Verão"
            data-imagem=""
            data-cor="linear-gradient(135deg,#3E5EE0,#26399C)"
            data-plano-id="4"
            data-plano-resumo="10s · 30 dias"
            data-institucional="false"
            data-tempo-exibicao=""
            data-arte="false"
            data-data-inicio="2026-07-01"
            data-data-fim="2026-07-31"
            data-status="Ativo"
            data-exibicoes-totais="3480"
            data-dias-restantes="2"
            data-progresso="93"
            data-observacoes=""
          >
            <div class="ad-card-thumb" style="background-image: url(<?= $anuncio["imagem"] ?>);"></div>
            <div class="ad-card-body">
              <div class="ad-card-head">
                <div class="ad-card-title"><?= htmlspecialchars($anuncio["titulo"]) ?></div>
                <span class="badge <?= htmlspecialchars($anuncio["status"]) ?>"><?= htmlspecialchars(ucfirst($anuncio["status"])) ?></span>
              </div>
              <div class="ad-card-anunciante"><i class="bi bi-person"></i> <?= htmlspecialchars($anuncio["usuario_nome"]) ?></div>
              <div class="ad-card-meta">
                <span><i class="bi bi-stopwatch"></i> 10s · 30 dias</span>
                <span><i class="bi bi-calendar3"></i> <?= htmlspecialchars(formatData($anuncio["data_inicio"]) . " - " . formatData($anuncio["data_fim"])) ?></span>
              </div>  
              <div class="ad-card-metrics">
                <div class="ad-card-views"><i class="bi bi-eye-fill"></i> <?= htmlspecialchars($anuncio["exibicoes"]) ?></div>
                <div class="ad-card-progress-head">
                  <span>Campanha</span>
                  <span><b><?= diasRestantes($anuncio["data_fim"]) ?></b> dias restantes</span>
                </div>
                <div class="progress-track"><div class="progress-fill" style="width: 30%;"></div></div>
              </div>
              <div class="ad-card-actions">
                <button class="act-btn" type="button" data-action="editar" title="Editar"><i class="bi bi-pencil"></i></button>
                <button class="act-btn" type="button" data-action="pausar" title="Pausar"><i class="bi bi-pause-fill"></i></button>
                <button class="act-btn" type="button" data-action="encerrar" title="Encerrar"><i class="bi bi-flag-fill"></i></button>
                <button class="act-btn danger" type="button" data-action="excluir" title="Excluir"><i class="bi bi-trash"></i></button>
              </div>
            </div>
          </div>
          <?php endforeach; ?>

        </div>


        <div class="empty-list-state" id="emptyAds">
          <i class="bi bi-megaphone"></i>
          <h2>Nenhum anúncio cadastrado</h2>
          <p>Cadastre o primeiro anúncio vinculando-o a um usuário e a um plano.</p>
        </div>

        <!-- Estado vazio específico da busca (nenhum resultado para o termo digitado) -->
        <div class="empty-list-state" id="emptyAdsSearch">
          <i class="bi bi-search"></i>
          <h2>Nenhum anúncio encontrado</h2>
          <p>Tente buscar por outro título ou usuário, ou ajuste o filtro de status.</p>
        </div>
      </div>

      <!-- ================================================================ -->
      <!-- TELA: PLANOS                                                      -->
      <!-- ================================================================ -->
      <div class="screen" id="screen-planos">
        <p class="section-intro">Tabela fixa de planos (tempo de exibição × duração), definida na V1. Os preços podem ser ajustados pelo administrador.</p>

        <!--
          ============================================================================
          PLANOS
          Grade fixa de 9 combinações (3 níveis × 3 tempos de exibição),
          agrupadas por nível/duração para facilitar a leitura (em vez de por
          tempo de exibição, que deixava a grade confusa). Fonte: tabela
          "planos" no banco (id, tempoExibicao, duracaoDias, preco, descrição
          opcional). Os 9 registros já existem por padrão na V1; o administrador
          só edita o preço/descrição de cada um (sem criar ou remover planos).

          Nomenclatura dos níveis (fixa, apenas de exibição — não é uma coluna
          nova no banco, é derivada de duracaoDias):
            Básico       -> duracaoDias = 30
            Profissional -> duracaoDias = 60
            Premium      -> duracaoDias = 90

          Guardei tempoExibicao/duracaoDias/preco/descrição em atributos data-*
          em cada card, pro JavaScript conseguir ler os dados direto do HTML já
          renderizado ao abrir o modal de edição.

          Repita o bloco .plan-group para cada nível (Básico/Profissional/
          Premium), e dentro dele um foreach dos planos daquele nível,
          ordenados por tempoExibicao.
          ============================================================================
        -->

        <!-- INÍCIO DO LOOP EXTERNO (grupo por nível/duração) -->
        <div class="plan-group">
          <div class="plan-group-title"><i class="bi bi-award"></i> Básico <span class="plan-group-duracao">30 dias</span></div>
          <div class="plans-grid">
            <!-- INÍCIO DO LOOP INTERNO (planos deste nível, por tempo de exibição) -->
            <div class="plan-card" data-id="1" data-tempo="10" data-duracao="30" data-preco="79,90" data-descricao="Para começar a testar">
              <div class="plan-card-duracao">10 segundos</div>
              <div class="plan-card-preco">R$ 79,90</div>
              <div class="plan-card-desc">Para começar a testar</div>
              <div class="plan-card-edit">
                <button class="pend-btn" type="button" data-action="editar-plano"><i class="bi bi-pencil"></i> Editar preço</button>
              </div>
            </div>
            <div class="plan-card" data-id="4" data-tempo="20" data-duracao="30" data-preco="89,90" data-descricao="Mais tempo de tela">
              <div class="plan-card-duracao">20 segundos</div>
              <div class="plan-card-preco">R$ 89,90</div>
              <div class="plan-card-desc">Mais tempo de tela</div>
              <div class="plan-card-edit">
                <button class="pend-btn" type="button" data-action="editar-plano"><i class="bi bi-pencil"></i> Editar preço</button>
              </div>
            </div>
            <div class="plan-card" data-id="7" data-tempo="30" data-duracao="30" data-preco="109,90" data-descricao="Máxima exposição por ciclo">
              <div class="plan-card-duracao">30 segundos</div>
              <div class="plan-card-preco">R$ 109,90</div>
              <div class="plan-card-desc">Máxima exposição por ciclo</div>
              <div class="plan-card-edit">
                <button class="pend-btn" type="button" data-action="editar-plano"><i class="bi bi-pencil"></i> Editar preço</button>
              </div>
            </div>
            <!-- FIM DO LOOP INTERNO -->
          </div>
        </div>

        <div class="plan-group">
          <div class="plan-group-title"><i class="bi bi-award-fill"></i> Profissional <span class="plan-group-duracao">60 dias</span></div>
          <div class="plans-grid">
            <div class="plan-card" data-id="2" data-tempo="10" data-duracao="60" data-preco="129,90" data-descricao="Bom custo-benefício">
              <div class="plan-card-duracao">10 segundos</div>
              <div class="plan-card-preco">R$ 129,90</div>
              <div class="plan-card-desc">Bom custo-benefício</div>
              <div class="plan-card-edit">
                <button class="pend-btn" type="button" data-action="editar-plano"><i class="bi bi-pencil"></i> Editar preço</button>
              </div>
            </div>
            <div class="plan-card" data-id="5" data-tempo="20" data-duracao="60" data-preco="139,90" data-descricao="Para negócios em crescimento">
              <div class="plan-card-duracao">20 segundos</div>
              <div class="plan-card-preco">R$ 139,90</div>
              <div class="plan-card-desc">Para negócios em crescimento</div>
              <div class="plan-card-edit">
                <button class="pend-btn" type="button" data-action="editar-plano"><i class="bi bi-pencil"></i> Editar preço</button>
              </div>
            </div>
            <div class="plan-card" data-id="8" data-tempo="30" data-duracao="60" data-preco="199,90" data-descricao="Para campanhas de maior alcance">
              <div class="plan-card-duracao">30 segundos</div>
              <div class="plan-card-preco">R$ 199,90</div>
              <div class="plan-card-desc">Para campanhas de maior alcance</div>
              <div class="plan-card-edit">
                <button class="pend-btn" type="button" data-action="editar-plano"><i class="bi bi-pencil"></i> Editar preço</button>
              </div>
            </div>
          </div>
        </div>

        <div class="plan-group">
          <div class="plan-group-title"><i class="bi bi-gem"></i> Premium <span class="plan-group-duracao">90 dias</span></div>
          <div class="plans-grid">
            <div class="plan-card" data-id="3" data-tempo="10" data-duracao="90" data-preco="199,90" data-descricao="Maior economia no período">
              <div class="plan-card-duracao">10 segundos</div>
              <div class="plan-card-preco">R$ 199,90</div>
              <div class="plan-card-desc">Maior economia no período</div>
              <div class="plan-card-edit">
                <button class="pend-btn" type="button" data-action="editar-plano"><i class="bi bi-pencil"></i> Editar preço</button>
              </div>
            </div>
            <div class="plan-card" data-id="6" data-tempo="20" data-duracao="90" data-preco="219,90" data-descricao="Maior economia no período">
              <div class="plan-card-duracao">20 segundos</div>
              <div class="plan-card-preco">R$ 219,90</div>
              <div class="plan-card-desc">Maior economia no período</div>
              <div class="plan-card-edit">
                <button class="pend-btn" type="button" data-action="editar-plano"><i class="bi bi-pencil"></i> Editar preço</button>
              </div>
            </div>
            <div class="plan-card" data-id="9" data-tempo="30" data-duracao="90" data-preco="259,90" data-descricao="Máxima exposição no período">
              <div class="plan-card-duracao">30 segundos</div>
              <div class="plan-card-preco">R$ 259,90</div>
              <div class="plan-card-desc">Máxima exposição no período</div>
              <div class="plan-card-edit">
                <button class="pend-btn" type="button" data-action="editar-plano"><i class="bi bi-pencil"></i> Editar preço</button>
              </div>
            </div>
          </div>
        </div>
        <!-- FIM DO LOOP EXTERNO -->

        <!--
          ============================================================================
          SERVIÇO ADICIONAL — ARTE
          Não é um "plano" (não tem tempo/duração), é um serviço avulso que pode
          ser marcado por anúncio (ver checkbox "arte" no formulário de Anúncios).
          Fonte: tabela própria (ex.: "servicos_adicionais") ou constante de
          configuração — $servicoArte['preco'].
          ============================================================================
        -->
        <div class="plan-service-card" data-id="arte" data-preco="39,90">
          <div class="plan-service-info">
            <div class="plan-service-icon"><i class="bi bi-palette-fill"></i></div>
            <div>
              <h4>Serviço adicional: Criação de arte</h4>
              <p>Cobrado por campanha quando o usuário não envia a própria arte.</p>
            </div>
          </div>
          <div style="display:flex; align-items:center; gap:14px;">
            <div class="plan-service-price" id="artePrecoDisplay">R$ 39,90</div>
            <button class="pend-btn" type="button" id="btnEditarArte"><i class="bi bi-pencil"></i> Editar preço</button>
          </div>
        </div>
      </div>

      <!-- ================================================================ -->
      <!-- TELA: PERFIL (ADMINISTRADOR)                                      -->
      <!-- Acessível apenas pelo menu do usuário (topbar) — não fica na      -->
      <!-- sidebar. Diferente do painel do cliente, aqui o próprio           -->
      <!-- administrador pode editar todos os seus dados.                    -->
      <!-- ================================================================ -->
      <div class="screen" id="screen-perfil">
        <p class="section-intro">Seus dados de acesso ao GunesAds. Diferente do painel do cliente, aqui você pode editar todas as suas informações.</p>

        <!--
          ============================================================================
          HERO DO PERFIL (ADMIN)
          Mesma fonte de dados do nome/iniciais já usados na topbar ($firstName /
          $iniciais, vindos da sessão via server/auth.php). Quando o admin tiver
          foto de perfil cadastrada ($admin['fotoPerfil']), trocar a div de
          iniciais abaixo por uma versão com background-image, mesmo padrão já
          usado no Painel do Usuário.
          ============================================================================
        -->
        <div class="profile-hero" id="profileHeroAdmin">
          <div class="profile-avatar-wrap">
            <!-- Mesma lógica de fallback do avatar da topbar (ver comentário acima). -->
            <div class="profile-avatar-lg" id="profileAvatarPreviewAdmin"<?= !empty($fotoPerfil) ? ' style="background-image:url(\'' . htmlspecialchars($fotoPerfil) . '\');"' : '' ?>><?= !empty($fotoPerfil) ? '' : $iniciais ?></div>
            <div class="profile-avatar-edit" id="avatarEditBtnAdmin" title="Alterar foto">
              <i class="bi bi-camera-fill"></i>
            </div>
            <!-- O campo "avatar_image" (dentro do #avatarFormAdmin) chega em $_FILES. -->
            <input
              type="file"
              id="avatarFileAdmin"
              name="avatar_image"
              accept=".jpg,.jpeg,.png,image/jpeg,image/png"
              style="display:none"
            >
          </div>
          <div class="profile-hero-info">
            <div class="profile-hero-name"><?= $firstName . " " . $lastName ?></div>
            <div class="profile-hero-badges">
              <span class="info-badge"><i class="bi bi-shield-fill-check"></i> Administrador</span>
            </div>
          </div>
        </div>

        <!--
          ============================================================================
          DADOS PESSOAIS — EDITÁVEIS
          Diferente do Painel do Usuário (somente leitura), aqui o administrador
          edita seus próprios dados. Campos ainda não têm fonte confirmada em
          server/auth.php além de $firstName — por segurança, os valores abaixo
          ficam em branco com placeholder até essa origem ser definida.
          em cada input.
          ============================================================================
        -->
        <div class="profile-grid">
          <div class="profile-card">
            <div class="profile-card-title"><i class="bi bi-person-fill"></i> Dados pessoais</div>
            <p class="profile-card-desc">Essas informações identificam sua conta de administrador no sistema.</p>

            <!--
              Formulário pronto para integração com PHP:
                - method="post", action vazio (aponte para o script de atualização)
                - o preventDefault() no JS evita reload aqui no protótipo; remova-o
                  quando o formulário passar a enviar de verdade (ex.: action="server/perfil/atualizar.php")
            -->
            <form id="adminProfileForm" name="adminProfileForm" method="post" action="server/usuarios/update_perfil.php" autocomplete="off">
              <label for="adminNome">Nome completo</label>
              <input type="text" id="adminNome" name="nome" value="<?= htmlspecialchars($fullName) ?>" placeholder="Seu nome completo">

              <label for="adminEmail">E-mail</label>
              <input type="email" id="adminEmail" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="voce@gunesads.com">

              <label for="adminTelefone">Telefone</label>
              <input type="tel" id="adminTelefone" name="telefone" value="<?= htmlspecialchars(formatTel($telefone)) ?>" placeholder="(99) 99999-9999">

              <label for="adminCargo">Cargo</label>
              <input type="text" id="adminCargo" name="cargo" value="<?= htmlspecialchars(ucfirst($nivel)) ?>" placeholder="Ex: Administrador">

              <div class="form-actions">
                <button class="btn-primary" type="submit">Salvar alterações</button>
              </div>
            </form>
          </div>

          <!-- Segurança / alterar senha -->
          <div class="profile-card">
            <div class="profile-card-title"><i class="bi bi-shield-lock-fill"></i> Segurança</div>
            <p class="profile-card-desc">Altere sua senha de acesso periodicamente.</p>

            <!--
              Formulário pronto para integração com PHP:
                - envia apenas os 3 campos de senha
                - o preventDefault() no JS evita reload aqui no protótipo; remova-o
                  quando o formulário passar a enviar de verdade (ex.: action="server/perfil/alterar_senha.php")
            -->
            <form
              id="adminChangePasswordForm"
              name="adminChangePasswordForm"
              method="post"
              action=""
              autocomplete="off"
            >
              <label for="adminCurrentPassword">Senha atual</label>
              <input
                type="password"
                id="adminCurrentPassword"
                name="current_password"
                autocomplete="current-password"
                placeholder="Digite sua senha atual"
              >

              <label for="adminNewPassword">Nova senha</label>
              <input
                type="password"
                id="adminNewPassword"
                name="new_password"
                autocomplete="new-password"
                placeholder="Mínimo de 8 caracteres"
              >

              <label for="adminConfirmPassword">Confirmar nova senha</label>
              <input
                type="password"
                id="adminConfirmPassword"
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
  </div>

  <!-- ======================= MODAL: CADASTRAR / EDITAR USUÁRIO ======================= -->
  <div class="modal-overlay" id="modalUsuario">
    <div class="modal-box">
      <div class="modal-head">
        <div>
          <h3 id="userModalTitulo">Novo usuário</h3>
          <div class="modal-sub">Dados de acesso e contato</div>
        </div>
        <button class="modal-close" type="button" data-close="modalUsuario"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <form method="post" action="server/usuarios/create_update.php" id="userForm" autocomplete="off" enctype="multipart/form-data">
          <input type="hidden" name="id" id="userId" value="">
          <!-- URL do contrato já salvo (edição); vazio em cadastro novo ou
               quando o usuário ainda não tem contrato. Só é sobrescrito no
               backend se um novo arquivo for enviado em "contrato_pdf". -->
          <input type="hidden" name="contrato_atual" id="userContratoAtual" value="">

          <label for="userNome">Nome completo</label>
          <input type="text" name="nome" id="userNome" placeholder="Ex: Arthur J. Lima">
          <div class="field-error" id="errUserNome">Informe o nome completo.</div>

          <label for="userEmail">E-mail</label>
          <input type="email" name="email" id="userEmail" placeholder="voce@exemplo.com">
          <div class="field-error" id="errUserEmail">Informe um e-mail válido.</div>

          <label for="userTelefone">Telefone</label>
          <input type="tel" name="telefone" id="userTelefone" placeholder="(99) 99999-9999">

          <label for="userNivel">Nível de acesso</label>
          <select name="nivel" id="userNivel">
            <option value="user">Usuário</option>
            <option value="admin">Administrador</option>
          </select>

          <!--
            CONTRATO (PDF) — OPCIONAL
            Campo de upload do contrato assinado do usuário. Somente PDF.
            No PHP real, chega em $_FILES['contrato_pdf'] quando um novo
            arquivo é selecionado; se vazio, mantém o valor de
            "contrato_atual" (URL já salva no banco).
          -->
          <label for="userContratoFile">Contrato (PDF)</label>
          <div class="upload-zone" id="userContratoUploadZone">
            <span class="upicon"><i class="bi bi-file-earmark-pdf"></i></span>
            <div id="userContratoUploadLabel">Clique para enviar o contrato (PDF)</div>
            <input type="file" name="contrato_pdf" id="userContratoFile" accept=".pdf,application/pdf" style="display:none">
          </div>

          <label for="userObservacoes">Observações internas (opcional)</label>
          <textarea name="observacoes" id="userObservacoes" placeholder="Visível apenas para o administrador"></textarea>
        </form>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalUsuario">Cancelar</button>
        <button class="modal-btn primary" type="submit" id="btnSalvarUsuario" form="userForm">Salvar usuário</button>
      </div>
    </div>
  </div>

  <!-- ======================= MODAL: CONFIRMAR DESATIVAÇÃO/REATIVAÇÃO DE USUÁRIO ======================= -->
  <div class="modal-overlay" id="modalStatusUsuario">
    <div class="modal-box">
      <div class="modal-head">
        <div><h3 id="statusModalTitulo">Desativar usuário?</h3></div>
        <button class="modal-close" type="button" data-close="modalStatusUsuario"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <div class="modal-warning-box" id="statusModalTexto">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <span>O usuário perderá o acesso ao painel até ser reativado.</span>
        </div>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalStatusUsuario">Cancelar</button>
        <button class="modal-btn danger" type="button" id="btnConfirmarStatusUsuario">Confirmar</button>
      </div>
    </div>
  </div>

  <!-- ======================= MODAL: CONFIRMAR EXCLUSÃO DE USUÁRIO ======================= -->
  <div class="modal-overlay" id="modalExcluirUsuario">
    <div class="modal-box">
      <div class="modal-head">
        <div><h3>Excluir usuário?</h3></div>
        <button class="modal-close" type="button" data-close="modalExcluirUsuario"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <div class="modal-warning-box">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <span>O usuário <b id="excluirUsuarioNome">este usuário</b> será removido permanentemente, junto de todo o histórico associado a ele.</span>
        </div>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalExcluirUsuario">Cancelar</button>
        <a href="#" class="modal-btn danger" type="button" id="btnConfirmarExcluirUsuario" style="text-decoration: none;">Sim, excluir</a>
      </div>
    </div>
  </div>

  <!-- ======================= MODAL: CADASTRAR / EDITAR ANÚNCIO ======================= -->
  <div class="modal-overlay" id="modalAnuncio">
    <div class="modal-box wide">
      <div class="modal-head">
        <div>
          <h3 id="anuncioModalTitulo">Novo anúncio</h3>
          <div class="modal-sub">Vincule a um usuário e a um plano</div>
        </div>
        <button class="modal-close" type="button" data-close="modalAnuncio"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <form action="server/anuncios/create_update.php" method="post" class="form-grid" id="anuncioForm" autocomplete="off" enctype="multipart/form-data">
          <div>
            <input type="hidden" id="anuncioId" value="">
            <input type="hidden" id="anuncioImagemUrl" value="">

            <!--
              ANÚNCIO INSTITUCIONAL (GunesAds, sem cliente vinculado)
              Quando marcado, o anúncio não pertence a um usuário anunciante:
              é uma campanha do próprio GunesAds (ex.: "Anuncie aqui"). O
              modelo de dados continua exigindo um usuario_id (ver Documento
              Mestre), então o backend deve vincular automaticamente esse
              anúncio ao administrador logado (ou a um usuário "Sistema"
              dedicado — decisão de implementação do backend), sem exigir
              seleção manual aqui. Também não há plano contratado: o próprio
              admin escolhe o tempo de exibição e a data de término.
            -->
            <div class="checkbox-row" id="anuncioInstitucionalRow" style="margin-top:0;">
              <input type="checkbox" name="institucional" id="anuncioInstitucional">
              <label for="anuncioInstitucional" style="margin:0;">Anúncio institucional (GunesAds, sem cliente vinculado)</label>
            </div>

            <div id="anuncioUsuarioGroup">
              <label for="anuncioUsuario">Usuário</label>
              <!--
                As <option> abaixo são um exemplo estático. No PHP, gerar via
                foreach ($usuarios as $usuario), com value="<?= $usuario['id'] ?>".
              -->
              <select id="anuncioUsuario">
                <option value="">Selecione um usuário</option>
                <?php foreach($usuarios as $usuario): ?>
                <option value="<?= $usuario["id"] ?>"><?= $usuario["nome"] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="field-error" id="errAnuncioUsuario">Selecione o usuário anunciante.</div>
            </div>

            <label for="anuncioTitulo">Título do anúncio</label>
            <input type="text" name="titulo" id="anuncioTitulo" placeholder="Ex: Promoção de Verão">
            <div class="field-error" id="errAnuncioTitulo">Informe o título do anúncio.</div>

            <div class="upload-zone-preview" id="anuncioImgPreview"></div>
            <div class="upload-zone" id="anuncioUploadZone">
              <span class="upicon"><i class="bi bi-cloud-arrow-up"></i></span>
              <div id="anuncioUploadLabel">Clique para enviar a imagem (JPEG, JPG ou PNG)</div>
              <input type="file" name="ad-file" id="anuncioFile" accept=".jpg,.jpeg,.png,image/jpeg,image/png" style="display:none">
            </div>

            <div id="anuncioPlanoGroup">
              <label for="anuncioPlano">Plano (tempo × duração)</label>
              <!--
                As <option> abaixo são um exemplo estático. No PHP, gerar via
                foreach ($planos as $plano), com value= e
                data-duracao= data-preco=.
              -->
              <select id="anuncioPlano">
                <option value="">Selecione um plano</option>
                <option value="1" data-duracao="30" data-preco="79,90">10s · 30 dias — R$ 79,90</option>
                <option value="2" data-duracao="60" data-preco="129,90">10s · 60 dias — R$ 129,90</option>
                <option value="3" data-duracao="90" data-preco="199,90">10s · 90 dias — R$ 199,90</option>
                <option value="4" data-duracao="30" data-preco="89,90">20s · 30 dias — R$ 89,90</option>
                <option value="5" data-duracao="60" data-preco="139,90">20s · 60 dias — R$ 139,90</option>
                <option value="6" data-duracao="90" data-preco="219,90">20s · 90 dias — R$ 219,90</option>
                <option value="7" data-duracao="30" data-preco="109,90">30s · 30 dias — R$ 109,90</option>
                <option value="8" data-duracao="60" data-preco="199,90">30s · 60 dias — R$ 199,90</option>
                <option value="9" data-duracao="90" data-preco="259,90">30s · 90 dias — R$ 259,90</option>
              </select>
              <div class="field-error" id="errAnuncioPlano">Selecione o plano contratado.</div>
            </div>

            <!--
              Campos exclusivos do modo institucional: substituem a seleção de
              plano quando não há cliente vinculado. O admin escolhe o tempo
              de exibição livremente e a data de término manualmente (abaixo,
              o campo #anuncioDataFim deixa de ser readonly nesse modo).
            -->
            <div id="anuncioInstitucionalFields" style="display:none;">
              <label for="anuncioTempoExibicao">Tempo de exibição</label>
              <select name="duracao" id="anuncioTempoExibicao">
                <option value="">Selecione o tempo</option>
                <option value="10">10 segundos</option>
                <option value="20">20 segundos</option>
                <option value="30">30 segundos</option>
              </select>
              <div class="field-error" id="errAnuncioTempoExibicao">Selecione o tempo de exibição.</div>
            </div>

            <div class="checkbox-row">
              <input type="checkbox" id="anuncioArte">
              <label for="anuncioArte" style="margin:0;">Contratar serviço de arte (+R$ 39,90)</label>
            </div>

            <div class="modal-grid-2">
              <div>
                <label for="anuncioDataInicio">Data de início</label>
                <input type="date" name="data-inicio" id="anuncioDataInicio">
              </div>
              <div>
                <label for="anuncioDataFim">Data de término</label>
                <input type="date" name="data-fim" id="anuncioDataFim" readonly>
              </div>
            </div>
            <div class="form-hint" id="anuncioDataFimHint">A data de término é calculada automaticamente a partir do plano selecionado.</div>

            <label for="anuncioStatus">Status inicial</label>
            <select name="status-inicial" id="anuncioStatus">
              <option value="Ativo">Ativo</option>
              <option value="Pausado">Pausado</option>
            </select>

            <label for="anuncioObservacoes">Observações internas (opcional)</label>
            <textarea name="observacoes" id="anuncioObservacoes" placeholder="Visível apenas para o administrador"></textarea>
          </div>

          <div>
            <label style="margin-top:0">Prévia do card</label>
            <div class="preview-card">
              <div class="ph" id="anuncioPcPreview">imagem do anúncio</div>
              <div class="pc-body">
                <div class="pc-title" id="anuncioPcTitulo">Título do anúncio</div>
                <div class="pc-desc" id="anuncioPcDesc">Selecione o plano</div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalAnuncio">Cancelar</button>
        <button class="modal-btn primary" type="submit" id="btnSalvarAnuncio" form="anuncioForm">Salvar anúncio</button>
      </div>
    </div>
  </div>

  <!-- ======================= MODAL: CONFIRMAR ENCERRAMENTO DE ANÚNCIO ======================= -->
  <div class="modal-overlay" id="modalEncerrarAnuncio">
    <div class="modal-box">
      <div class="modal-head">
        <div><h3>Encerrar anúncio?</h3></div>
        <button class="modal-close" type="button" data-close="modalEncerrarAnuncio"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <div class="modal-warning-box">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <span>O anúncio <b id="encerrarAnuncioNome">este anúncio</b> deixará de rodar nos tablets imediatamente e passará para o status "Encerrado".</span>
        </div>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalEncerrarAnuncio">Cancelar</button>
        <button class="modal-btn danger" type="button" id="btnConfirmarEncerrarAnuncio">Sim, encerrar</button>
      </div>
    </div>
  </div>

  <!-- ======================= MODAL: CONFIRMAR EXCLUSÃO DE ANÚNCIO ======================= -->
  <div class="modal-overlay" id="modalExcluirAnuncio">
    <div class="modal-box">
      <div class="modal-head">
        <div><h3>Excluir anúncio?</h3></div>
        <button class="modal-close" type="button" data-close="modalExcluirAnuncio"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <div class="modal-warning-box">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <span>O anúncio <b id="excluirAnuncioNome">este anúncio</b> será removido permanentemente, junto do histórico de exibições associado.</span>
        </div>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalExcluirAnuncio">Cancelar</button>
        <button class="modal-btn danger" type="button" id="btnConfirmarExcluirAnuncio">Sim, excluir</button>
      </div>
    </div>
  </div>

  <!-- ======================= MODAL: EDITAR PREÇO DO PLANO ======================= -->
  <div class="modal-overlay" id="modalEditarPlano">
    <div class="modal-box">
      <div class="modal-head">
        <div>
          <h3 id="planoModalTitulo">Editar plano</h3>
          <div class="modal-sub">Ajuste o preço e a descrição</div>
        </div>
        <button class="modal-close" type="button" data-close="modalEditarPlano"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <form id="planoForm" autocomplete="off">
          <input type="hidden" id="planoId" value="">

          <label for="planoPreco" style="margin-top:0;">Preço (R$)</label>
          <input type="text" id="planoPreco" placeholder="Ex: 119,90">
          <div class="field-error" id="errPlanoPreco">Informe um preço válido.</div>

          <label for="planoDescricao">Descrição curta (opcional)</label>
          <input type="text" id="planoDescricao" placeholder="Ex: Para negócios em crescimento">
        </form>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalEditarPlano">Cancelar</button>
        <button class="modal-btn primary" type="button" id="btnSalvarPlano">Salvar</button>
      </div>
    </div>
  </div>

  <script>
    /* ========================================================================
       GUNESADS — PAINEL ADMINISTRATIVO — LÓGICA (V1)
       Sumário:
         1. Navegação entre telas
         2. Sidebar responsiva
         3. Menu dropdown do usuário
         4. Sistema de modais (genérico)
         5. Usuários — busca + CRUD completo (editar, ativar/desativar, excluir)
         6. Anúncios — busca + filtro por status + CRUD + pausar/retomar/encerrar/renovar
         7. Planos — edição de preço/descrição (planos fixos + serviço de arte)
         8. Sistema global de alertas (Toasts)
         9. Data de boas-vindas no dashboard
         10. Perfil administrativo — dados editáveis + alterar senha + avatar

       Nenhuma função abaixo simula persistência de dados: cada ação de
       cadastro/edição/exclusão apenas atualiza a UI localmente para fins de
       protótipo. No PHP real, cada uma vira um <form>/AJAX que executa a
       operação real no banco (INSERT/UPDATE/DELETE) e recarrega os dados —
       ver comentários específicos em cada função abaixo.
       ==================================================================== */

    /* ---- 1. Navegação entre telas -------------------------------------- */
    const screenTitles = {
      dashboard: 'Dashboard',
      usuarios: 'Usuários',
      anuncios: 'Anúncios',
      planos: 'Planos',
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

    /* ---- 4. Sistema de modais (genérico) ----------------------------------- */
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

    /* ========================================================================
       5. USUÁRIOS
       ==================================================================== */
    const usersList = document.getElementById('usersList');
    const usersCount = document.getElementById('usersCount');
    const emptyUsers = document.getElementById('emptyUsers');
    const emptyUsersSearch = document.getElementById('emptyUsersSearch');
    const userSearchInput = document.getElementById('userSearchInput');

    function iniciais(nomeCompleto) {
      const partes = nomeCompleto.trim().split(/\s+/);
      const primeira = partes[0]?.[0] || '';
      const ultima = partes.length > 1 ? partes[partes.length - 1][0] : '';
      return (primeira + ultima).toUpperCase();
    }

    function atualizarContadorUsuarios() {
      const total = usersList.querySelectorAll('.user-row').length;
      usersCount.textContent = total;
      emptyUsers.classList.toggle('show', total === 0);
    }
    atualizarContadorUsuarios();

    /* Busca: filtra as linhas já renderizadas no DOM por nome ou e-mail,
       sem nenhuma consulta ao banco — apenas comportamento de interface. */
    function filtrarUsuarios() {
      const termo = userSearchInput.value.trim().toLowerCase();
      const linhas = usersList.querySelectorAll('.user-row');
      let visiveis = 0;

      linhas.forEach((row) => {
        const nome = row.dataset.nome.toLowerCase();
        const email = row.dataset.email.toLowerCase();
        const corresponde = !termo || nome.includes(termo) || email.includes(termo);
        row.style.display = corresponde ? '' : 'none';
        if (corresponde) visiveis += 1;
      });

      emptyUsersSearch.classList.toggle('show', termo !== '' && visiveis === 0 && linhas.length > 0);
    }

    userSearchInput.addEventListener('input', filtrarUsuarios);

    function getUserRow(id) {
      return usersList.querySelector(`.user-row[data-id="${id}"]`);
    }

    const userModalTitulo = document.getElementById('userModalTitulo');
    const userForm = document.getElementById('userForm');

    /* ---- Upload do contrato (PDF) do usuário ------------------------------
       Sem prévia visual (é um PDF, não imagem): apenas troca o texto da zona
       de upload pelo nome do arquivo escolhido. No PHP real, o arquivo chega
       em $_FILES['contrato_pdf'] (form já está com enctype multipart). --- */
    const userContratoFile = document.getElementById('userContratoFile');
    const userContratoUploadZone = document.getElementById('userContratoUploadZone');
    const userContratoUploadLabel = document.getElementById('userContratoUploadLabel');

    userContratoUploadZone.addEventListener('click', () => userContratoFile.click());

    userContratoFile.addEventListener('change', () => {
      const file = userContratoFile.files[0];
      if (!file) return;

      if (file.type !== 'application/pdf') {
        userContratoUploadLabel.textContent = 'Formato inválido. Envie apenas PDF.';
        userContratoUploadLabel.style.color = 'var(--danger)';
        userContratoFile.value = '';
        return;
      }

      userContratoUploadLabel.style.color = '';
      userContratoUploadLabel.textContent = file.name;
    });

    function abrirModalUsuario(modo, id) {
      userForm.reset();
      document.getElementById('errUserNome').classList.remove('show');
      document.getElementById('errUserEmail').classList.remove('show');
      userContratoUploadLabel.style.color = '';

      document.getElementById('userContratoAtual').value = '';
      userContratoUploadLabel.textContent = 'Clique para enviar o contrato (PDF)';

      if (modo === 'novo') {
        userModalTitulo.textContent = 'Novo usuário';
        document.getElementById('userId').value = '';
        document.getElementById('userNivel').value = 'Usuário';
      } else {
        const row = getUserRow(id);
        if (!row) return;
        userModalTitulo.textContent = 'Editar usuário';
        document.getElementById('userId').value = id;
        document.getElementById('userNome').value = row.dataset.nome;
        document.getElementById('userEmail').value = row.dataset.email;
        document.getElementById('userTelefone').value = row.dataset.telefone;
        document.getElementById('userNivel').value = row.dataset.nivel || 'Usuário';
        document.getElementById('userObservacoes').value = row.dataset.observacoes;

        if (row.dataset.contrato) {
          document.getElementById('userContratoAtual').value = row.dataset.contrato;
          userContratoUploadLabel.textContent = 'Contrato já enviado — clique para substituir';
        }
      }

      abrirModal('modalUsuario');
    }

    document.getElementById('btnNovoUsuario').addEventListener('click', () => abrirModalUsuario('novo'));

    document.getElementById('btnSalvarUsuario').addEventListener('click', () => {
      const id = document.getElementById('userId').value;
      const nome = document.getElementById('userNome').value.trim();
      const email = document.getElementById('userEmail').value.trim();
      const telefone = document.getElementById('userTelefone').value.trim();
      const nivel = document.getElementById('userNivel').value;
      const observacoes = document.getElementById('userObservacoes').value.trim();

      const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      document.getElementById('errUserNome').classList.toggle('show', !nome);
      document.getElementById('errUserEmail').classList.toggle('show', !emailValido);
      if (!nome || !emailValido) return;

      if (id) {
        // EDIÇÃO: a linha já existe na tela — atualizamos ela diretamente,
        // só pra dar o feedback visual aqui no protótipo.
        //
        // No PHP real: este botão vira um <form method="post"
        // action="editar_usuario.php"> enviando o "id" num campo hidden. O
        // PHP faz o UPDATE no banco e a página recarrega — a linha já
        // aparece atualizada, vinda do foreach.
        const row = getUserRow(id);
        if (row) {
          row.dataset.nome = nome;
          row.dataset.email = email;
          row.dataset.telefone = telefone;
          row.dataset.nivel = nivel;
          row.dataset.observacoes = observacoes;

          row.querySelector('.user-row-avatar').textContent = iniciais(nome);
          row.querySelector('.user-row-name').textContent = nome;
          row.querySelector('.sub-email').textContent = email;
          row.querySelector('.sub-telefone').textContent = telefone;

          const nivelBadge = row.querySelector('.nivel-badge');
          nivelBadge.className = `nivel-badge ${nivel === 'Admin' ? 'admin' : 'usuario'}`;
          nivelBadge.innerHTML = `<i class="bi bi-shield-fill-check"></i> ${nivel}`;

          // Contrato: se um novo PDF foi selecionado, atualizamos o botão
          // "Ver contrato" localmente (só feedback visual do protótipo). No
          // PHP real, a URL definitiva vem de $_FILES['contrato_pdf'] salvo
          // no servidor, refletida aqui após o reload da página.
          if (userContratoFile.files[0]) {
            row.dataset.contrato = URL.createObjectURL(userContratoFile.files[0]);
            const btnContrato = row.querySelector('[data-action="contrato"]');
            btnContrato.classList.remove('disabled');
            btnContrato.title = 'Ver contrato';
          }
        }
        mostrarToast('Usuário atualizado', `Os dados de ${nome} foram salvos`, 'sucesso');
      } else {
        // CADASTRO NOVO: de propósito, NÃO criamos uma linha nova via JS
        // (isso seria o JS "inventando" HTML).
        //
        // No PHP real: este botão vira um <form method="post"
        // action="criar_usuario.php">. O PHP gera a senha inicial, faz o
        // INSERT no banco e redireciona de volta pra esta página — o novo
        // usuário já aparece na lista porque vem do foreach.
        mostrarToast('Usuário pronto para salvar', `${nome} vai aparecer na lista assim que o formulário for enviado ao servidor`, 'sucesso');
      }

      fecharModal('modalUsuario');
    });

    /* ---- Ativar/desativar usuário ------------------------------------------ */
    let usuarioStatusSelecionadoId = null;

    function abrirConfirmarStatusUsuario(id) {
      const row = getUserRow(id);
      if (!row) return;
      usuarioStatusSelecionadoId = id;

      const vaiDesativar = row.dataset.status === 'Ativo';
      document.getElementById('statusModalTitulo').textContent = vaiDesativar ? 'Desativar usuário?' : 'Reativar usuário?';
      document.getElementById('statusModalTexto').innerHTML = vaiDesativar
        ? `<i class="bi bi-exclamation-triangle-fill"></i><span><b>${row.dataset.nome}</b> perderá o acesso ao painel até ser reativado.</span>`
        : `<i class="bi bi-info-circle-fill"></i><span><b>${row.dataset.nome}</b> voltará a ter acesso normal ao painel.</span>`;

      abrirModal('modalStatusUsuario');
    }

    document.getElementById('btnConfirmarStatusUsuario').addEventListener('click', () => {
      window.location.href = "server/usuarios/status.php?id=" + usuarioStatusSelecionadoId;
    });

    /* ---- Excluir usuário ---------------------------------------------------- */
    let usuarioExcluirSelecionadoId = null;

    function abrirConfirmarExcluirUsuario(id) {
      const row = getUserRow(id);
      if (!row) return;
      usuarioExcluirSelecionadoId = id;
      document.getElementById('excluirUsuarioNome').textContent = `"${row.dataset.nome}"`;
      abrirModal('modalExcluirUsuario');
    }

    document.getElementById('btnConfirmarExcluirUsuario').addEventListener('click', () => {
      window.location.href = `server/usuarios/delete.php?id=${usuarioExcluirSelecionadoId}`;
    });

    // Delegação de eventos da lista de usuários
    usersList.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-action]');
      if (!btn) return;
      const id = btn.closest('.user-row').dataset.id;

      if (btn.dataset.action === 'editar') abrirModalUsuario('editar', id);
      if (btn.dataset.action === 'contrato') abrirContratoUsuario(id);
      if (btn.dataset.action === 'status') abrirConfirmarStatusUsuario(id);
      if (btn.dataset.action === 'excluir') abrirConfirmarExcluirUsuario(id);
    });

    // "Ver contrato": abre o PDF numa nova aba. Sem ação se não houver
    // contrato cadastrado (botão fica com classe "disabled" nesse caso).
    function abrirContratoUsuario(id) {
      const row = getUserRow(id);
      if (!row || !row.dataset.contrato) return;
      window.open(row.dataset.contrato, '_blank', 'noopener');
    }

    /* ========================================================================
       6. ANÚNCIOS
       ==================================================================== */
    const adsGrid = document.getElementById('adsGrid');
    const adsCount = document.getElementById('adsCount');
    const emptyAds = document.getElementById('emptyAds');
    const emptyAdsSearch = document.getElementById('emptyAdsSearch');
    const filterChips = document.getElementById('filterChips');
    const adSearchInput = document.getElementById('adSearchInput');

    function atualizarContadorAnuncios() {
      const total = adsGrid.querySelectorAll('.ad-card').length;
      adsCount.textContent = total;
      emptyAds.classList.toggle('show', total === 0);
    }
    atualizarContadorAnuncios();

    function getAdCard(id) {
      return adsGrid.querySelector(`.ad-card[data-id="${id}"]`);
    }

    /* ---- Filtro por status + busca: combinados sobre os cards já
       renderizados no DOM, sem nenhuma consulta ao banco — apenas
       comportamento de interface. -------------------------------------- */
    function aplicarFiltrosAnuncios() {
      const filtro = filterChips.querySelector('.filter-chip.selected')?.dataset.filter || 'todos';
      const termo = adSearchInput.value.trim().toLowerCase();
      let visiveis = 0;

      adsGrid.querySelectorAll('.ad-card').forEach((card) => {
        const statusOk = filtro === 'todos' || card.dataset.status === filtro;
        const titulo = card.dataset.titulo.toLowerCase();
        const usuarioNome = card.dataset.usuarioNome.toLowerCase();
        const buscaOk = !termo || titulo.includes(termo) || usuarioNome.includes(termo);
        const mostrar = statusOk && buscaOk;

        card.style.display = mostrar ? '' : 'none';
        if (mostrar) visiveis += 1;
      });

      const totalCards = adsGrid.querySelectorAll('.ad-card').length;
      emptyAdsSearch.classList.toggle('show', totalCards > 0 && visiveis === 0);
    }

    filterChips.addEventListener('click', (e) => {
      const chip = e.target.closest('.filter-chip');
      if (!chip) return;

      filterChips.querySelectorAll('.filter-chip').forEach((c) => c.classList.remove('selected'));
      chip.classList.add('selected');

      aplicarFiltrosAnuncios();
    });

    adSearchInput.addEventListener('input', aplicarFiltrosAnuncios);

    /* ---- Upload de imagem + prévia do card ---------------------------------- */
    const anuncioFile = document.getElementById('anuncioFile');
    const anuncioUploadZone = document.getElementById('anuncioUploadZone');
    const anuncioUploadLabel = document.getElementById('anuncioUploadLabel');
    const anuncioImgPreview = document.getElementById('anuncioImgPreview');
    const anuncioImagemUrl = document.getElementById('anuncioImagemUrl');
    const anuncioPcPreview = document.getElementById('anuncioPcPreview');

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
      anuncioImagemUrl.value = objectUrl;
      anuncioImgPreview.style.backgroundImage = `url('${objectUrl}')`;
      anuncioImgPreview.classList.add('show');
      anuncioPcPreview.style.backgroundImage = `url('${objectUrl}')`;
      anuncioPcPreview.innerText = '';
    });

    // Título: atualiza a prévia do card ao vivo
    const anuncioTitulo = document.getElementById('anuncioTitulo');
    const anuncioPcTitulo = document.getElementById('anuncioPcTitulo');

    anuncioTitulo.addEventListener('input', () => {
      anuncioPcTitulo.textContent = anuncioTitulo.value.trim() || 'Título do anúncio';
    });

    // Plano: calcula a data de término automaticamente a partir da data de
    // início + duração do plano selecionado, e atualiza a prévia do card.
    const anuncioPlano = document.getElementById('anuncioPlano');
    const anuncioDataInicio = document.getElementById('anuncioDataInicio');
    const anuncioDataFim = document.getElementById('anuncioDataFim');
    const anuncioPcDesc = document.getElementById('anuncioPcDesc');

    function recalcularDataFim() {
      const opcao = anuncioPlano.options[anuncioPlano.selectedIndex];
      const duracao = Number(opcao?.dataset.duracao || 0);

      if (!duracao || !anuncioDataInicio.value) {
        anuncioDataFim.value = '';
        return;
      }

      const inicio = new Date(`${anuncioDataInicio.value}T00:00:00`);
      const fim = new Date(inicio);
      fim.setDate(fim.getDate() + duracao);
      anuncioDataFim.value = fim.toISOString().slice(0, 10);
    }

    function atualizarPrevisaoPlano() {
      const opcao = anuncioPlano.options[anuncioPlano.selectedIndex];
      anuncioPcDesc.textContent = opcao && opcao.value ? opcao.textContent : 'Selecione o plano';
      recalcularDataFim();
    }

    anuncioPlano.addEventListener('change', atualizarPrevisaoPlano);
    anuncioDataInicio.addEventListener('change', recalcularDataFim);

    // Anúncio institucional: alterna entre o par Usuário+Plano (anúncio de
    // cliente) e o par Tempo de exibição+Data de término manual (anúncio do
    // próprio GunesAds, sem cliente vinculado — ver comentário no HTML).
    const anuncioInstitucional = document.getElementById('anuncioInstitucional');
    const anuncioUsuarioGroup = document.getElementById('anuncioUsuarioGroup');
    const anuncioPlanoGroup = document.getElementById('anuncioPlanoGroup');
    const anuncioInstitucionalFields = document.getElementById('anuncioInstitucionalFields');
    const anuncioTempoExibicao = document.getElementById('anuncioTempoExibicao');
    const anuncioDataFimHint = document.getElementById('anuncioDataFimHint');

    function aplicarModoInstitucional() {
      const institucional = anuncioInstitucional.checked;

      anuncioUsuarioGroup.style.display = institucional ? 'none' : '';
      anuncioPlanoGroup.style.display = institucional ? 'none' : '';
      anuncioInstitucionalFields.style.display = institucional ? '' : 'none';

      anuncioDataFim.readOnly = !institucional;
      anuncioDataFimHint.style.display = institucional ? 'none' : '';

      if (institucional) {
        document.getElementById('anuncioUsuario').value = '';
        document.getElementById('anuncioPlano').value = '';
        document.getElementById('errAnuncioUsuario').classList.remove('show');
        document.getElementById('errAnuncioPlano').classList.remove('show');
        anuncioPcDesc.textContent = anuncioTempoExibicao.value ? `Institucional · ${anuncioTempoExibicao.value}s` : 'Anúncio institucional GunesAds';
      } else {
        anuncioTempoExibicao.value = '';
        document.getElementById('errAnuncioTempoExibicao').classList.remove('show');
        anuncioDataFim.value = '';
        atualizarPrevisaoPlano();
      }
    }

    anuncioInstitucional.addEventListener('change', aplicarModoInstitucional);
    anuncioTempoExibicao.addEventListener('change', () => {
      const tempo = anuncioTempoExibicao.value;
      anuncioPcDesc.textContent = tempo ? `Institucional · ${tempo}s` : 'Anúncio institucional GunesAds';
    });

    const anuncioModalTitulo = document.getElementById('anuncioModalTitulo');
    const anuncioForm = document.getElementById('anuncioForm');

    function limparErrosAnuncioForm() {
      document.getElementById('errAnuncioUsuario').classList.remove('show');
      document.getElementById('errAnuncioTitulo').classList.remove('show');
      document.getElementById('errAnuncioPlano').classList.remove('show');
      document.getElementById('errAnuncioTempoExibicao').classList.remove('show');
    }

    function abrirModalAnuncio(modo, id) {
      anuncioForm.reset();
      limparErrosAnuncioForm();
      anuncioImagemUrl.value = '';
      anuncioImgPreview.classList.remove('show');
      anuncioImgPreview.style.backgroundImage = '';
      anuncioUploadLabel.style.color = '';
      anuncioUploadLabel.innerText = 'Clique para enviar a imagem (JPEG, JPG ou PNG)';
      anuncioPcPreview.style.backgroundImage = '';
      anuncioPcPreview.innerText = 'imagem do anúncio';
      anuncioPcTitulo.textContent = 'Título do anúncio';
      anuncioPcDesc.textContent = 'Selecione o plano';
      anuncioInstitucional.checked = false;
      aplicarModoInstitucional();

      if (modo === 'novo') {
        anuncioModalTitulo.textContent = 'Novo anúncio';
        document.getElementById('anuncioId').value = '';
        document.getElementById('anuncioStatus').value = 'Ativo';
      } else {
        const card = getAdCard(id);
        if (!card) return;

        anuncioModalTitulo.textContent = 'Editar anúncio';
        document.getElementById('anuncioId').value = id;
        document.getElementById('anuncioTitulo').value = card.dataset.titulo;
        document.getElementById('anuncioArte').checked = card.dataset.arte === 'true';
        document.getElementById('anuncioDataInicio').value = card.dataset.dataInicio;
        document.getElementById('anuncioDataFim').value = card.dataset.dataFim;
        document.getElementById('anuncioStatus').value = card.dataset.status === 'Encerrado' ? 'Ativo' : card.dataset.status;
        document.getElementById('anuncioObservacoes').value = card.dataset.observacoes;

        anuncioInstitucional.checked = card.dataset.institucional === 'true';
        aplicarModoInstitucional();

        if (anuncioInstitucional.checked) {
          anuncioTempoExibicao.value = card.dataset.tempoExibicao || '';
          anuncioDataFim.value = card.dataset.dataFim;
          anuncioPcDesc.textContent = card.dataset.tempoExibicao ? `Institucional · ${card.dataset.tempoExibicao}s` : 'Anúncio institucional GunesAds';
        } else {
          document.getElementById('anuncioUsuario').value = card.dataset.usuarioId;
          document.getElementById('anuncioPlano').value = card.dataset.planoId;
          atualizarPrevisaoPlano();
        }

        anuncioPcTitulo.textContent = card.dataset.titulo;

        if (card.dataset.imagem) {
          anuncioImagemUrl.value = card.dataset.imagem;
          anuncioImgPreview.style.backgroundImage = `url('${card.dataset.imagem}')`;
          anuncioImgPreview.classList.add('show');
          anuncioUploadLabel.innerText = 'Imagem atual — clique para substituir';
          anuncioPcPreview.style.backgroundImage = `url('${card.dataset.imagem}')`;
          anuncioPcPreview.innerText = '';
        }
      }

      abrirModal('modalAnuncio');
    }

    document.getElementById('btnNovoAnuncio').addEventListener('click', () => abrirModalAnuncio('novo'));

    document.getElementById('btnSalvarAnuncio').addEventListener('click', () => {
      const id = document.getElementById('anuncioId').value;
      const institucional = anuncioInstitucional.checked;

      // Anúncio institucional não tem usuário nem plano selecionados — ver
      // comentário no HTML sobre como o backend deve vincular o usuario_id
      // automaticamente (admin logado ou usuário "Sistema" dedicado).
      const usuarioId = institucional ? '' : document.getElementById('anuncioUsuario').value;
      const usuarioNome = institucional
        ? 'GunesAds (institucional)'
        : (document.getElementById('anuncioUsuario').selectedOptions[0]?.textContent || '');
      const titulo = document.getElementById('anuncioTitulo').value.trim();
      const planoId = institucional ? '' : document.getElementById('anuncioPlano').value;
      const tempoExibicao = institucional ? document.getElementById('anuncioTempoExibicao').value : '';
      const planoResumo = institucional
        ? (tempoExibicao ? `${tempoExibicao}s · institucional` : 'Institucional')
        : (document.getElementById('anuncioPlano').selectedOptions[0]?.textContent.split(' — ')[0] || '');
      const arte = document.getElementById('anuncioArte').checked;
      const dataInicio = document.getElementById('anuncioDataInicio').value;
      const dataFim = document.getElementById('anuncioDataFim').value;
      const status = document.getElementById('anuncioStatus').value;
      const observacoes = document.getElementById('anuncioObservacoes').value.trim();

      limparErrosAnuncioForm();
      let valido = true;
      if (!titulo) { document.getElementById('errAnuncioTitulo').classList.add('show'); valido = false; }

      if (institucional) {
        if (!tempoExibicao) { document.getElementById('errAnuncioTempoExibicao').classList.add('show'); valido = false; }
        if (!dataInicio || !dataFim) {
          mostrarToast('Preencha as datas', 'Informe a data de início e a data de término da campanha institucional', 'aviso');
          valido = false;
        }
      } else {
        if (!usuarioId) { document.getElementById('errAnuncioUsuario').classList.add('show'); valido = false; }
        if (!planoId) { document.getElementById('errAnuncioPlano').classList.add('show'); valido = false; }
      }
      if (!valido) return;

      if (id) {
        // EDIÇÃO: o card já existe na tela — atualizamos os atributos data-*
        // e os elementos visíveis diretamente, só pra dar o feedback visual
        // aqui no protótipo.
        //
        // No PHP real: este botão vira um <form method="post"
        // action="editar_anuncio.php"> enviando o "id" num campo hidden. O
        // PHP faz o UPDATE no banco e a página recarrega — o card já aparece
        // atualizado, vindo do foreach.
        const card = getAdCard(id);
        if (card) {
          card.dataset.usuarioId = usuarioId;
          card.dataset.usuarioNome = usuarioNome;
          card.dataset.titulo = titulo;
          card.dataset.planoId = planoId;
          card.dataset.planoResumo = planoResumo;
          card.dataset.institucional = institucional ? 'true' : 'false';
          card.dataset.tempoExibicao = tempoExibicao;
          card.dataset.arte = arte ? 'true' : 'false';
          card.dataset.dataInicio = dataInicio;
          card.dataset.dataFim = dataFim;
          card.dataset.observacoes = observacoes;

          card.querySelector('.ad-card-title').textContent = titulo;
          card.querySelector('.ad-card-anunciante').innerHTML = `<i class="bi bi-person"></i> ${usuarioNome}`;
          card.querySelector('.ad-card-meta span:first-child').innerHTML = `<i class="bi bi-stopwatch"></i> ${planoResumo}`;

          if (anuncioImagemUrl.value) {
            card.dataset.imagem = anuncioImagemUrl.value;
            const thumb = card.querySelector('.ad-card-thumb');
            thumb.style.backgroundImage = `url('${anuncioImagemUrl.value}')`;
            const fallbackIcon = thumb.querySelector('.thumb-fallback');
            if (fallbackIcon) fallbackIcon.remove();
          }
        }
        mostrarToast('Anúncio atualizado', `As alterações em "${titulo}" foram salvas`, 'sucesso');
      } else {
        // CADASTRO NOVO: de propósito, NÃO criamos um card novo via JS.
        //
        // No PHP real: este botão vira um <form method="post"
        // action="criar_anuncio.php">. O PHP calcula dataFim (se ainda não
        // vier calculada), faz o INSERT no banco e redireciona de volta pra
        // esta página — o novo card já aparece na lista porque vem do
        // foreach, igual aos outros.
        mostrarToast('Anúncio pronto para salvar', `"${titulo}" vai aparecer na lista assim que o formulário for enviado ao servidor`, 'sucesso');
      }

      fecharModal('modalAnuncio');
    });

    /* ---- Pausar / Retomar --------------------------------------------------- */
    function alternarStatusAnuncio(id) {
      const card = getAdCard(id);
      if (!card || card.dataset.status === 'Encerrado') return;

      // No PHP real: este botão vira um pequeno <form> que faz um UPDATE de
      // status no banco (ex: action="pausar_anuncio.php").
      const ativo = card.dataset.status === 'Ativo';
      const novoStatus = ativo ? 'Pausado' : 'Ativo';
      card.dataset.status = novoStatus;

      const badge = card.querySelector('.badge');
      badge.className = `badge ${novoStatus.toLowerCase()}`;
      badge.textContent = novoStatus;

      const btnPausar = card.querySelector('[data-action="pausar"]');
      btnPausar.title = novoStatus === 'Ativo' ? 'Pausar' : 'Retomar';
      btnPausar.querySelector('i').className = novoStatus === 'Ativo' ? 'bi bi-pause-fill' : 'bi bi-play-fill';

      mostrarToast(
        novoStatus === 'Ativo' ? 'Anúncio retomado' : 'Anúncio pausado',
        `"${card.dataset.titulo}" ${novoStatus === 'Ativo' ? 'voltou a rodar nos tablets' : 'não roda nos tablets até ser retomado'}`,
        novoStatus === 'Ativo' ? 'sucesso' : 'aviso'
      );
    }

    /* ---- Encerrar ------------------------------------------------------------ */
    let anuncioEncerrarSelecionadoId = null;

    function abrirConfirmarEncerrarAnuncio(id) {
      const card = getAdCard(id);
      if (!card) return;
      anuncioEncerrarSelecionadoId = id;
      document.getElementById('encerrarAnuncioNome').textContent = `"${card.dataset.titulo}"`;
      abrirModal('modalEncerrarAnuncio');
    }

    document.getElementById('btnConfirmarEncerrarAnuncio').addEventListener('click', () => {
      const card = getAdCard(anuncioEncerrarSelecionadoId);
      fecharModal('modalEncerrarAnuncio');
      if (!card) return;

      // No PHP real: este botão vira um <form>/AJAX que faz UPDATE de
      // status = 'Encerrado' no banco (ex: action="encerrar_anuncio.php").
      card.dataset.status = 'Encerrado';
      card.dataset.diasRestantes = '0';

      const badge = card.querySelector('.badge');
      badge.className = 'badge encerrado';
      badge.textContent = 'Encerrado';

      // Status "Encerrado" não faz sentido ter Pausar; troca para Renovar.
      card.querySelector('.ad-card-actions').innerHTML = `
        <button class="act-btn" type="button" data-action="editar" title="Editar"><i class="bi bi-pencil"></i></button>
        <button class="act-btn" type="button" data-action="renovar" title="Renovar"><i class="bi bi-arrow-repeat"></i></button>
        <button class="act-btn danger" type="button" data-action="excluir" title="Excluir"><i class="bi bi-trash"></i></button>
      `;

      mostrarToast('Anúncio encerrado', `"${card.dataset.titulo}" não roda mais nos tablets`, 'erro');
    });

    /* ---- Renovar --------------------------------------------------------------
       Renovar duplica o período do plano já contratado a partir de hoje,
       reativando a campanha. No PHP real, este botão faz um UPDATE
       recalculando dataInicio/dataFim a partir do plano_id já salvo (ex:
       action="renovar_anuncio.php"). Aqui no protótipo apenas simulamos o
       resultado visual, sem inventar novas datas reais. -------------------- */
    function renovarAnuncio(id) {
      const card = getAdCard(id);
      if (!card) return;

      card.dataset.status = 'Ativo';

      const badge = card.querySelector('.badge');
      badge.className = 'badge ativo';
      badge.textContent = 'Ativo';

      card.querySelector('.ad-card-actions').innerHTML = `
        <button class="act-btn" type="button" data-action="editar" title="Editar"><i class="bi bi-pencil"></i></button>
        <button class="act-btn" type="button" data-action="pausar" title="Pausar"><i class="bi bi-pause-fill"></i></button>
        <button class="act-btn" type="button" data-action="encerrar" title="Encerrar"><i class="bi bi-flag-fill"></i></button>
        <button class="act-btn danger" type="button" data-action="excluir" title="Excluir"><i class="bi bi-trash"></i></button>
      `;

      mostrarToast('Anúncio renovado', `"${card.dataset.titulo}" está ativo novamente — as novas datas serão calculadas pelo servidor`, 'info');
    }

    /* ---- Excluir --------------------------------------------------------------- */
    let anuncioExcluirSelecionadoId = null;

    function abrirConfirmarExcluirAnuncio(id) {
      const card = getAdCard(id);
      if (!card) return;
      anuncioExcluirSelecionadoId = id;
      document.getElementById('excluirAnuncioNome').textContent = `"${card.dataset.titulo}"`;
      abrirModal('modalExcluirAnuncio');
    }

    document.getElementById('btnConfirmarExcluirAnuncio').addEventListener('click', () => {
      const card = getAdCard(anuncioExcluirSelecionadoId);
      fecharModal('modalExcluirAnuncio');
      if (!card) return;

      const titulo = card.dataset.titulo;

      // No PHP real: o botão de excluir vira um <form method="post"
      // action="excluir_anuncio.php"> (ou um link de confirmação) que apaga
      // a linha no banco (DELETE) e recarrega a página. Aqui no protótipo,
      // só removemos o card da tela pra simular o resultado.
      card.classList.add('removing');
      setTimeout(() => {
        card.remove();
        atualizarContadorAnuncios();
      }, 220);

      mostrarToast('Anúncio excluído', `"${titulo}" foi removido do sistema`, 'erro');
    });

    // Delegação de eventos: um único listener cobre todos os cards, mesmo
    // após re-renderizações (encerrar/renovar recriam as ações do card).
    adsGrid.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-action]');
      if (!btn) return;
      const id = btn.closest('.ad-card').dataset.id;

      if (btn.dataset.action === 'editar') abrirModalAnuncio('editar', id);
      if (btn.dataset.action === 'pausar') alternarStatusAnuncio(id);
      if (btn.dataset.action === 'encerrar') abrirConfirmarEncerrarAnuncio(id);
      if (btn.dataset.action === 'renovar') renovarAnuncio(id);
      if (btn.dataset.action === 'excluir') abrirConfirmarExcluirAnuncio(id);
    });

    /* ========================================================================
       7. PLANOS
       ==================================================================== */
    const planoForm = document.getElementById('planoForm');
    const planoModalTitulo = document.getElementById('planoModalTitulo');

    // Nomenclatura dos níveis é derivada da duração (ver comentário no HTML,
    // seção "Planos"): 30 dias = Básico, 60 dias = Profissional, 90 dias =
    // Premium. Não é uma coluna própria no banco.
    const NOMES_NIVEL_PLANO = { 30: 'Básico', 60: 'Profissional', 90: 'Premium' };

    function getPlanCard(id) {
      return document.querySelector(`.plan-card[data-id="${id}"]`);
    }

    function abrirModalEditarPlano(id) {
      const card = getPlanCard(id);
      if (!card) return;

      planoForm.reset();
      document.getElementById('errPlanoPreco').classList.remove('show');
      document.getElementById('planoId').value = id;
      document.getElementById('planoPreco').value = card.dataset.preco;
      document.getElementById('planoDescricao').value = card.dataset.descricao;

      const nivel = NOMES_NIVEL_PLANO[Number(card.dataset.duracao)] || '';
      planoModalTitulo.textContent = `Editar plano — ${nivel} · ${card.dataset.tempo}s`;

      abrirModal('modalEditarPlano');
    }

    document.querySelectorAll('[data-action="editar-plano"]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const id = btn.closest('.plan-card').dataset.id;
        abrirModalEditarPlano(id);
      });
    });

    // Serviço de arte usa o mesmo modal de edição de preço, com id "arte".
    document.getElementById('btnEditarArte').addEventListener('click', () => {
      const bloco = document.querySelector('.plan-service-card');
      planoForm.reset();
      document.getElementById('errPlanoPreco').classList.remove('show');
      document.getElementById('planoId').value = 'arte';
      document.getElementById('planoPreco').value = bloco.dataset.preco;
      document.getElementById('planoDescricao').value = '';
      planoModalTitulo.textContent = 'Editar preço — Serviço de arte';
      abrirModal('modalEditarPlano');
    });

    document.getElementById('btnSalvarPlano').addEventListener('click', () => {
      const id = document.getElementById('planoId').value;
      const preco = document.getElementById('planoPreco').value.trim();
      const descricao = document.getElementById('planoDescricao').value.trim();

      const precoValido = /^\d+(,\d{2})?$/.test(preco);
      document.getElementById('errPlanoPreco').classList.toggle('show', !precoValido);
      if (!precoValido) return;

      if (id === 'arte') {
        // No PHP real: UPDATE no registro do serviço de arte (ex:
        // action="editar_servico_arte.php").
        const bloco = document.querySelector('.plan-service-card');
        bloco.dataset.preco = preco;
        document.getElementById('artePrecoDisplay').textContent = `R$ ${preco}`;
        mostrarToast('Preço atualizado', 'O valor do serviço de arte foi salvo', 'sucesso');
      } else {
        // No PHP real: este botão vira um <form method="post"
        // action="editar_plano.php"> enviando o "id" num campo hidden. O PHP
        // faz o UPDATE no banco (planos) e a página recarrega — o card já
        // aparece atualizado, vindo do foreach.
        const card = getPlanCard(id);
        if (card) {
          card.dataset.preco = preco;
          card.dataset.descricao = descricao;
          card.querySelector('.plan-card-preco').textContent = `R$ ${preco}`;
          card.querySelector('.plan-card-desc').textContent = descricao;
        }
        mostrarToast('Plano atualizado', 'O preço e a descrição foram salvos', 'sucesso');
      }

      fecharModal('modalEditarPlano');
    });

    /* ========================================================================
       8. Sistema global de alertas (Toasts)
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

    /* ---- 9. Data de boas-vindas no dashboard ------------------------------ */
    const heroDate = document.getElementById('heroDate');
    if (heroDate) {
      const hoje = new Date();
      const dataFormatada = hoje.toLocaleDateString('pt-BR', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
      });
      heroDate.textContent = `${dataFormatada} · aqui está o panorama do sistema.`;
    }

    /* ========================================================================
       10. PERFIL ADMINISTRATIVO
       Tela acessada só pelo menu do usuário (topbar), sem item na sidebar.
       Diferente do painel do cliente, aqui o admin edita os próprios dados.
       Nenhuma função abaixo simula persistência real: cada <form> já está
       pronto para apontar para o backend PHP (ver comentários no HTML).
       ==================================================================== */

    /* ---- Upload de foto de perfil (prévia local, mesmo padrão do Painel
       do Usuário) --------------------------------------------------------- */
    const avatarEditBtnAdmin = document.getElementById('avatarEditBtnAdmin');
    const avatarFileAdmin = document.getElementById('avatarFileAdmin');
    const profileAvatarPreviewAdmin = document.getElementById('profileAvatarPreviewAdmin');
    const topbarAvatarAdmin = document.getElementById('topbarAvatarAdmin');

    avatarEditBtnAdmin.addEventListener('click', () => avatarFileAdmin.click());

    avatarFileAdmin.addEventListener('change', () => {
      const file = avatarFileAdmin.files[0];
      if (!file) return;

      const allowedTypes = ['image/jpeg', 'image/png'];
      if (!allowedTypes.includes(file.type)) {
        mostrarToast('Formato inválido', 'Envie apenas imagens JPEG, JPG ou PNG', 'erro');
        avatarFileAdmin.value = '';
        return;
      }

      const url = URL.createObjectURL(file);

      [profileAvatarPreviewAdmin, topbarAvatarAdmin].forEach((el) => {
        if (!el) return;
        el.style.backgroundImage = `url('${url}')`;
        el.textContent = '';
      });

      // No PHP real: o envio do arquivo acontece via <form method="post"
      // enctype="multipart/form-data" action="server/perfil/atualizar_foto.php">.
      // Aqui no protótipo, apenas simulamos a confirmação visual com um toast.
      mostrarToast('Foto atualizada', 'Sua nova foto de perfil foi salva', 'sucesso');
    });

    /* ---- Dados pessoais (editável) ------------------------------------------
       Validação simples de front-end apenas (nome e e-mail obrigatórios); a
       validação definitiva e a persistência real ficam a cargo do PHP ao
       processar o POST. ----------------------------------------------------- */
    document.getElementById('adminProfileForm').addEventListener('submit', (e) => {

      const nome = document.getElementById('adminNome').value.trim();
      const email = document.getElementById('adminEmail').value.trim();
      const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

      if (!nome) {
        mostrarToast('Informe seu nome', 'O campo "Nome completo" não pode ficar vazio', 'aviso');
        return;
      }

      if (email && !emailValido) {
        mostrarToast('E-mail inválido', 'Verifique o e-mail informado', 'erro');
        return;
      }

      // Reflete o novo nome na hero e na topbar, só como feedback visual do
      // protótipo. No PHP real, este form envia para o backend (UPDATE do
      // registro do administrador) e a página recarrega com os dados salvos.
      document.querySelector('#profileHeroAdmin .profile-hero-name').textContent = nome;
      const userHelloB = document.querySelector('.user-hello b');
      if (userHelloB) userHelloB.textContent = nome;

      mostrarToast('Dados atualizados', 'Suas informações foram salvas', 'sucesso');
    });

    /* ---- Alteração de senha (admin) ------------------------------------------
       Mesma lógica de conveniência de UX do Painel do Usuário: só confere se
       "Nova senha" e "Confirmar nova senha" coincidem. Validação real (senha
       atual correta, força mínima etc.) é responsabilidade do PHP. ---------- */
    document.getElementById('adminChangePasswordForm').addEventListener('submit', (e) => {

      const novaSenha = document.getElementById('adminNewPassword').value;
      const confirmarSenha = document.getElementById('adminConfirmPassword').value;

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
  </script>

  <?php 
    if (isset($_SESSION["toast"]) && isset($_SESSION["backScreen"])) {
      $toast = explode("/", $_SESSION["toast"]);
      $msg = $toast[0];
      $submsg = $toast[1];
      $tipo = $toast[2];

      $screen = $_SESSION["backScreen"];

      echo "<script>irParaTela('$screen')</script>";
      echo "<script>mostrarToast('$msg', '$submsg', '$tipo')</script>";

      unset($_SESSION["toast"]);
    }
  ?>
</body>
</html>
