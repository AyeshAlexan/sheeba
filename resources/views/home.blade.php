{{-- @extends('layouts.app') --}}
@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        @media (max-width: 768px) {
    .dash-toggle-btn {
        display: none; /* This would hide your icon on mobile */
    }
}
        /* ══════════════════════════════════════════════════
           DESIGN TOKENS
        ══════════════════════════════════════════════════ */
        :root {
            /* Retheme note: values only — every rule below reads these as
               var(--clr-*), so remapping here carries the sidebar/navbar
               blue-and-navy system (see assets/css/theme-redesign.css)
               through the whole dashboard without touching layout rules. */
            --clr-bg:        #F6F8FC;
            --clr-surface:   #FFFFFF;
            --clr-border:    #E5EAF2;

            --clr-indigo:    #1677FF;
            --clr-indigo-lt: #EAF3FF;
            --clr-violet:    #14213D;
            --clr-emerald:   #16A34A;
            --clr-emerald-lt:#EAFBEF;
            --clr-amber:     #F59E0B;
            --clr-amber-lt:  #FEF6E7;
            --clr-rose:      #EF4444;
            --clr-rose-lt:   #FEECEC;
            --clr-sky:       #0284C7;
            --clr-sky-lt:    #E0F2FE;

            --clr-text-h:    #14213D;
            --clr-text:      #334155;
            --clr-text-m:    #64748B;
            --clr-text-l:    #94A3B8;

            --radius-sm:  8px;
            --radius-md:  14px;
            --radius-lg:  20px;
            --shadow-sm:  0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
            --shadow-md:  0 4px 16px rgba(15,23,42,.08), 0 1px 4px rgba(15,23,42,.05);
            --shadow-lg:  0 12px 40px rgba(15,23,42,.12), 0 2px 8px rgba(15,23,42,.06);

            /* Chart palette */
            --chart-1: #1677FF;
            --chart-2: #16A34A;
            --chart-3: #F59E0B;
            --chart-4: #EF4444;
            --chart-5: #0284C7;
        }

        * { box-sizing: border-box; }
        body { background: var(--clr-bg); }

        /* ══════════════════════════════════════════════════
           TOGGLE BUTTON
        ══════════════════════════════════════════════════ */
        .dash-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--clr-surface);
            border: 1px solid var(--clr-border);
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-sm);
            color: var(--clr-text-m);
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            flex-shrink: 0;
            transition: box-shadow .2s, background .2s, color .2s;
        }
        .dash-toggle-btn:hover {
            background: var(--clr-indigo-lt);
            color: var(--clr-indigo);
            box-shadow: var(--shadow-md);
            border-color: var(--clr-indigo);
        }
        .dash-toggle-btn:active {
            transform: scale(.95);
        }

        /* ══════════════════════════════════════════════════
           PAGE FOOTER
        ══════════════════════════════════════════════════ */
        .dash-footer {
            text-align: center;
            font-size: 12px; color: var(--clr-text-l);
            margin: 8px 0 0;
            padding-bottom: 28px;
        }

        /* ══════════════════════════════════════════════════
           DYNAMIC GREETING HERO
        ══════════════════════════════════════════════════ */
        .hero-greeting {
            position: relative;
            display: flex; align-items: center; gap: 16px;
            border-radius: 18px;
            padding: 20px 28px;
            min-height: 96px;
            overflow: hidden;
            background: linear-gradient(120deg, #EFF5FF 0%, #E3EEFC 55%, #EAF2FE 100%);
            border: 1px solid #E3EDFC;
            box-shadow: var(--shadow-sm);
            transition: background .6s ease;
        }
        .hero-icon-badge {
            width: 48px; height: 48px; border-radius: 14px;
            background: rgba(255,255,255,.75);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(15,23,42,.06);
            position: relative; z-index: 2;
            transition: color .6s ease;
        }
        .hero-text { position: relative; z-index: 2; flex: 1; min-width: 0; }
        .hero-text h2 {
            font-size: 21px; font-weight: 800; margin: 0;
            color: var(--hero-text, var(--clr-text-h));
            display: flex; align-items: center; gap: 8px;
            transition: color .6s ease;
        }
        .hero-text p {
            font-size: 13px; margin: 5px 0 0;
            color: var(--hero-sub, var(--clr-text-m));
            transition: color .6s ease;
        }
        .hero-illustration {
            position: absolute; right: 0; top: 0; bottom: 0;
            width: 320px; height: 100%; pointer-events: none;
            object-fit: cover; object-position: right center;
            -webkit-mask-image: linear-gradient(to right, transparent 0%, #000 22%);
            mask-image: linear-gradient(to right, transparent 0%, #000 22%);
        }
        @media (max-width: 900px) { .hero-illustration { width: 220px; opacity: .9; } }
        @media (max-width: 640px) {
            .hero-illustration { display: none; }
            .hero-greeting { padding: 18px 20px; min-height: auto; }
        }
        @media (prefers-reduced-motion: reduce) {
            .hero-greeting, .hero-icon-badge, .hero-text h2, .hero-text p { transition: none !important; }
        }

        /* Profile card */
        .profile-pill {
            display: flex; align-items: center; gap: 12px;
            background: var(--clr-surface);
            border: 1px solid var(--clr-border);
            border-radius: 50px;
            padding: 6px 18px 6px 6px;
            box-shadow: var(--shadow-sm);
            cursor: pointer;
            transition: box-shadow .2s;
            text-decoration: none;
        }
        .profile-pill:hover { box-shadow: var(--shadow-md); }
        .profile-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, var(--clr-indigo) 0%, var(--clr-violet) 100%);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 15px; color: #fff;
            flex-shrink: 0;
            overflow: hidden;
        }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .profile-info { line-height: 1.3; }
        .profile-name  { font-size: 13px; font-weight: 700; color: var(--clr-text-h); }
        .profile-role  { font-size: 11px; color: var(--clr-text-m); font-weight: 500; }
        .profile-branch-badge {
            background: var(--clr-indigo-lt);
            color: var(--clr-indigo);
            font-size: 10px; font-weight: 700;
            padding: 2px 8px; border-radius: 20px;
            text-transform: uppercase; letter-spacing: .04em;
        }

        /* Breadcrumb date */
        .dash-date {
            font-size: 12px; color: var(--clr-text-m);
            background: var(--clr-surface);
            border: 1px solid var(--clr-border);
            border-radius: 50px;
            padding: 6px 14px;
            box-shadow: var(--shadow-sm);
        }

        /* ══════════════════════════════════════════════════
           STOCK ALERTS
        ══════════════════════════════════════════════════ */
        .sa-card {
            border-radius: var(--radius-lg);
            overflow: hidden;
            margin-bottom: 12px;
            box-shadow: var(--shadow-md);
            transition: opacity .35s ease, transform .35s ease;
        }
        .sa-card.dismissed { opacity: 0; transform: translateY(-10px) scale(.97); pointer-events: none; }
        .sa-header {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 20px;
            cursor: pointer; user-select: none;
        }
        .sa-icon-wrap {
            width: 38px; height: 38px;
            background: rgba(255,255,255,.2);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sa-icon-wrap i { color: #fff; font-size: 16px; }
        .sa-texts { flex: 1; }
        .sa-title  { font-size: 13px; font-weight: 700; color: #fff; margin: 0; }
        .sa-sub    { font-size: 11px; color: rgba(255,255,255,.82); margin: 2px 0 0; }
        .sa-count-pill {
            background: rgba(255,255,255,.22);
            color: #fff; font-size: 11px; font-weight: 700;
            padding: 3px 10px; border-radius: 20px; white-space: nowrap;
        }
        .sa-toggle-btn {
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.3);
            color: #fff; border-radius: 8px;
            padding: 5px 14px; font-size: 12px; font-weight: 600;
            cursor: pointer; white-space: nowrap;
            transition: background .2s;
        }
        .sa-toggle-btn:hover { background: rgba(255,255,255,.32); }
        .sa-dismiss-btn {
            width: 28px; height: 28px;
            background: rgba(255,255,255,.15);
            border: none; border-radius: 50%;
            color: #fff; font-size: 18px; line-height: 1;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: background .2s;
        }
        .sa-dismiss-btn:hover { background: rgba(0,0,0,.2); }
        .sa-panel { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); }
        .sa-panel.open { max-height: 600px; }
        .sa-panel table { font-size: 13px; margin-bottom: 0; }
        .sa-panel thead th {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .05em; background: #FAFBFD;
            padding: 10px 16px; border-bottom: 1px solid var(--clr-border);
            color: var(--clr-text-m);
        }
        .sa-panel tbody td { padding: 10px 16px; vertical-align: middle; color: var(--clr-text); }
        .sa-danger  { background: linear-gradient(135deg, #E11D48 0%, #9F1239 100%); }
        .sa-warning { background: linear-gradient(135deg, #D97706 0%, #92400E 100%); }

        /* ══════════════════════════════════════════════════
           METRIC CARDS (uniform premium stat grid)
        ══════════════════════════════════════════════════ */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .metric-card {
            position: relative;
            background: var(--clr-surface);
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(15,23,42,.05), 0 1px 2px rgba(15,23,42,.03);
            padding: 18px 18px 16px;
            display: flex;
            flex-direction: column;
            min-height: 140px;
            transition: box-shadow .25s, transform .25s;
        }
        .metric-card:hover { box-shadow: 0 8px 24px rgba(15,23,42,.09); transform: translateY(-2px); }
        .metric-icon {
            width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 17px; margin-bottom: 16px;
        }
        .metric-icon.indigo  { background: var(--clr-indigo-lt);  color: var(--clr-indigo); }
        .metric-icon.sky     { background: var(--clr-sky-lt);     color: var(--clr-sky); }
        .metric-icon.violet  { background: #ECEBFB; color: #6C4FD6; }
        .metric-icon.amber   { background: var(--clr-amber-lt);   color: var(--clr-amber); }
        .metric-icon.emerald { background: var(--clr-emerald-lt); color: var(--clr-emerald); }
        .metric-menu-btn {
            position: absolute; top: 16px; right: 16px;
            width: 24px; height: 24px; border: none; background: transparent;
            color: var(--clr-text-l); font-size: 14px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 6px; cursor: pointer;
            transition: background .15s ease, color .15s ease;
        }
        .metric-menu-btn:hover { background: var(--clr-bg); color: var(--clr-text-m); }
        .metric-label { font-size: 13px; font-weight: 500; color: var(--clr-text-m); margin-bottom: 7px; }
        .metric-value { font-size: 21px; font-weight: 800; color: var(--clr-text-h); line-height: 1.2; }
        .metric-trend {
            margin-top: auto; padding-top: 12px;
            font-size: 12.5px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .metric-trend.up   { color: var(--clr-emerald); }
        .metric-trend.down { color: var(--clr-rose); }
        .metric-trend i { font-size: 10px; }

        .btn-stat {
            font-size: 11px; font-weight: 700;
            padding: 7px 14px; border-radius: 8px;
            text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
            transition: all .2s; border: none; cursor: pointer;
        }
        .btn-stat.outline   { background: transparent; color: var(--clr-indigo); border: 1.5px solid var(--clr-indigo); }
        .btn-stat.outline:hover { background: var(--clr-indigo-lt); }

        /* ══════════════════════════════════════════════════
           CHART CARDS
        ══════════════════════════════════════════════════ */
        .chart-card {
            background: var(--clr-surface);
            border: 1px solid var(--clr-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            height: 100%;
            display: flex; flex-direction: column;
        }
        .chart-card-header {
            display: flex; align-items: center;
            justify-content: space-between; flex-wrap: wrap;
            gap: 12px;
            padding: 18px 22px 0;
        }
        .chart-card-title {
            font-size: 15px; font-weight: 800;
            color: var(--clr-text-h); margin: 0;
        }
        .chart-card-body { padding: 16px 22px 20px; flex: 1; }

        /* Period tabs */
        .period-tabs {
            display: flex; gap: 2px;
            background: var(--clr-bg);
            border: 1px solid var(--clr-border);
            border-radius: 10px; padding: 3px;
        }
        .period-tab {
            border: none; background: transparent;
            padding: 5px 13px; border-radius: 7px;
            font-size: 12px; font-weight: 600; color: var(--clr-text-m);
            cursor: pointer; transition: all .2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .period-tab.active {
            background: var(--clr-indigo); color: #fff;
            box-shadow: 0 2px 8px rgba(22,119,255,.3);
        }

        /* chart spinner overlay */
        .chart-wrap { position: relative; }
        .chart-loading {
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,.82); border-radius: var(--radius-lg);
            z-index: 10; transition: opacity .2s;
        }
        .chart-loading.hidden { opacity: 0; pointer-events: none; }

        /* ══════════════════════════════════════════════════
           INVOICE LEGEND (side column, next to donut)
        ══════════════════════════════════════════════════ */
        .inv-chart-row { display: flex; align-items: center; gap: 18px; }
        .inv-chart-row .chart-wrap { flex: 1 1 55%; min-width: 0; }
        .inv-legend-v { flex: 1 1 42%; display: flex; flex-direction: column; gap: 14px; }
        .inv-legend-row { display: flex; align-items: center; gap: 8px; }
        .inv-legend-dot {
            width: 9px; height: 9px; border-radius: 50%;
            display: inline-block; flex-shrink: 0;
        }
        .inv-legend-row.inv-indigo  .inv-legend-dot { background: var(--clr-indigo); }
        .inv-legend-row.inv-emerald .inv-legend-dot { background: var(--clr-emerald); }
        .inv-legend-row.inv-amber   .inv-legend-dot { background: var(--clr-amber); }
        .inv-legend-label { font-size: 13.5px; font-weight: 600; color: var(--clr-text-h); flex: 1; }
        .inv-legend-pct   { font-size: 14px; font-weight: 800; color: var(--clr-text-h); }
        @media (max-width: 480px) { .inv-chart-row { flex-direction: column; } }

        /* ══════════════════════════════════════════════════
           TOP SELLING ITEMS (leaderboard list)
        ══════════════════════════════════════════════════ */
        .top-items-list { display: flex; flex-direction: column; gap: 16px; }
        .ti-row { display: flex; align-items: center; gap: 12px; }
        .ti-rank {
            width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; color: #fff;
        }
        .ti-rank.gold   { background: linear-gradient(135deg, #F7C65B, #E6A535); }
        .ti-rank.silver { background: linear-gradient(135deg, #D4DCE6, #A9B6C5); }
        .ti-rank.bronze { background: linear-gradient(135deg, #DBA06E, #BD7E4C); }
        .ti-rank.plain  { background: var(--clr-bg); color: var(--clr-text-m); font-weight: 700; font-size: 11.5px; }
        .ti-main { flex: 1; min-width: 0; }
        .ti-name {
            font-size: 13px; font-weight: 600; color: var(--clr-text-h);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            margin-bottom: 6px;
        }
        .ti-bar-track { height: 20px; border-radius: 8px; background: var(--clr-bg); overflow: hidden; }
        .ti-bar-fill {
            height: 100%; border-radius: 8px;
            background: linear-gradient(90deg, var(--clr-indigo), #5AA2FF);
            transition: width .6s ease;
        }
        .ti-value { font-size: 12.5px; font-weight: 700; color: var(--clr-text-h); flex-shrink: 0; width: 82px; text-align: right; }

        /* ══════════════════════════════════════════════════
           KPI LIST (Stock History card)
        ══════════════════════════════════════════════════ */
        .kpi-list { display: flex; flex-direction: column; }
        .kpi-row {
            display: flex; align-items: center; justify-content: space-between;
            gap: 10px; padding: 11px 2px;
            border-bottom: 1px solid var(--clr-border);
        }
        .kpi-row:last-child { border-bottom: none; }
        .kpi-label { font-size: 13px; font-weight: 600; color: var(--clr-text-m); flex: 1; }
        .kpi-value { font-size: 21px; font-weight: 800; color: var(--clr-text-h); }
        .kpi-pct {
            display: inline-flex; align-items: center; gap: 3px;
            font-size: 11px; font-weight: 700;
            padding: 3px 9px; border-radius: 20px; white-space: nowrap;
        }
        .kpi-pct.up   { background: var(--clr-emerald-lt); color: var(--clr-emerald); }
        .kpi-pct.down { background: var(--clr-rose-lt);    color: var(--clr-rose); }

        /* Horizontal variant: used when the Stock History card spans full width */
        .kpi-list-row { flex-direction: row; flex-wrap: wrap; }
        .kpi-list-row .kpi-row {
            flex: 1 1 200px;
            flex-direction: column; align-items: flex-start;
            gap: 6px; padding: 10px 20px;
            border-bottom: none;
            border-right: 1px solid var(--clr-border);
        }
        .kpi-list-row .kpi-row:last-child { border-right: none; }
        .kpi-list-row .kpi-label { font-size: 12.5px; }
        @media (max-width: 767px) {
            .kpi-list-row .kpi-row { border-right: none; border-bottom: 1px solid var(--clr-border); flex-basis: 100%; }
            .kpi-list-row .kpi-row:last-child { border-bottom: none; }
        }

        /* ══════════════════════════════════════════════════
           PAYMENT TABLE CARDS
        ══════════════════════════════════════════════════ */
        .pay-card {
            background: var(--clr-surface);
            border: 1px solid var(--clr-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .pay-card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--clr-border);
        }
        .pay-card-title { font-size: 14px; font-weight: 800; color: var(--clr-text-h); margin: 0; }
        .pay-progress-bar {
            height: 6px; border-radius: 10px;
            overflow: hidden; display: flex;
            margin: 14px 20px 8px;
        }
        .pay-progress-bar div { height: 100%; transition: width .4s; }
        .pay-legend {
            display: flex; gap: 12px; flex-wrap: wrap;
            padding: 0 20px 14px;
            border-bottom: 1px solid var(--clr-border);
            font-size: 12px; font-weight: 600; color: var(--clr-text-m);
        }
        .pay-legend span { display: flex; align-items: center; gap: 5px; }
        .pay-legend i { font-size: 8px; }

        /* ══════════════════════════════════════════════════
           INVENTORY TABLE
        ══════════════════════════════════════════════════ */
        .inv-table-card {
            background: var(--clr-surface);
            border: 1px solid var(--clr-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .inv-table-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--clr-border);
        }
        .inv-table-title { font-size: 15px; font-weight: 800; color: var(--clr-text-h); margin: 0; }
        .inv-status-row { display: flex; gap: 6px; }

        /* Chips */
        .chip {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 11px; border-radius: 20px;
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .04em;
        }
        .chip-danger  { background: var(--clr-rose-lt);    color: var(--clr-rose); }
        .chip-warning { background: var(--clr-amber-lt);   color: var(--clr-amber); }
        .chip-ok      { background: var(--clr-emerald-lt); color: var(--clr-emerald); }
        .chip-info    { background: var(--clr-sky-lt);     color: var(--clr-sky); }

        /* Stock needed badge */
        .stock-chip {
            display: inline-flex; align-items: center; gap: 4px;
            background: var(--clr-indigo-lt); color: var(--clr-indigo);
            border-radius: 8px; padding: 4px 10px;
            font-size: 12px; font-weight: 700;
        }

        /* Code cell */
        code.item-code {
            background: var(--clr-bg); color: var(--clr-indigo);
            padding: 2px 8px; border-radius: 6px;
            font-size: 12px; font-family: 'DM Mono', monospace;
        }

        /* Table style */
        .pro-table { width: 100%; border-collapse: collapse; }
        .pro-table thead th {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .06em; color: var(--clr-text-m);
            background: var(--clr-bg); padding: 10px 16px;
            border-bottom: 1px solid var(--clr-border);
        }
        .pro-table tbody td {
            padding: 11px 16px; font-size: 13px; color: var(--clr-text);
            border-bottom: 1px solid var(--clr-border);
            vertical-align: middle;
        }
        .pro-table tbody tr:last-child td { border-bottom: none; }
        .pro-table tbody tr:hover td { background: var(--clr-bg); }
        .pro-table tbody tr.row-danger  td { background: #FFF5F5; }
        .pro-table tbody tr.row-warning td { background: #FFFBEB; }
        .pro-table tbody tr.row-danger:hover  td { background: #FEECEC; }
        .pro-table tbody tr.row-warning:hover td { background: #FEF7E0; }

        /* Inventory table — collapsed to 8 rows by default */
        .inv-row-extra { display: none; }
        .inv-table-card.expanded .inv-row-extra { display: table-row; }
        .inv-table-footer {
            display: flex; justify-content: center;
            padding: 12px 20px;
            border-top: 1px solid var(--clr-border);
        }
        .btn-show-more {
            display: inline-flex; align-items: center; gap: 6px;
            background: transparent;
            border: 1px solid var(--clr-border);
            color: var(--clr-indigo);
            font-size: 13px; font-weight: 700;
            padding: 8px 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: background .2s, border-color .2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-show-more:hover { background: var(--clr-indigo-lt); border-color: var(--clr-indigo); }
        .btn-show-more i { font-size: 11px; transition: transform .2s; }
        .btn-show-more.expanded i { transform: rotate(180deg); }

        /* ══════════════════════════════════════════════════
           ANIMATION & TRANSITIONS

           Cards start hidden (.reveal) and only animate in once JS
           adds .is-visible — triggered either by the page-loader
           finishing (for anything already on screen) or by scrolling
           the card into view (for anything further down the page).
           This keeps the reveal from "burning through" invisibly
           behind the loading-screen overlay.
        ══════════════════════════════════════════════════ */
        @media (prefers-reduced-motion: no-preference) {
            .reveal {
                opacity: 0;
                transform: translateY(18px);
                transition: opacity .55s cubic-bezier(.16,1,.3,1), transform .55s cubic-bezier(.16,1,.3,1);
            }
            .reveal.is-visible { opacity: 1; transform: translateY(0); }

            /* Stagger: elements that land together on first paint fan out slightly */
            .sa-card.reveal:nth-of-type(1) { transition-delay: .03s; }
            .sa-card.reveal:nth-of-type(2) { transition-delay: .07s; }
            .stat-grid .metric-card.reveal:nth-child(1) { transition-delay: .04s; }
            .stat-grid .metric-card.reveal:nth-child(2) { transition-delay: .08s; }
            .stat-grid .metric-card.reveal:nth-child(3) { transition-delay: .12s; }
            .stat-grid .metric-card.reveal:nth-child(4) { transition-delay: .16s; }
            .stat-grid .metric-card.reveal:nth-child(5) { transition-delay: .20s; }

            /* Count-up numbers land instantly under the fade, then tick up */
            .metric-value[data-countup] { font-variant-numeric: tabular-nums; }
        }
        @media (prefers-reduced-motion: reduce) {
            .reveal { opacity: 1 !important; transform: none !important; transition: none !important; }
            .chart-loading, .sa-panel, .sa-card, .metric-card { transition: none !important; animation: none !important; }
        }

        /* Snappier, consistent hover feedback across interactive surfaces */
        .pro-table tbody td { transition: background .15s ease; }
        .chip, .stock-chip { transition: transform .15s ease; }
        .chip:hover { transform: translateY(-1px); }
        .btn-stat { transition: background .15s ease, color .15s ease, transform .1s ease, border-color .15s ease; }
        .btn-stat:active { transform: scale(.97); }
        .sa-toggle-btn, .sa-dismiss-btn { transition: background .15s ease, transform .1s ease; }
        .sa-dismiss-btn:active, .sa-toggle-btn:active { transform: scale(.94); }
        .period-tab { transition: background .15s ease, color .15s ease; }

        /* Spacing utils */
        .mb-20 { margin-bottom: 20px; }
        .mb-24 { margin-bottom: 24px; }
        .row-gap { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px; }
        .col-7-5 { flex: 0 0 calc(58.33% - 10px); max-width: calc(58.33% - 10px); }
        .col-4-5 { flex: 0 0 calc(41.67% - 10px); max-width: calc(41.67% - 10px); }
        .col-full { flex: 0 0 100%; }
        .col-half { flex: 0 0 calc(50% - 10px); max-width: calc(50% - 10px); }
        @media (max-width: 991px) {
            .col-7-5, .col-4-5, .col-half { flex: 0 0 100%; max-width: 100%; }
        }
    </style>
    <style>
    /* Container for positioning */
    .profile-dropdown-wrapper {
        position: relative;
        display: inline-block;
    }

    /* Your existing pill styling with minor adjustments */
    .profile-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 5px 12px;
        background: #fff;
        border-radius: 50px;
        text-decoration: none;
        border: 1px solid #eee;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .profile-pill:hover {
        background: #f9f9f9;
        border-color: #ddd;
    }

    /* Dropdown Menu Box */
    .profile-dropdown-menu {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 220px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.05);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s ease;
        z-index: 1000;
        overflow: hidden;
    }

    /* Show state */
    .profile-dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* Menu Items */
    .profile-dropdown-menu ul {
        list-style: none;
        margin: 0;
        padding: 8px 0;
    }

    .profile-dropdown-menu ul li a,
    .profile-dropdown-menu ul li button {
        width: 100%;
        display: flex;
        align-items: center;
        padding: 10px 20px;
        font-size: 14px;
        color: #444;
        text-decoration: none;
        border: none;
        background: none;
        text-align: left;
        cursor: pointer;
        transition: background 0.2s;
    }

    .profile-dropdown-menu ul li a:hover,
    .profile-dropdown-menu ul li button:hover {
        background: #f4f6f9;
        color: #000;
    }

    .profile-dropdown-menu ul li a i {
        margin-right: 12px;
        font-size: 16px;
        color: #777;
    }

    .dropdown-divider {
        height: 1px;
        background: #eee;
        margin: 8px 0;
    }

    /* Rotate arrow animation */
    .dropdown-arrow {
        transition: transform 0.3s ease;
    }
    .profile-dropdown-menu.show ~ .profile-pill .dropdown-arrow {
        transform: rotate(180deg);
    }
</style>
</head>

<body class="nk-body bg-lighter npc-default has-sidebar no-touch nk-nio-theme">
<x-loading-screen label="Loading dashboard..." />
<div class="main-wrapper">
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- ─── DYNAMIC GREETING HERO ─── --}}
            @php
                $now  = \Carbon\Carbon::now('Asia/Colombo');
                $hour = $now->hour;
                $serverGreeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : ($hour < 21 ? 'Good Evening' : 'Good Night'));
            @endphp
            <div class="hero-greeting reveal mb-24" id="heroGreeting">
                <div class="hero-icon-badge" id="heroIconBadge"><svg id="heroIcon" viewBox="0 0 24 24" width="28" height="28" aria-hidden="true"></svg></div>
                <div class="hero-text">
                    <h2 id="heroGreetingText">{{ $serverGreeting }}</h2>
                    <p>
                        Here's what's happening with your business today &nbsp;·&nbsp;
                        <strong>{{ $now->format('D, d M Y') }}</strong>
                    </p>
                </div>
                <img class="hero-illustration" src="{{ asset('images/home.png') }}" alt="" aria-hidden="true">
            </div>

            {{-- ─── STOCK ALERT BANNERS ─── --}}
            @php
                $oversoldItems = $itemDetails->filter(fn($i) => $i->QTY < 0);
                $lowStockItems = $itemDetails->filter(fn($i) => $i->QTY >= 0 && $i->QTY <= $i->ReorderLevel);
            @endphp

            @if($oversoldItems->count())
            <div class="sa-card reveal mb-20" id="sa-oversold">
                <div class="sa-header sa-danger" onclick="togglePanel('sp-oversold', this)">
                    <div class="sa-icon-wrap"><i class="fas fa-skull-crossbones"></i></div>
                    <div class="sa-texts">
                        <p class="sa-title">Critical Stock Alert</p>
                        <p class="sa-sub">Negative quantity detected — immediate reorder required</p>
                    </div>
                    <span class="sa-count-pill">{{ $oversoldItems->count() }} oversold</span>
                    <button class="sa-toggle-btn" id="btn-oversold">▼ Show</button>
                    <button class="sa-dismiss-btn" onclick="event.stopPropagation(); dismissAlert('sa-oversold')">×</button>
                </div>
                <div class="sa-panel" id="sp-oversold">
                    <div class="table-responsive">
                        <table class="pro-table">
                            <thead><tr>
                                <th>Item Code</th><th>Item Name</th>
                                <th style="text-align:center">Current Stock</th>
                                <th style="text-align:center">Stock Needed</th>
                                <th style="text-align:center">Status</th>
                            </tr></thead>
                            <tbody>
                                @foreach($oversoldItems as $item)
                                <tr class="row-danger">
                                    <td><code class="item-code">{{ $item->Item_code }}</code></td>
                                    <td>{{ $item->Item_description }}</td>
                                    <td style="text-align:center"><strong style="color:var(--clr-rose);">{{ $item->QTY }}</strong></td>
                                    <td style="text-align:center">
                                        <span class="stock-chip"><i class="fas fa-plus" style="font-size:9px;"></i>{{ $item->RecorderQuantitiy - $item->QTY }} units</span>
                                    </td>
                                    <td style="text-align:center"><span class="chip chip-danger">Oversold</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            @if($lowStockItems->count())
            <div class="sa-card reveal mb-20" id="sa-lowstock">
                <div class="sa-header sa-warning" onclick="togglePanel('sp-lowstock', this)">
                    <div class="sa-icon-wrap"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="sa-texts">
                        <p class="sa-title">Low Stock Warning</p>
                        <p class="sa-sub">Items below reorder level — review and replenish soon</p>
                    </div>
                    <span class="sa-count-pill">{{ $lowStockItems->count() }} items</span>
                    <button class="sa-toggle-btn" id="btn-lowstock">▼ Show</button>
                    <button class="sa-dismiss-btn" onclick="event.stopPropagation(); dismissAlert('sa-lowstock')">×</button>
                </div>
                <div class="sa-panel" id="sp-lowstock">
                    <div class="table-responsive">
                        <table class="pro-table">
                            <thead><tr>
                                <th>Item Code</th><th>Item Name</th>
                                <th style="text-align:center">Current Stock</th>
                                <th style="text-align:center">Reorder Level</th>
                                <th style="text-align:center">Stock Needed</th>
                                <th style="text-align:center">Status</th>
                            </tr></thead>
                            <tbody>
                                @foreach($lowStockItems as $item)
                                <tr class="row-warning">
                                    <td><code class="item-code">{{ $item->Item_code }}</code></td>
                                    <td>{{ $item->Item_description }}</td>
                                    <td style="text-align:center"><strong style="color:var(--clr-amber);">{{ $item->QTY }}</strong></td>
                                    <td style="text-align:center">{{ $item->ReorderLevel }}</td>
                                    <td style="text-align:center">
                                        <span class="stock-chip"><i class="fas fa-plus" style="font-size:9px;"></i>{{ $item->RecorderQuantitiy - $item->QTY }} units</span>
                                    </td>
                                    <td style="text-align:center"><span class="chip chip-warning">Low Stock</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- ─── METRIC CARDS ─── --}}
            <div class="stat-grid mb-24">
                @foreach($statCards as $card)
                <div class="metric-card reveal">
                    <button type="button" class="metric-menu-btn" aria-label="More options"><i class="fas fa-ellipsis"></i></button>
                    <div class="metric-icon {{ $card['color'] }}"><i class="fas {{ $card['icon'] }}"></i></div>
                    <div class="metric-label">{{ $card['label'] }}</div>
                    <div class="metric-value" data-countup data-value="{{ $card['value'] }}" data-decimals="{{ $card['decimals'] }}">0</div>
                    <div class="metric-trend {{ $card['pct'] >= 0 ? 'up' : 'down' }}">
                        <i class="fas fa-{{ $card['pct'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                        {{ $card['pct'] >= 0 ? '+' : '' }}{{ $card['pct'] }}% Than Last Month
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ─── CHARTS ROW ─── --}}
            <div class="row mb-24" style="margin-bottom:20px;">
                {{-- Sales & Repair Bar Chart --}}
                <div class="col-xl-7 d-flex mb-3">
                    <div class="chart-card reveal" data-chart="sales" style="width:100%;">
                        <div class="chart-card-header">
                            <h5 class="chart-card-title">
                                <i class="fas fa-chart-column me-2" style="color:var(--clr-indigo);"></i>
                                Sales &amp; Repair Analytics
                            </h5>
                            <div class="period-tabs" id="salesTabs">
                                <button class="period-tab" onclick="changePeriod('sales','daily',this)">Daily</button>
                                <button class="period-tab" onclick="changePeriod('sales','weekly',this)">Weekly</button>
                                <button class="period-tab active" onclick="changePeriod('sales','monthly',this)">Monthly</button>
                                <button class="period-tab" onclick="changePeriod('sales','yearly',this)">Yearly</button>
                            </div>
                        </div>
                        <div class="chart-card-body chart-wrap">
                            <div class="chart-loading hidden" id="sales-loading">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                            <div id="sales_chart"></div>
                        </div>
                    </div>
                </div>

                {{-- Invoice Donut --}}
                <div class="col-xl-5 d-flex mb-3">
                    <div class="chart-card reveal" data-chart="invoice" style="width:100%;">
                        <div class="chart-card-header">
                            <h5 class="chart-card-title">
                                <i class="fas fa-chart-pie me-2" style="color:var(--clr-indigo);"></i>
                                Invoice Analytics
                            </h5>
                            <div class="period-tabs" id="invoiceTabs">
                                <button class="period-tab" onclick="changePeriod('invoice','daily',this)">Daily</button>
                                <button class="period-tab" onclick="changePeriod('invoice','weekly',this)">Weekly</button>
                                <button class="period-tab active" onclick="changePeriod('invoice','monthly',this)">Monthly</button>
                                <button class="period-tab" onclick="changePeriod('invoice','yearly',this)">Yearly</button>
                            </div>
                        </div>
                        <div class="chart-card-body inv-chart-row">
                            <div class="chart-wrap">
                                <div class="chart-loading hidden" id="invoice-loading">
                                    <div class="spinner-border text-primary" role="status"></div>
                                </div>
                                <div id="invoice_chart"></div>
                            </div>
                            <div class="inv-legend-v" id="invoice-totals">
                                <div class="inv-legend-row inv-indigo" title="{{ $invoiceChartData['totals']['sales'] }} invoices">
                                    <span class="inv-legend-dot"></span>
                                    <span class="inv-legend-label">Sales</span>
                                    <span class="inv-legend-pct" id="inv-sales-pct">{{ $invoiceChartData['series'][0] }}%</span>
                                </div>
                                <div class="inv-legend-row inv-emerald" title="{{ $invoiceChartData['totals']['purchases'] }} orders">
                                    <span class="inv-legend-dot"></span>
                                    <span class="inv-legend-label">Purchases</span>
                                    <span class="inv-legend-pct" id="inv-purch-pct">{{ $invoiceChartData['series'][1] }}%</span>
                                </div>
                                <div class="inv-legend-row inv-amber" title="{{ $invoiceChartData['totals']['returns'] }} returns">
                                    <span class="inv-legend-dot"></span>
                                    <span class="inv-legend-label">Returns</span>
                                    <span class="inv-legend-pct" id="inv-returns-pct">{{ $invoiceChartData['series'][2] }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── TOP SELLING ITEMS + PAYMENT SENT VS RECEIVED ─── --}}
            <div class="row mb-24" style="margin-bottom:20px; align-items:flex-start;">
                {{-- Top Selling Items --}}
                <div class="col-xl-5 d-flex mb-3">
                    <div class="chart-card reveal" data-chart="topItems" style="width:100%;">
                        <div class="chart-card-header">
                            <h5 class="chart-card-title">
                                <i class="fas fa-ranking-star me-2" style="color:var(--clr-indigo);"></i>
                                Top Selling Items
                            </h5>
                            <div class="period-tabs" id="topItemsTabs">
                                <button class="period-tab" onclick="changePeriod('topItems','daily',this)">Daily</button>
                                <button class="period-tab" onclick="changePeriod('topItems','weekly',this)">Weekly</button>
                                <button class="period-tab active" onclick="changePeriod('topItems','monthly',this)">Monthly</button>
                                <button class="period-tab" onclick="changePeriod('topItems','yearly',this)">Yearly</button>
                            </div>
                        </div>
                        <div class="chart-card-body chart-wrap pt-2">
                            <div class="chart-loading hidden" id="topItems-loading">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                            <div class="top-items-list" id="top_items_list"></div>
                        </div>
                    </div>
                </div>

                {{-- Right column: Payment trend (top) + Stock History (bottom), stacked --}}
                <div class="col-xl-7 mb-3" style="display:flex; flex-direction:column; gap:20px;">
                    <div class="chart-card reveal" data-chart="paymentTrend">
                        <div class="chart-card-header">
                            <h5 class="chart-card-title">
                                <i class="fas fa-arrow-right-arrow-left me-2" style="color:var(--clr-indigo);"></i>
                                Payment Sent vs Received
                            </h5>
                            <div class="period-tabs" id="paymentTrendTabs">
                                <button class="period-tab" onclick="changePeriod('paymentTrend','daily',this)">Daily</button>
                                <button class="period-tab active" onclick="changePeriod('paymentTrend','weekly',this)">Weekly</button>
                                <button class="period-tab" onclick="changePeriod('paymentTrend','monthly',this)">Monthly</button>
                                <button class="period-tab" onclick="changePeriod('paymentTrend','yearly',this)">Yearly</button>
                            </div>
                        </div>
                        <div class="chart-card-body chart-wrap">
                            <div class="chart-loading hidden" id="paymentTrend-loading">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                            <div id="payment_trend_chart"></div>
                        </div>
                    </div>

                    <div class="chart-card reveal">
                        <div class="chart-card-header">
                            <h5 class="chart-card-title">
                                <i class="fas fa-boxes-stacked me-2" style="color:var(--clr-text-m);"></i>
                                Stock History
                            </h5>
                            <span style="font-size:11px;color:var(--clr-text-l);font-weight:600;">This month</span>
                        </div>
                        <div class="chart-card-body" style="padding-top:4px;">
                            <div class="kpi-list">
                                @foreach($stockHistory as $row)
                                <div class="kpi-row">
                                    <span class="kpi-label">{{ $row['label'] }}</span>
                                    <span class="kpi-value">{{ number_format($row['value']) }}</span>
                                    <span class="kpi-pct {{ $row['pct'] >= 0 ? 'up' : 'down' }}">
                                        {{ $row['pct'] >= 0 ? '↑' : '↓' }} {{ abs($row['pct']) }}%
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── PAYMENT TABLES ─── --}}
            @php
                $invStatusTotal = max(array_sum($invoiceStatusCounts), 1);
                $invPaidPct     = round($invoiceStatusCounts['Paid']     / $invStatusTotal * 100);
                $invUnpaidPct   = round($invoiceStatusCounts['Unpaid']   / $invStatusTotal * 100);
                $invReturnedPct = round($invoiceStatusCounts['Returned'] / $invStatusTotal * 100);
                $invOverduePct  = max(100 - $invPaidPct - $invUnpaidPct - $invReturnedPct, 0);

                $methodTotal = max(array_sum($paymentMethodTotals), 1);
                $methodPct = collect($paymentMethodTotals)->map(fn($v) => round($v / $methodTotal * 100));
            @endphp
            <div class="row mb-24" style="margin-bottom:20px;">
                <div class="col-md-6 col-sm-6 mb-3">
                    <div class="pay-card reveal">
                        <div class="pay-card-header">
                            <h5 class="pay-card-title"><i class="fas fa-receipt me-2" style="color:var(--clr-indigo);"></i>Recent Sales Invoices</h5>
                            <a href="{{ route('sales_report') }}" target="_blank" class="btn-stat outline" style="font-size:11px;padding:5px 12px;">View All</a>
                        </div>
                        <div class="pay-progress-bar">
                            <div style="width:{{ $invPaidPct }}%;background:var(--clr-emerald);border-radius:10px 0 0 10px;"></div>
                            <div style="width:{{ $invUnpaidPct }}%;background:var(--clr-amber);"></div>
                            <div style="width:{{ $invOverduePct }}%;background:var(--clr-rose);"></div>
                            <div style="width:{{ $invReturnedPct }}%;background:var(--clr-sky);border-radius:0 10px 10px 0;"></div>
                        </div>
                        <div class="pay-legend">
                            <span><i class="fas fa-circle" style="color:var(--clr-emerald);font-size:8px;"></i>Paid</span>
                            <span><i class="fas fa-circle" style="color:var(--clr-amber);font-size:8px;"></i>Unpaid</span>
                            <span><i class="fas fa-circle" style="color:var(--clr-rose);font-size:8px;"></i>Overdue</span>
                            <span><i class="fas fa-circle" style="color:var(--clr-sky);font-size:8px;"></i>Returned</span>
                        </div>
                        <div class="table-responsive">
                            <table class="pro-table">
                                <thead><tr>
                                    <th>Invoice No</th><th>Customer</th><th>Date</th>
                                    <th>Amount</th><th>Status</th>
                                </tr></thead>
                                <tbody>
                                    @forelse($recentInvoices as $inv)
                                    <tr>
                                        <td><code class="item-code">{{ $inv->Invoice_no }}</code></td>
                                        <td>{{ $inv->Customer_Name ?: '—' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($inv->Invoice_date)->format('d M Y') }}</td>
                                        <td>{{ number_format($inv->Net_Amount, 2) }}</td>
                                        <td>
                                            @if($inv->computed_status === 'Returned')
                                                <span class="chip chip-info">Returned</span>
                                            @elseif($inv->computed_status === 'Paid')
                                                <span class="chip chip-ok">Paid</span>
                                            @elseif($inv->computed_status === 'Overdue')
                                                <span class="chip chip-danger">Overdue</span>
                                            @else
                                                <span class="chip chip-warning">Unpaid</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" style="text-align:center;color:var(--clr-text-l);">No invoices yet</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 mb-3">
                    <div class="pay-card reveal">
                        <div class="pay-card-header">
                            <h5 class="pay-card-title"><i class="fas fa-money-bill-wave me-2" style="color:var(--clr-emerald);"></i>Recent Customer Payments</h5>
                            <a href="{{ route('customer_payment_report') }}" target="_blank" class="btn-stat outline" style="font-size:11px;padding:5px 12px;">View All</a>
                        </div>
                        <div class="pay-progress-bar">
                            <div style="width:{{ $methodPct['Cash'] }}%;background:var(--clr-emerald);border-radius:10px 0 0 10px;"></div>
                            <div style="width:{{ $methodPct['Card'] }}%;background:var(--clr-indigo);"></div>
                            <div style="width:{{ $methodPct['Cheque'] }}%;background:var(--clr-amber);"></div>
                            <div style="width:{{ $methodPct['Bank'] }}%;background:var(--clr-sky);border-radius:0 10px 10px 0;"></div>
                        </div>
                        <div class="pay-legend">
                            <span><i class="fas fa-circle" style="color:var(--clr-emerald);font-size:8px;"></i>Cash</span>
                            <span><i class="fas fa-circle" style="color:var(--clr-indigo);font-size:8px;"></i>Card</span>
                            <span><i class="fas fa-circle" style="color:var(--clr-amber);font-size:8px;"></i>Cheque</span>
                            <span><i class="fas fa-circle" style="color:var(--clr-sky);font-size:8px;"></i>Bank</span>
                        </div>
                        <div class="table-responsive">
                            <table class="pro-table">
                                <thead><tr>
                                    <th>Payment No</th><th>Customer</th><th>Date</th>
                                    <th>Amount</th><th>Method</th>
                                </tr></thead>
                                <tbody>
                                    @forelse($recentPayments as $p)
                                    <tr>
                                        <td><code class="item-code">{{ $p->Payment_no }}</code></td>
                                        <td>{{ $p->Customer_Name ?: '—' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($p->Payment_date)->format('d M Y') }}</td>
                                        <td>{{ number_format($p->Payment_Amount, 2) }}</td>
                                        <td><span class="chip chip-ok">{{ $p->primary_method }}</span></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" style="text-align:center;color:var(--clr-text-l);">No payments yet</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── INVENTORY TABLE ─── --}}
            <div class="mb-24">
                <div class="inv-table-card reveal">
                    <div class="inv-table-header">
                        <h5 class="inv-table-title">
                            <i class="fas fa-boxes me-2" style="color:var(--clr-text-m);"></i>Inventory Details
                        </h5>
                        <div class="inv-status-row">
                            <span class="chip chip-danger">{{ $oversoldItems->count() }} oversold</span>
                            <span class="chip chip-warning">{{ $lowStockItems->count() }} low</span>
                            <span class="chip chip-ok">
                                {{ $itemDetails->where('QTY', '>', 0)->filter(fn($i) => $i->QTY > $i->ReorderLevel)->count() }} ok
                            </span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="pro-table">
                            <thead><tr>
                                <th>Item Code</th><th>Description</th>
                                <th style="text-align:center">Current QTY</th>
                                <th style="text-align:center">Reorder Level</th>
                                <th style="text-align:center">Reorder QTY</th>
                                <th style="text-align:center">Stock Needed</th>
                                <th style="text-align:center">Status</th>
                            </tr></thead>
                            <tbody>
                                @foreach($itemDetails as $item)
                                <tr class="{{ $item->QTY < 0 ? 'row-danger' : ($item->QTY <= $item->ReorderLevel ? 'row-warning' : '') }} {{ $loop->index >= 8 ? 'inv-row-extra' : '' }}">
                                    <td><code class="item-code">{{ $item->Item_code }}</code></td>
                                    <td>{{ $item->Item_description }}</td>
                                    <td style="text-align:center">
                                        @if($item->QTY < 0)
                                            <strong style="color:var(--clr-rose);">{{ $item->QTY }}</strong>
                                        @elseif($item->QTY <= $item->ReorderLevel)
                                            <strong style="color:var(--clr-amber);">{{ $item->QTY }}</strong>
                                        @else
                                            <strong style="color:var(--clr-emerald);">{{ $item->QTY }}</strong>
                                        @endif
                                    </td>
                                    <td style="text-align:center">{{ $item->ReorderLevel }}</td>
                                    <td style="text-align:center">{{ $item->RecorderQuantitiy }}</td>
                                    <td style="text-align:center">
                                        @if($item->QTY < $item->RecorderQuantitiy)
                                            <span class="stock-chip">
                                                <i class="fas fa-plus" style="font-size:9px;"></i>
                                                {{ $item->RecorderQuantitiy - $item->QTY }} units
                                            </span>
                                        @else
                                            <span style="color:var(--clr-text-l);font-size:13px;">—</span>
                                        @endif
                                    </td>
                                    <td style="text-align:center">
                                        @if($item->QTY < 0)
                                            <span class="chip chip-danger">Oversold</span>
                                        @elseif($item->QTY <= $item->ReorderLevel)
                                            <span class="chip chip-warning">Low Stock</span>
                                        @else
                                            <span class="chip chip-ok">OK</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($itemDetails->count() > 8)
                    <div class="inv-table-footer">
                        <button type="button" class="btn-show-more" id="inv-toggle-btn" onclick="toggleInventoryRows()">
                            <span id="inv-toggle-label">Show {{ $itemDetails->count() - 8 }} more</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <p class="dash-footer">© {{ date('Y') }} Kreethya . All Rights Reserved. Designed by Kreethya</p>

        </div>{{-- /content --}}
    </div>
</div>

{{-- ════ Scripts ════ --}}
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.min.js"></script>

<script>
// ─── Server-rendered initial data ───
const SALES_INIT         = @json($salesChartData);
const INVOICE_INIT       = @json($invoiceChartData);
const TOP_ITEMS_INIT     = @json($topItemsData);
const PAYMENT_TREND_INIT = @json($paymentTrendData);
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ─── Chart palette ───
const PALETTE = ['#1677FF','#16A34A','#F59E0B','#EF4444','#0284C7'];

let salesChart, invoiceChart, paymentTrendChart;

// ─── 1. Sales & Repair BAR chart ───
function buildSalesChart(data) {
    const allValues = [...data.received, ...data.pending, ...data.returns].map(Number);
    const dataMax = Math.max(...allValues, 0);
    const yMax = dataMax > 0 ? Math.ceil(dataMax * 1.25 / 10) * 10 : 10;

    const opts = {
        chart: {
            type: 'bar', height: 300, toolbar: { show: false },
            fontFamily: "'Plus Jakarta Sans', sans-serif",
            animations: { enabled: true, speed: 500 },
            background: 'transparent',
        },
        series: [
            { name: 'Sales',     data: data.received },
            { name: 'Purchases', data: data.pending  },
            { name: 'Returns',   data: data.returns  },
        ],
        xaxis: {
            categories: data.categories,
            labels: { style: { fontSize: '11px', fontWeight: 600, colors: '#94A3B8' } },
            axisBorder: { show: false }, axisTicks: { show: false },
        },
        yaxis: {
            max: yMax,
            forceNiceScale: false,
            labels: {
                formatter: v => v >= 1000 ? (v/1000).toFixed(0)+'k' : v,
                style: { fontSize: '11px', colors: '#94A3B8' }
            }
        },
        colors: [PALETTE[0], PALETTE[1], PALETTE[2]],
        plotOptions: {
            bar: {
                borderRadius: 6,
                borderRadiusApplication: 'end',
                columnWidth: '55%',
                dataLabels: { position: 'top' },
            }
        },
        dataLabels: { enabled: false },
        legend: {
            position: 'bottom', fontSize: '12px', fontWeight: 600,
            markers: { radius: 6 },
            labels: { colors: '#64748B' }
        },
        grid: {
            borderColor: '#EEF1F7', strokeDashArray: 3,
            xaxis: { lines: { show: false } },
            padding: { top: -10 }
        },
        tooltip: {
            theme: 'light',
            y: { formatter: v => Number(v).toLocaleString() },
            style: { fontSize: '12px', fontFamily: "'Plus Jakarta Sans', sans-serif" }
        },
        fill: { type: 'gradient', gradient: { shade: 'light', type: 'vertical', shadeIntensity: 0.4, opacityFrom: 1, opacityTo: 0.9 } },
    };

    if (salesChart) { salesChart.updateOptions(opts); return; }
    salesChart = new ApexCharts(document.querySelector('#sales_chart'), opts);
    salesChart.render();
}

// ─── 2. Invoice DONUT chart ───
function buildInvoiceChart(data) {
    const opts = {
        chart: {
            type: 'donut', height: 230, offsetY: 6,
            fontFamily: "'Plus Jakarta Sans', sans-serif",
            animations: { enabled: true, speed: 500 },
        },
        series: data.series,
        labels: data.labels,
        colors: [PALETTE[0], PALETTE[1], PALETTE[2]],
        plotOptions: {
            pie: {
                offsetY: 4,
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        name: { fontSize: '12px', fontWeight: 600, color: '#64748B', offsetY: -4 },
                        value: {
                            fontSize: '24px', fontWeight: 800, color: '#14213D', offsetY: 4,
                            formatter: v => v + '%'
                        },
                        total: {
                            show: true, label: 'Total',
                            fontSize: '12px', fontWeight: 700, color: '#64748B',
                            formatter: () => '100%'
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            offset: -22,
            formatter: v => Math.round(v) + '%',
            style: { fontSize: '11px', fontWeight: 700, colors: ['#fff'] },
            dropShadow: { enabled: false },
        },
        legend: { show: false },
        stroke: { width: 3, colors: ['#fff'] },
        fill: { type: 'solid' },
        states: { hover: { filter: { type: 'darken', value: 0.95 } } },
        tooltip: {
            theme: 'light',
            fillSeriesColor: false,
            marker: { show: true },
            y: { formatter: v => v + '%' },
            style: { fontSize: '12px', fontFamily: "'Plus Jakarta Sans', sans-serif" }
        },
    };

    if (invoiceChart) { invoiceChart.updateOptions(opts); return; }
    invoiceChart = new ApexCharts(document.querySelector('#invoice_chart'), opts);
    invoiceChart.render();

    document.getElementById('inv-sales-pct').textContent = data.series[0] + '%';
    document.getElementById('inv-purch-pct').textContent = data.series[1] + '%';
    document.getElementById('inv-returns-pct').textContent = data.series[2] + '%';
}

// ─── 3. Top Selling Items (HTML leaderboard list) ───
function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

function buildTopItemsList(data) {
    const container = document.getElementById('top_items_list');
    if (!container) return;

    const revenues = data.revenue.map(Number);
    const max = Math.max(...revenues, 1);

    container.innerHTML = data.categories.map((name, i) => {
        const pct = Math.max((revenues[i] / max) * 100, 4);
        const safeName = escapeHtml(name);

        return `<div class="ti-row">
            <div class="ti-rank plain">#${i + 1}</div>
            <div class="ti-main">
                <div class="ti-name" title="${safeName}"><i class="fas fa-box" style="color:var(--clr-indigo); font-size:11px; margin-right:6px;"></i>${safeName}</div>
                <div class="ti-bar-track"><div class="ti-bar-fill" style="width:${pct}%"></div></div>
            </div>
            <div class="ti-value">${revenues[i].toLocaleString()}</div>
        </div>`;
    }).join('');
}

// ─── 4. Payment Sent vs Received (line) ───
function buildPaymentTrendChart(data) {
    const opts = {
        chart: {
            type: 'area', height: 230, toolbar: { show: false },
            fontFamily: "'Plus Jakarta Sans', sans-serif",
            animations: { enabled: true, speed: 600, easing: 'easeinout' },
            background: 'transparent',
            dropShadow: { enabled: true, top: 6, left: 0, blur: 6, color: '#1677FF', opacity: 0.08 },
        },
        series: [
            { name: 'Payment Sent',     data: data.sent },
            { name: 'Payment Received', data: data.received },
        ],
        xaxis: {
            categories: data.categories,
            labels: { style: { fontSize: '11px', fontWeight: 600, colors: '#94A3B8' } },
            axisBorder: { show: false }, axisTicks: { show: false },
        },
        yaxis: {
            labels: {
                formatter: v => v >= 1000 ? (v/1000).toFixed(0)+'k' : v,
                style: { fontSize: '11px', colors: '#94A3B8' }
            }
        },
        colors: [PALETTE[2], PALETTE[1]],
        stroke: { curve: 'smooth', width: 3, lineCap: 'round' },
        markers: {
            size: 0, hover: { size: 6, sizeOffset: 2 },
            strokeWidth: 2, strokeColors: '#fff',
        },
        legend: {
            position: 'top', horizontalAlign: 'right',
            fontSize: '12px', fontWeight: 600,
            markers: { radius: 6 }, labels: { colors: '#64748B' },
            itemMargin: { horizontal: 10 },
        },
        grid: { borderColor: '#F0F3F9', strokeDashArray: 4, xaxis: { lines: { show: false } }, padding: { top: -16 } },
        tooltip: {
            shared: true, intersect: false, theme: 'light',
            y: { formatter: v => Number(v).toLocaleString() },
            style: { fontSize: '12px', fontFamily: "'Plus Jakarta Sans', sans-serif" }
        },
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02, stops: [0, 95] },
        },
    };

    if (paymentTrendChart) {
        paymentTrendChart.updateOptions({ series: opts.series, xaxis: opts.xaxis });
        return;
    }
    paymentTrendChart = new ApexCharts(document.querySelector('#payment_trend_chart'), opts);
    paymentTrendChart.render();
}

// ─── Period switcher ───
const tabGroups = { sales: 'salesTabs', invoice: 'invoiceTabs', topItems: 'topItemsTabs', paymentTrend: 'paymentTrendTabs' };

function changePeriod(chart, period, btn) {
    document.querySelectorAll(`#${tabGroups[chart]} .period-tab`)
        .forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const loader = document.getElementById(chart + '-loading');
    if (loader) loader.classList.remove('hidden');

    const urls = {
        sales:        '{{ route("dashboard.salesChartData") }}',
        invoice:      '{{ route("dashboard.invoiceChartData") }}',
        topItems:     '{{ route("dashboard.topItemsData") }}',
        paymentTrend: '{{ route("dashboard.paymentTrendData") }}',
    };

    fetch(`${urls[chart]}?period=${period}`, {
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (chart === 'sales')       buildSalesChart(data);
        if (chart === 'invoice')     buildInvoiceChart(data);
        if (chart === 'topItems')     buildTopItemsList(data);
        if (chart === 'paymentTrend') buildPaymentTrendChart(data);
    })
    .catch(console.error)
    .finally(() => { if (loader) loader.classList.add('hidden'); });
}

// ─── Alert toggle & dismiss ───
function togglePanel(panelId, headerEl) {
    const panel = document.getElementById(panelId);
    const btn   = headerEl.querySelector('.sa-toggle-btn');
    const open  = panel.classList.contains('open');
    panel.classList.toggle('open', !open);
    if (btn) btn.textContent = open ? '▼ Show' : '▲ Hide';
}

function dismissAlert(cardId) {
    const card = document.getElementById(cardId);
    card.classList.add('dismissed');
    setTimeout(() => card.remove(), 380);
}

// ─── Inventory table show more/less ───
function toggleInventoryRows() {
    const card  = document.querySelector('.inv-table-card');
    const btn   = document.getElementById('inv-toggle-btn');
    const label = document.getElementById('inv-toggle-label');
    const extraCount = document.querySelectorAll('.inv-row-extra').length;
    const expanded = card.classList.toggle('expanded');
    btn.classList.toggle('expanded', expanded);
    label.textContent = expanded ? 'Show less' : `Show ${extraCount} more`;
}

// ─── Hero number count-up ───
function animateCountUp(el) {
    const target   = parseFloat(el.dataset.value) || 0;
    const decimals = parseInt(el.dataset.decimals ?? '0', 10);
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (reduceMotion) {
        el.textContent = target.toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
        return;
    }

    const duration = 900;
    const start    = performance.now();
    const easeOutQuad = t => t * (2 - t);

    function tick(now) {
        const progress = Math.min((now - start) / duration, 1);
        const value = target * easeOutQuad(progress);
        el.textContent = value.toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
        if (progress < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
}

// ─── Dynamic greeting hero (morning / afternoon / evening / night) ───
const HERO_THEMES = {
    morning: {
        greeting: 'Good Morning', iconColor: '#F59E0B',
        bg: 'linear-gradient(120deg, #EFF5FF 0%, #E3EEFC 55%, #EAF2FE 100%)',
        textColor: '#14213D', subColor: '#4A5A78',
        // Sunburst with yellow rays, blue stripes
        iconSvg: `
            <g stroke="#F2C94C" stroke-width="2.1" stroke-linecap="round">
                <line x1="4.75" y1="12.06" x2="1.86" y2="11.28"/>
                <line x1="6.25" y1="9.18"  x2="3.96" y2="7.25"/>
                <line x1="8.83" y1="7.21"  x2="7.56" y2="4.49"/>
                <line x1="12"   y1="6.5"   x2="12"   y2="3.5"/>
                <line x1="15.17" y1="7.21" x2="16.44" y2="4.49"/>
                <line x1="17.75" y1="9.18" x2="20.04" y2="7.25"/>
                <line x1="19.25" y1="12.06" x2="22.14" y2="11.28"/>
            </g>
            <path d="M6,14 A6,6 0 0 1 18,14 Z" fill="#F6D76B"/>
            <rect x="5"   y="15.3" width="14" height="1.6" rx="0.8" fill="#9CC6F2"/>
            <rect x="6.5" y="17.6" width="11" height="1.4" rx="0.7" fill="#5B9AE8"/>
            <rect x="9"   y="19.6" width="6"  height="1.3" rx="0.65" fill="#2F63C9"/>
        `,
    },
    afternoon: {
        greeting: 'Good Afternoon', iconColor: '#F5A623',
        bg: 'linear-gradient(120deg, #EAF4FF 0%, #D9EAFB 100%)',
        textColor: '#14213D', subColor: '#4A5A78',
        // Full sun, high in the sky
        iconSvg: `
            <circle cx="12" cy="12" r="4.2" fill="currentColor"/>
            <line x1="18.5" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <line x1="16.6" y1="16.6" x2="18.4" y2="18.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <line x1="12" y1="18.5" x2="12" y2="21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <line x1="7.4" y1="16.6" x2="5.6" y2="18.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <line x1="5.5" y1="12" x2="3" y2="12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <line x1="7.4" y1="7.4" x2="5.6" y2="5.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <line x1="12" y1="5.5" x2="12" y2="3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <line x1="16.6" y1="7.4" x2="18.4" y2="5.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        `,
    },
    evening: {
        greeting: 'Good Evening', iconColor: '#EF7A54',
        bg: 'linear-gradient(120deg, #FDEFE8 0%, #F8E2E8 55%, #EFE6F7 100%)',
        textColor: '#432A2A', subColor: '#7A4F52',
        // Sunburst with warm orange/red rays and stripes
        iconSvg: `
            <g stroke="#F0A868" stroke-width="2.1" stroke-linecap="round">
                <line x1="4.75" y1="12.06" x2="1.86" y2="11.28"/>
                <line x1="6.25" y1="9.18"  x2="3.96" y2="7.25"/>
                <line x1="8.83" y1="7.21"  x2="7.56" y2="4.49"/>
                <line x1="12"   y1="6.5"   x2="12"   y2="3.5"/>
                <line x1="15.17" y1="7.21" x2="16.44" y2="4.49"/>
                <line x1="17.75" y1="9.18" x2="20.04" y2="7.25"/>
                <line x1="19.25" y1="12.06" x2="22.14" y2="11.28"/>
            </g>
            <path d="M6,14 A6,6 0 0 1 18,14 Z" fill="#F6D76B"/>
            <rect x="5"   y="15.3" width="14" height="1.6" rx="0.8" fill="#EDD86B"/>
            <rect x="6.5" y="17.6" width="11" height="1.4" rx="0.7" fill="#F0995F"/>
            <rect x="9"   y="19.6" width="6"  height="1.3" rx="0.65" fill="#E65C49"/>
        `,
    },
    night: {
        greeting: 'Good Night', iconColor: '#4C5A8C',
        bg: 'linear-gradient(120deg, #EAF4FF 0%, #D9EAFB 100%)',
        textColor: '#14213D', subColor: '#4A5A78',
        // Crescent moon with sparkle stars
        iconSvg: `
            <defs>
                <linearGradient id="heroMoonGrad" x1="4" y1="4" x2="20" y2="20" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#C9D4F5"/>
                    <stop offset="100%" stop-color="#5B6AA0"/>
                </linearGradient>
                <mask id="heroMoonMaskSm"><rect width="24" height="24" fill="#fff"/><circle cx="15.6" cy="9" r="5.7" fill="#000"/></mask>
            </defs>
            <circle cx="12" cy="12" r="6.3" fill="url(#heroMoonGrad)" mask="url(#heroMoonMaskSm)"/>
            <path d="M19,2.3 C19.3,4 19.9,4.6 21.6,4.9 C19.9,5.2 19.3,5.8 19,7.5 C18.7,5.8 18.1,5.2 16.4,4.9 C18.1,4.6 18.7,4 19,2.3 Z" fill="#F0C94C"/>
            <circle cx="6.3" cy="6.2" r="0.9" fill="#F0C94C" opacity="0.85"/>
            <circle cx="9" cy="3.8" r="0.6" fill="#F0C94C" opacity="0.6"/>
        `,
    },
};

function heroPeriodFor(hour) {
    if (hour >= 6 && hour < 12) return 'morning';
    if (hour >= 12 && hour < 17) return 'afternoon';
    if (hour >= 17 && hour < 21) return 'evening';
    return 'night';
}

function updateHeroGreeting() {
    const el = document.getElementById('heroGreeting');
    if (!el) return;

    const theme = HERO_THEMES[heroPeriodFor(new Date().getHours())];

    el.style.background = theme.bg;
    el.style.setProperty('--hero-text', theme.textColor);
    el.style.setProperty('--hero-sub', theme.subColor);

    document.getElementById('heroGreetingText').textContent = theme.greeting;

    document.getElementById('heroIcon').innerHTML = theme.iconSvg;
    document.getElementById('heroIconBadge').style.color = theme.iconColor;
}

document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') updateHeroGreeting();
});

// ─── Scroll/loader-triggered reveal ───
// Each chart card lazily builds (and plays its own draw-in animation)
// the first time it actually becomes visible — whether that's right
// when the loader clears (above the fold) or later when the user
// scrolls down to it.
const CHART_INITIALIZERS = {
    sales:        () => buildSalesChart(SALES_INIT),
    invoice:      () => buildInvoiceChart(INVOICE_INIT),
    topItems:     () => buildTopItemsList(TOP_ITEMS_INIT),
    paymentTrend: () => buildPaymentTrendChart(PAYMENT_TREND_INIT),
};

function initDashboardReveals() {
    const targets = document.querySelectorAll('.reveal');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const reveal = (el) => {
        el.classList.add('is-visible');

        const chartKey = el.dataset.chart;
        if (chartKey && CHART_INITIALIZERS[chartKey]) {
            CHART_INITIALIZERS[chartKey]();
        }

        const countupEl = el.querySelector('.metric-value[data-countup]');
        if (countupEl) animateCountUp(countupEl);
    };

    if (reduceMotion || !('IntersectionObserver' in window)) {
        targets.forEach(reveal);
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            reveal(entry.target);
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    targets.forEach(el => observer.observe(el));
}

// ─── Init ───
document.addEventListener('DOMContentLoaded', () => {
    updateHeroGreeting();

    if (document.getElementById('pageLoader')) {
        window.addEventListener('page-loader-hidden', initDashboardReveals, { once: true });
    } else {
        initDashboardReveals();
    }
});
</script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/js/toastr.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>

</body>
</html>
@endsection