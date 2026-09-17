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
           PAGE HEADER + PROFILE PILL
        ══════════════════════════════════════════════════ */
        .dash-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }
        /* Left group: toggle + greeting */
        .dash-topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
            min-width: 0;
        }
        .dash-greeting h2 {
            font-size: 22px; font-weight: 800;
            color: var(--clr-text-h); margin: 0;
        }
        .dash-greeting p {
            font-size: 13px; color: var(--clr-text-m);
            margin: 4px 0 0;
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
           STAT CARDS
        ══════════════════════════════════════════════════ */
        .stat-card {
            background: var(--clr-surface);
            border: 1px solid var(--clr-border);
            border-radius: var(--radius-lg);
            padding: 22px 22px 18px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow .25s, transform .25s;
            height: 100%;
        }
        .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
        .stat-card-top {
            display: flex; align-items: flex-start;
            justify-content: space-between; gap: 12px;
            margin-bottom: 16px;
        }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 20px;
        }
        .stat-icon.sales     { background: var(--clr-indigo-lt); color: var(--clr-indigo); }
        .stat-icon.purchases { background: var(--clr-sky-lt);    color: var(--clr-sky); }
        .stat-icon.customers { background: var(--clr-emerald-lt);color: var(--clr-emerald); }
        .stat-label { font-size: 12px; font-weight: 600; color: var(--clr-text-m); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 4px; }
        .stat-value { font-size: 26px; font-weight: 800; color: var(--clr-text-h); line-height: 1; }
        .stat-sub   { font-size: 12px; color: var(--clr-text-m); margin-top: 4px; }
        .stat-badge {
            display: inline-flex; align-items: center; gap: 3px;
            font-size: 11px; font-weight: 700;
            padding: 4px 10px; border-radius: 20px;
        }
        .stat-badge.up   { background: var(--clr-emerald-lt); color: var(--clr-emerald); }
        .stat-badge.neutral { background: var(--clr-indigo-lt); color: var(--clr-indigo); }
        .stat-progress {
            height: 5px; border-radius: 10px;
            background: var(--clr-border); overflow: hidden; margin: 14px 0 16px;
        }
        .stat-progress-fill {
            height: 100%; border-radius: 10px;
            transition: width .6s ease;
        }
        .fill-indigo  { background: linear-gradient(90deg, var(--clr-indigo), var(--clr-violet)); }
        .fill-sky     { background: linear-gradient(90deg, var(--clr-sky), #0EA5E9); }
        .fill-emerald { background: linear-gradient(90deg, var(--clr-emerald), #10B981); }
        .stat-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .btn-stat {
            font-size: 11px; font-weight: 700;
            padding: 7px 14px; border-radius: 8px;
            text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
            transition: all .2s; border: none; cursor: pointer;
        }
        .btn-stat.primary   { background: var(--clr-indigo); color: #fff; }
        .btn-stat.primary:hover { background: #0F68E0; }
        .btn-stat.outline   { background: transparent; color: var(--clr-indigo); border: 1.5px solid var(--clr-indigo); }
        .btn-stat.outline:hover { background: var(--clr-indigo-lt); }
        .btn-stat.sky-fill  { background: var(--clr-sky); color: #fff; }
        .btn-stat.sky-fill:hover { background: #0369A1; }
        .btn-stat.emerald-fill { background: var(--clr-emerald); color: #fff; }
        .btn-stat.emerald-fill:hover { background: #128A3E; }

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
            background: var(--clr-surface); color: var(--clr-text-h);
            box-shadow: 0 1px 4px rgba(15,23,42,.10);
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
           MULTI-LINE SUMMARY BAR
        ══════════════════════════════════════════════════ */
        .ml-summary-bar {
            display: flex; gap: 28px; flex-wrap: wrap;
            padding: 18px 22px 0;
            border-bottom: 1px solid var(--clr-border);
            margin-bottom: 0;
            padding-bottom: 18px;
        }
        .ml-metric { display: flex; flex-direction: column; gap: 3px; }
        .ml-metric-name {
            font-size: 11px; font-weight: 700;
            color: var(--clr-text-m); text-transform: uppercase; letter-spacing: .05em;
            display: flex; align-items: center; gap: 6px;
        }
        .ml-metric-name::before {
            content: ''; display: inline-block;
            width: 8px; height: 8px; border-radius: 50%;
        }
        .ml-metric-name.sales::before    { background: var(--chart-1); }
        .ml-metric-name.purchases::before{ background: var(--chart-2); }
        .ml-metric-name.stock::before    { background: var(--chart-3); }
        .ml-metric-value { font-size: 22px; font-weight: 800; color: var(--clr-text-h); }
        .ml-pct {
            display: inline-flex; align-items: center; gap: 3px;
            font-size: 11px; font-weight: 700;
            padding: 2px 8px; border-radius: 20px;
        }
        .ml-pct.up   { background: var(--clr-emerald-lt); color: var(--clr-emerald); }
        .ml-pct.down { background: var(--clr-rose-lt);    color: var(--clr-rose); }

        /* ══════════════════════════════════════════════════
           INVOICE LEGEND COUNTS
        ══════════════════════════════════════════════════ */
        .inv-legend {
            display: flex; gap: 0;
            border-top: 1px solid var(--clr-border);
            margin-top: 12px;
        }
        .inv-legend-item {
            flex: 1; text-align: center;
            padding: 12px 8px;
            border-right: 1px solid var(--clr-border);
        }
        .inv-legend-item:last-child { border-right: none; }
        .inv-legend-dot {
            width: 8px; height: 8px; border-radius: 50%;
            display: inline-block; margin-right: 5px;
        }
        .inv-legend-label { font-size: 11px; font-weight: 600; color: var(--clr-text-m); }
        .inv-legend-val   { font-size: 20px; font-weight: 800; color: var(--clr-text-h); margin-top: 2px; }

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
        ══════════════════════════════════════════════════ */
        @media (prefers-reduced-motion: no-preference) {
            @keyframes dashFadeUp {
                from { opacity: 0; transform: translateY(14px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            .dash-greeting, .sa-card, .stat-card, .chart-card, .pay-card, .inv-table-card {
                animation: dashFadeUp .5s cubic-bezier(.16,1,.3,1) both;
            }
            .dash-greeting { animation-delay: 0s; }
            .sa-card:nth-of-type(1) { animation-delay: .03s; }
            .sa-card:nth-of-type(2) { animation-delay: .07s; }
            .row.mb-24 > *:nth-child(1) .stat-card { animation-delay: .06s; }
            .row.mb-24 > *:nth-child(2) .stat-card { animation-delay: .12s; }
            .row.mb-24 > *:nth-child(3) .stat-card { animation-delay: .18s; }
            .row-gap > *:nth-child(1) .chart-card,
            .row-gap > *:nth-child(1) .pay-card { animation-delay: .1s; }
            .row-gap > *:nth-child(2) .chart-card,
            .row-gap > *:nth-child(2) .pay-card { animation-delay: .16s; }
            .inv-table-card { animation-delay: .2s; }

            /* Count-up numbers land instantly under the fade, then tick up */
            .stat-value[data-countup] { font-variant-numeric: tabular-nums; }
        }
        @media (prefers-reduced-motion: reduce) {
            .chart-loading, .sa-panel, .sa-card, .stat-card { transition: none !important; animation: none !important; }
        }

        /* Snappier, consistent hover feedback across interactive surfaces */
        .pro-table tbody td { transition: background .15s ease; }
        .chip, .stat-badge, .stock-chip { transition: transform .15s ease; }
        .chip:hover, .stat-badge:hover { transform: translateY(-1px); }
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

            {{-- ─── TOP BAR (Toggle + Greeting + Profile) ─── --}}
            <div class="dash-topbar mb-24">

                {{-- LEFT: greeting (sidebar toggle now lives once, in the shared navbar) --}}
                <div class="dash-topbar-left">
                    <div class="dash-greeting">
                        @php
                            $now  = \Carbon\Carbon::now('Asia/Colombo');
                            $hour = $now->hour;
                        @endphp
                        <h2>
                            Good {{ $hour < 12 ? 'Morning' : ($hour < 17 ? 'Afternoon' : 'Evening') }} 👋
                        </h2>
                        <p>
                            Here's what's happening with your business today &nbsp;·&nbsp;
                            <strong>{{ $now->format('D, d M Y') }}</strong>
                        </p>
                    </div>
                </div>
            </div>

            {{-- ─── STOCK ALERT BANNERS ─── --}}
            @php
                $oversoldItems = $itemDetails->filter(fn($i) => $i->QTY < 0);
                $lowStockItems = $itemDetails->filter(fn($i) => $i->QTY >= 0 && $i->QTY <= $i->ReorderLevel);
            @endphp

            @if($oversoldItems->count())
            <div class="sa-card mb-20" id="sa-oversold">
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
            <div class="sa-card mb-20" id="sa-lowstock">
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

            {{-- ─── STAT CARDS ─── --}}
            <div class="row mb-24" style="margin-bottom:20px;">
                {{-- Sales --}}
                <div class="col-xl-4 col-sm-6 col-12 mb-3">
                    <div class="stat-card">
                        <div class="stat-card-top">
                            <div>
                                <div class="stat-label">Total Sales</div>
                                <div class="stat-value" data-countup data-value="{{ $salessum }}" data-decimals="2">0.00</div>
                                <div class="stat-sub">Sales Invoices: <strong>{{ $Invoice }}</strong></div>
                            </div>
                            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                                <div class="stat-icon sales"><i class="fas fa-chart-line"></i></div>
                                <span class="stat-badge up"><i class="fas fa-arrow-up" style="font-size:9px;"></i>Active</span>
                            </div>
                        </div>
                        <div class="stat-progress">
                            <div class="stat-progress-fill fill-indigo" style="width:{{ min($Invoice,100) }}%"></div>
                        </div>
                        <div class="stat-actions">
                            <a class="btn-stat primary" target="_blank" href="{{ route('salesInvoice_withoutVat') }}">
                                <i class="fas fa-plus-circle"></i> Create Invoice
                            </a>
                            <a class="btn-stat outline" target="_blank" href="{{ route('sales_report') }}">
                                <i class="fas fa-chart-bar"></i> Report
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Purchases --}}
                <div class="col-xl-4 col-sm-6 col-12 mb-3">
                    <div class="stat-card">
                        <div class="stat-card-top">
                            <div>
                                <div class="stat-label">Total Purchases</div>
                                <div class="stat-value" data-countup data-value="{{ $PurchasesSum }}" data-decimals="2">0.00</div>
                                <div class="stat-sub">Purchase Orders: <strong>{{ $Purchases }}</strong></div>
                            </div>
                            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                                <div class="stat-icon purchases"><i class="fas fa-shopping-cart"></i></div>
                                <span class="stat-badge neutral"><i class="fas fa-minus" style="font-size:9px;"></i>Stable</span>
                            </div>
                        </div>
                        <div class="stat-progress">
                            <div class="stat-progress-fill fill-sky" style="width:{{ min($Purchases,100) }}%"></div>
                        </div>
                        <div class="stat-actions">
                            <a class="btn-stat sky-fill" target="_blank" href="{{ route('purchases') }}">
                                <i class="fas fa-plus-circle"></i> Create Purchases
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Customers --}}
                <div class="col-xl-4 col-sm-6 col-12 mb-3">
                    <div class="stat-card">
                        <div class="stat-card-top">
                            <div>
                                <div class="stat-label">Customers</div>
                                <div class="stat-value" data-countup data-value="{{ $customerdetails }}" data-decimals="0">0</div>
                                <div class="stat-sub">Qty Movement: <strong>{{ $QtyOut - $QtyIn }}</strong></div>
                            </div>
                            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                                <div class="stat-icon customers"><i class="fas fa-users"></i></div>
                                <span class="stat-badge up"><i class="fas fa-arrow-up" style="font-size:9px;"></i>Growing</span>
                            </div>
                        </div>
                        <div class="stat-progress">
                            <div class="stat-progress-fill fill-emerald" style="width:{{ min(abs($QtyOut-$QtyIn),100) }}%"></div>
                        </div>
                        <div class="stat-actions">
                            <a class="btn-stat emerald-fill" target="_blank" href="{{ route('master_customers') }}">
                                <i class="fas fa-user-plus"></i> Create Customers
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── CHARTS ROW ─── --}}
            <div class="row mb-24" style="margin-bottom:20px;">
                {{-- Sales & Repair Bar Chart --}}
                <div class="col-xl-7 d-flex mb-3">
                    <div class="chart-card" style="width:100%;">
                        <div class="chart-card-header">
                            <h5 class="chart-card-title">Sales &amp; Repair Analytics</h5>
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
                    <div class="chart-card" style="width:100%;">
                        <div class="chart-card-header">
                            <h5 class="chart-card-title">Invoice Analytics</h5>
                            <div class="period-tabs" id="invoiceTabs">
                                <button class="period-tab" onclick="changePeriod('invoice','daily',this)">Daily</button>
                                <button class="period-tab" onclick="changePeriod('invoice','weekly',this)">Weekly</button>
                                <button class="period-tab active" onclick="changePeriod('invoice','monthly',this)">Monthly</button>
                                <button class="period-tab" onclick="changePeriod('invoice','yearly',this)">Yearly</button>
                            </div>
                        </div>
                        <div class="chart-card-body chart-wrap" style="padding-bottom:0;">
                            <div class="chart-loading hidden" id="invoice-loading">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                            <div id="invoice_chart"></div>
                        </div>
                        <div class="inv-legend" id="invoice-totals">
                            <div class="inv-legend-item">
                                <span class="inv-legend-dot" style="background:#4F46E5;"></span>
                                <span class="inv-legend-label">Sales</span>
                                <div class="inv-legend-val" id="inv-sales-count">{{ $Invoice }}</div>
                            </div>
                            <div class="inv-legend-item">
                                <span class="inv-legend-dot" style="background:#059669;"></span>
                                <span class="inv-legend-label">Purchases</span>
                                <div class="inv-legend-val" id="inv-purch-count">{{ $Purchases }}</div>
                            </div>
                            <div class="inv-legend-item">
                                <span class="inv-legend-dot" style="background:#D97706;"></span>
                                <span class="inv-legend-label">Stock</span>
                                <div class="inv-legend-val" id="inv-stock-count">{{ $QtyIn }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── MULTI-LINE ANALYTICS ─── --}}
            <div class="mb-24" style="margin-bottom:20px;">
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h5 class="chart-card-title">
                            <i class="fas fa-chart-area me-2" style="color:var(--clr-indigo);"></i>
                            Multi-Channel Analytics
                        </h5>
                        <div class="period-tabs" id="mlTabs">
                            <button class="period-tab" onclick="changePeriod('ml','daily',this)">Daily</button>
                            <button class="period-tab active" onclick="changePeriod('ml','weekly',this)">Weekly</button>
                            <button class="period-tab" onclick="changePeriod('ml','monthly',this)">Monthly</button>
                            <button class="period-tab" onclick="changePeriod('ml','yearly',this)">Yearly</button>
                        </div>
                    </div>
                    <div class="ml-summary-bar" id="ml-summary">
                        @foreach($multiLineData['summary'] as $idx => $s)
                        @php $classes = ['sales','purchases','stock']; $cls = $classes[$idx] ?? 'sales'; @endphp
                        <div class="ml-metric">
                            <span class="ml-metric-name {{ $cls }}">{{ $s['label'] }}</span>
                            <span class="ml-metric-value">{{ number_format($s['value']) }}</span>
                            <span class="ml-pct {{ $s['pct'] >= 0 ? 'up' : 'down' }}">
                                {{ $s['pct'] >= 0 ? '↑' : '↓' }} {{ abs($s['pct']) }}%
                            </span>
                        </div>
                        @endforeach
                    </div>
                    <div class="chart-card-body chart-wrap pt-2">
                        <div class="chart-loading hidden" id="ml-loading">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                        <div id="ml_chart"></div>
                    </div>
                </div>
            </div>

            {{-- ─── PAYMENT TABLES ─── --}}
            <div class="row mb-24" style="margin-bottom:20px;">
                <div class="col-md-6 col-sm-6 mb-3">
                    <div class="pay-card">
                        <div class="pay-card-header">
                            <h5 class="pay-card-title"><i class="fas fa-money-bill-wave me-2" style="color:var(--clr-emerald);"></i>Cash Payment</h5>
                            <a href="#" class="btn-stat outline" style="font-size:11px;padding:5px 12px;">View All</a>
                        </div>
                        <div class="pay-progress-bar">
                            <div style="width:56%;background:var(--clr-emerald);border-radius:10px 0 0 10px;"></div>
                            <div style="width:10%;background:var(--clr-amber);"></div>
                            <div style="width:14%;background:var(--clr-rose);"></div>
                            <div style="width:20%;background:var(--clr-sky);border-radius:0 10px 10px 0;"></div>
                        </div>
                        <div class="pay-legend">
                            <span><i class="fas fa-circle" style="color:var(--clr-emerald);font-size:8px;"></i>Paid</span>
                            <span><i class="fas fa-circle" style="color:var(--clr-amber);font-size:8px;"></i>Unpaid</span>
                            <span><i class="fas fa-circle" style="color:var(--clr-rose);font-size:8px;"></i>Overdue</span>
                            <span><i class="fas fa-circle" style="color:var(--clr-sky);font-size:8px;"></i>Draft</span>
                        </div>
                        <div class="table-responsive">
                            <table class="pro-table">
                                <thead><tr>
                                    <th>Receipt No</th><th>Customer</th><th>Date</th>
                                    <th>Amount</th><th>Action</th>
                                </tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 mb-3">
                    <div class="pay-card">
                        <div class="pay-card-header">
                            <h5 class="pay-card-title"><i class="fas fa-receipt me-2" style="color:var(--clr-indigo);"></i>Sales Payment</h5>
                            <a href="#" class="btn-stat outline" style="font-size:11px;padding:5px 12px;">View All</a>
                        </div>
                        <div class="pay-progress-bar">
                            <div style="width:39%;background:var(--clr-emerald);border-radius:10px 0 0 10px;"></div>
                            <div style="width:35%;background:var(--clr-rose);"></div>
                            <div style="width:26%;background:var(--clr-amber);border-radius:0 10px 10px 0;"></div>
                        </div>
                        <div class="pay-legend">
                            <span><i class="fas fa-circle" style="color:var(--clr-emerald);font-size:8px;"></i>Sent</span>
                            <span><i class="fas fa-circle" style="color:var(--clr-amber);font-size:8px;"></i>Draft</span>
                            <span><i class="fas fa-circle" style="color:var(--clr-rose);font-size:8px;"></i>Expired</span>
                        </div>
                        <div class="table-responsive">
                            <table class="pro-table">
                                <thead><tr>
                                    <th>Receipt No</th><th>Date</th><th>Pawn Amt</th>
                                    <th>Payable Total</th><th>Action</th>
                                </tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── INVENTORY TABLE ─── --}}
            <div class="mb-24">
                <div class="inv-table-card">
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

        </div>{{-- /content --}}
    </div>
</div>

{{-- ════ Scripts ════ --}}
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/apexchart/apexcharts.min.js"></script>

<script>
// ─── Server-rendered initial data ───
const SALES_INIT   = @json($salesChartData);
const INVOICE_INIT = @json($invoiceChartData);
const ML_INIT      = @json($multiLineData);
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ─── Chart palette ───
const PALETTE = ['#1677FF','#16A34A','#F59E0B','#EF4444','#0284C7'];

let salesChart, invoiceChart, mlChart;

// ─── 1. Sales & Repair BAR chart ───
function buildSalesChart(data) {
    const opts = {
        chart: {
            type: 'bar', height: 290, toolbar: { show: false },
            fontFamily: "'Plus Jakarta Sans', sans-serif",
            animations: { enabled: true, speed: 500 },
            background: 'transparent',
        },
        series: [
            { name: 'Received', data: data.received },
            { name: 'Pending',  data: data.pending  },
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
        colors: [PALETTE[0], PALETTE[2]],
        plotOptions: {
            bar: {
                borderRadius: 8,
                columnWidth: '50%',
                dataLabels: { position: 'top' }
            }
        },
        dataLabels: { enabled: false },
        legend: {
            position: 'bottom', fontSize: '12px', fontWeight: 600,
            markers: { radius: 6 },
            labels: { colors: '#64748B' }
        },
        grid: { borderColor: '#E5EAF2', strokeDashArray: 4, xaxis: { lines: { show: false } } },
        tooltip: {
            theme: 'light',
            y: { formatter: v => Number(v).toLocaleString() },
            style: { fontSize: '12px', fontFamily: "'Plus Jakarta Sans', sans-serif" }
        },
        fill: { type: 'gradient', gradient: { shade: 'light', type: 'vertical', shadeIntensity: 0.15, opacityFrom: 1, opacityTo: 0.85 } },
    };

    if (salesChart) { salesChart.updateOptions(opts); return; }
    salesChart = new ApexCharts(document.querySelector('#sales_chart'), opts);
    salesChart.render();
}

// ─── 2. Invoice DONUT chart ───
function buildInvoiceChart(data) {
    const opts = {
        chart: {
            type: 'donut', height: 230,
            fontFamily: "'Plus Jakarta Sans', sans-serif",
            animations: { enabled: true, speed: 500 },
        },
        series: data.series,
        labels: data.labels,
        colors: [PALETTE[0], PALETTE[1], PALETTE[2], PALETTE[3]],
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true, label: 'Total',
                            fontSize: '12px', fontWeight: 700, color: '#64748B',
                            formatter: w => w.globals.seriesTotals.reduce((a,b) => a+b, 0)
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        legend: { show: false },
        stroke: { width: 2, colors: ['#fff'] },
        tooltip: {
            theme: 'light',
            y: { formatter: v => v + '%' },
            style: { fontSize: '12px', fontFamily: "'Plus Jakarta Sans', sans-serif" }
        },
    };

    if (invoiceChart) { invoiceChart.updateOptions(opts); return; }
    invoiceChart = new ApexCharts(document.querySelector('#invoice_chart'), opts);
    invoiceChart.render();

    document.getElementById('inv-sales-count').textContent = data.totals.sales;
    document.getElementById('inv-purch-count').textContent = data.totals.purchases;
    document.getElementById('inv-stock-count').textContent = data.totals.stock;
}

// ─── 3. Multi-line chart ───
function buildMlChart(data) {
    const opts = {
        chart: {
            type: 'area', height: 310, toolbar: { show: false },
            fontFamily: "'Plus Jakarta Sans', sans-serif",
            animations: { enabled: true, speed: 600 },
            background: 'transparent',
        },
        series: data.series.map(s => ({ name: s.name, data: s.data })),
        xaxis: {
            categories: data.categories,
            labels: { style: { fontSize: '11px', fontWeight: 600, colors: '#94A3B8' } },
            axisBorder: { show: false }, axisTicks: { show: false },
        },
        yaxis: {
            labels: {
                formatter: v => Number(v).toLocaleString(),
                style: { fontSize: '11px', colors: '#94A3B8' }
            }
        },
        colors: data.series.map((s, i) => s.color ?? PALETTE[i] ?? PALETTE[0]),
        stroke: { curve: 'smooth', width: 3 },
        markers: { size: 4, hover: { size: 6 }, strokeWidth: 2, strokeColors: '#fff' },
        legend: {
            position: 'bottom', fontSize: '12px', fontWeight: 600,
            markers: { radius: 6 }, labels: { colors: '#64748B' }
        },
        grid: { borderColor: '#E5EAF2', strokeDashArray: 4, xaxis: { lines: { show: false } } },
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.18, opacityTo: 0.01, stops: [0, 90] },
        },
        tooltip: {
            shared: true, intersect: false, theme: 'light',
            y: { formatter: v => Number(v).toLocaleString() },
            style: { fontSize: '12px', fontFamily: "'Plus Jakarta Sans', sans-serif" }
        },
    };

    if (mlChart) {
        mlChart.updateOptions({ series: opts.series, xaxis: opts.xaxis, colors: opts.colors });
        return;
    }
    mlChart = new ApexCharts(document.querySelector('#ml_chart'), opts);
    mlChart.render();
}

// ─── Period switcher ───
const tabGroups = { sales: 'salesTabs', invoice: 'invoiceTabs', ml: 'mlTabs' };

function changePeriod(chart, period, btn) {
    document.querySelectorAll(`#${tabGroups[chart]} .period-tab`)
        .forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const loaderId = chart === 'ml' ? 'ml-loading' : chart + '-loading';
    const loader = document.getElementById(loaderId);
    if (loader) loader.classList.remove('hidden');

    const urls = {
        sales:   '{{ route("dashboard.salesChartData") }}',
        invoice: '{{ route("dashboard.invoiceChartData") }}',
        ml:      '{{ route("dashboard.multiLineData") }}',
    };

    fetch(`${urls[chart]}?period=${period}`, {
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (chart === 'sales')   buildSalesChart(data);
        if (chart === 'invoice') {
            buildInvoiceChart(data);
            document.getElementById('inv-sales-count').textContent = data.totals.sales;
            document.getElementById('inv-purch-count').textContent = data.totals.purchases;
            document.getElementById('inv-stock-count').textContent = data.totals.stock;
        }
        if (chart === 'ml') { buildMlChart(data); renderMlSummary(data.summary); }
    })
    .catch(console.error)
    .finally(() => { if (loader) loader.classList.add('hidden'); });
}

function renderMlSummary(summary) {
    const names = ['sales','purchases','stock'];
    document.getElementById('ml-summary').innerHTML = summary.map((s, i) => `
        <div class="ml-metric">
            <span class="ml-metric-name ${names[i]}">${s.label}</span>
            <span class="ml-metric-value">${Number(s.value).toLocaleString()}</span>
            <span class="ml-pct ${s.pct >= 0 ? 'up' : 'down'}">
                ${s.pct >= 0 ? '↑' : '↓'} ${Math.abs(s.pct)}%
            </span>
        </div>
    `).join('');
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

// ─── Init ───
document.addEventListener('DOMContentLoaded', () => {
    buildSalesChart(SALES_INIT);
    buildInvoiceChart(INVOICE_INIT);
    buildMlChart(ML_INIT);
    renderMlSummary(ML_INIT.summary);
    document.querySelectorAll('.stat-value[data-countup]').forEach(animateCountUp);
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