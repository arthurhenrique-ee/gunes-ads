<?php 
  include "server/auth.php";
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
       GUNESADS — PAINEL ADMINISTRATIVO (Estrutura)
       --------------------------------------------------------------------------
       Reaproveita a mesma identidade visual, componentes e estrutura de
       sidebar/topbar/telas do painel do anunciante (gunes-ads-painel-user.html),
       incluindo as correções de responsividade da Etapa 1. Apenas as opções
       de menu e o conteúdo das telas mudam, conforme o contexto administrativo.

       Sumário deste arquivo:
         1. Reset básico + variáveis globais (idênticas ao painel do usuário)
         2. Layout base (sidebar + área principal) + sidebar responsiva
         3. Topbar + menu do usuário (adaptado ao contexto admin)
         4. Tela: Visão Geral (cards de métricas + atividade recente)
         5. Telas placeholder: Anúncios Pendentes, Usuários, Planos,
            Anúncios do Admin — conteúdo real chega nas próximas etapas
         6. Sistema de alertas (Toasts) — igual ao painel do usuário
       ========================================================================== */

    /* -------------------------------------------------------------------- */
    /* 1. Reset + variáveis globais (mesmos tokens do painel do usuário)    */
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

    .nav-badge-soon {
      display: inline-flex;
      align-items: center;
      margin-left: auto;
      padding: 2px 7px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      font-size: 9.5px;
      font-weight: 700;
      letter-spacing: 0.3px;
    }

    .nav-item:not(.active) .nav-badge-soon {
      color: var(--text-muted);
      background: var(--surface-2);
    }

    .sidebar.collapsed .nav-badge-soon {
      display: none;
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
    /* 3. Topbar + menu do usuário (já com as correções da Etapa 1)          */
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
      width: 200px;
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
    /* 4. Visão Geral — cards de métricas + atividade recente                */
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

    .panel {
      padding: 24px;
      margin-bottom: 20px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      box-shadow: var(--shadow);
    }

    .panel-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
    }

    .panel h3 {
      font-family: var(--font-display);
      font-size: 15px;
      font-weight: 600;
    }

    .panel-head .panel-sub {
      color: var(--text-muted);
      font-size: 12px;
    }

    .overview-grid {
      display: grid;
      grid-template-columns: 1.5fr 1fr;
      gap: 20px;
    }

    @media (max-width: 900px) {
      .overview-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Lista de atividade recente */
    .activity-list {
      display: flex;
      flex-direction: column;
    }

    .activity-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid var(--border);
    }

    .activity-item:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }

    .activity-item:first-child {
      padding-top: 0;
    }

    .activity-icon {
      display: flex;
      flex-shrink: 0;
      align-items: center;
      justify-content: center;
      width: 34px;
      height: 34px;
      border-radius: 10px;
      font-size: 14px;
    }

    .activity-text {
      min-width: 0;
    }

    .activity-title {
      font-size: 13.5px;
      font-weight: 500;
      line-height: 1.4;
    }

    .activity-title b {
      font-weight: 600;
    }

    .activity-time {
      margin-top: 2px;
      color: var(--text-muted);
      font-size: 12px;
    }

    /* Resumo lateral: pendências que exigem atenção do admin */
    .todo-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .todo-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      padding: 12px 14px;
      background: var(--surface-2);
      border-radius: 10px;
      font-size: 13px;
      font-weight: 500;
    }

    .todo-item .count {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 24px;
      height: 24px;
      padding: 0 7px;
      color: #fff;
      background: var(--primary);
      border-radius: 20px;
      font-size: 11.5px;
      font-weight: 700;
    }

    .todo-item .count.warn { background: var(--warning); }
    .todo-item .count.zero { background: var(--text-muted); }

    /* -------------------------------------------------------------------- */
    /* 4.5. Anúncios Pendentes — grade de cards + ações de moderação         */
    /* -------------------------------------------------------------------- */
    .pending-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
    }

    @media (max-width: 1300px) {
      .pending-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 640px) {
      .pending-grid {
        grid-template-columns: 1fr;
      }
    }

    .pend-card {
      display: flex;
      flex-direction: column;
      overflow: hidden;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      box-shadow: var(--shadow);
      transition: opacity 0.25s ease, transform 0.25s ease;
    }

    .pend-card.removing {
      opacity: 0;
      transform: scale(0.97);
    }

    .pend-card-thumb {
      position: relative;
      display: flex;
      align-items: flex-end;
      height: 110px;
      padding: 12px 14px;
      color: rgba(255, 255, 255, 0.85);
      font-size: 26px;
    }

    .pend-card-thumb .thumb-fallback {
      opacity: 0.55;
    }

    .pend-card-body {
      display: flex;
      flex: 1;
      flex-direction: column;
      padding: 16px;
    }

    .pend-card-head {
      margin-bottom: 10px;
    }

    .pend-card-title {
      font-weight: 600;
      font-size: 14.5px;
      line-height: 1.35;
    }

    .pend-card-anunciante {
      display: flex;
      align-items: center;
      gap: 5px;
      margin-top: 3px;
      color: var(--text-muted);
      font-size: 12.5px;
    }

    .pend-card-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 6px 14px;
      margin-bottom: 14px;
      color: var(--text-muted);
      font-size: 12.5px;
    }

    .pend-card-meta span {
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .pend-card-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: auto;
      padding-top: 14px;
      border-top: 1px solid var(--border);
    }

    .pend-btn {
      display: inline-flex;
      flex: 1 1 auto;
      align-items: center;
      justify-content: center;
      gap: 6px;
      height: 34px;
      min-width: 34px;
      padding: 0 10px;
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

    .pend-btn.approve:hover {
      color: var(--success);
      background: var(--success-light);
      border-color: var(--success);
    }

    .pend-btn.reject:hover {
      color: var(--danger);
      background: var(--danger-light);
      border-color: var(--danger);
    }

    .pend-btn.icon-only {
      flex: 0 0 auto;
      width: 34px;
    }

    /* Estado vazio: nenhum anúncio pendente */
    .empty-pending {
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

    .empty-pending.show {
      display: flex;
    }

    .empty-pending i {
      font-size: 30px;
      color: var(--success);
    }

    .empty-pending h2 {
      font-family: var(--font-display);
      font-size: 16px;
      font-weight: 600;
    }

    .empty-pending p {
      max-width: 340px;
      color: var(--text-muted);
      font-size: 13px;
    }

    /* -------------------------------------------------------------------- */
    /* 4.6. Sistema de modais — detalhes, comentário e confirmação           */
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

    .modal-overlay.show {
      display: flex;
    }

    @keyframes overlayIn {
      from { opacity: 0; }
      to   { opacity: 1; }
    }

    .modal-box {
      width: 100%;
      max-width: 440px;
      max-height: 88vh;
      overflow-y: auto;
      background: var(--surface);
      border-radius: 16px;
      box-shadow: 0 24px 60px rgba(15, 17, 23, 0.3);
      animation: modalIn 0.2s ease;
    }

    .modal-box.wide {
      max-width: 560px;
    }

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

    .modal-close:hover {
      color: var(--text);
    }

    .modal-body {
      padding: 20px 22px;
    }

    .modal-thumb {
      display: flex;
      align-items: flex-end;
      height: 160px;
      padding: 16px;
      margin-bottom: 16px;
      border-radius: 12px;
      color: #fff;
      font-family: var(--font-display);
      font-size: 20px;
      font-weight: 700;
    }

    .modal-detail-row {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      padding: 10px 0;
      border-bottom: 1px solid var(--border);
      font-size: 13px;
    }

    .modal-detail-row:last-child {
      border-bottom: none;
    }

    .modal-detail-row .k {
      color: var(--text-muted);
    }

    .modal-detail-row .v {
      font-weight: 600;
      text-align: right;
    }

    .modal-detail-desc {
      margin-top: 14px;
      color: var(--text);
      font-size: 13.5px;
      line-height: 1.6;
    }

    .modal-body label {
      display: block;
      margin-top: 0;
      margin-bottom: 6px;
      color: var(--text-muted);
      font-size: 13px;
      font-weight: 500;
    }

    .modal-body textarea {
      width: 100%;
      min-height: 110px;
      padding: 11px 14px;
      color: var(--text);
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 10px;
      font-family: var(--font-body);
      font-size: 14px;
      resize: vertical;
    }

    .modal-body textarea:focus {
      border-color: var(--primary);
      outline: none;
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

    .modal-btn.ghost:hover {
      border-color: var(--text-muted);
    }

    .modal-btn.primary {
      color: #fff;
      background: var(--primary);
      box-shadow: 0 8px 18px rgba(62, 94, 224, 0.28);
    }

    .modal-btn.primary:hover {
      background: var(--primary-dark);
    }

    .modal-btn.danger {
      color: #fff;
      background: var(--danger);
    }

    .modal-btn.danger:hover {
      background: #D6362F;
    }

    /* -------------------------------------------------------------------- */
    /* 4.7. Usuários — toolbar, lista de anunciantes e ações                 */
    /* -------------------------------------------------------------------- */
    .users-toolbar {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 18px;
    }

    .users-count {
      color: var(--text-muted);
      font-size: 13px;
    }

    .users-count b {
      color: var(--text);
    }

    .btn-new-user {
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

    .btn-new-user:hover {
      background: var(--primary-dark);
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
      transition: opacity 0.25s ease;
    }

    .user-row.inactive {
      opacity: 0.6;
    }

    .user-row-avatar {
      display: flex;
      flex-shrink: 0;
      align-items: center;
      justify-content: center;
      width: 42px;
      height: 42px;
      background: linear-gradient(135deg, var(--primary), #6B8CFF);
      color: #fff;
      border-radius: 10px;
      font-family: var(--font-display);
      font-weight: 700;
      font-size: 13.5px;
    }

    .user-row-info {
      flex: 1 1 200px;
      min-width: 0;
    }

    .user-row-name {
      font-weight: 600;
      font-size: 14px;
    }

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

    .status-badge.ativo { color: var(--success); background: var(--success-light); }
    .status-badge.aguardando { color: var(--warning); background: var(--warning-light); }
    .status-badge.inativo { color: var(--text-muted); background: var(--surface-2); }

    .inline-select {
      flex-shrink: 0;
      width: 160px;
      padding: 8px 10px;
      color: var(--text);
      background: var(--surface-2);
      border: 1px solid var(--border);
      border-radius: 8px;
      font-family: var(--font-body);
      font-size: 12.5px;
      font-weight: 500;
      cursor: pointer;
    }

    .inline-select:focus {
      border-color: var(--primary);
      outline: none;
    }

    .user-row-actions {
      display: flex;
      flex-shrink: 0;
      gap: 6px;
    }

    .user-act-btn {
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

    .user-act-btn:hover {
      color: var(--primary);
      background: var(--primary-light);
      border-color: var(--primary);
    }

    .user-act-btn.toggle-off:hover {
      color: var(--danger);
      background: var(--danger-light);
      border-color: var(--danger);
    }

    .user-act-btn.toggle-on:hover {
      color: var(--success);
      background: var(--success-light);
      border-color: var(--success);
    }

    /* Formulário dentro do modal de cadastro/edição de usuário */
    .modal-body input[type="text"],
    .modal-body input[type="email"],
    .modal-body input[type="tel"],
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
    .modal-body select:focus {
      border-color: var(--primary);
      outline: none;
    }

    .modal-body label {
      margin-top: 14px;
    }

    .modal-body label:first-child {
      margin-top: 0;
    }

    .modal-info-box {
      display: flex;
      gap: 10px;
      padding: 14px;
      margin-top: 16px;
      background: var(--info-light);
      border-radius: 10px;
      color: var(--text);
      font-size: 12.5px;
      line-height: 1.55;
    }

    .modal-info-box i {
      flex-shrink: 0;
      margin-top: 1px;
      color: var(--info);
    }

    .field-error {
      display: none;
      margin-top: 6px;
      color: var(--danger);
      font-size: 12px;
    }

    .field-error.show {
      display: block;
    }

    /* -------------------------------------------------------------------- */
    /* 4.8. Planos — grade de cards + criação/edição de planos               */
    /* -------------------------------------------------------------------- */
    .section-toolbar {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: flex-end;
      gap: 12px;
      margin-bottom: 18px;
    }

    .btn-new-item {
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

    .btn-new-item:hover {
      background: var(--primary-dark);
    }

    .plans-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    @media (max-width: 1100px) {
      .plans-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 640px) {
      .plans-grid {
        grid-template-columns: 1fr;
      }
    }

    .plan-card {
      position: relative;
      display: flex;
      flex-direction: column;
      padding: 26px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 16px;
      box-shadow: var(--shadow);
      transition: opacity 0.25s ease, transform 0.25s ease;
    }

    .plan-card.removing {
      opacity: 0;
      transform: scale(0.97);
    }

    .plan-card.featured {
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

    .plan-card-actions {
      display: flex;
      gap: 8px;
      padding-top: 14px;
      border-top: 1px solid var(--border);
    }

    .plan-card-actions .pend-btn {
      flex: 1;
    }

    /* Editor dinâmico de benefícios dentro do modal de plano */
    .feat-editor-list {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-bottom: 10px;
    }

    .feat-editor-row {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .feat-editor-row input {
      flex: 1;
    }

    .feat-remove-btn {
      display: flex;
      flex-shrink: 0;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      color: var(--text-muted);
      background: var(--surface-2);
      border: 1px solid var(--border);
      border-radius: 8px;
      cursor: pointer;
    }

    .feat-remove-btn:hover {
      color: var(--danger);
      background: var(--danger-light);
      border-color: var(--danger);
    }

    .btn-add-feat {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 12px;
      color: var(--primary);
      background: var(--primary-light);
      border: 1px dashed var(--primary);
      border-radius: 8px;
      font-family: var(--font-body);
      font-size: 12.5px;
      font-weight: 600;
      cursor: pointer;
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

    /* Zona de upload de imagem — mesmo padrão do "Criar Anúncio" do usuário */
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

    .upload-zone-preview.show {
      display: flex;
    }

    /* -------------------------------------------------------------------- */
    /* 4.9. Anúncios do Admin — grade de cards + cadastro                    */
    /* -------------------------------------------------------------------- */
    .admin-ads-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
    }

    @media (max-width: 1300px) {
      .admin-ads-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 640px) {
      .admin-ads-grid {
        grid-template-columns: 1fr;
      }
    }

    .badge.pausado { color: var(--warning); background: var(--warning-light); }

    .admin-ad-card {
      display: flex;
      flex-direction: column;
      overflow: hidden;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      box-shadow: var(--shadow);
      transition: opacity 0.25s ease, transform 0.25s ease;
    }

    .admin-ad-card.removing {
      opacity: 0;
      transform: scale(0.97);
    }

    .admin-ad-card-thumb {
      position: relative;
      display: flex;
      align-items: flex-end;
      height: 110px;
      padding: 12px 14px;
      color: rgba(255, 255, 255, 0.85);
      font-size: 26px;
    }

    .admin-ad-card-thumb .thumb-fallback {
      opacity: 0.55;
    }

    .admin-ad-card-body {
      display: flex;
      flex: 1;
      flex-direction: column;
      padding: 16px;
    }

    .admin-ad-card-head {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 10px;
    }

    .admin-ad-card-title {
      font-weight: 600;
      font-size: 14.5px;
      line-height: 1.35;
    }

    .admin-ad-card-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 6px 14px;
      margin-bottom: 16px;
      color: var(--text-muted);
      font-size: 12.5px;
    }

    .admin-ad-card-meta span {
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .admin-ad-card-actions {
      display: flex;
      gap: 6px;
      margin-top: auto;
      padding-top: 14px;
      border-top: 1px solid var(--border);
    }

    .admin-ad-card-actions .act-btn {
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

    .admin-ad-card-actions .act-btn:hover {
      color: var(--primary);
      background: var(--primary-light);
      border-color: var(--primary);
    }

    .admin-ad-card-actions .act-btn.danger:hover {
      color: var(--danger);
      background: var(--danger-light);
      border-color: var(--danger);
    }

    .empty-admin-ads {
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

    .empty-admin-ads.show {
      display: flex;
    }

    .empty-admin-ads i {
      font-size: 30px;
      color: var(--text-muted);
    }

    .empty-admin-ads h2 {
      font-family: var(--font-display);
      font-size: 16px;
      font-weight: 600;
    }

    .empty-admin-ads p {
      max-width: 340px;
      color: var(--text-muted);
      font-size: 13px;
    }

    /* -------------------------------------------------------------------- */
    /* 5. Telas placeholder — conteúdo real chega nas próximas etapas        */
    /* -------------------------------------------------------------------- */
    .placeholder-screen {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 14px;
      padding: 70px 24px;
      text-align: center;
      background: var(--surface);
      border: 1px dashed var(--border);
      border-radius: 16px;
    }

    .placeholder-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 56px;
      height: 56px;
      background: var(--primary-light);
      color: var(--primary);
      border-radius: 14px;
      font-size: 24px;
    }

    .placeholder-screen h2 {
      font-family: var(--font-display);
      font-size: 17px;
      font-weight: 600;
    }

    .placeholder-screen p {
      max-width: 380px;
      color: var(--text-muted);
      font-size: 13.5px;
      line-height: 1.6;
    }

    .placeholder-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 5px 12px;
      color: var(--warning);
      background: var(--warning-light);
      border-radius: 20px;
      font-size: 11.5px;
      font-weight: 600;
    }

    /* -------------------------------------------------------------------- */
    /* 6. Sistema de alertas (Toasts) — idêntico ao painel do usuário        */
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
      <button class="nav-item" type="button" data-screen="visao-geral">
        <span class="nav-icon"><i class="bi bi-grid-1x2-fill"></i></span><span class="nav-label">Visão Geral</span>
      </button>
      <button class="nav-item" type="button" data-screen="pendentes">
        <span class="nav-icon"><i class="bi bi-hourglass-split"></i></span><span class="nav-label">Anúncios Pendentes</span>
      </button>
      <button class="nav-item" type="button" data-screen="usuarios">
        <span class="nav-icon"><i class="bi bi-people-fill"></i></span><span class="nav-label">Usuários</span>
      </button>
      <button class="nav-item" type="button" data-screen="planos-admin">
        <span class="nav-icon"><i class="bi bi-credit-card-fill"></i></span><span class="nav-label">Planos</span>
      </button>
      <button class="nav-item active" type="button" data-screen="anuncios-admin">
        <span class="nav-icon"><i class="bi bi-megaphone-fill"></i></span><span class="nav-label">Anúncios do Admin</span>
      </button>
    </nav>
  </div>

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <!-- ======================= ÁREA PRINCIPAL ======================= -->
  <div class="main">

    <div class="topbar">
      <div class="topbar-left">
        <button class="menu-toggle" id="menuToggle" type="button" aria-label="Abrir ou recolher o menu"><i class="bi bi-list"></i></button>
        <h1 id="screen-title">Visão Geral</h1>
      </div>

      <div class="topbar-right">
        <div class="user-info" id="userInfo">
          <div class="user-text">
            <div class="user-hello">Olá, <b><?= $firstName ?></b></div>
            <div class="user-plan"><i class="bi bi-shield-fill-check"></i> Administrador</div>
          </div>
          <div class="avatar"><?= $iniciais ?></div>

          <div class="user-menu" id="userMenu">
            <div class="user-menu-item"><i class="bi bi-person"></i> Perfil</div>
            <div class="user-menu-item"><i class="bi bi-gear"></i> Configurações da conta</div>
            <div class="user-menu-divider"></div>
            <a href="server/logout.php" style="text-decoration: none;" class="user-menu-item danger"><i class="bi bi-box-arrow-right"></i> Sair</a>
          </div>
        </div>
      </div>
    </div>

    <div class="content">

      <!-- ----------------- TELA: VISÃO GERAL ----------------- -->
      <div class="screen" id="screen-visao-geral">
        <p class="section-intro">Panorama geral do GunesAds: acompanhe anunciantes, campanhas e o que precisa da sua atenção.</p>

        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            </div>
            <div class="stat-label">Usuários cadastrados</div>
            <div class="stat-value">86</div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon green"><i class="bi bi-broadcast"></i></div>
            </div>
            <div class="stat-label">Anúncios ativos</div>
            <div class="stat-value">54</div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon amber"><i class="bi bi-hourglass-split"></i></div>
            </div>
            <div class="stat-label">Pendentes de aprovação</div>
            <div class="stat-value">7</div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon red"><i class="bi bi-calendar-x-fill"></i></div>
            </div>
            <div class="stat-label">Campanhas encerradas (mês)</div>
            <div class="stat-value">12</div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon cyan"><i class="bi bi-cash-stack"></i></div>
            </div>
            <div class="stat-label">Faturamento estimado do mês</div>
            <div class="stat-value">R$ 9.680</div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <div class="stat-icon blue"><i class="bi bi-person-plus-fill"></i></div>
            </div>
            <div class="stat-label">Novos cadastros (7 dias)</div>
            <div class="stat-value">6</div>
          </div>
        </div>

        <div class="overview-grid">
          <!-- Atividade recente -->
          <div class="panel">
            <div class="panel-head">
              <h3>Atividade recente</h3>
              <span class="panel-sub">Últimas 24 horas</span>
            </div>
            <div class="activity-list" id="activityList">
              <!-- Itens injetados via JS a partir de dados de exemplo -->
            </div>
          </div>

          <!-- O que precisa de atenção -->
          <div class="panel">
            <div class="panel-head">
              <h3>Precisa da sua atenção</h3>
            </div>
            <div class="todo-list">
              <div class="todo-item">
                <span><i class="bi bi-hourglass-split" style="color:var(--warning); margin-right:8px;"></i>Anúncios aguardando aprovação</span>
                <span class="count warn">7</span>
              </div>
              <div class="todo-item">
                <span><i class="bi bi-exclamation-circle" style="color:var(--danger); margin-right:8px;"></i>Pagamentos pendentes</span>
                <span class="count warn">3</span>
              </div>
              <div class="todo-item">
                <span><i class="bi bi-chat-left-text" style="color:var(--primary); margin-right:8px;"></i>Comentários sem resposta</span>
                <span class="count zero">0</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ----------------- TELAS PLACEHOLDER (conteúdo nas próximas etapas) ----------------- -->
      <div class="screen" id="screen-pendentes">
        <p class="section-intro">Revise os anúncios enviados pelos anunciantes antes de irem ao ar nos tablets.</p>

        <div class="pending-grid" id="pendingGrid"></div>

        <div class="empty-pending" id="emptyPending">
          <i class="bi bi-check-circle-fill"></i>
          <h2>Nenhum anúncio aguardando aprovação</h2>
          <p>Assim que um anunciante enviar um novo anúncio, ele aparecerá aqui para revisão.</p>
        </div>
      </div>

      <div class="screen" id="screen-usuarios">
        <p class="section-intro">Gerencie os anunciantes cadastrados no GunesAds e o plano de cada um.</p>

        <div class="users-toolbar">
          <div class="users-count">Total: <b id="usersCount">0</b> usuários</div>
          <button class="btn-new-user" type="button" id="btnNovoUsuario">
            <i class="bi bi-person-plus-fill"></i> Novo usuário
          </button>
        </div>

        <!--
          ============================================================================
          ETAPA 11 — USUÁRIOS
          As linhas abaixo NÃO são mais geradas pelo JavaScript (antes vinham de
          um array USERS + função renderUsers()). Agora são HTML fixo, no formato
          que um "foreach" em PHP deve produzir — uma linha por usuário cadastrado
          no banco.

          Campos de cada usuário (viriam de $usuario['campo'] no PHP):
            id         -> id do usuário no banco
            nome       -> nome completo
            empresa    -> nome da empresa/serviço (pode ser vazio)
            email      -> e-mail de acesso
            telefone   -> telefone de contato
            plano      -> Básico / Profissional / Premium
            status     -> 'ativo', 'aguardando' (1º acesso) ou 'inativo'

          Guardei esses valores em atributos data-* na própria linha (data-nome,
          data-empresa, etc.), pra o JavaScript conseguir ler os dados direto do
          HTML já renderizado, sem precisar de nenhum array próprio dele.

          Dica pro PHP: use htmlspecialchars() em todo texto que vier do usuário
          (nome, empresa) e monte as iniciais do avatar (ex: "AL") direto no PHP
          também, se preferir — aqui deixei o JS calculando pra simplificar.
          ============================================================================
        -->
        <div class="users-list" id="usersList">

          <!-- INÍCIO DO LOOP: repetir este bloco para cada $usuario em $usuarios -->
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
            <select class="inline-select" data-action="plano">
              <option value="Básico">Básico</option>
              <option value="Profissional" selected>Profissional</option>
              <option value="Premium">Premium</option>
            </select>
            <div class="user-row-actions">
              <button class="user-act-btn" type="button" data-action="editar" title="Editar dados"><i class="bi bi-pencil"></i></button>
              <button class="user-act-btn toggle-off" type="button" data-action="status" title="Desativar">
                <i class="bi bi-slash-circle"></i>
              </button>
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
            <select class="inline-select" data-action="plano">
              <option value="Básico">Básico</option>
              <option value="Profissional">Profissional</option>
              <option value="Premium" selected>Premium</option>
            </select>
            <div class="user-row-actions">
              <button class="user-act-btn" type="button" data-action="editar" title="Editar dados"><i class="bi bi-pencil"></i></button>
              <button class="user-act-btn toggle-off" type="button" data-action="status" title="Desativar">
                <i class="bi bi-slash-circle"></i>
              </button>
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
            <select class="inline-select" data-action="plano">
              <option value="Básico" selected>Básico</option>
              <option value="Profissional">Profissional</option>
              <option value="Premium">Premium</option>
            </select>
            <div class="user-row-actions">
              <button class="user-act-btn" type="button" data-action="editar" title="Editar dados"><i class="bi bi-pencil"></i></button>
              <button class="user-act-btn toggle-off" type="button" data-action="status" title="Desativar">
                <i class="bi bi-slash-circle"></i>
              </button>
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
            <select class="inline-select" data-action="plano">
              <option value="Básico">Básico</option>
              <option value="Profissional" selected>Profissional</option>
              <option value="Premium">Premium</option>
            </select>
            <div class="user-row-actions">
              <button class="user-act-btn" type="button" data-action="editar" title="Editar dados"><i class="bi bi-pencil"></i></button>
              <button class="user-act-btn toggle-on" type="button" data-action="status" title="Reativar">
                <i class="bi bi-arrow-counterclockwise"></i>
              </button>
            </div>
          </div>
          <!-- FIM DO LOOP -->

        </div>
      </div>

      <div class="screen" id="screen-planos-admin">
        <p class="section-intro">Crie, edite e remova os planos de assinatura oferecidos aos anunciantes. As alterações aqui devem refletir automaticamente no painel do usuário (integração via backend nas próximas etapas).</p>

        <div class="section-toolbar">
          <button class="btn-new-item" type="button" id="btnNovoPlano">
            <i class="bi bi-plus-circle-fill"></i> Novo plano
          </button>
        </div>

        <div class="plans-grid" id="plansGrid"></div>
      </div>

      <div class="screen active" id="screen-anuncios-admin">
        <p class="section-intro">Cadastre anúncios próprios ou de teste — institucionais, "Anuncie aqui", produtos da motorista ou qualquer campanha administrada diretamente pelo sistema. Eles seguem o mesmo padrão visual e entram na rotação normalmente junto aos anúncios dos usuários.</p>

        <div class="section-toolbar">
          <button class="btn-new-item" type="button" id="btnNovoAnuncioAdmin">
            <i class="bi bi-plus-circle-fill"></i> Novo anúncio
          </button>
        </div>

        <!--
          ============================================================================
          ETAPA 11 — ANÚNCIOS DO ADMIN
          Os cards abaixo NÃO são mais gerados pelo JavaScript (antes vinham de um
          array ADMIN_ADS + função renderAdminAds()). Agora são HTML fixo, no
          formato que um "foreach" em PHP deve produzir — um bloco de card por
          linha da tabela "anuncios_admin" no banco.

          Campos de cada anúncio (viriam de $anuncio['campo'] no PHP):
            id         -> id do anúncio no banco
            nome       -> nome do anúncio (ex: "Anuncie aqui")
            tag        -> descrição/chamada curta
            categoria  -> Institucional / Anuncie aqui / Produto da motorista / Teste
            status     -> 'ativo' ou 'pausado'
            imagem     -> URL da imagem (se vazio, mostra o fundo colorido + ícone)

          Guardei esses valores em atributos data-* no próprio card (data-nome,
          data-tag, etc.) — assim o JavaScript consegue ler os dados direto do
          HTML já renderizado, sem precisar de nenhum array próprio dele.

          Dica pro PHP: dentro do foreach, lembre de usar htmlspecialchars() em
          todo texto que vier do usuário (nome, tag), pra evitar problema de
          segurança (XSS).
          ============================================================================
        -->
        <div class="admin-ads-grid" id="adminAdsGrid">

          <!-- INÍCIO DO LOOP: repetir este bloco para cada $anuncio em $anuncios -->
          <?php foreach ($anuncios as $anuncio):?>
          <div
            class="admin-ad-card"
            data-id="<?= $anuncio["id"] ?>"
            data-nome="<?= $anuncio["nome"] ?>"
            data-tag="<?= $anuncio["descricao"] ?>"
            data-categoria="<?= $anuncio["categoria"] ?>"
            data-status="<?= $anuncio["status"] ?>"
            data-imagem="<?= $anuncio["imagem"] ?>"
          >
            <div class="admin-ad-card-thumb" style="background:linear-gradient(135deg,#3E5EE0,#1B2C7A);">
              <i class="bi bi-image thumb-fallback"></i>
            </div>
            <div class="admin-ad-card-body">
              <div class="admin-ad-card-head">
                <div class="admin-ad-card-title"><?= $anuncio["nome"] ?></div>
                <span class="badge ativo"><?= ucfirst($anuncio["status"]) ?></span>
              </div>
              <div class="admin-ad-card-meta">
                <span><i class="bi bi-tag"></i> <span class="cat-text">Anuncie aqui</span></span>
              </div>
              <div class="admin-ad-card-meta ad-tag-desc"><?= $anuncio["descricao"] ?></div>
              <div class="admin-ad-card-actions">
                <button class="act-btn" type="button" data-action="editar-anuncio-admin" title="Editar"><i class="bi bi-pencil"></i></button>
                <button class="act-btn" type="button" data-action="pausar-anuncio-admin" title="Pausar"><i class="bi bi-pause-fill"></i></button>
                <button class="act-btn danger" type="button" data-action="excluir-anuncio-admin" title="Excluir"><i class="bi bi-trash"></i></button>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          <!-- FIM DO LOOP -->

        </div>

        <!--
          Estado vazio: no PHP, isto entra num "else" do foreach, tipo:
          <?php if (empty($anuncios)): ?> ... este bloco ... <?php endif; ?>
          Por enquanto fica escondido porque já temos cards de exemplo acima.
        -->
        <div class="empty-admin-ads" id="emptyAdminAds">
          <i class="bi bi-megaphone"></i>
          <h2>Nenhum anúncio do admin cadastrado</h2>
          <p>Cadastre campanhas institucionais ou de teste para que participem da rotação nos tablets.</p>
        </div>
      </div>

    </div>
  </div>

  <!-- ======================= MODAL: VER DETALHES ======================= -->
  <div class="modal-overlay" id="modalDetalhes">
    <div class="modal-box wide">
      <div class="modal-head">
        <div>
          <h3 id="detTitulo">Nome do anúncio</h3>
          <div class="modal-sub" id="detAnunciante">Anunciante</div>
        </div>
        <button class="modal-close" type="button" data-close="modalDetalhes"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <div class="modal-thumb" id="detThumb"></div>
        <div class="modal-detail-row"><span class="k">Duração pretendida</span><span class="v" id="detDuracao">—</span></div>
        <div class="modal-detail-row"><span class="k">Enviado em</span><span class="v" id="detData">—</span></div>
        <div class="modal-detail-row"><span class="k">E-mail do anunciante</span><span class="v" id="detEmail">—</span></div>
        <p class="modal-detail-desc" id="detDescricao"></p>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalDetalhes">Fechar</button>
        <button class="modal-btn danger" type="button" id="detBtnReprovar">Reprovar</button>
        <button class="modal-btn primary" type="button" id="detBtnAprovar">Aprovar</button>
      </div>
    </div>
  </div>

  <!-- ======================= MODAL: ENVIAR COMENTÁRIO ======================= -->
  <div class="modal-overlay" id="modalComentario">
    <div class="modal-box">
      <div class="modal-head">
        <div>
          <h3>Enviar comentário</h3>
          <div class="modal-sub" id="comAnuncioNome">para o anunciante</div>
        </div>
        <button class="modal-close" type="button" data-close="modalComentario"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <label for="comentarioTexto">Mensagem para o anunciante</label>
        <textarea id="comentarioTexto" placeholder="Ex: Envie uma imagem em melhor resolução para aprovarmos o anúncio."></textarea>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalComentario">Cancelar</button>
        <button class="modal-btn primary" type="button" id="btnEnviarComentario">Enviar comentário</button>
      </div>
    </div>
  </div>

  <!-- ======================= MODAL: CONFIRMAR REPROVAÇÃO ======================= -->
  <div class="modal-overlay" id="modalReprovar">
    <div class="modal-box">
      <div class="modal-head">
        <div>
          <h3>Reprovar anúncio?</h3>
        </div>
        <button class="modal-close" type="button" data-close="modalReprovar"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <div class="modal-warning-box">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <span>Esta ação vai reprovar <b id="reprovarNome">o anúncio</b>. O anunciante será notificado. Se preferir apenas pedir um ajuste, use "Enviar comentário" em vez de reprovar.</span>
        </div>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalReprovar">Cancelar</button>
        <button class="modal-btn danger" type="button" id="btnConfirmarReprovar">Sim, reprovar</button>
      </div>
    </div>
  </div>

  <!-- ======================= MODAL: CADASTRAR / EDITAR USUÁRIO ======================= -->
  <div class="modal-overlay" id="modalUsuario">
    <div class="modal-box">
      <div class="modal-head">
        <div>
          <h3 id="userModalTitulo">Novo usuário</h3>
          <div class="modal-sub">Dados do anunciante</div>
        </div>
        <button class="modal-close" type="button" data-close="modalUsuario"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <form id="userForm" autocomplete="off">
          <input type="hidden" id="userId" value="">

          <label for="userNome">Nome completo</label>
          <input type="text" id="userNome" placeholder="Ex: Arthur J. Lima">
          <div class="field-error" id="errNome">Informe o nome completo.</div>

          <label for="userEmail">E-mail</label>
          <input type="email" id="userEmail" placeholder="voce@exemplo.com">
          <div class="field-error" id="errEmail">Informe um e-mail válido.</div>

          <label for="userEmpresa">Nome da empresa/serviço</label>
          <input type="text" id="userEmpresa" placeholder="Ex: Loja do João">

          <label for="userTelefone">Telefone</label>
          <input type="tel" id="userTelefone" placeholder="(99) 99999-9999">

          <label for="userPlano">Plano / nível de acesso</label>
          <select id="userPlano">
            <option value="Básico">Básico</option>
            <option value="Profissional" selected>Profissional</option>
            <option value="Premium">Premium</option>
          </select>
        </form>

        <div class="modal-info-box" id="senhaPadraoAviso">
          <i class="bi bi-info-circle-fill"></i>
          <span>O usuário não define a senha no cadastro. Ele receberá uma <b>senha padrão</b> e será obrigado a alterá-la no primeiro acesso ao painel.</span>
        </div>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalUsuario">Cancelar</button>
        <button class="modal-btn primary" type="button" id="btnSalvarUsuario">Salvar usuário</button>
      </div>
    </div>
  </div>

  <!-- ======================= MODAL: CONFIRMAR DESATIVAÇÃO/REATIVAÇÃO ======================= -->
  <div class="modal-overlay" id="modalStatusUsuario">
    <div class="modal-box">
      <div class="modal-head">
        <div>
          <h3 id="statusModalTitulo">Desativar usuário?</h3>
        </div>
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

  <!-- ======================= MODAL: CADASTRAR / EDITAR PLANO ======================= -->
  <div class="modal-overlay" id="modalPlano">
    <div class="modal-box wide">
      <div class="modal-head">
        <div>
          <h3 id="planoModalTitulo">Novo plano</h3>
          <div class="modal-sub">Dados do plano de assinatura</div>
        </div>
        <button class="modal-close" type="button" data-close="modalPlano"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <form id="planoForm" autocomplete="off">
          <input type="hidden" id="planoId" value="">

          <label for="planoNome">Nome do plano</label>
          <input type="text" id="planoNome" placeholder="Ex: Profissional">
          <div class="field-error" id="errPlanoNome">Informe o nome do plano.</div>

          <label for="planoPreco">Preço (R$)</label>
          <input type="text" id="planoPreco" placeholder="Ex: 119,90">
          <div class="field-error" id="errPlanoPreco">Informe um preço válido.</div>

          <label for="planoDuracao">Duração</label>
          <input type="text" id="planoDuracao" placeholder="Ex: 2 meses">
          <div class="field-error" id="errPlanoDuracao">Informe a duração do plano.</div>

          <label for="planoDescricao">Descrição curta</label>
          <input type="text" id="planoDescricao" placeholder="Ex: Para negócios em crescimento">

          <label>Benefícios</label>
          <div class="feat-editor-list" id="featEditorList"></div>
          <button class="btn-add-feat" type="button" id="btnAddFeat">
            <i class="bi bi-plus-lg"></i> Adicionar benefício
          </button>

          <div class="checkbox-row">
            <input type="checkbox" id="planoDestaque">
            <label for="planoDestaque" style="margin:0;">Marcar como "Mais escolhido"</label>
          </div>
        </form>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalPlano">Cancelar</button>
        <button class="modal-btn primary" type="button" id="btnSalvarPlano">Salvar plano</button>
      </div>
    </div>
  </div>

  <!-- ======================= MODAL: CONFIRMAR REMOÇÃO DE PLANO ======================= -->
  <div class="modal-overlay" id="modalRemoverPlano">
    <div class="modal-box">
      <div class="modal-head">
        <div>
          <h3>Remover plano?</h3>
        </div>
        <button class="modal-close" type="button" data-close="modalRemoverPlano"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <div class="modal-warning-box">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <span>O plano <b id="removerPlanoNome">este plano</b> deixará de ficar disponível para novas contratações. Usuários já assinantes não são afetados automaticamente.</span>
        </div>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalRemoverPlano">Cancelar</button>
        <button class="modal-btn danger" type="button" id="btnConfirmarRemoverPlano">Sim, remover</button>
      </div>
    </div>
  </div>

  <!-- ======================= MODAL: CADASTRAR / EDITAR ANÚNCIO DO ADMIN ======================= -->
  <div class="modal-overlay" id="modalAnuncioAdmin">
    <div class="modal-box wide">
      <div class="modal-head">
        <div>
          <h3 id="anuncioAdminModalTitulo">Novo anúncio</h3>
          <div class="modal-sub">Anúncio institucional / de teste</div>
        </div>
        <button class="modal-close" type="button" data-close="modalAnuncioAdmin"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <form id="anuncioAdminForm" autocomplete="off" action="server/novo_anuncio.php">
          <input type="hidden" id="anuncioAdminId" value="">
          <input type="hidden" id="anuncioAdminImagemUrl" value="">

          <label style="margin-top:0;">Imagem do anúncio (opcional)</label>
          <div class="upload-zone-preview" id="anuncioAdminImgPreview"></div>
          <div class="upload-zone" id="anuncioAdminUploadZone">
            <span class="upicon"><i class="bi bi-cloud-arrow-up"></i></span>
            <div id="anuncioAdminUploadLabel">Clique para enviar a imagem (JPEG, JPG ou PNG). Se nenhuma imagem for enviada, um fundo colorido será usado no lugar.</div>
            <input
              type="file"
              id="anuncioAdminFile"
              accept=".jpg,.jpeg,.png,image/jpeg,image/png"
              style="display:none"
            >
          </div>

          <label for="anuncioAdminNome">Nome do anúncio</label>
          <input type="text" id="anuncioAdminNome" placeholder="Ex: Lojinha do Carro">
          <div class="field-error" id="errAnuncioAdminNome">Informe o nome do anúncio.</div>

          <label for="anuncioAdminTag">Descrição / chamada</label>
          <input type="text" id="anuncioAdminTag" placeholder="Ex: Água, balas e salgadinhos disponíveis a bordo">
          <div class="field-error" id="errAnuncioAdminTag">Informe a descrição do anúncio.</div>

          <label for="anuncioAdminCategoria">Categoria</label>
          <select id="anuncioAdminCategoria">
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
        <button class="modal-btn ghost" type="button" data-close="modalAnuncioAdmin">Cancelar</button>
        <button class="modal-btn primary" type="submit" id="btnSalvarAnuncioAdmin">Salvar anúncio</button>
      </div>
    </div>
  </div>

  <!-- ======================= MODAL: CONFIRMAR EXCLUSÃO DE ANÚNCIO DO ADMIN ======================= -->
  <div class="modal-overlay" id="modalExcluirAnuncioAdmin">
    <div class="modal-box">
      <div class="modal-head">
        <div>
          <h3>Excluir anúncio?</h3>
        </div>
        <button class="modal-close" type="button" data-close="modalExcluirAnuncioAdmin"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <div class="modal-warning-box">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <span>O anúncio <b id="excluirAnuncioAdminNome">este anúncio</b> será removido e deixará de aparecer no carrossel dos tablets.</span>
        </div>
      </div>
      <div class="modal-foot">
        <button class="modal-btn ghost" type="button" data-close="modalExcluirAnuncioAdmin">Cancelar</button>
        <button class="modal-btn danger" type="button" id="btnConfirmarExcluirAnuncioAdmin">Sim, excluir</button>
      </div>
    </div>
  </div>

  <script>
    /* ========================================================================
       GUNESADS — PAINEL ADMINISTRATIVO — LÓGICA
       Sumário:
         1. Navegação entre telas (mesma lógica do painel do usuário)
         2. Sidebar responsiva (idêntica ao painel do usuário)
         3. Menu dropdown do usuário
         4. Atividade recente (dados de exemplo, estrutura pronta para o backend)
         5. Sistema global de alertas (Toasts)
       ==================================================================== */

    /* ---- 1. Navegação entre telas -------------------------------------- */
    const screenTitles = {
      'visao-geral': 'Visão Geral',
      pendentes: 'Anúncios Pendentes',
      usuarios: 'Usuários',
      'planos-admin': 'Planos',
      'anuncios-admin': 'Anúncios do Admin',
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

    /* ---- 2. Sidebar responsiva (mesmo comportamento do painel do usuário) */
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

    /* ---- 3. Menu dropdown do usuário -------------------------------------- */
    const userInfo = document.getElementById('userInfo');
    const userMenu = document.getElementById('userMenu');

    userInfo.addEventListener('click', (e) => {
      e.stopPropagation();
      userMenu.classList.toggle('show');
    });

    document.addEventListener('click', () => {
      userMenu.classList.remove('show');
    });

    /* ---- 4. Atividade recente ---------------------------------------------
       Dados de exemplo — no backend, virá de uma query ordenada por data,
       cada item já no formato abaixo (tipo define ícone/cor). ------------ */
    const ACTIVITIES = [
      { tipo: 'pendente', texto: '<b>Studio Bella Estética</b> enviou um novo anúncio para aprovação', tempo: 'Há 24 min' },
      { tipo: 'usuario', texto: '<b>Combo Lanche + Suco</b> concluiu o cadastro', tempo: 'Há 1h 10min' },
      { tipo: 'pagamento', texto: '<b>Loja do João</b> teve o pagamento confirmado', tempo: 'Há 3h' },
      { tipo: 'expirado', texto: 'A campanha <b>Feira de Setembro</b> expirou', tempo: 'Ontem' },
    ];

    const ACTIVITY_ICONS = {
      pendente: { icon: 'bi-hourglass-split', bg: 'var(--warning-light)', color: 'var(--warning)' },
      usuario:  { icon: 'bi-person-plus-fill', bg: 'var(--primary-light)', color: 'var(--primary)' },
      pagamento:{ icon: 'bi-check-circle-fill', bg: 'var(--success-light)', color: 'var(--success)' },
      expirado: { icon: 'bi-calendar-x-fill', bg: 'var(--danger-light)', color: 'var(--danger)' },
    };

    const activityList = document.getElementById('activityList');

    ACTIVITIES.forEach((item) => {
      const cfg = ACTIVITY_ICONS[item.tipo] || ACTIVITY_ICONS.usuario;

      const el = document.createElement('div');
      el.className = 'activity-item';
      el.innerHTML = `
        <div class="activity-icon" style="background:${cfg.bg}; color:${cfg.color};">
          <i class="bi ${cfg.icon}"></i>
        </div>
        <div class="activity-text">
          <div class="activity-title">${item.texto}</div>
          <div class="activity-time"></div>
        </div>
      `;
      // "texto" contém apenas tags simples de negrito definidas por nós mesmos
      // (não é entrada livre do usuário); "tempo" vem por textContent por segurança.
      el.querySelector('.activity-time').textContent = item.tempo;
      activityList.appendChild(el);
    });

    /* ---- 4.1 Anúncios Pendentes -------------------------------------------
       Dados de exemplo, já na estrutura que deverá vir do backend
       (SELECT ... WHERE status = 'pendente'). As ações de aprovar/reprovar
       aqui só atualizam a UI; no PHP, cada uma deve disparar um UPDATE de
       status (e, no caso do comentário, um INSERT de notificação). ------ */
    let PENDING_ADS = [
      {
        id: 101,
        nome: 'Combo Detox da Semana',
        anunciante: 'Studio Bella Estética',
        email: 'contato@studiobella.com.br',
        duracao: '15 dias',
        dataEnvio: '18/07/2026',
        descricao: 'Divulgação do combo de tratamentos detox com desconto para novas clientes durante o mês de agosto.',
        cor: 'linear-gradient(135deg,#3EC6E0,#135A6B)',
      },
      {
        id: 102,
        nome: 'Rodízio de Pizza — Sexta em Dobro',
        anunciante: 'Pizzaria Dom Vitto',
        email: 'financeiro@domvitto.com.br',
        duracao: '30 dias',
        dataEnvio: '19/07/2026',
        descricao: 'Promoção de rodízio com valor em dobro às sextas-feiras, válida para consumo no salão.',
        cor: 'linear-gradient(135deg,#F3A638,#B9721B)',
      },
      {
        id: 103,
        nome: 'Consultoria Financeira Gratuita',
        anunciante: 'Vértice Consultoria',
        email: 'atendimento@verticeconsult.com.br',
        duracao: '7 dias',
        dataEnvio: '20/07/2026',
        descricao: 'Primeira consultoria financeira gratuita para novos clientes, com agendamento pelo WhatsApp.',
        cor: 'linear-gradient(135deg,#8A8CA5,#565875)',
      },
    ];

    const pendingGrid = document.getElementById('pendingGrid');
    const emptyPending = document.getElementById('emptyPending');

    function renderPendingAds() {
      pendingGrid.innerHTML = '';

      if (PENDING_ADS.length === 0) {
        emptyPending.classList.add('show');
        return;
      }
      emptyPending.classList.remove('show');

      PENDING_ADS.forEach((ad) => {
        const card = document.createElement('div');
        card.className = 'pend-card';
        card.dataset.id = ad.id;

        card.innerHTML = `
          <div class="pend-card-thumb" style="background:${ad.cor};">
            <i class="bi bi-image thumb-fallback"></i>
          </div>
          <div class="pend-card-body">
            <div class="pend-card-head">
              <div class="pend-card-title"></div>
              <div class="pend-card-anunciante"><i class="bi bi-shop"></i><span class="anunciante-nome"></span></div>
            </div>
            <div class="pend-card-meta">
              <span><i class="bi bi-calendar3"></i> ${ad.duracao}</span>
              <span><i class="bi bi-clock-history"></i> Enviado em ${ad.dataEnvio}</span>
            </div>
            <div class="pend-card-actions">
              <button class="pend-btn" type="button" data-action="detalhes" title="Ver detalhes"><i class="bi bi-eye"></i> Detalhes</button>
              <button class="pend-btn icon-only" type="button" data-action="comentario" title="Enviar comentário"><i class="bi bi-chat-left-text"></i></button>
              <button class="pend-btn approve icon-only" type="button" data-action="aprovar" title="Aprovar"><i class="bi bi-check-lg"></i></button>
              <button class="pend-btn reject icon-only" type="button" data-action="reprovar" title="Reprovar"><i class="bi bi-x-lg"></i></button>
            </div>
          </div>
        `;

        // Nome do anúncio e do anunciante via textContent (dados vindos do usuário)
        card.querySelector('.pend-card-title').textContent = ad.nome;
        card.querySelector('.anunciante-nome').textContent = ad.anunciante;

        pendingGrid.appendChild(card);
      });
    }

    renderPendingAds();

    /* ---- 4.2 Sistema de modais (abrir/fechar genérico) -------------------- */
    function abrirModal(id) {
      document.getElementById(id).classList.add('show');
    }

    function fecharModal(id) {
      document.getElementById(id).classList.remove('show');
    }

    document.querySelectorAll('[data-close]').forEach((btn) => {
      btn.addEventListener('click', () => fecharModal(btn.dataset.close));
    });

    // Fecha ao clicar fora da caixa do modal (no overlay)
    document.querySelectorAll('.modal-overlay').forEach((overlay) => {
      overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.classList.remove('show');
      });
    });

    /* ---- 4.3 Ações dos cards (detalhes / comentário / aprovar / reprovar) */
    let anuncioSelecionadoId = null;

    function getAnuncio(id) {
      return PENDING_ADS.find((a) => a.id === Number(id));
    }

    function removerCardDaLista(id) {
      const card = pendingGrid.querySelector(`.pend-card[data-id="${id}"]`);
      if (card) {
        card.classList.add('removing');
        setTimeout(() => {
          PENDING_ADS = PENDING_ADS.filter((a) => a.id !== Number(id));
          renderPendingAds();
        }, 220);
      }
    }

    function aprovarAnuncio(id) {
      const ad = getAnuncio(id);
      if (!ad) return;
      removerCardDaLista(id);
      mostrarAlerta('Anúncio aprovado', `"${ad.nome}" já pode entrar na rotação dos tablets`, 'sucesso');
    }

    function abrirConfirmarReprovacao(id) {
      const ad = getAnuncio(id);
      if (!ad) return;
      anuncioSelecionadoId = id;
      document.getElementById('reprovarNome').textContent = `"${ad.nome}"`;
      abrirModal('modalReprovar');
    }

    function abrirDetalhes(id) {
      const ad = getAnuncio(id);
      if (!ad) return;
      anuncioSelecionadoId = id;

      document.getElementById('detTitulo').textContent = ad.nome;
      document.getElementById('detAnunciante').textContent = ad.anunciante;
      document.getElementById('detDuracao').textContent = ad.duracao;
      document.getElementById('detData').textContent = ad.dataEnvio;
      document.getElementById('detEmail').textContent = ad.email;
      document.getElementById('detDescricao').textContent = ad.descricao;

      const thumb = document.getElementById('detThumb');
      thumb.style.background = ad.cor;
      thumb.textContent = ad.nome;

      abrirModal('modalDetalhes');
    }

    function abrirComentario(id) {
      const ad = getAnuncio(id);
      if (!ad) return;
      anuncioSelecionadoId = id;
      document.getElementById('comAnuncioNome').textContent = `para o anunciante de "${ad.nome}"`;
      document.getElementById('comentarioTexto').value = '';
      abrirModal('modalComentario');
    }

    // Delegação de eventos: um único listener cobre todos os cards, mesmo
    // após re-renderizações (aprovar/reprovar recriam a grade).
    pendingGrid.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-action]');
      if (!btn) return;
      const id = btn.closest('.pend-card').dataset.id;

      if (btn.dataset.action === 'detalhes') abrirDetalhes(id);
      if (btn.dataset.action === 'comentario') abrirComentario(id);
      if (btn.dataset.action === 'aprovar') aprovarAnuncio(id);
      if (btn.dataset.action === 'reprovar') abrirConfirmarReprovacao(id);
    });

    // Ações dentro do modal de Detalhes
    document.getElementById('detBtnAprovar').addEventListener('click', () => {
      fecharModal('modalDetalhes');
      aprovarAnuncio(anuncioSelecionadoId);
    });

    document.getElementById('detBtnReprovar').addEventListener('click', () => {
      fecharModal('modalDetalhes');
      abrirConfirmarReprovacao(anuncioSelecionadoId);
    });

    // Confirmação de reprovação
    document.getElementById('btnConfirmarReprovar').addEventListener('click', () => {
      const ad = getAnuncio(anuncioSelecionadoId);
      fecharModal('modalReprovar');
      if (ad) {
        removerCardDaLista(anuncioSelecionadoId);
        mostrarAlerta('Anúncio reprovado', `"${ad.nome}" foi reprovado e o anunciante será notificado`, 'erro');
      }
    });

    // Envio de comentário ao anunciante
    document.getElementById('btnEnviarComentario').addEventListener('click', () => {
      const texto = document.getElementById('comentarioTexto').value.trim();
      const ad = getAnuncio(anuncioSelecionadoId);

      if (!texto) {
        mostrarAlerta('Escreva uma mensagem', 'Digite o comentário antes de enviar ao anunciante', 'aviso');
        return;
      }

      fecharModal('modalComentario');
      if (ad) {
        mostrarAlerta('Comentário enviado', `O anunciante de "${ad.nome}" foi notificado`, 'info');
      }
    });

    /* ---- 4.4 Usuários -------------------------------------------------------
       ETAPA 11: as linhas já vêm prontas no HTML (no futuro, geradas pelo PHP
       com foreach $usuarios as $usuario — ver comentários no HTML acima). O
       JS não guarda mais uma cópia dos dados em array: ele lê e atualiza
       direto os elementos que já estão na página. ---------------------- */
    const usersList = document.getElementById('usersList');
    const usersCount = document.getElementById('usersCount');

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

    // Atualiza só o número "Total: X usuários", contando as linhas que
    // já existem no DOM (equivalente ao antigo USERS.length).
    function atualizarContadorUsuarios() {
      usersCount.textContent = usersList.querySelectorAll('.user-row').length;
    }
    atualizarContadorUsuarios();

    // Busca a linha pelo id direto no DOM (equivalente ao antigo getUser()).
    function getUserRow(id) {
      return usersList.querySelector(`.user-row[data-id="${id}"]`);
    }

    /* ---- 4.5 Modal de cadastro/edição de usuário -------------------------- */
    const userModalTitulo = document.getElementById('userModalTitulo');
    const senhaPadraoAviso = document.getElementById('senhaPadraoAviso');
    const userForm = document.getElementById('userForm');

    function abrirModalUsuario(modo, id) {
      userForm.reset();
      document.getElementById('errNome').classList.remove('show');
      document.getElementById('errEmail').classList.remove('show');

      if (modo === 'novo') {
        userModalTitulo.textContent = 'Novo usuário';
        senhaPadraoAviso.style.display = 'flex';
        document.getElementById('userId').value = '';
      } else {
        const row = getUserRow(id);
        if (!row) return;
        userModalTitulo.textContent = 'Editar usuário';
        senhaPadraoAviso.style.display = 'none'; // já tem senha própria; não se aplica na edição
        document.getElementById('userId').value = id;
        document.getElementById('userNome').value = row.dataset.nome;
        document.getElementById('userEmail').value = row.dataset.email;
        document.getElementById('userEmpresa').value = row.dataset.empresa;
        document.getElementById('userTelefone').value = row.dataset.telefone;
        document.getElementById('userPlano').value = row.dataset.plano;
      }

      abrirModal('modalUsuario');
    }

    document.getElementById('btnNovoUsuario').addEventListener('click', () => abrirModalUsuario('novo'));

    document.getElementById('btnSalvarUsuario').addEventListener('click', () => {
      const id = document.getElementById('userId').value;
      const nome = document.getElementById('userNome').value.trim();
      const email = document.getElementById('userEmail').value.trim();
      const empresa = document.getElementById('userEmpresa').value.trim();
      const telefone = document.getElementById('userTelefone').value.trim();
      const plano = document.getElementById('userPlano').value;

      let valido = true;
      const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

      document.getElementById('errNome').classList.toggle('show', !nome);
      document.getElementById('errEmail').classList.toggle('show', !emailValido);
      if (!nome || !emailValido) valido = false;

      if (!valido) return;

      if (id) {
        // EDIÇÃO: a linha já existe na tela — atualizamos ela diretamente,
        // só pra dar o feedback visual aqui no protótipo.
        //
        // No PHP real: este botão vira um <form method="post"
        // action="editar_usuario.php"> enviando o "id" num campo hidden.
        // O PHP faz o UPDATE no banco e a página recarrega — nesse momento
        // a linha já aparece atualizada, vinda do foreach.
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
          row.querySelector('.inline-select').value = plano;
        }
        mostrarAlerta('Usuário atualizado', `Os dados de ${nome} foram salvos`, 'sucesso');
      } else {
        // CADASTRO NOVO: aqui, de propósito, NÃO criamos uma linha nova via
        // JS (isso seria o JS "inventando" HTML, que é justamente o que a
        // Etapa 11 pede pra evitar).
        //
        // No PHP real: este botão vira um <form method="post"
        // action="criar_usuario.php">. Ao enviar, o PHP gera a senha
        // padrão, faz o INSERT no banco com status "aguardando" e
        // redireciona de volta pra esta página — o novo usuário já aparece
        // na lista porque vem do foreach, igual aos outros.
        mostrarAlerta('Usuário pronto para salvar', `${nome} vai aparecer na lista e receber a senha padrão assim que o formulário for enviado ao servidor`, 'sucesso');
      }

      fecharModal('modalUsuario');
    });

    /* ---- 4.6 Ativar/desativar usuário -------------------------------------- */
    let usuarioStatusSelecionadoId = null;

    function abrirConfirmarStatus(id) {
      const row = getUserRow(id);
      if (!row) return;
      usuarioStatusSelecionadoId = id;

      const vaiDesativar = row.dataset.status !== 'inativo';
      document.getElementById('statusModalTitulo').textContent = vaiDesativar ? 'Desativar usuário?' : 'Reativar usuário?';
      document.getElementById('statusModalTexto').innerHTML = vaiDesativar
        ? `<i class="bi bi-exclamation-triangle-fill"></i><span><b>${row.dataset.nome}</b> perderá o acesso ao painel até ser reativado. Os anúncios já ativos não são afetados automaticamente.</span>`
        : `<i class="bi bi-info-circle-fill"></i><span><b>${row.dataset.nome}</b> voltará a ter acesso normal ao painel.</span>`;

      abrirModal('modalStatusUsuario');
    }

    document.getElementById('btnConfirmarStatusUsuario').addEventListener('click', () => {
      const row = getUserRow(usuarioStatusSelecionadoId);
      fecharModal('modalStatusUsuario');
      if (!row) return;

      // No PHP real: este botão vira um pequeno <form> que faz um UPDATE de
      // status no banco (ex: action="alternar_status_usuario.php").
      const vaiDesativar = row.dataset.status !== 'inativo';
      const novoStatus = vaiDesativar ? 'inativo' : 'ativo';
      row.dataset.status = novoStatus;
      row.classList.toggle('inactive', novoStatus === 'inativo');

      const st = STATUS_LABEL[novoStatus];
      const badge = row.querySelector('.status-badge');
      badge.className = `status-badge ${novoStatus}`;
      badge.innerHTML = `<i class="bi ${st.icon}"></i> ${st.texto}`;

      const btnStatus = row.querySelector('[data-action="status"]');
      btnStatus.classList.toggle('toggle-on', novoStatus === 'inativo');
      btnStatus.classList.toggle('toggle-off', novoStatus !== 'inativo');
      btnStatus.title = novoStatus === 'inativo' ? 'Reativar' : 'Desativar';
      btnStatus.querySelector('i').className = novoStatus === 'inativo' ? 'bi bi-arrow-counterclockwise' : 'bi bi-slash-circle';

      mostrarAlerta(
        vaiDesativar ? 'Usuário desativado' : 'Usuário reativado',
        `${row.dataset.nome} ${vaiDesativar ? 'não tem mais acesso ao painel' : 'já pode acessar o painel normalmente'}`,
        vaiDesativar ? 'aviso' : 'sucesso'
      );
    });

    // Delegação de eventos da lista de usuários
    usersList.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-action]');
      if (!btn || btn.tagName === 'SELECT') return;
      const id = btn.closest('.user-row').dataset.id;

      if (btn.dataset.action === 'editar') abrirModalUsuario('editar', id);
      if (btn.dataset.action === 'status') abrirConfirmarStatus(id);
    });

    // Troca de plano direto no <select> da linha — no PHP real, isto vira
    // um pequeno form/AJAX que faz UPDATE plano no banco.
    usersList.addEventListener('change', (e) => {
      if (e.target.dataset.action !== 'plano') return;
      const row = e.target.closest('.user-row');
      row.dataset.plano = e.target.value;
      mostrarAlerta('Plano atualizado', `${row.dataset.nome} agora está no plano ${e.target.value}`, 'info');
    });

    /* ---- 4.7 Planos ---------------------------------------------------------
       Dados de exemplo, já refletindo os planos exibidos hoje no painel do
       usuário. No backend, esta lista deve vir de uma tabela "planos" e o
       painel do usuário deve consultar a mesma fonte para exibição
       automática (ver comentário na tela de Planos do usuário). --------- */
    let PLANS = [
      {
        id: 1,
        nome: 'Básico',
        preco: '69,90',
        duracao: '1 mês',
        descricao: 'Para começar a testar',
        beneficios: ['1 anúncio ativo', 'Roda em até 20 carros', 'Relatório semanal'],
        destaque: false,
      },
      {
        id: 2,
        nome: 'Profissional',
        preco: '119,90',
        duracao: '2 meses',
        descricao: 'Para negócios em crescimento',
        beneficios: ['Até 3 anúncios ativos', 'Roda em até 100 carros', 'Escolha de rotas/bairros', 'Relatório em tempo real'],
        destaque: true,
      },
      {
        id: 3,
        nome: 'Premium',
        preco: '169,90',
        duracao: '3 meses',
        descricao: 'Para máxima exposição',
        beneficios: ['Anúncios ilimitados', 'Roda em toda a frota', 'Prioridade de exibição', 'Gerente de conta dedicado'],
        destaque: false,
      },
    ];

    const plansGrid = document.getElementById('plansGrid');

    function renderPlans() {
      plansGrid.innerHTML = '';

      PLANS.forEach((plan) => {
        const card = document.createElement('div');
        card.className = `plan-card${plan.destaque ? ' featured' : ''}`;
        card.dataset.id = plan.id;

        card.innerHTML = `
          ${plan.destaque ? '<div class="plan-tag">MAIS ESCOLHIDO</div>' : ''}
          <div class="plan-name"></div>
          <div class="plan-price"><span class="preco-valor"></span><span class="preco-sufixo"></span></div>
          <div class="plan-desc"></div>
          <ul class="plan-feats"></ul>
          <div class="plan-card-actions">
            <button class="pend-btn" type="button" data-action="editar-plano"><i class="bi bi-pencil"></i> Editar</button>
            <button class="pend-btn reject" type="button" data-action="remover-plano"><i class="bi bi-trash"></i> Remover</button>
          </div>
        `;

        card.querySelector('.plan-name').textContent = plan.nome;
        card.querySelector('.preco-valor').textContent = `R$ ${plan.preco}`;
        card.querySelector('.preco-sufixo').textContent = ` / ${plan.duracao}`;
        card.querySelector('.plan-desc').textContent = plan.descricao;

        const featsList = card.querySelector('.plan-feats');
        plan.beneficios.forEach((b) => {
          const li = document.createElement('li');
          li.textContent = b;
          featsList.appendChild(li);
        });

        plansGrid.appendChild(card);
      });
    }

    renderPlans();

    function getPlan(id) {
      return PLANS.find((p) => p.id === Number(id));
    }

    // Editor dinâmico de benefícios dentro do modal de plano
    const featEditorList = document.getElementById('featEditorList');

    function criarLinhaFeat(valor = '') {
      const row = document.createElement('div');
      row.className = 'feat-editor-row';
      row.innerHTML = `
        <input type="text" class="feat-input" placeholder="Ex: Roda em até 50 carros">
        <button class="feat-remove-btn" type="button" title="Remover benefício"><i class="bi bi-x-lg"></i></button>
      `;
      row.querySelector('.feat-input').value = valor;
      row.querySelector('.feat-remove-btn').addEventListener('click', () => row.remove());
      return row;
    }

    document.getElementById('btnAddFeat').addEventListener('click', () => {
      featEditorList.appendChild(criarLinhaFeat());
    });

    function abrirModalPlano(modo, id) {
      document.getElementById('planoForm').reset();
      document.getElementById('errPlanoNome').classList.remove('show');
      document.getElementById('errPlanoPreco').classList.remove('show');
      document.getElementById('errPlanoDuracao').classList.remove('show');
      featEditorList.innerHTML = '';

      if (modo === 'novo') {
        document.getElementById('planoModalTitulo').textContent = 'Novo plano';
        document.getElementById('planoId').value = '';
        featEditorList.appendChild(criarLinhaFeat());
      } else {
        const plan = getPlan(id);
        if (!plan) return;
        document.getElementById('planoModalTitulo').textContent = 'Editar plano';
        document.getElementById('planoId').value = plan.id;
        document.getElementById('planoNome').value = plan.nome;
        document.getElementById('planoPreco').value = plan.preco;
        document.getElementById('planoDuracao').value = plan.duracao;
        document.getElementById('planoDescricao').value = plan.descricao;
        document.getElementById('planoDestaque').checked = plan.destaque;
        plan.beneficios.forEach((b) => featEditorList.appendChild(criarLinhaFeat(b)));
      }

      abrirModal('modalPlano');
    }

    document.getElementById('btnNovoPlano').addEventListener('click', () => abrirModalPlano('novo'));

    document.getElementById('btnSalvarPlano').addEventListener('click', () => {
      const id = document.getElementById('planoId').value;
      const nome = document.getElementById('planoNome').value.trim();
      const preco = document.getElementById('planoPreco').value.trim();
      const duracao = document.getElementById('planoDuracao').value.trim();
      const descricao = document.getElementById('planoDescricao').value.trim();
      const destaque = document.getElementById('planoDestaque').checked;
      const beneficios = Array.from(featEditorList.querySelectorAll('.feat-input'))
        .map((input) => input.value.trim())
        .filter(Boolean);

      let valido = true;
      document.getElementById('errPlanoNome').classList.toggle('show', !nome);
      document.getElementById('errPlanoPreco').classList.toggle('show', !preco);
      document.getElementById('errPlanoDuracao').classList.toggle('show', !duracao);
      if (!nome || !preco || !duracao) valido = false;

      if (!valido) return;

      // Se este plano for marcado como destaque, os demais deixam de ser
      // (apenas um plano em destaque por vez, igual ao painel do usuário).
      if (destaque) {
        PLANS.forEach((p) => { p.destaque = false; });
      }

      if (id) {
        const plan = getPlan(id);
        Object.assign(plan, { nome, preco, duracao, descricao, beneficios, destaque });
        mostrarAlerta('Plano atualizado', `As alterações em "${nome}" foram salvas`, 'sucesso');
      } else {
        const novoId = PLANS.length ? Math.max(...PLANS.map((p) => p.id)) + 1 : 1;
        PLANS.push({ id: novoId, nome, preco, duracao, descricao, beneficios, destaque });
        mostrarAlerta('Plano criado', `"${nome}" já está disponível para contratação`, 'sucesso');
      }

      fecharModal('modalPlano');
      renderPlans();
    });

    let planoSelecionadoId = null;

    function abrirConfirmarRemoverPlano(id) {
      const plan = getPlan(id);
      if (!plan) return;
      planoSelecionadoId = id;
      document.getElementById('removerPlanoNome').textContent = `"${plan.nome}"`;
      abrirModal('modalRemoverPlano');
    }

    document.getElementById('btnConfirmarRemoverPlano').addEventListener('click', () => {
      const plan = getPlan(planoSelecionadoId);
      fecharModal('modalRemoverPlano');
      if (!plan) return;

      const card = plansGrid.querySelector(`.plan-card[data-id="${plan.id}"]`);
      if (card) {
        card.classList.add('removing');
        setTimeout(() => {
          PLANS = PLANS.filter((p) => p.id !== plan.id);
          renderPlans();
        }, 220);
      }
      mostrarAlerta('Plano removido', `"${plan.nome}" não está mais disponível para novas contratações`, 'erro');
    });

    // Delegação de eventos dos cards de plano (cobre re-renderizações)
    plansGrid.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-action]');
      if (!btn) return;
      const id = btn.closest('.plan-card').dataset.id;

      if (btn.dataset.action === 'editar-plano') abrirModalPlano('editar', id);
      if (btn.dataset.action === 'remover-plano') abrirConfirmarRemoverPlano(id);
    });

    /* ---- 4.8 Anúncios do Admin -----------------------------------------------
       ETAPA 11: os cards já vêm prontos no HTML (no futuro, gerados pelo PHP
       com foreach $anuncios as $anuncio — ver comentários no HTML acima).
       O JS não guarda mais uma cópia dos dados em array: ele lê e atualiza
       direto os elementos que já estão na página. -------------------------- */
    const adminAdsGrid = document.getElementById('adminAdsGrid');
    const emptyAdminAds = document.getElementById('emptyAdminAds');

    // Mostra o estado vazio só se não sobrar nenhum card na grade.
    function atualizarEstadoVazioAdminAds() {
      const temCards = adminAdsGrid.querySelector('.admin-ad-card') !== null;
      emptyAdminAds.classList.toggle('show', !temCards);
    }
    atualizarEstadoVazioAdminAds();

    // Busca o card pelo id direto no DOM (equivalente ao antigo getAdminAd()).
    function getAdminAdCard(id) {
      return adminAdsGrid.querySelector(`.admin-ad-card[data-id="${id}"]`);
    }

    /* ---- Upload de imagem do anúncio do admin (com preview) --------------
       No backend, o mesmo tipo de validação (extensão/mime type) precisa
       ser repetida no servidor — validação no front-end é só conveniência
       para o usuário, nunca segurança. ---------------------------------- */
    const anuncioAdminFile = document.getElementById('anuncioAdminFile');
    const anuncioAdminUploadZone = document.getElementById('anuncioAdminUploadZone');
    const anuncioAdminUploadLabel = document.getElementById('anuncioAdminUploadLabel');
    const anuncioAdminImgPreview = document.getElementById('anuncioAdminImgPreview');
    const anuncioAdminImagemUrl = document.getElementById('anuncioAdminImagemUrl');

    anuncioAdminUploadZone.addEventListener('click', () => anuncioAdminFile.click());

    anuncioAdminFile.addEventListener('change', () => {
      const file = anuncioAdminFile.files[0];
      if (!file) return;

      const allowedTypes = ['image/jpeg', 'image/png'];
      if (!allowedTypes.includes(file.type)) {
        anuncioAdminUploadLabel.innerText = 'Formato inválido. Envie apenas JPEG, JPG ou PNG.';
        anuncioAdminUploadLabel.style.color = 'var(--danger)';
        anuncioAdminFile.value = '';
        return;
      }

      anuncioAdminUploadLabel.style.color = '';
      anuncioAdminUploadLabel.innerText = file.name;

      const objectUrl = URL.createObjectURL(file);
      anuncioAdminImagemUrl.value = objectUrl;
      anuncioAdminImgPreview.style.backgroundImage = `url('${objectUrl}')`;
      anuncioAdminImgPreview.classList.add('show');
    });

    function abrirModalAnuncioAdmin(modo, id) {
      document.getElementById('anuncioAdminForm').reset();
      document.getElementById('errAnuncioAdminNome').classList.remove('show');
      document.getElementById('errAnuncioAdminTag').classList.remove('show');
      anuncioAdminImagemUrl.value = '';
      anuncioAdminImgPreview.classList.remove('show');
      anuncioAdminImgPreview.style.backgroundImage = '';
      anuncioAdminUploadLabel.style.color = '';
      anuncioAdminUploadLabel.innerText = 'Clique para enviar a imagem (JPEG, JPG ou PNG). Se nenhuma imagem for enviada, um fundo colorido será usado no lugar.';

      if (modo === 'novo') {
        document.getElementById('anuncioAdminModalTitulo').textContent = 'Novo anúncio';
        document.getElementById('anuncioAdminId').value = '';
      } else {
        const card = getAdminAdCard(id);
        if (!card) return;
        document.getElementById('anuncioAdminModalTitulo').textContent = 'Editar anúncio';
        document.getElementById('anuncioAdminId').value = id;
        document.getElementById('anuncioAdminNome').value = card.dataset.nome;
        document.getElementById('anuncioAdminTag').value = card.dataset.tag;
        document.getElementById('anuncioAdminCategoria').value = card.dataset.categoria;

        if (card.dataset.imagem) {
          anuncioAdminImagemUrl.value = card.dataset.imagem;
          anuncioAdminImgPreview.style.backgroundImage = `url('${card.dataset.imagem}')`;
          anuncioAdminImgPreview.classList.add('show');
          anuncioAdminUploadLabel.innerText = 'Imagem atual — clique para substituir';
        }
      }

      abrirModal('modalAnuncioAdmin');
    }

    document.getElementById('btnNovoAnuncioAdmin').addEventListener('click', () => abrirModalAnuncioAdmin('novo'));

    document.getElementById('btnSalvarAnuncioAdmin').addEventListener('click', () => {
      const id = document.getElementById('anuncioAdminId').value;
      const nome = document.getElementById('anuncioAdminNome').value.trim();
      const tag = document.getElementById('anuncioAdminTag').value.trim();
      const categoria = document.getElementById('anuncioAdminCategoria').value;
      const imagem = anuncioAdminImagemUrl.value || '';

      let valido = true;
      document.getElementById('errAnuncioAdminNome').classList.toggle('show', !nome);
      document.getElementById('errAnuncioAdminTag').classList.toggle('show', !tag);
      if (!nome || !tag) valido = false;

      if (!valido) return;

      if (id) {
        // EDIÇÃO: o card já existe na tela — atualizamos ele diretamente,
        // só pra dar o feedback visual aqui no protótipo.
        //
        // No PHP real: este botão vira um <form method="post"
        // action="editar_anuncio_admin.php"> enviando o "id" num campo
        // hidden. O PHP faz o UPDATE no banco e a página recarrega — nesse
        // momento o card já aparece atualizado, vindo do foreach.
        const card = getAdminAdCard(id);
        if (card) {
          card.dataset.nome = nome;
          card.dataset.tag = tag;
          card.dataset.categoria = categoria;
          card.querySelector('.admin-ad-card-title').textContent = nome;
          card.querySelector('.cat-text').textContent = categoria;
          card.querySelector('.ad-tag-desc').textContent = tag;

          if (imagem) {
            card.dataset.imagem = imagem;
            const thumb = card.querySelector('.admin-ad-card-thumb');
            thumb.style.backgroundImage = `url('${imagem}')`;
            thumb.style.backgroundSize = 'cover';
            thumb.style.backgroundPosition = 'center';
            const fallbackIcon = thumb.querySelector('.thumb-fallback');
            if (fallbackIcon) fallbackIcon.remove();
          }
        }
        mostrarAlerta('Anúncio atualizado', `As alterações em "${nome}" foram salvas`, 'sucesso');
      } else {
        // CADASTRO NOVO: aqui, de propósito, NÃO criamos um card novo via JS
        // (isso seria o JS "inventando" HTML, que é justamente o que a
        // Etapa 11 pede pra evitar).
        //
        // No PHP real: este botão vira um <form method="post"
        // action="criar_anuncio_admin.php">. Ao enviar, o PHP faz o INSERT
        // no banco e redireciona de volta pra esta página — o novo card já
        // aparece na lista porque vem do foreach, igual aos outros.
        mostrarAlerta('Anúncio pronto para salvar', `"${nome}" vai aparecer na lista assim que o formulário for enviado ao servidor`, 'info');
      }

      fecharModal('modalAnuncioAdmin');
    });

    let anuncioAdminSelecionadoId = null;

    function abrirConfirmarExcluirAnuncioAdmin(id) {
      const card = getAdminAdCard(id);
      if (!card) return;
      anuncioAdminSelecionadoId = id;
      document.getElementById('excluirAnuncioAdminNome').textContent = `"${card.dataset.nome}"`;
      abrirModal('modalExcluirAnuncioAdmin');
    }

    document.getElementById('btnConfirmarExcluirAnuncioAdmin').addEventListener('click', () => {
      const card = getAdminAdCard(anuncioAdminSelecionadoId);
      fecharModal('modalExcluirAnuncioAdmin');
      if (!card) return;

      const nome = card.dataset.nome;

      // No PHP real: o botão de excluir vira um <form method="post"
      // action="excluir_anuncio_admin.php"> (ou um link de confirmação) que
      // apaga a linha no banco (DELETE) e recarrega a página. Aqui no
      // protótipo, só removemos o card da tela pra simular o resultado.
      card.classList.add('removing');
      setTimeout(() => {
        card.remove();
        atualizarEstadoVazioAdminAds();
      }, 220);

      mostrarAlerta('Anúncio excluído', `"${nome}" não aparece mais no carrossel`, 'erro');
    });

    function alternarStatusAnuncioAdmin(id) {
      const card = getAdminAdCard(id);
      if (!card) return;

      // No PHP real: cada botão de pausar/retomar vira um pequeno <form>
      // que faz um UPDATE de status no banco (ex: action="pausar_anuncio_admin.php").
      const ativo = card.dataset.status === 'ativo';
      const novoStatus = ativo ? 'pausado' : 'ativo';
      card.dataset.status = novoStatus;

      const badge = card.querySelector('.badge');
      badge.classList.toggle('ativo', novoStatus === 'ativo');
      badge.classList.toggle('pausado', novoStatus === 'pausado');
      badge.textContent = novoStatus === 'ativo' ? 'Ativo' : 'Pausado';

      const btnPausar = card.querySelector('[data-action="pausar-anuncio-admin"]');
      btnPausar.title = novoStatus === 'ativo' ? 'Pausar' : 'Retomar';
      btnPausar.querySelector('i').className = novoStatus === 'ativo' ? 'bi bi-pause-fill' : 'bi bi-play-fill';

      mostrarAlerta(
        novoStatus === 'ativo' ? 'Anúncio retomado' : 'Anúncio pausado',
        `"${card.dataset.nome}" ${novoStatus === 'ativo' ? 'voltou a entrar na rotação' : 'não entra mais na rotação até ser retomado'}`,
        novoStatus === 'ativo' ? 'sucesso' : 'aviso'
      );
    }

    // Delegação de eventos dos cards de anúncio do admin (cobre re-renderizações)
    adminAdsGrid.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-action]');
      if (!btn) return;
      const id = btn.closest('.admin-ad-card').dataset.id;

      if (btn.dataset.action === 'editar-anuncio-admin') abrirModalAnuncioAdmin('editar', id);
      if (btn.dataset.action === 'pausar-anuncio-admin') alternarStatusAnuncioAdmin(id);
      if (btn.dataset.action === 'excluir-anuncio-admin') abrirConfirmarExcluirAnuncioAdmin(id);
    });

    /* ---- 5. Sistema global de alertas (Toasts) — idêntico ao painel do usuário */
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
